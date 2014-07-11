<?php

require_once("functions.php");

//$queryTerms = 'science';
$pageSize = 3;
$facetParameterSetting = "setHoldingsOnly(false)";


// Load list of Content Types
$content_items = variable_get('dul_bento.summon_other_content_types', '');

$myContentTypes = array();
$formatedContentTypes = array();

$myContentTypes = explode("\n", str_replace("\r", "", $content_items));

foreach($myContentTypes as $type) {
   array_push($formatedContentTypes, 'addFacetValueFilters(ContentType,' . $type . ')');
}


// Load list of Libraries
$library_items = variable_get('dul_bento.summon_other_libraries', '');

$myLibraries = array();
$formatedLibraries = array();

$myLibraries = explode("\n", str_replace("\r", "", $library_items));


foreach($myLibraries as $item) {
   array_push($formatedLibraries, 'addFacetValueFilters(Library,' . $item . ')');
}


// Merge the Arrays
$contentTypes = array_merge($formatedContentTypes, $formatedLibraries);


$theSearch = urlencode($queryTerms);

///


echo '<div class="results-block" id="results-other">';

	echo '<h2>Other Resources <a href="http://duke.summon.serialssolutions.com/search?utf8=%E2%9C%93&s.fvf%5B%5D=ContentType%2CNewspaper+Article%2Ct&s.fvf%5B%5D=ContentType%2CJournal+Article%2Ct&s.fvf%5B%5D=ContentType%2CBook+Review%2Ct&s.fvf%5B%5D=ContentType%2CMagazine+Article%2Ct&s.fvf%5B%5D=ContentType%2CTrade+Publication+Article%2Ct&s.fvf%5B%5D=ContentType%2CArchitectural+Drawing&s.fvf%5B%5D=ContentType%2CArchival+Material&s.fvf%5B%5D=ContentType%2CArtifact&s.fvf%5B%5D=ContentType%2CAtlas&s.fvf%5B%5D=ContentType%2CAudio+Recording&s.fvf%5B%5D=ContentType%2CBlueprints&s.fvf%5B%5D=ContentType%2CBook+%2F+eBook&s.fvf%5B%5D=ContentType%2CBook+Chapter&s.fvf%5B%5D=ContentType%2CCase&s.fvf%5B%5D=ContentType%2CCompact+Disc&s.fvf%5B%5D=ContentType%2CComputer+File&s.fvf%5B%5D=ContentType%2CConference+Proceeding&s.fvf%5B%5D=ContentType%2CCourse+Reading&s.fvf%5B%5D=ContentType%2CData+Set&s.fvf%5B%5D=ContentType%2CDatabase&s.fvf%5B%5D=ContentType%2CDissertation&s.fvf%5B%5D=ContentType%2CElectronic+Resource&s.fvf%5B%5D=ContentType%2CFilm&s.fvf%5B%5D=ContentType%2CFinding+Aid&s.fvf%5B%5D=ContentType%2CGovernment+Document&s.fvf%5B%5D=ContentType%2CInteractive+Media&s.fvf%5B%5D=ContentType%2CJournal+%2F+eJournal&s.fvf%5B%5D=ContentType%2CKit&s.fvf%5B%5D=ContentType%2CLibrary+Holding&s.fvf%5B%5D=ContentType%2CMagazine&s.fvf%5B%5D=ContentType%2CManuscript&s.fvf%5B%5D=ContentType%2CMarket+Research&s.fvf%5B%5D=ContentType%2CMicrofilm&s.fvf%5B%5D=ContentType%2CModel&s.fvf%5B%5D=ContentType%2CMusic+Recording&s.fvf%5B%5D=ContentType%2CMusic+Score&s.fvf%5B%5D=ContentType%2CNewsletter&s.fvf%5B%5D=ContentType%2CNewspaper&s.fvf%5B%5D=ContentType%2CPamphlet&s.fvf%5B%5D=ContentType%2CPaper&s.fvf%5B%5D=ContentType%2CPatent&s.fvf%5B%5D=ContentType%2CPerformance&s.fvf%5B%5D=ContentType%2CPlay&s.fvf%5B%5D=ContentType%2CPoem&s.fvf%5B%5D=ContentType%2CPresentation&s.fvf%5B%5D=ContentType%2CPublication&s.fvf%5B%5D=ContentType%2CRealia&s.fvf%5B%5D=ContentType%2CReference&s.fvf%5B%5D=ContentType%2CReport&s.fvf%5B%5D=ContentType%2CSheet+Music&s.fvf%5B%5D=ContentType%2CSpecial+Collection&s.fvf%5B%5D=ContentType%2CSpoken+Word+Recording&s.fvf%5B%5D=ContentType%2CStandard&s.fvf%5B%5D=ContentType%2CStreaming+Video&s.fvf%5B%5D=ContentType%2CStudent+Thesis&s.fvf%5B%5D=ContentType%2CTechnical+Report&s.fvf%5B%5D=ContentType%2CTranscript&s.fvf%5B%5D=ContentType%2CVideo+Recording&s.fvf%5B%5D=ContentType%2CWeb+Resource&s.fvf%5B%5D=Library%2CDuke+Internet+Resource%2Ct&s.fvf%5B%5D=Library%2CPerkins%2FBostock+Library%2Ct&s.fvf%5B%5D=Library%2CFord+Library%2Ct&s.fvf%5B%5D=Library%2CPerkins+Public+Documents%2FMaps%2Ct&s.fvf%5B%5D=Library%2CMusic+Library%2Ct&s.fvf%5B%5D=Library%2CPerkins%2FBostock+Library+%7BV%7D%2Ct&s.fvf%5B%5D=Library%2CLilly+Library%2Ct&s.fvf%5B%5D=Library%2CMarine+Lab+Library%2Ct&s.fvf%5B%5D=Library%2CLibrary+Service+Center%2Ct&s.fvf%5B%5D=Library%2CGoodson+Law+Library%2Ct&s.fvf%5B%5D=Library%2CRubenstein+Library%2Ct&s.fvf%5B%5D=Library%2CMedical+Center+Library%2Ct&s.fvf%5B%5D=Library%2CBiol-Env.+Sciences+Library%2Ct&s.fvf%5B%5D=Library%2CDivinity+School+Library%2Ct&s.fvf%5B%5D=Library%2CUniversity+Archives%2Ct&keep_r=true&s.q=' . $queryTerms . '" class="callbox" style="margin-left: 10px;">See All »</a></h2>
				<p class="smaller muted">Research databases, encyclopedias &amp; more</p>
				<div class="results-panel">';

	////




	if($queryTerms == "") {
						
		$searchWarning = "Please enter a search term above.";		
		echo $searchWarning;
			
	} else {

		$data = querySummonDUL($queryTerms, $pageSize, $contentTypes, $facetParameterSetting);
		
		$theData = json_decode($data, TRUE);

	
		if ($theData['recordCount'] == "0") {
		
			echo "No Other Resources results found for <em>" . $queryTerms . "</em>.";
			
			echo '<br/><br/><a href="http://duke.summon.serialssolutions.com/search?utf8=%E2%9C%93&s.fvf%5B%5D=ContentType%2CNewspaper+Article%2Ct&s.fvf%5B%5D=ContentType%2CJournal+Article%2Ct&s.fvf%5B%5D=ContentType%2CBook+Review%2Ct&s.fvf%5B%5D=ContentType%2CMagazine+Article%2Ct&s.fvf%5B%5D=ContentType%2CTrade+Publication+Article%2Ct&s.fvf%5B%5D=ContentType%2CArchitectural+Drawing&s.fvf%5B%5D=ContentType%2CArchival+Material&s.fvf%5B%5D=ContentType%2CArtifact&s.fvf%5B%5D=ContentType%2CAtlas&s.fvf%5B%5D=ContentType%2CAudio+Recording&s.fvf%5B%5D=ContentType%2CBlueprints&s.fvf%5B%5D=ContentType%2CBook+%2F+eBook&s.fvf%5B%5D=ContentType%2CBook+Chapter&s.fvf%5B%5D=ContentType%2CCase&s.fvf%5B%5D=ContentType%2CCompact+Disc&s.fvf%5B%5D=ContentType%2CComputer+File&s.fvf%5B%5D=ContentType%2CConference+Proceeding&s.fvf%5B%5D=ContentType%2CCourse+Reading&s.fvf%5B%5D=ContentType%2CData+Set&s.fvf%5B%5D=ContentType%2CDatabase&s.fvf%5B%5D=ContentType%2CDissertation&s.fvf%5B%5D=ContentType%2CElectronic+Resource&s.fvf%5B%5D=ContentType%2CFilm&s.fvf%5B%5D=ContentType%2CFinding+Aid&s.fvf%5B%5D=ContentType%2CGovernment+Document&s.fvf%5B%5D=ContentType%2CInteractive+Media&s.fvf%5B%5D=ContentType%2CJournal+%2F+eJournal&s.fvf%5B%5D=ContentType%2CKit&s.fvf%5B%5D=ContentType%2CLibrary+Holding&s.fvf%5B%5D=ContentType%2CMagazine&s.fvf%5B%5D=ContentType%2CManuscript&s.fvf%5B%5D=ContentType%2CMarket+Research&s.fvf%5B%5D=ContentType%2CMicrofilm&s.fvf%5B%5D=ContentType%2CModel&s.fvf%5B%5D=ContentType%2CMusic+Recording&s.fvf%5B%5D=ContentType%2CMusic+Score&s.fvf%5B%5D=ContentType%2CNewsletter&s.fvf%5B%5D=ContentType%2CNewspaper&s.fvf%5B%5D=ContentType%2CPamphlet&s.fvf%5B%5D=ContentType%2CPaper&s.fvf%5B%5D=ContentType%2CPatent&s.fvf%5B%5D=ContentType%2CPerformance&s.fvf%5B%5D=ContentType%2CPlay&s.fvf%5B%5D=ContentType%2CPoem&s.fvf%5B%5D=ContentType%2CPresentation&s.fvf%5B%5D=ContentType%2CPublication&s.fvf%5B%5D=ContentType%2CRealia&s.fvf%5B%5D=ContentType%2CReference&s.fvf%5B%5D=ContentType%2CReport&s.fvf%5B%5D=ContentType%2CSheet+Music&s.fvf%5B%5D=ContentType%2CSpecial+Collection&s.fvf%5B%5D=ContentType%2CSpoken+Word+Recording&s.fvf%5B%5D=ContentType%2CStandard&s.fvf%5B%5D=ContentType%2CStreaming+Video&s.fvf%5B%5D=ContentType%2CStudent+Thesis&s.fvf%5B%5D=ContentType%2CTechnical+Report&s.fvf%5B%5D=ContentType%2CTranscript&s.fvf%5B%5D=ContentType%2CVideo+Recording&s.fvf%5B%5D=ContentType%2CWeb+Resource&s.fvf%5B%5D=Library%2CDuke+Internet+Resource%2Ct&s.fvf%5B%5D=Library%2CPerkins%2FBostock+Library%2Ct&s.fvf%5B%5D=Library%2CFord+Library%2Ct&s.fvf%5B%5D=Library%2CPerkins+Public+Documents%2FMaps%2Ct&s.fvf%5B%5D=Library%2CMusic+Library%2Ct&s.fvf%5B%5D=Library%2CPerkins%2FBostock+Library+%7BV%7D%2Ct&s.fvf%5B%5D=Library%2CLilly+Library%2Ct&s.fvf%5B%5D=Library%2CMarine+Lab+Library%2Ct&s.fvf%5B%5D=Library%2CLibrary+Service+Center%2Ct&s.fvf%5B%5D=Library%2CGoodson+Law+Library%2Ct&s.fvf%5B%5D=Library%2CRubenstein+Library%2Ct&s.fvf%5B%5D=Library%2CMedical+Center+Library%2Ct&s.fvf%5B%5D=Library%2CBiol-Env.+Sciences+Library%2Ct&s.fvf%5B%5D=Library%2CDivinity+School+Library%2Ct&s.fvf%5B%5D=Library%2CUniversity+Archives%2Ct&keep_r=true&s.q=">Try another search &raquo;</a>';
	
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
	
				// Thumb
				if(isset($document["thumbnail_m"][0])) {
				
					$imagePath = $document["thumbnail_s"][0];
					$imageSize = getimagesize($imagePath);
							
					if ($imageSize[0] != '1') {
				
						echo '<div class="thumbnail">';
							echo '<a href="' . $document["link"] . '"><img src="' . $document["thumbnail_m"][0] . '" alt="cover artwork" class="artwork"></a>';
						echo '</div>';
						
					}
					
					
				}
	
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
				
							echo ': <a href="' . $document["link"] . '">Full Text Online</a>';
				
						}
		
					echo '</div>';
		
		
				echo '</div>';
	
			echo '</div>';
		
			}
		
		}
	
	}


	echo '</div>';
	
echo '</div>';


?>