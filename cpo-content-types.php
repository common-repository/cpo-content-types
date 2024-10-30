<?php
/**
* Plugin Name: 				CPO Content Types
* Description: 				Adds support for a number of content types in your Wordpress installation.
* Version: 					1.1.0
* Author: 					WPChill
* Author URI: 				https://wpchill.com
* Requires: 				4.6 or higher
* License: 					GPLv3 or later
* License URI:       		http://www.gnu.org/licenses/gpl-3.0.html
* Requires PHP: 			5.6
*
* Copyright 2015-2017		Manuel Vicedo 		mvicedo@cpo.es, manuelvicedo@gmail.com			
* Copyright 2017-2020 		MachoThemes 		office@machothemes.com
* Copyright 2020 			WPChill 			heyyy@wpchill.com
*
* Original Plugin URI: 		https://cpothemes.com/plugins/cpo-content-types
* Original Author URI: 		https://cpothemes.com/
* Original Author: 			https://profiles.wordpress.org/manuelvicedo/
*
* Note: 
* Manuel Vicedo transferred ownership rights on: : 03/23/2017 01:14:08 PM  when ownership was handed over to MachoThemes
* The MachoThemes ownership period started on: 03/23/2017 01:14:09 PM 
* SVN commit proof of ownership transferral: https://plugins.trac.wordpress.org/changeset/1620187/cpo-content-types
*
* MachoThemes transferred ownership to WPChill on 5th of November, 2020.
*
* This program is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License, version 3, as
* published by the Free Software Foundation.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program; if not, write to the Free Software
* Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

//Plugin setup
if(!function_exists('ctct_setup')){
	add_action('plugins_loaded', 'ctct_setup');
	function ctct_setup(){
		//Load text domain
		$textdomain = 'ctct';
		$locale = apply_filters('plugin_locale', get_locale(), $textdomain);
		if(!load_textdomain($textdomain, trailingslashit(WP_LANG_DIR).$textdomain.'/'.$textdomain.'-'.$locale.'.mo')){
			load_plugin_textdomain($textdomain, false, dirname(dirname(plugin_basename(__FILE__))).'/languages/');
		}
	}
}


//Add admin stylesheets
add_action('admin_print_styles', 'ctct_add_styles_admin');
function ctct_add_styles_admin(){
	$stylesheets_path = plugins_url('css/' , __FILE__);
	wp_enqueue_style('ctct-admin', $stylesheets_path.'admin.css');
}


//Define custom columns for each custom post type page
add_action('manage_posts_custom_column', 'ctct_admin_columns', 2);
function ctct_admin_columns($column){
	global $post;
	switch($column){
		case 'ctct-image': echo get_the_post_thumbnail($post->ID, array(60,60)); break;
		case 'ctct-portfolio-cats': echo get_the_term_list($post->ID, 'cpo_portfolio_category', '', ', ', ''); break;
		case 'ctct-portfolio-tags': echo get_the_term_list($post->ID, 'cpo_portfolio_tag', '', ', ', ''); break;
		case 'ctct-service-cats': echo get_the_term_list($post->ID, 'cpo_service_category', '', ', ', ''); break;
		case 'ctct-service-tags': echo get_the_term_list($post->ID, 'cpo_service_tag', '', ', ', ''); break;
		default:break;
	}
}


//Add all components
$core_path = plugin_dir_path(__FILE__);
//General
require_once($core_path.'includes/settings.php');
require_once($core_path.'includes/metadata.php');
//Custom Post Types
require_once($core_path.'cposts/cpost_slides.php');
require_once($core_path.'cposts/cpost_features.php');
require_once($core_path.'cposts/cpost_portfolio.php');
require_once($core_path.'cposts/cpost_services.php');
require_once($core_path.'cposts/cpost_team.php');
require_once($core_path.'cposts/cpost_testimonials.php');
require_once($core_path.'cposts/cpost_clients.php');


//Plugin activation hook
function ctct_activation(){
    ctct_cpost_slides();
    ctct_cpost_features();
    ctct_cpost_portfolio();
    ctct_cpost_services();
    ctct_cpost_team();
    ctct_cpost_testimonials();
    ctct_cpost_clients();
	flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'ctct_activation');