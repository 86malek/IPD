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
	  	
		
  	var table_doc = $('#table_collect').dataTable({
	
	"stateDuration": 60*60*24*365,
	
	stateSave: true,
	
	
    "ajax": "module/collectivite/table/php/data_collectivite.php?job=get_collect_liste_admin",
    "columns": [
      { "data": "nom_lot",   "sClass": "company_name" },
	  { "data": "statut",   "sClass": "company_name" },
      { "data": "total_ligne_lot",   "sClass": "company_name" },
	  { "data": "total_ligne_taiter_lot",   "sClass": "company_name" },
	  { "data": "avancement",   "sClass": "company_name" },	  
	  { "data": "participant_lot",   "sClass": "company_name" },
	  { "data": "debut",   "sClass": "company_name", "sType": "date-uk" },
	  { "data": "fin",   "sClass": "company_name", "sType": "date-uk" },
	  { "data": "somme_traitement",   "sClass": "company_name" },
	  { "data": "jh_lot",   "sClass": "company_name" },
      { "data": "functions",      "sClass": "" },
	  { "data": "modif",      "sClass": "" }
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
	
    "oLanguage": {
      "oPaginate": {
        "sFirst":       "<<",
        "sPrevious":    "Précédent",
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
  var form_company = $('#form_company');
  form_company.validate();

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
  /*$(document).on('click', '.lightbox_bg', function(){
    hide_lightbox();
  });*/
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
 
	//si un clic est identifié sur le lien, on emploi la function suivante
	$(document).on('click', '#refreshhs', function() {
		$("#loading_container").show();
	});
	
	$(document).on('click', '#refresh', function(e){
		table_doc.api().ajax.reload(function(){
		hide_loading_message();
		show_message("Rafraîchissement terminé", 'success');
		}, true);
	});
	
	$(document).on(setInterval(function(){
		table_doc.api().ajax.reload(function(){
		hide_loading_message();
		}, true);
	}, 20000)
	);
	  
  $(document).on('click', '#function_edit_web', function(e){
    e.preventDefault();
    show_loading_message();
    var id      = $(this).data('id');
    var request = $.ajax({
      url:          'module/collectivite/table/php/data_collectivite.php?job=get_collect_liste_edit',
      cache:        false,
      data:         'id=' + id,
      dataType:     'json',
      contentType:  'application/json; charset=utf-8',
      type:         'get'
    });	
    request.done(function(output){
		
      if (output.result == 'success'){
		  
        $('.lightbox_content h2').text('Modification des données du fichier :');
        $('#form_company button').text('Enregistrement');
        $('#form_company').attr('class', 'form edit');
        $('#form_company').attr('data-id', id);
        $('#form_company .field_container label.error').hide();
        $('#form_company .field_container').removeClass('valid').removeClass('error');		
		$('#form_company #object').val(output.data[0].object);
		$('#form_company #nom').val(output.data[0].nom);
		
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
        url:          'module/collectivite/table/php/data_collectivite.php?job=edit_collect_liste&id=' + id,
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
				show_message("Fiche modifiée avec succés.", 'success');			
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
  
  $(document).on('click', 'a#del', function(e){
		e.preventDefault();
		var id = $(this).data('id');
		var doc_up = $(this).data('doc');
		var cat_name = $(this).data('name');
	
		t.confirm({
			title: cat_name,
			content: "Confirmation de supprission de du fichier " + cat_name,
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
						url:          'module/collectivite/table/php/data_collectivite.php?job=delete_collect_liste&id=' + id + '&cat=' + doc_up,
						cache:        false,
						dataType:     'json',
						contentType:  'application/json; charset=utf-8',
						type:         'get'
						});
						request.done(function(output){
						if (output.result == 'success'){
						  table_doc.api().ajax.reload(function(){
							hide_loading_message();
							hide_lightbox_del();
							show_message("Fichier '" + cat_name + "' effacée avec succès.", 'success');
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