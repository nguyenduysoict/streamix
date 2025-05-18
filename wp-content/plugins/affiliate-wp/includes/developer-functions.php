<?php
/**
 * Functions meant for developers.
 *
 * @package     AffiliateWP
 * @subpackage  Functions/Developers
 * @copyright   Copyright (c) 2024, Awesome Motive, inc
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       2.20.0
 * @author      Aubrey Portwood <aportwood@am.co>
 */

// phpcs:disable PEAR.Functions.FunctionCallSignature.FirstArgumentPosition -- There can be spaces before a comment.

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Trigger Error.
 *
 * Note, if `WP_DEBUG` is `false`, this error will not be triggered!
 *
 * @param string $function_name The name of the function or fully-qualified method.
 * @param string $message       The message you want to show.
 * @param int    $error_level   The error level (defaults to E_USER_NOTICE).
 */
function affiliatewp_trigger_error(
	string $function_name,
	string $message,
	int $error_level = E_USER_NOTICE
) {

	if ( ! WP_DEBUG ) {
		return;
	}

	if ( ! function_exists( 'wp_trigger_error' ) ) {

		// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_trigger_error, WordPress.Security.EscapeOutput.OutputNotEscaped -- Fallback to trigger_error when the official WP function is not available.
		trigger_error( $message, $error_level );
		return;
	}

	wp_trigger_error( $function_name, $message, $error_level );
}

/**
 * Deprecate a function.
 *
 * This triggers an `E_USER_NOTICE` when `WP_DEBUG` is `true`.
 *
 * Message ends up being something like:
 *
 * "function_name(): (Since AffiliateWP Version 2.19.2) Use another function instead."
 *
 * Usage:
 *
 *     affiliatewp_deprecate_function( 'my_function', __( 'Use another_func() instead.', 'affiliate-wp' ), '2.19.1' );
 *
 * @since 2.20.0
 *
 * @param string $function_name The function name or fully-qualified method name.
 * @param string $message       The message to show (usually what function to use instead).
 * @param string $version       The version the function was deprecated.
 * @param int    $error_level   This lets you determine how harsh the warning is, defaults to an `E_USER_NOTICE`.
 *
 * @throws \InvalidArgumentException If you do not specify a valid version.
 */
function affiliatewp_deprecate_function(
	string $function_name,
	string $message,
	string $version,
	int $error_level = E_USER_NOTICE
) {

	if ( empty( $version ) || ! version_compare( '0.0', $version, '<=' ) ) {

		// Show a notice about using the wrong version number, but continue.
		affiliatewp_trigger_error(
			__FUNCTION__,
			sprintf(

				// Translators: %s is the version string they passed.
				__( '$version should be a valid SemVer version number, you passed %s, which does not appear to be.', 'affiliate-wp' ),
				$version
			),
			E_USER_NOTICE
		);
	}

	affiliatewp_trigger_error(
		$function_name,
		sprintf(
			'(%1$s) %2$s',
			sprintf(

				// Translators: %s is the version number, e.g. 2.19.2.
				__( 'Since AffiliateWP Version %s', 'affiliate-wp' ),
				$version
			),
			$message
		),
		$error_level
	);
}
