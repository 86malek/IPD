! function(t) {
    "use strict";
$(document).ready(function(){
  
  var table_companies = $('#table_activite').dataTable({
    "ajax": "module/timesheet/table/php/data_liste_activite.php?job=get_activite",
    "columns": [
      { "data": "date",   "sClass": "" },
      { "data": "tache",   "sClass": "" },
      { "data": "categorie",   "sClass": "" },
      { "data": "temps",   "sClass": "" },
      { "data": "user",   "sClass": "" },
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
      "sLengthMenu":    "Activités par page : _MENU_",
      "sInfo":          "Total de _TOTAL_ Activités (Activités _START_ à _END_)",
	  "sSearch":          "Recherche...",
      "sInfoFiltered":  "(Filtré depuis _MAX_ total Activités)",
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
  
  var form_company = $('#form_activite');
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
	
  $(document).on('click', '#add_activite', function(e){
    e.preventDefault();
    $('.lightbox_container h2').text('Ajouter une activite');
    $('#form_activite button').text('Validation');
    $('#form_activite').attr('class', 'form add');
    $('#form_activite').attr('data-id', '');
    $('#form_activite .field_container label.error').hide();
    $('#form_activite .field_container').removeClass('valid').removeClass('error');
    $('#form_activite #date').val('');
    $('#form_activite #tache').val('');
    $('#form_activite #cat').val('');
    $('#form_activite #temps').val('');
    show_lightbox();
  });

  $(document).on('submit', '#form_activite.add', function(e){
    e.preventDefault();
    if (form_company.valid() == true){
      hide_ipad_keyboard();
      hide_lightbox();
      show_loading_message();
      var form_data = $('#form_activite').serialize();
      var request   = $.ajax({
        url:          'module/timesheet/table/php/data_liste_activite.php?job=add_activite',
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
            show_message("Activié ajoutée avec succés.", 'success');
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

  $(document).on('click', '#function_edit_activite', function(e){
    e.preventDefault();
    show_loading_message();
    var id      = $(this).data('id');
    var request = $.ajax({
      url:          'module/timesheet/table/php/data_liste_activite.php?job=get_activite_add',
      cache:        false,
      data:         'id=' + id,
      dataType:     'json',
      contentType:  'application/json; charset=utf-8',
      type:         'get'
    });
    request.done(function(output){
      if (output.result == 'success'){
        $('.lightbox_container h2').text("Modifier une tâche");
        $('#form_activite button').text('Validation');
        $('#form_activite').attr('class', 'form edit');
        $('#form_activite').attr('data-id', id);
        $('#form_activite .field_container label.error').hide();
        $('#form_activite .field_container').removeClass('valid').removeClass('error');
        $('#form_activite #date').val(output.data[0].date);


        $("#form_activite #tache option").filter(function() {
          return $(this).val() == output.data[0].tache; 
          }).prop('selected', true);

        
        $("#form_activite #cat option").filter(function() {
          return $(this).val() == output.data[0].cat; 
          }).prop('selected', true);


        $('#form_activite #temps').val(output.data[0].temps);


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
  
  $(document).on('submit', '#form_activite.edit', function(e){
    e.preventDefault();
    if (form_company.valid() == true){
      hide_ipad_keyboard();
      hide_lightbox();
      show_loading_message();
      var id        = $('#form_activite').attr('data-id');
      var form_data = $('#form_activite').serialize();
      var request   = $.ajax({
        url:          'module/timesheet/table/php/data_liste_activite.php?job=edit_activite&id=' + id,
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
            show_message("Tache '" + company_name + "' modifiée avec succés.", 'success');
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
			title: "Supprission de l'activité",
			content: "",
			autoClose: "cancelAction|10000",
			escapeKey: "cancelAction",
			draggable: !1,
			closeIcon: !0,
			buttons: {
				confirm: {
					btnClass: "btn-danger",
					text: "Confirmer",
					action: function() {
						t.alert("Suppression terminée")
						show_loading_message();						
						var request = $.ajax({
						url:          'module/timesheet/table/php/data_liste_activite.php?job=delete_activite&id=' + id,
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
						t.alert("Suppression annulée")
					}
				}
			}
			
		})
		
      
  });

});
}(jQuery);