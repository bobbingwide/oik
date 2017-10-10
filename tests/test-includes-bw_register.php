<?php // (C) Copyright Bobbing Wide 2017

/** 
 * Unit tests for the includes/bw_register.inc file
 */

class Tests_includes_bw_register extends BW_UnitTestCase {

	/** 
	 * set up logic
	 * 
	 * - ensure any database updates are rolled back
	 * - The oik plugin must be activated!
	 */
	function setUp() {
		parent::setUp();
		oik_require( "includes/bw_register.inc" );
	}
	
	/**
	 * Tests bw_singularize
	 * 
	 * Note: As you can see some of the singular values are WRONG! 
	 * If this is the case then the caller will need to provide the singular version.
	 */
	function test_bw_singularize() {
		$plutos = array( "Posts" => "Post"
		               , "Pages" => "Page"
									 , "Sheep" => "Sheep"
									 , "Dukes" => "Duke"
									 , "Duchesses" => "Duchesse"
									 , "Less" => "Le"
									 );
		foreach ( $plutos as $plural => $expected ) {
			$singular = bw_singularize( $plural );
			$this->assertEquals( $expected, $singular );
		}
	}
	
	/**
	 * This test is actually failing but we'll let it go for the time being
	 * as we test the routines passing the "name" as the plural version
	 */
	function test_bw_default_labels() {
		$this->switch_to_locale( 'en_GB' );
		$array = bw_default_labels();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
  }
	
	function test_bw_default_labels_posts() {
		$this->switch_to_locale( 'en_GB' );
		$array = bw_default_labels( array( "name" => "Posts" ) );
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
  }
	
	function test_bw_default_labels_pages() {
		$this->switch_to_locale( 'en_GB' );
		$array = bw_default_labels( array( "name" => "Pages" ) );
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
  }
	
	function test_bw_default_labels_contacts() {
		$this->switch_to_locale( 'en_GB' );
		$array = bw_default_labels( array( "name" => "Contacts" ) );
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
  }
	
	
}
