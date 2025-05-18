/* global AffiliateWPEducation, affiliatewp_education */
/**
 * AffiliateWP Education for non-pro sites.
 *
 * @since 2.18.0
 */

'use strict';

AffiliateWPEducation.nonPro = AffiliateWPEducation.nonPro || ( function( document, window, $ ) {

	/**
	 * Public functions and properties.
	 *
	 * @since 2.18.0
	 *
	 * @type {Object}
	 */
	const app = {

		/**
		 * Start the engine.
		 *
		 * @since 2.18.0
		 */
		init() {

			$( app.ready );
		},

		/**
		 * Document ready.
		 *
		 * @since 2.18.0
		 */
		ready() {

			app.events();
		},

		/**
		 * Register JS events.
		 *
		 * @since 2.18.0
		 */
		events() {

			app.trackClickEvents();
			app.trackSelect2SelectingEvent();
		},

		/**
		 * Handle the selecting event from Select2.
		 *
		 * @since 2.18.0
		 */
		trackSelect2SelectingEvent() {

			$( document ).on( 'select2:selecting', function( e ) {

				// Ensure the event target is a select2 menu.
				if (
					$( e.target ).hasClass( 'select2-hidden-accessible' ) &&
					'undefined' !== typeof e.params.args.data.element
				) {

					const selectedOptionClass = e.params.args.data.element.className;

					if ( selectedOptionClass && selectedOptionClass.includes( 'affwp-education-modal' ) ) {

						// Prevent feature from being selected.
						e.preventDefault();
						e.stopImmediatePropagation();

						app.showUpgradeModal( e.params.args.data.element );
					}
				}
			});
		},

		/**
		 * Handle the click events.
		 *
		 * @since 2.18.0
		 */
		trackClickEvents() {

			$( document ).on( 'click', '.affwp-education-modal', function( e ) {

				// Prevent any possible action.
				e.preventDefault();
				e.stopImmediatePropagation();

				app.showUpgradeModal( e.target );
			} );
		},

		/**
		 * Show the upgrade modal based on an HTML element.
		 *
		 * @since 2.18.0
		 *
		 * @param {Element} targetEl
		 */
		showUpgradeModal( targetEl ) {

			app.upgradeModal(
				AffiliateWPEducation.core.getNameValue( targetEl ),
				AffiliateWPEducation.core.getUTMContentValue( targetEl ),
				'pro',
				''
			);
		},

		/**
		 * Upgrade modal.
		 *
		 * @since 2.18.0
		 *
		 * @param {string} feature    Feature name.
		 * @param {string} utmContent UTM content.
		 * @param {string} type       Feature license type: pro or elite.
		 * @param {string} video      Feature video URL.
		 */
		upgradeModal( feature, utmContent, type, video ) {

			// Provide a default value.
			if ( typeof type === 'undefined' || type.length === 0 ) {
				type = 'pro';
			}

			// Make sure we received only supported type.
			if ( $.inArray( type, [ 'pro', 'ultimate' ] ) < 0 ) {
				return;
			}

			const message      = affiliatewp_education.upgrade[ type ].message.replace( /%name%/g, feature ),
				isVideoModal = ! _.isEmpty( video ),
				modalWidth   = AffiliateWPEducation.core.getUpgradeModalWidth( isVideoModal );

			const modal = $.alert( {
				backgroundDismiss: true,
				title            : feature + ' ' + affiliatewp_education.upgrade[type].title,
				icon             : 'fa fa-lock',
				content          : message,
				boxWidth         : modalWidth,
				useBootstrap     : false,
				theme            : 'modern,affiliatewp-education',
				closeIcon        : true,
				onOpenBefore() {

					if ( isVideoModal ) {
						this.$el.addClass( 'has-video' );
					}

					const videoHtml = isVideoModal ? '<iframe src="' + video + '" class="feature-video" frameborder="0" allowfullscreen="" width="475" height="267"></iframe>' : '';

					this.$btnc.after( '<div class="discount-note">' + affiliatewp_education.upgrade_bonus + '</div>' );
					this.$btnc.after( affiliatewp_education.upgrade[type].doc.replace( /%25name%25/g, feature ) );
					this.$btnc.after( videoHtml );

					this.$body.find( '.jconfirm-content' ).addClass( 'lite-upgrade' );
				},
				buttons : {
					confirm: {
						text    : affiliatewp_education.upgrade[type].button,
						btnClass: 'btn-confirm',
						keys    : [ 'enter' ],
						action() {

							window.open( AffiliateWPEducation.core.getUpgradeURL( utmContent, type ), '_blank' );
							app.upgradeModalThankYou( type );
						},
					},
				},
			} );

			$( window ).on( 'resize', function() {

				const modalWidth = AffiliateWPEducation.core.getUpgradeModalWidth( isVideoModal );

				if ( modal.isOpen() ) {
					modal.setBoxWidth( modalWidth );
				}
			} );
		},

		/**
		 * Upgrade modal second state.
		 *
		 * @since 2.18.0
		 *
		 * @param {string} type Feature license type: pro or ultimate.
		 */
		upgradeModalThankYou( type ) {

			$.alert( {
				title   : affiliatewp_education.thanks_for_interest,
				content : affiliatewp_education.upgrade[type].modal,
				icon    : 'fa fa-info-circle',
				boxWidth: '565px',
				useBootstrap : false,
				theme : 'modern,affiliatewp-education',
				buttons : {
					confirm: {
						text    : affiliatewp_education.ok,
						btnClass: 'btn-confirm',
						keys    : [ 'enter' ],
					},
				},
			} );
		},
	};

	// Provide access to public functions/properties.
	return app;

}( document, window, jQuery ) );

// Initialize.
AffiliateWPEducation.nonPro.init();
