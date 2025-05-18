jQuery( document ).ready( function() {

	( ( $ ) => {
		// Handles integration with AffiliateWP within the Elementor editor.
		const AffiliateWPIntegration = {
			fields: AffiliateWPElementor.fields,

			/**
			 * Retrieves the integration name.
			 *
			 * @return {string} The name of the integration.
			 */
			getName() {
				return 'affiliatewp';
			},

			/**
			 * Event handler for when an element setting is changed.
			 *
			 * @param {string} setting The setting that was changed.
			 * @return {void}
			 */
			onElementChange( setting ) {
				if ( setting.includes( 'affiliatewp' ) ) {
					this.updateFieldsMap();
				}
			},

			/**
			 * Event handler for when a section becomes active.
			 *
			 * @return {void}
			 */
			onSectionActive() {
				this.updateFieldsMap();
			},

			/**
			 * Updates the fields map in the Elementor editor.
			 *
			 * @return {void}
			 */
			updateFieldsMap() {
				this.getEditorControlView( 'affiliatewp_fields_map' ).updateMap();
			},
		};

		// Fields Map functionality.
		const FieldsMap = {

			/**
			 * Called before the fields map is rendered.
			 *
			 * @return {void}
			 */
			onBeforeRender() {
				this.$el.hide();
			},

			/**
			 * Updates the field mappings based on current settings.
			 *
			 * @return {void}
			 */
			updateMap() {
				const savedMapObject = {};

				this.collection.each( ( model ) => {
					savedMapObject[ model.get( 'remote_id' ) ] = model.get( 'local_id' );
				} );

				this.collection.reset();

				const fieldOptions = this.getFieldOptions();
				fieldOptions.forEach( ( option, index ) => {
					const model = {
						remote_id: option.remote_id,
						remote_label: option.remote_label,
						remote_type: option.remote_type ? option.remote_type : '',
						remote_required: option.remote_required ? option.remote_required : false,
						local_id: savedMapObject[ option.remote_id ] || '',
					};

					this.collection.add( model );
				} );

				this.render();
			},

			/**
			 * Retrieves field options for the form.
			 *
			 * @returns {Array} An array of field options.
			 */
			getFieldOptions() {
				return elementorPro.modules.forms.affiliatewp.fields;
			},

			/**
			 * Called when the fields map is rendered.
			 *
			 * @return {void}
			 */
			onRender() {
				this.children.each( ( view, index ) => {
					const fields = this.elementSettingsModel.get( 'form_fields' ).models;

					let options = {};

					// Add '-- None --' option.
					options[''] = `- ${ elementor.translate( 'None' ) } -`;

					fields.forEach( ( field ) => {
						let remoteType = view.model.get( 'remote_type' ); // email | text

						// If it's an email field, only show email fields from the form.
						if ( 'text' !== remoteType && remoteType !== field.get( 'field_type' ) ) {
							return;
						}

						const fieldId = field.get( 'custom_id' );
						const fieldLabel = field.get( 'field_label' );
						options[ fieldId ] = fieldLabel;
					} );

					const localFieldsControl = view.children.last();
					let label = view.model.get( 'remote_label' );

					// Add required indicator to mapped fields that are required.
					if ( view.model.get( 'remote_required' ) ) {
						label += '<span class="elementor-required">*</span>';
					}

					localFieldsControl.model.set( 'label', label );
					localFieldsControl.model.set( 'options', options );
					localFieldsControl.render();

					view.$el.find( '.elementor-repeater-row-tools' ).hide();
					view.$el.find( '.elementor-repeater-row-controls' )
						.removeClass( 'elementor-repeater-row-controls' )
						.find( '.elementor-control' )
						.css( {
							padding: '10px 0',
						} );
				} );

				if ( this.children.length ) {
					this.$el.show();
				}

				this.$el.find( '.elementor-button-wrapper' ).remove();
			},
		};

		// Initializes the custom control views and listeners for the Elementor editor.
		const init = () => {
			elementor.addControlView( 'affiliatewp_fields_map',
				elementor.modules.controls.Fields_map.extend( FieldsMap ) );

			elementorPro.modules.forms.affiliatewp = {
				...elementorPro.modules.forms.activecampaign,
				...AffiliateWPIntegration,
			};

			elementorPro.modules.forms.affiliatewp.addSectionListener( 'section_affiliatewp', () => {
				elementorPro.modules.forms.affiliatewp.onSectionActive();
			} );
		};

		init();

	} )( jQuery );
} );
