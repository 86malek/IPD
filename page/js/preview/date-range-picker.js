(function ($) {
  'use strict';

  $(document).ready(function() {
    $('.js-date-range').daterangepicker();

    var start = moment().subtract(29, 'days');
    var end = moment();

    $('.js-date-custom-ranges').daterangepicker({
      startDate: start,
      endDate: end,
      ranges: {
        'Aujourd\'hui': [moment(), moment()],
        'Hier': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        '7 derniers jours': [moment().subtract(6, 'days'), moment()],
        '30 derniers jours': [moment().subtract(29, 'days'), moment()],
        'Ce mois-ci': [moment().startOf('month'), moment().endOf('month')],
        'Le mois dernier': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
      },
	  "locale": {
        "format": "YYYY-MM-DD",
        "separator": " - ",
        "applyLabel": "Confirmer",
        "cancelLabel": "Annuler",
        "fromLabel": "between",
        "toLabel": "and",
		"cancelLabel": 'Annuler',
        "customRangeLabel": "Manuelle",
        "daysOfWeek": [
            "Di",
            "Lu",
            "Ma",
            "Me",
            "Je",
            "Ve",
            "Sa"
        ],
        "monthNames": [
            "Janvier",
            "Février",
            "Mars",
            "Avril",
            "Mai",
            "Juin",
            "Juillet",
            "Août",
            "Septembre",
            "Octobre",
            "Novembre",
            "Décembre"
        ],
        "firstDay": 1
    }
    });
  });
})(jQuery);
