<?php // (C) Copyright Bobbing Wide 2017

class Tests_issue_9 extends BW_UnitTestCase {

	/** 
	 * Issue #9 - 100% translatable and localizable on WordPress.org
	 * 
	 * For notes to assist conversion from the API for oik v3.1.4 or earlier to oik v3.2 or later,
	 * such that all translatable strings can be internationalized and therefore localized, see
	 * https://github.com/bobbingwide/oik/issues/9#issuecomment-331842723
	 *
	 * These are the basic tests showing that the static methods in class BW_ produce the same 
	 * output as the original functions. 
	 * 
	 * The original functions will eventually be deprecated. 
	 * But that won't be until all the plugins and themes that use them have been converted.
	 */
	
	/** 
	 * set up logic
	 * 
	 * - ensure any database updates are rolled back
	 */
	function setUp(): void {
		parent::setUp();
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
		oik_require( "admin/oik-admin.php" );
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
		$expected = '<tr><td><label for="test">Text field</label></td><td><input type="text" size="9" name="test" id="test" value="Textfield value" class="" /></td></tr>';
		$actual = bw_ret( BW_::bw_textfield( "test", 9, __( "Text field", "oik" ), "Textfield value" ) );
		$this->assertEquals( $expected, $actual );
	}
	
	function test_BW_bw_textfield_arr() {
		$expected = '<tr><td><label for="options[test]">Text field</label></td><td><input type="text" size="9" name="options[test]" id="options[test]" value="Textfield value" class="" /></td></tr>';
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
