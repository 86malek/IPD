$(document).ready(function(){
  
  var table_cat_fichier = $('#table_auto_fiche').dataTable({
	  "bStateSave": true,
        "fnStateSave": function (oSettings, oData) {
            localStorage.setItem( 'DataTables_'+window.location.pathname, JSON.stringify(oData) );
        },
        "fnStateLoad": function (oSettings) {
            return JSON.parse( localStorage.getItem('DataTables_'+window.location.pathname) );
        },
    "ajax": "table/data_traitement_auto.php?job=get_fiche_auto",
    "columns": [
      { "data": "collab",   "sClass": "nom" },
	  { "data": "fiche",   "sClass": "nom" },
	  { "data": "statut",   "sClass": "nom" },
	  { "data": "date",   "sClass": "nom" },
      { "data": "functions",      "sClass": "functions" }
    ],
	 dom: 'Bfrtip',
	"buttons": [
             'colvis'
        ],
    "aoColumnDefs": [
      { "bSortable": false, "aTargets": [-1] }
    ],
    "lengthMenu": [[5, 10, 30, 50, -1], [5, 10, 30, 50, "All"]],
    "oLanguage": {
      "oPaginate": {
        "sFirst":       "<< ",
        "sPrevious":    "Précédant",
        "sNext":        "Suivant",
        "sLast":        ">>",
      },
      "sLengthMenu":    "fiches par page : _MENU_",
      "sInfo":          "Total de _TOTAL_ fiches (Affichage _START_ à _END_)",
	  "sSearch":          "Recherche",
      "sInfoFiltered":  "(Filtré depuis _MAX_ total fiches)",
	  "sLoadingRecords": "Chargement en cours..."
    }
  });
  
  jQuery.validator.setDefaults({
    success: 'valid',
    rules: {
      fiscal_year: {
        required: true,
        min:      2000,
        max:      2025
      }
    },
    errorPlacement: function(error, element){
      error.insertBefore(element);
    },
    highlight: function(element){
      $(element).parent('.field_container').removeClass('valid').addClass('error');
    },
    unhighlight: function(element){
      $(element).parent('.field_container').addClass('valid').removeClass('error');
    }
  });
  var form_company = $('#form_auto_fiche ');
  form_company.validate();

  function show_message(message_text, message_type){
    $('#message').html('<p>' + message_text + '</p>').attr('class', message_type);
    $('#message_container').show();
    if (typeof timeout_message !== 'undefined'){
      window.clearTimeout(timeout_message);
    }
    timeout_message = setTimeout(function(){
      hide_message();
    }, 8000);
  }
  function hide_message(){
    $('#message').html('').attr('class', '');
    $('#message_container').hide();
  }

  function show_loading_message(){
    $('#loading_container').show();
  }
  function hide_loading_message(){
    $('#loading_container').hide();
  }

  function show_lightbox(){
    $('.lightbox_bg').show();
    $('.lightbox_container').show();
  }
  function hide_lightbox(){
    $('.lightbox_bg').hide();
    $('.lightbox_container').hide();
  }
  $(document).on('click', '.lightbox_bg', function(){
    hide_lightbox();
  });
  $(document).on('click', '.lightbox_close', function(){
    hide_lightbox();
  });
  $(document).keyup(function(e){
    if (e.keyCode == 27){
      hide_lightbox();
    }
  });
  
  function hide_ipad_keyboard(){
    document.activeElement.blur();
    $('input').blur();
  }

  $(document).on('click', '#add_auto_fiche', function(e){
    e.preventDefault();
    $('.lightbox_container h2').text('Ajouter une fiche AUTOMOBILE');
    $('#form_auto_fiche button').text('Enregistrement');
    $('#form_auto_fiche').attr('class', 'form add');
    $('#form_auto_fiche').attr('data-id', '');
    $('#form_auto_fiche .field_container label.error').hide();
    $('#form_auto_fiche .field_container').removeClass('valid').removeClass('error');
    $('#form_auto_fiche #fiche').val('');
	$('#form_auto_fiche #statut').val('');
    show_lightbox();
  });

  $(document).on('submit', '#form_auto_fiche.add', function(e){
    e.preventDefault();
    if (form_company.valid() == true){
      hide_ipad_keyboard();
      hide_lightbox();
      show_loading_message();
      var form_data = $('#form_auto_fiche').serialize();
      var request   = $.ajax({
        url:          'table/data_traitement_auto.php?job=add_fiche_auto',
        cache:        false,
        data:         form_data,
        dataType:     'json',
        contentType:  'application/json; charset=utf-8',
        type:         'get'
      });
      request.done(function(output){
        if (output.result == 'success'){
          table_cat_fichier.api().ajax.reload(function(){
            hide_loading_message();
            var cat_name = $('#fiche').val();
            show_message("Demandeur '" + cat_name + "' ajouté avec succés.", 'success');
          }, true);
        } else {
          hide_loading_message();
          show_message('Échec comunication base de données SQL', 'error');
        }
      });
      request.fail(function(jqXHR, textStatus){
        hide_loading_message();
        show_message('Échec comunication base de données SQL' + textStatus, 'error');
      });
    }
  });

  $(document).on('click', '#function_edit_fiche', function(e){
    e.preventDefault();
    show_loading_message();
    var id      = $(this).data('id');
    var request = $.ajax({
      url:          'table/data_traitement_auto.php?job=get_fiche_auto_add',
      cache:        false,
      data:         'id=' + id,
      dataType:     'json',
      contentType:  'application/json; charset=utf-8',
      type:         'get'
    });
    request.done(function(output){
      if (output.result == 'success'){
        $('.lightbox_content h2').text('Formulaire Modification du demandeur');
        $('#form_auto_fiche button').text('Enregistrement');
        $('#form_auto_fiche').attr('class', 'form edit');
        $('#form_auto_fiche').attr('data-id', id);
        $('#form_auto_fiche .field_container label.error').hide();
        $('#form_auto_fiche .field_container').removeClass('valid').removeClass('error');
        $('#form_auto_fiche #fiche').val(output.data[0].fiche);
		
		$("select option").filter(function() {
			return $(this).val() == output.data[0].statut; 
		}).prop('selected', true);


        hide_loading_message();
        show_lightbox();
      } else {
        hide_loading_message();
        show_message('Échec comunication base de données SQL', 'error');
      }
    });
    request.fail(function(jqXHR, textStatus){
      hide_loading_message();
      show_message('Échec comunication base de données SQL ' + textStatus, 'error');
    });
  });
  
  $(document).on('submit', '#form_auto_fiche.edit', function(e){
    e.preventDefault();
    if (form_company.valid() == true){
      hide_ipad_keyboard();
      hide_lightbox();
      show_loading_message();
      var id        = $('#form_auto_fiche').attr('data-id');
      var form_data = $('#form_auto_fiche').serialize();
      var request   = $.ajax({
        url:          'table/data_traitement_auto.php?job=edit_fiche_auto&id=' + id,
        cache:        false,
        data:         form_data,
        dataType:     'json',
        contentType:  'application/json; charset=utf-8',
        type:         'get'
      });
      request.done(function(output){
        if (output.result == 'success'){
          table_cat_fichier.api().ajax.reload(function(){
            hide_loading_message();
            var cat_name = $('#nom').val();
            show_message("Demandeur '" + cat_name + "' modifié avec succés.", 'success');
          }, true);
        } else {
          hide_loading_message();
          show_message('Échec comunication base de données SQL', 'error');
        }
      });
      request.fail(function(jqXHR, textStatus){
        hide_loading_message();
        show_message('Échec comunication base de données SQL' + textStatus, 'error');
      });
    }
  });
  
  $(document).on('click', '#del', function(e){
    e.preventDefault();
    var cat_name = $(this).data('fiche');
    if (confirm("Confirmation de supprission du '" + cat_name + "' !!")){
      show_loading_message();
      var id      = $(this).data('id');
      var request = $.ajax({
        url:          'table/data_traitement_auto.php?job=delete_fiche_auto&id=' + id,
        cache:        false,
        dataType:     'json',
        contentType:  'application/json; charset=utf-8',
        type:         'get'
      });
      request.done(function(output){
        if (output.result == 'success'){
          table_cat_fichier.api().ajax.reload(function(){
            hide_loading_message();
            show_message("Demandeur '" + cat_name + "' effacée avec succès.", 'success');
          }, true);
        } else {
          hide_loading_message();
          show_message('Échec comunication base de données SQL', 'error');
        }
      });
      request.fail(function(jqXHR, textStatus){
        hide_loading_message();
        show_message('Échec comunication base de données SQL ' + textStatus, 'error');
      });
    }
  });

});