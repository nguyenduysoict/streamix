<?php

defined( 'ABSPATH' ) or  die( 'No script kiddies please!' );
require_once('functions.php');

global $months;
global $status;

$status             = get_option('fslm_lks',  'N/A');
$buyer              = get_option('fslmesc_html_ebun', 'N/A');
$supported_until    = get_option('fslm_su', 'N/A');
$purchase_code      = get_option('fslm_lk', 'N/A');

$date               = strtotime($supported_until);
$supported_until_formatted = date('F d, Y H:i', $date);

$expires = date('YmdHi', $date);
$today   = date('YmdHi');

if($expires < $today) {
	$supported_until = "<span class=\"error\">" . __('Expired on: ', 'fslm') . ' ' . $supported_until_formatted . "</span>";
} else {
	$supported_until = "<span>" . __('Valid until:', 'fslm') . ' ' . $supported_until_formatted . "</span>";
}

?>
<div class="wrap fslm">

    <h1><?php echo __('License And Support', 'fslm'); ?></h1>

    <div class="postbox">
        
        <p><strong>
            <?php esc_html_e('If you have a question or need support, ', 'fslm'); ?><a href="https://support.firassaidi.com/" target="_blank"><?php esc_html_e('please open a new ticket here', 'fslm') ?></a><?php esc_html_e(' , and I will get back to you as soon as possible.
Make sure to send screenshots of the issue if possible.', 'fslm'); ?><br><br>
        </strong>

            <?php esc_html_e('The fastest way to get support is through our ticketing system.', 'fslm') ?>
        </p>
        
        <h4 class="title"><?php esc_html_e('This copy of FS License Manager is licensed to', 'fslm') ?></h4>

        <table id="licenses" class="wp-list-table widefat fixed striped posts">

            <tr>
                <td><strong><?php esc_html_e('Envato Username', 'fslm') ?></strong></td>
                <td><?php echo $buyer ?></td>
            </tr>

            <tr>
                <td><strong><?php esc_html_e('Purchase Code', 'fslm') ?></strong></td>
                <td><?php echo $purchase_code ?></td>
            </tr>
            
            <tr>
                <td><strong><?php esc_html_e('Support Status', 'fslm') ?></strong></td>
                <td><?php echo $supported_until ?></td>
            </tr>
               

            <tr>
                <td><strong><?php esc_html_e('License Status', 'fslm') ?></strong></td>
                <td style="text-transform: capitalize"><?php echo $status ?></td>
            </tr>

        </table>

        <p><strong><?php esc_html_e('Deactivate license key to transfer to another domain', 'fslm'); ?></strong>
        <form class="fslm-actions-from" action="<?php echo admin_url('admin-ajax.php'); ?>" method="post">
            <input type="hidden" name="action" value="fslm_deactivate">
            <input type="submit" class="fslm-actions button action" value="<?php esc_html_e('Deactivate', 'fslm'); ?>">
        </form>
        
    </div>
          
</div>
           

           