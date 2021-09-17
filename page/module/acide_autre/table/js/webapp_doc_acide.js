$(document).ready(function(){
  var id_import = $('#table_doc_acide').attr('data-id');
  var table_doc = $('#table_doc_acide').dataTable({
	"bStateSave": true,
	"fnStateSave": function (oSettings, oData) {
		localStorage.setItem( 'DataTables_'+window.location.pathname, JSON.stringify(oData) );
	},
	"fnStateLoad": function (oSettings) {
		return JSON.parse( localStorage.getItem('DataTables_'+window.location.pathname) );
	},
    "ajax": "module/acide_autre/table/php/data_doc_acide.php?job=get_doc_acide&id_cat=" + id_import,
    "columns": [
      { "data": "nom_fichier",   "sClass": "company_name" },
	  { "data": "cat_fichier",   "sClass": "company_name" },
      { "data": "user_fichier",   "sClass": "company_name" },
	  { "data": "insertion_fichier",   "sClass": "company_name" },	  
	  { "data": "statut_fichier",   "sClass": "company_name" },
      { "data": "download",      "sClass": "" }
    ],
	dom: 'Bfrtip',
	"buttons": [
             'colvis'
    ],
    "oLanguage": {
      "oPaginate": {
        "sFirst":       "<<",
        "sPrevious":    "Précédant",
        "sNext":        "Suivant",
        "sLast":        ">>",
      },
      "sLengthMenu":    "Fichiers par page : _MENU_",
      "sInfo":          "Total de _TOTAL_ Fichiers (Affichage _START_ à _END_)",
	  "sSearch":          "Recherche",
      "sInfoFiltered":  "(Filtré depuis _MAX_ total Fichiers)"
    }
  });
  
  // On page load: form validation
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
  var form_doc = $('#form_doc');
  form_doc.validate();

  // Show message
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
  // Hide message
  function hide_message(){
    $('#message').html('').attr('class', '');
    $('#message_container').hide();
  }

  // Show loading message
  function show_loading_message(){
    $('#loading_container').show();
  }
  // Hide loading message
  function hide_loading_message(){
    $('#loading_container').hide();
  }

  // Show lightbox
  function show_lightbox(){
    $('.lightbox_bg').show();
    $('.lightbox_container').show();
  }
  // Hide lightbox
  function hide_lightbox(){
    $('.lightbox_bg').hide();
    $('.lightbox_container').hide();
  }
  // Lightbox background
  $(document).on('click', '.lightbox_bg', function(){
    hide_lightbox();
  });
  // Lightbox close button
  $(document).on('click', '.lightbox_close', function(){
    hide_lightbox();
  });
  // Escape keyboard key
  $(document).keyup(function(e){
    if (e.keyCode == 27){
      hide_lightbox();
    }
  });
  
  // Hide iPad keyboard
  function hide_ipad_keyboard(){
    document.activeElement.blur();
    $('input').blur();
  } 

});