$(document).ready(function() {
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
	var m = $("#table_cat_fichier_client_detail").attr("data-id"),
		g = $("#table_cat_fichier_client_detail").dataTable({
			bStateSave: !0,
			fnStateSave: function(a, b) {
				localStorage.setItem("DataTables_" + window.location.pathname, JSON.stringify(b))
			},
			fnStateLoad: function(a) {
				return JSON.parse(localStorage.getItem("DataTables_" + window.location.pathname))
			},
			ajax: "module/client/table/php/data_cat_fichier_client_detail_jour_contact.php?job=get_cat_fichier_detail&id_stat=" + m,
			columns: [{
				data: "date",
				sClass: "functions"
			}, {
				data: "collab",
				sClass: "company_name"
			},
			{ "data": "traitesociete","sClass": "" },
				{ "data": "traite","sClass": "" },
			  { "data": "traiteindispo","sClass": "" },
			  { "data": "traitenonverif","sClass": "" },
			  { "data": "traitequite","sClass": "" },
			  { "data": "traiteok","sClass": "" },
			  { "data": "traiteokmodif","sClass": "" },
			  { "data": "traiteremplace","sClass": "" },
			  { "data": "traitehc","sClass": "" },
			  { "data": "traiteajout","sClass": "" },
			
			 {
				data: "temps",
				sClass: "company_name"
			}, {
				data: "dmt",
				sClass: "functions"
			}, {
				data: "ecart",
				sClass: "company_name"
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
				sLengthMenu: "Traitement par page : _MENU_",
				sInfo: "Total de _TOTAL_ Traitement (Affichage _START_ \u00e0 _END_)",
				sSearch: "Recherche",
				sInfoFiltered: "(Filtr\u00e9 depuis _MAX_ total Traitement)",
				sLoadingRecords: "Chargement en cours..."
			}
		});
});