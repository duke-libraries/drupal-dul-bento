<?php


echo '<h2>Research Guides <a href="<?php echo $requestURL; ?>" class="callbox" style="margin-left: 10px;">See All »</a></h2>
		<p class="smaller muted">For assistance researching unfamiliar topics</p>
		<div class="results-panel">';


if($queryTerms != "") {

	$theSearch = urlencode($queryTerms);

	echo "<div id='api_search_guides_iid150'></div>";
	echo "<script type='text/javascript' src='http://api.libguides.com/api_search.php?iid=150&type=guides&limit=3&more=false&sortby=relevance&context=object&format=js&search=" . $theSearch . "'> </script>";
	
	}


echo '</div>';
	


?>





