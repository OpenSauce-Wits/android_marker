<?php
use \PHPUnit\Framework\TestCase ;
require_once( "lib.php") ;
reuire_once( "feedback_lib.php") ;

class markerTest extends TestCase
{
	public function testSetFeedbackProvider() : \feedback_provider
	{
		$fbp = new feedback_provider() ;
		$this->assertTrue( isset( $fbp)) ;
		return $fbp ;
	}

	/**
	 * @depends testSetFeedbackProvider
	 */
	public function testSetMarker( \feedback_provider $fbp) : \marker
	{
		$m = new marker( $fbp) ;
		$this->assertTrue( isset( $m)) ;
		return $m ;
	}

	/**
	 * @depends testSetMarker
	 */
	public function testBuildProject( \marker $m) : void
	{
		$source = "d173e7b0c2a8159b8716c4ea98de0c0a2087b722" ;
		$input_json = "2a675d5d879035aef2c25c2a8f9e4aca4748fd96" ;
		$testcase = "b146d9cb03dbca0ded43f6ab94a98994eb06b4fe" ;

		$this->assertTrue( $m->build_project( $testcase, $input_json, $source)) ;
	}
}
?>
