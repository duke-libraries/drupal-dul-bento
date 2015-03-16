<?php

// Testing //

//echo '<!-- COOKIES: ';

//print_r($_COOKIE);

//echo '-->';



//echo '<!-- SHIB: ';

//echo $_SERVER["HTTP_SHIB_SESSION_ID"];

//echo $_SERVER["Shib-Session-ID"];

//echo '-->';





// END Testing //



$pageStart = microtime(true);

settype($Ntt, "string");
settype($results, "string");
settype($ctype, "string"); 
extract($_GET, EXTR_IF_EXISTS);

$bento_action = $GLOBALS['base_path'] . variable_get('dul_bento.results_url', '');

if(isset($Ntt))
{
	$queryTerms = stripslashes($Ntt);
	$queryDisplay = htmlspecialchars($queryTerms);
}
else $queryTerms = "Search for articles, books &amp; more";

$pageSize=preg_replace("[^0-9]","",$results); // sanitizing input to digits only
if($pageSize == "" || $pageSize > 20) $pageSize = 20; // max 20 from endeca, hard coded in application

if(isset($ctype) && $ctype!="")
$contentType = stripslashes($ctype); 
else $contentType = NULL;

?>

	<div class="discovery">
		<div class="search-resources-tabs bento">
			<div class="tab-content">
				
				<div id="articles" class="tab-pane active">			
					<form class="form-inline" action="<?php echo $bento_action; ?>">

						<input id="Ntt" type="text" name="Ntt" value="<?php echo $queryDisplay; ?>" class="" placeholder="Search articles, books, journals, &amp; our website"> <button type="submit" class="btn btn-primary bannerSearch"> <em class="icon-search icon-white");"> &nbsp; </em> </button>
						
					</form>
			
				</div>
				
				<ul class="inline small">
					<li><a href="/find/about" title="Learn more about this search results page" onClick="ga('send', 'event', { eventCategory: 'BentoResults', eventAction: 'MainSearch', eventLabel: 'About'});">How to use this search</a></li>
				</ul>
				
			</div>
		</div>
	</div>
	
	
	<div class="search-results-nav">
	
		<ul>
		
			<li><a href="#results-articles">Articles</a></li>
			<li><a href="#results-books">Books &amp; More</a></li>
			<li><a href="#results-website">Our Website</a></li>
			<li><a href="#results-libguides">Research Guides</a></li>
			<!--<li><a href="#results-staff">Staff</a></li>-->
			<li><a href="#results-images">Images</a></li>
			<li><a href="#results-other">Other Resources</a></li>
		
		</ul>
	
	</div>


	<div class="grid-4 alpha">
		
		<div class="content-pad-left">
		
		<?php include("assets/includes/summon_articles.php"); ?>
		
		</div>
	
	</div>
	
	
	<div class="grid-4">
	
		<div class="content-pad">
		
		<?php include("assets/includes/endeca.php"); ?>
		
		</div>
	
	</div>
	
	
	<div class="grid-4 omega">
	
		<div class="content-pad-right">
		
		<?php include("assets/includes/google_website.php"); ?>
		
		<?php include("assets/includes/google_libguides.php"); ?>
		
		<?php // include("assets/includes/staff.php"); ?>
		
		<?php include("assets/includes/summon_images.php"); ?>
		
		<?php include("assets/includes/summon_other.php"); ?>
		
		</div>
	
	</div>
	
<?php


// +++ Log performance data +++


$bentoLogging = variable_get('dul_bento.bento_logging', '');
$bentoLoggingDaily = variable_get('dul_bento.bento_logging_daily', '');


if ($bentoLogging == 1) {
	
	$nowDate = date('Y-m-d H:i:s');

	$pageEnd = microtime(true);
	$pageCreationTime = ($pageEnd - $pageStart);


	global $summonPerformance;

	$logfile1 = 'private://bento_log.txt';
	
	// extra output for daily logging
	if ($bentoLoggingDaily == 1) {
	
		$logfile2 = 'private://bento_log_' . date('Y-m-d') . '.txt';
		
	} 
	

	if ($summonPerformance != "") {

		$performance_info = "";
	
		$performance_info .= $nowDate . ',';
		
			// replace " with '
			$performanceTerms = str_replace('"',"'",$queryTerms);
	
		$performance_info .= '"' . $performanceTerms . '"' . ',';
	
		$performance_info .= $endecaXMLCreationTime . ",";
		
		$performance_info .= $endecaCreationTime . ",";

		$performance_info .= $summonPerformance;
		
		$performance_info .= $summonArticlesCreationTime . ",";
		
		$performance_info .= $summonImagesCreationTime . ",";
		
		$performance_info .= $summonOtherCreationTime . ",";

		$performance_info .= $pageCreationTime ."\r\n";

		file_put_contents($logfile1, $performance_info, FILE_APPEND | LOCK_EX);
		
		if ($bentoLoggingDaily == 1) {
			file_put_contents($logfile2, $performance_info, FILE_APPEND | LOCK_EX);
		}
		
		
	
		//echo $performance_info;
	
	}
	
}

	
	// private storage path = /srv/web/libcms/backup

	// datetime, queryterms, endeca, articles, images, other, fullpage



?>