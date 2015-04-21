jQuery(document).ready(function(){
    if (window.innerWidth > 640) {
        jQuery('.best-bet-flag').delay(50).fadeIn(500);
    } else {
        jQuery('.best-bet-flag').remove();
    }

    if (jQuery('.best-bet-link')) {
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