$(document).ready(function(){
  var table_companies = $('#table_gestion_traitement_auto').dataTable({
    "ajax": "table/data_gestion_traitement_auto.php?job=get_gestion_traitement_auto",
    "columns": [
	  { "data": "collab", "sClass": "company_name" },
      { "data": "datedebut", "sClass": "integer" },
	  { "data": "datefin", "sClass": "integer" },
	  { "data": "jh", "sClass": "integer" },
	  { "data": "time", "sClass": "company_name" },
	  { "data": "count", "sClass": "company_name" },
	  { "data": "countok", "sClass": "company_name" },
	  { "data": "countko", "sClass": "company_name" },
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
      "sLengthMenu":    "Opérations par page : _MENU_",
      "sInfo":          "Total de _TOTAL_ Opérations (Affichage _START_ à _END_)",
	  "sSearch":          "Recherche : ",
      "sInfoFiltered":  "(Filtré depuis _MAX_ total Opérations)",
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

  $(document).on('click', '#add_nomination', function(e){
    e.preventDefault();
    $('.lightbox_content h2').text('Ajout nouvelle entrée NOMINATION');
    $('#form_company button').text('Validation');
    $('#form_company').attr('class', 'form add');
    $('#form_company').attr('data-id', '');
    $('#form_company .field_container label.error').hide();
    $('#form_company .field_container').removeClass('valid').removeClass('error');
    $('#form_company #publication').val('');
    $('#form_company #rs').val('');
    $('#form_company #siret').val('');
	$("#form_company #title input:checked").each(function() 
        {
            $('#form_company #title').attr("value"); 
        });
	$('#form_company #nom').val('');
    $('#form_company #prenom').val('');
	$('#form_company #fonction').val('');
	
	$("#form_company #statut input:checked").each(function() 
        {
            $('#form_company #statut').attr("value"); 
        });
	$('#form_company #ancienne').val('');
	
	$("#form_company #bo input:checked").each(function() 
        {
            $('#form_company #bo').attr("value"); 
        });
	$("#form_company #nt input:checked").each(function() 
        {
            $('#form_company #nt').attr("value"); 
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
        url:          'table/data_gestion_traitement_auto.php?job=add_traitement_auto',
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


  $(document).on('click', '#function_edit_web', function(e){
    e.preventDefault();
    show_loading_message();
    var id      = $(this).data('id');
    var request = $.ajax({
      url:          'table/data_gestion_traitement_auto.php?job=get_traitement_add_auto',
      cache:        false,
      data:         'id=' + id,
      dataType:     'json',
      contentType:  'application/json; charset=utf-8',
      type:         'get'
    });
    request.done(function(output){
      if (output.result == 'success'){
        $('.lightbox_content h2').text('Modification Enregistrement NOMINATION');
        $('#form_company button').text('Validation');
        $('#form_company').attr('class', 'form edit');
        $('#form_company').attr('data-id', id);
        $('#form_company .field_container label.error').hide();
        $('#form_company .field_container').removeClass('valid').removeClass('error');
		
		$('#form_company #publication').val(output.data[0].publication);
		$('#form_company #rs').val(output.data[0].rs);
		$('#form_company #siret').val(output.data[0].siret);
		
		$("select option").filter(function() {
			return $(this).val() == output.data[0].title; 
		}).prop('selected', true);
		
	
		$("select option").filter(function() {
			return $(this).val() == output.data[0].statut; 
		}).prop('selected', true);
		
		$('#form_company #nom').val(output.data[0].nom);
		$('#form_company #prenom').val(output.data[0].prenom);
		$('#form_company #fonction').val(output.data[0].fonction);
		
		$('#form_company #ancienne').val(output.data[0].ancienne);
		
		if (output.data[0].bo == 1)
		{
			$( "#form_company #bo" ).prop( "checked", true );
		}
		else
		{
			$( "#form_company #bo" ).prop( "checked", false );
		}
		
		if (output.data[0].nt == 1)
		{
			$( "#form_company #nt" ).prop( "checked", true );
		}
		else
		{
			$( "#form_company #nt" ).prop( "checked", false );
		}
		
		
		

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
        url:          'table/data_gestion_traitement_auto.php?job=edit_traitement_auto&id=' + id,
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
            show_message("Opération '" + company_name + "' modifiée avec succés.", 'success');
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
        url:          'table/data_gestion_traitement_auto.php?job=delete_gestion_traitement_auto&id=' + id,
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