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
	var web = $('#table_webs_accomp_details').attr('data-id');
	var tri = $('#table_webs_accomp_details').attr('data-tri');
  	var table_accomp = $('#table_webs_accomp_details').dataTable({
  	"bStateSave": true,	
    "ajax": "module/webmaster/table/php/data_gestion_webs_accomp.php?job=get_rapport_acommp_details&web=" + web + "&tri=" + tri,	
    "columns": [
		
	  { "data": "type","sClass": "" },
	  { "data": "erreur","sClass": "" },
	  { "data": "constat","sClass": "" },
	  { "data": "axe","sClass": "" },
	  { "data": "date","sClass": "" },
	  { "data": "manger","sClass": "" },
	  { "data": "inpact","sClass": "" },
	  { "data": "fonction","sClass": "" }
	  
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



  $(document).on('click', '#function_edit_web', function(e){
    e.preventDefault();
    show_loading_message();
    var id      = $(this).data('id');
    var request = $.ajax({
      url:          'module/webmaster/table/php/data_gestion_webs_accomp.php?job=add_err_modif',
      cache:        false,
      data:         'id=' + id,
      dataType:     'json',
      contentType:  'application/json; charset=utf-8',
      type:         'get'
    });
    request.done(function(output){
      if (output.result == 'success'){
		
		
        $('.lightbox_content h2').text('Modification de l\'entrée plan d\'accompagnement');
        $('#form_company button').text('Enregistrement de la fiche');
        $('#form_company').attr('class', 'form edit');
        $('#form_company').attr('data-id', id);
        $('#form_company .field_container label.error').hide();
        $('#form_company .field_container').removeClass('valid').removeClass('error');
		
		$("#form_company #web option").filter(function() {
        return $(this).val() == output.data[0].web; 
        }).prop('selected', true);

        $("#form_company #err option").filter(function() {
        return $(this).val() == output.data[0].err; 
        }).prop('selected', true);

        $("#form_company #tri option").filter(function() {
        return $(this).val() == output.data[0].tri; 
        }).prop('selected', true);
		
		$('#form_company #date').val(output.data[0].date);
		$('#form_company #errtxt').val(output.data[0].errtxt);
		$('#form_company #constat').val(output.data[0].constat);
		$('#form_company #axe').val(output.data[0].axe);
		
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
  
  $(document).on('submit', '#form_company.edit', function(e){
    e.preventDefault();
    if (form_company.valid() == true){
      hide_ipad_keyboard();
      hide_lightbox();
      show_loading_message(); 
		
      var id        = $('#form_company').attr('data-id');
      var form_data = $('#form_company').serialize();
      var request   = $.ajax({
        url:          'module/webmaster/table/php/data_gestion_webs_accomp.php?job=modif_err&id=' + id,
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
            show_message("Rapport modifié avec succés.", 'success');
          }, true);
        } else {
          hide_loading_message();
          show_message('Edit request failed', 'error');
        }
      });
      request.fail(function(jqXHR, textStatus){
        hide_loading_message();
        show_message('Edit request failed : ' + textStatus, 'error');
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
						url:          'module/webmaster/table/php/data_gestion_webs_accomp.php?job=delete_err&id=' + id,
						cache:        false,
						dataType:     'json',
						contentType:  'application/json; charset=utf-8',
						type:         'get'
						});
						request.done(function(output){
						if (output.result == 'success'){
						  table_accomp.api().ajax.reload(function(){
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
						show_message("Une erreur s'est produite lors de l'enregistrement " + textStatus, 'error');
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