<!DOCTYPE html>
<!--[if IE 8]><html lang="ru" class="ie8"><![endif]-->
<!--[if IE 9]><html lang="ru" class="ie9"><![endif]-->
<!--[if !IE]><!--><html lang="ru"><!--<![endif]-->
<head>
    <meta charset="utf-8">

    <title><?=$title?></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php foreach($styles as $style):?>
        <link rel="stylesheet" href="<?=$style?>">
    <?php endforeach;?>

</head>

<body>

<div class="top_menu div_aic div_jcsb">
    <div class="logo">
        <a href="<?=PATH?>" target="_blank" style="color: white; font-weight: bold">САЙТ</a>
    </div>
    <div>
        <?php foreach($tables as $link):?>
            <?php if($add_str[$link]):?>
                <a style="color:white; margin-right: 10px" href="<?=PATH?>admin/add/<?=$link?>"><?=$add_str[$link]?></a>
            <?php else:?>
                <a style="color:white; margin-right: 10px" href="<?=PATH?>admin/add/<?=$link?>">Add <?=$link?></a>
            <?php endif;?>
        <?php endforeach;?>
    </div>
    <div class="menu_wrap div_jcsb div_aic">
        <div class="mnu_name div_aic">
            <div class="icon backgr_color"></div>
            <span class="name"><?=$user_name?></span>
        </div>
        <div class="mnu_exit">
            <a href="<?=PATH?>login/logout/<?=$user_id?>" class="exit div_df">
                <img src="<?=PATH?><?=ADMIN_TEMPLATE?>img/icon_exit.png" alt="exit">
            </a>
        </div>
    </div>
</div>
<div class="left_menu div_fdc">
    <div class="logo">
        <a class="div_aic div_jcc" href="https://veles-web.ru" target="_blank"><img src="<?=PATH?><?=ADMIN_TEMPLATE?>img/logo_small.png" alt="logo"></a>
    </div>

    <a class="div_aic div_jcc <?=$menu_class['admin']?>" href="<?=PATH.ADMIN_PATH?>">
        <img class="white" src="<?=PATH?><?=ADMIN_TEMPLATE?>img/mnu_white.png" alt="2">
        <img class="red" src="<?=PATH?><?=ADMIN_TEMPLATE?>img/mnu_red.png" alt="2">
    </a>
    <a class="div_aic div_jcc <?=$menu_class['portfolio']?>" href="<?=PATH.ADMIN_PATH?>/portfolio">
        <img class="white" src="<?=PATH?><?=ADMIN_TEMPLATE?>img/portfolio_white.png" alt="2">
        <img class="red" src="<?=PATH?><?=ADMIN_TEMPLATE?>img/portfolio_red.png" alt="2">
    </a>
    <a class="div_aic div_jcc <?=$menu_class['news']?>" href="<?=PATH.ADMIN_PATH?>/news">
        <img class="white" src="<?=PATH?><?=ADMIN_TEMPLATE?>img/review_white.png" alt="2">
        <img class="red" src="<?=PATH?><?=ADMIN_TEMPLATE?>img/review_red.png" alt="2">
    </a>
    <a class="div_aic div_jcc <?=$menu_class['workers']?>" href="<?=PATH.ADMIN_PATH?>/workers">
        <img class="white" src="<?=PATH?><?=ADMIN_TEMPLATE?>img/workers_white.png" alt="2">
        <img class="red" src="<?=PATH?><?=ADMIN_TEMPLATE?>img/workers_red.png" alt="2">
    </a>
    <a class="div_aic div_jcc <?=$menu_class['media']?>" href="<?=PATH.ADMIN_PATH?>/media">
        <img class="white" src="<?=PATH?><?=ADMIN_TEMPLATE?>img/media_white.png" alt="2">
        <img class="red" src="<?=PATH?><?=ADMIN_TEMPLATE?>img/media_red.png" alt="2">
    </a>
    <a class="div_aic div_jcc <?=$menu_class['settings']?>" href="<?=PATH.ADMIN_PATH?>/settings">
        <img class="white" src="<?=PATH?><?=ADMIN_TEMPLATE?>img/settings_white.png" alt="2">
        <img class="red" src="<?=PATH?><?=ADMIN_TEMPLATE?>img/settings_red.png" alt="2">
    </a>
    <a class="div_aic div_jcc <?=$menu_class['users']?>" href="<?=PATH.ADMIN_PATH?>/users">
        <img class="white" src="<?=PATH?><?=ADMIN_TEMPLATE?>img/users_white.png" alt="1">
        <img class="red" src="<?=PATH?><?=ADMIN_TEMPLATE?>img/users_red.png" alt="1">
    </a>
</div>
<div class="vg_main admin_main products">
    
    <?php if($_SESSION['res']['message']){
        echo $_SESSION['res']['message'];
        unset($_SESSION['res']['message']);
    }
    ?>
    <div class="panel_main div_jcfs div_aifs">
        <div class="panel_products">