! function(t) {
    "use strict";
$(document).ready(function() {
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
var stat = $("#table_collect_detail_jour").attr("data-id");
var request_data = $.ajax({
url:          'module/collectivite/table/php/data_collectivite_detail_jour.php?job=table_collect_detail_jour&id_stat=' + stat,
cache:        false,
dataType:     'json',
contentType:  'application/json; charset=utf-8',
type:         'get'
});

request_data.done(function(output){
	
	if (output.result == 'success'){
			(new GrowlNotification({
			  title: 'Terminé!',
			  description: 'Chargement du tableau terminé avec succès.',
			  image: 'img/notifications/03.png',
			  type: 'success',
			  position: 'bottom-right',
			  closeTimeout: 4000
			})).show();
	}else{
			(new GrowlNotification({
			  title: 'Attention!',
			  description: 'Erreur lors du chargement du tableau.',
			  image: 'img/notifications/04.png',
			  type: 'error',
			  position: 'bottom-right',
			  closeTimeout: 4000
			})).show();
	
	}
});

  function d(a, b) {
    $("#message").html("<p>" + a + "</p>").attr("class", b);
    $("#message_container").show();
    "undefined" !== typeof timeout_message && window.clearTimeout(timeout_message);
    timeout_message = setTimeout(function() {
      $("#message").html("").attr("class", "");
      $("#message_container").hide();
    }, 8E3);
  }
  function f() {
    $("#loading_container").show();
  }
  function c() {
    $("#loading_container").hide();
  }
  function k() {
    $(".lightbox_bg").show();
    $(".lightbox_container").show();
  }
  function e() {
    $(".lightbox_bg").hide();
    $(".lightbox_container").hide();
  }
  function l() {
    document.activeElement.blur();
    $("input").blur();
  }
  var m = $("#table_collect_detail_jour").attr("data-id"), g = $("#table_collect_detail_jour").dataTable({bStateSave:!0, fnStateSave:function(a, b) {
    localStorage.setItem("DataTables_" + window.location.pathname, JSON.stringify(b));
  }, fnStateLoad:function(a) {
    return JSON.parse(localStorage.getItem("DataTables_" + window.location.pathname));
  }, ajax:"module/collectivite/table/php/data_collectivite_detail_jour.php?job=table_collect_detail_jour&id_stat=" + m, columns:[{data:"date", sClass:"", "sType": "date-uk"}, {data:"nom", sClass:""}, {data:"collab", sClass:""}, {data:"temps", sClass:""}, {data:"ok", sClass:""}, {data:"ko", sClass:""}, {data:"okh", sClass:""}, {data:"kos", sClass:""}, {data:"ligne", sClass:""}, {data:"ecart", 
  sClass:""}], order:[[0, "asc"]], dom:"Bfrtip", buttons:[{extend:"excelHtml5", exportOptions:{columns:":visible"}}, {extend:"pdfHtml5", exportOptions:{columns:":visible"}}, {extend:"print", exportOptions:{columns:":visible"}}, "colvis"], aoColumnDefs:[{bSortable:!1, aTargets:[-1]}], lengthMenu:[[5, 10, 30, 50, -1], [5, 10, 30, 50, "All"]], oLanguage:{oPaginate:{sFirst:"<<", sPrevious:"Précédent", sNext:"Suivant", sLast:">>"}, sLengthMenu:"Cat\u00e9gories par page : _MENU_", 
  sInfo:"Total de _TOTAL_ Cat\u00e9gories (Affichage _START_ \u00e0 _END_)", sSearch:"Recherche", sInfoFiltered:"(Filtr\u00e9 depuis _MAX_ total Cat\u00e9gories)", sLoadingRecords:"Chargement en cours..."}});
  jQuery.validator.setDefaults({success:"valid", rules:{fiscal_year:{required:!0, min:2E3, max:2025}}, errorPlacement:function(a, b) {
    a.insertBefore(b);
  }, highlight:function(a) {
    $(a).parent(".field_container").removeClass("valid").addClass("error");
  }, unhighlight:function(a) {
    $(a).parent(".field_container").addClass("valid").removeClass("error");
  }});
  var h = $("#form_cat_fichier");
  h.validate();
  $(document).on("click", ".lightbox_bg", function() {
    e();
  });
  $(document).on("click", ".lightbox_close", function() {
    e();
  });
  $(document).keyup(function(a) {
    27 == a.keyCode && e();
  });
});
}(jQuery);