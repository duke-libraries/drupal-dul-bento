<?php

//$queryTerms = 'aery';

//$theSearch = urlencode($queryTerms);

$theSearch = $queryTerms;


///

if($queryTerms != "") {

// query string goes here


?>


	<div class="results-block" id="results-staff">
		
		<h2>Staff <a href="/about/directory/" class="callbox" style="margin-left: 10px;">See&nbsp;All&nbsp;&raquo;</a></h2>
		
		<p class="smaller muted">Contact library staff for help researching this topic</p>
	
		<div class="results-panel">

	<?php

	
	# set the active database to 'django'
	db_set_active('django');
	
	
	// 'Old' staff query technique
	
	//$query = db_select('directory_person', 'p')
	//	->fields('p');        // all columns in the 'directory_person table
	//$query->join('directory_orgunit_members', 'm', 'p.id = m.person_id');
	//$query->join('directory_orgunit', 'o', 'o.id = m.orgunit_id');
	//$query->addField('o', 'name', 'dept_name');
	
	//$query->condition(
	//	db_or()	->condition('p.first_name', '%' . $theSearch . '%', 'LIKE')
	//			->condition('p.last_name', '%' . $theSearch . '%', 'LIKE')
	//			->condition('p.nickname', '%' . $theSearch . '%', 'LIKE')
	//			->condition('p.display_name', '%' . $theSearch . '%', 'LIKE')
	//			->condition('p.preferred_title', '%' . $theSearch . '%', 'LIKE')
	//			->condition('p.profile', '%' . $theSearch . '%', 'LIKE')
	//			->condition('p.keywords', '%' . $theSearch . '%', 'LIKE')
	//			->condition('o.name', '%' . $theSearch . '%', 'LIKE')
	//	);
	// *commented out before * //$query->distinct(); // make distinct
	// *commented out before * //$query->orderBy('p.id', 'ASC');
	//$query->orderBy('p.last_name', 'ASC');
	//$query->orderBy('p.first_name', 'ASC');
	//$query->range(0,10); // limit to 10 results

	//$staff_persons = $query->execute();
	
	
	// 'New' staff query technique
	// simple 'or' search -- ideally we should index the table and assign relevancy multipliers, etc.
	$staff_persons = db_query("SELECT p.* FROM {directory_person} p INNER JOIN {directory_orgunit_members} m ON p.id = m.person_id INNER JOIN {directory_orgunit} o ON o.id = m.orgunit_id WHERE MATCH (p.first_name, p.last_name, p.nickname, p.display_name, p.preferred_title, p.profile, p.keywords, o.name) AGAINST ('" . $theSearch . "' IN BOOLEAN MODE) ORDER BY p.last_name ASC, p.first_name ASC LIMIT 10");
	
	
	


	if ($staff_persons->rowCount() == 0) {

		echo "No Staff results found for <em>" . $queryTerms . "</em>.";
	
		echo '<br/><br/><a href="/about/directory/">Try another search &raquo;</a>';

	} else {
	
		
		$staffNamesList = array();
		
		$staffCount = 0;
	
	
		foreach ($staff_persons as $person) {

			if ((!in_array($person->display_name, $staffNamesList)) && ($staffCount < 3)) {
				
				
				// Set Profile
				$theProfile = $person->profile;
				
					// remove html characters
					$theProfile = strip_tags(str_replace('&nbsp;',' ',$theProfile));
				
					// truncate long profiles
					if (strlen($theProfile) > 150) {
						$theProfile = wordwrap($theProfile, 150);
						$theProfile = substr($theProfile, 0, strpos($theProfile, "\n"));
						$theProfile = $theProfile . ' (&hellip;)';
					}
			
		
		
				// Set Title
				if ($person->preferred_title != "") {
					$theTitle = $person->preferred_title;
				} else {
					$theTitle = $person->title;
				}
					// truncate long title
					if (strlen($theTitle) > 50) {
						$theTitle = wordwrap($theTitle, 50);
						$theTitle = substr($theTitle, 0, strpos($theTitle, "\n"));
						$theTitle = $theTitle . ' (&hellip;)';
					}



				echo '<div class="personResult" title="' . $theProfile . '">';
	
	
					if ($person->photo_url != "") {
		
						echo '<div class="thumbnail">';
						echo '<a href="/about/directory/staff/' . $person->id .  '/"><img src="' . $person->photo_url . '"></a>';
						echo '</div>';
	
					} else {
			
						echo '<div class="thumbnail">';
						echo '<a href="/about/directory/staff/' . $person->id .  '/"><img src="http://libcms.oit.duke.edu/sites/default/files/dul/directory/no_photo_reading_devil.png"></a>';
						echo '</div>';

					}
	
					echo '<div class="name"><a href="/about/directory/staff/' . $person->id .  '/">' . $person->display_name . '</a></div>';
					echo '<div class="title">' . $theTitle . '</div>';
	
	
				echo '</div>';
				
			
			// add display name to array to compare	
			array_push($staffNamesList, $person->display_name);
			
			// increment counter
			$staffCount += 1;
			
				
			}
		
		}


	}


	# before you leave, set the active database back to the default.
	db_set_active();        // no arguments = 'default'


}

?>

		</div>


	</div>
	


	
