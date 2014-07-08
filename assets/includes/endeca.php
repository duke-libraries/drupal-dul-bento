<?php

$urlString = "http://search.library.duke.edu/search?Nty=1&Ntk=Keyword&N=0&output-format=xml&Ntt=";

$theSearch = urlencode($queryTerms);

$theXML = simplexml_load_file ($urlString . $theSearch);

$i = 1;

if ($theSearch != "") {

	$searchResults = $theXML->xpath('/trln-endeca-results/results-data/endeca-search-info/searchInfoItems/nav-search-info/nav-search-reports/item/numberOfMatchingResults');

	$searchResults = (string) $searchResults[0];

} else {

	$searchResults = "0";
}

?>

<div class="resultsHeader">
        
		<h2>Books &amp; More <a href="http://search.library.duke.edu/search?Nty=1&Ntk=Keyword&N=0&Ntt=<?php echo $theSearch; ?>" class="callbox" style="margin-left: 10px;">See All »</a></h2>
        
		<p class="smaller muted">Books, journals, films, audio &amp; more</p>	
		
<?php 

if($searchResults != "0" AND $theSearch != "") {

	while ($i < 7):

		$title = $theXML->xpath('/trln-endeca-results/results-data/endeca-records-list/records/item['.$i.']/properties/Main-Title/item');
		$ID = $theXML->xpath('/trln-endeca-results/results-data/endeca-records-list/records/item['.$i.']/properties/LocalId/item');
		$ISBN = $theXML->xpath('/trln-endeca-results/results-data/endeca-records-list/records/item['.$i.']/properties/Syndetics-ISBN/item');
		$author = $theXML->xpath('/trln-endeca-results/results-data/endeca-records-list/records/item['.$i.']/properties/Main-Author/item');
		$otherAuthors = $theXML->xpath('/trln-endeca-results/results-data/endeca-records-list/records/item['.$i.']/properties/Other-Authors/item');
		$itemtype = $theXML->xpath('/trln-endeca-results/results-data/endeca-records-list/records/item['.$i.']/properties/Item-Types/item');
		$OCLC = $theXML->xpath('/trln-endeca-results/results-data/endeca-records-list/records/item['.$i.']/properties/OCLCNumber/item');
		$Published = $theXML->xpath('/trln-endeca-results/results-data/endeca-records-list/records/item['.$i.']/properties/Published/item');
		$Material = $theXML->xpath('/trln-endeca-results/results-data/endeca-records-list/records/item['.$i.']/properties/Material/item');
		
		if (!empty ($title)) {
			$theTitle = (string) $title[0];
			$theTitle = htmlentities($theTitle, ENT_QUOTES, 'UTF-8');
		}
		
		if (!empty ($ID)) {
			$theID = (string) $ID[0];
		}
		
		if (!empty ($ISBN)) {
			$theISBN = (string) $ISBN[0];
		}
		
		if (!empty ($author)) {
			$theAuthor = (string) $author[0];
			$theAuthor = rtrim(htmlentities($theAuthor, ENT_QUOTES, 'UTF-8'), '.');
		} else {
			$theAuthor = "NONE";
		}
		
		if (!empty ($itemtype)) {
			$theItemtype = (string) $itemtype[0];
		}
		
		if (!empty ($OCLC)) {
			$theOCLC = (string) $OCLC[0];
		} else {
			$theOCLC = "";
		}
		
		if (!empty ($Published)) {
			$thePublished = (string) $Published[0];
			$thePublished = rtrim(ltrim($thePublished, 'c'), '.');
		}
			
		if (!empty ($Material)) {
			$theMaterial = (string) $Material[0]; 
		}
		
		if (!empty ($otherAuthors)) {
			
			//need to strip periods!
			
			if (isset($otherAuthors[0])) {
			$otherAuthors1 = (string) $otherAuthors[0];
				$otherAuthors1 = rtrim(htmlentities($otherAuthors1, ENT_QUOTES, 'UTF-8'), '.');
			}
			if (isset($otherAuthors[1])) {
			$otherAuthors2 = (string) $otherAuthors[1];
				$otherAuthors2 = rtrim(htmlentities($otherAuthors2, ENT_QUOTES, 'UTF-8'), '.');
			}
			if (isset($otherAuthors[2])) {
			$otherAuthors3 = (string) $otherAuthors[2];
				$otherAuthors3 = rtrim(htmlentities($otherAuthors3, ENT_QUOTES, 'UTF-8'), '.');
			}
			if (isset($otherAuthors[3])) {
			$otherAuthors4 = (string) $otherAuthors[3];
				$otherAuthors4 = rtrim(htmlentities($otherAuthors4, ENT_QUOTES, 'UTF-8'), '.');
			}
			if (isset($otherAuthors[4])) {
			$otherAuthors5 = (string) $otherAuthors[4];
				$otherAuthors5 = rtrim(htmlentities($otherAuthors5, ENT_QUOTES, 'UTF-8'), '.');
			}
		}
		

		echo '<div class="document-frame" titlelocalid="' . $theID . '">';
		
			echo '<div class="title">';
				echo '<div class="text">';
					echo '<h3 class="resultTitle"><a href="http://search.library.duke.edu/search?id=DUKE' . $theID . '">' . $theTitle . '</a></h3>';
				echo '</div>';
			echo '</div>';
		
		if (isset($theISBN)) {
			
			
			$imagePath = "http://www.syndetics.com/index.aspx?isbn=" . $theISBN . "/MC.GIF&oclc=" . $theOCLC . "&client=trlnet";
			$imageSize = getimagesize($imagePath);
				
			if ($imageSize[0] != '1') {
			
			
				echo '<div class="thumbnail">';
					echo '<a href="http://search.library.duke.edu/search?id=DUKE' . $theID . '"><img src="http://www.syndetics.com/index.aspx?isbn=' . $theISBN . '/MC.GIF&oclc=' . $theOCLC . '&client=trlnet" alt="cover artwork" class="artwork"></a>';
				echo '</div>';
				
				
			}
			
			
		}
		
		
			echo '<div class="document-summary">';
				
				// AUTHORS
				echo '<div class="authors">';
				
				if ($theAuthor != "NONE") {
					echo 'by <a href="http://duke.summon.serialssolutions.com/search?s.dym=false&s.q=Author%3A%22' . $theAuthor . '%22">' . $theAuthor . '</a>';
				
					if (!empty ($otherAuthors)) {
				
						if (isset($otherAuthors1)) {
					
							if (isset($theAuther)) {
						
								echo 'by <a href="http://duke.summon.serialssolutions.com/search?s.dym=false&s.q=Author%3A%22' . $otherAuthors1 . '%22">' . $otherAuthors1 . '</a>';
						
							} else {
						
								echo ' and <a href="http://duke.summon.serialssolutions.com/search?s.dym=false&s.q=Author%3A%22' . $otherAuthors1 . '%22">' . $otherAuthors1 . '</a>';
							}
					
						}	
					
						if (isset($otherAuthors2)) {
							echo ' and <a href="http://duke.summon.serialssolutions.com/search?s.dym=false&s.q=Author%3A%22' . $otherAuthors2 . '%22">' . $otherAuthors2 . '</a>';
						
						}
					
						if (isset($otherAuthors3)) {
							echo ' and <a href="http://duke.summon.serialssolutions.com/search?s.dym=false&s.q=Author%3A%22' . $otherAuthors3 . '%22">' . $otherAuthors3 . '</a>';
						
						}
					
						if (isset($otherAuthors4)) {
							echo ' and <a href="http://duke.summon.serialssolutions.com/search?s.dym=false&s.q=Author%3A%22' . $otherAuthors4 . '%22">' . $otherAuthors4 . '</a>';
						
						}
					
						if (isset($otherAuthors5)) {
							echo ' and <a href="http://duke.summon.serialssolutions.com/search?s.dym=false&s.q=Author%3A%22' . $otherAuthors5 . '%22">' . $otherAuthors5 . '</a>';
						
						}
						
					}
				
				}
				
				
					
				echo '</div>';
				
				
				// PUBLISHER
				echo '<div class="publisher">';
					
					if (isset($thePublished)) {
						echo $thePublished . ', ';
					}

					if (isset($theISBN)) {
						echo '<strong>ISBN </strong>' . $theISBN . ', ';
					}
					
					if (isset($theMaterial)) {
						echo $theMaterial;
					}
					
				echo '</div>';
				
				
		
			echo '</div>';

		echo '</div>';

		$i ++;

	endwhile;

}

else {
	
	$searchWarning = "No Books &amp; More results found for <em>" . $queryTerms . "</em>.";
	
	if ($theSearch == "") {
		$searchWarning = "Please enter a search term above.";
	}
	
	echo $searchWarning;
}

echo '</div>';



?>
