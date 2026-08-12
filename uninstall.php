<?php
/**
 * Uninstall routine: remove the per-post option meta and plugin settings.
 *
 * @package Inlarge
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_post_meta_by_key( '_inlarge_disabled' );
delete_post_meta_by_key( '_inlarge_galleries_enabled' );
// Legacy key from 1.2.0, when galleries were opt-out instead of opt-in.
delete_post_meta_by_key( '_inlarge_galleries_disabled' );
delete_option( 'inlarge_options' );
