<?php

if($queryTerms != "") {

?>

	<div class="results-block" id="results-website">

		<h2>Our Website <a href="/find/website?Ntt=<?php echo $queryTerms; ?>" class="callbox" style="margin-left: 10px;" <?php echo 'onclick="_gaq.push([\'_trackEvent\', \'BentoResults\', \'OurWebsite\', \'SeeAll\']);"' ?>>See&nbsp;All&nbsp;&raquo;</a></h2>
		<p class="smaller muted">Collections, policies, news &amp; more</p>

		<div class="results-panel">

			<div id="cse_web"></div>

		</div>

	</div>
	
<?php

}

?>

