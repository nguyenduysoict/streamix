<div class="arcu-comment" id="arcu-comment-<?php echo $item['id'] ?>">
    <?php 
    $comment = '';
    if (!is_null($item['comment'])) {
        if (function_exists('mb_strlen')){
            if (mb_strlen($item['comment'], 'utf-8') > 128){
                $comment = mb_substr($item['comment'], 0, 128) . '...';
            }else{
                $comment = $item['comment'];
            }
        }else{
            if (strlen($item['comment']) > 128){
                $comment = substr($item['comment'], 0, 128) . '...';
            }else{
                $comment = $item['comment'];
            }
        }
    }
    if ($comment){?>
        <?php echo $comment ?>
        <br/>
    <?php } ?>
    <div class="text-right">
        <a href="" class="arcu-edit-comment" data-id="<?php echo $item['id'] ?>"><?php echo __('[edit]', 'ar-contactus') ?></a>
    </div>
</div>