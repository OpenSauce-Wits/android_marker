<?php
use \PHPUnit\Framework\TestCase ;
require_once( "feedback_lib.php") ;
require_once( "lib.php") ;

class gradleHandlerTest extends TestCase
{
	public function testSetFeedbackProvider() : \feedback_provider
	{
		$fbp = new feedback_provider() ;
		$this->assertTrue( isset( $fbp)) ;
		return $fbp ;
	}

	/** @depends testSetFeedbackProvider
	 */
	public function testSetGradleHandler( \feedback_provider $fbp) : \gradle_handler
	{
		$gh = new gradle_handler( $fbp) ;
		$this->assertTrue( isset( $gh)) ;
		return $gh ;
	}

	/** @depends testSetGradleHandler
	 */
	public function testGetTaskResultNoLogFileFailure( \gradle_handler $gh) : void
	{
		$this->assertFalse( $gh->get_task_results()) ;
	}

	/** @depends testSetGradleHandler
	 */
	public function testGetTaskResultPassOnLogfileFound( \gradle_handler $gh) : void
	{
		$file = $gh->marker_logs."/GRADLE.log";
		$res = fopen( $file, 'wd') ;
		fclose( $res) ;
		$this->assertTrue( $gh->get_task_results()) ;
		rm( $file) ;
	}

	/** @depends testSetGradleHandler
	 */
	public function testTaskPassedWhenLogFileHasNothing( \gradle_handler $gh) : void
	{
		$this->assertTrue( $gh->gradle_task_passed()) ;
	}

	/** @depends testSetGradleHandler
	 */
	public function testTaskFailedWhenFailureFoundInLogFile( \gradle_handler $gh) : void
	{
		$file = $gh->marker_logs."/GRADLE.log" ;
		fopen( $file, 'wd') ;
		$this->assertFileExists( $file) ;

		file_put_contents( $file, "Somehting womgiitn FAILURE somthing else.") ;
		$this->assertTrue( $gh->get_task_results()) ;
		$this->assertFalse( $gh->gradle_task_passed()) ;
		rm( $file) ;
	}
}

?>
