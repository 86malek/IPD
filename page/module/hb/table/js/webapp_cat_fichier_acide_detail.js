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
			ajax: "module/hb/table/php/data_cat_fichier_acide_detail.php?job=get_cat_fichier_detail&id_stat=" + m,
			columns: [{
				data: "collab",
				sClass: ""
			}, {
				data: "temps",
				sClass: ""
			}, {
				data: "ajout",
				sClass: ""
			}, {
				data: "ajoutnew",
				sClass: ""
			},{
				data: "fermee",
				sClass: ""
			}, {
				data: "modif",
				sClass: ""
			}, {
				data: "ok",
				sClass: ""
			}, {
				data: "supp",
				sClass: ""
			}, {
				data: "encours",
				sClass: ""
			}, {
				data: "ko",
				sClass: ""
			}, {
				data: "ligne",
				sClass: ""
			}],
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
				sLoadingRecords: "Chargement en cours des données ... !"
			}
		});
	
});
}(jQuery);