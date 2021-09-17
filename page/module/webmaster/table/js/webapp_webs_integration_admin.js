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
  var id_import = $('#table_traitement_rapport').attr('data-id');
  
  var table_companies = $('#table_traitement_rapport').dataTable({
    "ajax": "module/webmaster/table/php/data_webs_integration.php?job=get_webs_integration_admin&id_import=" + id_import,
    "columns": [
		{ "data": "id", "sClass": "", "sType": "date-uk" },
		{ "data": "collab", "sClass": "" },
		{ "data": "campagne", "sClass": "" },
		  { "data": "type", "sClass": "" },
		  { "data": "debut", "sClass": "" },
		  { "data": "fin", "sClass": "" },
		  { "data": "temps", "sClass": "" },
		  { "data": "comm", "sClass": "" }
    ],
	"order": [[ 0, "ASC" ]],
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
  
	$(document).on('click', '#refresh', function(e){
		table_companies.api().ajax.reload(function(){
		hide_loading_message();
		show_message("Rafraîchissement des Rapports terminé", 'success');
		}, true);
	});

});
}(jQuery);