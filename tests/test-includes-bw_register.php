<?php // (C) Copyright Bobbing Wide 2017,2018

/** 
 * Unit tests for the includes/bw_register.php file
 */

class Tests_includes_bw_register extends BW_UnitTestCase {

	/** 
	 * set up logic
	 * 
	 * - ensure any database updates are rolled back
	 * - The oik plugin must be activated!
	 */
	function setUp(): void {
		parent::setUp();
		oik_require( "includes/bw_register.php" );
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
									 , "Status" => "Statu"
									 );
		foreach ( $plutos as $plural => $expected ) {
			$singular = bw_singularize( $plural );
			$this->assertEquals( $expected, $singular );
		}
	}
	
	/**
	 * Test added to check if the language file was being loaded
	 * @TODO Proper solution will confirm that it's the latest language file
	 * loaded from the plugin rather than a previous one loaded from wp-content/languages/plugins
	 */
	function test_en_GB_text_domain_loaded() {
		$this->switch_to_locale( 'en_GB' );
		$noposts = __( 'No %1$s found in Trash', "oik");
		$this->assertEquals( 'No %1$s found in bin', $noposts );
	}
	
	function test_bw_default_labels() {
		$this->switch_to_locale( 'en_GB' );
		$array = bw_default_labels();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
  }
	
	function test_bw_default_labels_bb_BB() {
		$this->switch_to_locale( 'bb_BB' );
		$array = bw_default_labels();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( 'en_GB' );
  }
	
	function test_bw_default_labels_posts() {
		$this->switch_to_locale( 'en_GB' );
		$array = bw_default_labels( array( "name" => "Posts" ) );
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
  }
	
	function test_bw_default_labels_posts_bb_BB() {
		$this->switch_to_locale( 'bb_BB' );
		$array = bw_default_labels( array( "name" => __( "Posts", "oik" ) ) );
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( 'en_GB' );
  }
	
	function test_bw_default_labels_pages() {
		$this->switch_to_locale( 'en_GB' );
		$array = bw_default_labels( array( "name" => "Pages" ) );
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
  }
	
	/**
	 * Note: "Pages" is not expected to be in the oik text domain so this won't be translated
	 * in the generated labels. Files in the tests folder should be excluded from makepot processing.
	 */
	function test_bw_default_labels_pages_bb_BB() {
		$this->switch_to_locale( 'bb_BB' );
		$array = bw_default_labels( array( "name" => __( "Pages", "oik" ) ) );
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( 'en_GB' );
  }
	
	/**
	 * Note: Passing in lower case 'contacts' means name will be lower case.
	 * This may not be what we want. So we won't do it. 
	 */
	function test_bw_default_labels_contacts() {
		$this->switch_to_locale( 'en_GB' );
		$array = bw_default_labels( array( "name" => "Contacts" ) );
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
  }
	
	/**
	 * It would have been Contacts but that's not in the oik text domain
	 * so we'll use "Themes" instead
	 */
	function test_bw_default_labels_themes_bb_BB() {
		$this->switch_to_locale( 'bb_BB' );
		$array = bw_default_labels( array( "name" => __( "Themes", "oik" ) ) );
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( 'en_GB' );
  }
	
	function test_bw_default_taxonomy_labels() {
		$this->switch_to_locale( 'en_GB' );
		$array = bw_default_taxonomy_labels();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( 'en_GB' );
	}
	
	function test_bw_default_taxonomy_labels_bb_BB() {
		$this->switch_to_locale( 'bb_BB' );
		$array = bw_default_taxonomy_labels();
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( 'en_GB' );
	}
	
	function test_bw_default_taxonomy_labels_themes() {
		$this->switch_to_locale( 'en_GB' );
		$array = bw_default_taxonomy_labels( array( "name" => "Themes" ) );
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
	}
	
	function test_bw_default_taxonomy_labels_themes_bb_BB() {
		$this->switch_to_locale( 'bb_BB' );
		$array = bw_default_taxonomy_labels( array( "name" => __( "Themes", "oik" ) ) );
		$html = $this->arraytohtml( $array, true );
		//$this->generate_expected_file( $html );
		$this->assertArrayEqualsFile( $html );
		$this->switch_to_locale( 'en_GB' );
	}
		
}
