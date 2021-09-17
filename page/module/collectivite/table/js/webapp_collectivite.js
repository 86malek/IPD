(function ($) {
  'use strict';
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

  var id_import = $('#table_traitement').attr('data-id');
  var name_user = $('#table_traitement').attr('data-name');
  var id_user = $('#table_traitement').attr('data-ide');
  var mode_page = $('#table_traitement').attr('data-mode');

  var table_companies = $('#table_traitement').dataTable({
      
	"bStateSave": true,

  "ajax": "module/collectivite/table/php/data_collectivite_traitement.php?job=get_traitement&id_import=" + id_import + "&name_user=" + name_user +"&id_user=" + id_user +"&mode=" + mode_page,
  "columns": [
  { "data": "lot", "sClass": "" },
  { "data": "date", "sClass": "", "sType": "date-uk" },
  { "data": "identificateur", "sClass": "" },
  { "data": "maj", "sClass": "" },
  { "data": "functions",      "sClass": "" }
  ],
  dom: 'Bfrtip',

  "buttons": [
  'colvis'
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
  "sSearch":          "Recherche : ",
  "sInfoFiltered":  "(Filtré depuis _MAX_ total Lignes)",
  "sLoadingRecords": "Chargement des fiches en cours..."
  },
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
      url:          'module/collectivite/table/php/data_collectivite_traitement.php?job=get_traitement_add',
      cache:        false,
      data:         'id=' + id,
      dataType:     'json',
      contentType:  'application/json; charset=utf-8',
      type:         'get'
    });
	
	var now     = new Date(); 
    var year    = now.getFullYear();
    var month   = now.getMonth()+1; 
    var day     = now.getDate();
    var hour    = now.getHours();
    var minute  = now.getMinutes();
    var second  = now.getSeconds(); 
    if(month.toString().length == 1) {
        var month = '0'+month;
    }
    if(day.toString().length == 1) {
        var day = '0'+day;
    }   
    if(hour.toString().length == 1) {
        var hour = '0'+hour;
    }
    if(minute.toString().length == 1) {
        var minute = '0'+minute;
    }
    if(second.toString().length == 1) {
        var second = '0'+second;
    }   
    var dateTime = year+'-'+month+'-'+day+' '+hour+':'+minute+':'+second;
	
    request.done(function(output){
		
      if (output.result == 'success'){
		  
        $('.lightbox_content h2').text('Fiche de traitment numéro : '+output.data[0].fiche);
        $('#form_company button').text('Enregistrement');
        $('#form_company').attr('class', 'form edit');
        $('#form_company').attr('data-id', id);
        $('#form_company .field_container label.error').hide();
        $('#form_company .field_container').removeClass('valid').removeClass('error');

        $('#form_company #fiche').prop('disabled', true);
    		$('#form_company #user').val();    		
    		$('#form_company #user_id').val();
    		$('#form_company #fiche').val(output.data[0].fiche);
    		$('#form_company #lot').val(output.data[0].lot);    		
    		$('#form_company #collect_fiche_debut').val(dateTime);
    		
    		if(output.data[0].reporting == 0){
    		$("#form_company #reporting option").filter(function() {
    			return $(this).val() == ''; 
    		}).prop('selected', true);
    		}else{			
    		$("#form_company #reporting option").filter(function() {
    			return $(this).val() == output.data[0].reporting; 
    		}).attr('selected', 'selected');	
    		}
		
		
		
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
	  	var now     = new Date(); 
  		var year    = now.getFullYear();
  		var month   = now.getMonth()+1; 
  		var day     = now.getDate();
  		var hour    = now.getHours();
  		var minute  = now.getMinutes();
  		var second  = now.getSeconds(); 
  		if(month.toString().length == 1) {
  			var month = '0'+month;
  		}
  		if(day.toString().length == 1) {
  			var day = '0'+day;
  		}   
  		if(hour.toString().length == 1) {
  			var hour = '0'+hour;
  		}
  		if(minute.toString().length == 1) {
  			var minute = '0'+minute;
  		}
  		if(second.toString().length == 1) {
  			var second = '0'+second;
  		}   
  		var dateTime_fin = year+'-'+month+'-'+day+' '+hour+':'+minute+':'+second;
      var id        = $('#form_company').attr('data-id');
      var form_data = $('#form_company').serialize();
      var request   = $.ajax({
        url:          'module/collectivite/table/php/data_collectivite_traitement.php?job=edit_traitement&id=' + id +'&collect_fiche_fin='+ dateTime_fin,
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
  


  $(document).on('click', '#add_ligne', function(e){
    e.preventDefault();

      var now     = new Date(); 
      var year    = now.getFullYear();
      var month   = now.getMonth()+1; 
      var day     = now.getDate();
      var hour    = now.getHours();
      var minute  = now.getMinutes();
      var second  = now.getSeconds(); 
      if(month.toString().length == 1) {
          var month = '0'+month;
      }
      if(day.toString().length == 1) {
          var day = '0'+day;
      }   
      if(hour.toString().length == 1) {
          var hour = '0'+hour;
      }
      if(minute.toString().length == 1) {
          var minute = '0'+minute;
      }
      if(second.toString().length == 1) {
          var second = '0'+second;
      }   
      var dateTime = year+'-'+month+'-'+day+' '+hour+':'+minute+':'+second;

      

      $('.lightbox_content h2').text('Nouvelle Fiche :');
      $('#form_company button').text('ENREGISTREMENT');
      $('#form_company').attr('class', 'form add');
      $('#form_company').attr('data-id', '');
      $('#form_company .field_container label.error').hide();
      $('#form_company .field_container').removeClass('valid').removeClass('error');

        $('#form_company #user').val();       
        $('#form_company #user_id').val();
        $('#form_company #fiche').val('');
        $('#form_company #lot').val();
        $('#form_company #fiche').prop('disabled', false);
        $('#form_company #collect_fiche_debut').val(dateTime);
        $('#form_company #reporting').val('');

    show_lightbox();

  });

  $(document).on('submit', '#form_company.add', function(e){
    e.preventDefault();
    if (form_company.valid() == true){
      hide_ipad_keyboard();
      hide_lightbox();
      show_loading_message();   
      var now     = new Date(); 
      var year    = now.getFullYear();
      var month   = now.getMonth()+1; 
      var day     = now.getDate();
      var hour    = now.getHours();
      var minute  = now.getMinutes();
      var second  = now.getSeconds(); 
      if(month.toString().length == 1) {
      var month = '0'+month;
      }
      if(day.toString().length == 1) {
      var day = '0'+day;
      }   
      if(hour.toString().length == 1) {
      var hour = '0'+hour;
      }
      if(minute.toString().length == 1) {
      var minute = '0'+minute;
      }
      if(second.toString().length == 1) {
      var second = '0'+second;
      }   
      var dateTime_fin = year+'-'+month+'-'+day+' '+hour+':'+minute+':'+second;   
      var form_data = $('#form_company').serialize();
      var request   = $.ajax({
        url:          'module/collectivite/table/php/data_collectivite_traitement.php?job=add_traitement&fin='+ dateTime_fin,
        cache:        false,
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
        show_message("Une erreur s'est produite lors de l'enregistrement" + textStatus, 'error');
      });
    }
  });



});
})(jQuery);