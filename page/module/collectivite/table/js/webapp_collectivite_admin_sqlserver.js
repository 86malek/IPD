! function(t) {
    "use strict";
$(document).ready(function(){ 

	  	/*var request_data = $.ajax({
		url:          'module/collectivite/table/php/data_collectivite_lot_sqlserver.php?job=get_collect_liste_admin',
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
					  description: 'Erreur lors du chargement du tableau.',
					  image: 'img/notifications/04.png',
					  type: 'error',
					  position: 'bottom-right',
					  closeTimeout: 4000
					})).show();
			
			}
		});*/
		
  var table_doc = $('#table_collect').dataTable({
	
	"stateDuration": 60*60*24*365,
	
	stateSave: true,
	
	
    "ajax": "module/collectivite/table/php/data_collectivite_lot_sqlserver.php?job=get_collect_liste_admin",
    "columns": [
      { "data": "IdINT",   "sClass": "" },
	  { "data": "RS1",   "sClass": "" },	  
	  { "data": "Categorie",   "sClass": "" },
      { "data": "IntervalleMaj",   "sClass": "" },
	  { "data": "Population",   "sClass": "" },
	  { "data": "DateMaj",   "sClass": "" },	  
	  { "data": "DateAlerte",   "sClass": "" },
	  { "data": "LotPop",   "sClass": "" }
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
  
  
  
  
  
	

});
}(jQuery);