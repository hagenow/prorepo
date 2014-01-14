<?php

require_once '../config.inc.php';
require_once '../functions.inc.php';

$conid = db_connect(); 

/************************************************
	Search Functionality
************************************************/

// Define Output HTML Formating
$html = '';
$html .= '<li class="result-cat">';
$html .= '<input type="hidden" name="cid" value="catID" /><input type="hidden" name="cname" value="catName" />';
$html .= '<a href="#" class="list-group-item" onclick="document.forms[\'cat\'].submit();">catName</a>';
$html .= '</li>';

// Get Search
$search_string = preg_replace("/[^A-Za-z0-9]/", " ", $_POST['query']);
$search_string = $conid->real_escape_string($search_string);

// declare vars
$output = '';

// Check Length More Than One Character
if (strlen($search_string) >= 1 && $search_string !== ' ') {
	// Build Query
	$query = 'SELECT * FROM '.TBL_PREFIX.'categories WHERE catName LIKE "%'.$search_string.'%"';

	// Do Search
	$result = $conid->query($query);
	while($results = $result->fetch_array()) {
		$result_array[] = $results;
	}

	// Check If We Have Results
	if (isset($result_array)) {
		foreach ($result_array as $result) {

			// Format Output Strings And Hightlight Matches
			// $display_catname = preg_replace("/".$search_string."/i", "<b class='highlight'>".$search_string."</b>", $result['catName']);
			//$display_catid = $result['catID'];
            //$display_url = '?show=newmod&catID='.urlencode($result['catID']).'&catName='.urlencode($result['catName']);


			// Insert Name
			$output = str_replace('catName', $result['catName'], $html);

			// Insert ID
			$output = str_replace('catID', $result['catID'], $output);

            // Insert URL
            // $output = str_replace('urlString', $display_url, $output);

			// Output
			echo($output);
		}
	}else{

		// Format No Results Output
		$output = str_replace('catName', '<b>No Results Found.</b>', $output);

		// Output
		echo($output);
	}
}

?>
