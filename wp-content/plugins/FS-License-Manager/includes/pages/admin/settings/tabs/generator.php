<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' ); ?>
<form method="post" action="options.php">

	<?php
	settings_fields( 'fslm_lkg_option_group' );

	$prefix              = esc_attr( get_option( 'fslm_prefix', '' ) );
	$chunks_number       = esc_attr( get_option( 'fslm_chunks_number', '4' ) );
	$chunks_length       = esc_attr( get_option( 'fslm_chunks_length', '4' ) );
	$suffix              = esc_attr( get_option( 'fslm_suffix', '' ) );
	$max_instance_number = esc_attr( get_option( 'fslm_max_instance_number', '1' ) );
	$valid               = esc_attr( get_option( 'fslm_valid', '0' ) );
	$active              = esc_attr( get_option( 'fslm_active', '0' ) );

	?>

	<h3><?php echo __( 'License Generator Default Settings', 'fslm' ); ?>:</h3>

	<div class="input-box">
		<div class="label">
			<span><?php echo __( 'Prefix', 'fslm' ); ?></span>
		</div>
		<div class="input">
			<input class="input-field" name="fslm_prefix" id="fslm_prefix" type="text" value="<?php echo $prefix ?>">
		</div>
	</div>

	<div class="input-box">
		<div class="label">
			<span><?php echo __( 'Number of chunks', 'fslm' ); ?></span>
		</div>
		<div class="input">
			<input class="input-field" name="fslm_chunks_number" id="fslm_chunks_number" type="text"
			       value="<?php echo $chunks_number ?>">
		</div>
	</div>

	<div class="input-box">
		<div class="label">
			<span><?php echo __( 'Chunk length', 'fslm' ); ?></span>
		</div>
		<div class="input">
			<input class="input-field" name="fslm_chunks_length" id="fslm_chunks_length" type="text"
			       value="<?php echo $chunks_length ?>">
		</div>
	</div>

	<div class="input-box">
		<div class="label">
			<span><?php echo __( 'Suffix', 'fslm' ); ?></span>
		</div>
		<div class="input">
			<input class="input-field" name="fslm_suffix" id="fslm_suffix" type="text" value="<?php echo $suffix ?>">
		</div>
	</div>

	<div class="input-box">
		<div class="label">
			<span><?php echo __( 'Activations', 'fslm' ); ?></span>
		</div>
		<div class="input">
			<input class="input-field" name="fslm_max_instance_number" id="fslm_max_instance_number" type="number"
			       min="1" value="<?php echo $max_instance_number ?>">
		</div>
	</div>

	<div class="input-box">
		<div class="label">
			<span><?php echo __( 'Validity (Days)', 'fslm' ); ?></span>
		</div>
		<div class="input">
			<input class="input-field" name="fslm_valid" id="fslm_valid" type="number" min="0"
			       value="<?php echo $valid ?>">
		</div>
	</div>


	<div class="input-box">
		<div class="label">
			<span><?php echo __( 'Active', 'fslm' ); ?></span>
		</div>
		<div class="input">
			<select class="input-field" name="fslm_active">

				<?php

				$noSelected  = $active == '0' ? 'selected' : '';
				$yesSelected = $active == '1' ? 'selected' : '';

				?>

				<option value="0" <?php echo $noSelected ?>><?php echo __( 'No', 'fslm' ); ?></option>
				<option value="1" <?php echo $yesSelected ?>><?php echo __( 'Yes', 'fslm' ); ?></option>
			</select>
		</div>
	</div>

	<?php submit_button(); ?>
</form>