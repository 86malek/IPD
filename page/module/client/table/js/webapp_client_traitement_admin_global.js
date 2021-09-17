! function(t) {
    "use strict";
$(document).ready(function(){
var id_cat_mere = $('#table_traitement').attr('data-id');
  var table_companies = $('#table_traitement').dataTable({
	"bStateSave": true,
		
    "ajax": "module/client/table/php/data_client_traitement.php?job=get_traitement_admin_globale&id_cat_mere=" + id_cat_mere,
	"deferRender": true,
    "columns": [
	
	{ "data": "date", "sClass": "" },
	
	{ "data": "rs", "sClass": "" },
	{ "data": "ad1", "sClass": "" },
	{ "data": "ad2", "sClass": "" },
	{ "data": "ad3", "sClass": "" },
	{ "data": "cp", "sClass": "" },
	{ "data": "ville", "sClass": "" },
	{ "data": "tel", "sClass": "" },
	{ "data": "fax", "sClass": "" },
	{ "data": "siret", "sClass": "" },
	{ "data": "es", "sClass": "" },
	{ "data": "eg", "sClass": "" },
	{ "data": "ca", "sClass": "" },
	
	{ "data": "rs_o", "sClass": "" },
	{ "data": "ad1_o", "sClass": "" },
	{ "data": "ad2_o", "sClass": "" },
	{ "data": "ad3_o", "sClass": "" },
	{ "data": "cp_o", "sClass": "" },
	{ "data": "ville_o", "sClass": "" },
	{ "data": "tel_o", "sClass": "" },
	{ "data": "fax_o", "sClass": "" },
	{ "data": "siret_o", "sClass": "" },
	{ "data": "es_o", "sClass": "" },
	{ "data": "est_o", "sClass": "" },
	{ "data": "eg_o", "sClass": "" },
	{ "data": "egt_o", "sClass": "" },
	{ "data": "en_o", "sClass": "" },
	{ "data": "ent_o", "sClass": "" },
	{ "data": "ca_o", "sClass": "" },
	{ "data": "cat_o", "sClass": "" },
	
	{ "data": "collab_societe", "sClass": "" },
	{ "data": "temps_societe", "sClass": "" },
	{ "data": "mood_societe", "sClass": "" },
	
	{ "data": "title_o", "sClass": "" },
	{ "data": "prenom_o", "sClass": "" },
	{ "data": "nom_o", "sClass": "" },
	{ "data": "fc_o", "sClass": "" },
	{ "data": "email_o", "sClass": "" },
	{ "data": "title", "sClass": "" },
	{ "data": "prenom", "sClass": "" },
	{ "data": "nom", "sClass": "" },
	{ "data": "fc", "sClass": "" },
	{ "data": "n_service", "sClass": "" },
	{ "data": "email", "sClass": "" },
	{ "data": "lk", "sClass": "" },
	{ "data": "tel2", "sClass": "" },

	{ "data": "com", "sClass": "" },
	
	{ "data": "fonction", "sClass": "" },
	
	{ "data": "collab", "sClass": "" },
	{ "data": "temps", "sClass": "" },
	{ "data": "mood", "sClass": "" }


    ],
    dom: 'Bfrtip',
	"buttons": [
	'csv', {
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
        "sFirst":       "<<",
        "sPrevious":    "Précédent",
        "sNext":        "Suivant",
        "sLast":        ">>",
      },
      "sLengthMenu":    "Cartes par page : _MENU_",
      "sInfo":          "Total de _TOTAL_ Cartes (Affichage _START_ à _END_)",
	  "sSearch":          "Recherche : ",
      "sInfoFiltered":  "(Filtré depuis _MAX_ total Cartes)",
	  "sLoadingRecords": "Chargement en cours..."
    }
  });  
  
  
  
  $(document).on('click', '#mood_affichage', function(){
      var temps_reel      = $(this).data('id');
		t.dialog({
			title: "Nb de modifications",
			content: "url:module/client/table/data/mood-table.php?id_stat=" + temps_reel,
			animation: 'zoom',
			columnClass: 'medium',
			closeAnimation: 'scale',
			backgroundDismiss: false,
			closeIcon: true,
			draggable: false
		  });
    });
  
  
  
  
   

});
}(jQuery);