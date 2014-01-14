<?php

require_once '../config.inc.php';
require_once '../functions.inc.php';

$conid = db_connect(); 

/************************************************
	Search Functionality
************************************************/

// Define Output HTML Formating
$html .= '<li class="result-mod">';
$html .= '<input type="hidden" name="modelid" value="modelID" /><input type="hidden" name="modelname" value="modelName" />';
$html .= '<a href="#" class="list-group-item" onclick="document.forms[\'mod\'].submit();">modelName</a>';
$html .= '</li>';

// Get Search
$search_string = preg_replace("/[^A-Za-z0-9]/", " ", $_POST['query']);
$search_string = $conid->real_escape_string($search_string);

// declare vars
$output = '';

// Check Length More Than One Character
if (strlen($search_string) >= 1 && $search_string !== ' ') {
	// Build Query
	$query = 'SELECT * FROM '.TBL_PREFIX.'models WHERE modelName LIKE "%'.$search_string.'%"';

	// Do Search
	$result = $conid->query($query);
	while($results = $result->fetch_array()) {
		$result_array[] = $results;
	}

	// Check If We Have Results
	if (isset($result_array)) {
		foreach ($result_array as $result) {

			// Insert Name
			$output = str_replace('modelName', $result['modelName'], $html);

			// Insert ID
			$output = str_replace('modelID', $result['modelID'], $output);

			// Output
			echo($output);
		}
	}else{

		// Format No Results Output
		$output = str_replace('modelName', '<b>No Results Found.</b>', $output);

		// Output
		echo($output);
	}
}

?>
