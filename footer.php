<?php
/**
 * The template for displaying the footer
 *
 * Displays from <div class="footer"> to </html>
 *
 * @package WordPress
 * @subpackage WRDSB
 */
?>
      <div class="footer" id="contact">
        <div class="container">
          <div class="row">
            <div class="col-sm-6 col-md-3">
            <!-- automate address -->
            <h1>WRDSB OYAP Advisory Committee</h1>
            <address>51 Ardelt Avenue<br />
            Kitchener ON  N2C 2R5<br />
            Phone: 519-570-0003</address>
            </div>
            <div class="col-sm-6 col-md-3">
              <?php if ( is_active_sidebar( 'footer-left' ) ) : ?>
                <?php dynamic_sidebar( 'footer-left' ); ?>
              <?php endif; ?>
            </div>
            <div class="col-sm-6 col-md-3">
              <?php if ( is_active_sidebar( 'footer-centre' ) ) : ?>
                <?php dynamic_sidebar( 'footer-centre' ); ?>
              <?php endif; ?>
            </div>
            <div class="col-sm-12 col-md-3">
              <?php if ( is_active_sidebar( 'footer-right' ) ) : ?>
                <?php dynamic_sidebar( 'footer-right' ); ?>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
          <div class="container" id="loginbar">
              <p class="copyright" style="text-align: center;">
            	<?php /* if ( is_user_logged_in() ) 
            	{
            		wp_loginout();
            	} 
            	else 
            	{ */ ?>
            		<a href="<?php echo site_url(); echo '/wp-login.php';?>">Log into <?php echo get_bloginfo('name'); ?></a>
            	<?php /*}? */ ?> 
              <br />&copy; OYAP <?php echo date("Y");?></p>
          </div>

    <?php wp_footer(); ?>
    </body>
    </html>
