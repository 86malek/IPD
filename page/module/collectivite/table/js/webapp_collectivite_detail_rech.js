	$(document).ready(function() {
	var date = $("#table_collect_detail_rech").attr("data-date");
	var lot = $("#table_collect_detail_rech").attr("data-lot"); 
	var collab = $("#table_collect_detail_rech").attr("data-collab");
	

	var table_companies = $('#table_collect_detail_rech').dataTable({
	"bStateSave": true,
	"fnStateSave": function (oSettings, oData) {
		localStorage.setItem( 'DataTables_'+window.location.pathname, JSON.stringify(oData) );
	},
	"fnStateLoad": function (oSettings) {
		return JSON.parse( localStorage.getItem('DataTables_'+window.location.pathname) );
	},
	"ajax": "module/collectivite/table/php/data_collectivite_detail_rech.php?job=table_collect_detail_rech&date=" + date + "&lot="+ lot +"&collab="+ collab,
	columns:[
	{data:"date", sClass:""},
	{data:"nom", sClass:""},
	{data:"collab", sClass:""},
	{data:"temps", sClass:""},
	{data:"ok", sClass:""}, 
	{data:"ko", sClass:""}, 
	{data:"okh", sClass:""}, 
	{data:"kos", sClass:""}, 
	{data:"ligne", sClass:""},
	{data:"ecart", sClass:""}],
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
	"order": [[ 3, "asc" ]],
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
	
	});