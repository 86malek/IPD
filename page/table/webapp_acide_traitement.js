$(document).ready(function(){

  var id_import = $('#table_traitement').attr('data-id');
  var name_user = $('#table_traitement').attr('data-name');
  var mode_page = $('#table_traitement').attr('data-mode');
  var table_companies = $('#table_traitement').dataTable({
	 "bStateSave": true,
        "fnStateSave": function (oSettings, oData) {
            localStorage.setItem( 'DataTables_'+window.location.pathname, JSON.stringify(oData) );
        },
        "fnStateLoad": function (oSettings) {
            return JSON.parse( localStorage.getItem('DataTables_'+window.location.pathname) );
        },
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
	
    "ajax": "table/data_acide_traitement.php?job=get_traitement&id_import=" + id_import + "&name_user=" + name_user +"&mode=" + mode_page,
    "columns": [
      { "data": "raison", "sClass": "company_name" },
      { "data": "codep", "sClass": "integer" },
	  { "data": "ville", "sClass": "company_name" },
	  { "data": "idcontact", "sClass": "integer" },
	  { "data": "civilite", "sClass": "company_name" },
	  { "data": "nom", "sClass": "company_name" },
	  { "data": "prenom", "sClass": "company_name" },
	  { "data": "idsociete", "sClass": "integer" },
	  { "data": "fonction", "sClass": "company_name" },
	  { "data": "urllinkedin", "sClass": "company_name" },
	  { "data": "newposte", "sClass": "company_name" },
	  { "data": "oldposte", "sClass": "company_name" },
	  { "data": "newentreprise", "sClass": "company_name" },
	  { "data": "oldentreprise", "sClass": "company_name" },
      { "data": "functions",      "sClass": "functions" }
    ],
    dom: 'Bfrtip',
	"buttons": [
            'colvis'
        ],
    "order": [[ 1, "desc" ]],
    "oLanguage": {
      "oPaginate": {
        "sFirst":       "<< ",
        "sPrevious":    "Précédant",
        "sNext":        "Suivant",
        "sLast":        ">>",
      },
      "sLengthMenu":    "Opérations par page : _MENU_",
      "sInfo":          "Total de _TOTAL_ Opérations (Affichage _START_ à _END_)",
	  "sSearch":          "Recherche : ",
      "sInfoFiltered":  "(Filtré depuis _MAX_ total Opérations)",
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

  

  $(document).on('click', '#function_edit_web', function(e){
    e.preventDefault();
    show_loading_message();
    var id      = $(this).data('id');
    var request = $.ajax({
      url:          'table/data_acide_traitement.php?job=get_traitement_add',
      cache:        false,
      data:         'id=' + id,
      dataType:     'json',
      contentType:  'application/json; charset=utf-8',
      type:         'get'
    });
    request.done(function(output){
      if (output.result == 'success'){
        $('.lightbox_content h2').text('Reporting');
        $('#form_company button').text('Enregistrement');
        $('#form_company').attr('class', 'form edit');
        $('#form_company').attr('data-id', id);
        $('#form_company .field_container label.error').hide();
        $('#form_company .field_container').removeClass('valid').removeClass('error');
        $('#form_company #raison').val(output.data[0].raison);
		$('#form_company #nom').val(output.data[0].nom);
		$('#form_company #prenom').val(output.data[0].prenom);
		$('#form_company #newe').val(output.data[0].newe);
		$('#form_company #url').val(output.data[0].url);
		$('#form_company #user').val();
		$("select option").filter(function() {
			return $(this).val() == output.data[0].reporting; 
		}).prop('selected', true);
		
		$("select option").filter(function() {
			return $(this).val() == output.data[0].title; 
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
      show_loading_message();
      var id        = $('#form_company').attr('data-id');
      var form_data = $('#form_company').serialize();
      var request   = $.ajax({
        url:          'table/data_acide_traitement.php?job=edit_traitement&id=' + id,
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
				hide_loading_message();
				var company_name = $('#raison').val();
				show_message("Opération '" + company_name + "' modifiée avec succés.", 'success');			
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
  
  $(document).on('click', '#del', function(e){
    e.preventDefault();
    var company_name = $(this).data('name');
    if (confirm("Confirmation de supprission du '" + company_name + "' !!")){
      show_loading_message();
      var id      = $(this).data('id');
      var request = $.ajax({
        url:          'table/data_qld.php?job=delete_qld&id=' + id,
        cache:        false,
        dataType:     'json',
        contentType:  'application/json; charset=utf-8',
        type:         'get'
      });
      request.done(function(output){
        if (output.result == 'success'){
          window.location.reload(function(){
				table_companies.api().ajax.reload;
            hide_loading_message();
            show_message("Opération '" + company_name + "' effacée avec succès.", 'success');
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

});