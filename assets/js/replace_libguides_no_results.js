(function($) {

	var myVar=setInterval(function(){replaceMe()},100);

	function replaceMe() {
		$("#cse_libguides .gs-no-results-result div:contains('No Website results found for your term.')").html('No Collection Guides results found for your term<br /><br /><a href="//library.duke.edu/rubenstein/findingaids/">Browse our collection guides &raquo;</a> ');

		if ($("#cse_libguides .gs-no-results-result div:contains('Collection')").length) {
			$("#results-collection-guides .see-all").hide();
		}

		if ($("#cse_web .gs-no-results-result div:contains('Website')").length) {
			$("#results-website .see-all").hide();
		}

	}

})(jQuery);
