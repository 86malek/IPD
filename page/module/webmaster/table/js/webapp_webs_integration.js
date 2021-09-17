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
  var id_import = $('#table_traitement_rapport').attr('data-id');
  var table_companies = $('#table_traitement_rapport').dataTable({
    "ajax": "module/webmaster/table/php/data_webs_integration.php?job=get_webs_integration&id_import=" + id_import,
    "columns": [
		{ "data": "id", "sClass": "", "sType": "date-uk" },
	  { "data": "campagne", "sClass": "" },
	  { "data": "type", "sClass": "" },
      { "data": "debut", "sClass": "" },
	  { "data": "fin", "sClass": "" },
	  { "data": "temps", "sClass": "" },
	  { "data": "comm", "sClass": "" },
      { "data": "functions", "sClass": "" }
    ],
    dom: 'Bfrtip',
	"buttons": [
            'colvis'
        ],
		 "order": [[ 0, "ASC" ]],
    "oLanguage": {
      "oPaginate": {
        "sFirst":       "<<",
        "sPrevious":    "Précédent",
        "sNext":        "Suivant",
        "sLast":        ">>",
      },
      "sLengthMenu":    "Ligne par page : _MENU_",
      "sInfo":          "Total de _TOTAL_ Ligne (Affichage _START_ à _END_)",
	  "sSearch":          "Recherche : ",
      "sInfoFiltered":  "(Filtré depuis _MAX_ total Ligne)",
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
		show_message("Rafraîchissement des Rapports terminé", 'success');
		}, true);
	});
	
  $(document).on('click', '#add_rapport', function(e){
    e.preventDefault();		
      $('.lightbox_content h2').text('Nouveau Rapport');
      $('#form_company button').text('Enregistrement');
      $('#form_company').attr('class', 'form add');
      $('#form_company').attr('data-id', '');
      $('#form_company .field_container label.error').hide();
      $('#form_company .field_container').removeClass('valid').removeClass('error');
      $('#form_company #type').val('');
      $('#form_company #campagne').val('');
      $('#form_company #debut').val('');
      $('#form_company #fin').val('');
      $('#form_company #comm').val('');
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
        url:          'module/webmaster/table/php/data_webs_integration.php?job=add_webs_integration',
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
            var campagne = $('#campagne').val();
            show_message("Rapport '" + campagne + "' ajouter avec succés.", 'success');
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

  $(document).on('click', '#function_edit_web', function(e){
    e.preventDefault();
    show_loading_message();
    var id      = $(this).data('id');
    var request = $.ajax({
      url:          'module/webmaster/table/php/data_webs_integration.php?job=get_add_webs_integration',
      cache:        false,
      data:         'id=' + id,
      dataType:     'json',
      contentType:  'application/json; charset=utf-8',
      type:         'get'
    });
    request.done(function(output){
      if (output.result == 'success'){
		
		
        $('.lightbox_content h2').text('MODICIATION FICHE INTÉGRATION');
        $('#form_company button').text('ENREGISTREMENT');
        $('#form_company').attr('class', 'form edit');
        $('#form_company').attr('data-id', id);
        $('#form_company .field_container label.error').hide();
        $('#form_company .field_container').removeClass('valid').removeClass('error');
		
		$("#form_company #type option").filter(function() {
        return $(this).val() == output.data[0].type; 
        }).prop('selected', true);
		
		$('#form_company #campagne').val(output.data[0].campagne);
		$('#form_company #debut').val(output.data[0].debut);
		$('#form_company #fin').val(output.data[0].fin);
		$('#form_company #comm').val(output.data[0].comm);
		
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
        url:          'module/webmaster/table/php/data_webs_integration.php?job=edit_webs_integration&id=' + id,
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
            show_message("Rapport modifié avec succés.", 'success');
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
			content: "Confirmation de supprission du rapport ",
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
						url:          'module/webmaster/table/php/data_webs_integration.php?job=delete_webs_integration&id=' + id,
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
							show_message("Rapport effacé avec succès.", 'success');
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