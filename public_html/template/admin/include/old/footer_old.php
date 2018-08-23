        </div>
    </div>
</div>
<div class="vg_modal">
    <?php
    if(isset($_SESSION['res']['answer'])){
        echo $_SESSION['res']['answer'];
        unset($_SESSION['res']);
    }
    ?>
</div>

<div class="modal_image_wrap">
    <div class="modal_image">

    </div>
    <div class="img_settings">
        <button id="release">Убрать выделение</button>
        <button id="crop">Обрезать</button>
        <div class="optlist offset">
            <?php if(is_array(CROP)):?>
                <?php foreach(CROP as $key => $crop):?>
                    <label><input type="checkbox" class="thumb_crop" value="<?=$key?>">Соблюдать пропорции (<?=$crop[0]?>:<?=$crop[1]?>)</label>
                <?php endforeach;?>
            <?php endif;?>
            <!--<label><input type="checkbox" id="size_lock" />min/max размер (80x80/350x350)</label>-->
        </div>
        <div  class="inline-labels">
            <label>X1 <input type="text" size="4" id="x1" name="x1" /></label>
            <label>Y1 <input type="text" size="4" id="y1" name="y1" /></label>
            <label>X2 <input type="text" size="4" id="x2" name="x2" /></label>
            <label>Y2 <input type="text" size="4" id="y2" name="y2" /></label>
            <label>W <input type="text" size="4" id="w" name="w" /></label>
            <label>H <input type="text" size="4" id="h" name="h" /></label>
        </div>
        <p>Результаты:</p>
        <div  id="cropresult"></div>
    </div>
</div>

<script>
    var path = '<?=PATH?>';
    var cropes = JSON.parse('<?=json_encode(CROP)?>');
</script>
<?php foreach($scripts as $script):?>
    <script src="<?=$script?>"></script>
<?php endforeach;?>

    </body>
</html>