$(document).ready(function(){
  
  var table_companies = $('#table_orgi').dataTable({
    "ajax": "table/data_orgi.php?job=get_orgi",
    "columns": [
      { "data": "nom",   "sClass": "nom" },
      { "data": "functions",      "sClass": "functions" }
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
    "aoColumnDefs": [
      { "bSortable": false, "aTargets": [-1] }
    ],
    "lengthMenu": [[5, 10, 30, 50, -1], [5, 10, 30, 50, "All"]],
    "oLanguage": {
      "oPaginate": {
        "sFirst":       "<< ",
        "sPrevious":    "Précédant",
        "sNext":        "Suivant",
        "sLast":        ">>",
      },
      "sLengthMenu":    "Équipes par page : _MENU_",
      "sInfo":          "Total de _TOTAL_ Équipes (Affichage _START_ à _END_)",
	  "sSearch":          "Recherche",
      "sInfoFiltered":  "(Filtré depuis _MAX_ total Équipes)",
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
      $(element).parent('.field_container_orgi').removeClass('valid').addClass('error');
    },
    unhighlight: function(element){
      $(element).parent('.field_container_orgi').addClass('valid').removeClass('error');
    }
  });
  var form_company = $('#form_orgi');
  form_company.validate();

  function show_message(message_text, message_type){
    $('#message_orgi').html('<p>' + message_text + '</p>').attr('class', message_type);
    $('#message_container_orgi').show();
    if (typeof timeout_message !== 'undefined'){
      window.clearTimeout(timeout_message);
    }
    timeout_message = setTimeout(function(){
      hide_message();
    }, 8000);
  }
  function hide_message(){
    $('#message_orgi').html('').attr('class', '');
    $('#message_container_orgi').hide();
  }

  function show_loading_message(){
    $('#loading_container_orgi').show();
  }
  function hide_loading_message(){
    $('#loading_container_orgi').hide();
  }

  function show_lightbox(){
    $('.lightbox_bg_orgi').show();
    $('.lightbox_container_orgi').show();
  }
  function hide_lightbox(){
    $('.lightbox_bg_orgi').hide();
    $('.lightbox_container_orgi').hide();
  }
  $(document).on('click', '.lightbox_bg_orgi', function(){
    hide_lightbox();
  });
  $(document).on('click', '.lightbox_close_orgi', function(){
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

  $(document).on('click', '#add_orgi', function(e){
    e.preventDefault();
    $('.lightbox_container_orgi h2').text('Ajout une nouvelle équipe à la base');
    $('#form_orgi button').text('Enregistrement');
    $('#form_orgi').attr('class', 'form add');
    $('#form_orgi').attr('data-id', '');
    $('#form_orgi .field_container label.error').hide();
    $('#form_orgi .field_container').removeClass('valid').removeClass('error');
    $('#form_orgi #nom').val('');
    show_lightbox();
  });

  $(document).on('submit', '#form_orgi.add', function(e){
    e.preventDefault();
    if (form_company.valid() == true){
      hide_ipad_keyboard();
      hide_lightbox();
      show_loading_message();
      var form_data = $('#form_orgi').serialize();
      var request   = $.ajax({
        url:          'table/data_orgi.php?job=add_orgi',
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
            var company_name = $('#nom').val();
            show_message("Équipe '" + company_name + "' ajoutée avec succés.", 'success');
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

  $(document).on('click', '#function_edit_orgi', function(e){
    e.preventDefault();
    show_loading_message();
    var id      = $(this).data('id');
    var request = $.ajax({
      url:          'table/data_orgi.php?job=get_orgi_add',
      cache:        false,
      data:         'id=' + id,
      dataType:     'json',
      contentType:  'application/json; charset=utf-8',
      type:         'get'
    });
    request.done(function(output){
      if (output.result == 'success'){
        $('.lightbox_content_orgi h2').text("Modification d'une équipe");
        $('#form_orgi button').text('Enregistrement');
        $('#form_orgi').attr('class', 'form edit');
        $('#form_orgi').attr('data-id', id);
        $('#form_orgi .field_container label.error').hide();
        $('#form_orgi .field_container').removeClass('valid').removeClass('error');
        $('#form_orgi #nom').val(output.data[0].nom);


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
  
  $(document).on('submit', '#form_orgi.edit', function(e){
    e.preventDefault();
    if (form_company.valid() == true){
      hide_ipad_keyboard();
      hide_lightbox();
      show_loading_message();
      var id        = $('#form_orgi').attr('data-id');
      var form_data = $('#form_orgi').serialize();
      var request   = $.ajax({
        url:          'table/data_orgi.php?job=edit_orgi&id=' + id,
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
            var company_name = $('#nom').val();
            show_message("Équipe '" + company_name + "' modifiée avec succés.", 'success');
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
  
  $(document).on('click', '#del', function(e){
    e.preventDefault();
    var company_name = $(this).data('name');
    if (confirm("Voulez-vous vraiement effacer l'enregistrment : '" + company_name + "'")){
      show_loading_message();
      var id      = $(this).data('id');
      var request = $.ajax({
        url:          'table/data_orgi.php?job=delete_orgi&id=' + id,
        cache:        false,
        dataType:     'json',
        contentType:  'application/json; charset=utf-8',
        type:         'get'
      });
      request.done(function(output){
        if (output.result == 'success'){
          table_companies.api().ajax.reload(function(){
            hide_loading_message();
            show_message("Equipe '" + company_name + "' effacée avec succès.", 'success');
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

});