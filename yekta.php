<?php

use App\Providers\PanelServiceProvider;

/**
 * Plugin Name: Yekta
 * Description: Yekta SSO WordPress Plugin
 * Version: 0.1.1
 * Author: Hesam
 */

if (!defined('ABSPATH')) {
    exit;
}

use App\Providers\AppServiceProvider;
use App\Providers\SSOServiceProvider;
use App\Routes\RouteServiceProvider;


require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

require_once(__DIR__ . '/Kernel/autoload.php');
require_once(__DIR__ . '/src/Helpers/helper.php');


register_activation_hook(__FILE__, function () {
    (new AppServiceProvider())->register();
});
add_action('plugins_loaded', function () {});


// $rtlLicenseClassName  = 'RTL_License_42dba671b448add4';
// $rtlLicenseFilePath   = __DIR__ . DIRECTORY_SEPARATOR . $rtlLicenseClassName . '.php';
// $rtlLicenseFileHash   = @sha1_file($rtlLicenseFilePath);
// if ( $rtlLicenseFileHash === 'a87c8d293cd73dcb58efb4e6be7ca174a4f10456' && file_exists($rtlLicenseFilePath) ) {
// 	require_once $rtlLicenseFilePath;

// 	if ( class_exists($rtlLicenseClassName) && method_exists($rtlLicenseClassName, 'isActive') ) {
// 		$rtlLicenseClass = new $rtlLicenseClassName();

// 		if ( $rtlLicenseClass->{'isActive'}() === true ) {
// 			add_action('init', function () {
//                 (new RouteServiceProvider())->boot();
//                 (new AppServiceProvider())->boot();
//                 (new SSOServiceProvider())->boot();
//                 (new PanelServiceProvider())->boot();
//             });
// 		}
// 	}
// }

add_action('init', function () {

    (new RouteServiceProvider())->boot();
    (new AppServiceProvider())->boot();
    (new SSOServiceProvider())->boot();
    (new PanelServiceProvider())->boot();
});



// function custom_footer_script()
// {
//     // Register the script
//     wp_register_script(
//         'custom-audioplayer', // Handle
//         WP_PLUGIN_DIR . '/' . appConfig('app.name') . '/' . 'resources/js/audioplayer.js', // Path to the script
//         array('jquery'), // Dependencies (e.g., jQuery)
//         time(), // Version
//         true // Load in footer
//     );
//     wp_enqueue_script('custom-audioplayer');
// }
// add_action('wp_enqueue_scripts', 'custom_footer_script');
