! function(t) {
    "use strict";
$(document).ready(function(){
  
  var table_cat_fichier = $('#table_gestion_rapport_webs').dataTable({
  	"bStateSave": true,
	
    "ajax": "module/webmaster/table/php/data_gestion_webs_integration.php?job=get_rapport_integration",
	
    "columns": [
	  { "data": "collab","sClass": "" },
	  { "data": "total","sClass": "" },
	  { "data": "leads","sClass": "" },
	  { "data": "perso","sClass": "" },
	  { "data": "flash","sClass": "" },
	  { "data": "reintegration","sClass": "" },
	  { "data": "integ","sClass": "" },
	  { "data": "crea","sClass": "" },
	  { "data": "crealead","sClass": "" },	  
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
      "sLengthMenu":    "Lignes par page : _MENU_",
      "sInfo":          "Total de _TOTAL_ Lignes (Affichage _START_ à _END_)",
	  "sSearch":          "Recherche",
      "sInfoFiltered":  "(Filtré depuis _MAX_ total Lignes)",
	  "sLoadingRecords": "Chargement en cours..."
    }
  });

$(document).on('click', '#refresh', function(e){
	table_cat_fichier.api().ajax.reload(function(){
	hide_loading_message();
	show_message("Rafraîchissement des Rapports terminé", 'success');
	}, true);
});

});
}(jQuery);