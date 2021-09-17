$(document).ready(function(){

   var id_import = $('#table_traitement').attr('data-id');
  var name_user = $('#table_traitement').attr('data-name');
  var id_user = $('#table_traitement').attr('data-ide');
  var mode_page = $('#table_traitement').attr('data-mode');
  
  var table_companies = $('#table_traitement').dataTable({
	 "bStateSave": true,
	 /*"stateSaveCallback": function (settings, data) {
		// Send an Ajax request to the server with the state object
		$.ajax( {
			"url": "table/dbManager.php?action=save",
			"data": {"name":"myKey", "state": data} ,//you can use the id of the datatable as key if it's unique
			"dataType": "json",
			"type": "POST",
			"success": function () {}
		} );
	},
	"stateLoadCallback": function (settings) {
		var o;

		// Send an Ajax request to the server to get the data. Note that
		// this is a synchronous request since the data is expected back from the
		// function
		$.ajax( {
			"url": "table/dbManager.php?action=load",
			"data":{"name":"myKey"},
			"async": false,
			"dataType": "json",
			"type": "POST",
			"success": function (json) {
				o = json;
			}
		} );

		return o;
	},*/	
    "ajax": "module/hb/table/php/data_acide_traitement.php?job=get_traitement&id_import=" + id_import + "&name_user=" + name_user +"&id_user=" + id_user +"&mode=" + mode_page,
    "columns": [

    { "data": "alerte",      "sClass": "" },
		{ "data": "functions",      "sClass": "functions" },
    { "data": "correction", "sClass": "company_name" },
    { "data": "telephone", "sClass": "company_name" },

    { "data": "raison", "sClass": "company_name" },
    { "data": "ville", "sClass": "company_name" },
    { "data": "siret", "sClass": "company_name" },
    { "data": "idc", "sClass": "company_name" },

    { "data": "civilite", "sClass": "company_name" },
    { "data": "nom", "sClass": "company_name" },
    { "data": "prenom", "sClass": "company_name" },
    { "data": "ids", "sClass": "company_name" },
    { "data": "fonction", "sClass": "company_name" },

    { "data": "fonctionexacte", "sClass": "company_name" },
    { "data": "email", "sClass": "company_name" },
      
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
	  "sLoadingRecords": "Chargement en cours des données en cours ..."
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
  /*$(document).keyup(function(e){
    if (e.keyCode == 27){
      hide_lightbox();
    }
  });*/
  
  function hide_ipad_keyboard(){
    document.activeElement.blur();
    $('input').blur();
  }

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
      $('.lightbox_content h2').text('Nouvelle ligne/Fiche :');
      $('#form_company button').text('ENREGISTREMENT');
      $('#form_company').attr('class', 'form add');
      $('#form_company').attr('data-id', '');
      $('#form_company .field_container label.error').hide();
      $('#form_company .field_container').removeClass('valid').removeClass('error');

        $('#form_company #raison').val('X');

        $('#form_company #newraison').val('');

        $('#form_company #siret').val('X');

        $('#form_company #newsiret').val('');

        $('#form_company #nomprenom').val('X');

        $('#form_company #newprenom').val('');

        $('#form_company #newnom').val('');

        $('#form_company #newfonction').val('');

        $('#form_company #fonction').val('X');

        $('#form_company #newtitle').val('');

        $('#form_company #email').val('X');

        $('#form_company #reporting').val('');
          
        $('#form_company #correctemail').val('');

        $('#form_company #phone').val('');

        $('#form_company #debut').val(dateTime);

        $('#form_company #user').val();

        $('#form_company #lot').val();

        $('#form_company #commentaire').val('X');

        $('#form_company #commentaire_collab').val('');


    show_lightbox();
  });

  $(document).on('submit', '#form_company.add', function(e){
    e.preventDefault();
    if (form_company.valid() == true){
      hide_ipad_keyboard();
      hide_lightbox();
      /*show_loading_message();*/
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
        url:          'module/hb/table/php/data_acide_traitement.php?job=add_traitement&fin='+ dateTime_fin,
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
            var campagne = $('#raison').val();
            show_message("Fiche '" + campagne + "' ajouter avec succées.", 'success');
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
      url:          'module/hb/table/php/data_acide_traitement.php?job=get_traitement_add',
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
        $('.lightbox_content h2').text('Fiche HB n° : '+ id);
        $('#form_company button').text('ENREGISTREMENT');
        $('#form_company').attr('class', 'form edit');
        $('#form_company').attr('data-id', id);
        $('#form_company .field_container label.error').hide();
        $('#form_company .field_container').removeClass('valid').removeClass('error');

        $('#form_company #raison').val(output.data[0].raison);

        $('#form_company #newraison').val(output.data[0].newraison);

        $('#form_company #siret').val(output.data[0].siret);

        $('#form_company #newsiret').val(output.data[0].newsiret);

        $('#form_company #nomprenom').val(output.data[0].nomprenom);

        $('#form_company #newprenom').val(output.data[0].newprenom);

        $('#form_company #newnom').val(output.data[0].newnom);

        $('#form_company #newfonction').val(output.data[0].newfonction);

        $('#form_company #fonction').val(output.data[0].fonction);

        $("#form_company #newtitle option").filter(function() {
          return $(this).val() == output.data[0].newtitle; 
        }).prop('selected', true);

        $('#form_company #email').val(output.data[0].email);

        $("#form_company #reporting option").filter(function() {
          return $(this).val() == output.data[0].reporting; 
        }).prop('selected', true);
          
        $('#form_company #correctemail').val(output.data[0].correctemail);

        $('#form_company #phone').val(output.data[0].phone);

    		$('#form_company #debut').val(dateTime);

    		$('#form_company #user').val();

    		$('#form_company #lot').val();

    		$('#form_company #commentaire').val(output.data[0].commentaire);

        $('#form_company #commentaire_collab').val(output.data[0].commentaire_collab);
		
		    /*$("#form_company #reporting").change(function () {
                                                 
          var str = "";
          $("#form_company #reporting option:selected").each(function () {
                str += $(this).val();
              });
           
          if(str == 3){
                $('#form_company #newe').attr('disabled', false);
				$('#form_company #siret').attr('disabled', false);
				$('#form_company #nt').attr('disabled', false);
          }
          else{
              	$('#form_company #newe').attr('disabled', true);
				$('#form_company #siret').attr('disabled', true);
				$('#form_company #nt').attr('disabled', true);
          }
        });
		
		  if(output.data[0].reporting == 3){
  		$('#form_company #newe').attr('disabled', false);
  				$('#form_company #siret').attr('disabled', false);
  				$('#form_company #nt').attr('disabled', false);
  		}else{
  				$('#form_company #newe').attr('disabled', true);
				$('#form_company #siret').attr('disabled', true);
				$('#form_company #nt').attr('disabled', true);
			}*/

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
      /*show_loading_message();*/
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
        url:          'module/hb/table/php/data_acide_traitement.php?job=edit_traitement&id=' + id + '&fin='+ dateTime_fin,
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
          show_message('Une erreur s\'est produite lors de l\'enregistrement', 'error');
        }
      });
      request.fail(function(jqXHR, textStatus){
        hide_loading_message();
        show_message('Une erreur s\'est produite lors de l\'enregistrement: ' + textStatus, 'error');
      });
    }
  }); 

});