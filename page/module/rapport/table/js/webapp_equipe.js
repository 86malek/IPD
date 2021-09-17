! function(t) {
    "use strict";
$(document).ready(function(){

  

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

  var table_companies = $('#table_equipe').dataTable({
    
    "ajax": "module/rapport/table/php/data_rapport.php?job=get_rapport_equipe",
    "columns": [
      { "data": "equipe",   "sClass": "" },
      { "data": "nb",   "sClass": ""}
    ],
    "info": false,
    paging: false,
    "bFilter": false,
     dom: 'Bfrtip',
      "buttons": [
      {
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
      },'colvis'
    ],
    "oLanguage": {
      "oPaginate": {
        "sFirst":       "<< ",
        "sPrevious":    "Précédent",
        "sNext":        "Suivant",
        "sLast":        ">>",
      },
      "sLengthMenu":    "Lignes par page : _MENU_",
      "sInfo":          "Total de _TOTAL_ Lignes (Lignes _START_ à _END_)",
      "sSearch":          "Recherche",
      "sInfoFiltered":  "(Filtré depuis _MAX_ total Lignes)",
      "sLoadingRecords": "Chargement en cours des données ..."
    }
  }); 

  var date = $("#table_rapport_equipe").attr("data-date");
  var equipe = $("#table_rapport_equipe").attr("data-equipe");
  var table_rapport = $('#table_rapport_equipe').dataTable({
    
    "ajax": "module/rapport/table/php/data_rapport.php?job=get_rapport_stat_equipe&date=" + date + "&equipe=" + equipe,
    "columns": [
      { "data": "mission",   "sClass": "" },
      { "data": "cc",   "sClass": "" },
      { "data": "global",   "sClass": "" },
      { "data": "traite",   "sClass": "" },
      { "data": "datee",   "sClass": "" },
      { "data": "jh",   "sClass": ""},
      { "data": "perf",   "sClass": ""}
    ],
    
    "info": false,
    paging: false,
     dom: 'Bfrtip',
      "buttons": [
      {
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
      },'colvis'
    ],
    "oLanguage": {
      "oPaginate": {
        "sFirst":       "<< ",
        "sPrevious":    "Précédent",
        "sNext":        "Suivant",
        "sLast":        ">>",
      },
      "sLengthMenu":    "Lignes par page : _MENU_",
      "sInfo":          "Total de _TOTAL_ Lignes (Lignes _START_ à _END_)",
      "sSearch":          "Recherche",
      "sInfoFiltered":  "(Filtré depuis _MAX_ total Lignes)",
      "sLoadingRecords": "Chargement en cours des données ..."
    }
    
  });

  var date_collect = $("#table_rapport_equipe_collect").attr("data-date");
  var equipe_collect = $("#table_rapport_equipe_collect").attr("data-equipe");
  var table_rapport = $('#table_rapport_equipe_collect').dataTable({
    
    "ajax": "module/rapport/table/php/data_rapport.php?job=get_rapport_stat_equipe&date=" + date_collect + "&equipe=" + equipe_collect,
    "columns": [
      { "data": "mission",   "sClass": "" },
      { "data": "cc",   "sClass": "" },
      { "data": "global",   "sClass": "" },
      { "data": "traite",   "sClass": "" },
      { "data": "datee",   "sClass": "" },
      { "data": "jh",   "sClass": ""},
      { "data": "perf",   "sClass": ""}
    ],
    "info": false,
    paging: false,
     dom: 'Bfrtip',
      "buttons": [
      {
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
      },'colvis'
    ],
    "oLanguage": {
      "oPaginate": {
        "sFirst":       "<< ",
        "sPrevious":    "Précédent",
        "sNext":        "Suivant",
        "sLast":        ">>",
      },
      "sLengthMenu":    "Lignes par page : _MENU_",
      "sInfo":          "Total de _TOTAL_ Lignes (Lignes _START_ à _END_)",
      "sSearch":          "Recherche",
      "sInfoFiltered":  "(Filtré depuis _MAX_ total Lignes)",
      "sLoadingRecords": "Chargement en cours des données ..."
    }
  }); 

  var date_qd = $("#table_rapport_equipe_qd").attr("data-date");
  var equipe_qd = $("#table_rapport_equipe_qd").attr("data-equipe");
  var table_rapport = $('#table_rapport_equipe_qd').dataTable({
    
    "ajax": "module/rapport/table/php/data_rapport.php?job=get_rapport_stat_equipe&date=" + date_qd + "&equipe=" + equipe_qd,
    "columns": [
      { "data": "mission",   "sClass": "" },
      { "data": "cc",   "sClass": "" },
      { "data": "global",   "sClass": "" },
      { "data": "traite",   "sClass": "" },
      { "data": "datee",   "sClass": "" },
      { "data": "jh",   "sClass": ""},
      { "data": "perf",   "sClass": ""}
    ],
    "info": false,
    paging: false,
     dom: 'Bfrtip',
      "buttons": [
      {
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
      },'colvis'
    ],
    "oLanguage": {
      "oPaginate": {
        "sFirst":       "<< ",
        "sPrevious":    "Précédent",
        "sNext":        "Suivant",
        "sLast":        ">>",
      },
      "sLengthMenu":    "Lignes par page : _MENU_",
      "sInfo":          "Total de _TOTAL_ Lignes (Lignes _START_ à _END_)",
      "sSearch":          "Recherche",
      "sInfoFiltered":  "(Filtré depuis _MAX_ total Lignes)",
      "sLoadingRecords": "Chargement en cours des données ..."
    }
  });

  var date_indus = $("#table_rapport_equipe_indus").attr("data-date");
  var equipe_indus = $("#table_rapport_equipe_indus").attr("data-equipe");
  var table_rapport = $('#table_rapport_equipe_indus').dataTable({
    
    "ajax": "module/rapport/table/php/data_rapport.php?job=get_rapport_stat_equipe&date=" + date_indus + "&equipe=" + equipe_indus,
    "columns": [
      { "data": "mission",   "sClass": "" },
      { "data": "cc",   "sClass": "" },
      { "data": "global",   "sClass": "" },
      { "data": "traite",   "sClass": "" },
      { "data": "datee",   "sClass": "" },
      { "data": "jh",   "sClass": ""},
      { "data": "perf",   "sClass": ""}
    ],
    "info": false,
      paging: false,
     dom: 'Bfrtip',
      "buttons": [
      {
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
      },'colvis'
    ],
    "oLanguage": {
      "oPaginate": {
        "sFirst":       "<< ",
        "sPrevious":    "Précédent",
        "sNext":        "Suivant",
        "sLast":        ">>",
      },
      "sLengthMenu":    "Lignes par page : _MENU_",
      "sInfo":          "Total de _TOTAL_ Lignes (Lignes _START_ à _END_)",
      "sSearch":          "Recherche",
      "sInfoFiltered":  "(Filtré depuis _MAX_ total Lignes)",
      "sLoadingRecords": "Chargement en cours des données ..."
    }
  });
  var date_rf = $("#table_rapport_equipe_rf").attr("data-date");
  var rf = $("#table_rapport_equipe_rf").attr("data-equipe");
  var table_rapport = $('#table_rapport_equipe_rf').dataTable({
    
    "ajax": "module/rapport/table/php/data_rapport.php?job=get_rapport_stat_equipe&date=" + date_rf + "&equipe=" + rf,
    "columns": [
      { "data": "mission",   "sClass": "" },
      { "data": "cc",   "sClass": "" },
      { "data": "global",   "sClass": "" },
      { "data": "traite",   "sClass": "" },
      { "data": "datee",   "sClass": "" },
      { "data": "jh",   "sClass": ""},
      { "data": "perf",   "sClass": ""}
    ],
    "info": false,
    paging: false,
     dom: 'Bfrtip',
      "buttons": [
      {
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
    "oLanguage": {
      "oPaginate": {
        "sFirst":       "<< ",
        "sPrevious":    "Précédent",
        "sNext":        "Suivant",
        "sLast":        ">>",
      },
      "sLengthMenu":    "Lignes par page : _MENU_",
      "sInfo":          "Total de _TOTAL_ Lignes (Lignes _START_ à _END_)",
      "sSearch":          "Recherche",
      "sInfoFiltered":  "(Filtré depuis _MAX_ total Lignes)",
      "sLoadingRecords": "Chargement en cours des données ..."
    }
  });


  $(document).on('click', '#team', function(){
      t.dialog({
        title: "Service IPD",
        content: "url:module/rapport/table/data/equipe-table.php",
        animation: 'zoom',
        columnClass: 'medium',
        closeAnimation: 'scale',
        backgroundDismiss: false,
        closeIcon: true,
        draggable: true
      });
    });


});
}(jQuery);