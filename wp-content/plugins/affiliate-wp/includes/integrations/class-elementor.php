<?php
/**
 * Integrations: Elementor
 *
 * This file contains the class and methods necessary for integrating AffiliateWP
 * with the Elementor page builder. It includes functions for form handling,
 * affiliate registration, and additional Elementor controls specific to AffiliateWP.
 *
 * @package    AffiliateWP
 * @subpackage Integrations
 * @copyright  Copyright (c) 2023, Sandhills Development, LLC
 * @since      2.19.0
 */

/**
 * Implements an integration for Elementor.
 *
 * @since 2.19.0
 *
 * @see Affiliate_WP_Base
 */
class Affiliate_WP_Elementor extends Affiliate_WP_Base {

	/**
	 * Get things started
	 *
	 * @access  public
	 *
	 * @since 2.19.0
	*/
	public function init(): void {
		add_filter( 'elementor_pro/forms/render/item', array( $this, 'disable_logged_in_fields' ), 10, 3 );
		add_action( 'elementor_pro/forms/actions/register', array( $this, 'add_new_action' ) );
		add_action( 'elementor/controls/register',  array( $this, 'register_controls' ) );
		add_action( 'elementor_pro/forms/new_record', array( $this, 'affiliate_email' ), 10, 2 );
		add_action( 'affwp_review_affiliate_end', array( $this, 'display_registration_data' ) );
		add_action( 'affwp_edit_affiliate_end', array( $this, 'display_registration_data' ) );
	}

	/**
	 * Disable logged in fields.
	 *
	 * @access public
	 * @since 2.19.0
	 * @param array $item       The field value.
	 * @param int   $item_index The field index.
	 * @param Form  $form An instance of the form.
	 */
	public function disable_logged_in_fields( $item, $item_index, $form ): array {

		$settings = $form->get_settings_for_display();

		// Return early if this form isn't an affiliate registration form.
		if ( ! ( ! empty( $settings['affiliate_registration'] ) && 'yes' === $settings['affiliate_registration'] ) ) {
			return $item;
		}

		// Return early if user is not logged in or if in admin area.
		if ( ! is_user_logged_in() || is_admin() ) {
			return $item;
		}

		// Get logged in WP user details.
		$current_user = wp_get_current_user();

		$user_attributes = array(
			'user_login' => $current_user->user_login,
			'name'       => $current_user->display_name,
			'user_email' => $current_user->user_email,
			'user_url'   => $current_user->user_url,
		);

		foreach ( $form->get_settings( 'affiliatewp_fields_map' ) as $mapped_field ) {

			if ( $mapped_field['local_id'] !== $item['custom_id'] ) {
				continue;
			}

			$attributes = 'user_url' === $mapped_field['remote_id'] ? array() : array(
				'readonly' => 'readonly',
				'style'    => 'background-color: #f7f7f7; opacity: 0.5; cursor: not-allowed;'
			);

			if ( isset( $user_attributes[ $mapped_field['remote_id'] ] ) ) {
				$field_value = $user_attributes[ $mapped_field['remote_id'] ];

				$attributes['value'] = $field_value;

				if ( ! empty( $field_value ) ) {
					$form->add_render_attribute(
						"input{$item_index}",
						$attributes
					);
				}
			}

			if ( 'user_pass' === $mapped_field['remote_id'] ) {
				$attributes['value'] = '************'; // Set dummy value to bypass validation (if required)

				$form->add_render_attribute(
					"input{$item_index}",
					$attributes
				);
			}

			break;
		}

		return $item;
	}

	/**
	 * Display registration data on the affiliate review/edit screen.
	 *
	 * @access public
	 *
	 * @since 2.19.0
	 *
	 * @param int $affiliate_id Affiliate ID.
	 */
	public function display_registration_data( $affiliate ): void {
		// Entry ID will only exist for registrations while WPForms Pro was installed.
		$field_data = affwp_get_affiliate_meta( $affiliate->affiliate_id, 'elementor_affiliate_registration_data', true );

		if ( empty( $field_data ) ) {
			return;
		}

		?>
		<tr>
			<th scope="row">
				<?php esc_html_e( 'Additional Application Info', 'affiliate-wp' ); ?>
			</th>
			<td>
				<table class="widefat striped">
					<tbody>
					<?php if ( ! empty( $field_data ) ) : ?>
						<?php foreach ( $field_data as $field_id => $field ) : ?>

							<?php $field_value = isset( $field['value'] ) ? wp_strip_all_tags( $field['value'] ) : ''; ?>

							<tr class="form-row">
								<td>
									<?php if ( ! empty( $field['title'] ) ) : ?>
										<?php esc_html_e( wp_strip_all_tags( $field['title'] ) ); ?>
									<?php else : ?>
										<?php esc_html_e( wp_strip_all_tags( $field_id ) ); ?>
									<?php endif; ?>
								</td>
								<td>
									<?php if ( 'acceptance' === $field['type'] ) : ?>
										<?php esc_html_e( 'Yes', 'affiliate-wp' ); ?>
									<?php elseif ( ! ( is_string( $field_value ) && $field_value === '' ) ) : ?>
										<?php echo wp_kses_post( nl2br( make_clickable( $field_value ) ) ); ?>
									<?php else : ?>
										<?php esc_html_e( 'Empty', 'affiliate-wp' ); ?>
									<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php endif ?>
					</tbody>
				</table>
			</td>
		</tr>
		<?php
	}

	/**
	 * Register new field mapping control.
	 *
	 * @since 2.19.0
	 * @param $controls Controls_Manager
	 *
	 * @return void
	 */
	public function register_controls( $controls ): void {
		include_once( __DIR__ .  '/elementor/field-mapping.php' );
		$controls->register( new Field_Mapping() );
	}

	/**
	 * Add new form action after form submission.
	 *
	 * @since 2.19.0
	 *
	 * @param ElementorPro\Modules\Forms\Registrars\Form_Actions_Registrar $form_actions_registrar
	 * @return void
	 */
	public function add_new_action( $form_actions_registrar ): void {
		include_once( __DIR__ .  '/elementor/form-actions/affiliatewp.php' );
		$form_actions_registrar->register( new \AffiliateWP_Action_After_Submit() );
	}

	/**
	 * Set up a new custom action to email the affiliate after registration.
	 *
	 * @since 2.19.0
	 *
	 * @param ElementorPro\Modules\Forms\Classes\Form_Record $record
	 * @param ElementorPro\Modules\Forms\Classes\Ajax_Handler $handler
	 *
	 * @return void
	 */
	public function affiliate_email( $record, $handler ): void {
		include_once( __DIR__ . '/elementor/form-actions/affiliate-email.php' );

		$affiliate_registration = $record->get_form_settings( 'affiliate_registration' );
		if ( empty( $affiliate_registration ) || 'yes' !== $affiliate_registration ) {
			return;
		}

		$affiliate_email = new Affiliate_Email_After_Registration();
		$affiliate_email->run( $record, $handler );
	}

	/**
	 * Runs the check necessary to confirm this plugin is active.
	 *
	 * @since 2.19.0
	 *
	 * @return bool True if the plugin is active, false otherwise.
	 */
	function plugin_is_active(): bool {
		return class_exists( 'Elementor\Plugin' );
	}

}
