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
	
	
  var table_cat_fichier = $('#table_cat_fichier').dataTable({
  	"bStateSave": true,
    "ajax": "module/ie/table/php/data_cat_fichier_ie.php?job=get_cat_fichier",
    "columns": [
	  { "data": "fichier","sClass": "" },
	  { "data": "statut","sClass": "" },
	  { "data": "total","sClass": "" },
	  { "data": "traite","sClass": "" },
	  { "data": "pourcent","sClass": "" },
	  { "data": "collab","sClass": "" },
	  { "data": "debut","sClass": "", "sType": "date-uk" },
	  { "data": "fin","sClass": "", "sType": "date-uk" },
	  { "data": "temps","sClass": "" },
	  { "data": "jh","sClass": "" },
      { "data": "functions","sClass": "" }
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
	  "sSearch":          "Recherche",
      "sInfoFiltered":  "(Filtré depuis _MAX_ total Lignes)",
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
  var form_company = $('#form_cat_fichier');
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
  function hide_lightbox_del(){
    $('#modal-confirm').hide();
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
		table_cat_fichier.api().ajax.reload(function(){
		hide_loading_message();
		show_message("Rafraîchissement terminé", 'success');
		}, true);
	});
	
	$(document).on(setInterval(function(){
		table_cat_fichier.api().ajax.reload(function(){
		hide_loading_message();
		}, true);
	}, 20000)
	);
	
  
  $(document).on('click', '#function_edit_cat_fichier', function(e){
    e.preventDefault();
    show_loading_message();
    var id      = $(this).data('id');
    var request = $.ajax({
      url:          'module/ie/table/php/data_cat_fichier_ie.php?job=get_cat_fichier_add',
      cache:        false,
      data:         'id=' + id,
      dataType:     'json',
      contentType:  'application/json; charset=utf-8',
      type:         'get'
    });
    request.done(function(output){
      if (output.result == 'success'){
        $('.lightbox_content h2').text('Modification du nom de dossier');
        $('#form_cat_fichier button').text('Enregistrement');
        $('#form_cat_fichier').attr('class', 'form edit');
        $('#form_cat_fichier').attr('data-id', id);
        $('#form_cat_fichier .field_container label.error').hide();
        $('#form_cat_fichier .field_container').removeClass('valid').removeClass('error');
        $('#form_cat_fichier #nom').val(output.data[0].nom);
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
  
  $(document).on('submit', '#form_cat_fichier.edit', function(e){
    e.preventDefault();
    if (form_company.valid() == true){
      hide_ipad_keyboard();
      hide_lightbox();
      show_loading_message();
      var id        = $('#form_cat_fichier').attr('data-id');
      var form_data = $('#form_cat_fichier').serialize();
      var request   = $.ajax({
        url:          'module/ie/table/php/data_cat_fichier_ie.php?job=edit_cat_fichier&id=' + id,
        cache:        false,
        data:         form_data,
        dataType:     'json',
        contentType:  'application/json; charset=utf-8',
        type:         'get'
      });
      request.done(function(output){
        if (output.result == 'success'){
          table_cat_fichier.api().ajax.reload(function(){
            hide_loading_message();
            var cat_name = $('#nom').val();
            show_message("Dossier '" + cat_name + "' modifié avec succés.", 'success');
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
  $(document).on('click', '#operateur_affichage', function(){
		var temps_reel      = $(this).data('id');
		t.dialog({
			title: "Opérateurs",
			content: "url:data/operateur-table.php?id_stat=" + temps_reel,
			animation: "zoom",
			columnClass: "medium",
			closeAnimation: "scale",
			backgroundDismiss: !0,
			closeIcon: !0,
			draggable: !1
		})
	});
	
    $('#objectif').on('click', function () {
	
		  $.confirm({
			title: 'Objectif journalier',
			content: '' +
			'<form action="" class="formName">' +
			'<input type="hidden" id="input-section" required value="1" />'+
			'<div class="form-group">' +
			'<label>Nombre de lignes</label><br>' +
			'<span class="text-danger"></span><br>' +
			'<input type="number" placeholder="Champ numérique" class="name form-control" id="input-ligne" required />' +
			'</div>' +
			
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
					url:          'module/ie/table/data/objectif-table.php?nbheure=' + input_heure.val() + '&nblignes=' + input_ligne.val() + '&section=' + input_section.val(),
					cache:        false,
					dataType:     'json',
					contentType:  'application/json; charset=utf-8',
					type:         'get'
					});
					//$.alert('Mise à jour terminée');
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
						url:          'module/ie/table/php/data_cat_fichier_ie.php?job=delete_cat_fichier&id=' + id + '&cat=' + doc_up,
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