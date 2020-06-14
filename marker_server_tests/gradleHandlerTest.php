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
		$res = fopen( $gh->marker_logs."/GRADLE.log", 'wd') ;
		fclose( $res) ;
		$this->assertTrue( $gh->get_task_results()) ;
	}
}

?>
