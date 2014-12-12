<?php

function hmacsha1($key,$data) {
	$blocksize=64;
	$hashfunc='sha1';
	if (strlen($key)>$blocksize) {
		$key=pack('H*', $hashfunc($key));
	}
	$key=str_pad($key,$blocksize,chr(0x00));
	$ipad=str_repeat(chr(0x36),$blocksize);
	$opad=str_repeat(chr(0x5c),$blocksize);
	$hmac = pack(
				'H*',$hashfunc(
					($key^$opad).pack(
						'H*',$hashfunc(
							($key^$ipad).$data
						)
					)
				)
			);
	return base64_encode($hmac);
}

function is_authorized() {

	// Returns TRUE if the browser is authenticated
	// Returns TRUE if the request is coming from a wired campus IP range
	// Otherwise returns FALSE

	// Grab the IP address of the request
	$theIP =  ($_SERVER['REMOTE_ADDR']);

	// Grab Shib session cookie
	$shibQuery = $_COOKIE[current(preg_grep('/^_shibsession_/', array_keys($_COOKIE)))];

	// Check for shib cookie first
	// Then check for IP range matches
	if (!empty($shibQuery)) {
		$authorized = true;
	} elseif (preg_match('/152\.(3|16)\.\d*\.\d*/', $theIP)) {
		// This checks to match
		// 152.3.*
		// 152.16.*
		$authorized = true;
	} elseif (preg_match('/67\.159\.(\d*)\.\d*/', $theIP, $matches)) {
		// This checks to match
		// 67.159.64-127.*
		if ($matches[1] >= 64 and $matches[1] <= 127) {
			$authorized = true;
		} else {
			$authorized = false;
		}
	} elseif (preg_match('/198\.(\d*)\.\d*\.\d*/', $theIP, $matches)) {
		// This checks to match
		// 198.29-86.*
		if ($matches[1] >= 29 and $matches[1] <= 86) {
			$authorized = true;
		} else {
			$authorized = false;
		}
	} else {
		$authorized = false;
	}

	return $authorized;
}

function formatAuthor($document) {
	
	$authorList = "";
	
	$i = 1;
	$t = 1;
	
	if (isset($document["Author"])) {
		
		$t = count($document["Author"]);
		
		if(is_array($document["Author"])) {
		
			foreach($document["Author"] as $author) {
			
				$authorList .= $author;
				if(($t - 1) == $i) $authorList .= " and ";
				elseif(($t - 1) > $i) $authorList .= "; ";
				$i++;
			}
			
		}
		
	}
	
	if (isset($document["CorporateAuthor"])) {
			
		if (!isset($document["Author"])) {
			
			$authorList = $document["CorporateAuthor"][0];
				
		} 
		
		else {
			
			$authorList .= "; and " . $document["CorporateAuthor"][0];
			
		}
		
	}
	
		
	return $authorList;

	
}

function querySummonDUL($query, $results, $contentTypes, $facetParameterSetting, $section) {

	// ==============================================
	// Summon API query parameters
	// ==============================================
	
	
	$pagesize = 20; // default value
	if(isset($results)) $pagesize = $results; 
	
	$summon_js_query = stripslashes($query);
	
	// query encoded for query request
	$request_query = urlencode($summon_js_query);
	$key_query = $summon_js_query;

	$authorization_status = is_authorized();
	
	// These definitions are for the 'Identification String'
	if ($authorization_status) {
		$queryParameter = "s.q=" . $key_query . "&s.role=authenticated";  // User query with authentication for all results
	} else {
		$queryParameter = "s.q=" . $key_query . "&s.role=none";  // User query without authentication
	}
	
	$facetParameter = "s.cmd=" . $facetParameterSetting; // Limit to records held by Duke
	$facetParameter .= " setPageSize($pagesize)";  // set number of results per page
	
	
	// add contentTypes
	$facetParameter = $facetParameter . implode(" ", $contentTypes);
	
	//echo $facetParameter . '<br />';
	
	
	$requestParameters = array();
	array_push($requestParameters, $facetParameter);
	array_push($requestParameters, $queryParameter);
	
	
	// These definitions are for the cURL request
	
	if ($authorization_status) {
		$encodedQueryParameter = "s.q=" . $request_query . "&s.role=authenticated";  // User query with authentication for all results
	} else {
		$encodedQueryParameter = "s.q=" . $request_query . "&s.role=none";  // User query without authentication
	}
	
	$encodedFacetParameter = "s.cmd=" . urlencode($facetParameterSetting); // Limit to records held by Duke
	
	$encodedFacetParameter .= urlencode(" setPageSize($pagesize)");  // set number of results per page
	
	
	$encodedContentTypes = array();
	
	foreach($contentTypes as $type) {
   
		array_push($encodedContentTypes, urlencode($type));
		
	}
	
	$encodedFacetParameter = $encodedFacetParameter . implode("+", $encodedContentTypes);
	
	
	$encodedRequestParameters = array();
	array_push($encodedRequestParameters, $encodedFacetParameter);
	array_push($encodedRequestParameters, $encodedQueryParameter);
	
	
	// ==============================================
	// Summon API authentication configuration
	// ==============================================
	
	
	$accessId = variable_get('dul_bento.summon_accessId', '');
	$secretKey = variable_get('dul_bento.summon_secretKey', '');
	$clientKey = variable_get('dul_bento.summon_clientKey', '');
	
	
	// Build the 'Authorization Headers' for Summon API authentication
	$headers = array('Accept' => 'application/json',
					 'x-summon-date' => date('D, d M Y H:i:s T'),
					 'Host' => 'api.summon.serialssolutions.com');
	
	// Build the 'Identification String' used for Summon API authentication
	$identificationString = implode($headers, "\n") . "\n/2.0.0/search\n" . implode($requestParameters, "&") . "\n";
	
	
	// Build the HMAC hash used for Summon API authentication
	$hmacHash = hmacsha1($secretKey, $identificationString);
	
	// Build the 'Authorization Header' used for Summon API authentication
	$headers['Authorization'] = "Summon $accessId;$clientKey;$hmacHash";
	
	// Assemble the target Summon API request URL
	
		
		$summonApiUrl = "http://api.summon.serialssolutions.com/2.0.0/search?" . implode($encodedRequestParameters, "&");


	
	// Reformat HTTP headers array to be curl-friendly
	foreach ($headers as $key => $value) {
		$curlHeaders[] = $key . ": " . $value;
	}
	
	//Debug:
	//echo $summonApiUrl;


	// ==============================================
	// Summon API request using cURL
	// ==============================================
	
	// Make Summon API request using cURL
	$ch = curl_init();
	$timeout = 5;
	curl_setopt($ch, CURLOPT_URL, $summonApiUrl);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $curlHeaders);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$response = curl_exec($ch);
	
		// Check for logging
		$bentoLogging = variable_get('dul_bento.bento_logging', '');
		
		if ($bentoLogging == 1) {
			// Get Info
			$info = curl_getinfo($ch);
			$summonTime = $info['total_time'];
			global $summonPerformance;
			//$summonPerformance .= $section . ": " . $summonTime . "\r\n";
			$summonPerformance .= $summonTime . ",";
		}
	
	curl_close($ch);
	
	// ==============================================
	// Summon API response in JSON format
	// ==============================================
	
	return $response;	

}

?>