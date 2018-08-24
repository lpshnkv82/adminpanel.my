<div class="vg-element vg-full vg-box-shadow">
    <div class="vg-wrap vg-element vg-full">
        <div class="vg-element vg-full">
            <div class="vg-element vg-full vg-left">
                <span class="vg-header"><?php echo $translate[$row][0] ? $translate[$row][0] : $row?></span>
            </div>
        </div>
        <div class="vg-wrap vg-element vg-full gallery_container">
                <label class="vg-dotted-square vg-center">
                    <img src="<?=PATH.ADMIN_TEMPLATE?>img/plus.png" alt="plus">
                    <input class="gallery_img" style="display: none;" type="file" name="<?=$row?>[]" multiple>
                </label>
<<<<<<< HEAD
                <?php if($data[$row]):?>
                    <?php $data[$row] = explode("|", $data[$row]);?>
                    <?php foreach($data[$row] as $item):?>
                        <div class="vg-dotted-square">
                            <img class="vg_delete" src="<?=PATH.UPLOAD_DIR.$item?>">
                        </div>
                    <?php endforeach;?>
                    <?php
                        for ($i = 0; $i < 2; $i++){
                            echo '<div class="vg-dotted-square empty_container"></div>';
                        }
                    ?>

                <?php else:?>
                    <?php
                        for ($i = 0; $i < 13; $i++){
                            echo '<div class="vg-dotted-square empty_container"></div>';
                        }
                    ?>
                <?php endif;?>
        </div>
    </div>
</div>