<div class="multy_container div_shdw">
    <?php if($pages):?>
        <div>
            <?php foreach($pages as $item):?>
                <a style="color:black" href="<?=PATH.ADMIN_PATH?>/edit/<?=$edit[0]?>/<?=$item['id']?><?=$edit[1]?>" class="product_item">
                    <div class="product_item_main">
                        <?php if($item['img'] && file_exists($_SERVER['DOCUMENT_ROOT'].PATH.UPLOAD_DIR.$item['img'])):?>
                            <img src="<?=PATH?><?=UPLOAD_DIR?><?=$item['img']?>" alt="<?=$item['name']?>">
                        <?php else:?>
                            <img src="<?=PATH?><?=TEMPLATE?>img/noimage.jpg" alt="<?=PATH?><?=UPLOAD_DIR?><?=$item['img']?>">
                        <?php endif;?>
                    </div>
                    <div class="product_item_description">
                        <p style="color:#a9a11c"><?=$item['name']?></p>
                    </div>
                    <?php if($item['page_type'] == 'main'):?>
                        <div class="page_type">
                            <p>Главная</p>
                        </div>
                        <?php elseif($item['page_type'] == 'about'):?>
                            <div class="page_type">
                                <p>О нас</p>
                            </div>
                    <?php endif;?>
                </a>
            <?php endforeach;?>
        </div>
    <?php endif;?>
</div>