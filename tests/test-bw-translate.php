<?php // (C) Copyright Bobbing Wide 2017


class Tests_bw_translate extends BW_UnitTestCase {

	/** 
	 * set up logic
	 * 
	 * - ensure any database updates are rolled back
	 * - if you don't call parent::setUp then $this->setExpectedDeprecated() won't work; as the deprecation checking is not enabled
	 */
	function setUp() {
		parent::setUp();
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
	 * Confirms the behavior of translate against a null string
	 * 
	 * - This test copied from my patch to /svn/wordpress-develop/phpunit/tests/pomo/translations.php
	 * - This test should return "0=zero" until WordPress TRAC #41257 is fixed.
	 * - Once implemented the assertions will need changing.
	 * - OR the test can be removed if it's implemented in core.
	 */
	function test_41257_translate_null_returns_translation_of_0() {
		$entry_digit_0 = new Translation_Entry( array('singular' => '0', 'translations' => array('0=zero') ) );
		$domain = new Translations();
		$domain->add_entry( $entry_digit_0 );
		$this->assertEquals( '0=zero', $domain->translate( "0" ) );
		$this->assertEquals( '0=zero', $domain->translate( null ) );
		$this->assertEquals( '0=zero', $domain->translate( "" ) );
		$this->assertEquals( '0=zero', $domain->translate( 0 ) );
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
