<div class="vg-main-left header">
    <?php if($pages):?>
        <div class="vg-wrap">
            <div class="vg-column header_text">
                <div class="el_center site_name">
                    <span><?=$set['type']?></span>
                </div>
                <div class="vg-main-left page_name">
                    <div class="header_bg">
                        <span>01</span>
                        <span>/</span>
                        <span>01</span>
                    </div>
                    <h1><?=$pages[0]['name']?></h1>
                </div>
                <div class="el_center">
                    <?=$pages[0]['short_content']?>
                </div>
                <div class="el_center">
                    <a href="<?=PATH?>categories/id/<?=$pages[0]['id']?>" class="color button">Перейти</a>
                </div>
            </div>
            <div class="header_right header_img">
                <img src="<?=$pages[0]['img']?>" alt="<?=$pages[0]['name']?>">
            </div>
        </div>
    <?php endif; ?>
</div>
<div class="hidden_main">
    <?php foreach ($pages as $page):?>
    <div>
        <span class="hidden_id"><?=$page['id']?></span>
        <span class="hidden_name"><?=$page['name']?></span>
        <span class="hidden_text"><?=$page['short_content']?></span>
        <span class="hidden_img"><?=$page['img']?></span>
    </div>
    <?php endforeach;?>
</div>