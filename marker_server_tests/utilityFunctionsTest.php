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
	public function testRemoveDir(): void
	{
		rm( self::$folder) ;
		$this->assertFalse( is_dir( self::$folder)) ;
	}

	/* @depends testCreateDir
	 * @depends testRemoveDir
	 */
	public function testCreateDirOverride() : void
	{
		$new_dir = "path/to" ;
		//we override the previous directory $folder
		create_dir( $new_dir, true) ;
		$this->assertDirectoryExists( $new_dir) ;

		//check the override passed by ensuring inner /folder does not exist
		$this->assertFalse( is_dir( self::$folder)) ;

		rm( $new_dir) ;
	}

	/* @/depends testCreateDir
	 * @/depends testRemoveDir
	 */
	/*
	public function testCopyRecursivelyAFile() : void
	{
		//create source file
		$filename = "myfile.txt" ;
		$dest = "dest" ;

		$fp = fopen( $filename, 'wb') ;
		fclose( $fp) ;

		//create destination copy directory
		create_dir( $dest) ;

		//copy files
		copy_r( $filename, $dest) ;

		//check if it copied the file
		$this->assertTrue( file_exists( "$dest/$filename")) ;

		rm( $filename) ;
		rm( $dest) ;

	}
	*/

	public static function tearDownAfterClass() : void
	{
		self::$folder = null ;
	}
}
?>
