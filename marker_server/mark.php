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

//getting contenthashes of source files, testcases and source_structure files
$source = $task["source"] ;
$source_structure = $task["input"] ;
$testcases = $task["output"] ;
$timelimit = $task["timelimit"] ;

//init feedback provider 
//TODO init fb provder with url to report judge status to moodle
$feedbackprovider = new feedback_provider() ;
//init marker
$marker = new marker( $feedbackprovider) ;

//TODO marker object should be null if construction failed
//TODO feedback provider object should be null if construction failed

$testcases_dest = settings::$testcases ;
$source_dest = settings::$source ;
$source_structure_dest = settings::$source_structure ;

$files = array( "testcases" => array( $testcases, $testcases_dest),
	"source" => array( $source, $source_dest),
	"source_structure" => array( $source_structure, $source_structure_dest) 
);

//fetch files from client
$files_fetched = $marker->get_client_files( $files) ;

//build project
if( $files_fetched)
{
	//build project
	if( $marker->build_project( $testcases, $source_structure, $source))
	{
		//TODO start marking android project
		//initialize gradle handler
		$gradle_handler = new gradle_handler( $feedbackprovider) ;
		if( $gradle_handler->run_gradle_task( "clean"))
		{
			//run unit tests
			if( $gradle_handler->run_gradle_task( "testDebugUnitTest"))
			{
				log_( "DOME// report tets results to moodle") ;
			}
			else
			{
				log_("Failed to run tests") ;
			}
		}
		else
		{
			log_( "Something failed in the gradle handles.") ;
		}
	}
	else
	{
		//TODO report to moodle
		log_( "DOME// report failure to build project to moodle.") ;
	}
}
else
{
//TODO report to moodle
	log_( "FIXME// Report judge status to moodle.") ;
}

//returning result
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
###PLAYING WITH MARKER FEEDBACK AND GRADING
###CASE 1 : LANGUAGE NOT SET
$outputs = array( "result" => ONLINEJUDGE_STATUS_INTERNAL_ERROR , "oj_feedback" => "MOCK RESULT : Marker error: Invalid Language") ;
$return = array( "status" => ONLINEJUDGE_STATUS_INTERNAL_ERROR, "grade" => -1.0, "outputs" => array( $outputs)) ;
echo json_encode( $return) ;
?>
