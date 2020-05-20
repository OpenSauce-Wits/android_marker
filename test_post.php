<?php
$data = array( "59df21a17f3e3ee286d9eec590198bd4d10a70cf" => "/tmp/trash/59df21a17f3e3ee286d9eec590198bd4d10a70cf",
"9bc815f099ddc65bc4e686a43145c689b56062bd" => "/tmp/trash/9bc815f099ddc65bc4e686a43145c689b56062bd") ;

$data_string = json_encode( $data) ;

$options = array(
	'http' => array(
		'method' => 'POST',
		'content' => $data_string,
		'header' => "Content-Type: application/json\r\n".
		"Accept: application/json\r\n"
	)
);

$context = stream_context_create( $options) ;
$result = file_get_contents( "http://192.168.17.128/get_files.php", false, $context) ;

print_r( $result) ;
?>
