$(document).ready(function(){
  var id_import = $('#table_traitement_nomination').attr('data-id');
  var mode_page = $('#table_traitement_nomination').attr('data-mode');
  var table_companies = $('#table_traitement_nomination').dataTable({
	"bStateSave": true,
    "ajax": "module/nomination/table/php/data_acide_traitement_nomination.php?job=get_traitement_nomination&id_import=" + id_import +"&mode=" + mode_page,
    "columns": [
	  { "data": "alerte", "sClass": "" },
      { "data": "date", "sClass": "" },
	  { "data": "publication", "sClass": "" },
      { "data": "rs", "sClass": "" },
	  { "data": "siret", "sClass": "" },
	  { "data": "title", "sClass": "" },
	  { "data": "nom", "sClass": "" },
	  { "data": "prenom", "sClass": "" },
	  { "data": "fe", "sClass": "" },	  
	  { "data": "ancienne", "sClass": "" },
	  { "data": "statut", "sClass": "" },
	  { "data": "type", "sClass": "" },
      { "data": "functions", "sClass": "" }
    ],
    dom: 'Bfrtip',
	"buttons": [
            'colvis'
        ],
    "aoColumnDefs": [
      { "bSortable": false, "aTargets": [-1] }
    ],
    "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
    "oLanguage": {
      "oPaginate": {
        "sFirst":       "<<",
        "sPrevious":    "Précédent",
        "sNext":        "Suivant",
        "sLast":        ">>",
      },
      "sLengthMenu":    "Fiches par page : _MENU_",
      "sInfo":          "Total de _TOTAL_ Fiches (Affichage _START_ à _END_)",
	  "sSearch":          "Recherche : ",
      "sInfoFiltered":  "(Filtré depuis _MAX_ total Fiches)",
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

  $(document).on('click', '#add_nomination', function(e){
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
		
      $('.lightbox_content h2').text('NOUVELLE FICHE NOMINATION');
      $('#form_company button').text('ENREGISTREMENT DE LA FICHE');
      $('#form_company').attr('class', 'form add');
      $('#form_company').attr('data-id', '');
      $('#form_company .field_container label.error').hide();
      $('#form_company .field_container').removeClass('valid').removeClass('error');
      $('#form_company #publication').val('');
      $('#form_company #rs').val('');
      $('#form_company #siret').val('');
      $('#form_company #title').val('');
      $('#form_company #statut').val('');
      $('#form_company #etat').val('');
	  $('#form_company #comm').val('');
	  $("#form_company #etat").change(function () {
                                                 
          var str = "";
          $("#form_company #etat option:selected").each(function () {
                str += $(this).val();
              });
           
          if(str == 1){
                $('#form_company #statut').attr('disabled', true);
		$('#form_company #ancienne').attr('disabled', true);
          }
          else{
              $('#form_company #statut').attr('disabled', false);$('#form_company #ancienne').attr('disabled', false);
          }
        });	
      $('#form_company #nom').val('');
      $('#form_company #prenom').val('');
      $('#form_company #fonction').val('');	
      $('#form_company #ancienne').val('');
      $('#form_company #user_id').val();
      $('#form_company #user').val();
	  $('#form_company #debut').val(dateTime);
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
        url:          'module/nomination/table/php/data_acide_traitement_nomination.php?job=add_traitement_nomination&fin='+ dateTime_fin,
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
            var nom = $('#nom').val();
            show_message("Fiche '" + nom + "' ajouter avec succés.", 'success');
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
      url:          'module/nomination/table/php/data_acide_traitement_nomination.php?job=get_traitement_add_nomination',
      cache:        false,
      data:         'id=' + id,
      dataType:     'json',
      contentType:  'application/json; charset=utf-8',
      type:         'get'
    });
    request.done(function(output){
      if (output.result == 'success'){
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
		
        $('.lightbox_content h2').text('MODICIATION FICHE NOMINATION');
        $('#form_company button').text('ENREGISTREMENT DE LA FICHE');
        $('#form_company').attr('class', 'form edit');
        $('#form_company').attr('data-id', id);
        $('#form_company .field_container label.error').hide();
        $('#form_company .field_container').removeClass('valid').removeClass('error');

		$("#publication option").filter(function() {
        return $(this).val() == output.data[0].publication; 
        }).prop('selected', true);
		
        $('#form_company #rs').val(output.data[0].rs);
        $('#form_company #siret').val(output.data[0].siret);

        $("#title option").filter(function() {
        return $(this).val() == output.data[0].title; 
        }).prop('selected', true);


        $("#form_company #statut option").filter(function() {
			return $(this).val() == output.data[0].statut;
        }).prop('selected', true);
		

        $('#form_company #nom').val(output.data[0].nom);
        $('#form_company #prenom').val(output.data[0].prenom);
        $('#form_company #fonction').val(output.data[0].fonction);
        $('#form_company #user_id').val(output.data[0].user_id);
        $('#form_company #ancienne').val(output.data[0].ancienne);
		$('#form_company #comm').val(output.data[0].comm);
		
        $("#etat option").filter(function() {
        return $(this).val() == output.data[0].etat; 
        }).prop('selected', true);	
		
		$("#form_company #etat").change(function () {
                                                 
          var str = "";
          $("#form_company #etat option:selected").each(function () {
                str += $(this).val();
              });
           
          if(str == 1){
                $('#form_company #statut').attr('disabled', true);
		$('#form_company #ancienne').attr('disabled', true);
          }
          else{
              $('#form_company #statut').attr('disabled', false);$('#form_company #ancienne').attr('disabled', false);
          }
        });	
		
		if(output.data[0].etat == 1){
		$('#form_company #statut').attr('disabled', true);
		$('#form_company #ancienne').attr('disabled', true);
		}else{$('#form_company #statut').attr('disabled', false);$('#form_company #ancienne').attr('disabled', false);}	
		
		$('#form_company #debut').val(dateTime);
		
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
        url:          'module/nomination/table/php/data_acide_traitement_nomination.php?job=edit_traitement_nomination&id=' + id +'&fin='+ dateTime_fin,
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
            var company_name = $('#rs').val();
            show_message("Fiche '" + company_name + "' modifiée avec succés.", 'success');
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
  
  

});