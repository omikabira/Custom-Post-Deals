ajaxurl = pluginVar.ajax;
jQuery(window).on("load", function(event) {
 jQuery("#filter-sectors").on("change", function(event) {
    var sector = jQuery(this).val();

    jQuery.post(ajaxurl, {
        "action": "sector_filter",
        "sector": sector
    }, function (val) {
        jQuery("#all-deals").html(jQuery.parseHTML(val));
    });

});

 jQuery("#filter-stage").on("change", function(event) {
    var stage = jQuery(this).val();

    jQuery.post(ajaxurl, {
        "action": "stage_filter",
        "stage": stage
    }, function (val) {
        jQuery("#all-deals").html(jQuery.parseHTML(val));
    });

});

 jQuery("#filter_fund").on("change", function(event) {
    var fund = jQuery(this).val();

    jQuery.post(ajaxurl, {
        "action": "fund_filter",
        "fund": fund
    }, function (val) {
        jQuery("#all-deals").html(jQuery.parseHTML(val));
    });

});

});