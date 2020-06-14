<?php
require_once( "feedback_lib.php") ;
use \PHPUnit\Framework\TestCase ;

class feedbackProviderTest extends TestCase
{
	public static $fbp = null ;
	public static $itr = null ;
	public static $utr = null ;

	public static function setUpBeforeClass() : void
	{
		self::$fbp = new feedback_provider() ;
		self::$itr = new instrumented_tests_results( [ "tests/IT.html"]) ;
		self::$utr = new unit_tests_results( [ "tests/UT.html"]) ;
	}

	public function testStubFuncSendFeedbackToMoodle() : void
	{
		$this->assertTrue( self::$fbp->send_feedback_to_moodle()) ;
	}

	public function testSetCountsForUnitTests() : void
	{
		$this->assertTrue( self::$utr->set_counts()) ;
	}
	public function testSetCountsForInstrumentedTests() : void
	{
		$this->assertTrue( self::$itr->set_counts()) ;
	}

	public static function tearDownAfterClass() : void
	{
		self::$fbp = null ;
		self::$itr = null ;
		self::$utr = null ;
	}
}


?>
