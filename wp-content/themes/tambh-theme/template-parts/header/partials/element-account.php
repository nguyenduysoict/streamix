<?php
/**
 * Account element.
 *
 * @package          Flatsome\Templates
 * @flatsome-version 3.16.0
 */

$icon_style = get_theme_mod('account_icon_style');
?>
<?php if(is_woocommerce_activated()){ ?>
<li class="account-item has-icon
  <?php if(is_account_page()) echo ' active'; ?>
  <?php if ( is_user_logged_in() ) { ?> has-dropdown<?php } ?>"
>
<?php if($icon_style && $icon_style !== 'image' && $icon_style !== 'plain') echo '<div class="header-button">'; ?>

<?php if ( is_user_logged_in() ) { ?>
<a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" class="account-link account-login
  <?php if($icon_style && $icon_style !== 'image') echo get_flatsome_icon_class($icon_style, 'small'); ?>"
  title="<?php _e('My account', 'woocommerce'); ?>">

	<?php if ( get_theme_mod( 'header_account_title', 1 ) ) { ?>
		<span class="header-account-title">
		<?php
		if ( get_theme_mod( 'header_account_username' ) ) {
			$current_user = wp_get_current_user();
			echo apply_filters( 'flatsome_header_account_username', esc_html( $current_user->display_name ) );
		} else {
			esc_html_e( 'My account', 'woocommerce' );
		}
		?>
		</span>
	<?php } ?>

  <?php if($icon_style == 'image'){
    echo '<i class="image-icon circle">'.get_avatar(get_current_user_id()).'</i>';
   } else  if($icon_style){
    echo get_flatsome_icon('icon-user');
   } ?>

</a>

<?php } else { ?>
<?php } ?>

<?php if($icon_style && $icon_style !== 'image' && $icon_style !== 'plain') echo '</div>'; ?>

<?php
// Show Dropdown for logged in users
if ( is_user_logged_in() ) { ?>
<ul class="nav-dropdown  <?php flatsome_dropdown_classes(); ?>">
    <?php wc_get_template('myaccount/account-links.php'); ?>
</ul>
<?php } ?>

</li>
<?php } else {
	fl_header_element_error( 'woocommerce' );
}
?>
