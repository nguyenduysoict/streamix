<?php
/**
 * AffiliateWP Admin Education for non-pro sites.
 *
 * Load the resources necessary to handle AffiliateWP Product Education modals for non-pro sites.
 *
 * @package     AffiliateWP
 * @subpackage  AffiliateWP\Admin\Education
 * @copyright   Copyright (c) 2023, Awesome Motive, Inc
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       2.18.0
 * @author      Darvin da Silveira <ddasilveira@awesomeomotive.com>
 */

namespace AffiliateWP\Admin\Education;

/**
 * Product education non-pro class.
 *
 * @since 2.18.0
 */
class Non_Pro extends Core {

	/**
	 * Load all hooks.
	 *
	 * @since 2.18.0
	 */
	public function init() {

		// Initiate core.
		parent::init();

		add_action( 'plugins_loaded', array( $this, 'hooks' ) );
	}

	/**
	 * Hooks.
	 *
	 * @since 2.18.0
	 */
	public function hooks() {

		// Execute core hooks.
		parent::init();

		// Allowed only on AffiliateWP pages.
		if ( ! $this->allow_load() ) {
			return;
		}

		// Use is a PRO already, no need to load this.
		if ( affwp_can_access_pro_features() ) {
			return;
		}

		add_action( 'affiliatewp_admin_education_strings', array( $this, 'append_pro_feature_upgrade_strings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueues' ) );
	}

	/**
	 * Load enqueues.
	 *
	 * @since 2.18.0
	 */
	public function enqueues() {

		// Enqueue core scripts.
		parent::init();

		// Only Personal and Plus license holders.
		affiliate_wp()->scripts->enqueue(
			'affiliatewp-admin-education-non-pro',
			array(
				'jquery-confirm',
				'affiliatewp-admin-education-core',
			),
			sprintf(
				'%1$sadmin-education-non-pro%2$s.js',
				affiliate_wp()->scripts->get_path(),
				affiliate_wp()->scripts->get_suffix(),
			)
		);
	}

	/**
	 * Update the strings to add pro feature only contents.
	 *
	 * @since 2.18.0
	 *
	 * @param array $js_strings The strings to localize.
	 *
	 * @return array
	 */
	public function append_pro_feature_upgrade_strings( array $js_strings = array() ) : array {

		$page_prefix = 'affiliate-wp-';
		$page = isset( $_GET['page'] ) ? sanitize_text_field( $_GET['page'] ) : '';

		// Check if the page starts with 'affiliate-wp-' and remove the prefix.
		if ( strpos( $page, $page_prefix ) === 0 ) {
			$upgrade_utm_medium = substr( $page, strlen( $page_prefix ) );
		} else {
			$upgrade_utm_medium = 'settings'; // default value if the page does not start with 'affiliate-wp-'
		}

		// Append 'tab' or 'action' if they exist, replacing underscores with hyphens.
		if ( isset( $_GET['tab'] ) ) {
			$tab_value = str_replace( '_', '-', sanitize_text_field( $_GET['tab'] ) );
			$upgrade_utm_medium .= '-' . $tab_value;
		} elseif ( isset( $_GET['action'] ) ) {
			$action_value = str_replace( '_', '-', sanitize_text_field( $_GET['action'] ) );
			$upgrade_utm_medium .= '-' . $action_value;
		}

		// phpcs:disable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned -- We do not want to align these.
		return array_merge_recursive(
			$js_strings,
			array(
				'upgrade' => array(
					'pro'   => array(
						'title'   => esc_html__( 'is a PRO Feature', 'affiliate-wp' ),
						'message' => '<p>' . esc_html(
							sprintf( /* translators: %s - addon name. */
								__( 'We\'re sorry, %s is not available on your plan. Please upgrade to the PRO plan to unlock all these awesome features.', 'affiliate-wp' ),
								'%name%'
							)
						) . '</p>',
						'doc'     => sprintf(
							'<a href="%1$s" target="_blank" rel="noopener noreferrer" class="already-purchased">%2$s</a>',
							esc_url( affwp_utm_link( 'https://affiliatewp.com/docs/upgrade-affiliatewp-license/', $upgrade_utm_medium, 'AP - %name%' ) ),
							esc_html__( 'Already purchased?', 'affiliate-wp' )
						),
						'button'  => esc_html__( 'Upgrade to PRO', 'affiliate-wp' ),
						'url'     => affwp_admin_upgrade_link( $upgrade_utm_medium ),
						'modal'   => $this->upgrade_modal_text(),
					),
				),
				'thanks_for_interest' => esc_html__( 'Thanks for your interest in AffiliateWP Pro!', 'affiliate-wp' ),
				'upgrade_bonus' => wpautop(
					wp_kses(
						__( '<strong>Bonus:</strong> AffiliateWP users get <span>60% off</span> regular price, automatically applied at checkout.', 'affiliate-wp' ),
						array(
							'strong' => array(),
							'span'   => array(),
						)
					)
				),
			)
		);
		// phpcs:enable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned
	}

	/**
	 * Get an upgrade modal text.
	 *
	 * @since 2.18.0
	 *
	 * @return string
	 */
	private function upgrade_modal_text() : string {

		return '<p>' .
			sprintf(
				wp_kses( /* translators: %s - affiliatewp.com contact page URL. */
					__( 'Thank you for considering upgrading. If you have any questions, please <a href="%s" target="_blank" rel="noopener noreferrer">let us know</a>.', 'affiliate-wp' ),
					array(
						'a' => array(
							'href'   => array(),
							'target' => array(),
							'rel'    => array(),
						),
					)
				),
				esc_url(
					affwp_utm_link(
						'https://affiliatewp.com/contact/',
						'Upgrade Follow Up Modal',
						'Contact Support'
					)
				)
			) .
			'</p>' .
			'<p>' .
			wp_kses(
				__( 'After upgrading, your license key will remain the same.<br>You may need to do a quick refresh to unlock your new addons. In your WordPress admin, go to <strong>AffiliateWP &raquo; Settings</strong>. If you don\'t see your updated plan, click <em>refresh</em>.', 'affiliate-wp' ),
				array(
					'strong' => array(),
					'br'     => array(),
					'em'     => array(),
				)
			) .
			'</p>' .
			'<p>' .
			sprintf(
				wp_kses( /* translators: %s - WPForms.com upgrade license docs page URL. */
					__( 'Check out <a href="%s" target="_blank" rel="noopener noreferrer">our documentation</a> for step-by-step instructions.', 'affiliate-wp' ),
					array(
						'a' => array(
							'href'   => array(),
							'target' => array(),
							'rel'    => array(),
						),
					)
				),
				'https://affiliatewp.com/docs/upgrade-affiliatewp-license/'
			) .
			'</p>';
	}
}

// Initiate.
( new Non_Pro() )->init();
