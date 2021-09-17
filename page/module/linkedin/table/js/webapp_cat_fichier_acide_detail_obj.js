! function(t) {
    "use strict";
$(document).ready(function(){
  
  var table_companies = $('#table_gestion_traitement_lk_obj').dataTable({
	"bStateSave": true,
    "ajax": "module/linkedin/table/php/data_cat_fichier_acide.php?job=get_gestion_traitement_lk_obj",
    "columns": [
	  { "data": "ligne", "sClass": "" },
      { "data": "heure", "sClass": "" },
	  { "data": "date_debut", "sClass": "" },
      { "data": "date_fin", "sClass": "" },
	  { "data": "actif", "sClass": "" },
	  { "data": "functions", "sClass": "" }
    ],
    dom: 'Bfrtip',
	"buttons": [
            'colvis'
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
      "sLengthMenu":    "Lignes par page : _MENU_",
      "sInfo":          "Total de _TOTAL_ Lignes (Affichage _START_ à _END_)",
	  "sSearch":          "Recherche : ",
      "sInfoFiltered":  "(Filtré depuis _MAX_ total Lignes)",
	  "sLoadingRecords": "Chargement en cours des données..."
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
  var form_company = $('#form_lk');
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

  $(document).on('click', '#add_lk_obj', function(e){
    e.preventDefault();
		
		
      $('.lightbox_content h2').text('Nouvel objectif');
      $('#form_lk button').text('ENREGISTREMENT');
      $('#form_lk').attr('class', 'form add');
      $('#form_lk').attr('data-id', '');
      $('#form_lk .field_container label.error').hide();
      $('#form_lk .field_container').removeClass('valid').removeClass('error');
      $('#form_lk #heure').val('');
      $('#form_lk #fiche').val('');
      $('#form_lk #intervalle').val('');
	  
    show_lightbox();
	
  });

  $(document).on('submit', '#form_lk.add', function(e){
    e.preventDefault();
    if (form_company.valid() == true){
      hide_ipad_keyboard();
      hide_lightbox();
      show_loading_message();
		
      var form_data = $('#form_lk').serialize();
      var request   = $.ajax({
        url:          'module/linkedin/table/php/data_cat_fichier_acide.php?job=add_gestion_traitement_lk_obj',
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
            show_message("Nouvel objectif ajouté avec succés.", 'success');
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

  $(document).on('click', '#function_edit_obj', function(e){
    e.preventDefault();
    show_loading_message();
    var id      = $(this).data('id');
    var request = $.ajax({
      url:          'module/linkedin/table/php/data_cat_fichier_acide.php?job=get_gestion_traitement_add_lk_obj',
      cache:        false,
      data:         'id=' + id,
      dataType:     'json',
      contentType:  'application/json; charset=utf-8',
      type:         'get'
    });
    request.done(function(output){
      if (output.result == 'success'){
		
        $('.lightbox_content h2').text('Modification objectif');
        $('#form_lk button').text('ENREGISTREMENT');
        $('#form_lk').attr('class', 'form edit');
        $('#form_lk').attr('data-id', id);
        $('#form_lk .field_container label.error').hide();
        $('#form_lk .field_container').removeClass('valid').removeClass('error');
		

        $('#form_lk #heure').val(output.data[0].heure);
        $('#form_lk #fiche').val(output.data[0].fiche);
        $('#form_lk #custom-ranges').val(output.data[0].intervalle);
		
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
  
  $(document).on('submit', '#form_lk.edit', function(e){
    e.preventDefault();
    if (form_company.valid() == true){
      hide_ipad_keyboard();
      hide_lightbox();
      show_loading_message();
		
      var id        = $('#form_lk').attr('data-id');
      var form_data = $('#form_lk').serialize();
      var request   = $.ajax({
        url:          'module/linkedin/table/php/data_cat_fichier_acide.php?job=edit_gestion_traitement_lk_obj&id=' + id,
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
            show_message("Objectif modifié avec succés.", 'success');
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
  
  
	
	$(document).on('click', '#del_obj', function(e){
		e.preventDefault();
		var id = $(this).data('id');
		var doc_up = $(this).data('doc');
		var cat_name = $(this).data('name');
	
		t.confirm({
			title: cat_name,
			content: "Confirmation de Suppression de l'objectif n° : " + cat_name,
			autoClose: "cancelAction|10000",
			escapeKey: "cancelAction",
			draggable: !1,
			closeIcon: !0,
			buttons: {
				confirm: {
					btnClass: "btn-danger",
					text: "Confirmer",
					action: function() {
						t.alert("Suppression terminée avec succès")
						show_loading_message();
						
						var request = $.ajax({
						url:          'module/linkedin/table/php/data_cat_fichier_acide.php?job=delete_gestion_traitement_lk_obj&id=' + id + '&cat=' + doc_up,
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
							show_message("Objectif n° '" + cat_name + "' effacé avec succès.", 'success');
						  }, true);
						} else {
						  hide_loading_message();
						  show_message("Une erreur s'est produite lors de l'enregistrement", 'error');
						}
						});
						request.fail(function(jqXHR, textStatus){
						hide_loading_message();
						show_message("Une erreur s'est produite lors de l'enregistrement : " + textStatus, 'error');
						});
					}
				},
				cancelAction: {
					text: "Annuler",
					action: function() {
						t.alert("La Suppression est annulée ..!")
					}
				}
			}
			
		})
		
      
  });
  
  

});
}(jQuery);