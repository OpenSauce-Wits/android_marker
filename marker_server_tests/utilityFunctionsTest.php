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

	public function testCanCreateADirectory(): void
	{
		create_dir( self::$folder) ;
		$this->assertDirectoryExists( self::$folder) ;
	}

	/** @depends testCanCreateADirectory
	 */
	public function testCanRemoveADirectory(): void
	{
		rm( self::$folder) ;
		$this->assertFalse( is_dir( self::$folder)) ;
	}

	/** @depends testCanCreateADirectory
	 * @depends testCanRemoveADirectory
	 * @depends testCanRemoveAFile
	 */
	public function testCreateDirectoryAndOverrideExistingOne() : void
	{
		$outer_dir = "path/to" ;
		$inner_dir = "path/to/inner" ;

		create_dir( $inner_dir) ;
		$this->assertDirectoryExists( $inner_dir) ;

		//we override the previous directory $folder
		create_dir( $outer_dir, true) ;
		$this->assertDirectoryExists( $outer_dir) ;

		//check the override passed by ensuring inner folder does not exist
		$this->assertFalse( is_dir( $inner_dir)) ;

		rm( $outer_dir) ;
	}

	public function testCanRemoveAFile() : void
	{
		$file = "myfile.f" ;
		$resource = fopen( $file, 'wd') ;
		fclose( $resource) ;

		//check file exists
		$this->assertTrue( file_exists( $file)) ;

		//remove file
		rm( $file) ;
		//check file gone
		$this->assertFalse( file_exists( $file)) ;
	}

	/** @depends testCanCreateADirectory
	 * @depends testCanRemoveADirectory
	 * @depends testCanRemoveAFile
	 */
	public function testSuccessfullyCopiesFileIntoAFolder() : void
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

	public static function tearDownAfterClass() : void
	{
		self::$folder = null ;
	}
}
?>
