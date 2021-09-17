! function(t) {
    "use strict";
$(document).ready(function(){
  var id_import = $('#table_traitement_rapport').attr('data-id');
  
  var table_companies = $('#table_traitement_rapport').dataTable({
	 "bStateSave": true,
    "ajax": "module/cnil/table/php/data_dmp.php?job=get_dmp_admin&id_import=" + id_import,
    "columns": [
		{ "data": "collab", "sClass": "" },
		{ "data": "date", "sClass": "" },
		{ "data": "ch1", "sClass": "" },
		{ "data": "ch2", "sClass": "" }
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
      "sLengthMenu":    "Lignes par page : _MENU_",
      "sInfo":          "Total de _TOTAL_ Lignes (Affichage _START_ à _END_)",
	  "sSearch":          "Recherche : ",
      "sInfoFiltered":  "(Filtré depuis _MAX_ total Lignes)",
	  "sLoadingRecords": "Chargement en cours..."
    }
  }); 

});
}(jQuery);