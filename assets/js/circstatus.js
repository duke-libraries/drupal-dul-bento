/**
 * File: circstatus.js
 * Authors: Derrek Croney (dlc32)
 *
 * Limited port of the functionality of "duke_functions_2.js" (loaded with Endeca pages)
 * that parses markup related to a line returned from Endeca feed (including barcode information), then 
 * invokes AJAX with predefined URL to fetch circulation status and replace a
 * resource's availability string.
 */

(function ($) {
	Drupal.behaviors.dul_bento = {
		attach: function (context, settings) {
			// process all rows returned from Endeca RSS (or XML) feed

			circstatusURL = settings.circstatus_url_prefix;

			// Local IDs, or Sys(tem) Numbers as known by ALEPH
			// are represented by a "titlelocalid" attribute.
			$( 'DIV[titlelocalid]' ).each(function(ndx, elem) {
				sysno = $( this ).attr('titlelocalid');
				if (sysno.length == 0) {
					return;
				}

				url = circstatusURL + sysno + '/';
				$.ajax({
					dataType : 'jsonp',
					url : url,
					type : 'GET',
					timeout : 5000,
					success : function(data, status, o) {
						d = data;
						// locate the element with item-id
						for (barcodeKey in data) {
							barcodeData = data[barcodeKey].data;

							$( 'DIV[itemid="' + barcodeKey + '"]').each(function(ndx, el) {
								console.log( this );
								$( '.library', this ).html( barcodeData['sub-library'] );
							});
						}
					},
					error : function(o, status, err) {
						console.log( err );
					},
					data : { "key" : 'barcode' }
				});
				
			});
		}
	}
})(jQuery);
