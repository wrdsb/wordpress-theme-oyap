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
); ?>
<?php get_header(); ?>

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
        <div class="table-responsive hidden-sm hidden-xs" >
          <table class="table table-fixed-head" data-toggle="table" data-sort-name="role">
            <thead>
              <tr>
                <th class="text-left" data-field="name" data-sortable="true">Name</th>
                <th class="text-left" data-field="email" data-sortable="true">Email</th>
                <th class="text-left" data-field="phone" data-sortable="true">Phone</th>
                <th class="text-left" data-field="company" data-sortable="true">Company</th>
                <th class="text-left" data-field="role" data-sortable="true">Position/Title</th>
                <th class="text-left" data-field="industry" data-sortable="true">Industry</th>
                <th class="text-left" data-field="website" data-sortable="true">Website</th>
              </tr>
            </thead>
            <tbody>
              <?php 
              $nottheseones = array ( 1, 2, 5 ); // WordPress userID -- Add '3' when testing complete
              foreach($user_query->results as $user) {
                if (!in_array($user->ID, $nottheseones)) {
                  // use their Avatar
                  $user_headshot = esc_url( get_avatar_url( $user->ID ) );
                   // get how they want to be contacted
                  $wrdsb_contact_options = get_user_option('wrdsb_contact_options', $user->ID);
                  // get their voicemail
                  $wrdsb_voicemail = get_user_option('wrdsb_voicemail', $user->ID);
                  // remove V from voicemail
                  if (substr($wrdsb_voicemail,0,1) === 'V') {
                    $wrdsb_voicemail = ltrim($wrdsb_voicemail, 'V');
                  }
                  // get the teacher website
                  $wrdsb_website_url = get_user_option('wrdsb_website_url', $user->ID); 

                  if ($user->last_name !== '' && $user->first_name !== '') {
                    $staff_name = $user->last_name . ", " . $user->first_name;
                  } else {
                    $staff_name = '';

                  $user_phone       = $user->phone;
                  $user_linkedin    = $user->linkedin;
                  $user_company     = $user->company;
                  $user_title       = $user->position;
                  $user_industry    = $user->industry;
                  $user_companyURL  = $user->companyURL;
                  $user_description = $user->description;
                  }
                  ?>
                  <tr>
                    <td><?php echo $staff_name; ?><img src="<?php echo $user_headshot; ?>" alt="" /><p><?php echo get_user_option('description', $user->ID); ?></p></td>
                     <?php 
                    /* Email and Voicemail Columns */

                    // if set to Email Only 
                    if ($wrdsb_contact_options == 'Email') { ?>
                      <td><a href="mailto:<?php echo $user->user_email; ?>"><?php echo $user->user_email; ?></a></td>
                    <?php /*  <td>&nbsp;</td> */ ?>
                    
                    <?php // if set to Voicemail Only
                    } elseif ($wrdsb_contact_options == 'Voicemail') { ?>
                      <td>&nbsp;</td>
                      <?php //<td>echo $wrdsb_voicemail;</td> */?>
                    
                    <?php // if set to Both
                    } else { ?>
                      <td><a href="mailto:<?php echo $user->user_email; ?>"><?php echo $user->user_email; ?></a></td>
                      <td><?php echo $user->phone; ?></td>
                    <?php } ?>
                    <td><?php echo $user->company; ?></td>
                    <td><?php echo get_user_option('position', $user->ID); ?></td>
                    <td><?php echo $user->industry; ?></td>
                    <td><a href="<?php echo $user->companyURL; ?>"><?php echo $user->companyURL; ?></a></td>
                  </tr>
                <?php } 
              }?>
            </tbody>
          </table>
        </div>
      <?php
      }else {
	echo '<p>Oops, our Advisory Committee needs members!</p>';
      } ?>
    </div>
  </div>
</div>
<?php get_footer(); ?>
