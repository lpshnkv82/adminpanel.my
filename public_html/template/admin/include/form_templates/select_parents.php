<div class="vg-element vg-full vg-left vg-box-shadow">
    <div class="vg-wrap vg-full">
        <div class="vg-element vg-full vg-left">
            <span class="vg-header"><?php echo $translate[$row][0] ? $translate[$row][0] : $row?></span>
        </div>
        <div class="select-wrapper vg-element vg-full vg-left vg-no-offset">
            <div class="select-arrow-3 select-arrow-31"></div>
            <select name="<?=$row?>" class="vg-input vg-text vg-full vg-firm-color1">
                <?php if($parents):?>
                    <option value="0">Корневая</option>
                    <?php foreach($parents as $item):?>
                        <option value="<?=$item[$res_arr['id_row']]?>" <?=$data[$row] == $item[$res_arr['id_row']] ? 'selected' : ''?>><?=$item[$res_arr['name_row']]?></option>
                    <?php endforeach;?>
                <?php endif;?>
            </select>
        </div>
    </div>
</div>