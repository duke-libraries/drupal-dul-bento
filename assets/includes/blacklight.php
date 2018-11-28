<?php

//display errors
//ini_set('display_errors',1);
//ini_set('display_startup_errors',1);
//error_reporting(-1);

$blacklightStart = microtime(true);

//$searchURL = "https://find-dev.library.duke.edu/";

$baseURL = "https://find-dev.library.duke.edu/";

$searchURL = "https://find-dev.library.duke.edu/?utf8=✓&f_inclusive%5Bresource_type_f%5D%5B%5D=Audiobook&f_inclusive%5Bresource_type_f%5D%5B%5D=Book&f_inclusive%5Bresource_type_f%5D%5B%5D=Database&f_inclusive%5Bresource_type_f%5D%5B%5D=Dataset+--+Geospatial&f_inclusive%5Bresource_type_f%5D%5B%5D=Dataset+--+Statistical&f_inclusive%5Bresource_type_f%5D%5B%5D=Game&f_inclusive%5Bresource_type_f%5D%5B%5D=Government+publication&f_inclusive%5Bresource_type_f%5D%5B%5D=Image&f_inclusive%5Bresource_type_f%5D%5B%5D=Journal%2C+Magazine%2C+or+Periodical&f_inclusive%5Bresource_type_f%5D%5B%5D=Kit&f_inclusive%5Bresource_type_f%5D%5B%5D=Map&f_inclusive%5Bresource_type_f%5D%5B%5D=Music+recording&f_inclusive%5Bresource_type_f%5D%5B%5D=Music+score&f_inclusive%5Bresource_type_f%5D%5B%5D=Newspaper&f_inclusive%5Bresource_type_f%5D%5B%5D=Non-musical+sound+recording&f_inclusive%5Bresource_type_f%5D%5B%5D=Object&f_inclusive%5Bresource_type_f%5D%5B%5D=Software%2Fmultimedia&f_inclusive%5Bresource_type_f%5D%5B%5D=Thesis%2FDissertation&f_inclusive%5Bresource_type_f%5D%5B%5D=Video&f_inclusive%5Bresource_type_f%5D%5B%5D=Web+page+or+site&range%5Bpublication_year_isort%5D%5Bbegin%5D=&range%5Bpublication_year_isort%5D%5Bend%5D=&sort=score+desc%2C+publication_year_isort+desc%2C+title_sort_ssort_single+asc&search_field=advanced&commit=Search&all_fields=";

//$urlString = "https://find-dev.library.duke.edu/catalog.json?search_field=all_fields&q=";
$urlString = "https://find-dev.library.duke.edu/catalog.json?f_inclusive%5Bresource_type_f%5D%5B%5D=Audiobook&f_inclusive%5Bresource_type_f%5D%5B%5D=Book&f_inclusive%5Bresource_type_f%5D%5B%5D=Database&f_inclusive%5Bresource_type_f%5D%5B%5D=Dataset+--+Geospatial&f_inclusive%5Bresource_type_f%5D%5B%5D=Dataset+--+Statistical&f_inclusive%5Bresource_type_f%5D%5B%5D=Game&f_inclusive%5Bresource_type_f%5D%5B%5D=Government+publication&f_inclusive%5Bresource_type_f%5D%5B%5D=Image&f_inclusive%5Bresource_type_f%5D%5B%5D=Journal%2C+Magazine%2C+or+Periodical&f_inclusive%5Bresource_type_f%5D%5B%5D=Kit&f_inclusive%5Bresource_type_f%5D%5B%5D=Map&f_inclusive%5Bresource_type_f%5D%5B%5D=Music+recording&f_inclusive%5Bresource_type_f%5D%5B%5D=Music+score&f_inclusive%5Bresource_type_f%5D%5B%5D=Newspaper&f_inclusive%5Bresource_type_f%5D%5B%5D=Non-musical+sound+recording&f_inclusive%5Bresource_type_f%5D%5B%5D=Object&f_inclusive%5Bresource_type_f%5D%5B%5D=Software%2Fmultimedia&f_inclusive%5Bresource_type_f%5D%5B%5D=Thesis%2FDissertation&f_inclusive%5Bresource_type_f%5D%5B%5D=Video&f_inclusive%5Bresource_type_f%5D%5B%5D=Web+page+or+site&range%5Bpublication_year_isort%5D%5Bbegin%5D=&range%5Bpublication_year_isort%5D%5Bend%5D=&sort=score+desc%2C+publication_year_isort+desc%2C+title_sort_ssort_single+asc&search_field=advanced&commit=Search&all_fields=";

$theSearch = urlencode($queryTerms);

$searchResults = "";






$blacklightJSONStart = microtime(true);

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

$blacklightJSONEnd = microtime(true);

$tempISBN = "";
$isDiffISBN = false;

$i = 0;


if ($theSearch != "" && $searchResults != "-1") {

	$nodeCount = 0;

	//check for results
	foreach ($theJSON['response']['docs'] as $key=>$value){
	    $nodeCount += 1;
	}

	// broken path...
	if ($nodeCount == 0) {

		$searchResults = "-1";
		// error output on line ~923

	}

	// there is a node!
	else {

		$numTotalResults = $theJSON['response']['pages']['total_count'];

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


	<div class="results-block first" id="results-books">

		<div class="resultsHeader">

				<h2><div class="anchor-highlight hide">»</div> Books &amp; Media <a href="<?php echo $searchURL . $theSearch; ?>" class="callbox" style="margin-left: 10px;" <?php echo 'onClick="ga(\'send\', \'event\', { eventCategory: \'BentoResults\', eventAction: \'BooksMedia\', eventLabel: \'SeeAll\'});"' ?>>See&nbsp;All&nbsp;&raquo;</a></h2>

				<p class="small text-muted">Books, music, movies &amp; more</p>

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

				$title = $theJSON['response']['docs'][$i]['title_main'];
	      $ID = $theJSON['response']['docs'][$i]['id'];
	      $ISBN = $theJSON['response']['docs'][$i]['isbn_number_a'];
	      $UPC = $theJSON['response']['docs'][$i]['upc_a'];
	      $author = $theJSON['response']['docs'][$i]['statement_of_responsibility_a'];
	      $otherAuthors = $theJSON['response']['docs'][$i]['author_suggest'];
	      $itemtype = $theJSON['response']['docs'][$i]['resource_type_a'];
	      $Published = $theJSON['response']['docs'][$i]['publication_year_isort_stored_single'];
	      $Items_a = $theJSON['response']['docs'][$i]['items_a'];
	      $Available_a = $theJSON['response']['docs'][$i]['available_a'];


				// Title
	      if (!empty ($title)) {
	        $theTitle = (string) $title;
	        $theTitle = htmlentities($theTitle, ENT_QUOTES, 'UTF-8');

	        // nix '[electronic resource]' and '[serial]'
	        $theTitle = str_replace(' [electronic resource]', '', $theTitle);
	        $theTitle = str_replace(' [serial]', '', $theTitle);

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
	        $theID = "empty";
	      }


	      // ISBN
	      if (!empty ($ISBN)) {
	        $theISBN = (string) $ISBN[0];
	      }

	      // UPC
	      if (!empty ($UPC)) {
	        $theUPC = (string) $UPC[0];
	        $theUPC = str_replace('UPC: ', '', $theUPC);
	      }

	      // Main Author
	      if (!empty ($author)) {
	        $theAuthor = (string) $author[0];
	      } else {
	        $theAuthor = "NONE";
	      }

	      // if ($theAuthor == "NONE") {

	      //}

	      // Item Type
	      if (!empty ($Items_a)) {
	        $firstType = json_decode($Items_a[0], true);
	        $theItemtype = (string) trim($firstType['type']);
	      } elseif (!empty ($itemtype)) { //fallback item type
	        $theItemtype = (string) trim($itemtype[0]);
	      }

	      // Translate Item Types

				switch($theItemtype):

					case 'MFICH':
							$theItemtypeDisplay = "Microfiche";
							break;
					case 'ITNET':
							$theItemtypeDisplay = "Online";
							break;
					case 'LPREC':
							$theItemtypeDisplay = "LP record";
							break;
					case 'CDREC':
							$theItemtypeDisplay = "Audio CD";
							break;
					case 'DVD':
							$theItemtypeDisplay = "Video DVD";
							break;
					case 'MSS':
							$theItemtypeDisplay = "Manuscript";
							break;
					case 'SER':
							$theItemtypeDisplay = "Serial";
							break;
					case 'BPER':
							$theItemtypeDisplay = "Serial";
							break;
					default:
							$theItemtypeDisplay = ucfirst(strtolower($theItemtype));

				endswitch;




	      // Published
	      if (!empty ($Published)) {
	        $thePublished = (string) $Published;
	        $thePublished = rtrim(ltrim($thePublished, 'c'), '.');
	      }



	      // Location
	      if (!empty ($Items_a)) {

	        $locationCount = count($Items_a);
	        $firstLocation = json_decode($Items_a[0], true);

	        $theLocation = $loc_b_json[$firstLocation['loc_b']];

	        if (!empty($firstLocation['loc_n']) && $loc_n_json[$firstLocation['loc_n']] != "") {
	          $theLocation .= '—' . $loc_n_json[$firstLocation['loc_n']];
	        }

	      }

	      // Availability
	      if (!empty ($Available_a)) {
	        $theAvailability = (string) $Available_a[0];
	      } elseif (!empty ($Items_a)) {
	        $firstStatus = json_decode($Items_a[0], true);
	        $theAvailability = $firstStatus['status'];
	      }



				echo '<div class="document-frame" titlelocalid="' . $theID . '">';

					echo '<div class="title">';
						echo '<div class="text">';

							echo '<h3 class="resultTitle"><a href="' . $baseURL . 'catalog/' . $theID . '" onClick="ga(\'send\', \'event\', { eventCategory: \'BentoResults\', eventAction: \'BooksMedia\', eventLabel: \'ItemTitle' . $resultCount . '\'});">' . $theTitle . '</a></h3>';

						echo '</div>';
					echo '</div>';


					// Thumbnails
					if (isset($theUPC)){
						$imagePath = 'https://syndetics.com/index.php?client=trlnet&upc=' . $theUPC . '&isbn=' . $theISBN . '%2FMC.GIF';
					} else if (isset($theISBN)) {
		        $imagePath = 'https://syndetics.com/index.php?client=trlnet&isbn=' . $theISBN . '%2FMC.GIF';
		      }

					if (isset($imagePath)) {

						echo '<div class="thumbnail">';

							echo '<a href="' . $baseURL . 'catalog/' . $theID . '" onClick="ga(\'send\', \'event\', { eventCategory: \'BentoResults\', eventAction: \'BooksMedia\', eventLabel: \'ItemThumbnail' . $resultCount . '\'});"><img src="' . $imagePath . '" alt="cover artwork" class="artwork"></a>';

						echo '</div>';

					}


					echo '<div class="document-summary">';

						// AUTHORS
						echo '<div class="authors">';

							if ($theAuthor != "NONE") {
								echo $theAuthor;
							}

						echo '</div>';



						// Format and ISBN/ISSN/UPC
						echo '<div class="isbn">';

							if (!empty ($theItemtypeDisplay)) {
								echo '<span class="item-type">' . $theItemtypeDisplay . '</span>';
							}


							// added year
							if (!empty ($thePublished)) {

								if ($theItemtypeDisplay != "") {
									echo ', ';
								}

								echo $thePublished;

							}


						echo '</div>';



						// HOLDINGS
						if (!empty ($theLocation)) {

						echo '<div class="holdings-wrapper">';

							echo '<div class="holdings">';

							if ($theLocation != "") {
								echo '<span class="library">' . $theLocation . '</span>';


								// Available
								if ($theAvailability != "") {
									echo ', ';
									if (strpos($theAvailability,'Available') !== false) {
										echo '<span class="available-status green">' . $theAvailability . '</span>';
									} elseif (strpos($theAvailability,'Not Available') !== false) {
										echo '<span class="available-status red">' . $theAvailability . '</span>';
									} else {
										echo '<span class="available-status brown">' . $theAvailability . '</span>';
									}
								}

							}

							echo '</div>';



							// Extra Holdings
				      if ($locationCount) {

				        $holdingsCount = $locationCount - 1;

				        // single extra item
				        if ($holdingsCount == 1) {

				          echo '<div class="more-holdings">There is ' . $holdingsCount . ' additional item available &ndash; <a href="' . $baseURL . 'catalog/' . $theID . '" onClick="ga(\'send\', \'event\', { eventCategory: \'BentoResults\', eventAction: \'BooksMedia\', eventLabel: \'ItemMoreHoldings' . $resultCount . '\'});">show more&nbsp;&raquo;</a></div>';

				        // multiple extra items
				      } elseif ($holdingsCount > 1) {

				          echo '<div class="more-holdings">There are ' . $holdingsCount . ' additional items available &ndash; <a href="' . $baseURL . 'catalog/' . $theID . '" onClick="ga(\'send\', \'event\', { eventCategory: \'BentoResults\', eventAction: \'BooksMedia\', eventLabel: \'ItemMoreHoldings' . $resultCount . '\'});">show more&nbsp;&raquo;</a></div>';

				        }

				      }

						echo '</div>';

						}
						// End holdings

					echo '</div>';

				echo '</div>';



				// clear all variables
				unset($title);
				unset($ID);
				unset($ISBN);
				unset($UPC);
				unset($author);
				unset($otherAuthors);
				unset($itemtype);
				unset($Published);
				unset($Items_a);
				unset($Available_a);

				unset($theTitle);
				unset($theID);
				unset($theISBN);
				unset($imagePath);
				unset($theUPC);
				unset($theAuthor);
				unset($firstType);
				unset($theItemtype);
				unset($theItemtypeDisplay);
				unset($thePublished);
				unset($locationCount);
				unset($firstLocation);
				unset($theLocation);
				unset($theAvailability);
				unset($firstStatus);

				$i ++;

			endwhile;

			unset($resultCount);

		}



		elseif ($searchResults == "-1") {

			if ($chHTTPCode == 200 && $chTotalTime < $timeout) {

				echo '<div class="no-results">';

				$searchWarning = "No Books &amp; Media results found for <em>" . $queryDisplay . "</em>.";

				$searchWarning .= '<br/><br/><a href="' . $searchURL . '" onClick="ga(\'send\', \'event\', { eventCategory: \'BentoResults\', eventAction: \'BooksMedia\', eventLabel: \'TryAnotherSearch\'});">Try another search &raquo;</a>';

				echo $searchWarning;

				echo '</div>';

			} else {

					echo '<div class="no-results">';

					$searchWarning = "A network or server error was encountered while searching for <em>" . $queryDisplay . "</em>. Please try again in a few moments";

					$searchWarning .= '<br/><br/>If you continue to encounter this error, please <a href="//library.duke.edu/research/ask" onClick="ga(\'send\', \'event\', { eventCategory: \'BentoResults\', eventAction: \'BooksMedia\', eventLabel: \'GetHelp\'});">report the problem to Library Staff</a>';

					echo $searchWarning;

					echo '</div>';

			}

		}

		else {

			echo '<div class="no-results">';

			$searchWarning = "No Books &amp; Media results found for <em>" . $queryDisplay . "</em>.";

			$searchWarning .= '<br/><br/><a href="' . $searchURL . '" onClick="ga(\'send\', \'event\', { eventCategory: \'BentoResults\', eventAction: \'BooksMedia\', eventLabel: \'TryAnotherSearch\'});">Try another search &raquo;</a>';

			echo $searchWarning;

			echo '</div>';

		}

		echo '</div>';


		// See all bottom link
		if($searchResults != "0" AND $searchResults != "-1" AND $theSearch != "" AND $numTotalResults > 3) {

			echo '<div class="see-all">';

        echo '<a href="' . $searchURL . $theSearch . '" onClick="ga(\'send\', \'event\', { eventCategory: \'BentoResults\', eventAction: \'BooksMedia\', eventLabel: \'SeeAllBottom\'});">See all <strong>' . number_format($numTotalResults) .'</strong> books and media results at Duke</a>';

      echo '</div>';

		}


	echo '</div>';

}

$blacklightEnd = microtime(true);

// Check for logging
$bentoLogging = variable_get('dul_bento.bento_logging', '');

if ($bentoLogging == 1) {

	global $blacklightCreationTime;
	global $blacklightJSONCreationTime;

	$blacklightCreationTime = ($blacklightEnd - $blacklightStart);
	$blacklightJSONCreationTime = ($blacklightJSONEnd - $blacklightJSONStart);

}

?>
