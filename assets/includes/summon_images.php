<?php

require_once("functions.php");

echo '<h2>Images <a href="http://duke.summon.serialssolutions.com/search?s.cmd=removeFacetValueFilter(ContentType,Book+Review)&s.fvf%5B%5D=ContentType,Image,f&s.fvf%5B%5D=ContentType,Photograph,f&s.fvf%5B%5D=ContentType,Book+Review,t&s.light=t&s.q=' . $queryTerms . '" class="callbox" style="margin-left: 10px;">See All »</a></h2>
		<p class="smaller muted">From our digitized collections</p>
		<div class="results-panel">';


////

//$queryTerms = 'asdasd asdasdad';
$pageSize = 3;
$contentType1 = 'Image';
$contentType2 = 'Photograph';

$theSearch = urlencode($queryTerms);


if($queryTerms == "") {
						
	$searchWarning = "Please enter a search term above.";		
	echo $searchWarning;
		
} else {

	//querySummon($query, $results, $type)
	$data = querySummonDUL($queryTerms, $pageSize, $contentType1, $contentType2);
	$theData = json_decode($data, TRUE);

	//Debug:
	//echo "The data:<br />";
	//print_r($data);
	
	if ($theData['recordCount'] == "0") {
		
		echo "No results found.";
	
	} else {

		// Loop through results array and add markup!

		foreach($theData['documents'] as $document) {
			
			// truncate long titles
			$theTitle = $document["Title"][0];
	
			if (strlen($theTitle) > 110) {
				$theTitle = wordwrap($theTitle, 110);
				$theTitle = substr($theTitle, 0, strpos($theTitle, "\n"));
				$theTitle = $theTitle . ' (&hellip;)';
			}
			
			
			//if(isset($document["thumbnail_s"][0])) {
		
				echo '<div class="document-frame">';
					
					if(isset($document["thumbnail_s"][0])) {
						echo '<div class="thumbnail">';
							echo '<a href="' . $document["link"] . '"><img src="' . $document["thumbnail_s"][0] . '" alt="cover artwork" class="artwork"></a>';
						echo '</div>';
					
					} else {
						echo '<div class="thumbnail">';
							echo '<a href="' . $document["link"] . '"><img src="'. $GLOBALS['base_path'] . drupal_get_path('module', 'dul_bento') .  '/assets/images/no_image_available.png" alt="No image available" class="artwork"></a>';
						echo '</div>';
					
					}
				
					echo '<div class="result-title">';
						echo '<a href="' . $document["link"] . '">' . $theTitle . '</a>';
					echo '</div>';
			
				echo '</div>';

			//}
		
		}
		
	}
	
}


echo '</div>';


?>