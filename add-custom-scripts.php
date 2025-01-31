<?php
/**
 * Plugin Name: Add Custom Scripts
 * Plugin URI: http://wordpress.org/add-custom-scripts
 * Description: With this extremly simple and easy to use widget, every website ownder can add custom scripts to his WordPress website within just a few seconds. Add Custom Scripts allows you to add your own scripts (including Google Analytics) to your header or footer regardless of what theme you are using.</p>
 * Author: Brian Stunnert
 * Version: 1.0
 *
 * Copyright 2013  Brian Stunnert  (email : brian.stunnert@outlook.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
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
 *
 * @package Easy-Script-Adder
 * @author John Regan
 * @version 1.0
 */


/**
 * Print direct link to Add Custom Scripts admin page
 *
 * Fetches array of links generated by WP Plugin admin page ( Deactivate | Edit )
 * and inserts a link to the Add Custom Scripts admin page
 *
 * @since  1.0
 * @param  array $links Array of links generated by WP in Plugin Admin page.
 * @return array $links Array of links to be output on Plugin Admin page.
 */
function ssjr3_settings_link( $links ) {
	$settings_page = '<a href="' . admin_url('tools.php?page=add-custom-scripts.php' ) .'">Settings</a>';
	array_unshift( $links, $settings_page );
	return $links;
}

$plugin = plugin_basename(__FILE__);

add_filter( "plugin_action_links_$plugin", 'ssjr3_settings_link' );

/**
 * Register text domain
 *
 * @since 1.0
 */
function ssjr3_textdomain() {
	load_plugin_textdomain('ssjr3');
}

add_action('init', 'ssjr3_textdomain');


/**
 * Delete Settings on Uninstall
 *
 * @since 1.0
 */
function ssjr3_uninstall() {
	unregister_setting( 'ssjr3_settings_group', 'ssjr3_settings' );
}

register_uninstall_hook( __FILE__, 'ssjr3_uninstall' );

/**
 * Print Header Scripts
 *
 * @since 1.0
 */
function ssjr3_header_scripts() {
	ob_start();
		$options = get_option( 'ssjr3_settings' );
		$sfhs_header = isset( $options['header_scripts_input'] ) ? $options['header_scripts_input'] : '';
		echo "<script type=text/javascript>\n";
		if ( isset( $options['ssjr3_credit'] ) ) {
			echo "// Rendered by the Add Custom Scripts Plugin
// http://wordpress.org/plugins/add-custom-scripts/
 \n";
		}
		echo $sfhs_header;
		echo "\n</script>";
	echo ob_get_clean();
}

/**
 * Print Footer Scripts
 *
 * @since 1.0
 */
function ssjr3_footer_scripts() {
	ob_start();
		$options = get_option( 'ssjr3_settings' );
		$sfhs_footer = isset( $options['footer_scripts_input'] ) ? $options['footer_scripts_input'] : '';
		echo "<script type=text/javascript>\n";
		if ( isset( $options['ssjr3_credit'] ) ) {
			echo "// Rendered by the Add Custom Scripts Plugin
// http://wordpress.org/plugins/add-custom-scripts/
\n";
		}
		echo $sfhs_footer;
		echo "\n</script>";
	echo ob_get_clean();
}

/**
 * Attaches Scripts to Hooks to be rendered
 *
 * @since 1.0
 */
function ssjr3_render_scripts() {
	$options = get_option( 'ssjr3_settings' );
	if ( isset( $options['header_scripts_input'] ) )
		add_action( 'wp_head', 'ssjr3_header_scripts' );

	if ( isset( $options['footer_scripts_input'] ) )
		add_action( 'wp_footer', 'ssjr3_footer_scripts' );
}

add_action( 'init', 'ssjr3_render_scripts' );

/**
 * Register "Header/Footer Scripts" submenu in "Tools" Admin Menu
 *
 * @since 1.0
 */
function ssjr3_register_submenu_page() {
	add_management_page( __( 'Add Custom Scripts', 'ssjr3' ), __( 'Header/Footer Scripts', 'ssjr3' ), 'edit_themes', basename(__FILE__), 'ssjr3_render_submenu_page' );
}

add_action( 'admin_menu', 'ssjr3_register_submenu_page' );


/**
 * Register settings
 *
 * @since 1.0
 */
function ssjr3_register_settings() {
	register_setting( 'ssjr3_settings_group', 'ssjr3_settings' );

	add_settings_section( 'ssjr3_primary_section', __( '', 'rt_polls' ), 'ssjr3_primary_section_cb', __FILE__ );

	add_settings_field( 'ssjr3_header_scripts_input', __( 'Header Scripts', 'rt_polls' ), 'ssjr3_header_scripts_input', __FILE__, 'ssjr3_primary_section' );

	add_settings_field( 'ssjr3_footer_scripts_input', __( 'Footer Scripts', 'rt_polls' ), 'ssjr3_footer_scripts_input', __FILE__, 'ssjr3_primary_section' );

	add_settings_field( 'ssjr3_credit', __( 'This Plugin is Really Helpful!', 'rt_polls' ), 'ssjr3_credit', __FILE__, 'ssjr3_primary_section' );
}

add_action('admin_init', 'ssjr3_register_settings');

/**
 * Render Primary Section text
 *
 * @since  1.0
 */
function ssjr3_primary_section_cb() {}

/**
 * Render Header Scripts Input
 *
 * @since  1.0
 */
function ssjr3_header_scripts_input() {
	$options  = get_option('ssjr3_settings');
	$field_value   = isset( $options['header_scripts_input'] ) ? $options['header_scripts_input'] : ''; ?>
	<textarea id="header-scripts-input" name="ssjr3_settings[header_scripts_input]" placeholder="Header Scripts" style="width:300px; height: 200px;" ><?php echo esc_html( $field_value ) ?></textarea>
	<?php
}

/**
 * Render Footer Scripts Input
 *
 * @since  1.0
 */
function ssjr3_footer_scripts_input() {
	$options  = get_option('ssjr3_settings');
	$field_value   = isset( $options['footer_scripts_input'] ) ? $options['footer_scripts_input'] : ''; ?>
	<textarea id="footer-scripts-input" name="ssjr3_settings[footer_scripts_input]" placeholder="Footer Scripts" style="width:300px; height: 200px;" ><?php echo esc_html( $field_value ) ?></textarea>
	<?php
}

/**
 * Render Credit Author checkbox
 *
 * @since  1.0
 */
function ssjr3_credit() {
	$options = get_option('ssjr3_settings');
	$field_value = isset( $options['ssjr3_credit'] ) ? $options['ssjr3_credit'] : 1;
	?>
		<input type="checkbox" name="ssjr3_settings[ssjr3_credit]" value="1" <?php checked( 1, $field_value ) ?> />
		<p style="width: 300px;" class="description">
			<?php _e( 'Print credit to the author along with your script(s).  Nothing will be publicly visible on your site.', 'ssjr3' ) ?>
		</p>
	<?php
}

/**
 * Render Plugin submenu page
 *
 * @since 1.0
 */
function ssjr3_render_submenu_page() {
	if ( isset( $_GET['settings-updated'] ) ) : ?>
		<div id="message" class="updated"><p><?php _e( 'Scripts updated successfully.' ); ?></p></div>
	<?php endif; ?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<h2><?php _e( 'Add Custom Scripts Plugin', 'ssjr3' ); ?></h2>
		<p><?php _e('Add your own scripts (including Google Analytics) to your header or footer regardless of what theme you are using.', 'ssjr3') ?></p>
		<form name="ssjr3-form" action="options.php" method="post" enctype="multipart/form-data">
			<?php settings_fields('ssjr3_settings_group'); ?>
			<?php do_settings_sections( __FILE__ ); ?>
			<p class="submit">
				<input name="scripts-submit" type="submit" class="button-primary" value="<?php esc_attr_e( 'Update Scripts', 'ssjr3' ); ?>" />
			</p>
		</form>
	</div>
	<?php
}
