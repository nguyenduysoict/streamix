<?php
/**
 * SVG Icons.
 *
 * @package     AffiliateWP
 * @subpackage  Utils
 * @copyright   Copyright (c) 2023, Awesome Motive, Inc.
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       2.16.0
 * @author      Darvin da Silveira <ddasilveira@awesomeomotive.com>
 */

namespace AffiliateWP\Utils;

use DOMDocument;
use Exception;

/**
 * Handle icons rendering.
 */
class Icons {

	/**
	 * SVG icons KSES.
	 *
	 * @since 2.16.0
	 *
	 * @var array|string[] $kses List of allowed tags and attributes.
	 */
	private static array $kses = array(
		'svg'      => array(
			'viewbox'      => true,
			'class'        => true,
			'stroke-width' => true,
			'height'       => true,
			'width'        => true,
			'd'            => true,
			'xmlns'        => true,
			'fill'         => true,
		),
		'path'     => array(
			'stroke'          => true,
			'stroke-width'    => true,
			'stroke-linecap'  => true,
			'stroke-linejoin' => true,
			'd'               => true,
			'fill'            => true,

		),
		'g'        => array(
			'clip-path'    => true,
			'stroke-width' => true,
		),
		'defs'     => array(),
		'clippath' => array(
			'id' => true,
		),
		'rect'     => array(
			'width'        => true,
			'height'       => true,
			'fill'         => true,
			'stroke-width' => true,
		),
	);

	/**
	 * SVG icons collection.
	 *
	 * Notes for devs: all icons should have width and height of 20, viewbox should be the nearest possible to the width
	 * and height sizes, also, set all stroke and fill parameters for <path> tags to `currentColor`.
	 *
	 * @since 2.16.0
	 *
	 * @var array|string[] $icons SVG icons array.
	 *                            The key represents the name of the icon and the value represents the corresponding SVG.
	 */
	private static array $icons = array(
		'copy'     => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="-0.25 -0.25 24.5 24.5" stroke-width="2" height="20" width="20" fill="none"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" d="M16.75 4.5V1.75C16.75 1.19772 16.3023 0.75 15.75 0.75H1.75C1.19772 0.75 0.75 1.19771 0.75 1.75V15.75C0.75 16.3023 1.19772 16.75 1.75 16.75H4.5"></path><path stroke="currentColor" stroke-linejoin="round" d="M7.25 8.25C7.25 7.69771 7.69772 7.25 8.25 7.25H22.25C22.8023 7.25 23.25 7.69772 23.25 8.25V22.25C23.25 22.8023 22.8023 23.25 22.25 23.25H8.25C7.69771 23.25 7.25 22.8023 7.25 22.25V8.25Z"></path></svg>',
		'edit'     => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="-0.25 -0.25 24.5 24.5" stroke-width="2" height="20" width="20"><path d="M13.045,14.136l-3.712.531.53-3.713,9.546-9.546A2.25,2.25,0,0,1,22.591,4.59Z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"></path><path d="M18.348 2.469L21.53 5.651" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"></path><path d="M18.75,14.25v7.5a1.5,1.5,0,0,1-1.5,1.5h-15a1.5,1.5,0,0,1-1.5-1.5v-15a1.5,1.5,0,0,1,1.5-1.5h7.5" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"></path></svg>',
		'list'     => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" width="20" height="20" fill="none"><path d="M2.04082 14.9004H17.9592" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M2.04082 10.0024H17.9592" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M2.04082 5.10449H17.9592" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
		'grid'     => '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2.16667 1H6.83333C6.83333 1 8 1 8 2.16667V6.83333C8 6.83333 8 8 6.83333 8H2.16667C2.16667 8 1 8 1 6.83333V2.16667C1 2.16667 1 1 2.16667 1Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M2.16667 12H6.83333C6.83333 12 8 12 8 13.1667V17.8333C8 17.8333 8 19 6.83333 19H2.16667C2.16667 19 1 19 1 17.8333V13.1667C1 13.1667 1 12 2.16667 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M13.1667 1H17.8333C17.8333 1 19 1 19 2.16667V6.83333C19 6.83333 19 8 17.8333 8H13.1667C13.1667 8 12 8 12 6.83333V2.16667C12 2.16667 12 1 13.1667 1Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M13.1667 12H17.8333C17.8333 12 19 12 19 13.1667V17.8333C19 17.8333 19 19 17.8333 19H13.1667C13.1667 19 12 19 12 17.8333V13.1667C12 13.1667 12 12 13.1667 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
		'download' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" width="20" height="20" fill="none"><g clip-path="url(#clip0_4_36)"><path d="M10.0008 3.26532V13.0612" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M6.32735 9.38776L10.0008 13.0612L13.6743 9.38776" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M19.1845 13.0612V14.2857C19.1845 14.9352 18.9265 15.5581 18.4672 16.0174C18.0079 16.4767 17.385 16.7347 16.7355 16.7347H3.26613C2.61662 16.7347 1.99371 16.4767 1.53444 16.0174C1.07516 15.5581 0.817146 14.9352 0.817146 14.2857V13.0612" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></g><defs><clipPath id="clip0_4_36"><rect width="20" height="20" fill="white"/></clipPath></defs></svg>',
		'share'    => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" width="20" height="20" fill="none"><g clip-path="url(#clip0_4_29)"><path d="M14.2857 6.93878H15.5102C15.835 6.93878 16.1464 7.06779 16.376 7.29743C16.6057 7.52706 16.7347 7.83852 16.7347 8.16327V17.9592C16.7347 18.2839 16.6057 18.5954 16.376 18.825C16.1464 19.0547 15.835 19.1837 15.5102 19.1837H4.48979C4.16504 19.1837 3.85359 19.0547 3.62395 18.825C3.39431 18.5954 3.2653 18.2839 3.2653 17.9592V8.16327C3.2653 7.83852 3.39431 7.52706 3.62395 7.29743C3.85359 7.06779 4.16504 6.93878 4.48979 6.93878H5.71428" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M10 0.816315V9.38774" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M6.93877 3.87754L10 0.816315L13.0612 3.87754" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></g><defs><clipPath id="clip0_4_29"><rect width="20" height="20" fill="white"/></clipPath></defs></svg',
		'twitter'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" width="20" height="20" fill="none"><path d="M17.9525 6.04571C17.9647 6.22203 17.9647 6.39834 17.9647 6.57628C17.9647 11.9982 13.8371 18.2513 6.28966 18.2513V18.248C4.06013 18.2513 1.8769 17.6126 0 16.4085C0.324193 16.4475 0.65001 16.467 0.97664 16.4678C2.82429 16.4694 4.61913 15.8495 6.07272 14.7079C4.31688 14.6746 2.77717 13.5298 2.23928 11.8584C2.85436 11.9771 3.48812 11.9527 4.09181 11.7877C2.17753 11.401 0.800325 9.71908 0.800325 7.7658C0.800325 7.74793 0.800325 7.73087 0.800325 7.7138C1.37071 8.0315 2.00934 8.20781 2.6626 8.22731C0.859638 7.02235 0.30388 4.62382 1.39265 2.74854C3.47593 5.31202 6.54966 6.87041 9.84928 7.03535C9.51859 5.61021 9.97034 4.11681 11.0364 3.11498C12.689 1.56146 15.2882 1.64108 16.8418 3.29292C17.7607 3.11173 18.6415 2.77454 19.4475 2.29678C19.1412 3.24661 18.5001 4.05343 17.6437 4.56613C18.457 4.47025 19.2517 4.2525 20 3.92018C19.4491 4.74569 18.7552 5.46477 17.9525 6.04571Z" fill="currentColor"/></svg>',
		'facebook' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" width="20" height="20" fill="none"><path d="M20 10.0607C20 4.504 15.5233 0 10 0C4.47667 0 0 4.504 0 10.0607C0 15.0833 3.656 19.2453 8.43733 20V12.9693H5.89867V10.06H8.43733V7.844C8.43733 5.32267 9.93 3.92933 12.2147 3.92933C13.308 3.92933 14.4533 4.126 14.4533 4.126V6.602H13.1913C11.9493 6.602 11.5627 7.378 11.5627 8.174V10.0607H14.336L13.8927 12.9687H11.5627V20C16.344 19.2453 20 15.0833 20 10.0607Z" fill="currentColor"/></svg>',
		'mail'     => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" width="20" height="20" fill="none"><path d="M1.42857 4.08163H18.5714V16.3265H1.42857V4.08163Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M18.2947 4.53061L11.6465 9.64408C11.1745 10.0072 10.5956 10.2041 10 10.2041C9.40441 10.2041 8.82554 10.0072 8.35347 9.64408L1.70531 4.53061" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
	);

	/**
	 * Display an icon, with an optional text fallback.
	 *
	 * @since 2.16.0
	 *
	 * @param string $icon The icon name.
	 * @param string $fallback_text A fallback text if for some reason the SVG icon can not be displayed.
	 * @param array  $svg_attrs  SVG html attributes to replace.
	 *
	 * @return void
	 */
	public static function render( string $icon, string $fallback_text = '', array $svg_attrs = array() ) : void {
		echo self::generate( $icon, $fallback_text, $svg_attrs );
	}

	/**
	 * Retrieves an icon, with an optional text fallback.
	 *
	 * @since 2.17.0
	 *
	 * @param string $icon          The icon name.
	 * @param string $fallback_text A fallback text if for some reason the SVG icon can not be displayed.
	 * @param array  $svg_attrs     SVG html attributes to replace.
	 *
	 * @return void
	 */
	public static function generate( string $icon, string $fallback_text = '', array $svg_attrs = array() ) : string {

		try {

			return wp_kses( self::get( $icon, $svg_attrs ), self::$kses );

		} catch ( Exception $e ) {

			if ( empty( $fallback_text ) ) {

				return esc_html( $icon );
			}

			return esc_html( $fallback_text );
		}
	}

	/**
	 * Return an icon, optionally replace attributes.
	 *
	 * @since 2.16.0
	 *
	 * @param string $icon The icon name.
	 * @param array  $svg_attrs SVG html attributes to replace.
	 *
	 * @return string The final SVG.
	 * @throws Exception Error if icon not find or while handling DOMDocument object.
	 */
	public static function get( string $icon, array $svg_attrs = array() ) : string {

		if ( ! isset( self::$icons[ $icon ] ) ) {
			throw new Exception( 'Could not find the SVG icon.' );
		}

		if ( empty( $svg_attrs ) ) {
			return self::$icons[ $icon ]; // Return the SVG as it is.
		}

		$allowed_attributes = array(
			'class',
			'viewBox',
			'stroke-width',
			'width',
			'height',
			'fill',
		);

		$el = new DOMDocument();

		if ( $el->loadXML( self::$icons[ $icon ] ) === false ) {
			throw new Exception( 'Could not load the SVG icon.' );
		}

		foreach ( $svg_attrs as $attr_key => $attr_value ) {

			if ( ! in_array( $attr_key, $allowed_attributes, true ) ) {
				continue; // Icon is not in the allowed list.
			}

			foreach ( $el->getElementsByTagName( 'svg' ) as $svg ) {
				$svg->setAttribute( $attr_key, esc_attr( $attr_value ) );
			}
		}

		$el = $el->saveXML();

		if ( false === $el ) {
			throw new Exception( 'Could not save the SVG icon.' );
		}

		return $el;
	}

	/**
	 * Return an array with all registered icons.
	 *
	 * @since 2.16.0
	 *
	 * @return array The array collection.
	 */
	public static function list() : array {
		return array_keys( self::$icons );
	}

	/**
	 * Return the SVG kses.
	 *
	 * @since 2.16.0
	 *
	 * @return array|string[] Array of allowed tags and attributes.
	 */
	public static function kses() : array {
		return self::$kses;
	}
}
