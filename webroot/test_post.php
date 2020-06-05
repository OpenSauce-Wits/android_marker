<?php
$data_string = '{"language":"1","source":"e6905f7ab781d6417e2d7855bf90fc15ee4762a6","input":"8636af514ee1eaa402ab583ef1114af6d37d4cc5","output":"1fee844bf266d306372895020a4354f8ba3ee780","timelimit":"1"}' ;
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
$result = file_get_contents( "http://192.168.17.128/marker_server/mark.php", false, $context) ;

echo $result ;
?>
