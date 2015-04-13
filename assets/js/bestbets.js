jQuery(document).ready(function(){
    if (window.innerWidth > 640) {
        jQuery('.best-bet-flag').delay(50).fadeIn(500);
    } else {
        jQuery('.best-bet-flag').remove();
    }
});