<?php
/*
Plugin Name: WoW Raid
Plugin URI: http://danielhuesken.de/portfolio/wowraid/
Description: World of Warcraft Raid Planer, Guild Management and Blog tools.
Author: Daniel H&uuml;sken
Version: 0.1.0.§
Author URI: http://danielhuesken.de
Text Domain: wowraid
Domain Path: /lang/
*/

/*  
	Copyright 2009  Daniel Hüsken  (email : daniel@huesken-net.de)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

// don't load directly 
if ( !defined('ABSPATH') ) 
	die('-1');

//Set plugin dirname
define('WOWRAID_PLUGIN_DIR', dirname(plugin_basename(__FILE__)));
//Set Plugin Version
define('WOWRAID_VERSION', '0.1.0');
//WoWRaid SQL Tabels
global $wpdb,$wp_version;
$wpdb->wowraid_chars = $wpdb->prefix.'wowraid_chars';
$wpdb->wowraid_items = $wpdb->prefix.'wowraid_items';

//load Text Domain
load_plugin_textdomain('wowraid', false, WOWRAID_PLUGIN_DIR.'/lang');	
//Load functions file
require_once(WP_PLUGIN_DIR.'/'.WOWRAID_PLUGIN_DIR.'/app/functions.php');
//Plugin activate
register_activation_hook(__FILE__, 'wowraid_plugin_activate'); 
//Plugin deactivate
register_deactivation_hook(__FILE__, 'wowraid_plugin_deactivate');
//Plugin uninstall
register_uninstall_hook(__FILE__, 'wowraid_plugin_uninstall');
//Version check
if (version_compare($wp_version, '2.8', '<')) { // Let only Activate on WordPress Version 2.8 or heiger
	add_action('admin_notices', create_function('', 'echo \'<div id="message" class="error fade"><p><strong>' . __('Sorry, WoWRaid works only under WordPress 2.8 or higher','wowraid') . '</strong></p></div>\';'));
} else {
	//Plugin init	
	add_action('plugins_loaded', 'wowraid_init');
}
?>
