<?php

settype($Ntt, "string");
settype($results, "string");
settype($ctype, "string"); 
extract($_GET, EXTR_IF_EXISTS);

if(isset($Ntt))
{
	$queryTerms = stripslashes($Ntt);
}
else $queryTerms = "Search for articles, books &amp; more";

$pageSize=preg_replace("[^0-9]","",$results); // sanitizing input to digits only
if($pageSize == "" || $pageSize > 20) $pageSize = 20; // max 20 from endeca, hard coded in application

if(isset($ctype) && $ctype!="")
$contentType = stripslashes($ctype); 
else $contentType = NULL;

?>


	<div class="search-resources-tabs">
		<div class="tab-content">
			<div id="articles" class="tab-pane active">			
				<form class="form-inline" action="bento">

					<input id="Ntt" type="text" name="Ntt" value="<?php echo $queryTerms; ?>" class="" placeholder="Search articles, books, journals, &amp; our website"> <button type="submit" class="btn btn-primary bannerSearch"> <em class="icon-search icon-white");"> &nbsp; </em> </button>

				</form>
			
			</div>
		</div>
	</div>


	<div class="grid-4 alpha">
		
		<div class="content-pad">
		
		<?php
		
		include("assets/includes/summon_articles.php"); 
		
		include("assets/includes/summon_other.php"); 
		
		?>
		
		</div>
	
	</div>
	
	
	<div class="grid-4">
	
		<div class="content-pad">
		
		<?php include("assets/includes/endeca.php"); ?>
		
		</div>
	
	</div>
	
	
	<div class="grid-4 omega">
	
		<div class="content-pad">
		
		<?php include("assets/includes/google_libguides.php"); ?>
		
		<?php //include("assets/includes/staff.php"); ?>
		
		<?php include("assets/includes/summon_images.php"); ?>
		
		<?php include("assets/includes/google_website.php"); ?>
		
		</div>
	
	</div>