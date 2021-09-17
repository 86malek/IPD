! function(t) {
    "use strict";
$(document).ready(function(){ 

	  	var request_data = $.ajax({
		url:          'module/acide_autre/table/php/data_doc_acide.php?job=get_doc_admin_acide',
		cache:        false,
		dataType:     'json',
		contentType:  'application/json; charset=utf-8',
		type:         'get'
		});
	
		request_data.done(function(output){
			
			if (output.result == 'success'){
					//var nom = output.data[0].nom_fichier;				
					(new GrowlNotification({
					  title: 'Terminé!',
					  description: 'Chargement du tableau terminé avec succès.',
					  image: 'img/notifications/03.png',
					  type: 'success',
					  position: 'bottom-right',
					  closeTimeout: 4000
					})).show();
			}else{
					(new GrowlNotification({
					  title: 'Attention!',
					  description: 'Erreur lors du chargement du tableau',
					  image: 'img/notifications/04.png',
					  type: 'error',
					  position: 'bottom-right',
					  closeTimeout: 4000
					})).show();
			
			}
		});
  var table_doc = $('#table_doc_acide').dataTable({
	"bStateSave": true,
	"fnStateSave": function (oSettings, oData) {
		localStorage.setItem( 'DataTables_'+window.location.pathname, JSON.stringify(oData) );
	},
	"fnStateLoad": function (oSettings) {
		return JSON.parse( localStorage.getItem('DataTables_'+window.location.pathname) );
	},
    "ajax": "module/acide_autre/table/php/data_doc_acide.php?job=get_doc_admin_acide",
    "columns": [
      { "data": "nom_fichier",   "sClass": "company_name" },
	  { "data": "cat_fichier",   "sClass": "company_name" },	  
	  { "data": "equipe",   "sClass": "company_name" },
      { "data": "user_fichier",   "sClass": "company_name" },
	  { "data": "statut_fichier",   "sClass": "company_name" },	  
	  { "data": "down",   "sClass": "company_name" },
	  { "data": "up",   "sClass": "company_name" },
	  { "data": "traitement",   "sClass": "company_name" },
	  { "data": "jh",   "sClass": "company_name" },
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
    "oLanguage": {
      "oPaginate": {
        "sFirst":       "<<",
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
  
  
  $(document).on('click', 'a#del', function(e){
		e.preventDefault();
		var id = $(this).data('id');
		var doc_up = $(this).data('doc');
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
						url:          'module/acide_autre/table/php/data_doc_acide.php?job=delete_doc_acide&id=' + id + '&cat=' + doc_up,
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
  
  $('#objectif').on('click', function () {
	
	$.confirm({
	title: ' NB Heures',
	content: '' +
	'<form action="" class="formName">' +	
	
	'<div class="form-group">' +
	'<label>Heures de travail</label><br>' +
	'<span class="text-danger"></span><br>' +
	'<input type="number" placeholder="Champ numérique" class="name form-control" id="input-heure" required />' +
	'</div>' +
	
	'</form>',
	draggable: false,
	closeIcon: true,
	buttons: {
	sayMyName: {
	text: 'Enregistrement',
	btnClass: 'btn-success',
	action: function () {
	var input_ligne = this.$content.find('input#input-ligne');
	var input_heure = this.$content.find('input#input-heure');
	var input_section = this.$content.find('input#input-section');
	var errorText = this.$content.find('.text-danger');
	if (input_ligne.val() == '' || input_heure.val() == '') {
	errorText.html('Champs obligatoires !!').slideDown(200);
	return false;
	} else {
	var request = $.ajax({
	url:          'module/acide_autre/table/data/objectif-table.php?nbheure=' + input_heure.val(),
	cache:        false,
	dataType:     'json',
	contentType:  'application/json; charset=utf-8',
	type:         'get'
	});
	window.location.reload(this, true);
	}
	}
	},
	later: {
	text: 'Annuler'
	
	}
	}
	});
	});
	

});
}(jQuery);