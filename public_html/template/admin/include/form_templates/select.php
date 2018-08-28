<div class="vg-element vg-full vg-left vg-box-shadow">
    <div class="vg-wrap vg-full">
        <div class="vg-element vg-full vg-left">
            <span class="vg-header"><?php echo $translate[$row][0] ? $translate[$row][0] : $row?></span>
        </div>
        <div class="select-wrapper vg-element vg-full vg-left vg-no-offset">
            <div class="select-arrow-3 select-arrow-31"></div>
            <select name="<?=$row?>" class="vg-input vg-text vg-full vg-firm-color1">
                <?php if($menu_pos):?>
                    <?php for($i = 1; $i <= $menu_pos; $i++):?>
                        <option value="<?=$i?>" <?=$data[$row] == $i ? 'selected' : ''?>><?=$i?></option>
                    <?php endfor;?>
                <?php endif;?>
            </select>
        </div>
    </div>
</div>