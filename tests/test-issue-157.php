<?php

// (C) Copyright Bobbing Wide 2020

class Tests_issue_157 extends BW_UnitTestCase {

	/**
	 *
	 */

	/**
	 * set up logic
	 *
	 * - ensure any database updates are rolled back
	 */
	function setUp(): void {
		parent::setUp();

	}

	function test_issue_157() {

		$html =

	}

	function bad_html() {
		$bad_html =
			'<figure class="wp-block-embed-wordpress wp-block-embed is-type-wp-embed is-provider-oik-plugins-com"><div class="wp-block-embed__wrapper">
<blockquote class="wp-embedded-content" data-secret="TlJhsdqOKY"><a href="https://s.b/oikcom/wordpress-plugins-from-oik-plugins/oik-base-plugin/oik-jquery-library/">oik jQuery library</a></blockquote><iframe class="wp-embedded-content" sandbox="allow-scripts" security="restricted" style="position: absolute; clip: rect(1px, 1px, 1px, 1px);" title="&#8220;oik jQuery library&#8221; &#8212; <span class="bw_oik"><abbr  title="OIK Information Kit">oik</abbr></span> plugins.com&#8221; src=&#8221;https://s.b/oikcom/wordpress-plugins-from-oik-plugins/oik-base-plugin/oik-jquery-library/embed/#?secret=TlJhsdqOKY&#8221; data-secret=&#8221;TlJhsdqOKY&#8221; width=&#8221;500&#8243; height=&#8221;282&#8243; frameborder=&#8221;0&#8243; marginwidth=&#8221;0&#8243; marginheight=&#8221;0&#8243; scrolling=&#8221;no&#8221;></iframe>
</div><figcaption>oik jQuery library embed</figcaption></figure>';
return $bad_html;

	}

	function good_html() {
		$good_html =
			'<figure class="wp-block-embed-wordpress wp-block-embed is-type-wp-embed is-provider-oik-plugins-com"><div class="wp-block-embed__wrapper">
<blockquote class="wp-embedded-content" data-secret="TlJhsdqOKY"><a href="https://s.b/oikcom/wordpress-plugins-from-oik-plugins/oik-base-plugin/oik-jquery-library/">oik jQuery library</a></blockquote><iframe class="wp-embedded-content" sandbox="allow-scripts" security="restricted" style="position: absolute; clip: rect(1px, 1px, 1px, 1px);" title="&#8220;oik jQuery library&#8221; &#8212; <span class="bw_oik"><abbr  title="OIK Information Kit">oik</abbr></span> plugins.com" src="https://s.b/oikcom/wordpress-plugins-from-oik-plugins/oik-base-plugin/oik-jquery-library/embed/#?secret=TlJhsdqOKY" data-secret="TlJhsdqOKY" width="500" height="282" frameborder="0" marginwidth="0" marginheight="0" scrolling="no"></iframe>
</div><figcaption>oik jQuery library embed</figcaption></figure>';
	}

}
