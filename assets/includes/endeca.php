<?php

$endecaStart = microtime(true);

$urlString = "http://search.library.duke.edu/search?Nty=1&Ntk=Keyword&N=0&output-format=xml&Ntt=";

$theSearch = urlencode($queryTerms);


$endecaXMLStart = microtime(true);
$theXML = simplexml_load_file ($urlString . $theSearch);
$endecaXMLEnd = microtime(true);


$tempISBN = "";
$isDiffISBN = false;

$i = 1;

$maxResults = 8;

if ($theSearch != "") {

	$searchResults = $theXML->xpath('/trln-endeca-results/results-data/endeca-search-info/searchInfoItems/nav-search-info/nav-search-reports/item/numberOfMatchingResults');

	$searchResults = (string) $searchResults[0];

} else {

	$searchResults = "0";
}



if ($theSearch != "") {
			
?>


	<div class="results-block first" id="results-books">

		<div class="resultsHeader">
		
				<h2>Books &amp; Media <a href="http://search.library.duke.edu/search?Nty=1&Ntk=Keyword&N=0&Ntt=<?php echo $theSearch; ?>" class="callbox" style="margin-left: 10px;" <?php echo 'onClick="ga(\'send\', \'event\', { eventCategory: \'BentoResults\', eventAction: \'BooksMedia\', eventLabel: \'SeeAll\'});"' ?>>See&nbsp;All&nbsp;&raquo;</a></h2>
		
				<p class="smaller muted">Books, music, movies &amp; more</p>	
		
		<?php 

		if($searchResults != "0" AND $theSearch != "") {
			
			$resultCount = 0; // for GA event tracking
			
			while ($i < $maxResults):
			
				$resultCount = $i;

				$title = $theXML->xpath('/trln-endeca-results/results-data/endeca-records-list/records/item['.$i.']/properties/Main-Title/item');
				$ID = $theXML->xpath('/trln-endeca-results/results-data/endeca-records-list/records/item['.$i.']/properties/LocalId/item');
				$Imprint = $theXML->xpath('/trln-endeca-results/results-data/endeca-records-list/records/item['.$i.']/properties/Imprint/item');
				$Publisher = $theXML->xpath('/trln-endeca-results/results-data/endeca-records-list/records/item['.$i.']/properties/Publisher/item');
				$ISBN = $theXML->xpath('/trln-endeca-results/results-data/endeca-records-list/records/item['.$i.']/properties/Syndetics-ISBN/item');
				$UPC = $theXML->xpath('/trln-endeca-results/results-data/endeca-records-list/records/item['.$i.']/properties/UPC/item');
				$author = $theXML->xpath('/trln-endeca-results/results-data/endeca-records-list/records/item['.$i.']/properties/Main-Author/item');
				$otherAuthors = $theXML->xpath('/trln-endeca-results/results-data/endeca-records-list/records/item['.$i.']/properties/Other-Authors/item');
				$itemtype = $theXML->xpath('/trln-endeca-results/results-data/endeca-records-list/records/item['.$i.']/properties/Item-Types/item');
				$OCLC = $theXML->xpath('/trln-endeca-results/results-data/endeca-records-list/records/item['.$i.']/properties/OCLCNumber/item');
				$Published = $theXML->xpath('/trln-endeca-results/results-data/endeca-records-list/records/item['.$i.']/properties/Published/item');
				$Material = $theXML->xpath('/trln-endeca-results/results-data/endeca-records-list/records/item['.$i.']/properties/Material/item');
				$ItemID = $theXML->xpath('/trln-endeca-results/results-data/endeca-records-list/records/item['.$i.']/properties/Item-ID/item');
				$Libraries = $theXML->xpath('/trln-endeca-results/results-data/endeca-records-list/records/item['.$i.']/properties/Libraries/item');
				$CallNumber = $theXML->xpath('/trln-endeca-results/results-data/endeca-records-list/records/item['.$i.']/properties/Call-Number/item');
				$SOR = $theXML->xpath('/trln-endeca-results/results-data/endeca-records-list/records/item['.$i.']/properties/Statement-of-Responsibility/item');
				$DueDate = $theXML->xpath('/trln-endeca-results/results-data/endeca-records-list/records/item['.$i.']/properties/Item-Due-Date/item');
				$ThumbnailURL = $theXML->xpath('/trln-endeca-results/results-data/endeca-records-list/records/item['.$i.']/properties/Thumbnail-URL/item');
				
				$Statuses = $theXML->xpath('/trln-endeca-results/results-data/endeca-records-list/records/item['.$i.']/properties/Statuses/item');
				
				# NOTE: The Libraries, Items Types, Call Numbers and Item IDs, as a unit, represent (a portion) of a Holdings record
				# so, an array will be created to store those values (array)
				# the itemtype, libraries and item-id's need to be split by the '|' delimiter
				# use an array to store the 'holding' data
				$arrHoldings = array();
		
				if (isset($ItemID[0])) {
					$tmpItemID = explode('|', (string) $ItemID[0]);
				} else {
					$tmpItemID = "";
				}
				
				if (isset($Libraries[0])) {
					$tmpLibraries = explode('|', (string) $Libraries[0]);
				}
				
				if (isset($itemtype[0])) {
					$tmpItemType = explode('|', (string) $itemtype[0]);
				}
				
				if (isset($CallNumber[0])) {
					$tmpCallNumber = explode('|', (string) $CallNumber[0]);
				}
				
				if (isset($Statuses[0])) {
					$tmpStatus = explode('|', (string) $Statuses[0]);
					$primaryStatus = $tmpStatus[0];
				}
				
				
				if ($tmpItemID != "") {
					for ($k = 0; $k < count($tmpItemID); $k++) {
						$arrHoldings[] = array(
							'item-id' => $tmpItemID[$k],
							'library' => $tmpLibraries[$k],
							'item-type' => $tmpItemType[$k],
							'call-number' => $tmpCallNumber[$k],
							'status' => $tmpStatus[$k],
						);
					}
				}
				# the last element in $arrElement is an empty one
				# due to the way Endeca returns the pipe-delimited string (appending an '|' at the end)
				array_pop($arrHoldings);

				// free up memory in the event all of the 'bento' search process 
				// becomes RAM intensive
				unset($tmpLibraries);
				unset($tmpItemType);
				unset($tmpItemID);
				unset($tmpCallNumber);
				unset($tmpStatus);

				// Title
				if (!empty ($title)) {
					$theTitle = (string) $title[0];
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
					$theID = (string) $ID[0];
				} else {
					$theID = "";
				}
					
					// Check for DC
					$dcItem = "false";
					if (strpos($theID,'DUKEDC') !== false) {
    					$dcItem = "true";
					}
				
				
				
				
				// ISBN
				if (!empty ($ISBN)) {
					
					$theISBN = (string) $ISBN[0];
					
					
					// check if ISBN repeats
					
					if ($tempISBN != $theISBN) {
					
						$isDiffISBN = true;
						$tempISBN = $theISBN;
					
					} else {
						
						$isDiffISBN = false;
					
					}

					
				}
				
				// Imprint
				if (!empty ($Imprint)) {
					$theImprint = (string) $Imprint[0];
				}
				
				// Publisher
				if (!empty ($Publisher)) {
					$thePublisher = (string) $Publisher[0];
				}
				
				// UPC
				if (!empty ($UPC)) {
					$theUPC = (string) $UPC[0];
				}
				
		
				// Main Author
				if (!empty ($author)) {
					$theAuthor = (string) $author[0];
					$theAuthor = str_replace('|', '', $theAuthor);
					$theAuthor = rtrim(htmlentities($theAuthor, ENT_QUOTES, 'UTF-8'), '.');
				} else {
					$theAuthor = "NONE";
				}
		
				// Item Type
				if (!empty ($itemtype)) {
					$theItemtype = (string) $itemtype[0];
					$theItemtypeHolder = explode("|",$theItemtype);
					$theItemtype = $theItemtypeHolder[0];
					
					// Set Item Type Display
					
					$theItemtypeDisplay = ucwords(strtolower(str_replace('|', '', $theItemtype)));
					
					if ($theItemtypeDisplay == "Mfich") {
						
						$theItemtypeDisplay = "Microfiche";
						
					} elseif ($theItemtypeDisplay == "Itnet") {
						
						$theItemtypeDisplay = "Internet resource";
						
					} elseif ($theItemtypeDisplay == "Lprec") {
						
						$theItemtypeDisplay = "LP record";
						
					} elseif ($theItemtypeDisplay == "Cdrec") {
						
						$theItemtypeDisplay = "Audio CD";
						
					} elseif ($theItemtypeDisplay == "Dvd") {
						
						$theItemtypeDisplay = "Video DVD";
						
					} elseif ($theItemtypeDisplay == "Mss") {
						
						$theItemtypeDisplay = "Manuscript";
						
					} 
					
				}
				
				
				// Due Date (just check if it exists)
				
				if (!empty ($DueDate)) {
					$theDueDate = (string) $DueDate[0];
					$theDueDate = str_replace("|", "", $theDueDate);
					
				} else {
					$theDueDate = "";
				}
				
		
				// OCLC
				if (!empty ($OCLC)) {
					$theOCLC = (string) $OCLC[0];
				} else {
					$theOCLC = "";
				}
		
				// Published
				if (!empty ($Published)) {
					$thePublished = (string) $Published[0];
					$thePublished = rtrim(ltrim($thePublished, 'c'), '.');
				}
		
				// Material
				if (!empty ($Material)) {
					$theMaterial = (string) $Material[0]; 
				}
		
				// Other Authors (array)
				if (!empty ($otherAuthors)) {
			
					//need to strip periods!
			
					if (isset($otherAuthors[0])) {
					$otherAuthors1 = (string) $otherAuthors[0];
						$otherAuthors1 = rtrim(htmlentities($otherAuthors1, ENT_QUOTES, 'UTF-8'), '.');
					}
				}
		
				if (!empty ($SOR)) {
					$theSOR = (string) $SOR[0];
					$theSOR = htmlentities($theSOR, ENT_QUOTES, 'UTF-8');
				}
				
				
				// Thumbnail-URL (for DC items)
				if (!empty ($ThumbnailURL)) {
					$theThumbnailURL = (string) $ThumbnailURL[0];
					$theThumbnailURL = htmlentities($theThumbnailURL, ENT_QUOTES, 'UTF-8');
				}
				
		
		

				echo '<div class="document-frame" titlelocalid="' . $theID . '">';
		
					echo '<div class="title">';
						echo '<div class="text">';
							
							// check for DC
							
							if ($dcItem == "true") {
								
								echo '<h3 class="resultTitle"><a href="http://search.library.duke.edu/search?id=' . $theID . '" onClick="ga(\'send\', \'event\', { eventCategory: \'BentoResults\', eventAction: \'BooksMedia\', eventLabel: \'ItemTitle' . $resultCount . '\'});">' . $theTitle . '</a></h3>';
								
							} else {
							
								echo '<h3 class="resultTitle"><a href="http://search.library.duke.edu/search?id=DUKE' . $theID . '" onClick="ga(\'send\', \'event\', { eventCategory: \'BentoResults\', eventAction: \'BooksMedia\', eventLabel: \'ItemTitle' . $resultCount . '\'});">' . $theTitle . '</a></h3>';
							
							}
						
						
						echo '</div>';
					echo '</div>';
				
				
				// ISBN Thumbnails
				if (isset($theISBN)) {
				
					if ($isDiffISBN == true) {
			
						$imagePath = "http://www.syndetics.com/index.aspx?isbn=" . $theISBN . "/MC.GIF&oclc=" . $theOCLC . "&client=trlnet";
						//$imageSize = getimagesize($imagePath);
						
						
						//if ($imageWidth != '1') {
			
							echo '<div class="thumbnail">';
								
								echo '<a href="http://search.library.duke.edu/search?id=DUKE' . $theID . '" onClick="ga(\'send\', \'event\', { eventCategory: \'BentoResults\', eventAction: \'BooksMedia\', eventLabel: \'ItemThumbnail' . $resultCount . '\'});"><img src="http://www.syndetics.com/index.aspx?isbn=' . $theISBN . '/MC.GIF&oclc=' . $theOCLC . '&client=trlnet" alt="cover artwork" class="artwork"></a>';
							
							
							echo '</div>';
							
						//}
				
				
					}
			
				
				// UPC Thumbnails
				} else if (isset($theUPC)) {
				
					$imagePath = "http://www.syndetics.com/index.aspx?upc=" . $theUPC . "/MC.GIF&oclc=" . $theUPC . "&client=trlnet";
					//$imageSize = getimagesize($imagePath);
					
					
					//if ($imageSize[0] != '1') {
			
						echo '<div class="thumbnail">';
							
								
							echo '<a href="http://search.library.duke.edu/search?id=DUKE' . $theID . '" onClick="ga(\'send\', \'event\', { eventCategory: \'BentoResults\', eventAction: \'BooksMedia\', eventLabel: \'ItemThumbnail' . $resultCount . '\'});"><img src="http://www.syndetics.com/index.aspx?upc=' . $theUPC . '/MC.GIF&oclc=' . $theOCLC . '&client=trlnet" alt="cover artwork" class="artwork"></a>';
								
							
						echo '</div>';
							
					//}
				
				// DC Thumbnails
				} else if (isset($theThumbnailURL)) {
				
					// Check for DC
					if ($dcItem == "true") {
					
						echo '<div class="thumbnail">';
								
							echo '<a href="http://search.library.duke.edu/search?id=' . $theID . '" onClick="ga(\'send\', \'event\', { eventCategory: \'BentoResults\', eventAction: \'BooksMedia\', eventLabel: \'ItemThumbnail' . $resultCount . '\'});"><img src="' . $theThumbnailURL . '" alt="item thumbnail" class="artwork"></a>';
									
						echo '</div>';
					
					}
					
				}		
		
		
					echo '<div class="document-summary">';
				
						// AUTHORS
						echo '<div class="authors">';
				
						if ($theAuthor != "NONE") {
							echo 'by <a href="http://duke.summon.serialssolutions.com/search?s.dym=false&s.q=Author%3A%22' . $theAuthor . '%22" onClick="ga(\'send\', \'event\', { eventCategory: \'BentoResults\', eventAction: \'BooksMedia\', eventLabel: \'ItemAuthor' . $resultCount . '\'});">' . $theAuthor . '</a>';
				
						}
				
				
						

						echo '</div>';
				
				
						// PUBLISHER
						//echo '<div class="publisher">';
					
							//if (isset($theImprint)) {
								
								//echo $theImprint;
								
							//} else {
							
								//if (isset($thePublisher)) {
									//echo $thePublisher;
								//}
								
								//if (isset($thePublished)) {
									//echo ', ' . $thePublished;
								//}
								
							//}

					
						//echo '</div>';
						
						
						// Format and ISBN/ISSN/UPC
						echo '<div class="isbn">';
							
							if (!empty ($theItemtype)) {
					
								echo '<span class="item-type">' . $theItemtypeDisplay . '</span>'; 
								
							}
							
							//if (isset($theISBN)) {
							
								//echo ', <strong>ISBN:</strong> ' . $theISBN;
							
							//} else if (isset($theUPC)) {
							
								//echo ', <strong>UPC:</strong> ' . $theUPC;
							
							//} else if ($theOCLC != "") {
							
								//echo ', <strong>OCLC:</strong> ' . $theOCLC;
								
							//}
							
							// added year
							if (!empty ($thePublished)) {
								
								echo ', ' . $thePublished;
								
							}

						
						echo '</div>';
						
						
						// HOLDINGS
						if (!empty ($arrHoldings)) {
						
						echo '<div class="holdings-wrapper">';
						
							$holdingsCount = count($arrHoldings);
							//$firstHolding = array_shift($arrHoldings); // first item in array
							$firstHolding = array_pop($arrHoldings); //last item in array
				
							echo '<div class="holdings">';
							
							// Replace Library Names
								if ($firstHolding['library'] == "PERKN") {
									$libraryName = "Perkins/Bostock Library";
								}
							
								if ($firstHolding['library'] == "SCL") {
									$libraryName = "Rubenstein Library";
								}
							
								if ($firstHolding['library'] == "LILLY") {
									$libraryName = "Lilly Library";
								}
							
								if ($firstHolding['library'] == "LAW") {
									$libraryName = "Goodson Law Library";
								}
							
								if ($firstHolding['library'] == "ARCH") {
									$libraryName = "University Archives";
								}
							
								if ($firstHolding['library'] == "MUSIC") {
									$libraryName = "Music Library";
								}
								
								if ($firstHolding['library'] == "FORD") {
									$libraryName = "Ford Library";
								}
								
								if ($firstHolding['library'] == "DIV") {
									$libraryName = "Divinity School Library";
								}
								
								if ($firstHolding['library'] == "DIV") {
									$libraryName = "Divinity School Library";
								}
								
								if ($firstHolding['library'] == "MCL") {
									$libraryName = "Medical Center Library";
								}
								
								if ($firstHolding['library'] == "LSC") {
									$libraryName = "Library Service Center";
								}
								
								if ($firstHolding['library'] == "MARIN") {
									$libraryName = "Marine Lab Library";
								}
								
								if ($firstHolding['library'] == "BES") {
									$libraryName = "Biol-Env. Sciences Library";
								}
								
								if ($firstHolding['library'] == "DOCS") {
									$libraryName = "Perkins Public Documents/Maps";
								}
								
								if ($firstHolding['library'] == "VESIC") {
									$libraryName = "Perkins/Bostock Library {V}";
								}

							
							if (isset($libraryName)) {
								echo '<span class="library">' . $libraryName . '</span>' . ', ';
							}

							// echo '<span class="call-number">' . $firstHolding['call-number'] . '</span>';
							
							
							// change status message where appropriate
							
								//if ($firstHolding['status'] == "Ask at Circulation Desk") {
									//$firstHolding['status'] = "Available";
								//}
								
								if ($firstHolding['status'] == "Temporarily Unavailable") {
									$firstHolding['status'] = "Not Available";
								}
								
								if ($firstHolding['status'] == "On Shelf") {
									$firstHolding['status'] = "Available";
								}
								
								if ($firstHolding['status'] == "LSC") {
									$firstHolding['status'] = "Available";
								}
								
								if ($firstHolding['status'] == "Non-circulating") {
									$firstHolding['status'] = "Available - Library Use Only";
								}
								
								if ($firstHolding['status'] == "Library use only") {
									$firstHolding['status'] = "Available - Library Use Only";
								}
								
								if ($firstHolding['status'] == "Reading Room use only") {
									$firstHolding['status'] = "Available - Library Use Only";
								}
								
								if ($firstHolding['status'] == "Requested") {
									$firstHolding['status'] = "On Hold";
								}
								
								if ($firstHolding['status'] == "Recently labeled in LC") {
									$firstHolding['status'] = "Ask at Circulation Desk";
								}
								
								if ($firstHolding['status'] == "In process-LC") {
									$firstHolding['status'] = "Ask at Circulation Desk";
								}
								
								if ($firstHolding['status'] == "Rubenstein Move Prepped") {
									$firstHolding['status'] = "Available";
								}
								
								if ($firstHolding['status'] == "Rubenstein Move Print Prepped") {
									$firstHolding['status'] = "Ask at Circulation Desk";
								}
								
								if ($firstHolding['status'] == "In process-LC Cataloging Dept.") {
									$firstHolding['status'] = "Ask at Circulation Desk";
								}
								
								if ($firstHolding['status'] == "Sent to Binding") {
									$firstHolding['status'] = "Being Repaired";
								}
								
								if ($firstHolding['status'] == "Preservation") {
									$firstHolding['status'] = "Being Repaired";
								}
								
								if ($firstHolding['status'] == "Bindery-Boxing") {
									$firstHolding['status'] = "Being Repaired";
								}
								
								if ($firstHolding['status'] == "Preservation-Reformatting") {
									$firstHolding['status'] = "Being Repaired";
								}
								
								if ($firstHolding['status'] == "Ready to Bind") {
									$firstHolding['status'] = "Being Repaired";
								}
								
								if ($firstHolding['status'] == "Commercial Bindery") {
									$firstHolding['status'] = "Being Repaired";
								}
								
								if ($firstHolding['status'] == "In process-Binding") {
									$firstHolding['status'] = "Being Repaired";
								}
								
								if ($firstHolding['status'] == "Preservation-Disaster Response") {
									$firstHolding['status'] = "Being Repaired";
								}
								
								if ($firstHolding['status'] == "In Route") {
									$firstHolding['status'] = "In Transit";
								}
								
								if ($firstHolding['status'] == "In Transit/Sublibrary") {
									$firstHolding['status'] = "In Transit";
								}
								
								if ($firstHolding['status'] == "Missing") {
									$firstHolding['status'] = "Lost/Missing";
								}
								
								if ($firstHolding['status'] == "Lost") {
									$firstHolding['status'] = "Lost/Missing";
								}
								
								if ($firstHolding['status'] == "Order Initiated") {
									$firstHolding['status'] = "On Order";
								}
								
								if ($firstHolding['status'] == "In process-Acquisitions") {
									$firstHolding['status'] = "On Order";
								}
								
								if ($firstHolding['status'] == "In process-Cataloging") {
									$firstHolding['status'] = "On Order";
								}
								
								if ($firstHolding['status'] == "In process-Collection Dev.") {
									$firstHolding['status'] = "On Order";
								}
								
								if ($firstHolding['status'] == "In process-MPU") {
									$firstHolding['status'] = "On Order";
								}
								
								if ($firstHolding['status'] == "In process-Documents") {
									$firstHolding['status'] = "On Order";
								}
								
								if ($firstHolding['status'] == "In process-Outsourced Cat.") {
									$firstHolding['status'] = "On Order";
								}
								
								if ($firstHolding['status'] == "Sent to Technical Services") {
									$firstHolding['status'] = "On Order";
								}
								
								if ($firstHolding['status'] == "In process") {
									$firstHolding['status'] = "On Order";
								}
								
								if ($firstHolding['status'] == "Expected") {
									$firstHolding['status'] = "On Order";
								}
								
								if ($firstHolding['status'] == "Claimed") {
									$firstHolding['status'] = "On Order";
								}
								
								if ($firstHolding['status'] == "Publication Delayed") {
									$firstHolding['status'] = "On Order";
								}
								
								if ($firstHolding['status'] == "Delivery Delayed") {
									$firstHolding['status'] = "On Order";
								}
								
								if ($firstHolding['status'] == "Out of Stock") {
									$firstHolding['status'] = "On Order";
								}
								
								if ($firstHolding['status'] == "Out of Print") {
									$firstHolding['status'] = "On Order";
								}
								
								if ($theDueDate != "") {
									$firstHolding['status'] = "Checked Out";
								}
								
								
								if (isset($libraryName)) {
									if ($libraryName == "Rubenstein Library") {
										if ($firstHolding['status'] == "Available") {
											$firstHolding['status'] = "Available (Library Use Only)";
										}
									}
								}
							
							
							
							
							
							if (strpos($firstHolding['status'],'Available') !== false) {
							
								echo '<span class="available-status green">' . $firstHolding['status'] . '</span>';
							
							} else {
							
								echo '<span class="available-status red">' . $firstHolding['status'] . '</span>';
							
							}
							
							
							
							echo '</div>';
							
							//echo 'firstHolding-status: ';
							//echo $firstHolding['status'];
							//echo '<br />';
							
							
							$holdingsCount = count($arrHoldings);
							
							// Extra Holdings
							if ($holdingsCount) {
								
								// single holding
								if ($holdingsCount == 1) {
									
									// Check for DC
									if ($dcItem == "true") {
									
										echo '<div class="more-holdings">There is ' . $holdingsCount . ' additional item available &ndash; <a href="http://search.library.duke.edu/search?id=' . $theID . '" onClick="ga(\'send\', \'event\', { eventCategory: \'BentoResults\', eventAction: \'BooksMedia\', eventLabel: \'ItemMoreHoldings' . $resultCount . '\'});">show more &raquo;</a></div>';
								
									} else {
										
										echo '<div class="more-holdings">There is ' . $holdingsCount . ' additional item available &ndash; <a href="http://search.library.duke.edu/search?id=DUKE' . $theID . '" onClick="ga(\'send\', \'event\', { eventCategory: \'BentoResults\', eventAction: \'BooksMedia\', eventLabel: \'ItemMoreHoldings' . $resultCount . '\'});">show more &raquo;</a></div>';
										
									}
								
								
								// multiple holdings
								} else {
								
									// Check for DC
									if ($dcItem == "true") {
									
										echo '<div class="more-holdings">There are ' . $holdingsCount . ' additional items available &ndash; <a href="http://search.library.duke.edu/search?id=' . $theID . '" onClick="ga(\'send\', \'event\', { eventCategory: \'BentoResults\', eventAction: \'BooksMedia\', eventLabel: \'ItemMoreHoldings' . $resultCount . '\'});">show more &raquo;</a></div>';
										
									} else {
										
										echo '<div class="more-holdings">There are ' . $holdingsCount . ' additional items available &ndash; <a href="http://search.library.duke.edu/search?id=DUKE' . $theID . '" onClick="ga(\'send\', \'event\', { eventCategory: \'BentoResults\', eventAction: \'BooksMedia\', eventLabel: \'ItemMoreHoldings' . $resultCount . '\'});">show more &raquo;</a></div>';
									
									}
								
								
								}
								
								//print_r($arrHoldings);
								
								// extra holdings 
									
								

							
							}
							
						//print_r($Statuses);
						
						echo '</div>';
						
						}
						// End holdings
				
				
		
					echo '</div>';

				echo '</div>';
				
				
				
				// clear all variables
				unset($title);
				unset($theTitle);
				unset($ID);
				unset($theID);
				unset($Imprint);
				unset($theImprint);
				unset($Publisher);
				unset($thePublisher);
				unset($ISBN);
				unset($theISBN);
				unset($UPC);
				unset($theUPC);
				unset($author);
				unset($theAuthor);
				unset($otherAuthors);
				unset($otherAuthors1);
				unset($itemtype);
				unset($theItemtype);
				unset($OCLC);
				unset($theOCLC);
				unset($Published);
				unset($thePublished);
				unset($Material);
				unset($theMaterial);
				unset($ItemID);
				unset($theItemID);
				unset($Libraries);
				unset($theLibraries);
				unset($CallNumber);
				unset($theCallNumber);
				unset($SOR);
				unset($theSOR);
				unset($Statuses);
				unset($primaryStatus);
				unset($DueDate);
				unset($theDueDate);
				
				unset($holdingsCount);
				unset($holdingString);
				unset($x);
				
				unset ($libraryName);
				
				unset ($dcItem);
				unset ($ThumbnailURL);
				unset ($theThumbnailURL);
				
				$i ++;

			endwhile;
			
			unset($resultCount);

		}

		else {
			
			echo '<div class="no-results">';
			
			$searchWarning = "No Books &amp; More results found for <em>" . $queryTerms . "</em>.";
	
			$searchWarning .= '<br/><br/><a href="http://search.library.duke.edu/" onClick="ga(\'send\', \'event\', { eventCategory: \'BentoResults\', eventAction: \'BooksMedia\', eventLabel: \'TryAnotherSearch\'});">Try another search &raquo;</a>';
	
			echo $searchWarning;
			
			echo '</div>';
			
		}

		echo '</div>';
		
	
		// See all bottom link
		if($searchResults != "0" AND $theSearch != "") {
			
			echo '<div class="see-all">';
				
				echo '<a href="http://search.library.duke.edu/search?Nty=1&Ntk=Keyword&N=0&Ntt=' . $theSearch . '" onClick="ga(\'send\', \'event\', { eventCategory: \'BentoResults\', eventAction: \'BooksMedia\', eventLabel: \'SeeAllBottom\'});">See All Results</a>';
				
			echo '</div>';
		
		}
	

	echo '</div>';

}

$endecaEnd = microtime(true);

// Check for logging
$bentoLogging = variable_get('dul_bento.bento_logging', '');
		
if ($bentoLogging == 1) {

	global $endecaCreationTime;
	global $endecaXMLCreationTime;

	$endecaCreationTime = ($endecaEnd - $endecaStart);
	$endecaXMLCreationTime = ($endecaXMLEnd - $endecaXMLStart);
	
	//$difference = ($endecaCreationTime - $endecaXMLCreationTime);
	//echo '<br>Difference Time: ' . $difference . '<br><br>';
	
}

?>
