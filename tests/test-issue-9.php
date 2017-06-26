<?php // (C) Copyright Bobbing Wide 2017

class Tests_issue_9 extends BW_UnitTestCase {

	/** 
	 * From     | BW_::method() | oik # | Tot # | Parameters to translate
	 * ----     | --            | ----- | ----- | ----------
	 * p        | p             | 129   |       | array( 'string' );
	 * _alink   | alink         |  46   |       | array( null, null,'string', 'string' );
	 * alink    | alink         |  52	  |       | array( null, null, 'string', 'string', null, null );
	 * _bwtnt   | bwtnt         | 39    | 50    | array( 'string', null );
	 * oik_menu_header | oik_menu_header | 12 | 78 | array( 'string', null );
	 * oik_box | oik_box | 32  | 194 |  array( null, null, 'string' );
	 

  alink  | 46 |  | array( null, null, 'string', 'string', null, null );
  bw_array_get_dcb   | | |  array( null, null, 'string' );
  bw_emailfield   | | |  array( null, null, 'string', null, null, null );
  bw_emailfield_arr   | | |  array( null, 'string', null, null, null, null, null );
  bw_form_field   | | |  array( null, null, 'string' );
  bw_radio   | | |  array( null, null, 'string', null, null, null ); 
  bw_register_field   | | |  array( null, null, 'string' );
  bw_select_arr   | | |  array( null, 'string', null, null, null, null, null );
  bw_skv   | | |  array( null, 'string', 'string' );
  bw_textarea   | | |  array( null, null, 'string', null, null ); 
  bw_textarea_arr   | | |  array( null, 'string', null, null, null, null, null );
  bw_textarea_cb_arr   | | |  array( null, 'string' ); // missing the remaining parms as a test.
  bw_textfield   | | |  array( null, null, 'string', null, null, null );
  bw_textfield_arr   | | |  array( null, 'string', null, null, null, null, null );
  bw_translate   | | |  array( 'string' );
  bw_tt   | | |  array( null, 'string' );
  bwt   | | |  array( 'string' );
  ehwhat   | | |  array( 'string' );
  li   | | |  array( 'string' );
  lit   | | |  array( 'string' );
  p  | 129 |  array( 'string' );
  td   | | |  array( 'string' );
  th   | | |  array( 'string' );
  // 
  // label']
  // submit
  //h1   | | |  array( 'string' );
  //h2   | | |  array( 'string' );
  //h3   | | |  array( 'string' );
  //h4   | | |  array( 'string' );
  //h5   | | |  array( 'string' );
  //h6   | | |  array( 'string' );
	 *
	 */
	
	/** 
	 * set up logic
	 * 
	 * - ensure any database updates are rolled back
	 */
	function setUp() {
		parent::setUp();
		//bobbcomp::bw_get_option( "fred" );
		oik_require_lib( "class-BW-" );
	}

	function test_BW_p() {
		$expected = bw_ret( p( "This page left intentionally blank" ) );
		$actual = bw_ret( BW_::p( __( "This page left intentionally blank" , "oik" ) ) );
		$this->assertEquals( $expected, $actual );
	}

	function test_BW_alink() {
		$expected = bw_ret( _alink( null, "http://example.com", "Text", "Alt" ) );
		$actual = bw_ret( BW_::alink( null, "http://example.com", __( "Text", "oik" ), __( "Alt", "oik" ) ) );
		$this->assertEquals( $expected, $actual );
	}

	function test_BW_oik_menu_header() {
		$expected = bw_ret( oik_menu_header() );
		$actual = bw_ret( BW_::oik_menu_header() );
		$this->assertEquals( $expected, $actual );

		$expected = bw_ret( oik_menu_header( "Text" ) );
		$actual = bw_ret( BW_::oik_menu_header( __( "Text", "oik" ) ) );
		$this->assertEquals( $expected, $actual );

		$expected = bw_ret( oik_menu_header( "Text" , "class" ) );
		$actual = bw_ret( BW_::oik_menu_header( __( "Text", "oik" ), "class" ) );
		$this->assertEquals( $expected, $actual );
	}

	function test_BW_oik_box() {
		oik_require( "admin/oik-admin.inc" );
		$expected = bw_ret( oik_box() );
		$actual = bw_ret( BW_::oik_box() );
		$this->assertEquals( $expected, $actual );
	}

	function test_BW_bwtnt() {
		$expected = bw_ret( _bwtnt( "Text", " etc" ) );
		$actual = bw_ret( BW_::bwtnt( __( "Text", "oik" ), " etc" ) );
		$this->assertEquals( $expected, $actual );
	}
	
	function test_BW_label() {
		$expected = '<label for="name">Text for label</label>';
		$actual = BW_::label( "name", "Text for label" );
		$this->assertEquals( $expected, $actual );
	}
	
	function test_BW_bw_textfield() {
		$expected = '<tr><td><label for="test">Text field</label></td><td><input type="text" size="9"name="test" id="test" value="Textfield value" class="" /></td></tr>';
		$actual = bw_ret( BW_::bw_textfield( "test", 9, __( "Text field", "oik" ), "Textfield value" ) );
		$this->assertEquals( $expected, $actual );
	}
	
	function test_BW_bw_textfield_arr() {
		$expected = '<tr><td><label for="options[test]">Text field</label></td><td><input type="text" size="9"name="options[test]" id="options[test]" value="Textfield value" class="" /></td></tr>';
		$array = [ 'test' => "Textfield value" ];
		$actual = bw_ret( BW_::bw_textfield_arr( "options", __( "Text field", "oik" ), $array, "test", 9 ) );
		$this->assertEquals( $expected, $actual );
	}
	
																				
	function test_BW_bw_emailfield() {
		$expected = '<tr><td><label for="test">Email field</label></td><td><input type="email" name="test" value="herb@bobbingwide.com" size="9" /></td></tr>';
		$actual = bw_ret( BW_::bw_emailfield( "test", 9, __( "Email field", "oik" ), "herb@bobbingwide.com" ) ); 
		$this->assertEquals( $expected, $actual );
	}
	
	function test_BW_bw_emailfield_arr() {
		$expected = '<tr><td><label for="options[test]">Email field</label></td><td><input type="email" name="options[test]" value="herb@bobbingwide.com" size="9" /></td></tr>';
		$array = [ 'test' => "herb@bobbingwide.com" ];
		$actual = bw_ret( BW_::bw_emailfield_arr( "options", __( "Email field", "oik" ), $array, "test", 9 ) );
		$this->assertEquals( $expected, $actual );
	
	}

}
