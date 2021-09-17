! function(t) {
    "use strict";
$(document).ready(function(){
  
  var table_companies = $('#table_service').dataTable({
    "ajax": "module/timesheet/table/php/data_liste_service.php?job=get_service",
    "columns": [
      { "data": "nom",   "sClass": "" },
      { "data": "functions",      "sClass": "" }
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
    "oLanguage": {
      "oPaginate": {
        "sFirst":       "<< ",
        "sPrevious":    "Précédent",
        "sNext":        "Suivant",
        "sLast":        ">>",
      },
      "sLengthMenu":    "Services par page : _MENU_",
      "sInfo":          "Total de _TOTAL_ Services (Services _START_ à _END_)",
	  "sSearch":          "Recherche...",
      "sInfoFiltered":  "(Filtré depuis _MAX_ total Service)",
	  "sLoadingRecords":  "Chargement en cours..."
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
  
  var form_company = $('#form_service');
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
  
	$(document).on('click', '#refresh', function(e){
		table_companies.api().ajax.reload(function(){
		hide_loading_message();
		show_message("Rafraîchissement terminé", 'success');
		}, true);
	});
	
  $(document).on('click', '#add_service', function(e){
    e.preventDefault();
    $('.lightbox_container h2').text('Ajouter un service');
    $('#form_service button').text('Validation');
    $('#form_service').attr('class', 'form add');
    $('#form_service').attr('data-id', '');
    $('#form_service .field_container label.error').hide();
    $('#form_service .field_container').removeClass('valid').removeClass('error');
    $('#form_service #nom').val('');
    show_lightbox();
  });

  $(document).on('submit', '#form_service.add', function(e){
    e.preventDefault();
    if (form_company.valid() == true){
      hide_ipad_keyboard();
      hide_lightbox();
      show_loading_message();
      var form_data = $('#form_service').serialize();
      var request   = $.ajax({
        url:          'module/timesheet/table/php/data_liste_service.php?job=add_service',
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
            show_message("Service '" + company_name + "' ajouté avec succés.", 'success');
          }, true);
        } else {
          hide_loading_message();
          show_message("Une erreur s'est produite lors de l'enregistrement", 'error');
        }
      });
      request.fail(function(jqXHR, textStatus){
        hide_loading_message();
        show_message("Une erreur s'est produite lors de l'enregistrement " + textStatus, 'error');
      });
    }
  });

  $(document).on('click', '#function_edit_service', function(e){
    e.preventDefault();
    show_loading_message();
    var id      = $(this).data('id');
    var request = $.ajax({
      url:          'module/timesheet/table/php/data_liste_service.php?job=get_service_add',
      cache:        false,
      data:         'id=' + id,
      dataType:     'json',
      contentType:  'application/json; charset=utf-8',
      type:         'get'
    });
    request.done(function(output){
      if (output.result == 'success'){
        $('.lightbox_container h2').text("Modifier un service");
        $('#form_service button').text('Validation');
        $('#form_service').attr('class', 'form edit');
        $('#form_service').attr('data-id', id);
        $('#form_service .field_container label.error').hide();
        $('#form_service .field_container').removeClass('valid').removeClass('error');
        $('#form_service #nom').val(output.data[0].nom);


        hide_loading_message();
        show_lightbox();
      } else {
        hide_loading_message();
        show_message("Une erreur s'est produite lors de l'enregistrement", 'error');
      }
    });
    request.fail(function(jqXHR, textStatus){
      hide_loading_message();
      show_message("Une erreur s'est produite lors de l'enregistrement " + textStatus, 'error');
    });
  });
  
  $(document).on('submit', '#form_service.edit', function(e){
    e.preventDefault();
    if (form_company.valid() == true){
      hide_ipad_keyboard();
      hide_lightbox();
      show_loading_message();
      var id        = $('#form_service').attr('data-id');
      var form_data = $('#form_service').serialize();
      var request   = $.ajax({
        url:          'module/timesheet/table/php/data_liste_service.php?job=edit_service&id=' + id,
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
            show_message("Service '" + company_name + "' modifié avec succés.", 'success');
          }, true);
        } else {
          hide_loading_message();
          show_message("Une erreur s'est produite lors de l'enregistrement", 'error');
        }
      });
      request.fail(function(jqXHR, textStatus){
        hide_loading_message();
        show_message("Une erreur s'est produite lors de l'enregistrement " + textStatus, 'error');
      });
    }
  });
 
  $(document).on('click', 'a#del', function(e){
		e.preventDefault();
		var id = $(this).data('id');
	
		t.confirm({
			title: "Supprission du service",
			content: "!!",
			autoClose: "cancelAction|10000",
			escapeKey: "cancelAction",
			draggable: !1,
			closeIcon: !0,
			buttons: {
				confirm: {
					btnClass: "btn-danger",
					text: "Confirmer",
					action: function() {
						t.alert("Supprission terminée")
						show_loading_message();						
						var request = $.ajax({
						url:          'module/timesheet/table/php/data_liste_service.php?job=delete_service&id=' + id,
						cache:        false,
						dataType:     'json',
						contentType:  'application/json; charset=utf-8',
						type:         'get'
						});
						request.done(function(output){
						if (output.result == 'success'){
						  	table_companies.api().ajax.reload(function(){
							hide_loading_message();
							hide_lightbox_del();
							show_message("Changement de statut terminé", 'success');
						  }, true);
						} else {
						  hide_loading_message();
						  show_message("Une erreur lors de l'enregistrement", 'error');
						}
						});
						request.fail(function(jqXHR, textStatus){
						hide_loading_message();
						show_message("Une erreur lors de l'enregistrement" + textStatus, 'error');
						});
					}
				},
				cancelAction: {
					text: "Annuler",
					action: function() {
						t.alert("Supprission annulée")
					}
				}
			}
			
		})
		
      
  });

});
}(jQuery);