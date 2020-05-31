<?php

/*Small script for fetching requested files from local machine to requester's
 * Receives request in the form array({ "filecontenthash" : "destination_path_on_requesting_machine"})
 * Will then resolve file directory from "filecontenthash" as well as moodle directory structure
 * and copy the file to the specified 'remote' directory
 * FIXME Currently being run on same machine, may not work for remote machines
 */

require_once("marker_server/lib.php") ;

$data = file_get_contents( 'php://input');
$files = json_decode( $data, true) ;
$filesdir = '/var/moodledata/filedir/' ;	///< moodle keeps files we'll need for marking here

$all_failed = false ;

$response = array() ;

try
{
	foreach ( $files as $file)
	{
		foreach ( $file as $sha1sum => $file_dest)
		{
			//prepare full file path name
			//e.g if contenthash is 's232fwu52rw542gw25652'
			//then the directory to the file is <filesdir>/s2/32/s232fwu52rw542gw25652
			$file_source = $filesdir."".substr( $sha1sum, 0, 2)."/".substr( $sha1sum, 2, 2)."/".$sha1sum ;
		
			//check file on disk
			if( file_exists( $file_source))
			{
				$success = false ;
				//send it back to requester
				try
				{
					if ( !create_dir( $file_dest))
					{
						$success = recurse_copy( $file_source, $file_dest) ;
						if( !$success)
						{
							log_( "Copying ".$file_source." to ".$file_dest." inside getfiles.php failed.") ;
						}
					}
					else
					{
						$success = true ;
					}

				}
				catch( \Exception $e)
				{
					//foreach file, returns true if copied else false
					error_log( "############## MARKER_LOG[get_files.php] #################") ;
					error_log( "Some exception caught whilst running copy()") ;
					$success = false ;
				}
				$response[] = array( $sha1sum => $success) ;

			}
		}
	}
}
catch( \Exception $e)
{
	error_log( "############## MARKER_LOG[get_files.php] #################") ;
	error_log( "Some exception caught whilst trying to copy files.") ;
	$all_failed = true ;
}

if ( $all_failed)
{
	$response[] = array( "error" => "file copying failed") ;
}
	echo json_encode( $response) ;
?>
