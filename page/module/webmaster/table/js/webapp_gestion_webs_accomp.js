! function(t) {
    "use strict";
$(document).ready(function(){
  
  var table_accomp = $('#table_webs_accomp').dataTable({
  	"bStateSave": true,	
    "ajax": "module/webmaster/table/php/data_gestion_webs_accomp.php?job=get_rapport_acommp_general",	
    "columns": [
		
	  { "data": "webs","sClass": "" },
	  { "data": "tri","sClass": "" },	  
	  { "data": "prime","sClass": "" },
    { "data": "point","sClass": "" },
	  { "data": "suivi","sClass": "" }
	  
	  
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
        "sFirst":       "<<",
        "sPrevious":    "Précédent",
        "sNext":        "Suivant",
        "sLast":        ">>",
      },
      "sLengthMenu":    "Lignes par page : _MENU_",
      "sInfo":          "Total de _TOTAL_ Lignes (Affichage _START_ à _END_)",
	  "sSearch":        "Recherche",
      "sInfoFiltered":  "(Filtré depuis _MAX_ total Lignes)",
	  "sLoadingRecords": "Chargement en cours des données ..."
    }
  });

$(document).on('click', '#refresh', function(e){
	table_accomp.api().ajax.reload(function(){
	hide_loading_message();
	show_message("Rafraîchissement des lignes terminé", 'success');
	}, true);
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
	
  $(document).on('click', '#add_rapport', function(e){
    e.preventDefault();		
      $('.lightbox_content h2').text("Nouvelle entrée plan d'accompagnement :");
      $('#form_company button').text('Enregistrement de la fiche');

      $('#form_company').attr('class', 'form add');
      $('#form_company').attr('data-id', '');

      $('#form_company .field_container label.error').hide();
      $('#form_company .field_container').removeClass('valid').removeClass('error');

      $('#form_company #web').val('');
      $('#form_company #err').val('');
      $('#form_company #tri').val('');
      $('#form_company #date').val('');
      $('#form_company #constat').val('');
      $('#form_company #axe').val('');
      $('#form_company #errtxt').val('');

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
        url:          'module/webmaster/table/php/data_gestion_webs_accomp.php?job=add_err',
        cache:        false,
        data:         form_data,
        dataType:     'json',
        contentType:  'application/json; charset=utf-8',
        type:         'get'
      });
      request.done(function(output){
        if (output.result == 'success'){
          table_accomp.api().ajax.reload(function(){
            hide_loading_message();
            var campagne = $('#campagne').val();
            show_message("Erreur '" + campagne + "' ajoutée avec succés.", 'success');
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



});
}(jQuery);