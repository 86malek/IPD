$(document).ready(function() {
	var date = $("#table_cat_fichier_acide_rech").attr("data-date");
	var collab = $("#table_cat_fichier_acide_rech").attr("data-collab");
	function d(a, b) {
		$("#message").html("<p>" + a + "</p>").attr("class", b);
		$("#message_container").show();
		"undefined" !== typeof timeout_message && window.clearTimeout(timeout_message);
		timeout_message = setTimeout(function() {
			$("#message").html("").attr("class", "");
			$("#message_container").hide()
		}, 8E3)
	}

	function f() {
		$("#loading_container").show()
	}

	function c() {
		$("#loading_container").hide()
	}

	function k() {
		$(".lightbox_bg").show();
		$(".lightbox_container").show()
	}

	function e() {
		$(".lightbox_bg").hide();
		$(".lightbox_container").hide()
	}

	function l() {
		document.activeElement.blur();
		$("input").blur()
	}
		g = $("#table_cat_fichier_acide_rech").dataTable({
			bStateSave: !0,
			fnStateSave: function(a, b) {
				localStorage.setItem("DataTables_" + window.location.pathname, JSON.stringify(b))
			},
			fnStateLoad: function(a) {
				return JSON.parse(localStorage.getItem("DataTables_" + window.location.pathname))
			},
			ajax: "module/siretisation/table/php/data_cat_fichier_siret_detail_rech.php?job=get_cat_fichier_detail_rech&date=" + date + "&collab="+ collab,
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
				sLengthMenu: "Cat\u00e9gories par page : _MENU_",
				sInfo: "Total de _TOTAL_ Cat\u00e9gories (Affichage _START_ \u00e0 _END_)",
				sSearch: "Recherche",
				sInfoFiltered: "(Filtr\u00e9 depuis _MAX_ total Cat\u00e9gories)",
				sLoadingRecords: "Chargement en cours..."
			}
		});
	
});