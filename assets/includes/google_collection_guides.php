<?php

if($queryTerms != "") {

?>

	<div class="results-block first" id="results-collection-guides">

		<h3><div class="anchor-highlight hide">»</div> Collection Guides <a href="//archives.lib.duke.edu/?q=<?php echo $queryDisplay; ?>&group=true" class="callbox" style="margin-left: 10px;" <?php echo 'onClick="ga(\'send\', \'event\', { eventCategory: \'BentoResults\', eventAction: \'CollectionGuides\', eventLabel: \'SeeAll\'});"' ?>>See&nbsp;All&nbsp;&raquo;</a></h3>
		<p class="small text-muted">Detailed inventories of archival collections</p>

		<div class="results-panel" onClick="ga('send', 'event', { eventCategory: 'BentoResults', eventAction: 'CollectionGuides', eventLabel: 'resultsDiv'})">

			<!--<div id="cse_libguides"></div>-->

			<gcse:searchresults-only defaultToRefinement="guides" id="cse_libguides"></gcse:searchresults-only>

		</div>

		<div class="see-all">

			<a href="//archives.lib.duke.edu/?q=<?php echo $queryDisplay; ?>&group=true" onclick="ga('send', 'event', { eventCategory: 'BentoResults', eventAction: 'CollectionGuides', eventLabel: 'SeeAllBottom'});">See all collection guide results</a>

		</div>

	</div>


<?php

}

?>
