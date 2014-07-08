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
	
		elseif(isset($document[CorporateAuthor][0])) {
			$authorList = $document[CorporateAuthor][0]; // for Government documents, etc.
		}
	
		else $authorList = NULL;
	
		return $authorList;
		
	}

	
}

function querySummonDUL($query, $results, $contentTypes, $facetParameterSetting) {

	// ==============================================
	// Summon API query parameters
	// ==============================================
	
	
	$pagesize = 20; // default value
	if(isset($results)) $pagesize = $results; 
	
	$summon_js_query = stripslashes($query);
	
	// query encoded for query request
	$request_query = urlencode($summon_js_query);
	$key_query = $summon_js_query;
	
	// These definitions are for the 'Identification String'
	$queryParameter = "s.q=" . $key_query . "&s.role=authenticated";  // User query with authentication for all results
	
	$facetParameter = "s.cmd=" . $facetParameterSetting; // Limit to records held by Duke
	$facetParameter .= " setPageSize($pagesize)";  // set number of results per page
	
	
	// add contentTypes
	$facetParameter = $facetParameter . implode(" ", $contentTypes);
	
	//echo $facetParameter . '<br />';
	
	
	$requestParameters = array();
	array_push($requestParameters, $facetParameter);
	array_push($requestParameters, $queryParameter);
	
	
	// These definitions are for the cURL request
	//$encodedQueryParameter = "s.q=" . $request_query;  // User query
	$encodedQueryParameter = "s.q=" . $request_query . "&s.role=authenticated";  // User query with authentication for all results
	
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
	curl_close($ch);
	
	// ==============================================
	// Summon API response in JSON format
	// ==============================================
	
	return $response;
	
	

}

?>