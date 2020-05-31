<?php

/**
 * @file mark.php
 * Web service interface.
 */
require_once("config.php"); // Include Global Configuration
require_once("lib.php");    // Include Library Functions

$inputJSON = file_get_contents('php://input');  // Get input from the client
$task = json_decode($inputJSON, TRUE);        // Decode the JSON object

//url of plugin that copies remote files to local marker
//FIXME might not work for really remote machines
//FIXME might nneed to zero index twice
log_( "Mark.php received input : ".$inputJSON) ;
log_( "Mark.php is currently testing the returning of results.") ;

$source = $task["source"] ;
//$source = json_decode( $task["source_hashes"][0][0]) ;
$source_structure = $task["input"] ;
$testcases = $task["output"] ;
$timelimit = $task["timelimit"] ;

//init feedback provider
$feedbackprovider = new feedback_provider() ;
//init marker
$marker = new marker( $feedbackprovider) ;

//download tests and source files
$copy_urls = array() ;
$copy_urls[] = array( $source => settings::$source."/".$source) ;
$copy_urls[] = array( $testcases => settings::$testcases."/".$testcases) ;
$copy_urls[] = array( $source_structure => settings::$source_structure."/".$source_structure) ;

$urls_json = json_encode( $copy_urls) ;

//TODO prepare or validate existence of marker_server tmp dir

$options = array(
	'http' => array(
		'method' => 'POST',
		'content' => $urls_json,
		'header' => 'Content-Type: application/json\r\n'.
		"Accept: application/json\r\n"
	)
);
$context = stream_context_create( $options) ;
$result = file_get_contents( settings::$getfiles_url, false, $context) ;
$result = json_decode( $result) ;

$project_files = array() ; ////<files used to build project { "name_of_file" : "<path>/<to>/<zip>/<file>"} 
/*
//TODO check value of $result and report to judge if file copying failed
foreach ( $result as $res)
{
	foreach( $res as $name => $contents)
	{
		if( $res == "error")
		{
			//reporting failure to moodle
			$result = array( "result" => ONLINEJUDGE_STATUS_INTERNAL_ERROR,
				"stderr" => $contents,
				"stdout" => "0"
			);
			break ;
		}
		else if( $contents == false)
		{
			$result = array( "result" => ONLINEJUDGE_STATUS_INTERNAL_ERROR,
				"stderr" => "File ".$name." failed to copy.",
				"stdout" => "0"
			);
			break ;
		}
		else if ($contents == true)
		{
			if( $name == $testcases)
			{
				$project_files[] = array( 'testcases' => settings::$testcases."/".$name);
			}
			else if ( $name == $source)
			{
				$project_files[] = array( 'source' => settings::$source."/".$source) ;
			}
			else if ( $name == $source_structure)
			{
				$project_files[] = array( 'source_structure' => settings::$source_structure."/".$name ) ;
			}
		}
	}
}
*/
/*
//build android project
//$project = build_project( $project_files) ;

//returning result
echo json_encode( $project) ;
$result = array(
        "result" => ONLINEJUDGE_STATUS_PENDING,
        "stdout" => "0",
        "stderr" => "0"
);

echo json_encode( $result) ;

sleep( 30) ;

$result = array(
        "result" => ONLINEJUDGE_STATUS_ACCEPTED,
        "stdout" => "0",
        "stderr" => "0"
) ;
//returning result
echo json_encode( $result) ;
 */
###PLAYING WITH MARKER FEEDBACK AND GRADING
###CASE 1 : LANGUAGE NOT SET
$outputs = array( "result" => ONLINEJUDGE_STATUS_INTERNAL_ERROR , "oj_feedback" => "MOCK RESULT : Marker error: Invalid Language") ;
$return = array( "status" => ONLINEJUDGE_STATUS_INTERNAL_ERROR, "grade" => -1.0, "outputs" => array( $outputs)) ;
echo json_encode( $return) ;
?>
