! function(t) {
    "use strict";
$(document).ready(function() {
	
	var m = $("#table_cat_fichier_acide_detail").attr("data-id"),
		g = $("#table_cat_fichier_acide_detail").dataTable({
			bStateSave: !0,
			fnStateSave: function(a, b) {
				localStorage.setItem("DataTables_" + window.location.pathname, JSON.stringify(b))
			},
			fnStateLoad: function(a) {
				return JSON.parse(localStorage.getItem("DataTables_" + window.location.pathname))
			},
			ajax: "module/siretisation/table/php/data_cat_fichier_siret_detail_jour.php?job=get_cat_fichier_detail&id_stat=" + m,
			columns: [{
				data: "date",
				sClass: "functions"
			}, {
				data: "collab",
				sClass: "company_name"
			}, {
				data: "temps",
				sClass: "company_name"
			}, {
				data: "ok",
				sClass: ""
			},{
				data: "nt",
				sClass: ""
			}, {
				data: "stee",
				sClass: ""
			}, {
				data: "stef",
				sClass: ""
			}, {
				data: "rowligne_encours",
				sClass: ""
			}, {
				data: "ligne",
				sClass: "functions"
			}, {
				data: "dmt",
				sClass: "functions"
			}],
			order: [
				[0, "asc"]
			],
			dom: "Bfrtip",
			buttons: [{
				extend: "excelHtml5",
				exportOptions: {
					columns: ":visible"
				}
			}, {
				extend: "pdfHtml5",
				exportOptions: {
					columns: ":visible"
				}
			}, {
				extend: "print",
				exportOptions: {
					columns: ":visible"
				}
			}, "colvis"],
			aoColumnDefs: [{
				bSortable: !1,
				aTargets: [-1]
			}],
			lengthMenu: [
				[5, 10, 30, 50, -1],
				[5, 10, 30, 50, "All"]
			],
			oLanguage: {
				oPaginate: {
					sFirst: "<<",
					sPrevious: "Pr\u00e9c\u00e9dent",
					sNext: "Suivant",
					sLast: ">>"
				},
				sLengthMenu: "Lignes par page : _MENU_",
				sInfo: "Total de _TOTAL_ Lignes (Affichage _START_ \u00e0 _END_)",
				sSearch: "Recherche",
				sInfoFiltered: "(Filtr\u00e9 depuis _MAX_ total Lignes)",
				sLoadingRecords: "Chargement en cours..."
			}
		});
		
		
	
});
}(jQuery);