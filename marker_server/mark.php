<?php

/**
 * @file mark.php
 * Web service interface.
 */
require_once("config.php"); // Include Global Configuration
require_once("lib.php");    // Include Library Functions
require_once( "feedback_lib.php") ;

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

//report that we've begun marking
$result = array(
        "result" => ONLINEJUDGE_STATUS_JUDGING,
        "stdout" => "Marking student submission.",
        "stderr" => "Judging submission."
);

echo json_encode( $result) ;

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
			$results_available = true ;
			//run unit tests
			if( $gradle_handler->run_gradle_task( "testDebugUnitTest"))
			{
				log_( "DOME// report test results to moodle") ;
			}
			else
			{
				$results_available = false ;
				log_( "DOME// Report failure to run tests.") ;
			}

			//run instrumented tests
			$avd_manager = new avd_manager( $feedbackprovider) ;
			//TODO avd_manager should be null if construction failed
			if( $avd_manager->avd_online())
			{
				//run the instrumented tests( Installs and runs the tests for debug on connected devices)
				if( $gradle_handler->run_gradle_task( "connectedDebugAndroidTest"))
				{
					log_("DOME// Report instrumented test results.") ;
					//uninstall android apk
					if( !$gradle_handler->run_gradle_task( "uninstallAll"))
					{
						$results_available = true ;
						log_( "DOME// report to admin that we couldn't unsintall apk.") ;
					}
				}
				else
				{
					log_("DOME// Report failure to run instrumentation tests.") ;
				}
			}
			else
			{
				log_("DOME// Report avd not available") ;
			}

			if( $results_available && $feedbackprovider->set_test_results())
			{
				log_( json_encode( $feedbackprovider->get_unit_tests())) ;
				log_( json_encode( $feedbackprovider->get_instrumented_tests())) ;
				log_( "Num UT".$feedbackprovider->unit_tests->num_tests) ;
				log_( "Num IT".$feedbackprovider->instrumented_tests->num_tests) ;
				log_( "DOME//Report final results and grade to moodle.") ;
			}
			else
			{
				$result = array(
				"result" => ONLINEJUDGE_STATUS_INTERNAL_ERROR,
				"stdout" => "Marking student submission.",
				"stderr" => "Judging submission."
				);
				echo json_encode( $result) ;
				log_("NOT all results were available.") ;
			}
		}
		else
		{
			log_( "DOME// report failure to clean") ;
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
?>
