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

			// Local IDs, or Sys(tem) Numbers as known by ALEPH
			// are represented by a "titlelocalid" attribute.
			$( 'DIV[titlelocalid]' ).each(function(ndx, elem) {
			
			});
		}
	}
})(jQuery);
