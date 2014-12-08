(function($){ 
$(window).load(function(){
$('div.thumbnail').find('img').each(function () {
    
    var $this = $(this), width = $(this).get(0).naturalWidth;
	var $parentdiv = $(this).closest('div');
    
    if (width < 2) {
        //$this.addClass('hide');
        $parentdiv.addClass('hide');
        
    }
    
});
});
})(jQuery);