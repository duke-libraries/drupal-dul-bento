(function($) {

var myVar=setInterval(function(){replaceMe()},100);

function replaceMe() {
	$("#cse_libguides .gs-no-results-result div:contains('No Results')").html('No Research Guides results found for your term<br /><br /><a href="/research/guides">Browse our guides &raquo;</a> ');
	
}

})(jQuery);