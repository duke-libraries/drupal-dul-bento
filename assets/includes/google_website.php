<?php

if($queryTerms != "") {

?>

	<div class="results-block first" id="results-website">

		<h2><div class="anchor-highlight hide">»</div> Our Website <a href="/find/website?Ntt=<?php echo $queryDisplay; ?>" class="callbox" style="margin-left: 10px;" <?php echo 'onClick="ga(\'send\', \'event\', { eventCategory: \'BentoResults\', eventAction: \'OurWebsite\', eventLabel: \'SeeAll\'});"' ?>>See&nbsp;All&nbsp;&raquo;</a></h2>
		<p class="small text-muted">Guides, policies, news &amp; more</p>

		<div class="results-panel" onClick="ga('send', 'event', { eventCategory: 'BentoResults', eventAction: 'OurWebsite', eventLabel: 'resultsDiv'})">

			<!--<div id="cse_web" onClick="ga('send', 'event', { eventCategory: 'BentoResults', eventAction: 'OurWebsite', eventLabel: 'resultsDiv'})"></div>-->

			<gcse:searchresults-only defaultToRefinement="website" id="cse_web"></gcse:searchresults-only>

		</div>

		<div class="see-all">

			<a href="/find/website?Ntt=<?php echo $queryDisplay; ?>" onclick="ga('send', 'event', { eventCategory: 'BentoResults', eventAction: 'OurWebsite', eventLabel: 'SeeAllBottom'});">See all website results</a>

		</div>

	</div>

<?php

}

?>
