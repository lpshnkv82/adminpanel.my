<div class="vg-element vg-full  vg-no-space-top">
    <div class="vg-element vg-full vg-firm-background-color4 vg-box-shadow">
        <div class="vg-wrap vg-element vg-half vg-left vg-no-space-top">
            <div class="vg-element vg-full vg-left">
                <span class="vg-header"><?php echo $translate[$row][0] ? $translate[$row][0] : $row?></span>
            </div>
            <div class="vg-wrap vg-element vg-fourth">
                <label class="vg-element vg-full vg-center vg-left vg-space-between" for="yes">
                    <span class="vg-text vg-half">Да</span>
                    <input id="yes" type="radio" name="<?=$row?>" class="vg-input vg-half" value="1" checked>
                </label>
                <label class="vg-element vg-full vg-center vg-left vg-space-between" for="no">
                    <span class="vg-text vg-half">Нет</span>
                    <input id="no" type="radio" name="<?=$row?>" class="vg-input vg-half" value="0" <?=$data[$row] === 0 ? 'checked' : ''?>>
                </label>
            </div>
        </div>
    </div>
</div>