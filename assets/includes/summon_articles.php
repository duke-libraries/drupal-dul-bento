<?php

$summonArticlesTimeStart = microtime(true);

// cookie variable
//$_SERVER['HTTP_COOKIE'];

require_once("functions.php");

//$queryTerms = 'science';
$pageSize = 10;
$contentTypes = array('Journal Article','Magazine Article','Conference Proceeding','Newspaper Article:t', 'Book Review:t');
$facetParameterSetting = "setHoldingsOnly(true)"; // Limit to records held by Duke
$section = "Articles Search";

$formatedContentTypes = array();

foreach($contentTypes as $type) {
	array_push($formatedContentTypes, 'addFacetValueFilters(ContentType,' . $type . ')');
}

$contentTypes = $formatedContentTypes;


$theSearch = urlencode($queryTerms);

///


if($queryTerms != "") {


echo '<div class="results-block first" id="results-articles">';

	echo '<h2>Articles <a href="http://duke.summon.serialssolutions.com/advanced#!/search?ho=t&fvf=ContentType,Journal%20Article,f|ContentType,Magazine%20Article,f&l=en&q=' . $queryDisplay . '" class="callbox" style="margin-left: 10px;" onClick="ga(\'send\', \'event\', { eventCategory: \'BentoResults\', eventAction: \'Articles\', eventLabel: \'SeeAll\'});">See&nbsp;All&nbsp;&raquo;</a></h2>
			<p class="smaller muted">From journals and magazines</p>
			<div class="results-panel">';


	////


	$data = querySummonDUL($queryTerms, $pageSize, $contentTypes, $facetParameterSetting, $section);

	$theData = json_decode($data, TRUE);

	//Debug:
	//echo "The data:<br />";
	//print_r($data);


	if ($theData['recordCount'] == "0") {

		echo '<div class="no-results">';

		echo "No Articles results found for <em>" . $queryDisplay . "</em>.";

		echo '<br/><br/><a href="http://duke.summon.serialssolutions.com/" onClick="ga(\'send\', \'event\', { eventCategory: \'BentoResults\', eventAction: \'Articles\', eventLabel: \'TryAnotherSearch\'});">Try another search &raquo;</a>';

		echo '</div>';


	} elseif ($theData['recordCount'] == "") {


		echo '<div class="no-results">';
		echo "A network or server error was encountered while searching for <em>" . $queryDisplay . "</em>. Please try again in a few moments.";

		echo '<br/><br/>If you continue to encounter this error, please <a href="//library.duke.edu/research/ask" onClick="ga(\'send\', \'event\', { eventCategory: \'BentoResults\', eventAction: \'Articles\', eventLabel: \'GetHelp\'});">report the problem to Library Staff</a>.';

		echo '</div>';


	} else {

		$resultCount = 0; // for GA event tracking

		// Loop through results array and add markup!

		//foreach($theData['documents'] as $document) {
		// 8/20/2014 -- changed loop method to implement counter

		for ($i = 0; $i < count($theData['documents']); $i++) {

			$document = $theData['documents'][$i];

			$resultCount = $i + 1;

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
						echo '<h3 class="resultTitle"><a href="' . $document["link"] . '" onClick="ga(\'send\', \'event\', { eventCategory: \'BentoResults\', eventAction: \'Articles\', eventLabel: \'ItemTitle' . $resultCount . '\'});">' . $theTitle . '</a></h3>';
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

						// split into array based on 'and'

						$authorListTemp = str_replace(";", "and", $authorList);
						$authorListTemp = htmlspecialchars($authorListTemp);
						$authorListArray = explode("and", $authorListTemp);
						$authorCount = count($authorListArray);

						//$authorListDisplay = $authorList;

						echo 'by ';


						// loop through authors array
						$n = 0;
						while ($n < $authorCount) {

							echo '<a href="http://duke.summon.serialssolutions.com/search?s.dym=false&s.q=Author%3A%22' . str_replace(',', '%2C+', $authorListArray[$n]) . '%22" onClick="ga(\'send\', \'event\', { eventCategory: \'BentoResults\', eventAction: \'Articles\', eventLabel: \'ItemAuthor' . $resultCount . '\'});">' . $authorListArray[$n] . '</a>';

							$n ++;

							if ($n < $authorCount) {

								if (($n + 1) == $authorCount) {
									echo ' and ';
								}

								else {
									echo '; ';
								}
							}


						}



						// truncate long lists of authors
						//if (strlen($authorListDisplay) > 175) {
							//$authorListDisplay = wordwrap($authorListDisplay, 175);
							//$authorListDisplay = substr($authorListDisplay, 0, strpos($authorListDisplay, "\n"));
							//$authorListDisplay = $authorListDisplay . ' (&hellip;)';
						//}

						//echo '<a href="http://duke.summon.serialssolutions.com/search?s.dym=false&s.q=Author%3A%22' . str_replace(',', '%2C+', $authorList) . '%22" onClick="ga(\'send\', \'event\', { eventCategory: \'BentoResults\', eventAction: \'Articles\', eventLabel: \'ItemAuthor' . $resultCount . '\'});">' . $authorListDisplay . '</a>';

					}

					echo '</div>';


					// PUBLISHER
					echo '<div class="publisher">';

					//Engineering & Technology, ISSN 1750-9637, 12/2012, Volume 7, Issue 12, pp. 102 - 103

					if (isset($document["PublicationTitle"][0])) {
						$thePubTitle = (string) $document["PublicationTitle"][0];
						$thePubTitle = rtrim(htmlentities($thePubTitle, ENT_QUOTES, 'UTF-8'), '.');
					} else {
						$thePubTitle = "";
					}

					echo $thePubTitle;


					// removed issn display
					//if(isset($document["ISSN"][0])) {

						//if ($thePubTitle != "") {

							//echo ', ';

						//}

						//echo '<strong>ISSN</strong> ' . $document["ISSN"][0];

					//}

					if(isset($document["PublicationDate"][0])) {

						if ($thePubTitle != "") {

							echo ', ';

						}

						$theDate = $document["PublicationDate"][0];

						// not needed without issn
						//if(isset($document["ISSN"][0])) {

							//echo ', ';

						//}

						echo date("m/Y", strtotime($theDate));
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

							echo ': <a href="' . $document["link"] . '" onClick="ga(\'send\', \'event\', { eventCategory: \'BentoResults\', eventAction: \'Articles\', eventLabel: \'ItemFullText' . $resultCount . '\'});">Full Text Online</a>';

						}

					echo '</div>';



				// clear all variables
					unset($theTitle);
					unset($authorList);
					unset($authorListTemp);
					unset($authorListArray);
					unset($authorCount);
					unset($thePubTitle);
					unset($theDate);
					unset($startPage);
					unset($endPage);
					unset($contentType);
					unset($resultCount);



				echo '</div>';


			echo '</div>';


		}

	}


	echo '</div>';


	// See all bottom link

	if ($theData['recordCount'] != "0" AND $theData['recordCount'] != "") {

		echo '<div class="see-all">';

			echo '<a href="http://duke.summon.serialssolutions.com/advanced#!/search?ho=t&fvf=ContentType,Journal%20Article,f|ContentType,Magazine%20Article,f&l=en&q=' . $queryDisplay . '" onClick="ga(\'send\', \'event\', { eventCategory: \'BentoResults\', eventAction: \'Articles\', eventLabel: \'SeeAllBottom\'});">See All Results</a>';

		echo '</div>';

	}



echo '</div>';

}

$summonArticlesTimeEnd = microtime(true);

// Check for logging
$bentoLogging = variable_get('dul_bento.bento_logging', '');

if ($bentoLogging == 1) {

	global $summonArticlesCreationTime;

	$summonArticlesCreationTime = ($summonArticlesTimeEnd - $summonArticlesTimeStart);

}

?>
