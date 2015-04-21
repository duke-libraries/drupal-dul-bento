<?php
date_default_timezone_set('America/New_York');

require_once('SolrPhpClient/Service.php');

$logfile = 'private://bestbets_log.txt';

//$queryTerms = 'thompson one'; // switch to use $queryTerms

// Remove these special characters:  ( ) ? * [ ] ` ! # $ % { } < > ^ ~ | + & 
$sanitized_query = preg_replace("/\(|\)|\?|\*|\[|\]|`|!|#|\$|%|{|}|<|>|\^|~|\||\+|&/", "", $queryTerms);

// Replace these special characters with a single space: . : ; , - _ / = @
$sanitized_query = preg_replace("/\.|:|;|,|-|_|\/|=|@|'/", " ", $sanitized_query);

// This is a lazy way of doing this, but replace é and è with e
$sanitized_query = preg_replace("/é|è/", "e", $sanitized_query);


// Replace 1 or more whitespace charaters with a single space
$sanitized_query = preg_replace("/[ \t\n\r\s]+/", " ", $sanitized_query);

$bestbets_query = str_replace('"', '', $sanitized_query);

// TODO: Strip multiple spaces, spaces at beginning and end, special characters.

$limit = 1;
$params = array();
$results = '';
$errors = array();

// The dismax parser was proving troublesome for best bets with longer keywords and many stopwords
// So we're back to exact phrase matching for now.
$bestbets_query = 'keywords: "' . $bestbets_query . '"';

// if (substr_count($bestbets_query, ' ') < 3) {
//     // for queries containing 3 or fewer terms
//     // use non-analyzed keyword field with phrased search
//     $bestbets_query = 'keywords: "' . $bestbets_query . '"';
// } else {
//     // use DisMax query parser for longer queries
//     // NOTE: edismax parser causes too liberal matching if the word "or"
//     //       is present in the query
//     // force at least 4 terms to match
//     $params = array('defType' => 'dismax', 'mm' => '3', 'qf' => 'text');
// }

$solr = new Apache_Solr_Service('collections-01.lib.duke.edu', '8080', '/solr_bestbets');

if (get_magic_quotes_gpc() == 1) {
    $bestbets_query = stripslashes($bestbets_query);
}

try {
    $results = $solr->search($bestbets_query, 0, $limit, $params);
} catch ( Exception $e ) {
    $errors[] = $e->getMessage();
}

$id = $results->response->docs[0]->id;
$title = $results->response->docs[0]->title;
$url = $results->response->docs[0]->url;
$description = $results->response->docs[0]->description;

if ($title && $url && $id) {

    $bestbet_loginfo = date('Y-m-d H:i:s');
    $bestbet_loginfo .= "\tbb_serve";
    $bestbet_loginfo .= "\t" . $id . "\n";

    file_put_contents($logfile, $bestbet_loginfo, FILE_APPEND | LOCK_EX);

    echo '<h3><a href="' . $url . '" class="best-bet-link" data-best-bet-id="' . $id . '" onClick="ga(\'send\', \'event\', { eventCategory: \'BentoResults\', eventAction: \'BestBets\', eventLabel: \'' . $title . '\'})">' . $title . '</a>&nbsp;&nbsp;&nbsp;<span class="best-bet-flag">Best Bet</span></h3>';
    echo '<p class="best-bet-description">' . $description . '</p>';
}

?>