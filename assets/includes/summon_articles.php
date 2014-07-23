<?php

require_once("functions.php");

//$queryTerms = 'science';
$pageSize = 9;
$contentTypes = array('Journal Article','Magazine Article');
$facetParameterSetting = "setHoldingsOnly(true)"; // Limit to records held by Duke

$formatedContentTypes = array();

foreach($contentTypes as $type) {
	array_push($formatedContentTypes, 'addFacetValueFilters(ContentType,' . $type . ')');
}

$contentTypes = $formatedContentTypes;


$theSearch = urlencode($queryTerms);

///


if($queryTerms != "") {
						

echo '<div class="results-block first" id="results-articles">';


	echo '<h2>Articles <a href="http://duke.summon.serialssolutions.com/search?s.fvf%5B%5D=ContentType%2CJournal+Article%2Cf&s.fvf%5B%5D=ContentType%2CMagazine+Article%2Cf&keep_r=true&articleScope=s.q&s.q=' . $queryTerms . '" class="callbox" style="margin-left: 10px;">See&nbsp;All&nbsp;&raquo;</a></h2>
			<p class="smaller muted">From journals, newspapers &amp; magazines</p>
			<div class="results-panel">';


	////


	$data = querySummonDUL($queryTerms, $pageSize, $contentTypes, $facetParameterSetting);

	$theData = json_decode($data, TRUE);

	Debug:
	//echo "The data:<br />";
	//print_r($data);


	if ($theData['recordCount'] == "0") {
	
		echo "No Articles results found for <em>" . $queryTerms . "</em>.";
	
		echo '<br/><br/><a href="http://duke.summon.serialssolutions.com/">Try another search &raquo;</a>';

	} else {


		// Loop through results array and add markup!

		foreach($theData['documents'] as $document) {

		echo '<div class="document-frame">';
		
			$theTitle = $document["Title"][0];
		
			// truncate long titles
			if (strlen($theTitle) > 185) {
				$theTitle = wordwrap($theTitle, 185);
				$theTitle = substr($theTitle, 0, strpos($theTitle, "\n"));
				$theTitle = $theTitle . ' (&hellip;)';
			}

		
			// Title
			echo '<div class="title">';
				echo '<div class="text">';
					echo '<h3 class="resultTitle"><a href="' . $document["link"] . '">' . $theTitle . '</a></h3>';
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
	
				$thePubTitle = (string) $document["PublicationTitle"][0];
				$thePubTitle = rtrim(htmlentities($thePubTitle, ENT_QUOTES, 'UTF-8'), '.');
			
				echo $thePubTitle;
	
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


	echo '</div>';

echo '</div>';

}

?>