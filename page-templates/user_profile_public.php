<?php
/*
Template Name: Public Profiles
*/
?>
<?php $user_query = new WP_User_Query(
  array(
    'blog_id' => $GLOBALS['blog_id'],
    'meta_key' => 'last_name',
    'orderby' => 'meta_value',
    'order' => 'ASC'
  )
); 

function json_sort(&$json, $ascending = true) {
    $names = [];
    
    // Creating a named array for sorting
    foreach($json AS $name => $value) {
        $names[] = $name;
    }
    
    if($ascending) {
        asort($names);
    } else {
        arsort($names);
    }
    
    $result = [];
    
    foreach($names AS $index => $name) {
        // Sorting Sub-Data
        if(is_array($json[$name]) || is_object($json[$name])) {
            json_sort($json[$name], $ascending);
        }
        
        $result[$name] = $json[$name];
    }
    
    $json = $result;
}



?>
<?php get_header(); ?>

<style type="text/css">
  .oyap_biocard {
    border: 1px dashed #ccc;
    padding: 0;
    width: 500px;
    float: left; 
    margin: 0 25px 50px 0;
    background-color: #fff;
    border-radius: 15px 15px 0 0;
    -moz-border-radius: 15px 15px 0 0;
    -webkit-border-radius: 15px 15px 0 0;
  }
  .oyap_biocard:hover {
    border: 1px dashed #666;
    -webkit-box-shadow: 0px 8px 13px 0px rgba(221,221,221,1);
    -moz-box-shadow: 0px 8px 13px 0px rgba(221,221,221,1);
    box-shadow: 0px 8px 13px 0px rgba(221,221,221,1);
    border-radius: 15px 15px 0 0;
    -moz-border-radius: 15px 15px 0 0;
    -webkit-border-radius: 15px 15px 0 0;
  }
  .oyap_keydeets {
    padding: 0;
    height: 96px;
    background-color: #ddd;
    border-radius: 15px 15px 0 0;
    -moz-border-radius: 15px 15px 0 0;
    -webkit-border-radius: 15px 15px 0 0;
  }
  .oyap_keydeets img {
    float: right; 
    padding: 0; 
    margin: 0;
    border-radius: 0 15px 0 0;
    -moz-border-radius: 0 15px 0 0;
    -webkit-border-radius: 0 15px 0 0;
  }
  .oyap_keydeets h2 {
    margin-top: 0 !important;
    font-size: 25px;
    font-weight: bold;
    margin-bottom: 0 !important;
  }
  .oyap_keydeets div {
    padding: 10px 0 0 10px;
  }
  .oyap_keydeets p {
    margin: 0 !important;
    line-height: 1em !important;
  }
  .oyap_contact {
    padding: 0 25px;
    margin: 0;
  }
  .oyap_contact h3 {
    margin-top: 0;
    padding-top: 15px;
  }
  .oyap_contact p {
    margin-bottom: 0 !important;
    padding-bottom: 15px;
  }
  .oyap_bio {
    padding: 0 25px;
    margin: 0;
  }
  .oyap_bio p {
    margin-bottom: 0 !important;
    padding-bottom: 15px;
  }
  .oyap_industry, .oyap_otherdeets {
    margin: 0;
    padding: 0;
  }
  
  .oyap_industry_education {
    background-color: #b8e4fc;
  }
  .oyap_industry_service {
    background-color: #1bb24b;
  }
  .oyap_industry_motive {
    background-color: #49345f;
    color: #fff;
  }
  .oyap_industry_industrial {
    background-color: #d2232a;
    color: #fff;
  }
  .oyap_industry_construction {
    background-color: #ffcb05;
  }
  .oyap_industry_public {
    background-color: #ccc;
  }
  .oyap_industry p {
    padding: 15px;
    font-size: 18px;
    text-transform: uppercase;
    font-weight: bold;
    text-align: center;
    margin: 0 !important;
  }
  /*.hide {
    display: none;
    visibility: hidden;
    height: 0;
  }*/
  .oyap_otherdeets_education {
    background-color: #e2f5ff;
  }
  .oyap_otherdeets_service {
    background-color: #e8f7ed;
  }
  .oyap_otherdeets_motive {
    background-color: #f4edfc;
  }
  .oyap_otherdeets_industrial {
    background-color: #fceff0;
  }
  .oyap_otherdeets_construction {
    background-color: #f9f5e5;
  }
  .oyap_otherdeets_public {
    background-color: #eee;
  }


</style>

<div class="container" role="main">
  <div class="row">
    <div class="col-md-12">
    <?php
      // Start the content loop.
      while ( have_posts() ) : the_post();
        // Include the post format-specific content template.
        get_template_part( 'content', 'page' );
      endwhile;

      // User Loop
      if (!empty($user_query->results)) { ?>        
         <?php 
        foreach($user_query->results as $user) {
          $nottheseones = array ( 1, 2, 3, 5, 9 ); // WordPress userID -- Add '3' when testing complete

          if (!in_array($user->ID, $nottheseones)) {
            // use their Avatar
            $user_headshot = esc_url( get_avatar_url( $user->ID ) );

            // format their name
            if ($user->last_name !== '' && $user->first_name !== '') {
              $member_name      = $user->last_name . ", " . $user->first_name;
              $member_alt_name  = $user->first_name . ' ' . $user->last_name;
            } else {
              $member_name      = '';
              $member_alt_name  = '';
            }

            // format their phone
            $user_phone = preg_replace('/[^0-9]/','',$user->phone);
            
            if(strlen($user_phone) > 10) {
                $countryCode = substr($user_phone, 0, strlen($user_phone)-10);
                $areaCode = substr($user_phone, -10, 3);
                $nextThree = substr($user_phone, -7, 3);
                $lastFour = substr($user_phone, -4, 4);

                $user_phone = '+'.$countryCode.' ('.$areaCode.') '.$nextThree.'-'.$lastFour;
            }
            else if(strlen($user_phone) == 10) {
                $areaCode = substr($user_phone, 0, 3);
                $nextThree = substr($user_phone, 3, 3);
                $lastFour = substr($user_phone, 6, 4);

                $user_phone = '('.$areaCode.') '.$nextThree.'-'.$lastFour;
            }
            else if(strlen($user_phone) == 7) {
                $nextThree = substr($user_phone, 0, 3);
                $lastFour = substr($user_phone, 3, 4);

                $user_phone = $nextThree.'-'.$lastFour;
            }
            else {
              $user_phone = '';
            }
            
            $user_company     = $user->company;
            $user_title       = $user->position;
            $user_industry    = $user->industry;
            $user_companyURL  = $user->companyURL;
            $user_description = $user->description;


            if ($user_industry === 'Education Sector') {
              $oyap_industry = ' oyap_industry_education';
              $oyap_industry_faded = ' oyap_otherdeets_education';
            } else if ($user_industry === 'Service Sector') {
              $oyap_industry = ' oyap_industry_service';
              $oyap_industry_faded = ' oyap_otherdeets_service';
            } else if ($user_industry === 'Motive Sector') {
              $oyap_industry = ' oyap_industry_motive';
              $oyap_industry_faded = ' oyap_otherdeets_motive';
            } else if ($user_industry === 'Industrial Sector') {
              $oyap_industry = ' oyap_industry_industrial';
              $oyap_industry_faded = ' oyap_otherdeets_industrial';
            } else if ($user_industry === 'Construction Sector') {
              $oyap_industry = ' oyap_industry_construction'; 
              $oyap_industry_faded = ' oyap_otherdeets_construction';
            } else if ($user_industry === 'Public Service / Government') {
              $oyap_industry = ' oyap_industry_public';  
              $oyap_industry_faded = ' oyap_otherdeets_public';
            } else {
              unset($oyap_industry);
            }

            if ($user->linkedin !== '') {
              $user_linkedin = '<li><a href="' . $user->linkedin . '" target="_blank" rel="noopener">' . $user->first_name . ' on LinkedIn</a></li>';
            } else {
              unset($user_linkedin);
            }

            if ($user->twitter !== '') {
              $user_twitter = '<li><a href="' . $user->twitter . '" target="_blank" rel="noopener">' . $user->first_name . ' on Twitter</a></li>';
            } else {
              unset($user_twitter);
            }

            if ($user->instagram !== '') {
              $user_instagram = '<li><a href="' . $user->instagram . '" target="_blank" rel="noopener">' . $user->first_name . ' on Instagram</a></li>';
            } else {
              unset($user_instagram);
            }

            if ($user->facebook !== '') {
              $user_facebook = '<li><a href="' . $user->facebook . '" target="_blank" rel="noopener">' . $user->first_name . ' on Facebook</a></li>';
            } else {
              unset($user_facebook);
            }

            if ($user_phone !== '') {
              $user_phone_display = '<li>' . $user_phone . '</li>';
            } else {
              unset($user_phone_display);
            }

            // is there a biography?
            if ($user_description === '') {
              $nothingtoseehere = ' class="hide"';
            } else {
              unset($nothingtoseehere);
            }

            // What is the website?
            if ($user_companyURL != '') {
              $user_company_display = '<a href="'. $user_companyURL . '" target="_blank" rel="noopener noreferrer">'.$user_company. '</a>';
             }
            else if ($user_companyURL === '') {
              unset($user_companyURL);
              $user_company_display = $user->company;
            }

            ?>
            <div class="oyap_biocard">
              <div class="oyap_keydeets"><img src="<?php echo $user_headshot; ?>" alt="<?php echo $member_alt_name; ?>" />
                <div>
                  <h2><?php echo $member_alt_name; ?></h2>
                  <?php echo $user_company_display; ?><br />
                  <?php echo get_user_option('position', $user->ID); ?></p>
                </div>
              </div>
              <div class="oyap_otherdeets<?php echo $oyap_industry_faded;?>">
              <div class="oyap_contact">
                <h3>Contact <?php echo $user->first_name; ?></h3>

                <ul>
                  <li><a href="mailto:<?php echo $user->user_email; ?>"><?php echo $user->user_email; ?></a></li>
                  <?php echo $user_linkedin; ?>
                  <?php echo $user_phone_display; ?>
                  <?php echo $user_twitter; ?>
                  <?php echo $user_instagram; ?>
                  <?php echo $user_facebook; ?>
                </ul>
              </div>

              <div class="oyap_bio">
              <h3<?php echo $nothingtoseehere; ?>>In <?php echo $user->first_name; ?>'s own words</h3>

              <p><?php echo get_user_option('description', $user->ID); ?></p>
              </div>
              </div>
              <div class="oyap_industry<?php echo $oyap_industry; ?>">
                <p><?php echo $user_industry; ?></p>
              </div>
            </div>
          <?php } }
        }else { ?>
	<p>Oops, our Advisory Committee needs members!</p>';
    <?php  } ?>
    </div>
  </div>
</div>
<?php get_footer(); ?>
