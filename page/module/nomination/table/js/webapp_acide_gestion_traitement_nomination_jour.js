! function(p) {
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
			var m = $("#table_gestion_traitement_nomination_jour").attr("data-id");
			var date = $("#table_gestion_traitement_nomination_jour").attr("data-date");
		
			g = $("#table_gestion_traitement_nomination_jour").dataTable({
				bStateSave: !0,
				fnStateSave: function(a, b) {
					localStorage.setItem("DataTables_" + window.location.pathname, JSON.stringify(b))
				},
				fnStateLoad: function(a) {
					return JSON.parse(localStorage.getItem("DataTables_" + window.location.pathname))
				},
				ajax: "module/nomination/table/php/data_acide_gestion_traitement_nomination_jour.php?job=get_gestion_traitement_nomination_jour&id_user=" + m + "&date=" + date,
				columns: [{
					data: "collab",
					sClass: ""
				}, {
					data: "date",
					sClass: ""
				}, {
					data: "prod",
					sClass: ""
				}, {
					data: "jh",
					sClass: ""
				}, {
					data: "time",
					sClass: ""
				}, {
					data: "countajout",
					sClass: ""
				}, {
					data: "countmodif",
					sClass: ""
				}, {
					data: "countsupp",
					sClass: ""
				}, {
					data: "countbo",
					sClass: ""
				}, {
					data: "countnt",
					sClass: ""
				}, {
					data: "count",
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
				aoColumnDefs: [{
					bSortable: !1,
					aTargets: [-1]
				}],
				lengthMenu: [
					[10, 25, 50, 100, -1],
					[10, 25, 50, 100, "All"]
				],
				oLanguage: {
					oPaginate: {
						sFirst: "<< ",
						sPrevious: "Pr\u00e9c\u00e9dent",
						sNext: "Suivant",
						sLast: ">>"
					},
					sLengthMenu: "Op\u00e9rations par page : _MENU_",
					sInfo: "Total de _TOTAL_ Op\u00e9rations (Affichage _START_ \u00e0 _END_)",
					sSearch: "Recherche : ",
					sInfoFiltered: "(Filtr\u00e9 depuis _MAX_ total Op\u00e9rations)",
					sLoadingRecords: "Chargement en cours..."
				}
			});	
		
	})
}(jQuery);