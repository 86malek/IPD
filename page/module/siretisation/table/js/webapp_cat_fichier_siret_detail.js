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
			ajax: "module/siretisation/table/php/data_cat_fichier_siret_detail.php?job=get_cat_fichier_detail&id_stat=" + m,
			columns: [{
				data: "collab",
				sClass: ""
			}, {
				data: "temps",
				sClass: ""
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
				sLengthMenu: "ligens par page : _MENU_",
				sInfo: "Total de _TOTAL_ ligens (Affichage _START_ \u00e0 _END_)",
				sSearch: "Recherche",
				sInfoFiltered: "(Filtr\u00e9 depuis _MAX_ total ligens)",
				sLoadingRecords: "Chargement en cours..."
			}
		});
		
		function show_message(message_text, message_type){
    $('#message').html('<p>' + message_text + '</p>').attr('class', message_type);
    $('#message_container').show();
    if (typeof timeout_message !== 'undefined'){
      window.clearTimeout(timeout_message);
    }
    timeout_message = setTimeout(function(){
      hide_message();
    }, 8000);
  }
  function hide_message(){
    $('#message').html('').attr('class', '');
    $('#message_container').hide();
  }
		$(document).on('click', '#refresh', function(e){
		g.api().ajax.reload(function(){
		hide_loading_message();
		show_message("Rafraîchissement terminé", 'success');
		}, true);
	});
	
	$(document).on(setInterval(function(){
		g.api().ajax.reload(function(){
		hide_loading_message();
		}, true);
	}, 20000)
	);
	
});