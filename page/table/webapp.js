$(document).ready(function(){
  
  var table_companies = $('#table_companies').dataTable({
    "ajax": "table/effectif.php?job=get_companies",
    "columns": [
          { "data": "matricule_collaborateurs",   "sClass": "integer" },
          { "data": "nom_collaborateurs",   "sClass": "company_name" },
          { "data": "prenom_collaborateurs",   "sClass": "company_name" },
          { "data": "anciente_collaborateurs",   "sClass": "company_name" },
          { "data": "email_collaborateurs",   "sClass": "company_name" },
          { "data": "ip_collaborateurs",   "sClass": "integer" },	  
          { "data": "coordinateur",   "sClass": "company_name" },
          { "data": "nomination_organigramme",   "sClass": "company_name" },
          { "data": "somme_abs_collaborateurs",   "sClass": "company_name" },
          { "data": "taux_abs_collaborateurs",   "sClass": "company_name" },
          { "data": "functions",      "sClass": "functions" }
    ],
	dom: 'Bfrtip',
	"buttons": [
            {
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
      "sLengthMenu":    "Collaborateurs par page : _MENU_",
      "sInfo":          "Total de _TOTAL_ collaborateurs (Affichage _START_ à _END_)",
	  "sSearch":          "Recherche",
      "sInfoFiltered":  "(Filtré depuis _MAX_ total collaborateurs)"
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
  var form_company = $('#form_company');
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

  $(document).on('click', '#add_company', function(e){
    e.preventDefault();
    $('.lightbox_content h2').text('Ajout des informations collaborateur');
    $('#form_company button').text('Ajouter un collaborateur');
    $('#form_company').attr('class', 'form add');
    $('#form_company').attr('data-id', '');
    $('#form_company .field_container label.error').hide();
    $('#form_company .field_container').removeClass('valid').removeClass('error');
    $('#form_company #matricule').val('');
    $('#form_company #nom').val('');
    $('#form_company #prenom').val('');
    $('#form_company #date').val('');
	$('#form_company #email').val('');
	$('#form_company #ip').val('');
	$('#form_company #Poste').val('');
	$('#form_company #somme').val('');
	$("#form_company #Poste input:checked").each(function() 
        {
            $('#form_company #Poste').attr("value"); 
        });
	$("#form_company #coordinateur input:checked").each(function() 
        {
            $('#form_company #coordinateur').attr("value"); 
        });
    show_lightbox();
  });

  $(document).on('submit', '#form_company.add', function(e){
    e.preventDefault();
    if (form_company.valid() == true){
      hide_ipad_keyboard();
      hide_lightbox();
      show_loading_message();
      var form_data = $('#form_company').serialize();
      var request   = $.ajax({
        url:          'table/effectif.php?job=add_company',
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
            var company_name = $('#nom').val();
            show_message("Collaborateur '" + company_name + "' ajouter avec succés.", 'success');
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

  $(document).on('click', '#function_edit_company', function(e){
    e.preventDefault();
    show_loading_message();
    var id      = $(this).data('id');
    var request = $.ajax({
      url:          'table/effectif.php?job=get_company',
      cache:        false,
      data:         'id=' + id,
      dataType:     'json',
      contentType:  'application/json; charset=utf-8',
      type:         'get'
    });
    request.done(function(output){
      if (output.result == 'success'){
        $('.lightbox_content h2').text('Modification des informations du collaborateur');
        $('#form_company button').text('Modification collaborateur');
        $('#form_company').attr('class', 'form edit');
        $('#form_company').attr('data-id', id);
        $('#form_company .field_container label.error').hide();
        $('#form_company .field_container').removeClass('valid').removeClass('error');
        $('#form_company #matricule').val(output.data[0].matricule);
        $('#form_company #nom').val(output.data[0].nom);
        $('#form_company #prenom').val(output.data[0].prenom);
		$('#form_company #date').val(output.data[0].date);
		$('#form_company #email').val(output.data[0].email);
		$('#form_company #ip').val(output.data[0].ip);
		$('#form_company #somme').val(output.data[0].somme);
		if (output.data[0].coordinateur == 1)
		{
			$( "#form_company #coordinateur" ).prop( "checked", true );
		}
		else
		{
			$( "#form_company #coordinateur" ).prop( "checked", false );
		}
		$("select option").filter(function() {
			return $(this).val() == output.data[0].Poste; 
		}).prop('selected', true);


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
  
  $(document).on('submit', '#form_company.edit', function(e){
    e.preventDefault();
    if (form_company.valid() == true){
      hide_ipad_keyboard();
      hide_lightbox();
      show_loading_message();
      var id        = $('#form_company').attr('data-id');
      var form_data = $('#form_company').serialize();
      var request   = $.ajax({
        url:          'table/effectif.php?job=edit_company&id=' + id,
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
            var company_name = $('#nom').val();
            show_message("Company '" + company_name + "' edited successfully.", 'success');
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
  
  $(document).on('click', '#del', function(e){
    e.preventDefault();
    var company_name = $(this).data('name');
    if (confirm("Confirmation de supprission du '" + company_name + "' !!")){
      show_loading_message();
      var id      = $(this).data('id');
      var request = $.ajax({
        url:          'table/effectif.php?job=delete_company&id=' + id,
        cache:        false,
        dataType:     'json',
        contentType:  'application/json; charset=utf-8',
        type:         'get'
      });
      request.done(function(output){
        if (output.result == 'success'){
          table_companies.api().ajax.reload(function(){
            hide_loading_message();
            show_message("Collaborateur '" + company_name + "' effacer avec succès.", 'success');
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