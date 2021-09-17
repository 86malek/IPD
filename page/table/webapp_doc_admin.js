$(document).ready(function(){ 
  
  var table_doc = $('#table_doc').dataTable({
    "ajax": "table/data_doc.php?job=get_doc_admin",
    "columns": [
		{ "data": "demandeur_fichier",   "sClass": "company_name" },
      { "data": "nom_fichier",   "sClass": "company_name" },
	  { "data": "cat_fichier",   "sClass": "company_name" },	  
	  { "data": "equipe",   "sClass": "company_name" },
      { "data": "user_fichier",   "sClass": "company_name" },
	  { "data": "statut_fichier",   "sClass": "company_name" },	  
	  { "data": "down",   "sClass": "company_name" },
	  { "data": "up",   "sClass": "company_name" },
	  { "data": "traitement",   "sClass": "company_name" },
	  { "data": "nb_ligne",   "sClass": "integer" },
      { "data": "download",      "sClass": "" }
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
        "sFirst":       "<< ",
        "sPrevious":    "Précédant",
        "sNext":        "Suivant",
        "sLast":        ">>",
      },
      "sLengthMenu":    "Fichiers par page : _MENU_",
      "sInfo":          "Total de _TOTAL_ Fichiers (Affichage _START_ à _END_)",
	  "sSearch":          "Recherche",
      "sInfoFiltered":  "(Filtré depuis _MAX_ total Fichiers)"
    }
  });
  
  // On page load: form validation
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
  var form_doc = $('#form_doc');
  form_doc.validate();

  // Show message
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
  // Hide message
  function hide_message(){
    $('#message').html('').attr('class', '');
    $('#message_container').hide();
  }

  // Show loading message
  function show_loading_message(){
    $('#loading_container').show();
  }
  // Hide loading message
  function hide_loading_message(){
    $('#loading_container').hide();
  }

  // Show lightbox
  function show_lightbox(){
    $('.lightbox_bg').show();
    $('.lightbox_container').show();
  }
  // Hide lightbox
  function hide_lightbox(){
    $('.lightbox_bg').hide();
    $('.lightbox_container').hide();
  }
  // Lightbox background
  $(document).on('click', '.lightbox_bg', function(){
    hide_lightbox();
  });
  // Lightbox close button
  $(document).on('click', '.lightbox_close', function(){
    hide_lightbox();
  });
  // Escape keyboard key
  $(document).keyup(function(e){
    if (e.keyCode == 27){
      hide_lightbox();
    }
  });
  
  // Hide iPad keyboard
  function hide_ipad_keyboard(){
    document.activeElement.blur();
    $('input').blur();
  }

  $(document).on('click', '#add_doc', function(e){
    e.preventDefault();
    $('.lightbox_content h2').text('Ajout des informations collaborateur');
    $('#form_doc button').text('Ajouter un collaborateur');
    $('#form_doc').attr('class', 'form add');
    $('#form_doc').attr('data-id', '');
    $('#form_doc .field_container label.error').hide();
    $('#form_doc .field_container').removeClass('valid').removeClass('error');
    $('#form_doc #matricule').val('');
    $('#form_doc #nom').val('');
    $('#form_doc #prenom').val('');
    $('#form_doc #date').val('');
	$('#form_doc #email').val('');
	$('#form_doc #ip').val('');
	$('#form_doc #Poste').val('');
	$('#form_doc #somme').val('');
	$("#form_doc #Poste input:checked").each(function() 
        {
            $('#form_doc #Poste').attr("value"); 
        });
	$("#form_doc #coordinateur input:checked").each(function() 
        {
            $('#form_doc #coordinateur').attr("value"); 
        });
    show_lightbox();
  });

  $(document).on('submit', '#form_doc.add', function(e){
    e.preventDefault();
    if (form_doc.valid() == true){
      hide_ipad_keyboard();
      hide_lightbox();
      show_loading_message();
      var form_data = $('#form_doc').serialize();
      var request   = $.ajax({
        url:          'table/data_doc.php?job=add_doc',
        cache:        false,
        data:         form_data,
        dataType:     'json',
        contentType:  'application/json; charset=utf-8',
        type:         'get'
      });
      request.done(function(output){
        if (output.result == 'success'){
          	table_doc.api().ajax.reload(function(){
            hide_loading_message();
            var company_name = $('#nom').val();
            show_message("Collaborateur '" + company_name + "' ajouter avec succés.", 'success');
          }, true);
        } else {
          hide_loading_message();
          show_message('Add request failed', 'error');
        }
      });
      request.fail(function(jqXHR, textStatus){
        hide_loading_message();
        show_message('Add request failed: ' + textStatus, 'error');
      });
    }
  });

  $(document).on('click', '.function_edit a', function(e){
    e.preventDefault();
    show_loading_message();
    var id      = $(this).data('id');
    var request = $.ajax({
      url:          'table/data_doc.php?job=get_doc_form',
      cache:        false,
      data:         'id=' + id,
      dataType:     'json',
      contentType:  'application/json; charset=utf-8',
      type:         'get'
    });
    request.done(function(output){
      if (output.result == 'success'){
        $('.lightbox_content h2').text('Modification des informations du collaborateur');
        $('#form_doc button').text('Modification collaborateur');
        $('#form_doc').attr('class', 'form edit');
        $('#form_doc').attr('data-id', id);
        $('#form_doc .field_container label.error').hide();
        $('#form_doc .field_container').removeClass('valid').removeClass('error');
        $('#form_doc #matricule').val(output.data[0].matricule);
        $('#form_doc #nom').val(output.data[0].nom);
        $('#form_doc #prenom').val(output.data[0].prenom);
		$('#form_doc #date').val(output.data[0].date);
		$('#form_doc #email').val(output.data[0].email);
		$('#form_doc #ip').val(output.data[0].ip);
		$('#form_doc #somme').val(output.data[0].somme);
		if (output.data[0].coordinateur == 1)
		{
			$( "#form_doc #coordinateur" ).prop( "checked", true );
		}
		else
		{
			$( "#form_doc #coordinateur" ).prop( "checked", false );
		}
		$("select option").filter(function() {
			return $(this).val() == output.data[0].Poste; 
		}).prop('selected', true);


        hide_loading_message();
        show_lightbox();
      } else {
        hide_loading_message();
        show_message('Information request failed', 'error');
      }
    });
    request.fail(function(jqXHR, textStatus){
      hide_loading_message();
      show_message('Information request failed: ' + textStatus, 'error');
    });
  });
  
  $(document).on('submit', '#form_doc.edit', function(e){
    e.preventDefault();
    if (form_doc.valid() == true){
      hide_ipad_keyboard();
      hide_lightbox();
      show_loading_message();
      var id        = $('#form_doc').attr('data-id');
      var form_data = $('#form_doc').serialize();
      var request   = $.ajax({
        url:          'table/data_doc.php?job=edit_doc&id=' + id,
        cache:        false,
        data:         form_data,
        dataType:     'json',
        contentType:  'application/json; charset=utf-8',
        type:         'get'
      });
      request.done(function(output){
        if (output.result == 'success'){
          // Reload datable
          table_doc.api().ajax.reload(function(){
            hide_loading_message();
            var company_name = $('#nom').val();
            show_message("Company '" + company_name + "' edited successfully.", 'success');
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
  
  // Delete company
  $(document).on('click', '#del', function(e){
    e.preventDefault();
    var doc_name = $(this).data('name');
    if (confirm("CConfirmation de suppression du fichier '" + doc_name + "' !!")){
      show_loading_message();
      var id      = $(this).data('id');
	  var doc_up      = $(this).data('doc');
      var request = $.ajax({
        url:          'table/data_doc.php?job=delete_doc&id=' + id + '&cat=' + doc_up,
        cache:        false,
        dataType:     'json',
        contentType:  'application/json; charset=utf-8',
        type:         'get'
      });
      request.done(function(output){
        if (output.result == 'success'){
          table_doc.api().ajax.reload(function(){
            hide_loading_message();
            show_message("Fichier '" + doc_name + "' effacé avec succès.", 'success');
          }, true);
        } else {
          hide_loading_message();
          show_message('Delete request failed', 'error');
        }
      });
      request.fail(function(jqXHR, textStatus){
        hide_loading_message();
        show_message('Erreur lors de la suppression: ' + textStatus, 'error');
      });
    }
  });

});