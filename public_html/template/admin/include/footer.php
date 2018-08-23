            </div><!--.vg-main.vg-right-->
        </div><!--.vg-carcass-->
        <script>
            var path = '<?=PATH?>';
            var cropes = JSON.parse('<?=json_encode(CROP)?>');
        </script>
        <?php foreach($scripts as $script):?>
            <script src="<?=$script?>"></script>
        <?php endforeach;?>
    </body>
</html>