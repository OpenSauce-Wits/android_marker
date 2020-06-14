<?php
$data_string = '{"language":"1","source":"928615ca81c6572c3b69b8cccec57826bca6720e","input":"7b5739303fe8a4d235367ca627f4fac5ed235e70","output":"68a561aaaae3bd6e960c0fd77dbe4f6863bd5569","timelimit":"1"}' ;
$data_string = json_encode( json_decode( $data_string)) ;

$options = array(
	'http' => array(
		'method' => 'POST',
		'content' => $data_string,
		'header' => "Content-Type: application/json\r\n".
		"Accept: application/json\r\n"
	)
);

$context = stream_context_create( $options) ;
$result = file_get_contents( "http://192.168.137.75/marker_server/mark.php", false, $context) ;

echo $result ;
?>
