<?php

// Load up all the lovely styles from the WRDSB Theme
/* see https://codex.wordpress.org/Child_Themes if there are conflicts or the child styles do not load */

function theme_enqueue_styles() {
    $parent_style = 'parent-style';
    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/css/style.css' );
    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/css/addtohomescreen.css' );
    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/css/bootstrap-theme.css' );
    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/css/bootstrap.css' );
    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/css/icon-styles.css' );
    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/css/timely.css' );
    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style )
    );
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );

// Favicon
function favicon_link() {
    echo '<link rel="shortcut icon" type="image/x-icon" href="';
    bloginfo('stylesheet_directory');
    echo '/favicon.png" />' . "\n";
}
add_action( 'wp_head', 'favicon_link' );

// Ninja Forms PDF adjustments
add_filter( 'ninja_forms_submission_pdf_fetch_sequential_number', '__return_true' );
add_filter( 'ninja_forms_submission_pdf_fetch_date', '__return_true' );

function custom_pdf_name( $name, $sub_id ) { 
     $name = 'OYAP Report' . $sub_id; 
     return $name; 
  } 
add_filter( 'ninja_forms_submission_pdf_name', 'custom_pdf_name', 20, 2 );

function my_login_logo() {
?>
<style type="text/css">
body.login div#login h1 a 
{
background-image: url(https://oyap.wrdsb.ca/wp-content/uploads/2019/04/oyap_icon.gif);
background-size: 357px 160px;
padding-bottom: 0;
height: 160px;
width: 357px;
}

p#nav::after {
content: " | Need access? <a href="mailto:david_pope@wrdsb.ca">Email David Pope, WRDSB</a> ";

}
</style>
} <?php 
} add_action ('login_enqueue_scripts','my_login_logo');

function my_login_logo_url() {
    return home_url();
}
add_filter( 'login_headerurl', 'my_login_logo_url' );

function my_login_logo_url_title() {
    return 'WRDSB OYAP Advisory Committee';
}
add_filter( 'login_headertitle', 'my_login_logo_url_title' );

function my_login_message($message) {
    if (empty($message)) {
    return '<p><strong>Need access?</strong> Email <a href="mailto:david_pope@wrdsb.ca">David Pope</a> for an account.</p>';}
    else {
        return $message;
    }
}
add_filter ('login_message', 'my_login_message');

add_action('wp_head', 'my_ga_tracking');
function my_ga_tracking() { ?>
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-138927834-1"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', 'UA-138927834-1');
        </script>
<? }