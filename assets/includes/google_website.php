<?php

if($queryTerms != "") {

?>

	<div class="results-block" id="results-website">

		<h2>Our Website <a href="/find/website?Ntt=<?php echo $queryTerms; ?>" class="callbox" style="margin-left: 10px;" <?php echo 'onClick="ga(\'send\', \'event\', { eventCategory: \'BentoResults\', eventAction: \'OurWebsite\', eventLabel: \'SeeAll\'});"' ?>>See&nbsp;All&nbsp;&raquo;</a></h2>
		<p class="smaller muted">Collections, policies, news &amp; more</p>

		<div class="results-panel">

			<div id="cse_web"></div>

		</div>

	</div>
	
<?php

}

?>

