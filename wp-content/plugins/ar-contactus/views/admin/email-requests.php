<div class="wrap">
    <h1 class="wp-heading-inline"><?php echo __('Email requests', 'ar-contactus') ?></h1>
    <?php if (current_user_can('manage_options')){ ?>
        <a href="<?php echo admin_url('options-general.php?page=ar-contactus-key-config') ?>" class="page-title-action"><?php echo __('Settings', 'ar-contactus') ?></a>
    <?php } ?>
    <a href="" onclick="arCU.callback.reload(); return false;" class="page-title-action"><?php echo __('Reload table', 'ar-contactus') ?></a>
    <a href="" onclick="arCU.callback.export(1); return false;" class="page-title-action"><?php echo __('Export to CSV', 'ar-contactus') ?></a>
    <?php echo ArContactUsAdmin::render('/admin/_email_requests.php', array(
        'callbackList' => $callbackList,
        'activeSubmit' => $activeSubmit,
        'noSegment' => true
    )) ?>
</div>
<script>
    window.addEventListener('load', function(){
        arCU.ajaxUrl = ajaxurl;
        arCU.nonce = '<?php echo wp_create_nonce('arcu_config') ?>';
        arCU.editTitle = 'Edit item';
        arCU.addTitle = 'Add item';
        arCU.init();
        arCU.callback.updateCounter();
        setInterval(function(){
            arCU.callback.updateCounter();
        }, 5000);
    });
</script>

<div class="ui modal small" id="arcu-comment-modal">
    <i class="close icon"></i>
    <div class="header" id="arcu-comment-modal-title">
        <?php echo __('Edit item comment', 'ar-contactus') ?>
    </div>
    <form id="arcu-comment-form" method="POST" onsubmit="arCU.comment.save(); return false;">
        <input type="hidden" id="arcu-comment_id" name="id" data-serializable="true" autocomplete="off" data-default=""/>
        <div class="ui form" style="padding: 20px;">
            <div class="ui grid">
                <div class="row">
                    <div class="two wide column">
                    </div>
                    <div class="twelve wide column">
                        <div id="arcu-request-item-content">
                            
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="two wide column">
                    </div>
                    <div class="twelve wide column">
                        <div class="field">
                            <label><?php echo __('Comment', 'ar-contactus') ?></label>
                            <textarea id="arcu-comment_comment"></textarea>
                            <div class="errors"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="actions">
            <button type="button" class="button-large black deny button">
                <?php echo __('Cancel', 'ar-contactus') ?>
            </button>
            <button type="submit" class="button button-primary button-large icon">
                <?php echo __('Save', 'ar-contactus') ?>
                <i class="checkmark icon"></i>
            </button>
        </div>
    </form>
</div>
<script>
    window.addEventListener('load', function(){
        jQuery(document).on('click', '.arcu-edit-comment', function(){
            arCU.comment.edit(jQuery(this).data('id'));
            return false;
        });
    });
</script>