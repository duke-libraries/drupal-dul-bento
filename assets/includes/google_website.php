<?php

if($queryTerms != "") {

?>

	<div class="results-block" id="results-website">

		<h2>Our Website <a href="http://www.google.com/cse?cx=012356957315223414689%3Atvt-hlpicwm&ie=UTF-8&q=#gsc.tab=0&gsc.sort=&gsc.q=<?php echo $queryTerms; ?>" class="callbox" style="margin-left: 10px;" <?php echo 'onclick="_gaq.push([\'_trackEvent\', \'BentoResults\', \'OurWebsite\', \'SeeAll\']);"' ?>>See&nbsp;All&nbsp;&raquo;</a></h2>
		<p class="smaller muted">Collections, policies, news &amp; more</p>

		<div class="results-panel">

			<div id="cse_web"></div>

		</div>

	</div>
	
<?php

}

?>

