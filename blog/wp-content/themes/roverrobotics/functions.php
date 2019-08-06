<?php
add_action('wp_enqueue_scripts', 'google_fonts');
add_action('wp_enqueue_scripts', 'enlighter');
function enlighter() {
    wp_enqueue_script( get_template_directory_uri() . '/assets/js/EnlighterJS.min.js', array (), 1.1, true);
}
function google_fonts() {
       wp_enqueue_style('Noto+Serif','//fonts.googleapis.com/css?family=Noto+Serif&display=swap');
}
function get_post_thumbnail_url ( $id, $size='large' ) {    
    if ( has_post_thumbnail($id) ) {
        $_id = get_post_thumbnail_id($id);
        return wp_get_attachment_image_src($_id, $size)[0];
    } 
}

function get_post_thumbnail_dimensions ( $id, $size='large' ) {
    if ( has_post_thumbnail($id) ) {
        $_id = get_post_thumbnail_id( $id );
        $img = wp_get_attachment_image_src( $_id, $size);
        return array($img[1], $img[2]);
    }
}

function get_post_thumbnail_padding_top ( $id, $size='large' ) {
    $dims = get_post_thumbnail_dimensions($id, $size);
    return (($dims[1] / $dims[0]) * 100) . '%';
}

function get_post_thumbnail_alt ( $id ) {
    if ( has_post_thumbnail($id) ) {
        $_id = get_post_thumbnail_id($id); 
        return get_post_meta($_id, '_wp_attachment_image_alt', true);
    } else {
        $attachments = get_posts(array(
            'post_type' => 'attachment',
            'numberposts' => -1,
            'post_status' => null,
            'post_parent' => $id
        ));

        if ( $attachments ) {
            return get_post_meta($attachments[0]->ID, '_wp_attachment_image_alt', true);
        }
    }
}

function get_acf_image_url ( $img, $size='large' ) {
    return $img['sizes'][$size];
}

function get_acf_image_dimensions ( $img, $size='large' ) {
    $sizes = $img['sizes'];
    return array($sizes[$size . '-width'], $sizes[$size . '-height']);
}

function get_acf_image_padding_top ( $img, $size='large' ) {
    $dims = get_acf_image_dimensions($img, $size);
    return (($dims[1] / $dims[0]) * 100) . '%';
}   

