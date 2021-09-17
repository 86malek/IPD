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
	var m = $("#table_cat_fichier_acide_detail").attr("data-id"),
		g = $("#table_cat_fichier_acide_detail").dataTable({
			bStateSave: !0,
			fnStateSave: function(a, b) {
				localStorage.setItem("DataTables_" + window.location.pathname, JSON.stringify(b))
			},
			fnStateLoad: function(a) {
				return JSON.parse(localStorage.getItem("DataTables_" + window.location.pathname))
			},
			ajax: "module/linkedin/table/php/data_cat_fichier_acide_detail_jour.php?job=get_cat_fichier_detail&id_stat=" + m,
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
				sClass: "company_name"
			}, {
				data: "modif",
				sClass: "company_name"
			}, {
				data: "supp",
				sClass: "company_name"
			}, {
				data: "ajout",
				sClass: "company_name"
			}, {
				data: "ligne",
				sClass: "functions"
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
				sLengthMenu: "Cat\u00e9gories par page : _MENU_",
				sInfo: "Total de _TOTAL_ Cat\u00e9gories (Affichage _START_ \u00e0 _END_)",
				sSearch: "Recherche",
				sInfoFiltered: "(Filtr\u00e9 depuis _MAX_ total Cat\u00e9gories)",
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
	var h = $("#form_cat_fichier");
	h.validate();
	$(document).on("click", ".lightbox_bg", function() {
		e()
	});
	$(document).on("click", ".lightbox_close", function() {
		e()
	});
	$(document).keyup(function(a) {
		27 == a.keyCode && e()
	});
	$(document).on("click", "#add_cat_fichier", function(a) {
		a.preventDefault();
		$(".lightbox_container h2").text("Ajouter une nouvelle cat\u00e9gorie");
		$("#form_cat_fichier button").text("Enregistrement");
		$("#form_cat_fichier").attr("class", "form add");
		$("#form_cat_fichier").attr("data-id", "");
		$("#form_cat_fichier .field_container label.error").hide();
		$("#form_cat_fichier .field_container").removeClass("valid").removeClass("error");
		$("#form_cat_fichier #nom").val("");
		k()
	});
	$(document).on("submit", "#form_cat_fichier.add", function(a) {
		a.preventDefault();
		1 == h.valid() && (l(), e(), f(), a = $("#form_cat_fichier").serialize(), a = $.ajax({
			url: "table/data_cat_fichier_acide.php?job=add_cat_fichier",
			cache: !1,
			data: a,
			dataType: "json",
			contentType: "application/json; charset=utf-8",
			type: "get"
		}), a.done(function(a) {
			"success" == a.result ? g.api().ajax.reload(function() {
				c();
				var a = $("#nom").val();
				d("Cat\u00e9gorie '" + a + "' ajout\u00e9e avec succ\u00e9s.", "success")
			}, !0) : (c(), d("Une erreur s'est produite lors de l'enregistrement", "error"))
		}), a.fail(function(a, n) {
			c();
			d("Une erreur s'est produite lors de l'enregistrement" + n, "error")
		}))
	});
	$(document).on("click", "#function_edit_cat_fichier", function(a) {
		a.preventDefault();
		f();
		var b = $(this).data("id");
		a = $.ajax({
			url: "table/data_cat_fichier_acide.php?job=get_cat_fichier_add",
			cache: !1,
			data: "id=" + b,
			dataType: "json",
			contentType: "application/json; charset=utf-8",
			type: "get"
		});
		a.done(function(a) {
			"success" == a.result ? ($(".lightbox_content_cat_fichier h2").text("Modification de la cat\u00e9gorie"), $("#form_cat_fichier button").text("Enregistrement"), $("#form_cat_fichier").attr("class", "form edit"), $("#form_cat_fichier").attr("data-id", b), $("#form_cat_fichier .field_container label.error").hide(), $("#form_cat_fichier .field_container").removeClass("valid").removeClass("error"), $("#form_cat_fichier #nom").val(a.data[0].nom), c(), k()) : (c(), d("Une erreur s'est produite lors de l'enregistrement", "error"))
		});
		a.fail(function(a, b) {
			c();
			d("Une erreur s'est produite lors de l'enregistrement" + b, "error")
		})
	});
	$(document).on("submit", "#form_cat_fichier.edit", function(a) {
		a.preventDefault();
		if (1 == h.valid()) {
			l();
			e();
			f();
			a = $("#form_cat_fichier").attr("data-id");
			var b = $("#form_cat_fichier").serialize();
			a = $.ajax({
				url: "table/data_cat_fichier_acide.php?job=edit_cat_fichier&id=" + a,
				cache: !1,
				data: b,
				dataType: "json",
				contentType: "application/json; charset=utf-8",
				type: "get"
			});
			a.done(function(a) {
				"success" == a.result ? g.api().ajax.reload(function() {
					c();
					var a = $("#nom").val();
					d("Cat\u00e9gorie '" + a + "' modifi\u00e9e avec succ\u00e9s.", "success")
				}, !0) : (c(), d("Une erreur s'est produite lors de l'enregistrement", "error"))
			});
			a.fail(function(a, b) {
				c();
				d("Une erreur s'est produite lors de l'enregistrement" + b, "error")
			})
		}
	});
	$(document).on("click", "a#del", function(a) {
		a.preventDefault();
		var b = $(this).data("name");
		if (confirm("Confirmation de supprission du '" + b + "' !!")) {
			f();
			a = $(this).data("id");
			var e = $(this).data("doc");
			a = $.ajax({
				url: "table/data_cat_fichier_acide.php?job=delete_cat_fichier&id=" + a + "&cat=" + e,
				cache: !1,
				dataType: "json",
				contentType: "application/json; charset=utf-8",
				type: "get"
			});
			a.done(function(a) {
				"success" == a.result ? g.api().ajax.reload(function() {
					c();
					d("Cat\u00e9gorie '" + b + "' effac\u00e9e avec succ\u00e8s.", "success")
				}, !0) : (c(), d("Une erreur s'est produite lors de l'enregistrement", "error"))
			});
			a.fail(function(a, b) {
				c();
				d("Une erreur s'est produite lors de l'enregistrement" + b, "error")
			})
		}
	})
});