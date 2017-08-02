<?php // (C) Copyright Bobbing Wide 2017

class Tests_issue_9 extends BW_UnitTestCase {

	/** 
	 * When the tests for the functions have been written then these notes can either be removed or copied to the issue's comments. 
	 * This is to be of help to anyone converting other plugins that use the oik APIs.
	 * 
	 * 
	 * T

 
Test | From     | BW_::method() | oik # | Tot # | Parameters to translate
--   | ----     | ------------- | ----- | ----- | --------------------------
y | _alink   | alink         |  12 | 46   |  array( null, null,'string', 'string' );
y |_bwtnt   | bwtnt         | 39    | 50    | array( 'string', null );
y | alink    | alink         |  52	  |       | array( null, null, 'string', 'string', null, null );
y | br | br | 11 | 58 | array( 'string' );
  | bw_array_get_dcb | n/a  | 20 | 115 |  array( null, null, 'string' );
y | bw_emailfield   | 1  | 34 |  array( null, null, 'string', null, null, null );
y | bw_emailfield_arr   | 1 | 11 |  array( null, 'string', null, null, null, null, null );
 | bw_form_field   |  | 150 |  array( null, null, 'string' );
 | bw_radio   | 3 | 16 |  array( null, null, 'string', null, null, null ); 
 | bw_register_field   | | 343 |  array( null, null, 'string' );
 | bw_select_arr   | 2 | 18 |  array( null, 'string', null, null, null, null, null );
 | bw_skv   | 250 | 769 |  array( null, 'string', 'string' );
 | bw_textarea   | 12 | 139 |  array( null, null, 'string', null, null ); 
 | bw_textarea_arr   | 6 | 48 |  array( null, 'string', null, null, null, null, null );
 | bw_textarea_cb_arr   | 1  | 53 |  array( null, 'string' ); // missing the remaining parms as a test.
 | bw_textfield   | 27 | 139 |  array( null, null, 'string', null, null, null );
 | bw_textfield_arr | 60 | 161 |  array( null, 'string', null, null, null, null, null );
 | bw_translate   | | 138 |  array( 'string' );
 | bw_translate | 20 | 109 | array( 'string' )
 | bw_tt   | 1 | 1 |  array( null, 'string' );
 | bwt   | 1  | 1 |  array( 'string' );
 | ehwhat   | | |  array( 'string' );
 | label | 10 | 90 | array( null, 'string' ); 
 | li   | 15 | 44 |  array( 'string' );
 | lit   | 4 | 12 |  array( 'string' );
 | oik_box   | 23 | 197 |  array( null, null, 'string' );
 | oik_box | oik_box | 32  | 194 |  array( null, null, 'string' );
 | oik_menu_header   | 11 | 76 |  array( 'string', null );
 | oik_menu_header | oik_menu_header | 12 | 78 | array( 'string', null );
y | p        | p             | 129   | 902      | array( 'string' );
 | td   | 4 | 51 |  array( 'string' );
 | th   | 3 | 60 |  array( 'string' );
	
Since the following functions were not listed in makeoik then
the strings weren't being extracted so translation couldn't have been performed on them

bw_checkbox | 
bw_checkbox_arr | 

therefore it's OK to call BW_::label() in bw_checkbox(), so no need to change bw_checkbox_arr()

bw_form_field_  |
bw_form_field_* |


bw_register_field relied on translation being performed in bw_form_field or bw_format_field

However, the `gettext` filter could be invoked so the text could be modified by any plugin that implements this hook.

bw_translate() should mostly become __() with the required text domain depending on the source file

bw_tt() performed deferred translation of #hint text for fields 

bwt() is only used by th()

li() simply calls lit() so we'll implement lit()

ehwhat() is not a function

td() is sometimes used for text - the string should be translated. Consider using bw_td(). 
th() is also sometimes used for text, the string should be translated and bw_th() used instead. 

bw_array_get_dcb() is a special case that performs translation for the default text.
Rather than replace the callback parameter with null when we've already performed translation
we should just change the call to bw_array_get()

br()

retimage() - title parameter should be translated using __()
	 

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
		
		$this->setExpectedDeprecated( "bw_translate" );
		$expected = bw_ret( p( "This page left intentionally blank" ) );
		$actual = bw_ret( BW_::p( __( "This page left intentionally blank" , "oik" ) ) );
		$this->assertEquals( $expected, $actual );
	}

	function test_BW_alink() {
		$this->setExpectedDeprecated( "bw_translate" );
		$expected = bw_ret( _alink( null, "http://example.com", "Text", "Alt" ) );
		$actual = bw_ret( BW_::alink( null, "http://example.com", __( "Text", "oik" ), __( "Alt", "oik" ) ) );
		$this->assertEquals( $expected, $actual );
	}
	
	/** 
	 */
	function test_BW_oik_menu_header() {
		$this->setExpectedDeprecated( "bw_translate" );
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
	
	/**
	 * 
	 * Passing no parameters to oik_box() causes a strange translation of a null title to "0" - see TRAC #41257
	 * So we might as well pass a non-null value and see it being translated; the old way and the new way.
	 * 
	 * Note: Here we're using the en_US spelling. The translations should be the same.
	 */
	function test_BW_oik_box() {
		$this->setExpectedDeprecated( "bw_translate" );
		oik_require( "admin/oik-admin.inc" );
		$expected = bw_ret( oik_box( "class", "ID", "Behavior when image clicked" ) );
		$actual = bw_ret( BW_::oik_box( "class", "ID", __( "Behavior when image clicked", "oik" ) ) );
		$this->assertEquals( $expected, $actual );
	}

	function test_BW_bwtnt() {
		$this->setExpectedDeprecated( "bw_translate" );
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
	
	function test_BW_bw_textarea() {
		$expected = '<tr><td><label for="test">Text area</label></td><td><textarea rows="10" cols="9" name="test">Textarea value</textarea></td></tr>';
		$actual = bw_ret( BW_::bw_textarea( "test", 9, __( "Text area", "oik" ), "Textarea value", 10 ) );
		$this->assertEquals( $expected, $actual );
	}
	
	function test_BW_bw_textarea_arr() {
		$expected = '<tr><td><label for="options[test]">Text area</label></td><td><textarea rows="5" cols="10" name="options[test]">Textarea value</textarea></td></tr>';
		$array = [ 'test' => "Textarea value" ];
		$actual = bw_ret( BW_::bw_textarea_arr( "options", __( "Text area", "oik" ), $array, "test", 10 ) );
		$this->assertEquals( $expected, $actual );
	}
 
	function test_BW_bw_textarea_cb_arr() {
		$expected = '<tr><td><label for="options[test]">Text area&nbsp;<input type="hidden" name="options[test_cb]" value="0" /><input type="checkbox" name="options[test_cb]" id="options[test_cb]" checked="checked"/></label></td><td><textarea rows="5" cols="10" name="options[test]">Textarea value</textarea></td></tr>';
    $array = [ 'test_cb' => "on", "test" => "Textarea value" ];
		$actual = bw_ret( BW_::bw_textarea_cb_arr( "options", __( "Text area", "oik" ) , $array, "test", 10 ) );
		$this->assertEquals( $expected, $actual );
	}

	function test_BW_bw_radio() {
		$expected = '<tr><td><label for="test">Radio button</label></td><td><label for="test">Yes</label><input type="radio" name="test" value="Y" /><label for="test">No</label><input type="radio" name="test" id="1" value="N" /></td></tr>';
		$actual = bw_ret( BW_::bw_radio( "test", __( "Radio button", "oik" ), array( "Y", "N" ), array( __( "Yes" ), __( "No" ) ) ) );
		$this->assertEquals( $expected, $actual ); 
	}

	function test_BW_bw_select() {
		$expected = '<tr><td><label for="test">Select list</label></td><td><select name="test"><option value="yes"  selected=\'selected\'>Yes</option><option value="no" >No</option><option value="maybe" >Maybe</option></select></td></tr>';
		$options = array( "yes" => __( "Yes" )
									  , "no" => __( "No" )
										, "maybe" => __( "Maybe" )
										);
		$args = array( '#multiple' => false
								 , '#options' => $options
								 , '#optional' => false
								 );
		$actual = bw_ret( BW_::bw_select( "test", __( "Select list", "oik" ), "yes", $args ) );
		$this->assertEquals( $expected, $actual );
	}
	
	function test_BW_bw_select_arr() {
		$expected = '<tr><td><label for="options[test]">Select list</label></td><td><select name="options[test]"><option value="yes"  selected=\'selected\'>Yes</option><option value="no" >No</option><option value="maybe" >Maybe</option></select></td></tr>';
		$array = [ "test" => "yes" ];
		$options = array( "yes" => __( "Yes" )
									  , "no" => __( "No" )
										, "maybe" => __( "Maybe" )
										);
		$args = array( '#multiple' => false
								 , '#options' => $options
								 , '#optional' => false
								 );
		$actual = bw_ret( BW_::bw_select_arr( "options", __( "Select list", "oik" ), $array, "test", $args ) );
		$this->assertEquals( $expected, $actual );
	} 
	
	function test_BW_bw_skv() {
		$actual = BW_::bw_skv( "default value", "value1|value2", __( "Notes about arg", "oik" ) );
		$expected = array( "default" => "default value", "values" => "value1|value2", "notes" => "Notes about arg" );
		$this->assertEquals( $expected, $actual );
	}
	
	function test_BW_lit() {
		$expected = '<li class="myclass" id="myid">List item text</li>';
		$actual = bw_ret( BW_::lit( __( "List item text", "oik" ), "myclass", "myid" ) );
		$this->assertEquals( $expected, $actual );
	}
	
	function test_BW_br() {
		$expected = '<br />optional text';
		$actual = bw_ret( BW_::br( __( "optional text", "oik" ) ) );
		$this->assertEquals( $expected, $actual );
	}
		
	
	
	
		

}
