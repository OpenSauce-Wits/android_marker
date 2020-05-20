<?php

/*Small script for fetching requested files from local machine to requester's
 * Receives request in the form array({ "filecontenthash" : "destination_path_on_requesting_machine"})
 * Will then resolve file directory from "filecontenthash" as well as moodle directory structure
 * and copy the file to the specified 'remote' directory
 * FIXME Currently being run on same machine, may not work for remote machines
 */

$data = file_get_contents( 'php://input');
$files = json_decode( $data, true) ;

$filesdir = '/var/moodledata/filedir/' ;	///< moodle keeps files we'll need for marking here

$response = array() ;
foreach ( $files as $sha1sum => $file_dest)
{
	//prepare full file path name
	//e.g if contenthash is 's232fwu52rw542gw25652'
	//then the directory to the file is <filesdir>/s2/32/s232fwu52rw542gw25652
	$file_source = $filesdir."".substr( $sha1sum, 0, 2)."/".substr( $sha1sum, 2, 2)."/".$sha1sum ;

	//check file on disk
	if( file_exists( $file_source))
	{
		//send it back to requester
		$success = copy( $file_source, $file_dest) ;
		//foreach file, returns true if copied else false
		$response[] = array( $sha1sum => $success) ;
	}
}
	echo json_encode( $response) ;
?>
