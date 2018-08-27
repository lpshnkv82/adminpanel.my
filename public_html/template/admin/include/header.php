<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta type="keywords" content="...">
        <meta type="description" content="...">
        <title>Document</title>

        <?php foreach($styles as $style):?>
            <link rel="stylesheet" href="<?=$style?>">
        <?php endforeach;?>
    </head>
<body>
<div class="vg-carcass vg-hide">
    <div class="vg-main">
        <div class="vg-one-of-twenty vg-firm-background-color2  vg-center">
            <a href="<?=PATH?>" target="_blank">
                <div class="vg-element vg-full">
                    <span class="vg-text2 vg-firm-color1">САЙТ</span>
                </div>
            </a>
        </div>
        <div class="vg-element vg-ninteen-of-twenty vg-firm-background-color4 vg-space-between  vg-box-shadow">
            <div class="vg-element vg-third">
                <div class="vg-element vg-fifth vg-center" id="hideButton">
                    <div>
                        <img src="<?=PATH.ADMIN_TEMPLATE?>img/menu-button.png" alt="">
                    </div>
                </div>
                <div class="vg-element vg-wrap-size vg-center vg-search  vg-relative" id="searchButton">
                    <div>
                        <img src="<?=PATH.ADMIN_TEMPLATE?>img/search.png" alt="">
                    </div>
                    <input type="text" class="vg-input vg-text">
                    <div class="vg-element vg-firm-background-color4 vg-box-shadow search_links">
                        <a class="vg-text" href="/">asaskldjasldjhklasdd</a>
                        <a class="vg-text" href="/">asaskldjasldjhklasdd</a>
                        <a class="vg-text" href="/">asaskldj asldjhklasddasask ldjasldjhkl sddasaskldjasldj hklasddasaskl djasldjh klasddasaskldja ldjhklasdd</a>
                        <a class="vg-text" href="/">asaskldjasldjhklasdd</a>
                        <a class="vg-text" href="/">asaskldjasldjhklasdd</a>
                    </div>
                </div>
            </div>
            <div class="vg-element vg-fifth">
                <div class="vg-element vg-half vg-right">
                    <div class="vg-element vg-text vg-center">
                        <span class="vg-firm-color5"><?=$user_name?></span>
                    </div>
                </div>
                <a href="<?=PATH?>login/logout/1" class="vg-element vg-half vg-center">
                    <div>
                        <img src="<?=PATH.ADMIN_TEMPLATE?>img/out.png" alt="">
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="vg-main vg-right vg-relative">
        <div class="vg-wrap vg-firm-background-color1 vg-center vg-block vg-menu">
            <?php if($tables):?>
                <?php foreach($tables as $link):?>
                    <a href="<?=PATH.ADMIN_PATH?>/show/<?=$link?>" class="vg-wrap vg-element vg-full vg-center <?=$link == $table ? 'active' : ''?>">
                        <div class="vg-element vg-half  vg-center">
                            <div>
                                <img src="<?=PATH.ADMIN_TEMPLATE?>img/<?=$leftMenu[$link]['img'] ? $leftMenu[$link]['img'] : 'pages.png'?>" alt="pages">
                            </div>
                        </div>
                        <div class="vg-element vg-half vg-center vg_hidden">
                            <span class="vg-text vg-firm-color5"><?=$leftMenu[$link]['name'] ? $leftMenu[$link]['name'] : $link?></span>
                        </div>
                    </a>
                <?php endforeach;?>
            <?php endif;?>
        </div>