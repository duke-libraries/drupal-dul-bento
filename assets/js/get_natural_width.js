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