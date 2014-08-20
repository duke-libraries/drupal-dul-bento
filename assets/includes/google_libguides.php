<?php

if($queryTerms != "") {

?>

	<div class="results-block first" id="results-libguides">

		<h2>Research Guides <a href="http://www.google.com/cse?cx=012356957315223414689%3Aeqn6ecttvlm&ie=UTF-8&q=#gsc.tab=0&gsc.sort=&gsc.q=<?php echo $queryTerms; ?>" class="callbox" style="margin-left: 10px;" <?php echo 'onclick="_gaq.push([\'_trackEvent\', \'BentoResults\', \'ResearchGuides\', \'SeeAll\']);"' ?>>See&nbsp;All&nbsp;&raquo;</a></h2>
		<p class="smaller muted">For assistance researching this topic</p>
	
		<div class="results-panel">
	
			<div id="cse_libguides"></div>
		
		</div>

	</div>


<?php

}

?>



