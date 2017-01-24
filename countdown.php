<?php
/*
   Plugin Name: Simple Countdown
   Plugin URI: https://github.com/PomeloProductions/countdown
   Version: 0.3.0
   Author: Pomelo Productions
   Description: Plugin for adding countdowns to sites
   Text Domain: countdown
   License: GPLv3
  */

namespace Countdown;

use WordWrap;

function hasWordWrap() {
    if ( is_admin() && current_user_can( 'activate_plugins' ) &&  !is_plugin_active( 'word-wrap/word-wrap.php' ) ) {
        add_action( 'admin_notices', '\Countdown\showInstallErrorMessage' );

        deactivate_plugins( plugin_basename( __FILE__ ) );

        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }
    }
}
add_action( 'admin_init', '\Countdown\hasWordWrap' );

function showInstallErrorMessage(){
    echo '<div class="error"><p>Sorry, but Simple Countdown requires Word Wrap to be installed and active.</p></div>';
}

function autoload($className) {
    $fileName = str_replace("Countdown\\", "", $className);
    $fileName = str_replace("\\", "/", $fileName);
    if(file_exists(__DIR__ . "/classes/" . $fileName . ".php"))
        require(__DIR__ . "/classes/" . $fileName . ".php");
}

spl_autoload_register(__NAMESPACE__ . "\\autoload");

include_once(__DIR__ . '/../word-wrap/word-wrap.php');
WordWrap::init(basename(__DIR__));
