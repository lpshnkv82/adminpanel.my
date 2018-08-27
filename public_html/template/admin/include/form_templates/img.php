<div class="vg-element vg-full vg-box-shadow img_container">
    <div class="vg-wrap vg-element vg-full">
        <div class="vg-wrap vg-element vg-full">
            <div class="vg-element vg-half vg-left">
                <span class="vg-header"><?php echo $translate[$row][0] ? $translate[$row][0] : $row?></span>
            </div>
            <div class="vg-element vg-full vg-left">
                <span class="vg-text vg-firm-color5">(не более 10 мб)</span><span class="vg_subheader"></span>
            </div>
        </div>
        <div class="vg-wrap vg-element vg-full">
            <label class="vg-wrap vg-full file_upload vg-relative vg-left">
                <span class="vg-element vg-full vg-input vg-text vg-left vg-button">Выбрать</span>
                <input type="file" name="<?=$row?>" class="single_img">
            </label>
        </div>
        <div class="vg-wrap vg-element vg-full">
                <div class="vg-element vg-left img_show">
                    <?php if($data[$row]):?>
                        <img src="<?=PATH.UPLOAD_DIR.$data[$row]?>" alt="service">
                    <?php endif;?>
                </div>
        </div>

    </div>
</div>