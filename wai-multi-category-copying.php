<?php
/**
 * Multi Category Copying
 * 
 * Plugin Name: WAI Multi Category Copying
 * Version: 0.1
 * Plugin URI: 
 * Description: Main site's category copy to sub site in multi site.
 * Author: waiya
 * Author URI: 
 * License: GPL v3
 */

if (! defined('WAI_MCC_FILE')) {
	define( 'WAI_MCC_FILE', __FILE__ );
}

// Load Main File
require_once dirname( WAI_MCC_FILE ) . '/wai-multi-category-copying-main.php';