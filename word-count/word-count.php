<?php

/**
 * Plugin Name:       Word Count
 * Plugin URI:        https://pobitro.me/plugins/word-count
 * Description:       Simply count words per page
 * Version:           1.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Pobitro Mondal
 * Author URI:        https://pobitro.me
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       word-count
 * Domain Path:       /languages
 */

// Activation
function wordcount_activation_hook() {
}

register_activation_hook( __FILE__, 'wordcount_activation_hook' );

// Deactivation
function wordcount_deactivation_hook() {
}

register_deactivation_hook( __FILE__, 'wordcount_deactivation_hook' );

//uninstall
function wordcount_uninstall_hook() {
}

register_uninstall_hook( __FILE__, 'wordcount_uninstall_hook' );

function wordcount_loaded_textdomain() {
    load_plugin_textdomain( 'word-count', false, dirname( __FILE__ ) . '/languages' );
}

add_action( 'plugins_loaded', 'wordcount_loaded_textdomain' );

function wordcount_content_words( $content ) {
    $striped_words = strip_tags( $content );
    $word_number   = str_word_count( $striped_words );
    $label         = __( 'Total Word Numbers', 'word-count' );
    $label         = apply_filters( 'wordcount_label', $label );
    $tag           = apply_filters( 'wordcount_tag', 'h2' );

    $content .= sprintf( "<%s>%s: %s</%s>", $tag, $label, $word_number, $tag );

    return $content;
}
add_filter( 'the_content', 'wordcount_content_words' );

function wordcount_reading_time( $content ) {
    $striped_words   = strip_tags( $content );
    $word_number     = str_word_count( $striped_words );
    $reading_minute  = floor( $word_number / 200 );
    $reading_seconds = floor( $word_number % 200 / ( 200 / 60 ) );
    $is_visible      = apply_filters( 'wordcount_readtime_display', 1 );
    if ( $is_visible ) {
        $label = __( 'Total Reading Time', 'word-count' );
        $label = apply_filters( 'wordcount_readtime_heading', $label );
        $tag   = apply_filters( 'wordcount_readtime_tag', 'h2' );
        if ( $reading_minute <= 1 ) {
            $content .= sprintf( '<%s>%s: %s minute %s seconds</%s>', $tag, $label, $reading_minute, $reading_seconds, $tag );
        } else {
            $content .= sprintf( '<%s>%s: %s minutes %s seconds</%s>', $tag, $label, $reading_minute, $reading_seconds, $tag );
        }
    }

    return $content;
}

add_filter( 'the_content', 'wordcount_reading_time' );
