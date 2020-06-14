<?php

/**
 * @file lib.php
 * General library routines.
 */
// Include global configurations
require_once("config.php");
require_once( "feedback_lib.php") ;

const output_max_length = 20000;
const result_correct = ONLINEJUDGE_STATUS_ACCEPTED;        		///< Correct Submission
const result_incorrect = ONLINEJUDGE_STATUS_WRONG_ANSWER;     		///< Incorrect Submission
const result_compile_error = ONLINEJUDGE_STATUS_COMPILATION_ERROR;     	///< Compile Error
const result_presentation_error = ONLINEJUDGE_STATUS_PRESENTATION_ERROR; ///< Presentation Error
const result_time_limit = ONLINEJUDGE_STATUS_TIME_LIMIT_EXCEED;       	///< Exceeded Time Limit
const result_marker_error = ONLINEJUDGE_STATUS_INTERNAL_ERROR;	    	///< Marker Error
const result_mixed = ONLINEJUDGE_STATUS_MULTI_STATUS;	    		///< Submission has been graded

class marker
{
	public $feedbackprovider = null ;
	public $rootdir = null;
	public $getfiles_url = null ; ///< client files host
	public $project_builder = null ;

	function __construct( $feedbackprovider)
	{
		$this->rootdir = settings::$temp ;
		$this->getfiles_url = settings::$getfiles_url ;
		if( !isset( $feedbackprovider))
		{
			log_( "Feedback provider not available.") ;
			die( "Feedback provider not available.") ;
		}

		$this->feedbackprovider = $feedbackprovider ;

		if( !create_dir( $this->rootdir))
		{
			$feedbackprovider->log_error( "MARKER ERROR", "Failed to create directory ".$this->rootdir) ;
			log_( "Failed to create essential directories.") ;
			die( "Failed to create essential directories.") ;
		}
	}

	/* @function get_client_files - downloads the specified files from the client side
	 * @param $files - associative array of keys $filename => array( "filename" , [$source , $dest])
	 * @return false if we fail to get any of the files else true
	 */
	public function get_client_files( $files)
	{
		try
		{
			$data = json_encode( $files) ;
			$options = array(
        			'http' => array(
                		'method' => 'POST',
                		'content' => $data,
                		'header' => 'Content-Type: application/json\r\n'.
                		"Accept: application/json\r\n")
			);

			$context = stream_context_create( $options) ;
			$result = file_get_contents( $this->getfiles_url, false, $context) ;
			//result is returned in the form 'filename' => TRUE - success else FALSE
			$result = json_decode( $result) ;

			if( !isset( $result))
			{
				$feedbackprovider->log_error( "MARKER ERROR", "Could not get response from ".$this->getfiles_url) ;
				return false ;
			}
			else if( array_key_exists( 'error', $result))
			{
				$feedbackprovider->log_error("MARKER ERROR", "Fatal error encountered at ". $this->getfiles_url." Couldn't download files.") ;
				return false ;
			}
			
			//check if any of the files failed to download
			foreach ( $result as $filename => $success)
			{
				if( !$success)
				{
					$feedbackprovider->log_error( "MARKER ERROR", "File ".$filename." download failed.") ;
					return false ;
				}
			}

		}
		catch( \Exception $e)
		{
			$feedbackprovider->log_error( "MARKER ERROR", "Exception encoutered whilst downloading files from client.") ;
			return false ;
		}
		return true ;
	}

	public function get_root_dir()
	{
		return $this->rootdir ;
	}

	/*@function build_project - builds a complete android project
	 * @param $tests - name of the zipfile containing lecture's test code
	 * @param $structurefile - name of json file containing project structure file
	 * @param $source - name of zip file containing student's source code
	 * @return true for successful project build else false
	 */
	public function build_project( $tests, $structurefile, $source)
	{
		//TODO check whether project build was fully initialised
		$this->project_builder = new project_builder( $tests, $structurefile, $source, $this->feedbackprovider) ;

		if( !$this->project_builder->all_dirs_available())
		{
			return false ;
		}

		//unzip tests to final project dir
		if( !$this->project_builder->unzip_tests_to_project_dir())
		{
			return false ;
		}
		//unzip source files to temp directory
		else if( !$this->project_builder->unzip_source_files())
		{
			return false ;
		}
		//move source files into final project directory
		else if( !$this->project_builder->move_source_files_to_project_dir())
		{
			return false ;
		}

		return true ;
		
	}
}

class gradle_handler
{
	private $marker_tools = null ;
	private $marker_data = null ;
	private $marker_logs = null ;
	private $feedbackprovider = null ;
	private $gradle_logs = null ;

	public function __construct( $feedbackprovider)
	{
		$this->marker_tools = settings::$marker_tools ;
		$this->marker_data = settings::$marker_data ;
		$this->marker_logs = settings::$marker_logs ;

		if( $feedbackprovider == null)
		{
			log_( "Feedback provider not available.") ;
			die( "Feedback provider not available.") ;
		}
		$this->feedbackprovider = $feedbackprovider ;

		if( !file_exists( $this->marker_tools))
		{
			$this->feedbackprovider->log_error( "GRADLE ERROR", "Marker tools directory $this->marker_tools not found.") ;
			die( "Marker tools directory $this->marker_tools not found.") ;
		}

		if( !file_exists( $this->marker_data))
		{
			$this->feedbackprovider->log_error( "GRADLE ERROR", "Marker data directory $this->marker_data not found.") ;
			die( "Marker data directory $this->marker_data not found.") ;
		}
		else if( !create_dir( $this->marker_logs))
		{
			$this->feedbackprovider->log_error( "GRADLE ERROR", "Marker logs directory $this->marker_logs could not be created.") ;
			die( "Marker logs directory $this->marker_logs could not be created.") ;
		}
	}

	/*@function get_task_results fetches the results of a gradle task from a log file
	 * @return true if mamanged to fetch results, else false
	 */
	public function get_task_results()
	{
		//check logfile first
		if( !file_exists( $this->marker_logs."/GRADLE.log"))
		{
			return false ;
		}

		try
		{
			$this->gradle_logs = file_get_contents( $this->marker_logs."/GRADLE.log") ;
		}
		catch( \Exception $e)
		{
			$this->feedbackprovider->log_error( "GRADLE ERROR", "Caught an exception whilst trying to read in contents of gradle log file.") ;
			return false ;
		}

		return true ;
	}

	/* @function run_gradle_task runs the specified gradle task
	 * @param $task a string specifying the gradle task to be run
	 * @return true or false depending on whether the task failed or passed
	 */
	public function run_gradle_task( $task)
	{
		//make sure a task has been specified
		if( strlen( $task) == 0)
		{
			$this->feedbackprovider->log_error( "GRADLE ERROR", "Gradle task specified is empty.") ;
			return false ;
		}

		shell_exec( "cd ".settings::$marker_tools." && echo $task | ./run_gradle_task.sh") ;
	//FIXME might need to wait for the gradle task to finish execution	
		if( $this->get_task_results())
		{
			if( !$this->gradle_task_passed())
			{
				$this->feedbackprovider->log_error( "GRADLE ERROR", "Task $task failed.") ;
				return false ;
			}
		}

		return true ;
	}

	/* @function gradle_task_passed checks whether the log files reported a failure
	 * @return false if task failed else true
	 */
	public function gradle_task_passed()
	{
		$pos = strpos( $this->gradle_logs, "FAILURE") ;

		if( $pos !== false )
		{
			$this->feedbackprovider->log_error( "GRADLE ERROR", $this->gradle_logs) ;
			return false ;
		}
		
		return true ;
	}
}

class avd_manager
{
	private $feedbackprovider = null ;
	private $avd_logs = null ;
	private $marker_tools = null ;
	private $marker_data = null ;
	private $marker_logs = null ;
	private $avds = null ; //< an associative arrays storing avd names and an array storing booleans on whether the avd is online and whether it is in use

	function __construct( $feedbackprovider)
	{
		$this->marker_tools = settings::$marker_tools ;
		$this->marker_data = settings::$marker_data ;
		$this->marker_logs = settings::$marker_logs ;
		$this->avd_logs = settings::$marker_logs."/AVD.log" ;

		if( $feedbackprovider == null)
		{
			log_( "Feedback provider not available.") ;
			die( "Feedback provider not available.") ;
		}
		$this->feedbackprovider = $feedbackprovider ;
		
		if( !file_exists( $this->marker_tools))
		{
			$this->feedbackprovider->log_error( "AVD ERROR", "Marker tools directory $this->marker_tools not found.") ;
			die( "Marker tools directory $this->marker_tools not found.") ;
		}

		if( !file_exists( $this->marker_data))
		{
			$this->feedbackprovider->log_error( "AVD ERROR", "Marker data directory $this->marker_data not found.") ;
			die( "Marker data directory $this->marker_data not found.") ;
		}
		else if( !create_dir( $this->marker_logs))
		{
			$this->feedbackprovider->log_error( "AVD ERROR", "Marker logs directory $this->marker_logs could not be created.") ;
			die( "Marker logs directory $this->marker_logs could not be created.") ;
		}
	}

	/* @function create_avd creates an avd and adds it by name to the list of available avds
	 * @param $avdname
	 * @return true when on no error else false
	 */
	public function create_avd( $avdname = null)
	{
		if( !isset( $avdname))
		{
			$avdname = rand() ;
			$avdname = md5( $avdname) ;
		}

		shell_exec( "cd $this->marker_tools && echo $avdname | ./create_avd.sh") ;

		if( $this->load_avds())
		{
			return avd_available( $avdname) ;
		}
		return false ;
	}

	public function avd_online()
	{
			//TODO check if any avds are online
			shell_exec( "cd $this->marker_tools && ./check_online_avd.sh") ;

			$logs = file_get_contents( $this->avd_logs) ;
			if( strpos( $logs, "device") === false)
			{
				$this->feedbackprovider->log_error( "AVD ERROR", "No avd is online.") ;
				log_( "AVD CHECK::: ".$logs) ;
				return false ;
			}
			return true ;
	}

	/* @function avd_avalaible checks if the specified avd has been created
	 * @param $avdname
	 * @return boolean
	 */
	public function avd_available( $avdname)
	{
		shell_exec( "cd $this->marker_tools && ./list_avds.sh") ;
		$data = file_get_contents( $this->avd_logs) ;
		if( strpos( $this->avd_logs, $avdname))
		{
			$this->feedbackprovider->log_error( "AVD ERROR", "AVD $avdname not created.") ;
			return false ;
		}
		return true ;
	}

	/* @function start_avd powers on the specified avd
	 * @param $avdname
	 * @return boolean
	 */
	public function start_avd( $avdname)
	{
		if( $this->avd_available( $avdname))
		{
			//TODO really start it from here
			//for now just return true, we'll start it from terminal
			return true ;
		}

		return false ;
	}

}

class project_builder
{
	/*class that takes in directories for files it needs and builds 
	 * a project in some directory
	 */

	public $feedbackprovider = null;
	private $serverrootdir = null ;
	private $dirtotests = null;
	private $dirtostructurefile = null;
	private $dirtosource = null ;
	private $temp_dir = null ;
	private $finalprojectdir = null ;

	private $alldirsavailable = TRUE ;
	/*Constructor
	 * @param $tests - string specifying the name of tests zip file in the server
	 * @param $structurefile - string specifying the name of lecture's json file( in the server)
	 * which specifies the required structure of the student's submission
	 * @param $source - string specifying the name of the student's source zip file
	 * @param $feedbackprovider
	 */
	function __construct( $tests, $structurefile, $source, $feedbackprovider)
	{
		$this->serverrootdir = settings::$temp ;
		$this->temp_dir = settings::$temp."/temp" ;
		$this->dirtotests = settings::$testcases."/".$tests ;
		$this->dirtostructurefile = settings::$source_structure."/".$structurefile ;
		$this->dirtosource = settings::$source."/".$source ;
		$this->finalprojectdir = settings::$project_dir ;
		if( $feedbackprovider == null)
		{
			log_( "Feedback provider not available.") ;
			die( "Feedback provider not available.") ;
		}
		$this->feedbackprovider = $feedbackprovider ;

		if( !isset( $this->finalprojectdir))
		{
			$this->feedbackprovider->log_error( "BUILD ERROR", "Final project folder not specified.") ;
			log_( "No project folder specified.") ;
			die( "No project folder specified.") ;
		}
		else if( !create_dir( $this->finalprojectdir, true))
		{
			$this->feedbackprovider->log_error( "BUILD ERROR", "Failed to created final project directory ".$this->finalprojectdir) ;
			$this->alldirsavailable = FALSE ;
		}

		if( !isset( $this->dirtotests))
		{
			$this->feedbackprovider->log_error( "BUILD ERROR", "Directory to the test files not specified.") ;
			$this->alldirsavailable = FALSE ;
		}
		else if( !file_exists( $this->dirtotests))
		{
			$this->feedbackprovider->log_error( "BUILD ERROR", "Test files ".$this->dirtotests." not found.") ;
			$this->alldirsavailable = FALSE ;
		}

		if( !isset( $this->dirtostructurefile))
		{
			$this->feedbackprovider->log_error( "BUILD ERROR", "Directory to the structure file not specified.") ;
			$this->alldirsavailable = FALSE ;
		}
		else if( !file_exists( $this->dirtostructurefile))
		{
			$this->feedbackprovider->log_error( "BUILD ERROR", "Structure file ".$this->dirtostructurefile." not found.") ;
			$this->alldirsavailable = FALSE ;
		}

		if( !isset( $this->dirtosource))
		{
			$this->feedbackprovider->log_error( "BUILD ERROR", "Directory to the source files not specified.") ;
			$this->alldirsavailable = FALSE ;
		}
		else if( !file_exists( $this->dirtosource))
		{
			$this->feedbackprovider->log_error( "BUILD ERROR", "Source files ".$this->dirtosource." not found.") ;
			$this->alldirsavailable = FALSE ;
		}

		//create temp dir
		if( !create_dir( $this->temp_dir, true))
		{
			$this->feedbackprovider->log_error( "BUILD ERROR", "Failed to create temporary directoroy ".$this->temp_dir) ;
			$this->alldirsavailable = FALSE ;
			log_( "Failed to create temporary directory ".$this->temp_dir) ;
			die( "Failed to create temporary directory ".$this->temp_dir) ;
		}
	}
		
	public function all_dirs_available()
	{
		return $this->alldirsavailable ;
	}

	public function unzip_source_files()
	{
		return $this->unzip_to( $this->dirtosource, $this->temp_dir) ;
	}

	/* @function move_source_files_to_project - moves the source files to the project directory
	 * by following the stucture specified in the structure file
	 */
	public function move_source_files_to_project_dir()
	{
		$success = TRUE ;
		if( $this->unzip_source_files())
		{
			$json_file_data = "" ;
			$success = $this->read_structure_file_to( $json_file_data) ;
			if( $success)
			{
				foreach( $json_file_data as $file)
				{
					foreach( $file as $filename => $dirs)
					{
						$source_dir = $dirs[0] ;
						$dest_dir = $dirs[1] ;

						if( !$this->move_file( $filename, ( strlen( $source_dir) > 0 ? $this->temp_dir."/".$source_dir : $this->temp_dir), $this->get_project_dir()."/".$dest_dir))
						{
							$success = FALSE ;
						}
					}
				}
			}
		}
		return $success ;
	}

	/* @function read_structure_file_to - reads in the contents of the $dirtostructure
	 * @param $dest - the variable into which we'll read in the contents of the structure file
	 * @return done
	 */
	public function read_structure_file_to( & $dest)
	{
		$dest = json_decode( file_get_contents( $this->dirtostructurefile)) ;
		if( isset( $dest))
		{
			return true ;
		}

		$this->feedbackprovider->log_error( "BUILD ERROR", "Failed to read in project structure.") ;
		return false ;
	}

	public function move_file( $filename, $source_dir, $dest_dir)
	{
		if( create_dir( $dest_dir))
		{
			if( !file_exists( $source_dir))
			{
				$this->feedbackprovider->log_error( "BUILD ERROR", "Failed to move file ".$filename." as source directory ".$source_dir." does not exist.") ;
				return false ;
			}
			else if(!file_exists( $source_dir."/".$filename))
			{
				$this->feedbackprovider->log_error("BUILD ERROR", "Failed to move file ".$filename." as it does not exist inside ".$source_dir) ;
				return false ;
			}
			else if( !copy_r( $source_dir."/".$filename, $dest_dir."/".$filename))
				//copying the files for now
				//FIXME find a way to move the files
			//else if( !move_uploaded_file( $source_dir."/".$filename, $dest_dir."/".$filename))
			{
				$this->feedbackprovider->log_error( "BUILD ERROR", "Failed to move uploaded file ".$filename." from ".$source_dir." to ".$dest_dir) ;
				return false ;
			}
		}
		else
		{
			$this->feedbackprovider->log_error("BUILD ERROR", "Couldn't move files. Failed to create directory ".$dest_dir) ;
			return false ;
		}
		return true ;
	}

	public function unzip_to( $zipfile, $dest)
	{
		$zip = new ZipArchive() ;

		if( !( file_exists( $zipfile) && is_file( $zipfile)))
		{
			$this->feedbackprovider->log_error( "BUILDER ERROR", "Zip file ".$zipfile. " does not exist.") ;
			return false ;
		}

		if( $zip->open( $zipfile))
		{
			if( !$zip->extractTo( $dest))
			{
				$this->feedbackprovider->log_error( "BUILD ERROR", "Failed to uzip tests ".$zipfile." to directory ".$dest) ;
				$zip-close() ;
				return false ;
			}
			$zip->close() ;
		}else
		{
			$this->feedbackprovider->log_error( "BUILD ERROR", "Failed to unzip tests to project. Couldn't open zip file ".$zipfile) ;
			return false ;
		}

		return true ;
	}

	public function unzip_tests_to_project_dir()
	{
		return $this->unzip_to( $this->dirtotests, $this->get_project_dir()) ;
	}

	//returns the absolute path to final project directory
	public function get_project_dir()
	{
		return $this->finalprojectdir ;
	}
}

/* @function rm - a rapper to delete tree that checks whether a file or directory exists  and that the argument is not an empty string before deleting it
 * @param $dir_or_file - a string specifying path to file or directory
 */
function rm( $dir_or_file)
{
	//strip string of empty chars
	if ( isset( $dir_or_file))
	{
		$dir_or_file = str_replace( ' ', '', $dir_or_file) ;
		if( strlen( $dir_or_file) > 0)
		{
			if( file_exists( $dir_or_file))
			{
				return delTree( $dir_or_file) ;
			}
		}
		else
		{
			error_log("Error! Attempting to delete unspecified directory.") ;
		}
	}
	return true ;
}

/* @function create_dir
 * @param $pathtodir - string specifying the path of the file directory to be created
 * @param $override - if true will remove older directory and its contents
 * @return true if mamanged to create the directory else false
 */
function create_dir( $pathtodir, $override=false)
{
	if( $override && rm( $pathtodir))
	{
                //recreate the directory
                return create_dir( $pathtodir, !$override) ;
        }
        else
	{
		if( !file_exists( $pathtodir))
		{
                	return mkdir( $pathtodir, 0777, true) ;
		}
		return true ;
        }
}

/* @source https://www.php.net/manual/en/function.rmdir.php
 * @author  nbari at dalmp dot com 
 * @function delTree
 * @param $dir - directory to be deleted recursively
 */
function delTree($dir)
{
        $files = array_diff(scandir($dir), array('.','..'));
        foreach ($files as $file)
        {
                (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
        }
	return rmdir($dir);

}
/*
function copy_r( $source, $dest, $source_dir=null)
{
	if( !is_dir( $source) && !isset( $source_dir))/
	{
		return create_dir( $dest) && copy( $source, "$dest/$source") ;
	}
	else if( isset( $source_dir))
	{
		if( create_dir( "$dest/$source_dir"))
		{
			//recursively copy all files inside source
			$files = array_diff( scandir( $source), array( '.', '..')) ;
			foreach( $files as $file)
			{
				if( !copy_r( "$source/$file",  "$dest/$source"))
				{
					return false ;
				}
			}
			return true ;
		}
		else
		{
			return false ;
		}

	}
	if( is_dir( $source) && isset( $source_dir))
	{
		//create a corresponding folder inside $dest
		if( create_dir( $dest))
		{
			//recursively copy all files inside source
			$files = array_diff( scandir( $source), array( '.', '..')) ;
			foreach( $files as $file)
			{
				if( !copy_r( "$source/$file",  "$dest/$source"))
				{
					return false ;
				}
			}
			return true ;
		}
		else
		{
			return false ;
		}
	}else
	{
		//remember that copy uses named destination to copy files
		return create_dir( $dest) && copy( $source, "$dest/$source") ;
	}
	/*
	$files = array_diff( scandir( $source), array( '.', '..')) ;
	$success = true ;
	foreach ( $files as $file)
	{
		if( is_dir( "$source/$file")
		$success = ( is_dir( "$source/$file")) ? copy_r( "$source/$file", "$dest/$file") : create_dir( $dest) && copy( "$source/$file", "$dest/$file") ;
	}
	return $success ;
	*/
//}
?>
