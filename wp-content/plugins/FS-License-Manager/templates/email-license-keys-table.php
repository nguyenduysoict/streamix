<?php
/**
 * Template for email license keys table.
 *
 * Version: 1.0
 * @package fs-license-manager
 */

defined( 'ABSPATH' ) or die();
$download = '';

$val = '<h4>' . (count($values) == 1 ? get_option('fslm_meta_key_name',
		'License Key') : get_option('fslm_meta_key_name_plural', 'License Key')) . '</h4>' .
       '<table class="order-details td fslm-license-keys-table" style="border-collapse: collapse; width: 100%; border: 1px solid #e5e5e5; font-family: \'Helvetica Neue\', Helvetica, Roboto, Arial, sans-serif;">' .
       '<thead>' .
       '<tr>' .
       '<th class="td" style="text-align:left;width: 20%;"><strong>' . __('Product',
		'fslm') . '</strong></th>' .
       '<th class="td" style="text-align:left;width: 80%;"><strong>' . (count($values) == 1 ? get_option('fslm_meta_key_name',
		'License Key') : get_option('fslm_meta_key_name_plural', 'License Key')) . '</strong></th>' .
       '</tr>' .
       '</thead>' .
       '<tbody>';

foreach ($values as $value) {
	if ($value['visible'] == 'Yes') {

		$show_in = get_post_meta((int)$value['product_id'], 'fslm_show_in', true);
		if ($show_in == "") {
			$show_in = get_option('fslm_show_in', '2');
		}

		if ($show_in == "1") {
			$view_in_website = true;
			$visible_key_found = true;
			break;
		}

		if ($show_in == "0" || $show_in == "2") {
			$visible_key_found = true;

			$display = get_post_meta((int)$value['product_id'], 'fslm_display', true);
			if ($display == "") {
				$display = get_option('fslm_display', '2');
			}

			$license_key = $value['license_key'];
			$license_key = $this->encrypt_decrypt('decrypt', $license_key, ENCRYPTION_KEY, ENCRYPTION_VI);

			$license_key = br2newLine($license_key);
			$license_key = preg_replace("/\\\\\"|\\\\'/", '"', $license_key);
			$license_key = $this->newLine2br($license_key);

			$meta = '';

			if ($display != '1') {
				$meta = $license_key . '<br>';
			}

			$image_license_key = '';

			if ($display != '0') {
				$image_license_key = $this->get_image_name($value['license_id']);

				if ($image_license_key != '') {
					$upload_directory = wp_upload_dir();
					$image_license_key = '<img  style="width: auto" class="fslm_ilksrc" src="' . $upload_directory['baseurl'] . '/fslm_keys/' . $image_license_key . '">';

				}
			}

			if ($value['max_instance_number'] > 0) {
				$meta .= ' <strong>' . __('Can be used',
						'fslm') . '</strong> ' . $value['max_instance_number'] . ' ' . __('time(s)',
						'fslm') . '<br>';
			}

			if (($value['max_instance_number'] == 0) && isset($value['uses']) && $value['uses'] > 1) {
				$meta .= ' <strong>' . __('Can be used',
						'fslm') . '</strong> ' . $value['uses'] . ' ' . __('times', 'fslm') . '<br>';
			}

			if (($value['expiration_date'] != '0000-00-00') && ($value['expiration_date'] != '')) {
				$meta .= ' ' . '<strong>' . __('Expires ',
						'fslm') . '</strong>' . $this->fslm_format_date($value['expiration_date']);
			}

			$product_name = get_the_title($value['product_id']);

			if ($value['variation_id'] != 0) {
				$variation = wc_get_product($value['variation_id']);
				if ($variation) {
					$product_name .= ' - ' . implode(' ', $variation->get_variation_attributes());
				}
			}

			$val .= '<tr><td class="td" style="text-align:left;width: 20%;">' . $product_name . '</td><td class="td" style="text-align:left;width: 80%;">' . $meta . $image_license_key . '</td></tr>';
		}
	}
}

$val .= "</tbody></table><br><br>";
