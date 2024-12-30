<?php
/**
 * Plugin Name: JKWS Webhost Information for MainWP
 * Description: Adds the Webhost field to child sites.
 * Version: 0.9.2
 * Author: Jos Klever
 * Author URI: https://joskleverwebsupport.nl
 * Plugin URI: https://github.com/josklever/mainwp-jkws-webhost-information
 *
 * RequiresPlugins: mainwp
 *
 * License: GPL3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 *
 * GitHub Plugin URI: https://github.com/josklever/mainwp-jkws-webhost-information
 * Primary Branch: main
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Add the Webhost field to the Edit page of a single site
add_action( 'mainwp_manage_sites_edit', 'jkws_mainwp_manage_sites_edit', 10, 1 );
function jkws_mainwp_manage_sites_edit( $website ) {
	$webhost  = apply_filters( 'mainwp_getwebsiteoptions', false, $website, 'webhost' );
	?>
	<h3 class="ui dividing header">Additional information (Optional)</h3>
	<div class="ui grid field mainwp_addition_fields_addsite">
		<label class="six wide column middle aligned"><?php esc_html_e( 'Webhost', 'mainwp' ); ?></label>
		<div class="ui six wide column" data-tooltip="<?php esc_attr_e( 'Webhost', 'mainwp' ); ?>" data-inverted="" data-position="top left">
			<div class="ui left labeled input">
				<input type="text" id="mainwp_managesites_edit_webhost" name="mainwp_managesites_edit_webhost" value="<?php echo esc_html($webhost); ?>"/>
			</div>
		</div>
	</div>
	<?php
}

//  Update the Webhost field in the database of the added new site
add_action( 'mainwp_site_added', 'jkws_save_webhost', 10, 1 );
//  Update the Webhost field in the database of the edited site
add_action( 'mainwp_update_site', 'jkws_save_webhost', 10, 1 );
function jkws_save_webhost( $site_id ) {
	$webhost = isset( $_POST['mainwp_managesites_edit_webhost'] ) ? sanitize_text_field( wp_unslash( $_POST['mainwp_managesites_edit_webhost'] ) ) : '';
	apply_filters( 'mainwp_updatewebsiteoptions', false, $site_id, 'webhost', $webhost );	
}

//Add the Webhost column to the Sites Overview page
add_filter( 'mainwp_sitestable_getcolumns', 'jkws_mainwp_sitestable_getcolumns', 10, 1 );
function jkws_mainwp_sitestable_getcolumns( $columns ) {
	$columns['webhost'] = "Webhost";
	return $columns;
}
add_filter( 'mainwp-sitestable-item', 'jkws_sitestable_item', 10 );
function jkws_sitestable_item( $item ) {
     $webhost = apply_filters( 'mainwp_getwebsiteoptions', array(), $item['id'], 'webhost' );
     if ( isset( $webhost ) ) {
          $item[ 'webhost' ] = $webhost;
     } else {
          $item[ 'webhost' ] = '';
     }
     return $item;
}
