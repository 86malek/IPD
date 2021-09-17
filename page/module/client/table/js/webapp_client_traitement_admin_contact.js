! function(t) {
    "use strict";
$(document).ready(function(){
	var id_cat_mere = $('#table_traitement').attr('data-idcatt');
  var id_import = $('#table_traitement').attr('data-id');
  var table_companies = $('#table_traitement').dataTable({
	"bStateSave": true,
		
    "ajax": "module/client/table/php/data_client_traitement.php?job=get_traitement_admin_contact&id_import=" + id_import +"&id_cat_mere=" + id_cat_mere,
    "columns": [

    { "data": "contact", "sClass": "" },
    
    { "data": "rs", "sClass": "" },
    { "data": "ad1", "sClass": "" },
    { "data": "ad2", "sClass": "" },
    { "data": "ad3", "sClass": "" },
    { "data": "cp", "sClass": "" },
    { "data": "ville", "sClass": "" },
    { "data": "tel", "sClass": "" },
    { "data": "fax", "sClass": "" },
    { "data": "siret", "sClass": "" },
    { "data": "es", "sClass": "" },
    { "data": "eg", "sClass": "" },
    { "data": "ca", "sClass": "" },

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
    
    "oLanguage": {
      "oPaginate": {
        "sFirst":       "<<",
        "sPrevious":    "Précédent",
        "sNext":        "Suivant",
        "sLast":        ">>",
      },
      "sLengthMenu":    "Cartes par page : _MENU_",
      "sInfo":          "Total de _TOTAL_ Cartes (Affichage _START_ à _END_)",
	  "sSearch":          "Recherche : ",
      "sInfoFiltered":  "(Filtré depuis _MAX_ total Cartes)",
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
      url:          'module/client/table/php/data_client_traitement.php?job=get_traitement_add_admin',
      cache:        false,
      data:         'id=' + id,
      dataType:     'json',
      contentType:  'application/json; charset=utf-8',
      type:         'get'
    });
    request.done(function(output){
      if (output.result == 'success'){
        $('.lightbox_content h2').text('TRAITEMENT LIGNES (ADMIN)');
        $('#form_company button').text('ENREGISTREMENT');
        $('#form_company').attr('class', 'form edit');
        $('#form_company').attr('data-id', id);
        $('#form_company .field_container label.error').hide();
        $('#form_company .field_container').removeClass('valid').removeClass('error');
        $('#form_company #rs_o').val(output.data[0].rs_o);
    $('#form_company #ad1_o').val(output.data[0].ad1_o);
    $('#form_company #ad2_o').val(output.data[0].ad2_o);
    $('#form_company #ad3_o').val(output.data[0].ad3_o);
    $('#form_company #cp_o').val(output.data[0].cp_o);
    $('#form_company #ville_o').val(output.data[0].ville_o);
    $('#form_company #tel_o').val(output.data[0].tel_o);
    $('#form_company #fax_o').val(output.data[0].fax_o);
    $('#form_company #siret_o').val(output.data[0].siret_o);
    $('#form_company #esite_o').val(output.data[0].esite_o);
    $('#form_company #egroupe_o').val(output.data[0].egroupe_o);
    $('#form_company #ca_o').val(output.data[0].ca_o);    
    
    $('#form_company #rs').val(output.data[0].rs);
    $('#form_company #ad1').val(output.data[0].ad1);
    $('#form_company #ad2').val(output.data[0].ad2);
    $('#form_company #ad33').val(output.data[0].ad33);
    $('#form_company #cp').val(output.data[0].cp);
    $('#form_company #ville').val(output.data[0].ville);
    $('#form_company #tel').val(output.data[0].tel);
    $('#form_company #fax').val(output.data[0].fax);
    $('#form_company #siret').val(output.data[0].siret);
    $('#form_company #esite').val(output.data[0].esite);
    $('#form_company #egroupe').val(output.data[0].egroupe);
    $('#form_company #enat').val(output.data[0].enat);
    $('#form_company #ca').val(output.data[0].ca);
    $('#form_company #catt').val(output.data[0].catt);
	
	$('#form_company #t1').val(output.data[0].t1);		
		$('#form_company #t2').val(output.data[0].t2);
		$('#form_company #t3').val(output.data[0].t3);
		$('#form_company #stat').val(output.data[0].stat);
    
    
    
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
        url:          'module/client/table/php/data_client_traitement.php?job=edit_traitement_admin&id=' + id,
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
			content: "url:module/client/table/data/mood-table.php?id_stat=" + temps_reel,
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