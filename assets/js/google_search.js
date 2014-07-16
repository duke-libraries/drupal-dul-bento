// Get the search parameters from the URL
function parseParamsFromUrl() {
  var params = {};
  var parts = window.location.search.substr(1).split('&');
  for (var i = 0; i < parts.length; i++) {
    var keyValuePair = parts[i].split('=');
    var key = decodeURIComponent(keyValuePair[0]);
    params[key] = keyValuePair[1] ?
        decodeURIComponent(keyValuePair[1].replace(/\+/g, ' ')) :
        keyValuePair[1];
  }
  return params;
}


var urlParams = parseParamsFromUrl();
var queryParamName = 'Ntt';

//alert(urlParams[queryParamName]);

google.load('search', '1');
google.setOnLoadCallback(function(){
	
	var customSearchOptions = {};
  	var orderByOptions = {};
	
	orderByOptions['keys'] = [{label: 'Relevance', key: ''} , {label: 'Date', key: 'date'}];
  	customSearchOptions['enableOrderBy'] = true;
  	customSearchOptions['orderByOptions'] = orderByOptions;
	
	// Website search	
	var cx_web = '012356957315223414689:tvt-hlpicwm';
  	var customSearchControl_web =   new google.search.CustomSearchControl(cx_web, customSearchOptions);
  	customSearchControl_web.setResultSetSize(google.search.Search.FILTERED_CSE_RESULTSET);
  	var options_web = new google.search.DrawOptions();
  	options_web.enableSearchResultsOnly();
  	options_web.setAutoComplete(true);
 	google.search.Csedr.addOverride("bentoweb_");
 	//options_web.setNoResultsString("No Website results found for your term");

    // Draw the Custom Search Control in the div named "cse_web"
  	customSearchControl_web.draw('cse_web', options_web); 
    customSearchControl_web.execute(urlParams[queryParamName]);


	// Libguides search
	var cx_libguides = '012356957315223414689:eqn6ecttvlm';
  	var customSearchControl_libguides =   new google.search.CustomSearchControl(cx_libguides, customSearchOptions);
  	customSearchControl_libguides.setResultSetSize(google.search.Search.FILTERED_CSE_RESULTSET);
  	var options_libguides = new google.search.DrawOptions();
  	options_libguides.enableSearchResultsOnly();
  	options_libguides.setAutoComplete(true);
 	google.search.Csedr.addOverride("bentolibguides_");
 	//options_libguides.setNoResultsString("No Research Guides results found for your term");

    // Draw the Custom Search Control in the div named "cse_libguides"
  	customSearchControl_libguides.draw('cse_libguides', options_libguides);
    customSearchControl_libguides.execute(urlParams[queryParamName]);
	

}, true);