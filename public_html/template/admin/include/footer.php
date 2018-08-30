            </div><!--.vg-main.vg-right-->
        </div><!--.vg-carcass-->

        <div class="vg_modal vg-center">
            <?php
            if(isset($_SESSION['res']['answer'])){
                echo $_SESSION['res']['answer'];
                unset($_SESSION['res']);
            }
            ?>
        </div>
            <div class="vg-wrap vg-element vg-no-offset vg-firm-background-color1 vg-fixed no-zoom thumbnails_wrap">
                <div class="vg-element vg-full inline-labels vg-firm-background-color4 vg-center vg-relative">
                    <label class="vg-full vg-text">X1 <input class="vg-input vg-text" type="text" size="4" id="x1" /></label>
                    <label class="vg-full vg-text">Y1 <input class="vg-input vg-text" type="text" size="4" id="y1" /></label>
                    <label class="vg-full vg-text">X2 <input class="vg-input vg-text" type="text" size="4" id="x2" /></label>
                    <label class="vg-full vg-text">Y2 <input class="vg-input vg-text" type="text" size="4" id="y2" /></label>
                    <label class="vg-full vg-text">W <input class="vg-input vg-text" type="text" size="4" id="w" /></label>
                    <label class="vg-full vg-text">H <input class="vg-input vg-text" type="text" size="4" id="h" /></label>
                   <span class="toggle_menu"></span>
                </div>
                <div class="vg-wrap vg-element vg-no-offset vg-full modal_image_wrap">
                    <div class="vg-element vg-no-offset vg-full vg-no-space-top modal_image">

                    </div>
                    <div class="vg-element vg-no-offset vg-full img_settings">
                        <a class="vg-input vg-full vg-firm-background-color4 vg-firm-color1 vg-no-offset" id="release">Убрать выделение</a>
                        <a class="vg-input vg-full vg-firm-background-color4 vg-firm-color1 vg-no-offset" id="crop">Обрезать</a>
                        <div class="optlist offset">
                            <!--<label><input type="checkbox" id="ar_lock" />Соблюдать пропорции (4:3)</label>
                                    <label><input type="checkbox" id="size_lock" />min/max размер (80x80/350x350)</label>-->
                        </div>
                    </div>
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