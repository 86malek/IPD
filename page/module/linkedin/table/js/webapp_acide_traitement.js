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
    "ajax": "module/linkedin/table/php/data_acide_traitement.php?job=get_traitement&id_import=" + id_import + "&name_user=" + name_user +"&id_user=" + id_user +"&mode=" + mode_page,
    "columns": [
	{ "data": "alerte",      "sClass": "" },
		{ "data": "functions",      "sClass": "functions" },
      { "data": "raison", "sClass": "company_name" },
	  { "data": "nom", "sClass": "company_name" },
	  { "data": "prenom", "sClass": "company_name" },
	  { "data": "urllinkedin", "sClass": "company_name" },
	  { "data": "newposte", "sClass": "company_name" },
	  { "data": "oldposte", "sClass": "company_name" },
	  { "data": "newentreprise", "sClass": "company_name" },
	  { "data": "oldentreprise", "sClass": "company_name" },
	  { "data": "nt", "sClass": "company_name" },
	  { "data": "siret", "sClass": "company_name" }
      
    ],
    dom: 'Bfrtip',
	"buttons": [
            'colvis'
        ],
    "order": [[ 1, "desc" ]],
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
      url:          'module/linkedin/table/php/data_acide_traitement.php?job=get_traitement_add',
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
        $('.lightbox_content h2').text('TRAITEMENT FICHIER LK');
        $('#form_company button').text('ENREGISTREMENT');
        $('#form_company').attr('class', 'form edit');
        $('#form_company').attr('data-id', id);
        $('#form_company .field_container label.error').hide();
        $('#form_company .field_container').removeClass('valid').removeClass('error');
        $('#form_company #raison').val(output.data[0].raison);
		$('#form_company #nom').val(output.data[0].nom);
		$('#form_company #prenom').val(output.data[0].prenom);
		$('#form_company #newe').val(output.data[0].newe);
		$('#form_company #url').val(output.data[0].url);
		$('#form_company #ville').val(output.data[0].ville);
		$('#form_company #cp').val(output.data[0].cp);
		$('#form_company #ids').val(output.data[0].ids);
		$('#form_company #debut').val(dateTime);
		$('#form_company #user').val();
		$('#form_company #lot').val();
		$('#form_company #commentaire').val(output.data[0].commentaire);
		
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
			
		$('#form_company #siret').val(output.data[0].siret);
		$('#form_company #nfonction').val(output.data[0].nfonction);
			
		
		$("#form_company #reporting option").filter(function() {
			return $(this).val() == output.data[0].reporting; 
		}).prop('selected', true);
		
		$("#form_company #title option").filter(function() {
			return $(this).val() == output.data[0].title; 
		}).prop('selected', true);
		
		$("#form_company #nt option").filter(function() {
			return $(this).val() == output.data[0].nt; 
		}).prop('selected', true);

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
        url:          'module/linkedin/table/php/data_acide_traitement.php?job=edit_traitement&id=' + id + '&fin='+ dateTime_fin,
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

});