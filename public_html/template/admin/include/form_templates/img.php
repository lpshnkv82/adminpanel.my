<div class="vg-wrap vg-element vg-full vg-box-shadow img_container img_wrapper">
    <div class="vg-wrap vg-element vg-half">
        <div class="vg-wrap vg-element vg-full">
        <div class="vg-element vg-full vg-left">
                <span class="vg-header"><?php echo $translate[$row][0] ? $translate[$row][0] : $row?></span>
            </div>
            <div class="vg-element vg-full vg-left">
                <span class="vg-text vg-firm-color5"><?=$translate[$row][1]?></span><span class="vg_subheader"></span>
            </div>
        </div>
        <div class="vg-wrap vg-element vg-full">
            <label for="<?=$row?>" class="vg-wrap vg-full file_upload vg-left">
                <span class="vg-element vg-full vg-input vg-text vg-left vg-button">Выбрать</span>
                <input id="<?=$row?>" type="file" name="<?=$row?>" class="single_img">
            </label>
        </div>
        <div class="vg-wrap vg-element vg-full">
                <div class="vg-element vg-left img_show main_img_show">
                    <?php if($data[$row]):?>
                        <img src="<?=PATH.UPLOAD_DIR.$data[$row]?>" alt="service">
                    <?php endif;?>
                </div>
        </div>
        </div>
    <!--Thumbnail-->
    <?php foreach($columns as $value):?>
        <?php if(in_array('thumbnails', $value)):?>
            <div class="vg-wrap vg-element vg-half thumbnails_show">
                <div class="vg-wrap vg-element vg-full">
                    <div class="vg-element vg-full vg-left">
                        <span class="vg-header">Отредактированное изображение</span>
                    </div>
                </div>
                <div class="vg-wrap vg-element vg-full offset-top">
                    <div class="vg-element vg-left img_show thumb_img">
                        <?php if($data['thumbnails']):?>
                            <?php $thumbnails = explode("|", $data['thumbnails'])?>
                                <?php foreach($thumbnails as $thumb):?>
                                        <?php if(strpos($thumb,$row.'thumbnails_') === 0):?>
                                            <img class="vg_delete" src="<?=PATH.UPLOAD_DIR.$thumb?>?ver<?=mt_rand(1, 100)?>" alt="service">
                                        <?php endif;?>
                                <?php endforeach;?>
                        <?php endif;?>
                    </div>
                </div>
            </div>
            <?php break;?>
        <?php endif;?>
    <?php endforeach;?>

    <!--Thumbnail-->
</div>