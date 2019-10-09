<?php
/**
 * コピーページテンプレート
 */
?>
<div class="box">
<h2><?php _e('Multi site category copy'); ?></h2>
<form name="mcc" action="" method="post">
    <div class="metabox-holder">
        <div class="postbox">
            <h3 class="hndle">カテゴリーの同期</h3>
            <div class="inside">
                <div class="main">
                    <?php if(!empty($terms)){ ?>
                    ライフガーデン：<select name="base_term_id">
                        <?php foreach($terms as $term) { ?>
                        <option value="<?php echo $term->taxonomy; ?>"><?php echo $term->taxonomy; ?></option>
                        <?php } ?>
                    </select>
                    <?php } ?>
                    <?php
                        foreach($sites as $sk => $sv){
                            ?>
                            <div class="input-text-wrap">
                                <label>
                                    <?php echo $sv; ?> <input type="checkbox" name="child_blog_id[]" value="<?php echo $sk; ?>">
                                </label>
                            </div>
                            <?php
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php submit_button('チェックしたブログにタクソノミーを同期'); ?>
</form>
</div>