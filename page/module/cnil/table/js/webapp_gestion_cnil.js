! function(t) {
    "use strict";
$(document).ready(function(){
  
  var table_cat_fichier = $('#table_gestion_rapport_cnil').dataTable({
  	"bStateSave": true,
	
    "ajax": "module/cnil/table/php/data_gestion_cnil.php?job=get_rapport_cnil",
	
    "columns": [
	  { "data": "collab","sClass": "" },
	  { "data": "totalr","sClass": "" },
	  { "data": "totals","sClass": "" },
	  { "data": "totald","sClass": "" },
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