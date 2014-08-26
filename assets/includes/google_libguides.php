<?php

if($queryTerms != "") {

?>

	<div class="results-block first" id="results-libguides">

		<h2>Research Guides <a href="/research/guides/results?Ntt=<?php echo $queryTerms; ?>" class="callbox" style="margin-left: 10px;" <?php echo 'onClick="ga(\'send\', \'event\', { eventCategory: \'BentoResults\', eventAction: \'ResearchGuides\', eventLabel: \'SeeAll\'});"' ?>>See&nbsp;All&nbsp;&raquo;</a></h2>
		<p class="smaller muted">For assistance researching this topic</p>
	
		<div class="results-panel">
	
			<div id="cse_libguides"></div>
		
		</div>

	</div>


<?php

}

?>



