! function(t) {
    "use strict";
$(document).ready(function(){

  var id_import = $('#table_traitement').attr('data-id');
  var name_user = $('#table_traitement').attr('data-name');
  var id_user = $('#table_traitement').attr('data-ide');
  
  var table_companies = $('#table_traitement').dataTable({
	 "bStateSave": true,
	  "ajax": "module/client/table/php/data_client_traitement.php?job=get_traitement_contact_admin&id_import=" + id_import,
    "columns": [
	
		{ "data": "alerte",      "sClass": "" },
		{ "data": "rs", "sClass": "" },
		
		{ "data": "civ", "sClass": "" },
		{ "data": "prenom", "sClass": "" },
		{ "data": "nom", "sClass": "" },
		{ "data": "fonctione", "sClass": "" },
		{ "data": "email", "sClass": "" },
		{ "data": "fonction", "sClass": "" },
		{ "data": "collab", "sClass": "" },
		{ "data": "temps", "sClass": "" },
		{ "data": "mood", "sClass": "" }
	  
      
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
    "order": [[ 1, "desc" ]],
    "oLanguage": {
      "oPaginate": {
        "sFirst":       "<<",
        "sPrevious":    "Précédent",
        "sNext":        "Suivant",
        "sLast":        ">>",
      },
      "sLengthMenu":    "Contacts par page : _MENU_",
      "sInfo":          "Contacts de _TOTAL_ Lignes (Contacts _START_ à _END_)",
	  "sSearch":          "Recherche : ",
      "sInfoFiltered":  "(Filtré depuis _MAX_ total Contacts)",
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

  

  $(document).on('click', '#function_edit_web', function(e){
    e.preventDefault();
    show_loading_message();
    var id      = $(this).data('id');
    var request = $.ajax({
      url:          'module/client/table/php/data_client_traitement.php?job=get_traitement_add_contact_admin',
      cache:        false,
      data:         'id=' + id,
      dataType:     'json',
      contentType:  'application/json; charset=utf-8',
      type:         'get'
    });
	
    request.done(function(output){

      if (output.result == 'success'){

			  
        $('.lightbox_content h2').text('TRAITEMENT fiche Client (ADMIN)');
        $('#form_company button').text('ENREGISTREMENT');
        $('#form_company').attr('class', 'form edit');
        $('#form_company').attr('data-id', id);
        $('#form_company .field_container label.error').hide();		
        $('#form_company .field_container').removeClass('valid').removeClass('error');
		
			
		$('#form_company #rs').val(output.data[0].rs);
		$('#form_company #nsiret').val(output.data[0].siret);
		$('#form_company #ad').val(output.data[0].ad);
		$('#form_company #cp').val(output.data[0].cp);
		$('#form_company #ville').val(output.data[0].ville);
		$('#form_company #tel').val(output.data[0].tel);
		$('#form_company #tel2').val(output.data[0].tel2);	
		
		$('#form_company #civ_o').val(output.data[0].civ_o);	
		$("#form_company #civ option").filter(function() {
		return $(this).val() == output.data[0].civ; 
		}).prop('selected', true);
		
		$("#form_company #service option").filter(function() {
		return $(this).val() == output.data[0].service; 
		}).prop('selected', true);
		
		$('#form_company #prenom').val(output.data[0].prenom);
		$('#form_company #prenom_o').val(output.data[0].prenom_o);
		$('#form_company #nom').val(output.data[0].nom);
		$('#form_company #nom_o').val(output.data[0].nom_o);
		$('#form_company #fonction_o').val(output.data[0].fonction_o);
		$('#form_company #fc').val(output.data[0].fc);
		$('#form_company #email_o').val(output.data[0].email_o);
		$('#form_company #email').val(output.data[0].email);
		$('#form_company #lk').val(output.data[0].lk);
		
		$("#form_company #stat option").filter(function() {
		return $(this).val() == output.data[0].stat; 
		}).prop('selected', true);
		
		$('#form_company #commentaire_collab').val(output.data[0].commentaire_collab);
		
		$("#form_company #type option").filter(function() {
		return $(this).val() == output.data[0].type; 
		}).prop('selected', true);
		
		
		
		$('#form_company #commentaire').val(output.data[0].commentaire);

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
      /*show_loading_message();*/
	  

      var id        = $('#form_company').attr('data-id');
      var form_data = $('#form_company').serialize();
      var request   = $.ajax({
        url:          'module/client/table/php/data_client_traitement.php?job=edit_traitement_contact_admin&id=' + id,
        cache:        true,
        data:         form_data,
        dataType:     'json',
        contentType:  'application/json; charset=utf-8',
        type:         'get'
      });
      request.done(function(output){
        if (output.result == 'success'){
			window.location.reload(function(){
				table_companies.api().ajax.reload;
				/*hide_loading_message();*/
				/*var company_name = $('#raison').val();
				show_message("Opération '" + company_name + "' modifiée avec succés.", 'success');*/			
			}, true); 
			
			/*table_companies.api().ajax.reload(function(){				
				hide_loading_message();
				var company_name = $('#raison').val();
				show_message("Opération '" + company_name + "' modifiée avec succés.", 'success');			
			}, true); */        
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
  
  $(document).on('click', '#mood_affichage', function(){
      var temps_reel      = $(this).data('id');
		t.dialog({
			title: "Nb de modifications",
			content: "url:module/client/table/data/mood-table_2.php?id_stat=" + temps_reel,
			animation: 'zoom',
			columnClass: 'medium',
			closeAnimation: 'scale',
			backgroundDismiss: false,
			closeIcon: true,
			draggable: false
		  });
    });
	
});

}(jQuery);