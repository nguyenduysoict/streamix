<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' ); ?>
<form method="post" action="options.php">

	<?php
	settings_fields( 'fslm_extra_option_group' );
	do_settings_sections( 'fslm_extra_option_group' );
	?>

	<h3><?php echo __( 'Extra Settings', 'fslm' ); ?>:</h3>

	<div class="input-box">
		<div class="label">
			<span><?php echo __( 'Delete Plugins License Keys Tables Before Deactivating The Plugin', 'fslm' ); ?></span>
		</div>
		<div class="input">
			<input type="checkbox"
			       name="fslm_delete_lk_db_tables"
				<?php echo esc_attr( get_option( 'fslm_delete_lk_db_tables', '' ) ) == 'on' ? 'checked' : ''; ?>>
		</div>
	</div>

	<div class="input-box">
		<div class="label">
            <span><?php echo __( 'Delete Plugins Generator Rules Tables Before Deactivating The Plugin',
		            'fslm' ); ?></span>
		</div>
		<div class="input">
			<input type="checkbox"
			       name="fslm_delete_gr_db_tables"
				<?php echo esc_attr( get_option( 'fslm_delete_gr_db_tables', '' ) ) == 'on' ? 'checked' : ''; ?>>
		</div>
	</div>

	<div class="input-box">
		<div class="label">
            <span><?php echo __( 'Delete Plugins Licensed Products Tables Before Deactivating The Plugin',
		            'fslm' ); ?></span>
		</div>
		<div class="input">
			<input type="checkbox"
			       name="fslm_delete_lp_db_tables"
				<?php echo esc_attr( get_option( 'fslm_delete_lp_db_tables', '' ) ) == 'on' ? 'checked' : ''; ?>>
		</div>
	</div>

	<div class="input-box">
		<div class="label">
			<span><?php echo __( 'Enable Debug Logging', 'fslm' ); ?></span>
		</div>
		<div class="input">
			<input type="checkbox" name="fslm_debug_enabled"
				<?php echo esc_attr( get_option( 'fslm_debug_enabled', 'off' ) ) == 'on' ? 'checked' : ''; ?>>
		</div>
	</div>

	<div class="input-box">
		<hr>
		<div class="label">
			<span><?php echo __( 'Use Alternative License Key Delivery Method', 'fslm' ); ?></span>
		</div>
		<div class="input">
			<input type="checkbox" name="fslm_alt_delivery_method"
				<?php echo esc_attr( get_option( 'fslm_alt_delivery_method', 'off' ) ) == 'on' ? 'checked' : ''; ?>>
		</div>

		<blockquote class="text-danger error">
			<?php esc_html_e( 'Do not use it if you sell license keys that can be delivered multiple times.', 'fslm' ); ?>
			<br>
			<?php esc_html_e( 'Do not use if you are using the license key generator feature.', 'fslm' ); ?><br>
		</blockquote>
		<hr>
	</div>

	<?php submit_button(); ?>

</form>

<form method="post" action="options.php">
	<?php
	settings_fields( 'fslm_update_option_group' );
	do_settings_sections( 'fslm_update_option_group' );
	?>

	<input type="hidden" name="fslm_db_version" value="0">

	<?php submit_button( __( 'Run database update script', 'fslm' ) ); ?>

</form>

<br>

<h3><?php echo __( 'Delete product license keys', 'fslm' ); ?>:</h3>

<form method="post" action="<?php echo admin_url( 'admin.php?action=delete_product_license_keys' ) ?> ">

	<div class="input-box">


		<?php if ( isset( $_GET['dc'] ) ) { ?>
			<div class="updated"><p><?php echo $_GET['dc'] . ' ' . __( 'license keys got deleted.', 'fslm' ) ?></p>
			</div>
		<?php } ?>

		<div class="label">
			<span><?php echo __( 'Product', 'fslm' ); ?></span>
		</div>
		<div class="input">
			<select class="input-field elk_product_id" id="elk_product_id" name="product_id">
				<option value="0">Select Product</option>
				<?php

				global $wpdb;

				// A sql query to return all post titles
				$results = $wpdb->get_results( $wpdb->prepare( "SELECT 
                                                                    ID, 
                                                                    post_title 
                                                                FROM 
                                                                    {$wpdb->posts} 
                                                                WHERE 
                                                                    post_type = %s 
                                                                    AND post_status != 'auto-draft'",
					"product" ), ARRAY_A );


				foreach ( $results as $index => $post ) {
					echo '<option value="' . $post['ID'] . '">' . $post['ID'] . ' - ' . $post['post_title'] . '</option>';
				}

				?>

			</select>
		</div>
	</div>


	<input type="submit" name="submit" id="submit" class="button button-primary"
	       value="<?php echo __( 'Delete', 'fslm' ); ?>">
	<blockquote>
		<p class="description">
			<?php echo __( 'All the license keys assigned to that product will be deleted.', 'fslm' ) ?>
		</p>
	</blockquote>

</form>
