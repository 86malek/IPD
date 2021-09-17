! function(g) {
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

		function m() {
			$(".lightbox_bg").show();
			$(".lightbox_container").show()
		}

		function e() {
			$(".lightbox_bg").hide();
			$(".lightbox_container").hide()
		}

		function n() {
			document.activeElement.blur();
			$("input").blur()
		}
		var h = $("#table_gestion_traitement_nomination_enrechisement").dataTable({
				bStateSave: !0,
				fnStateSave: function(a, b) {
					localStorage.setItem("DataTables_" + window.location.pathname, JSON.stringify(b))
				},
				fnStateLoad: function(a) {
					return JSON.parse(localStorage.getItem("DataTables_" + window.location.pathname))
				},
				ajax: "module/nomination/table/php/data_acide_gestion_traitement_nomination.php?job=get_gestion_traitement_nomination_enrechisement",
				columns: [{
					data: "date",
					sClass: ""
				}, {
					data: "prefix",
					sClass: ""
				}, {
					data: "code",
					sClass: ""
				}, {
					data: "actif",
					sClass: ""
				}, {
					data: "functions",
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
					sLengthMenu: "Collaborateurs par page : _MENU_",
					sInfo: "Total de _TOTAL_ Collaborateurs (Affichage _START_ \u00e0 _END_)",
					sSearch: "Recherche : ",
					sInfoFiltered: "(Filtr\u00e9 depuis _MAX_ total des Collaborateurs)",
					sLoadingRecords: "Chargement en cours..."
				}
			}),
			p = $("#table_gestion_traitement_nomination").dataTable({
				bStateSave: !0,
				fnStateSave: function(a, b) {
					localStorage.setItem("DataTables_" + window.location.pathname, JSON.stringify(b))
				},
				fnStateLoad: function(a) {
					return JSON.parse(localStorage.getItem("DataTables_" + window.location.pathname))
				},
				ajax: "module/nomination/table/php/data_acide_gestion_traitement_nomination.php?job=get_gestion_traitement_nomination",
				columns: [{
					data: "collab",
					sClass: ""
				}, {
					data: "date_debut",
					sClass: ""
				}, {
					data: "date_fin",
					sClass: ""
				}, {
					data: "time",
					sClass: ""
				}, {
					data: "jh",
					sClass: ""
				}, {
					data: "count",
					sClass: ""
				}, {
					data: "countbo",
					sClass: ""
				}, {
					data: "countnt",
					sClass: ""
				}, {
					data: "functions",
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
						sFirst: "<<",
						sPrevious: "Pr\u00e9c\u00e9dent",
						sNext: "Suivant",
						sLast: ">>"
					},
					sLengthMenu: "Donn\u00e9es par page : _MENU_",
					sInfo: "Total de _TOTAL_ Donn\u00e9es (Affichage _START_ \u00e0 _END_)",
					sSearch: "Recherche : ",
					sInfoFiltered: "(Filtr\u00e9 depuis _MAX_ total des Donn\u00e9es)",
					sLoadingRecords: "Chargement en cours..."
				}
			});
		jQuery.validator.setDefaults({
			success: "valid",
			rules: {
				fiscal_year: {
					required: !0,
					min: 2E3,
					max: 2025
				}
			},
			errorPlacement: function(a, b) {
				a.insertBefore(b)
			},
			highlight: function(a) {
				$(a).parent(".field_container").removeClass("valid").addClass("error")
			},
			unhighlight: function(a) {
				$(a).parent(".field_container").addClass("valid").removeClass("error")
			}
		});
		var k = $("#form_enrichissement");
		k.validate();
		$(document).on("click", ".lightbox_bg", function() {
			e()
		});
		$(document).on("click", ".lightbox_close", function() {
			e()
		});
		$(document).keyup(function(a) {
			27 == a.keyCode && e()
		});
		$(document).on("click", "#add_nomination_enrichissement", function(a) {
			a.preventDefault();
			$(".lightbox_content h2").text("Ajouter une nouveau enrichissement NOMINATION");
			$("#form_enrichissement button").text("Valider l'ajout");
			$("#form_enrichissement").attr("class", "form add");
			$("#form_enrichissement").attr("data-id", "");
			$("#form_enrichissement .field_container label.error").hide();
			$("#form_enrichissement .field_container").removeClass("valid").removeClass("error");
			$("#form_enrichissement #mot").val("");
			$("#form_enrichissement #prefix").val("");
			m()
		});
		$(document).on("submit", "#form_enrichissement.add", function(a) {
			a.preventDefault();
			1 == k.valid() && (n(), e(), f(), a = $("#form_enrichissement").serialize(), a = $.ajax({
				url: "module/nomination/table/php/data_acide_gestion_traitement_nomination.php?job=add_gestion_traitement_nomination_enrechisement",
				cache: !1,
				data: a,
				dataType: "json",
				contentType: "application/json; charset=utf-8",
				type: "get"
			}), a.done(function(a) {
				"success" == a.result ? h.api().ajax.reload(function() {
					c();
					var a = $("#mot").val();
					d("Enrichissement '" + a + "' ajouter avec succ\u00e9s.", "success")
				}, !0) : (c(), d("Une erreur s'est produite lors de l'enregistrement", "error"))
			}), a.fail(function(a, l) {
				c();
				d("Une erreur s'est produite lors de l'enregistrement" + l, "error")
			}))
		});
		$(document).on("click", "#function_edit_enrechisement", function(a) {
			a.preventDefault();
			f();
			var b = $(this).data("id");
			a = $.ajax({
				url: "module/nomination/table/php/data_acide_gestion_traitement_nomination.php?job=get_gestion_traitement_add_nomination_enrechisement",
				cache: !1,
				data: "id=" + b,
				dataType: "json",
				contentType: "application/json; charset=utf-8",
				type: "get"
			});
			a.done(function(a) {
				"success" == a.result ? ($(".lightbox_content h2").text("Modification Enregistrement Enrechisement m\u00e9tier NOMINATION"), $("#form_enrichissement button").text("Valider la modification"), $("#form_enrichissement").attr("class", "form edit"), $("#form_enrichissement").attr("data-id", b), $("#form_enrichissement .field_container label.error").hide(), $("#form_enrichissement .field_container").removeClass("valid").removeClass("error"), $("#form_enrichissement #mot").val(a.data[0].mot), $("#form_enrichissement #prefix").val(a.data[0].prefix), c(), m()) : (c(), d("Une erreur s'est produite lors de l'enregistrement", "error"))
			});
			a.fail(function(a, b) {
				c();
				d("Une erreur s'est produite lors de l'enregistrement " + b, "error")
			})
		});
		$(document).on("submit", "#form_enrichissement.edit", function(a) {
			a.preventDefault();
			if (1 == k.valid()) {
				n();
				e();
				f();
				a = $("#form_enrichissement").attr("data-id");
				var b = $("#form_enrichissement").serialize();
				a = $.ajax({
					url: "module/nomination/table/php/data_acide_gestion_traitement_nomination.php?job=edit_gestion_traitement_nomination_enrechisement&id=" + a,
					cache: !1,
					data: b,
					dataType: "json",
					contentType: "application/json; charset=utf-8",
					type: "get"
				});
				a.done(function(a) {
					"success" == a.result ? h.api().ajax.reload(function() {
						c();
						var a = $("#mot").val();
						d("Enrichissement '" + a + "' modifi\u00e9e avec succ\u00e9s.", "success")
					}, !0) : (c(), d("Une erreur s'est produite lors de l'enregistrement", "error"))
				});
				a.fail(function(a, b) {
					c();
					d("Une erreur s'est produite lors de l'enregistrement : " + b, "error")
				})
			}
		});
		$(document).on("click", "#del_enrechisement", function(a) {
			a.preventDefault();
			var b = $(this).data("id"),
				l = $(this).data("doc"),
				e = $(this).data("name");
			g.confirm({
				title: e,
				content: "Confirmation de supprission de l'entr\u00e9s " + e,
				autoClose: "cancelAction|10000",
				escapeKey: "cancelAction",
				draggable: !1,
				closeIcon: !0,
				buttons: {
					confirm: {
						btnClass: "btn-danger",
						text: "Confirmer",
						action: function() {
							g.alert("Supprission termin\u00e9e");
							f();
							var a = $.ajax({
								url: "module/nomination/table/php/data_acide_gestion_traitement_nomination.php?job=delete_gestion_traitement_nomination_enrechisement&id=" + b + "&cat=" + l,
								cache: !1,
								dataType: "json",
								contentType: "application/json; charset=utf-8",
								type: "get"
							});
							a.done(function(a) {
								"success" == a.result ? h.api().ajax.reload(function() {
									c();
									hide_lightbox_del();
									d("Cat\u00e9gorie '" + e + "' effac\u00e9e avec succ\u00e8s.", "success")
								}, !0) : (c(), d("Une erreur s'est produite lors de l'enregistrement", "error"))
							});
							a.fail(function(a, b) {
								c();
								d("Une erreur s'est produite lors de l'enregistrement" + b, "error")
							})
						}
					},
					cancelAction: {
						text: "Annuler",
						action: function() {
							g.alert("La supprission est annul\u00e9e")
						}
					}
				}
			})
		});
		$(document).on("click", "#del", function(a) {
			a.preventDefault();
			var b = $(this).data("name");
			confirm("Confirmation de supprission du '" + b + "' !!") && (f(), a = $(this).data("id"), a = $.ajax({
				url: "module/nomination/table/php/data_acide_gestion_traitement_nomination.php?job=delete_gestion_traitement_nomination&id=" + a,
				cache: !1,
				dataType: "json",
				contentType: "application/json; charset=utf-8",
				type: "get"
			}), a.done(function(a) {
				"success" == a.result ? p.api().ajax.reload(function() {
					c();
					d("Ligne '" + b + "' effac\u00e9e avec succ\u00e8s.", "success")
				}, !0) : (c(), d("Une erreur s'est produite lors de l'enregistrement", "error"))
			}), a.fail(function(a, b) {
				c();
				d("Une erreur s'est produite lors de l'enregistrement" + b, "error")
			}))
		})
	})
}(jQuery);