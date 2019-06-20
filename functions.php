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

//Remove dashboard widgets
function remove_dashboard_meta() {
        remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_primary', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_secondary', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
        remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );
        remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_activity', 'dashboard', 'normal');
        remove_meta_box( 'wpe_dify_news_feed', 'dashboard', 'normal');
        remove_meta_box( 'jetpack_summary_widget', 'dashboard', 'normal');
    if (! current_user_can ('manage_options')) {
    }
}
add_action ('admin_init', 'remove_dashboard_meta');

// Add more fields to User Profile
if (! function_exists('contact_methods')) :
  function contact_methods ($contact_methods) {
      $contact_methods['phone']    = __('Phone');
      $contact_methods['linkedin'] = __('LinkedIn');

      return $contact_methods;
  }
  add_filter ('user_contactmethods', 'contact_methods', 10, 1);
endif;

// Add industries checklist to User Profile

add_action( 'show_user_profile', 'show_extra_profile_fields' );
add_action( 'edit_user_profile', 'show_extra_profile_fields' );

function show_extra_profile_fields( $user ) { ?>
    <h3>Company Details</h3>
    <table class="form-table">
        <tr>
            <th><label for="company">Company name</label></th>
            <td><input type="text" value="<?php echo esc_attr(get_user_meta($user->ID, 'company', true)); ?>" name="company" id="company" size="50"/></td>
            </td>
        </tr>
        <tr>
            <th><label for="position">Your position / title</label></th>
            <td><input type="text" value="<?php echo esc_attr(get_user_meta($user->ID, 'position', true)); ?>" name="position" id="position" size="50"/></td>
            </td>
        </tr>
        <tr>
            <th><label for="companyURL">Your company's website</label></th>
            <td><input type="text" value="<?php echo esc_attr(get_user_meta($user->ID, 'companyURL', true)); ?>" name="companyURL" id="companyURL" size="50"/></td>
            </td>
        </tr>
        <tr>
            <th><label for="industry">Industry</label></th>
            <td>
                <select name="industry" id="industry" >
                    <option value="Service Sector" <?php selected( 'Service Sector', get_the_author_meta( 'industry', $user->ID ) ); ?>>Service Sector</option>
                    <option value="Transportation Sector" <?php selected( 'Transportation Sector', get_the_author_meta( 'industry', $user->ID ) ); ?>>Transportation Sector</option>
                    <option value="Industrial Sector" <?php selected( 'Industrial Sector', get_the_author_meta( 'industry', $user->ID ) ); ?>>Industrial Sector</option>
                    <option value="Construction Sector" <?php selected( 'Construction Sector', get_the_author_meta( 'industry', $user->ID ) ); ?>>Construction Sector</option>
                    <option value="Education Sector" <?php selected( 'Education Sector', get_the_author_meta( 'industry', $user->ID ) ); ?>>Education Sector</option>
                    <option value="Public Service / Government" <?php selected( 'Public Service / Government', get_the_author_meta( 'industry', $user->ID ) ); ?>>Public Service / Government</option>

                </select>
            </td>
        </tr>
    </table>
<?php }

add_action( 'personal_options_update', 'save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'save_extra_profile_fields' );

function save_extra_profile_fields( $user_id ) {
    if ( !current_user_can( 'edit_user', $user_id ) )
        return false;
    update_usermeta( $user_id, 'industry', $_POST['industry'] );
    update_usermeta( $user_id, 'company', $_POST['company'] );
    update_usermeta( $user_id, 'position', $_POST['position'] );
    update_usermeta( $user_id, 'companyURL', $_POST['companyURL'] );
}