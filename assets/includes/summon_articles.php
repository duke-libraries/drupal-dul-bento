<?php

require_once("functions.php");

echo '<h2>Articles <a href="<?php echo $requestURL; ?>" class="callbox" style="margin-left: 10px;">See All »</a></h2>
		<p class="smaller muted">From journals, newspapers &amp; magazines</p>
		<div class="results-panel">';


////

//$queryTerms = 'puppies';
$pageSize = 6;
$contentType1 = 'Journal Article';
$contentType2 = 'Magazine Article';

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
	
		echo '<div class="document-frame">';
	
			// Title
			echo '<div class="title">';
				echo '<div class="text">';
					echo '<h3 class="resultTitle"><a href="' . $document["link"] . '">' . $document["Title"][0] . '</a></h3>';
				echo '</div>';
			echo '</div>';
	
			// Thumb (removed!)
			//if(isset($document["thumbnail_m"][0])) {
				//echo '<div class="thumbnail">';
					//echo '<a href="' . $document["link"] . '"><img src="' . $document["thumbnail_m"][0] . '" alt="cover artwork" class="artwork"></a>';
				//echo '</div>';
			//}
	
			echo '<div class="document-summary">';
				
				// AUTHORS
				echo '<div class="authors">';
				$authorList = formatAuthor($document);
				if($authorList != "") {
					echo 'by <a href="http://duke.summon.serialssolutions.com/search?s.dym=false&s.q=Author%3A%22' . str_replace(',', '%2C+', $authorList) . '%22">' . $authorList . '</a>';
				}
		
				echo '</div>';
		
		
				// PUBLISHER
				echo '<div class="publisher">';
		
				//Engineering & Technology, ISSN 1750-9637, 12/2012, Volume 7, Issue 12, pp. 102 - 103
		
				$theTitle = $document["PublicationTitle"][0];
				echo htmlentities($theTitle);
		
				if(isset($document["ISSN"][0])) {
		
					echo ', <strong>ISSN</strong> ' . $document["ISSN"][0];
			
				}
		
				if(isset($document["PublicationDate"][0])) {
			
					$theDate = $document["PublicationDate"][0];
			
					echo ', ' . date("m/Y", strtotime($theDate)); 
				}
		
				if(isset($document["Volume"][0])) {
		
					echo ', <strong>Volume</strong> ' . $document["Volume"][0];
			
				}
		
				if(isset($document["Issue"][0])) {
					echo ', <strong>Issue</strong> ' . $document["Issue"][0];
				}
		
				if(isset($document["StartPage"][0])) {
			
					$startPage = $document["StartPage"][0];
		
					if(isset($document["EndPage"][0])) {
				
						$endPage = $document["EndPage"][0];
				
					}
	
					if(isset($startPage) && isset($endPage) && $endPage != $startPage) {
				
						echo ', pp. ' . $startPage . '&ndash;' . $endPage;
			
					} elseif(isset($startPage)) {
				
						echo ', p. ' . $startPage;
			
					}
			
				}
		
		
				echo '</div>';
		
		
				// Content Type
		
				echo '<div class="content-type">';
			
					echo $contentType = $document["ContentType"][0];
			
					if($document["hasFullText"] == 1) {
				
						echo ': <a href="' . $document["link"] . '">Full Text Online</a>';
				
					}
		
				echo '</div>';
		
		
			echo '</div>';
	
		echo '</div>';
		
		}
		
	}
	
}


echo '</div>';


?>