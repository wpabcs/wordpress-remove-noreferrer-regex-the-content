<?php
/**
 * Plugin Name: Remove noreferrer
 * Plugin URI: https://www.wpabcs.com/
 * Description: Filter and remove noreferrer from the rel attribute
 * Version: 1.0
 * Author: wpabcs
 */

// Remove extraneous whitespace
function wpabcs_remove_whitespace( $input ) {
    return trim( preg_replace( '#\s+#', ' ', $input ) );
}

// Replace noreferrer
function wpabcs_replace_rel ( $matches ) {
		// String '<a href="https://www.example.com" target="_blank" rel="noreferrer noopener">external example link</a>'
		$anchor_element_prefix = $matches[1]; // returns: '<a href="https://www.example.com" target="_blank" rel='
		$anchor_rel = wpabcs_remove_whitespace( str_ireplace( 'noreferrer', '', $matches[3] ) ); // returns: 'noopener'
		$anchor_element_suffix = $matches[4]; // returns: '>external example link</a>'
		
		return $anchor_element_prefix . $anchor_rel . $anchor_element_suffix;
};

// Sayonara noreferrer
function wpabcs_remove_noreferrer( $content ) {

    $regex = '#(<a\s.*rel=)([\"\']??)(.+)(>.*<\/a>)#i';
    
    return preg_replace_callback( $regex, 'wpabcs_replace_rel', $content );
}
add_filter( 'the_content', 'wpabcs_remove_noreferrer', 999 );
?>
