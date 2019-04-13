<?php // (C) Copyright Bobbing Wide 2019

class Tests_issue_125 extends BW_UnitTestCase {

	/** 
	 * Issue #125 - tests for draconian validation
	 * We assume the WordPress functions that are called have been tested
	 * So here we only need to check that bad input is sanitized.
	 * But we should at least test the example in the Issue.
	 * The logic implicitely tests:
	 * - oik_sanitize_key_value
	 * - oik_list_validations
	 */
	
	/** 
	 * set up logic
	 * 
	 * - ensure any database updates are rolled back
	 */
	function setUp() {
		parent::setUp();
		oik_require( "admin/oik-admin.php" );
	}
	
	/**
	 *
	 */
	function test_oik_draconian_validation_valid() {
		//oik_require( "admin/oik-admin.php" );
		$input    = [ 'email' => 'herb@bobbingwide.com', 'field' => 'value' ];
		$expected = [ 'email' => 'herb@bobbingwide.com', 'field' => 'value' ];
		$output   = oik_draconian_validation( $input );
		$this->assertEquals( $output, $expected );


	}

	/**
	 * For some reason a@b.c is not a valid email address!
	 */
	function test_oik_draconian_validation_invalid() {
		$inputs      = [];
		$expecteds   = [];
		$inputs[]    = [ 'email' => 'a@b.c' ];
		$inputs[]    = [ 'field' => 'two  spaces?' ];
		$inputs[]    = [ 'field' => '"><script>alert( "XSS")</script>' ];
		$inputs[]    = ['gmap_intro' => "Line1\nline2"];
		$expecteds[] = [ 'email' => '' ];
		$expecteds[] = [ 'field' => 'two spaces?' ];
		$expecteds[] = [ 'field' => '">' ];
		$expecteds[] = [ 'gmap_intro' => "Line1\nline2"];

		for ( $loop = 0; $loop < count( $inputs ); $loop++ ) {
			$input    = $inputs[ $loop ];
			$expected = $expecteds[ $loop ];
			$output   = oik_draconian_validation( $input );
			$this->assertEquals( $expected, $output );
		}

	}


		
}
