! function(t) {
    "use strict";
$(document).ready(function(){
  var id_cat_mere = $('#table_cat_fichier_client').attr('data-id');
  var table_cat_fichier = $('#table_cat_fichier_client').dataTable({
  	"bStateSave": true,
    "ajax": "module/client/table/php/data_cat_fichier_client_contact.php?job=get_cat_fichier&id_cat_mere=" + id_cat_mere,
    "columns": [
	  { "data": "fichier","sClass": "" },
	  
	  { "data": "societetotall","sClass": "" },
	  { "data": "societetotal","sClass": "" },
	  { "data": "societetraitee","sClass": "" },
	  
	  { "data": "traite","sClass": "" },
	  { "data": "traiteindispo","sClass": "" },
	  { "data": "traitenonverif","sClass": "" },
	  { "data": "traitequite","sClass": "" },
	  { "data": "traiteok","sClass": "" },
	  { "data": "traiteokmodif","sClass": "" },

    { "data": "traiteokcharge","sClass": "" },
    { "data": "traiteokprise","sClass": "" },
    { "data": "traiteko","sClass": "" },
    { "data": "traiteencour","sClass": "" },
    { "data": "traiterefus","sClass": "" },

	  { "data": "traiteremplace","sClass": "" },
	  { "data": "traitehc","sClass": "" },
	  { "data": "traiteajout","sClass": "" },
	  
	  { "data": "collab","sClass": "" },
	  { "data": "debut","sClass": "" },
	  { "data": "fin","sClass": "" },
	  { "data": "temps","sClass": "" },
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
      "sLengthMenu":    "Dossiers par page : _MENU_",
      "sInfo":          "Total de _TOTAL_ Dossier (Affichage _START_ à _END_)",
	  "sSearch":          "Recherche",
      "sInfoFiltered":  "(Filtré depuis _MAX_ total Dossiers)",
	  "sLoadingRecords": "Chargement en cours..."
    }
  });

});
}(jQuery);