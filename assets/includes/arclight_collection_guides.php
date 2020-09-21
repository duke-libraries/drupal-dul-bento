<?php

//display errors
//ini_set('display_errors',1);
//ini_set('display_startup_errors',1);
//error_reporting(-1);

$arclightGuidesStart = microtime(true);

$baseURL = "https://archives.lib.duke.edu/";
$catalogURL = "https://archives.lib.duke.edu/catalog/";
$searchURLGrouped = "https://archives.lib.duke.edu/?utf8=%E2%9C%93&group=true&search_field=all_fields&q=";
$searchURLAll = "https://archives.lib.duke.edu/catalog?search_field=all_fields&q=";
$urlString = "https://archives.lib.duke.edu/catalog.json?utf8=%E2%9C%93&search_field=all_fields&q=";

$theSearch = urlencode($queryTerms);

$searchResults = "";

$itemCampaignParams = "?utm_campaign=bento&utm_content=bento_result_link&utm_source=library.duke.edu&utm_medium=referral";

$allCampaignParams = "&utm_campaign=bento&utm_content=bento_see_more_link&utm_source=library.duke.edu&utm_medium=referral";

$arclightGuidesJSONStart = microtime(true);

	if ($theSearch != "") {

		// check for blacklight response
		$ch=curl_init();
		$timeout=10;

		curl_setopt($ch, CURLOPT_URL, $urlString . $theSearch);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout+2);

		$chResult=curl_exec($ch);
		$chHTTPCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$chTotalTime = curl_getinfo($ch, CURLINFO_TOTAL_TIME);

		curl_close($ch);

		if ($chResult === false) {
		    $searchResults = "-1";
		} else {

			if ($chHTTPCode == 200 && $chTotalTime < $timeout) {

					//$theXML = simplexml_load_string ($chResult);
					$theJSON = json_decode($chResult,true);

			} else {

					$searchResults = "-1";
			}

		}
	}

$arclightGuidesJSONEnd = microtime(true);

$tempISBN = "";
$isDiffISBN = false;

$i = 0;


if ($theSearch != "" && $searchResults != "-1") {

	$nodeCount = 0;

	//check for results
	foreach ($theJSON['data'] as $key=>$value){
	    $nodeCount += 1;
	}

	// broken path...
	if ($nodeCount == 0) {

		$searchResults = "-1";
		// error output on line ~923

	}

	// there is a node!
	else {

		$numTotalResults = $theJSON['meta']['pages']['total_count'];

		// check for actual results
		if ($numTotalResults == "0") {
			$searchResults = "0";
		} else {
			$searchResults = "1";
		}

	}

}


if ($theSearch != "") {

?>


	<div class="results-block first" id="results-collection-guides">

		<div class="resultsHeader">

				<h3><div class="anchor-highlight hide">»</div> Collection Guides <a href="<?php echo $searchURLGrouped . $theSearch . $allCampaignParams; ?>" class="callbox" style="margin-left: 10px;" <?php echo 'onClick="ga(\'send\', \'event\', { eventCategory: \'BentoResults\', eventAction: \'CollectionGuides\', eventLabel: \'SeeAll\'});"' ?>>See&nbsp;All&nbsp;&raquo;</a></h3>

				<p class="small text-muted">Detailed inventories of archival collections</p>

		<?php

		if ($searchResults != "0" AND $searchResults != "-1" AND $theSearch != "") {

			$resultCount = 0; // for GA event tracking

			// number of results to display
			if ($nodeCount < 3) {
				$maxResults = $nodeCount;
			} else {
				$maxResults = 3;
			}

			include("locations_lookup.php");

			while ($i < $maxResults):

				$resultCount = $i + 1;

				$title = $theJSON['data'][$i]['attributes']['normalized_title']['attributes']['value'];
	      $ID = $theJSON['data'][$i]['id'];
	      $description = $theJSON['data'][$i]['attributes']['short_description']['attributes']['value'];
        $parentLabels = $theJSON['data'][$i]['attributes']['parent_labels']['attributes']['value'];
        $parentLabelsLabel = $theJSON['data'][$i]['attributes']['parent_labels']['attributes']['label'];
        $parentIDs = $theJSON['data'][$i]['attributes']['parent_ids']['attributes']['value'];
        $onlineComponent = $theJSON['data'][$i]['attributes']['online_content?']['attributes']['value'];
        $restrictedComponent = $theJSON['data'][$i]['attributes']['restricted_component?']['attributes']['value'];

        $onlineIconBlock = '<span data-toggle="tooltip" title="" data-original-title="Available Online"><span class="blacklight-icons al-online-content-icon"><!--?xml version="1.0"?--><svg viewBox="0 0 640 512" role="img" aria-label="online icon"><path d="M608 0H160a32 32 0 0 0-32 32v96h160V64h192v320h128a32 32 0 0 0 32-32V32a32 32 0 0 0-32-32zM232 103a9 9 0 0 1-9 9h-30a9 9 0 0 1-9-9V73a9 9 0 0 1 9-9h30a9 9 0 0 1 9 9zm352 208a9 9 0 0 1-9 9h-30a9 9 0 0 1-9-9v-30a9 9 0 0 1 9-9h30a9 9 0 0 1 9 9zm0-104a9 9 0 0 1-9 9h-30a9 9 0 0 1-9-9v-30a9 9 0 0 1 9-9h30a9 9 0 0 1 9 9zm0-104a9 9 0 0 1-9 9h-30a9 9 0 0 1-9-9V73a9 9 0 0 1 9-9h30a9 9 0 0 1 9 9zm-168 57H32a32 32 0 0 0-32 32v288a32 32 0 0 0 32 32h384a32 32 0 0 0 32-32V192a32 32 0 0 0-32-32zM96 224a32 32 0 1 1-32 32 32 32 0 0 1 32-32zm288 224H64v-32l64-64 32 32 128-128 96 96z"></path></svg></span></span>';

        $restrictedIconBlock = '<span data-toggle="tooltip" title="" data-original-title="Some restrictions apply"><span class="blacklight-icons al-restricted-component-icon"><!--?xml version="1.0"?--><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="exclamation-triangle" class="svg-inline--fa fa-exclamation-triangle fa-w-18" role="img" viewBox="0 0 576 512" aria-label="exclamation_triangle_solid icon"><path fill="currentColor" d="M569.517 440.013C587.975 472.007 564.806 512 527.94 512H48.054c-36.937 0-59.999-40.055-41.577-71.987L246.423 23.985c18.467-32.009 64.72-31.951 83.154 0l239.94 416.028zM288 354c-25.405 0-46 20.595-46 46s20.595 46 46 46 46-20.595 46-46-20.595-46-46-46zm-43.673-165.346l7.418 136c.347 6.364 5.609 11.346 11.982 11.346h48.546c6.373 0 11.635-4.982 11.982-11.346l7.418-136c.375-6.874-5.098-12.654-11.982-12.654h-63.383c-6.884 0-12.356 5.78-11.981 12.654z"></path></svg></span></span>';


				// Title
	      if (!empty ($title)) {
	        $theTitle = (string) $title;
	        $theTitle = htmlentities($theTitle, ENT_QUOTES, 'UTF-8');

	        // truncate long titles
	        if (strlen($theTitle) > 135) {
	          $theTitle = wordwrap($theTitle, 110);
	          $theTitle = substr($theTitle, 0, strpos($theTitle, "\n"));
	          $theTitle = $theTitle . ' (&hellip;)';
	        }


	      } else {
	        $theTitle = "";
	      }


	      // ID

	      if (!empty ($ID)) {
	        $theID = (string) $ID;
	      } else {
	        $theID = "";
	      }


        // Description

	      if (!empty ($description)) {
	        $theDescription = (string) $description;

          // truncate long titles
	        if (strlen($theDescription) > 200) {
	          $theDescription = wordwrap($theDescription, 180);
	          $theDescription = substr($theDescription, 0, strpos($theDescription, "\n"));
	          $theDescription = $theDescription . ' (&hellip;)';
	        }

	      } else {
	        $theDescription = "";
	      }


        // Parent Labels
        if (!empty ($parentLabels)) {
          $parentLabelsArray = array();
	        $parentLabelsArray = $parentLabels;
	      }

        // Parent Labels Label
	      if (!empty ($parentLabelsLabel)) {
	        $theParentLabelsLabel= (string) $parentLabelsLabel;
	      } else {
	        $theParentLabelsLabel = "";
	      }

        // Parent IDs
        if (!empty ($parentIDs)) {
          $parentIDsArray = array();
	        $parentIDsArray = $parentIDs;
	      }


        // icons
        if ($onlineComponent == 'true' || $restrictedComponent == 'true') {
          $iconRender = '<div class="icon-wrapper">';
        }
        
        if ($onlineComponent == 'true') {
          $iconRender .= $onlineIconBlock;
        }

        if ($restrictedComponent == 'true') {
          $iconRender .= $restrictedIconBlock;
        }

        if ($onlineComponent == 'true' || $restrictedComponent == 'true') {
          $iconRender .= '</div>';
        }



				echo '<div class="document-frame" titlelocalid="' . $theID . '">';

					echo '<div class="title">';
						echo '<div class="text">';

							echo '<h3 class="resultTitle"><a href="' . $baseURL . 'catalog/' . $theID . $itemCampaignParams . '" onClick="ga(\'send\', \'event\', { eventCategory: \'BentoResults\', eventAction: \'CollectionGuides\', eventLabel: \'ItemTitle' . $resultCount . '\'});">' . $theTitle . '</a>' . $iconRender . '</h3>';

						echo '</div>';
					echo '</div>';


					echo '<div class="document-summary">';

						// Description
						echo '<div class="description">';

							if ($theDescription != "") {
								echo $theDescription;
							}

						echo '</div>';

            // Parent Labels
						echo '<div class="parent-labels">';

							if (!empty($parentLabelsArray)) {
								
                if ($theParentLabelsLabel != "") {
                  echo $theParentLabelsLabel . ': ';
                }

                // let's loop through the parents to make URLs

                $parentCount = count($parentLabelsArray);
                $pc = 0;

                while ($pc < $parentCount) {

                  if ($pc != 0) {
                    $catalog_search = $parentIDsArray[0] . '_' . $parentIDsArray[$pc];
                  } else {
                    $catalog_search = $parentIDsArray[$pc];
                  }

                  echo '<span class="parent"><a href="' . $catalogURL . $catalog_search . '" target="_blank" onClick="ga(\'send\', \'event\', { eventCategory: \'BentoResults\', eventAction: \'CollectionGuides\', eventLabel: \'ItemParentLabel' . $resultCount . '\'});">' . $parentLabelsArray[$pc] . '</a></span>';

                  if ($pc + 1 != $parentCount) {
                    echo ' &raquo; ';
                  }

                  $pc ++;

                }
                


							}

						echo '</div>';


					echo '</div>';

				echo '</div>';



				// clear all variables
				unset($title);
				unset($ID);
				unset($description);
        unset($parentLabels);
        unset($parentLabelsLabel);
        unset($parentIDs);
        unset($onlineComponent);
        unset($restrictedComponent);

				unset($theTitle);
				unset($theID);
				unset($theDescription);
        unset($parentLabelsArray);
        unset($theParentLabelsLabel);
        unset($parentIDsArray);
        unset($catalog_search);

        unset($pc);
        unset ($parentCount);


				$i ++;

			endwhile;

			unset($resultCount);

		}



		elseif ($searchResults == "-1") {

			if ($chHTTPCode == 200 && $chTotalTime < $timeout) {

				echo '<div class="no-results">';

				$searchWarning = "No Collection Guides results found for <em>" . $queryDisplay . "</em>.";

				$searchWarning .= '<br/><br/><a href="' . $searchURLAll . '" onClick="ga(\'send\', \'event\', { eventCategory: \'BentoResults\', eventAction: \'CollectionGuides\', eventLabel: \'TryAnotherSearch\'});">Try another search &raquo;</a>';

				echo $searchWarning;

				echo '</div>';

			} else {

					echo '<div class="no-results">';

					$searchWarning = "A network or server error was encountered while searching for <em>" . $queryDisplay . "</em>. Please try again in a few moments";

					$searchWarning .= '<br/><br/>If you continue to encounter this error, please <a href="//library.duke.edu/research/ask" onClick="ga(\'send\', \'event\', { eventCategory: \'BentoResults\', eventAction: \'CollectionGuides\', eventLabel: \'GetHelp\'});">report the problem to Library Staff</a>';

					echo $searchWarning;

					echo '</div>';

			}

		}

		else {

			echo '<div class="no-results">';

			$searchWarning = "No Collection Guides results found for <em>" . $queryDisplay . "</em>.";

			$searchWarning .= '<br/><br/><a href="' . $searchURLAll . '" onClick="ga(\'send\', \'event\', { eventCategory: \'BentoResults\', eventAction: \'CollectionGuides\', eventLabel: \'TryAnotherSearch\'});">Try another search &raquo;</a>';

			echo $searchWarning;

			echo '</div>';

		}

		echo '</div>';


		// See all bottom link
		if($searchResults != "0" AND $searchResults != "-1" AND $theSearch != "" AND $numTotalResults > 3) {

			echo '<div class="see-all">';

        echo '<a href="' . $searchURLAll . $theSearch . $allCampaignParams . '" onClick="ga(\'send\', \'event\', { eventCategory: \'BentoResults\', eventAction: \'CollectionGuides\', eventLabel: \'SeeAllBottom\'});">See all <strong>' . number_format($numTotalResults) .'</strong> collection guides results at Duke</a>';

      echo '</div>';

		}


	echo '</div>';

}

$arclightGuidesEnd = microtime(true);

// Check for logging
$bentoLogging = variable_get('dul_bento.bento_logging', '');

if ($bentoLogging == 1) {

	global $arclightGuidesCreationTime;
	global $arclightGuidesJSONCreationTime;

	$arclightGuidesCreationTime = ($arclightGuidesEnd - $arclightGuidesStart);
	$arclightGuidesJSONCreationTime = ($arclightGuidesJSONEnd - $arclightGuidesJSONStart);

}

?>
