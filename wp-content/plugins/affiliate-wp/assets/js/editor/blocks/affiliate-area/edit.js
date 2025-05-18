/**
 * Affiliate Content Edit Component.
 *
 * @since 2.8
 */

/**
 * External dependencies
 */
import classnames from 'classnames';

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';

import {
	useBlockProps,
	useInnerBlocksProps as __stableUseInnerBlocksProps,
	__experimentalUseInnerBlocksProps,
} from '@wordpress/block-editor';

const allowedBlocks = [
	'affiliatewp/registration',
	'affiliatewp/login',
	'wpforms/form-selector',
];

const template = [
	['affiliatewp/registration'],
	['affiliatewp/login'],
];


/**
 * Affiliate Area.
 *
 * Affiliate area block component.
 *
 * @since 2.8
 *
 * @returns {JSX.Element} The rendered component.
 */
function AffiliateArea() {

	const useInnerBlocksProps = __stableUseInnerBlocksProps
		? __stableUseInnerBlocksProps
		: __experimentalUseInnerBlocksProps;

	const blockProps = useBlockProps({
		className: classnames(
			'affwp-affiliate-area'
		),
	});

	const innerBlocksProps = useInnerBlocksProps(blockProps, {
		template,
		allowedBlocks,
	});

	return (
		<div {...innerBlocksProps} />
	);
}
export default AffiliateArea;