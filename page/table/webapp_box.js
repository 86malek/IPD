! function(t) {
    "use strict";
$(document).ready(function(){
  
	
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
	  $(element).parent('#message').removeClass('valid').addClass('error');
    },
    unhighlight: function(element){
      $(element).parent('.field_container').addClass('valid').removeClass('error');
	  $(element).parent('#message').addClass('valid').removeClass('error');
    }
  });
  var form_box = $('#form_box');
  form_box.validate();

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
  



$(document).on('click', '#nouveau_message', function(e){
e.preventDefault();
$('.lightbox_content h2').text('Nouveau message privé');
$('#form_box button').text('Envoyer votre message');
$('#form_box').attr('class', 'form add');
$('#form_box').attr('data-id', '');
$('#form_box .field_container label.error').hide();
$('#form_box .field_container').removeClass('valid').removeClass('error');
$('#form_box #objet').val('');
$('#form_box #message').val('');
$('#form_box #user').val();
$("#form_box #destinataire input:checked").each(function(){$('#form_box #destinataire').attr("value");});
$("#form_box #dossier input:checked").each(function(){$('#form_box #dossier').attr("value");});
show_lightbox();
});


$(document).on('submit', '#form_box.add', function(e){
e.preventDefault();
if (form_box.valid() == true){
  hide_ipad_keyboard();
  hide_lightbox();
  show_loading_message();
  var form_data = $('#form_box').serialize();
  var request   = $.ajax({
	url:          'table/data_box.php?job=add_new_message',
	cache:        false,
	data:         form_data,
	dataType:     'json',
	contentType:  'application/json; charset=utf-8',
	type:         'get'
  });
  request.done(function(output){
	if (output.result == 'success'){
		window.location.reload(function(){
			hide_loading_message();
			show_message("Votre message a bien été envoyé !", 'success');		
		}, true);
	} else {
	  hide_loading_message();
	  show_message("Une erreur s'est produite lors de l'envoi du message", 'error');
	}
  });
  request.fail(function(jqXHR, textStatus){
	hide_loading_message();
	show_message("Une erreur s'est produite lors de l'envoi du message " + textStatus, 'error');
  });
}
});


$(document).on('click', '#function_edit_web', function(e){
e.preventDefault();
show_loading_message();
var id      = $(this).data('id');
var request = $.ajax({
  url:          'table/data_webmaster.php?job=get_webmaster_add',
  cache:        false,
  data:         'id=' + id,
  dataType:     'json',
  contentType:  'application/json; charset=utf-8',
  type:         'get'
});
request.done(function(output){
  if (output.result == 'success'){
	$('.lightbox_content h2').text('Modifier une opération webmasters');
	$('#form_box button').text('Enregistrement');
	$('#form_box').attr('class', 'form edit');
	$('#form_box').attr('data-id', id);
	$('#form_box .field_container label.error').hide();
	$('#form_box .field_container').removeClass('valid').removeClass('error');
	$('#form_box #operation').val(output.data[0].operation);
	$('#form_box #nb').val(output.data[0].nb);
	$('#form_box #compagne').val(output.data[0].compagne);
	$('#form_box #volume').val(output.data[0].volume);
	$('#form_box #debut').val(output.data[0].debut);
	$('#form_box #fin').val(output.data[0].fin);


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
$(document).on('submit', '#form_box.edit', function(e){
e.preventDefault();
if (form_box.valid() == true){
  hide_ipad_keyboard();
  hide_lightbox();
  show_loading_message();
  var id        = $('#form_box').attr('data-id');
  var form_data = $('#form_box').serialize();
  var request   = $.ajax({
	url:          'table/data_webmaster.php?job=edit_webmaster&id=' + id,
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
		var company_name = $('#operation').val();
		show_message("Opération webmaster '" + company_name + "' modifiée avec succés.", 'success');
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
$(document).on('click', 'a#del', function(e){
	e.preventDefault();
	var id      = $(this).data('id');
	var doc_up      = $(this).data('doc');
	var cat_name = $(this).data('name');

	t.confirm({
		title: cat_name,
		content: "Confirmation de supprission de l'entrés " + cat_name,
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
					url:          'table/data_cat_fichier_acide.php?job=delete_cat_fichier&id=' + id + '&cat=' + doc_up,
					cache:        false,
					dataType:     'json',
					contentType:  'application/json; charset=utf-8',
					type:         'get'
					});
					request.done(function(output){
					if (output.result == 'success'){
					  table_cat_fichier.api().ajax.reload(function(){
						hide_loading_message();
						hide_lightbox_del();
						show_message("Catégorie '" + cat_name + "' effacée avec succès.", 'success');
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
					t.alert("La supprission est annulée")
				}
			}
		}
		
	})
	
  
});




});
}(jQuery);