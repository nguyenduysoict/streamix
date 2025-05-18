/**
 * AffiliateWP JavaScript Namespace
 *
 * All affiliatewp JS utilities scripts should be attached to our namespace for easy access.
 *
 * @since 2.15.0
 */

'use strict';

/* eslint-disable no-console, no-unused-vars */
const affiliatewp = window.affiliatewp || {

	/**
	 * Check if the resource exists and attach to the affiliatewp object.
	 *
	 * @since 2.15.0
	 * @param {string} name The resource name to be attached.
	 * @param {*} resource A function, object or property. If not provided, will look into the window object.
	 * @param {boolean} destroyOriginalProperty Whether to remove from window object or not.
	 * @throws {Error} If the resource was already specified, this will throw an error.
	 */
	attach( name, resource = null, destroyOriginalProperty = true ) {

		if ( this.hasOwnProperty( name ) ) {
			throw new Error( `Resource '${name}' is already registered in the current object.` );
		}

		// Assign to the affiliatewp instance.
		this[name] = resource || this.extend( name, destroyOriginalProperty );
	},

	/**
	 * Remove a resource (object, function, property) from affiliatewp object.
	 *
	 * @since 2.15.0
	 *
	 * @param {string} name The resource name to be removed.
	 *
	 * @return {*} Return the resource or null if resource was not found.
	 */
	detach( name ) {

		if ( ! this.hasOwnProperty( name ) ) {
			return null;
		}

		const resource = this[name];

		delete this[name];

		return resource; // Return the resource, so it still can be assigned.
	},

	/**
	 * Look for a resource in window object and return this resource, optionally destroying it at the same time.
	 *
	 * @since 2.16.0
	 *
	 * @param {string} windowObjectName The name of the resource to look into the window object.
	 * @param {boolean} destroyOriginalProperty Whether to remove from window object or not.
	 *
	 * @return {null|*} The resource.
	 */
	extend( windowObjectName, destroyOriginalProperty = true ) {

		if ( ! window.hasOwnProperty( windowObjectName ) ) {

			// This would not stop execution, but it needs to be logged for debug purposes.
			console.error( `Resource '${windowObjectName}' not found in the window object.` );

			return null; // Not found in window object.
		}

		const resource = window[windowObjectName];

		if ( destroyOriginalProperty ) {
			delete window[windowObjectName];
		}

		return resource;
	},

	/**
	 * Check if a resource exists.
	 *
	 * @since 2.16.0
	 *
	 * @param {string} name The resource name.
	 *
	 * @return {boolean} Whether the resource is enabled or not.
	 */
	has( name ) {
		return this.hasOwnProperty( name );
	},

	/**
	 * Merge two objects. Similar to wp_parse_args() function.
	 *
	 * Note that only properties existing in the second parameter will be considered.
	 * If `args` contains properties that `defaults` doesn't have, those properties will be ignored.
	 *
	 * @since 2.16.0
	 *
	 * @param {Object} args Args to be merged/replace.
	 * @param {Object} defaults Default args.
	 *
	 * @return {Object} The new object.
	 */
	parseArgs( args, defaults = {} ) {

		if ( typeof args !== 'object' || typeof defaults !== 'object' ) {

			// This would not stop execution, but it needs to be logged for debug purposes.
			console.error( 'You must provide two valid objects' );

			return {}; // Not able to parse, return an empty object.
		}

		const mergeObjects = ( arg, def ) => {

			for ( const key in arg ) {

				const hasKey = arg.hasOwnProperty( key );

				// If hasKey doesn't exist, the property will be ignored, otherwise we replace in our object.
				if ( hasKey && typeof arg[key] === 'object' && typeof def[key] === 'object' ) {
					mergeObjects( arg[key], def[key] );
				} else if ( hasKey ) {
					def[key] = arg[key];
				}
			}

			return def;
		};

		return mergeObjects( args, defaults );
	}
};
