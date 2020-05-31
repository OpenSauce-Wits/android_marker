<?php
$data_string = '{"language":"1","source":"b474b1d83fcd3f760d1497a30ffb6a4252ddf0d9","input":"57dd7cf59e04300eb1edcb9e2e3026169af3af87","output":"b66b45e80a34c62886588084b62d2fbf34c31faf","timelimit":"1"}' ;
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
