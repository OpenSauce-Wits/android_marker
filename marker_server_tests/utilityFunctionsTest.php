<?php
//Tests functions that aren't part of class
use \PHPUnit\Framework\TestCase ;
require_once( "lib.php") ;

class utilityFunctionsTest extends Testcase
{
	public static $folder = null ;
	public static function setUpBeforeClass() : void
	{
		self::$folder = "path/to/folder" ;
	}

	public function testCreateDir(): void
	{
		create_dir( self::$folder) ;
		$this->assertDirectoryExists( self::$folder) ;
	}

	/* @depends testCreateDir
	 */
	public function testCreateDirOverride() : void
	{
		$new_dir = "path/to" ;
		//we override the previous directory $folder
		create_dir( $new_dir, true) ;
		$this->assertDirectoryExists( $new_dir) ;

		//check the override passed by ensure inner /folder does not exist
		$this->assertFalse( is_dir( self::$folder)) ;
	}

	/* @depends testCreateDir
	 */
	public function testRemoveDir(): void
	{
		rm( self::$folder) ;
		$this->assertFalse( is_dir( self::$folder)) ;
	}

	public static function tearDownAfterClass() : void
	{
		self::$folder = null ;
	}
}
?>
