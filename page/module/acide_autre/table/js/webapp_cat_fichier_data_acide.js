! function(t) {
    "use strict";
$(document).ready(function(){
  
  var table_cat_fichier = $('#table_cat_fichier').dataTable({
	"bStateSave": true,
	"fnStateSave": function (oSettings, oData) {
		localStorage.setItem( 'DataTables_'+window.location.pathname, JSON.stringify(oData) );
	},
	"fnStateLoad": function (oSettings) {
		return JSON.parse( localStorage.getItem('DataTables_'+window.location.pathname) );
	},
    "ajax": "module/acide_autre/table/php/data_cat_fichier_data_acide.php?job=get_cat_fichier",
    "columns": [
      { "data": "nom",   "sClass": "nom" },
      { "data": "functions",      "sClass": "functions" }
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
      "sLengthMenu":    "Catégories par page : _MENU_",
      "sInfo":          "Total de _TOTAL_ Catégories (Affichage _START_ à _END_)",
	  "sSearch":          "Recherche",
      "sInfoFiltered":  "(Filtré depuis _MAX_ total Catégories)",
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

  $(document).on('click', '#add_cat_fichier', function(e){
    e.preventDefault();
    $('.lightbox_container h2').text('Formulaire ajout de catégorie');
    $('#form_cat_fichier button').text('Enregistrement');
    $('#form_cat_fichier').attr('class', 'form add');
    $('#form_cat_fichier').attr('data-id', '');
    $('#form_cat_fichier .field_container label.error').hide();
    $('#form_cat_fichier .field_container').removeClass('valid').removeClass('error');
    $('#form_cat_fichier #nom').val('');
    show_lightbox();
  });

  $(document).on('submit', '#form_cat_fichier.add', function(e){
    e.preventDefault();
    if (form_company.valid() == true){
      hide_ipad_keyboard();
      hide_lightbox();
      show_loading_message();
      var form_data = $('#form_cat_fichier').serialize();
      var request   = $.ajax({
        url:          'module/acide_autre/table/php/data_cat_fichier_data_acide.php?job=add_cat_fichier',
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
            show_message("Catégorie '" + cat_name + "' ajoutée avec succés.", 'success');
          }, true);
        } else {
          hide_loading_message();
          show_message('Échec comunication base de données SQL', 'error');
        }
      });
      request.fail(function(jqXHR, textStatus){
        hide_loading_message();
        show_message('Échec comunication base de données SQL' + textStatus, 'error');
      });
    }
  });

  $(document).on('click', '#function_edit_cat_fichier', function(e){
    e.preventDefault();
    show_loading_message();
    var id      = $(this).data('id');
    var request = $.ajax({
      url:          'module/acide_autre/table/php/data_cat_fichier_data_acide.php?job=get_cat_fichier_add',
      cache:        false,
      data:         'id=' + id,
      dataType:     'json',
      contentType:  'application/json; charset=utf-8',
      type:         'get'
    });
    request.done(function(output){
      if (output.result == 'success'){
        $('.lightbox_content h2').text('Formulaire Modification de catégorie');
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
        show_message('Échec comunication base de données SQL', 'error');
      }
    });
    request.fail(function(jqXHR, textStatus){
      hide_loading_message();
      show_message('Échec comunication base de données SQL ' + textStatus, 'error');
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
        url:          'module/acide_autre/table/php/data_cat_fichier_data_acide.php?job=edit_cat_fichier&id=' + id,
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
            show_message("Catégorie '" + cat_name + "' modifiée avec succés.", 'success');
          }, true);
        } else {
          hide_loading_message();
          show_message('Échec comunication base de données SQL', 'error');
        }
      });
      request.fail(function(jqXHR, textStatus){
        hide_loading_message();
        show_message('Échec comunication base de données SQL' + textStatus, 'error');
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
						url:          'module/acide_autre/table/php/data_cat_fichier_data_acide.php?job=delete_cat_fichier&id=' + id + '&cat=' + doc_up,
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
