<?php

use ElementorPro\Modules\ThemeBuilder\Documents\Theme_Section_Document;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class deGrid extends Theme_Section_Document {

	public static function get_properties() {
		$properties = parent::get_properties();

		$properties['condition_type'] = 'de_grid';
		$properties['location'] = 'de_grid';
    	$properties['support_kit'] = true;
		$properties['support_site_editor'] = true;

		return $properties;
	}

  protected static function get_site_editor_type() {
		return 'de_grid';
	}
  
  protected static function get_site_editor_thumbnail_url() {
		return DETHEMEKIT_ADDONS_DIR_URL . 'assets/images/custom-grid.svg';
	}
  
	public function get_name() {
		return 'de_grid';
	}

	public static function get_title() {
		return __( 'De Grid', 'detheme-kit' );
	}

  protected static function get_editor_panel_categories() {
		$categories = [
			'de-grid' => [
				'title' => __( 'De Grid', 'detheme-kit' ),
			],
		];
    return $categories + parent::get_editor_panel_categories();
	
  }

/*

I want a preview like the template not default

*/
  
 
}

