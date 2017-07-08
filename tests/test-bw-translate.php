<?php // (C) Copyright Bobbing Wide 2017


class Tests_bw_translate extends BW_UnitTestCase {

	/** 
	 * set up logic
	 * 
	 * - ensure any database updates are rolled back
	 */
	function setUp() {
		parent::setUp();
		//bobbcomp::bw_get_option( "fred" );
		//oik_require_lib( "class-BW-" );
		oik_require_lib( "bobbfunc" );
		
	}
	
	/**
	 * We want to test in en_GB since we want translations to be performed
	 * The trouble is, in en_GB null translates to "0" ?
	 */
	
	function test_locale() {
		$locale = is_admin() ? get_user_locale() : get_locale();
		$this->assertEquals( false, is_admin() );
		if ( $locale == "en_GB" ) {
			$this->assertEquals( "en_GB", $locale );
		} else {
			$this->assertEquals( "en_US", $locale );
		}
	}
	
	/**
	 * This test should return "0" until WordPress TRAC #41257 is fixed
	 * or while the oik language file contains a translation for "0"
	 *
	 */
	function test_bw_translate_null() {
		$this->setExpectedDeprecated( "bw_translate" );
		$actual = bw_translate( null );
		
		$locale = is_admin() ? get_user_locale() : get_locale();
		$expected = "0";
		if ( $locale == "en_US" ) {
			$expected = "";
		}
		$this->assertEquals( $expected, $actual );
	}
	
	function test_bw_translate_bobbingwide() {
		$this->setExpectedDeprecated( "bw_translate" );
		$actual = bw_translate( "bobbingwide" );
		$expected = "bobbingwide";
		$this->assertEquals( $expected, $actual );
	}
	
	function test_get_translations_for_domain_verbose() {
		$translations = get_translations_for_domain( "verbose" );
		$this->assertInstanceOf( "NOOP_Translations", $translations );
	}
		
		


}
