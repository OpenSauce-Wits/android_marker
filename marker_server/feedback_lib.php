<?php
require_once( "config.php") ;

class feedback_provider
{
	public $unit_tests = null ;
	public $instrumented_tests = null ;
	/*Class for storing feedback to be provided to moodle when running android things
         */
        public $allfeedback = array(); //<an associative array containing all types of feedback to be provided
        //will format feedback to send it to moodle
        public function send_feedback_to_moodle()
        {
                return true ;
        }

        public function log_error( $error_type, $error_message)
        {
                //TODO store in some way
                log_( $error_type. "::::".$error_message) ;
        }

        /* @function set_test_results - gets the test results and stores them in their vaiables
	 */
        public function set_test_results()
	{
		//run script to find paths to results
		shell_exec( "cd ".settings::$marker_tools." && echo ".settings::$project_dir."| ./get_dirs_to_results.sh") ;
		if( file_exists( settings::$marker_logs."/FEEDBACK.log"))
		{
			$paths = file_get_contents( settings::$marker_logs."/FEEDBACK.log") ;
			//split string by emparands
			$dirs = explode( "&", $paths) ;
			$dirs[1] = substr($dirs[1], 0, -1) ;
			
			$success = true ;
			
			if( strlen( $dirs[0]) > 5 && file_exists( $dirs[0]))
			{
				//set results for unit tests
				$this->unit_tests = new unit_tests_results( $dirs[0]) ;
			}
			else
			{
				$success = false ;
				$this->log_error( "FEEDBACK ERROR", "No unit test results found.") ;
			}

			if( strlen( $dirs[1]) > 5 && file_exists( $dirs[1]))
			{
				//set results for instrumented tests
				$this->instrumented_tests = new instrumented_tests_results( $dirs[1]) ;
			}
			else
			{
				$this->log_error( "FEEDBACK ERROR", "No instrumenented test results found.") ;
				return $success ;
			}
			return true ;
		}
		else
		{
			$this->log_error("FEEDBACK ERROR", "File ".settings::$marker_logs."/FEEDBACK.log not found.") ;
			return false ;
		}
	}
}

class unit_tests_results extends test_results
{
	function __construct( $file)
	{
		parent::__construct( $file) ;
	}
	function set_counts( $flag=true)
	{
		parent::set_counts( true) ;
	}

	function get_results()
	{
		return parent::get_test_results( 1) ;
	}
}

class instrumented_tests_results extends test_results
{
	function __construct( $file)
	{
		parent::__construct( $file) ;
	}
	function set_counts($flag=false)
	{
		parent::set_counts( false) ;
	}

	function get_results()
	{
		$array = parent::get_test_results() ;
		foreach( $array as $key => $value)
		{
			//strip result of time
			$array[$key] = substr( $value, 0, strpos( $value,"(")-1) ;
		}
		return $array ;
	}
}
class test_results
{
	protected $html_data = null ;
	public $num_tests = null ;
	public $num_failures = null ;
	public $num_ignored = null ;

	function __construct( $path_to_html_file)
	{
		if( !file_exists( $path_to_html_file))
		{
			error_log( $path_to_html_file." not found.") ;
			die() ;
		}
		$this->html_data = file_get_contents( $path_to_html_file) ;
	}

	/* @function set_counts - sets number of tests and number of failures and num ignored if specified
	 * @param set_ignored - does so if true
	 * @return - returns success status of function
	 */
	protected function set_counts( $set_ignored=false)
	{
		$clean_table = $this->get_table( $this->get_table( $this->html_data, true), true) ;
		$DOM = new DOMDocument() ;
		@$DOM->loadHTML( $clean_table) ;

		$h = $DOM->getElementsByTagName( 'td') ;
		$this->num_tests = $this->get_num( $h[0]->textContent );
		$this->num_failures = $this->get_num( $h[1]->textContent) ;
		if( $set_ignored)
			$this->num_ignored = $this->get_num( $h[2]->textContent) ;
		return true ;
	}

	/*@ function get_test_results - returns all tests run and their associated result
	 * @param $offset - accounts for differences in the ordering of the unit tests table and instrumentation ones
	 * @return json_array of the form [{ "test_name" : "result"}, ...]
	 */
	protected function get_test_results($offset=0)
	{
		//get the inner-most html table
		$results_table = $this->get_table( $this->get_table( $this->get_table( $this->html_data, true), true)) ;
		//get all table rows as array
		$DOM = new DOMDocument() ;
		@$DOM->loadHTML( $results_table) ;
		$rows = $DOM->getElementsByTagName( 'td') ;

		$return_array = array() ;

		for( $i = 0; $i < sizeof( $rows); $i = $i + 2 + $offset)
		{
			$test_name = $rows[$i]->textContent ;
			$test_result = $rows[$i+1+$offset]->textContent ;

			$return_array[$test_name] = $test_result ;
		}

		return $return_array ;
	}
	
	/* @function get_table - takes in an html and returns an html of outtermost table
	 * @param $string_html
	 * @param $as_string - discards outter <table> </table> tags if true
	 * @return table html
	 */
	function get_table( $string_html, $as_string=false)
	{
		//TODO error checking
		$i = strpos( $string_html, "<table>") ;
		$j = strrpos( $string_html, "</table>") ;
		if( $as_string)
		{
			$i = $i + 7 ;
		}
		return substr( $string_html, $i, $i + $j+8);
	}

	function get_num( $string)
	{
		//TODO error checking
		//the string is of the form "# tests/failures/ignored..." and we need to return #
		return ( int) filter_var( $string, FILTER_SANITIZE_NUMBER_INT);
	}
}

function log_( $message) 
{ 
        error_log( "##################MARKER_LOG[lib.php]#####################") ; 
        error_log( $message) ; 
} 

?>

