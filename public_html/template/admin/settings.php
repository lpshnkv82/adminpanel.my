<div class="vg_main products_container">
    <div class="panel_main div_jcfs div_aifs">

        <form class="left_side params div_fdc div_shdw" method="post" action="<?=PATH.ADMIN_PATH?>/settings" enctype="multipart/form-data" table="<?=$table?>">
            <div class="params_row div_fww">
                <div class="left_in div_half div_fdc">
                    <label for="prod_name" class="par_name backgr_bef">Название организации</label>
                    <input id="prod_name" type="text" name="name" value="<?=htmlspecialchars($set['name'])?>">
                </div>
            </div>
            <div class="params_row div_fww">
                <div class="left_in div_fdc">
                    <label for="prod_name" class="par_name backgr_bef">Тип компании</label>
                    <input id="prod_name" type="text" name="type" value="<?php echo isset($_SESSION['res']['type']) ? htmlspecialchars($_SESSION['res']['type']) : htmlspecialchars($set['type']);?>">
                </div>
            </div>
            <div class="params_row div_fww">
                <div class="left_in div_fdc">
                    <label for="prod_name" class="par_name backgr_bef">Заголовок категорий</label>
                    <input id="prod_name" type="text" name="sidebar_header" value="<?php echo isset($_SESSION['res']['sidebar_header']) ? htmlspecialchars($_SESSION['res']['sidebar_header']) : htmlspecialchars($set['sidebar_header']);?>">
                </div>
            </div>

            <div class="params_row div_fww">
                <div class="left_in div_half div_fdc">
                    <label for="keywords" class="par_name backgr_bef">Ключевые слова</label>
                    <span class="par_desc">(максимум 70 символов)</span>
                    <textarea id="keywords" rows="5" name="keywords"><?php echo isset($_SESSION['res']['keywords']) ? htmlspecialchars($_SESSION['res']['keywords']) : htmlspecialchars($set['keywords'])?></textarea>
                </div>
                <div class="right_in div_half div_fdc">
                    <label for="meta_desc" class="par_name backgr_bef">Метоописание</label>
                    <span class="par_desc">(максимум 160 символов)</span>
                    <textarea id="meta_desc" rows="5" name="description"><?php echo isset($_SESSION['res']['description']) ? htmlspecialchars($_SESSION['res']['description']) : htmlspecialchars($set['description'])?></textarea>
                </div>
            </div>
            <div class="params_row div_fww">
                <div class="left_in div_half div_fdc">
                    <label for="prod_name" class="par_name backgr_bef">Адрес</label>
                    <input id="prod_name" type="text" name="adress" value="<?=htmlspecialchars($set['adress'])?>">
                </div>
            </div>
            <div class="params_row div_fww">
                <div class="left_in div_half div_fdc">
                    <label for="prod_name" class="par_name backgr_bef">Телефон</label>
                    <span class="par_desc" style="color:red; font-size: 12px">Введите номера телефонов через запятую</span>
                    <input id="prod_name" type="text" name="phone" value="<?=htmlspecialchars($set['phone'])?>">
                </div>
            </div>
            <div class="params_row div_fww">
                <div class="left_in div_half div_fdc">
                    <label for="prod_name" class="par_name backgr_bef">E-mail</label>
                    <input id="prod_name" type="text" name="email" value="<?=htmlspecialchars($set['email'])?>">
                </div>
            </div>
            <br>
            <div class="left_in div_half div_fdc">
                <label class="par_name backgr_bef">Основное изображение</label>
                <span class="par_desc">(размер не более <?=MAX_FOTO_SIZE?>)</span>
                <div class="prev_wrap div_df">
                    <div class="img_prev div_aic div_aifs main_img">
                        <?php if(!empty($set['img'])):?>
                            <img src="<?=PATH?><?=UPLOAD_DIR?><?=$set['img']?>" alt="prev">
                        <?php endif;?>
                        <?php if(!empty($set['thumbnails'])):?>
                            <?php $thumbs = explode("|", $set['thumbnails']);?>
                            <?php foreach ($thumbs as $item):?>
                                <div <?php echo strpos($item, 'cut_height') !== false ? 'class="cut_height"' : ''?>>
                                    <img class="vg_delete" src="<?=PATH?><?=UPLOAD_DIR?><?=$item?>" alt="prev">
                                </div>
                            <?php endforeach;?>
                        <?php endif;?>
                    </div>
                    <div class="controls div_fdc">
                        <label for="img_prev" class="btn_add bord_light backgr_hov_light color_light btn_prev div_jcc div_aic">
                            <span>Изменить</span>
                            <input type="file" name="img" id="img_prev">
                        </label>
                    </div>
                </div>
            </div>
            <div class="params_row div_fww">
                <div class="left_in div_fdc">
                    <label for="prod_desc" class="par_name backgr_bef">Основное описание</label>
                    <!--<span class="par_desc">(максимум 1000 символов)</span>-->
                    <textarea id="prod_desc" rows="5" name="content"><?php echo isset($_SESSION['res']['content']) ? htmlspecialchars($_SESSION['res']['content']) : htmlspecialchars($set['content'])?></textarea>
                </div>
            </div>

            <div class="controls_wrap div_df">
                <button class="btn_add btn_save btn_high color_light bord_light backgr_hov_light div_aic div_jcc">
                    <span>Сохранить</span>
                </button>
            </div>
        </form>