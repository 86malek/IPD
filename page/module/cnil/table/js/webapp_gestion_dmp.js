! function(t) {
    "use strict";
$(document).ready(function(){
  
  var table_cat_fichier = $('#table_gestion_rapport_dmp').dataTable({
  	"bStateSave": true,
	
    "ajax": "module/cnil/table/php/data_gestion_dmp.php?job=get_rapport_dmp",
	
    "columns": [
	  { "data": "collab","sClass": "" },
	  { "data": "totalsemaine","sClass": "" },
	  { "data": "totalemail","sClass": "" },
	  { "data": "jh","sClass": "" },
      { "data": "functions","sClass": "" }
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
    "oLanguage": {
      "oPaginate": {
        "sFirst":       "<<",
        "sPrevious":    "Précédent",
        "sNext":        "Suivant",
        "sLast":        ">>",
      },
      "sLengthMenu":    "lignes par page : _MENU_",
      "sInfo":          "Total de _TOTAL_ lignes (Affichage _START_ à _END_)",
	  "sSearch":          "Recherche",
      "sInfoFiltered":  "(Filtré depuis _MAX_ total lignes)",
	  "sLoadingRecords": "Chargement en cours..."
    }
  });

});
}(jQuery);