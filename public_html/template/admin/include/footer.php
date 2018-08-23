            </div><!--.vg-main.vg-right-->
        </div><!--.vg-carcass-->

        <div class="vg_modal">
            <?php
            if(isset($_SESSION['res']['answer'])){
                echo $_SESSION['res']['answer'];
                unset($_SESSION['res']);
            }
            ?>
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