<table class="wp-list-table widefat fixed striped" id="arcontactus-menu-items">
    <thead>
        <tr>
            <th scope="col" class="manage-column column-name column-primary arcu-width-80"><?php echo __('Position', 'ar-contactus') ?></th>
            <th scope="col" class="manage-column column-description arcu-width-80"><?php echo __('Icon', 'ar-contactus') ?></th>
            <th scope="col" class="manage-column column-description"><?php echo __('Title', 'ar-contactus') ?></th>
            <th scope="col" class="manage-column column-description"><?php echo __('Type', 'ar-contactus') ?></th>
            <th scope="col" class="manage-column column-description arcu-width-80"><?php echo __('Display', 'ar-contactus') ?></th>
            <?php if ($isWPML) { ?>
                <th scope="col" class="manage-column column-description arcu-width-100"><?php echo __('Language', 'ar-contactus') ?></th>
            <?php } ?>
            <th scope="col" class="manage-column column-description arcu-width-60"><?php echo __('Active', 'ar-contactus') ?></th>
            <th scope="col" class="manage-column column-description arcu-width-60"></th>
        </tr>
    </thead>

    <tbody id="the-list">
        <?php foreach ($items as $item){?>
        <tr data-id="<?php echo (int)$item->id ?>">
            <td class="">
                <span class="arcu-mobile-table-header">
                    <?php echo __('Position', 'ar-contactus') ?>
                </span>
                <span class="drag-handle">
                    <i class="icon move"></i>
                    <span class="position">
                        <?php echo (int)$item->position ?>
                    </span>
                </span>
            </td>
            <td>
                <span class="arcu-mobile-table-header">
                    <?php echo __('Icon', 'ar-contactus') ?>
                </span>
                <span>
                    <?php if (isset($item->params) && isset($item->params->icon_type) && $item->params->icon_type == 2 && $item->params->icon_img){ ?>
                        <?php echo wp_get_attachment_image($item->params->icon_img, 'full', false) ?>
                    <?php } else { ?>
                        <?php echo ArContactUsConfigModel::getIcon($item->icon) ?>
                    <?php } ?>
                    <span class="lbl-color" style="background: #<?php echo ArContactUsTools::escJsString($item->color) ?>"><?php echo ArContactUsTools::escJsString($item->color) ?></span>
                </span>
            </td>
            <td>
                <span class="arcu-mobile-table-header">
                    <?php echo __('Title', 'ar-contactus') ?>
                </span>
                <span>
                    <?php echo ArContactUsTools::escJsString($item->title) ?>
                    <?php if ($item->subtitle){ ?>
                    <br/><span class="text-sm muted"><?php echo ArContactUsTools::escJsString($item->subtitle) ?></span>
                    <?php } ?>
                </span>
            </td>
            <td>
                <span class="arcu-mobile-table-header">
                    <?php echo __('Type', 'ar-contactus') ?>
                </span>
                <?php if ($item->type == 0){
                    echo __('Link:', 'ar-contactus'); ?>
                    <a href="<?php echo $item->link ?>" target="_blank">
                        <?php echo ArContactUsTools::escJsString($item->link) ?>
                    </a>
                <?php }elseif ($item->type == 1){
                    echo __('Integration', 'ar-contactus') . ':' . ArContactUsTools::escJsString($item->integration);
                }elseif ($item->type == 2){
                    echo __('Custom JS code', 'ar-contactus');
                }elseif ($item->type == 3){
                    echo __('Form:', 'ar-contactus') . $item->params->form;
                }elseif ($item->type == 4){
                    echo __('Custom popup', 'ar-contactus');
                } ?>
            </td>
            <td>
                <span class="arcu-mobile-table-header">
                    <?php echo __('Display options', 'ar-contactus') ?>
                </span>
                <?php if ($item->display == 1){ ?>
                    <span style="color: #00a426" title="<?php echo __('displays on desktop and mobile', 'ar-contactus') ?>"> 
                        <svg style="display: inline-block" aria-hidden="true" data-prefix="far" data-icon="desktop-alt" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="svg-inline--fa fa-desktop-alt fa-w-18 fa-3x"><path fill="currentColor" d="M528 0H48C21.5 0 0 21.5 0 48v288c0 26.5 21.5 48 48 48h480c26.5 0 48-21.5 48-48V48c0-26.5-21.5-48-48-48zM48 54c0-3.3 2.7-6 6-6h468c3.3 0 6 2.7 6 6v234H48V54zm432 434c0 13.3-10.7 24-24 24H120c-13.3 0-24-10.7-24-24s10.7-24 24-24h98.7l18.6-55.8c1.6-4.9 6.2-8.2 11.4-8.2h78.7c5.2 0 9.8 3.3 11.4 8.2l18.6 55.8H456c13.3 0 24 10.7 24 24z" class=""></path></svg>
                        <svg title="displays on mobile" style="display: inline-block" aria-hidden="true" data-prefix="fas" data-icon="mobile-android-alt" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" class="svg-inline--fa fa-mobile-android-alt fa-w-10 fa-3x"><path fill="currentColor" d="M272 0H48C21.5 0 0 21.5 0 48v416c0 26.5 21.5 48 48 48h224c26.5 0 48-21.5 48-48V48c0-26.5-21.5-48-48-48zm-64 452c0 6.6-5.4 12-12 12h-72c-6.6 0-12-5.4-12-12v-8c0-6.6 5.4-12 12-12h72c6.6 0 12 5.4 12 12v8zm64-80c0 6.6-5.4 12-12 12H60c-6.6 0-12-5.4-12-12V60c0-6.6 5.4-12 12-12h200c6.6 0 12 5.4 12 12v312z" class=""></path></svg>
                    </span>
                <?php }elseif($item->display == 2){ ?>
                    <span style="color: #7c529d" title="<?php echo __('displays on desktop only', 'ar-contactus') ?>">
                        <svg aria-hidden="true" data-prefix="far" data-icon="desktop-alt" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="svg-inline--fa fa-desktop-alt fa-w-18 fa-3x"><path fill="currentColor" d="M528 0H48C21.5 0 0 21.5 0 48v288c0 26.5 21.5 48 48 48h480c26.5 0 48-21.5 48-48V48c0-26.5-21.5-48-48-48zM48 54c0-3.3 2.7-6 6-6h468c3.3 0 6 2.7 6 6v234H48V54zm432 434c0 13.3-10.7 24-24 24H120c-13.3 0-24-10.7-24-24s10.7-24 24-24h98.7l18.6-55.8c1.6-4.9 6.2-8.2 11.4-8.2h78.7c5.2 0 9.8 3.3 11.4 8.2l18.6 55.8H456c13.3 0 24 10.7 24 24z" class=""></path></svg>
                    </span>
                <?php }elseif($item->display == 3){ ?>
                    <span style="color: #ff8400" title="<?php echo __('displays on mobile only', 'ar-contactus') ?>">
                        <svg aria-hidden="true" data-prefix="fas" data-icon="mobile-android-alt" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" class="svg-inline--fa fa-mobile-android-alt fa-w-10 fa-3x"><path fill="currentColor" d="M272 0H48C21.5 0 0 21.5 0 48v416c0 26.5 21.5 48 48 48h224c26.5 0 48-21.5 48-48V48c0-26.5-21.5-48-48-48zm-64 452c0 6.6-5.4 12-12 12h-72c-6.6 0-12-5.4-12-12v-8c0-6.6 5.4-12 12-12h72c6.6 0 12 5.4 12 12v8zm64-80c0 6.6-5.4 12-12 12H60c-6.6 0-12-5.4-12-12V60c0-6.6 5.4-12 12-12h200c6.6 0 12 5.4 12 12v312z" class=""></path></svg>
                    </span>
                <?php } ?>
                
                <?php if ($item->registered_only == 0){ ?>
                    <span style="color: #00a426" title="<?php echo __('show for all users', 'ar-contactus') ?>"> 
                        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="users" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" class="svg-inline--fa fa-users fa-w-20 fa-3x"><path fill="currentColor" d="M96 224c35.3 0 64-28.7 64-64s-28.7-64-64-64-64 28.7-64 64 28.7 64 64 64zm448 0c35.3 0 64-28.7 64-64s-28.7-64-64-64-64 28.7-64 64 28.7 64 64 64zm32 32h-64c-17.6 0-33.5 7.1-45.1 18.6 40.3 22.1 68.9 62 75.1 109.4h66c17.7 0 32-14.3 32-32v-32c0-35.3-28.7-64-64-64zm-256 0c61.9 0 112-50.1 112-112S381.9 32 320 32 208 82.1 208 144s50.1 112 112 112zm76.8 32h-8.3c-20.8 10-43.9 16-68.5 16s-47.6-6-68.5-16h-8.3C179.6 288 128 339.6 128 403.2V432c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48v-28.8c0-63.6-51.6-115.2-115.2-115.2zm-223.7-13.4C161.5 263.1 145.6 256 128 256H64c-35.3 0-64 28.7-64 64v32c0 17.7 14.3 32 32 32h65.9c6.3-47.4 34.9-87.3 75.2-109.4z" class=""></path></svg>
                    </span>
                <?php }elseif($item->registered_only == 1){ ?>
                    <span style="color: #7c529d" title="<?php echo __('show to logged-in users only', 'ar-contactus') ?>">
                        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="user-tie" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="svg-inline--fa fa-user-tie fa-w-14 fa-3x"><path fill="currentColor" d="M224 256c70.7 0 128-57.3 128-128S294.7 0 224 0 96 57.3 96 128s57.3 128 128 128zm95.8 32.6L272 480l-32-136 32-56h-96l32 56-32 136-47.8-191.4C56.9 292 0 350.3 0 422.4V464c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48v-41.6c0-72.1-56.9-130.4-128.2-133.8z" class=""></path></svg>
                    </span>
                <?php }elseif($item->registered_only == 2){ ?>
                    <span style="color: #ff8400" title="<?php echo __('show to logged-out users only', 'ar-contactus') ?>">
                        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="user-secret" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="svg-inline--fa fa-user-secret fa-w-14 fa-3x"><path fill="currentColor" d="M383.9 308.3l23.9-62.6c4-10.5-3.7-21.7-15-21.7h-58.5c11-18.9 17.8-40.6 17.8-64v-.3c39.2-7.8 64-19.1 64-31.7 0-13.3-27.3-25.1-70.1-33-9.2-32.8-27-65.8-40.6-82.8-9.5-11.9-25.9-15.6-39.5-8.8l-27.6 13.8c-9 4.5-19.6 4.5-28.6 0L182.1 3.4c-13.6-6.8-30-3.1-39.5 8.8-13.5 17-31.4 50-40.6 82.8-42.7 7.9-70 19.7-70 33 0 12.6 24.8 23.9 64 31.7v.3c0 23.4 6.8 45.1 17.8 64H56.3c-11.5 0-19.2 11.7-14.7 22.3l25.8 60.2C27.3 329.8 0 372.7 0 422.4v44.8C0 491.9 20.1 512 44.8 512h358.4c24.7 0 44.8-20.1 44.8-44.8v-44.8c0-48.4-25.8-90.4-64.1-114.1zM176 480l-41.6-192 49.6 32 24 40-32 120zm96 0l-32-120 24-40 49.6-32L272 480zm41.7-298.5c-3.9 11.9-7 24.6-16.5 33.4-10.1 9.3-48 22.4-64-25-2.8-8.4-15.4-8.4-18.3 0-17 50.2-56 32.4-64 25-9.5-8.8-12.7-21.5-16.5-33.4-.8-2.5-6.3-5.7-6.3-5.8v-10.8c28.3 3.6 61 5.8 96 5.8s67.7-2.1 96-5.8v10.8c-.1.1-5.6 3.2-6.4 5.8z" class=""></path></svg>
                    </span>
                <?php } ?>
            </td>
            <?php if ($isWPML) { ?>
            <td>
                <span class="arcu-mobile-table-header">
                    <?php echo __('Language', 'ar-contactus') ?>
                </span>
                <?php if (empty($item->language)) {?>
                    <span class="muted">
                        <?php echo __('all', 'ar-contactus') ?>
                    </span>
                <?php }else{ ?>
                    <?php echo $item->language ?>
                <?php } ?>
            </td>
            <?php } ?>
            <td>
                <span class="arcu-mobile-table-header">
                    <?php echo __('Active', 'ar-contactus') ?>
                </span>
                <a href="#" onclick="arCU.toggle(<?php echo $item->id ?>); return false;" class="<?php echo $item->status? 'lbl-success' : 'lbl-default' ?>">
                    <?php echo $item->status? __('Yes', 'ar-contactus') : __('No', 'ar-contactus') ?>
                </a>
            </td>
            <td>
                <a href="#" title="Edit" onclick="arCU.edit(<?php echo (int)$item->id ?>); return false;" class="edit" data-id="<?php echo (int)$item->id ?>">
                    <?php echo __('Edit', 'ar-contactus') ?>
                </a>

                <a href="#" title="Delete" onclick="arCU.remove(<?php echo (int)$item->id ?>); return false;" data-id="<?php echo (int)$item->id ?>" class="delete">
                    <?php echo __('Delete', 'ar-contactus') ?>
                </a>
            </td>
        </tr>
        <?php } ?>
    </tbody>

    <tfoot>
        <tr>
            <th scope="col" class="manage-column column-name column-primary"><?php echo __('Position', 'ar-contactus') ?></th>
            <th scope="col" class="manage-column column-description"><?php echo __('Icon', 'ar-contactus') ?></th>
            <th scope="col" class="manage-column column-description"><?php echo __('Title', 'ar-contactus') ?></th>
            <th scope="col" class="manage-column column-description"><?php echo __('Type', 'ar-contactus') ?></th>
            <th scope="col" class="manage-column column-description"><?php echo __('Display', 'ar-contactus') ?></th>
            <?php if ($isWPML) { ?>
                <th scope="col" class="manage-column column-description arcu-width-100"><?php echo __('Language', 'ar-contactus') ?></th>
            <?php } ?>
            <th scope="col" class="manage-column column-description"><?php echo __('Active', 'ar-contactus') ?></th>
            <th scope="col" class="manage-column column-description"></th>
        </tr>
    </tfoot>
</table>