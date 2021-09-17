! function(t) {
    "use strict";
$(document).ready(function(){
  var id_import = $('#table_traitement_nomination').attr('data-id');
  var mode_import = $('#table_traitement_nomination').attr('data-mode');
  var table_companies = $('#table_traitement_nomination').dataTable({
    "ajax": "module/nomination/table/php/data_acide_traitement_nomination.php?job=get_traitement_nomination_admin&id_import=" + id_import + "&mode=" + mode_import,
    "columns": [
		{ "data": "alerte",      "sClass": "" },
	  { "data": "collab", "sClass": "" },
      { "data": "date", "sClass": "" },
	  { "data": "publication", "sClass": "" },
      { "data": "rs", "sClass": "" },
	  { "data": "siret", "sClass": "" },
	  { "data": "title", "sClass": "" },
	  { "data": "nom", "sClass": "" },
	  { "data": "prenom", "sClass": "" },
	  { "data": "fe", "sClass": "" },	  
	  { "data": "ancienne", "sClass": "" },
	  { "data": "statut", "sClass": "" },
	  { "data": "type", "sClass": "" },
      { "data": "functions", "sClass": "" },
	  { "data": "mood",      "sClass": "" },
	  { "data": "temps",      "sClass": "" }
    ],
    dom: 'Bfrtip',
	"buttons": [
            'csv', {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: ':visible'
                }
            },{
                extend: 'print',
                exportOptions: {
                    columns: ':visible'
                }
            }, 'colvis'
        ],
    "aoColumnDefs": [
      { "bSortable": false, "aTargets": [-1] }
    ],
    "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
    "oLanguage": {
      "oPaginate": {
        "sFirst":       "<<",
        "sPrevious":    "Précédent",
        "sNext":        "Suivant",
        "sLast":        ">>",
      },
      "sLengthMenu":    "Fiches par page : _MENU_",
      "sInfo":          "Total de _TOTAL_ Fiches (Affichage _START_ à _END_)",
	  "sSearch":          "Recherche : ",
      "sInfoFiltered":  "(Filtré depuis _MAX_ total Fiches)",
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
  var form_company = $('#form_company_admin');
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
  /*$(document).on('click', '.lightbox_bg', function(){
    hide_lightbox();
  });*/
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

  $(document).on('click', '#mood_affichage', function(){
      var temps_reel      = $(this).data('id');
		t.dialog({
			title: "Nb de modifications",
			content: "url:module/nomination/table/data/mood-table.php?id_stat=" + temps_reel,
			animation: 'zoom',
			columnClass: 'medium',
			closeAnimation: 'scale',
			backgroundDismiss: false,
			closeIcon: true,
			draggable: false
		  });
    });

  


  $(document).on('click', '#add_nomination', function(e){
    e.preventDefault();		
		
      $('.lightbox_content h2').text('NOUVELLE FICHE NOMINATION (ADMIN)');
      $('#form_company_admin button').text('ENREGISTREMENT DE LA FICHE');
      $('#form_company_admin').attr('class', 'form add');
      $('#form_company_admin').attr('data-id', '');
      $('#form_company_admin .field_container label.error').hide();
      $('#form_company_admin .field_container').removeClass('valid').removeClass('error');
      $('#form_company_admin #publication').val('');
      $('#form_company_admin #rs').val('');
      $('#form_company_admin #siret').val('');
      $('#form_company_admin #title').val('');
      $('#form_company_admin #statut').val('');
      $('#form_company_admin #etat').val('');
	  $('#form_company_admin #comm').val('');
	  $("#form_company_admin #etat").change(function () {
                                                 
          var str = "";
          $("#form_company_admin #etat option:selected").each(function () {
                str += $(this).val();
              });
           
          if(str == 1 || str == ''){
                $('#form_company_admin #statut').attr('disabled', true);
				$('#form_company_admin #ancienne').attr('disabled', true);
          }
          else{
              $('#form_company_admin #statut').attr('disabled', false);$('#form_company_admin #ancienne').attr('disabled', false);
          }
        });	
      $('#form_company_admin #nom').val('');
      $('#form_company_admin #prenom').val('');
      $('#form_company_admin #fonction').val('');	
      $('#form_company_admin #ancienne').val('');
      $('#form_company_admin #user_id').val();
      $('#form_company_admin #user').val();
    show_lightbox();
  });

  $(document).on('submit', '#form_company_admin.add', function(e){
    e.preventDefault();
    if (form_company.valid() == true){
      hide_ipad_keyboard();
      hide_lightbox();
      show_loading_message();
	  
	  	
		
      var form_data = $('#form_company_admin').serialize();
      var request   = $.ajax({
        url:          'module/nomination/table/php/data_acide_traitement_nomination.php?job=add_traitement_nomination_admin',
        cache:        false,
        data:         form_data,
        dataType:     'json',
        contentType:  'application/json; charset=utf-8',
        type:         'get'
      });
      request.done(function(output){
        if (output.result == 'success'){
          table_companies.api().ajax.reload(function(){
            hide_loading_message();
            var nom = $('#nom').val();
            show_message("Fiche '" + nom + "' ajouter avec succés.", 'success');
          }, true);
        } else {
          hide_loading_message();
          show_message("Une erreur s'est produite lors de l'enregistrement", 'error');
        }
      });
      request.fail(function(jqXHR, textStatus){
        hide_loading_message();
        show_message("Une erreur s'est produite lors de l'enregistrement" + textStatus, 'error');
      });
    }
  });

  $(document).on('click', '#function_edit_web', function(e){
    e.preventDefault();
    show_loading_message();
    var id      = $(this).data('id');
    var request = $.ajax({
      url:          'module/nomination/table/php/data_acide_traitement_nomination.php?job=get_traitement_add_nomination_admin',
      cache:        false,
      data:         'id=' + id,
      dataType:     'json',
      contentType:  'application/json; charset=utf-8',
      type:         'get'
    });
    request.done(function(output){
      if (output.result == 'success'){
		
		
        $('.lightbox_content h2').text('MODICIATION FICHE NOMINATION (ADMIN)');
        $('#form_company_admin button').text('ENREGISTREMENT DE LA FICHE');
        $('#form_company_admin').attr('class', 'form edit');
        $('#form_company_admin').attr('data-id', id);
        $('#form_company_admin .field_container label.error').hide();
        $('#form_company_admin .field_container').removeClass('valid').removeClass('error');

		$("#publication option").filter(function() {
        return $(this).val() == output.data[0].publication; 
        }).prop('selected', true);
		
        $('#form_company_admin #rs').val(output.data[0].rs);
        $('#form_company_admin #siret').val(output.data[0].siret);

        $("#title option").filter(function() {
        return $(this).val() == output.data[0].title; 
        }).prop('selected', true);


        $("#form_company_admin #statut option").filter(function() {			
			return $(this).val() == output.data[0].statut;
        }).prop('selected', true);
		
		
		

        $('#form_company_admin #nom').val(output.data[0].nom);
        $('#form_company_admin #prenom').val(output.data[0].prenom);
        $('#form_company_admin #fonction').val(output.data[0].fonction);
        $('#form_company_admin #user_id').val(output.data[0].user_id);
        $('#form_company_admin #ancienne').val(output.data[0].ancienne);
		$('#form_company_admin #comm').val(output.data[0].comm);
		
        $("#etat option").filter(function() {
        return $(this).val() == output.data[0].etat; 
        }).prop('selected', true);	
		
		$("#form_company_admin #etat").change(function () {
                                                 
          var str = "";
          		$("#form_company_admin #etat option:selected").each(function () {
                str += $(this).val();
              });
           
          if(str == 1){
                $('#form_company_admin #statut').attr('disabled', true);
				$('#form_company_admin #ancienne').attr('disabled', true);
          }
          else{
              $('#form_company_admin #statut').attr('disabled', false);$('#form_company_admin #ancienne').attr('disabled', false);
          }
        });	
		
		if(output.data[0].etat == 1){
		$('#form_company_admin #statut').attr('disabled', true);
		$('#form_company_admin #ancienne').attr('disabled', true);
		}else{$('#form_company_admin #statut').attr('disabled', false);$('#form_company_admin #ancienne').attr('disabled', false);}	
		
		
        hide_loading_message();
        show_lightbox();
      } else {
        hide_loading_message();
        show_message("Une erreur s'est produite lors de l'enregistrement", 'error');
      }
    });
    request.fail(function(jqXHR, textStatus){
      hide_loading_message();
      show_message("Une erreur s'est produite lors de l'enregistrement" + textStatus, 'error');
    });
  });
  
  $(document).on('submit', '#form_company_admin.edit', function(e){
    e.preventDefault();
    if (form_company.valid() == true){
      hide_ipad_keyboard();
      hide_lightbox();
      show_loading_message();
	  var now     = new Date(); 
		
		
      var id        = $('#form_company_admin').attr('data-id');
      var form_data = $('#form_company_admin').serialize();
      var request   = $.ajax({
        url:          'module/nomination/table/php/data_acide_traitement_nomination.php?job=edit_traitement_nomination_admin&id=' + id,
        cache:        false,
        data:         form_data,
        dataType:     'json',
        contentType:  'application/json; charset=utf-8',
        type:         'get'
      });
      request.done(function(output){
        if (output.result == 'success'){
          table_companies.api().ajax.reload(function(){
            hide_loading_message();
            var company_name = $('#rs').val();
            show_message("Fiche '" + company_name + "' modifiée avec succés.", 'success');
          }, true);
        } else {
          hide_loading_message();
          show_message('Edit request failed', 'error');
        }
      });
      request.fail(function(jqXHR, textStatus){
        hide_loading_message();
        show_message('Edit request failed: ' + textStatus, 'error');
      });
    }
  });
  
  $(document).on('click', '#del', function(e){
    e.preventDefault();
    var company_name = $(this).data('name');
    if (confirm("Confirmation de supprission du '" + company_name + "' !!")){
      show_loading_message();
      var id      = $(this).data('id');
      var request = $.ajax({
        url:          'module/nomination/table/php/data_acide_traitement_nomination.php?job=delete_traitement_nomination_admin&id=' + id,
        cache:        false,
        dataType:     'json',
        contentType:  'application/json; charset=utf-8',
        type:         'get'
      });
      request.done(function(output){
        if (output.result == 'success'){
          table_companies.api().ajax.reload(function(){
            hide_loading_message();
            show_message("Ligne '" + company_name + "' effacée avec succès.", 'success');
          }, true);
        } else {
          hide_loading_message();
          show_message("Une erreur s'est produite lors de l'enregistrement", 'error');
        }
      });
      request.fail(function(jqXHR, textStatus){
        hide_loading_message();
        show_message("Une erreur s'est produite lors de l'enregistrement" + textStatus, 'error');
      });
    }
  });

});
}(jQuery);