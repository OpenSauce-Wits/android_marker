<?php
require_once( "lib.php") ;
require_once( "config.php") ;
require_once( "feedback_lib.php") ;

$feedbackprovider = new feedback_provider() ;
$feedbackprovider->set_test_results() ;



?>
