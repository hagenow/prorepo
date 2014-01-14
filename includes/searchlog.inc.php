<?php

require_once '../config.inc.php';
require_once '../functions.inc.php';

$conid = db_connect(); 

/************************************************
	Search Functionality
************************************************/

// Define Output HTML Formating
$html .= '<li class="result-log">';
$html .= '<input type="hidden" name="logid" value="logID" /><input type="hidden" name="logname" value="logName" />';
$html .= '<a href="#" class="list-group-item" onclick="document.forms[\'log\'].submit();">logName</a>';
$html .= '</li>';

// Get Search
$search_string = preg_replace("/[^A-Za-z0-9]/", " ", $_POST['query']);
$search_string = $conid->real_escape_string($search_string);

// declare vars
$output = '';

// Check Length More Than One Character
if (strlen($search_string) >= 1 && $search_string !== ' ') {
	// Build Query
	$query = 'SELECT * FROM '.TBL_PREFIX.'logs WHERE logName LIKE "%'.$search_string.'%"';

	// Do Search
	$result = $conid->query($query);
	while($results = $result->fetch_array()) {
		$result_array[] = $results;
	}

	// Check If We Have Results
	if (isset($result_array)) {
		foreach ($result_array as $result) {

			// Insert Name
			$output = str_replace('logName', $result['logName'], $html);

			// Insert ID
			$output = str_replace('logID', $result['logID'], $output);

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
