<?php
/*
Plugin Name: VJMedia: Backup Helper (General Site)
Description: for General site use, without Monitor
Version: 1.0
Author: <a href="http://www.vjmedia.com.hk/">技術組</a>
*/

if ( ! defined( 'WPINC' ) ) { die; }

define('VJBHG_PATH','vj-backuphelpergeneral/vj-backuphelpergeneral.php');

if ( ! function_exists( 'vjbhg_dummy' ) ) {
        require trailingslashit( WP_PLUGIN_DIR ) . VJBHG_PATH;
}

function vjbhg_deactivate( $plugin, $network_wide ) {
        if ( VJBHG_PATH === $plugin ) {
                deactivate_plugins( VJBHG_PATH );
        }
} add_action( 'activated_plugin', 'vjbhg_deactivate', 10, 2 );

function vjbhg_mu_plugin_active( $actions ) {
        if ( isset( $actions['activate'] ) ) {
                unset( $actions['activate'] );
        }
        if ( isset( $actions['delete'] ) ) {
                unset( $actions['delete'] );
        }
        if ( isset( $actions['deactivate'] ) ) {
                unset( $actions['deactivate'] );
        }

        return array_merge( array( 'mu-plugin' => esc_html__( 'Activated as mu-plugin', 'vj-backuphelpergeneral' ) ), $actions );
}
add_filter( 'network_admin_plugin_action_links_' . VJBHG_PATH, 'vjbhg_mu_plugin_active' );
add_filter( 'plugin_action_links_' . VJBHG_PATH, 'vjbhg_mu_plugin_active' );

add_action( 'after_plugin_row_' . VJBHG_PATH,
	function () {
		print( '<script>jQuery(".inactive[data-plugin=\'vj-backuphelpergeneral/vj-backuphelpergeneral.php\']").attr("class", "active");</script><script>jQuery(".active[data-plugin=\'vj-backuphelpergeneral/vj-backuphelpergeneral.php\'] .check-column input").remove();</script>' );
		print( '<script>jQuery("tr[data-plugin=\'vj-backuphelpergeneral/vj-backuphelpergeneral.php\']").hide();</script>');
	}
);


