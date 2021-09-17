! function(t) {
    "use strict";
$(document).ready(function(){
  
  jQuery.extend( jQuery.fn.dataTableExt.oSort, {
  "date-uk-pre": function ( a ) {
    var ukDatea = a.split('/');
    return (ukDatea[2] + ukDatea[1] + ukDatea[0]) * 1;
  },
  
  "date-uk-asc": function ( a, b ) {
    return ((a < b) ? -1 : ((a > b) ? 1 : 0));
  },
  
  "date-uk-desc": function ( a, b ) {
    return ((a < b) ? 1 : ((a > b) ? -1 : 0));
  }
  } );

  var table_companies = $('#table_notif').dataTable({
    
    "ajax": "module/contact/table/php/data_notif.php?job=get_notif",
    "columns": [
      { "data": "nomprenom",   "sClass": "" },
      { "data": "date",   "sClass": "", "sType": "date-uk"  },
      { "data": "sujet",   "sClass": "" },
      { "data": "email",   "sClass": "" },
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
      "sLengthMenu":    "Lignes par page : _MENU_",
      "sInfo":          "Total de _TOTAL_ Lignes (Lignes _START_ à _END_)",
      "sSearch":          "Recherche",
      "sInfoFiltered":  "(Filtré depuis _MAX_ total Lignes)",
      "sLoadingRecords": "Chargement en cours des données ..."
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
  var form_company = $('#form_notif');
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


  $(document).on('click', '#function_edit_notif', function(e){
    e.preventDefault();
    show_loading_message();
    var id      = $(this).data('id');
    var request = $.ajax({
      url:          'module/contact/table/php/data_notif.php?job=get_notif_add',
      cache:        false,
      data:         'id=' + id,
      dataType:     'json',
      contentType:  'application/json; charset=utf-8',
      type:         'get'
    });
    request.done(function(output){
      if (output.result == 'success'){
        $('.lightbox_container h2').text("Visualisation de la notification N° : " + id);
        $('#form_notif button').text('Clôturer la demande');
        $('#form_notif').attr('class', 'form edit');
        $('#form_notif').attr('data-id', id);
        $('#form_notif .field_container label.error').hide();
        $('#form_notif .field_container').removeClass('valid').removeClass('error');
        $('#form_notif #nomprenom').val(output.data[0].nomprenom);
        $('#form_notif #obj').val(output.data[0].obj);
        $('#form_notif #mail').val(output.data[0].mail);
        $('#form_notif #message').val(output.data[0].message);


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

  
  $(document).on('submit', '#form_notif.edit', function(e){
    e.preventDefault();
    if (form_company.valid() == true){
      hide_ipad_keyboard();
      hide_lightbox();
      show_loading_message();
      var id        = $('#form_notif').attr('data-id');
      var form_data = $('#form_notif').serialize();
      var request   = $.ajax({
        url:          'module/contact/table/php/data_notif.php?job=edit_notif&id=' + id,
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
            show_message("Notification N° :  '" + id + "' modifiée avec succés.", 'success');
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
			title: "Supprission de la notification",
			content: "No° : " + id,
			autoClose: "cancelAction|10000",
			escapeKey: "cancelAction",
			draggable: !1,
			closeIcon: !0,
			buttons: {
				confirm: {
					btnClass: "btn-danger",
					text: "Confirmer",
					action: function() {
						t.alert("Supprission de la notification  No° :" + id + " terminée !")
						show_loading_message();						
						var request = $.ajax({
						url:          'module/contact/table/php/data_notif.php?job=delete_notif&id=' + id,
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
							show_message("Supprission de la notification", 'success');
						  }, true);
						} else {
						  hide_loading_message();
						  show_message("Une erreur lors de l'enregistrement", 'error');
						}
						});
						request.fail(function(jqXHR, textStatus){
						hide_loading_message();
						show_message("Une erreur lors de l'enregistrement " + textStatus, 'error');
						});
					}
				},
				cancelAction: {
					text: "Annuler",
					action: function() {
						t.alert("Supprission annulée !")
					}
				}
			}
			
		})
		
      
  });

});
}(jQuery);