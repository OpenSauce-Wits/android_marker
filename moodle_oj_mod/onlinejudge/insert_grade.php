<?php
require_once( __DIR__.'/oj_gradelib.php') ;
//DESCRIPTION : this script will serve as a callback for the marker. This wil allow the marker to conveniently insert grades at a later stage of the marking as well as the feedback 
//RESIDENCE : anywhere as long as it can access oj_gradelib.php

//call input from marker caller
$input = file_get_contents( 'php://input') ;
$args = json_decode( $input, true) ;

error_log( "CALL TO INSERT GRADE SCRIPT : " . json_encode( $args)) ;

//do the grade insertion
insert_grade( $args) ;

echo "Inserting grade." ;

?>
