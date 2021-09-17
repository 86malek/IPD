! function(t) {
    "use strict";
$(document).ready(function(){
	
  	var table_companies = $('#table_user').dataTable({
	"bStateSave": true,
    "ajax": "module/admin/table/php/data_liste_contact.php?job=get_liste_user",
    "columns": [
		{ "data": "matricule", "sClass": "" },
		{ "data": "full_name", "sClass": "" },
		{ "data": "equipe", "sClass": "" },
		{ "data": "ip", "sClass": "" },
		{ "data": "user_email", "sClass": "" },
		{ "data": "niveau", "sClass": "" },		
		{ "data": "functions", "sClass": "" },		
		{ "data": "actif", "sClass": "" },
		{ "data": "date", "sClass": "" },
		{ "data": "mdp", "sClass": "" }
	  
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
      "sLengthMenu":    "Utilisateurs par page : _MENU_",
      "sInfo":          "Total de _TOTAL_ Utilisateurs (Affichage _START_ à _END_)",
	  "sSearch":          "Recherche : ",
      "sInfoFiltered":  "(Filtré depuis _MAX_ total Utilisateurs)",
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

  $(document).on('click', '#add_user', function(e){
    e.preventDefault();		
      $('.lightbox_content h2').text("AJOUTER UN NOUVELLE UTILISATEUR");
      $('#form_company button').text('ENREGISTREMENT');
      $('#form_company').attr('class', 'form add');
      $('#form_company').attr('data-id', '');
      $('#form_company .field_container label.error').hide();
      $('#form_company .field_container').removeClass('valid').removeClass('error');
	  $('#form_company #equipe').val('');
      $('#form_company #full_name').val('');
      $('#form_company #ip').val('');
      $('#form_company #user_email').val('');
	  $('#form_company #user_email').attr('required', true);
      $('#form_company #pwd').val('');
      $('#form_company #niveau').val('');
	  $('#form_company #matricule').val('');
	  $('#form_company #pwd').attr('disabled', false);
    show_lightbox();
  });

  $(document).on('submit', '#form_company.add', function(e){
	  
    e.preventDefault();
	
    if (form_company.valid() == true){      	  
	  			
      var form_data = $('#form_company').serialize();
      var request   = $.ajax({
        url:          'module/admin/table/php/data_liste_contact.php?job=add_user',
        cache:        false,
        data:         form_data,
        dataType:     'json',
        contentType:  'application/json; charset=utf-8',
        type:         'get'
      });
	  
      request.done(function(output){
        if (output.result == 'success'){
			hide_ipad_keyboard();
			hide_lightbox();
			show_loading_message();
			table_companies.api().ajax.reload(function(){
			hide_loading_message();
			show_message("Nouvel utilisateur ajouté avec succés.", 'success');
          }, true);
        } else {
          hide_loading_message();
          show_message("ALERTE : " + output.message, 'error');
        }
      });
      request.fail(function(jqXHR, textStatus){
        hide_loading_message();
        show_message("ALERTE :" + output.message, 'error');
      });
	  
    }
	
  });

  $(document).on('click', '#function_edit_web', function(e){
    e.preventDefault();
    show_loading_message();
    var id      = $(this).data('id');
    var request = $.ajax({
      url:          'module/admin/table/php/data_liste_contact.php?job=get_user_edit',
      cache:        false,
      data:         'id=' + id,
      dataType:     'json',
      contentType:  'application/json; charset=utf-8',
      type:         'get'
    });
    request.done(function(output){
      if (output.result == 'success'){
		
		
        $('.lightbox_content h2').text('MODIFIER UN UTILISATEUR');
        $('#form_company button').text('ENREGISTREMENT');
        $('#form_company').attr('class', 'form edit');
        $('#form_company').attr('data-id', id);
        $('#form_company .field_container label.error').hide();
        $('#form_company .field_container').removeClass('valid').removeClass('error');
		
			$("#form_company #equipe option").filter(function() {
			return $(this).val() == output.data[0].equipe; 
			}).prop('selected', true);
		
			$('#form_company #full_name').val(output.data[0].full_name);
			$('#form_company #ip').val(output.data[0].ip);
			$('#form_company #user_email').val('');
			$('#form_company #pwd').val('');
			$('#form_company #pwd').attr('disabled', true);
			
			$('#form_company #user_email').attr('required', false);
			
			$("#form_company #niveau option").filter(function() {
			return $(this).val() == output.data[0].niveau; 
			}).prop('selected', true);
			
			$('#form_company #matricule').val(output.data[0].matricule);
		
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
       
		
      var id        = $('#form_company').attr('data-id');
      var form_data = $('#form_company').serialize();
      var request   = $.ajax({
        url:          'module/admin/table/php/data_liste_contact.php?job=edit_user&id=' + id,
        cache:        false,
        data:         form_data,
        dataType:     'json',
        contentType:  'application/json; charset=utf-8',
        type:         'get'
      });
      request.done(function(output){
        if (output.result == 'success'){
				hide_ipad_keyboard();
				hide_lightbox();
				show_loading_message();
				table_companies.api().ajax.reload(function(){
				hide_loading_message();
				show_message("Utilisateur modifié avec succés.", 'success');
				}, true);
        } else {
          hide_loading_message();
          show_message("ALERTE : " + output.message, 'error');
        }
      });
      request.fail(function(jqXHR, textStatus){
        hide_loading_message();
        show_message('Edit request failed: ' + textStatus, 'error');
      });
    }
  });
  
  $(document).on('click', 'a#function_mdp', function(e){
		e.preventDefault();
		var id = $(this).data('id');
	
		t.confirm({
			title: "Réinitialisation du mot de passe",
			content: "Pass : Wellcome01",
			autoClose: "cancelAction|10000",
			escapeKey: "cancelAction",
			draggable: !1,
			closeIcon: !0,
			buttons: {
				confirm: {
					btnClass: "btn-warning",
					text: "Confirmer",
					action: function() {
						t.alert("Réinitialisation terminée avec succès")
						show_loading_message();						
						var request = $.ajax({
						url:          'module/admin/table/php/data_liste_contact.php?job=user_mdp&id=' + id,
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
							show_message("Réinitialisation du mot de passe terminée.", 'success');
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
				},
				cancelAction: {
					text: "Annuler",
					action: function() {
						t.alert("Demande annulée")
					}
				}
			}
			
		})
		
      
  });
  
  $(document).on('click', 'a#function_actif', function(e){
		e.preventDefault();
		var id = $(this).data('id');
		var actif = $(this).data('st');
	
		t.confirm({
			title: "Activation / Blocage de compte",
			content: "Confirmation de changement statut",
			autoClose: "cancelAction|10000",
			escapeKey: "cancelAction",
			draggable: !1,
			closeIcon: !0,
			buttons: {
				confirm: {
					btnClass: "btn-info",
					text: "Confirmer",
					action: function() {
						t.alert("Changement de statut terminé")
						show_loading_message();						
						var request = $.ajax({
						url:          'module/admin/table/php/data_liste_contact.php?job=user_statut&id=' + id + '&st=' + actif,
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
						  show_message("Une erreur s'est produite lors de l'enregistrement", 'error');
						}
						});
						request.fail(function(jqXHR, textStatus){
						hide_loading_message();
						show_message("Une erreur s'est produite lors de l'enregistrement" + textStatus, 'error');
						});
					}
				},
				cancelAction: {
					text: "Annuler",
					action: function() {
						t.alert("Changement annulé")
					}
				}
			}
			
		})
		
      
  });
  
  

});
}(jQuery);