! function(t) {
    "use strict";
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

  var table_companies = $('#table_rapport_cumul').dataTable({
    
    "ajax": "module/rapport/table/php/data_rapport.php?job=get_rapport_cumul",
    "columns": [
      { "data": "demande",   "sClass": "" },
	  { "data": "equipe",   "sClass": "" },
	  { "data": "operation",   "sClass": "" },
	  { "data": "nbcc",   "sClass": "" },
	  { "data": "obj",   "sClass": "" },
	  { "data": "rea",   "sClass": "" },
	  { "data": "nature",   "sClass": "" },
	  { "data": "taux",   "sClass": "" },
	  { "data": "jh",   "sClass": "" },
	  { "data": "debut",   "sClass": "" },
	  { "data": "fin",   "sClass": "" }
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
      },'colvis'
    ],
    "oLanguage": {
      "oPaginate": {
        "sFirst":       "<< ",
        "sPrevious":    "Précédent",
        "sNext":        "Suivant",
        "sLast":        ">>",
      },
      "sLengthMenu":    "Lignes par page : _MENU_",
      "sInfo":          "Total de _TOTAL_ Lignes (Lignes _START_ à _END_)",
      "sSearch":          "Recherche",
      "sInfoFiltered":  "(Filtré depuis _MAX_ total Lignes)",
      "sLoadingRecords": "Chargement en cours des données ..."
    }
  }); 
  });
}(jQuery);