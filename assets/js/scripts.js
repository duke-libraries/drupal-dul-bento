// Best Bets

jQuery(document).ready(function(){
    if (window.innerWidth > 640) {
        jQuery('.best-bet-flag').delay(50).fadeIn(500);
    } else {
        jQuery('.best-bet-flag').remove();
    }

    if (jQuery('.best-bet-link')[0]) {
        jQuery.ajax({
            url:'/sites/all/modules/dul_bento/assets/includes/bestbets_logger.php?id=' + jQuery('.best-bet-link').data('best-bet-id') + '&event=bb_serve',
            timeout: 10000
        });
    }

    jQuery('.best-bet-link').click(function() {

        jQuery.ajax({
            url:'/sites/all/modules/dul_bento/assets/includes/bestbets_logger.php?id=' + jQuery('.best-bet-link').data('best-bet-id') + '&event=bb_click',
            timeout: 10000
        });
    });
});




// Replace Libguides with no results

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




// Get natural width of thumbnails

(function($){

	$(window).load(function(){

		$('#results-books div.thumbnail').find('img').each(function () {

			var $this = $(this), width = $(this).get(0).naturalWidth;
			var $parentdiv = $(this).closest('div');

			if (width < 2) {
				//$this.addClass('hide');
				$parentdiv.addClass('hide');

			}

		});


		$('#results-images div.thumbnail').find('img').each(function () {

			var $this = $(this), width = $(this).get(0).naturalWidth;
			var $parentdiv = $(this).closest('div');

			if (width < 2) {

				$parentdiv.html('<p>No preview available</p>');

			}

		});

	});


})(jQuery);
