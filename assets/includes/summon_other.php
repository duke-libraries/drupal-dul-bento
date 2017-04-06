<?php

$summonOtherTimeStart = microtime(true);

require_once("functions.php");

//$queryTerms = 'science';
$pageSize = 2;
$facetParameterSetting = "setHoldingsOnly(false)";
$section = "Other Search";


// Load list of Content Types
$content_items = variable_get('dul_bento.summon_other_content_types', '');

$myContentTypes = array();
$formatedContentTypes = array();
$urlContentTypes = array();

$myContentTypes = explode("\n", str_replace("\r", "", $content_items));

// format for Summon API Query
foreach($myContentTypes as $type) {
   array_push($formatedContentTypes, 'addFacetValueFilters(ContentType,' . $type . ')');
}

// format for 'See All' links
foreach($myContentTypes as $type) {
   array_push($urlContentTypes, 'ContentType,' . $type . ',f');
}


// Load list of Libraries
$library_items = variable_get('dul_bento.summon_other_libraries', '');

$myLibraries = array();
$formatedLibraries = array();
$urlLibraries = array();

$myLibraries = explode("\n", str_replace("\r", "", $library_items));

// format for Summon API Query
foreach($myLibraries as $item) {
   array_push($formatedLibraries, 'addFacetValueFilters(Library,' . $item . ')');
}

// format for 'See All' links
foreach($myLibraries as $item) {
   array_push($urlLibraries, $item . ',f');
}


// Merge the Arrays
$contentTypes = array_merge($formatedContentTypes, $formatedLibraries);

// Merge URL Arrays
$URLTypes = array_merge($urlContentTypes, $urlLibraries);
$seeAllURL = '//duke.summon.serialssolutions.com/#!/search?ho=t&fvf=' . urlencode(implode('|', $URLTypes)) . '&l=en&q=';


$theSearch = urlencode($queryTerms);

///


if($queryTerms != "") {


	echo '<div class="results-block" id="results-other">';

		echo '<h2><div class="anchor-highlight hide">»</div> Other Resources <a href="' . $seeAllURL . $queryDisplay . '" class="callbox" style="margin-left: 10px;" onClick="ga(\'send\', \'event\', { eventCategory: \'BentoResults\', eventAction: \'OtherResources\', eventLabel: \'SeeAll\'});">See&nbsp;All&nbsp;&raquo;</a></h2>
					<p class="small text-muted">Research databases, encyclopedias &amp; more</p>
					<div class="results-panel">';

		////



		$data = querySummonDUL($queryTerms, $pageSize, $contentTypes, $facetParameterSetting, $section);

		$theData = json_decode($data, TRUE);

		// debug
		// print_r($theData);


		if ($theData['recordCount'] == "0") {

			echo '<div class="no-results">';

			echo "No Other Resources results found for <em>" . $queryDisplay . "</em>.";

			echo '<br/><br/><a href="' . $seeAllURL . '" onClick="ga(\'send\', \'event\', { eventCategory: \'BentoResults\', eventAction: \'OtherResources\', eventLabel: \'TryAnotherSearch\'});">Try another search &raquo;</a>';

			echo '</div>';

		} elseif ($theData['recordCount'] == "") {

			echo '<div class="no-results">';

			echo "There was an error while searching for <em>" . $queryDisplay . "</em>.";

			echo '<br/><br/><a href="' . $seeAllURL . '" onClick="ga(\'send\', \'event\', { eventCategory: \'BentoResults\', eventAction: \'OtherResources\', eventLabel: \'TryAnotherSearch\'});">Try another search &raquo;</a>';

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

					// Title
					echo '<div class="title">';
						echo '<div class="text">';
							echo '<h3 class="resultTitle"><a href="' . $document["link"] . '" onClick="ga(\'send\', \'event\', { eventCategory: \'BentoResults\', eventAction: \'OtherResources\', eventLabel: \'ItemTitle' . $resultCount . '\'});">' . $document["Title"][0] . '</a></h3>';
						echo '</div>';
					echo '</div>';


					echo '<div class="document-summary">';

						// AUTHORS
						echo '<div class="authors">';

						$authorList = formatAuthor($document);

						if($authorList != "") {

							// truncate long author lists
							if (strlen($authorList) > 125) {
								$authorList = wordwrap($authorList, 125);
								$authorList = substr($authorList, 0, strpos($authorList, "\n"));
								$authorListDisplay = $authorList . ' (&hellip;)';
							}

							else {

								$authorListDisplay = $authorList;

							}

							echo 'by <a href="http://duke.summon.serialssolutions.com/search?s.dym=false&s.q=Author%3A%22' . str_replace(',', '%2C+', $authorList) . '%22" onClick="ga(\'send\', \'event\', { eventCategory: \'BentoResults\', eventAction: \'OtherResources\', eventLabel: \'ItemAuthor' . $resultCount . '\'});">' . $authorListDisplay . '</a>';
						}

						echo '</div>';


						// PUBLISHER
						echo '<div class="publisher">';

						//Engineering & Technology, ISSN 1750-9637, 12/2012, Volume 7, Issue 12, pp. 102 - 103

						if (isset ($document["PublicationTitle"][0])) {

							$theTitle = $document["PublicationTitle"][0];
							echo htmlentities($theTitle);

						}

						if (isset ($document["PublicationTitle"][0]) AND isset($document["ISSN"][0])) {

							echo ', ';

						}



						if(isset($document["ISSN"][0])) {

							echo '<strong>ISSN</strong> ' . $document["ISSN"][0];

						}


						if (isset ($document["PublicationDate"][0]) AND isset($document["ISSN"][0])) {

							echo ', ';

						}


						if(isset($document["PublicationDate"][0])) {

							$theDate = $document["PublicationDate"][0];

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

								echo ': <a href="' . $document["link"] . '" onClick="ga(\'send\', \'event\', { eventCategory: \'BentoResults\', eventAction: \'OtherResources\', eventLabel: \'ItemFullText' . $resultCount . '\'});">Full Text Online</a>';

							}

						echo '</div>';


					// clear all variables
					unset($theTitle);
					unset($authorList);
					unset($authorListDisplay);
					unset($thePubTitle);
					unset($theDate);
					unset($startPage);
					unset($endPage);
					unset($contentType);


					echo '</div>';

				echo '</div>';

			}

			unset ($resultCount);

		}

		echo '</div>';


    // See all bottom link

		if ($theData['recordCount'] > "1" AND $theData['recordCount'] != "") {

			echo '<div class="see-all">';

				echo '<a href="' . $seeAllURL . $queryDisplay . '" onClick="ga(\'send\', \'event\', { eventCategory: \'BentoResults\', eventAction: \'OtherResources\', eventLabel: \'SeeAllBottom\'});">See all <strong>' . number_format($theData['recordCount']) . '</strong> other resource results</a>';

			echo '</div>';

		}


	echo '</div>';

	}

$summonOtherTimeEnd = microtime(true);

// Check for logging
$bentoLogging = variable_get('dul_bento.bento_logging', '');

if ($bentoLogging == 1) {

	global $summonOtherCreationTime;

	$summonOtherCreationTime = ($summonOtherTimeEnd - $summonOtherTimeStart);

}

?>
