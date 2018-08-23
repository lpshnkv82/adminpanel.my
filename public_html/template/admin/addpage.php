<div class="vg_main admin_main products">
    <div class="panel_main div_jcfs div_aifs">
        
        <form class="left_side params div_fdc div_shdw" method="post" action="<?=PATH.ADMIN_PATH?>/add" enctype="multipart/form-data" table="<?=$table?>">
            <input type="hidden" name="table" value="<?=$table?>">

            <div class="params_row div_fww">
                <?php if(in_array('name', $columns)):?>
                    <div class="params_row div_half div_fww">
                        <div class="left_in div_fdc">
                            <label for="prod_name" class="par_name backgr_bef">Название</label>
                            <input id="prod_name" type="text" name="name" value="<?=htmlspecialchars($_SESSION['res']['name'])?>">
                        </div>
                    </div>
                <?php endif;?>

                <?php if(in_array('price', $columns)):?>
                    <div class="params_row div_half div_fww">
                        <div class="left_in div_fdc">
                            <label for="prod_name" class="par_name backgr_bef">Цена</label>
                            <input id="prod_name" type="text" name="price" value="<?=htmlspecialchars($_SESSION['res']['price'])?>">
                        </div>
                    </div>
                <?php endif;?>
            </div>

            <div class="params_row div_fww">

                <?php if(in_array('keywords', $columns)):?>
                    <div class="left_in div_half div_fdc">
                        <label for="keywords" class="par_name backgr_bef">Ключевые слова</label>
                        <span class="par_desc">(максимум 70 символов)</span>
                        <textarea id="keywords" rows="5" name="keywords"><?=htmlspecialchars($_SESSION['res']['keywords'])?></textarea>
                    </div>
                <?php endif;?>
                <?php if(in_array('description', $columns)):?>
                    <div class="right_in div_half div_fdc">
                        <label for="meta_desc" class="par_name backgr_bef">Метоописание</label>
                        <span class="par_desc">(максимум 160 символов)</span>
                        <textarea id="meta_desc" rows="5" name="description"><?=htmlspecialchars($_SESSION['res']['description'])?></textarea>
                    </div>
                <?php endif;?>
            </div>

            <div class="params_row div_fww">
                <?php if(in_array('img', $columns)):?>
                    <div class="left_in div_half div_fdc">
                        <label class="par_name backgr_bef">Основное изображение</label>
                        <span class="par_desc">(размер не более <?=MAX_FOTO_SIZE?>)</span>
                        <div class="prev_wrap div_df">
                            <div class="img_prev div_aic div_aifs main_img">
                                <?php if(!empty($_SESSION['res']['img'])):?>
                                    <img src="<?=PATH?><?=UPLOAD_DIR?><?=$_SESSION['res']['img']?>" alt="prev">
                                <?php endif;?>
                            </div>
                            <div class="controls div_fdc">
                                <label for="img_prev" class="btn_add bord_light backgr_hov_light color_light btn_prev div_jcc div_aic">
                                    <span>Изменить</span>
                                    <input type="file" name="img" id="img_prev" class="single_img">
                                </label>
                            </div>
                        </div>
                    </div>
                <?php endif;?>

                <?php if(in_array('gallery_img', $columns)):?>
                    <div class="right_in div_half div_fdc">
                        <label class="par_name backgr_bef">Галерея изображений</label>
                        <span class="par_desc">(размер не более <?=MAX_FOTO_SIZE?>)</span>
                        <div class="gal_wrap">
                            <?php $i = 0;?>
                            <?php if(!empty($_SESSION['res']['gallery_img'])):?>
                                <?php $gallery_img = explode("|", $_SESSION['res']['gallery_img']);?>
                                <?php foreach($gallery_img as $img):?>
                                    <?php $i++;?>
                                    <div class="gal_item div_aic div_jcc">
                                        <img class="vg_delete" style="cursor:pointer;" src="<?=PATH?><?=UPLOAD_DIR?><?=$img?>" alt="gallery">
                                    </div>
                                <?php endforeach;?>
                            <?php endif;?>
                            <?php $i = 0;?>
                            <?php while($i < 14):?>
                                <div class="gal_item gal_item_free div_aic div_jcc">
                                    <img src="<?=PATH?><?=ADMIN_TEMPLATE?>img/gallery/gal_prev.jpg" alt="gallery">
                                </div>
                                <?php $i++;?>
                            <?php endwhile;?>
                            <label for="btn_plus" class="gal_item div_aic div_jcc">
                                    <span class="btn_plus bord_color backgr_bef hover">
                                        <input type="file" name="gallery_img[]" id="btn_plus" multiple>
                                    </span>
                            </label>
                        </div>
                    </div>
                <?php endif;?>
            </div>

            <?php if(in_array('short_content', $columns)):?>
                <div class="params_row div_fww">
                    <div class="left_in div_fdc">
                        <label for="prod_desc" class="par_name backgr_bef">Краткое описание</label>
                        <span class="par_desc">(максимум 1000 символов)</span>
                        <textarea id="prod_desc" rows="5" name="short_content"><?=htmlspecialchars($_SESSION['res']['short_content'])?></textarea>
                    </div>
                </div>
            <?php endif;?>

            <?php if(in_array('content', $columns)):?>
                <div class="params_row div_fww">
                    <div class="left_in div_fdc">
                        <label for="prod_desc" class="par_name backgr_bef">Описание</label>
                        <span class="par_desc">(максимум 1000 символов)</span>
                        <textarea id="prod_desc" rows="5" name="content"><?=htmlspecialchars($_SESSION['res']['content'])?></textarea>
                    </div>
                </div>
            <?php endif;?>

            <?php if(in_array('parent_id', $columns)):?>
                <div class="params_row display div_df">
                    <div class="params_row div_fdc">
                        <span class="par_name backgr_bef">Выберите категорию</span>
                        <div class="select_wrap bord_top_aft">
                            <select id="select-1" name="parent_id">
                                <option class="select_item" value="0" selected >Родительская категория</option>
                                <?php if($main_pages):?>
                                    <?php foreach($main_pages as $main_page):?>
                                        <option class="select_item" value="<?=$main_page['id']?>" <?php if($page['parent_id'] == $main_page['id']) echo 'selected';?>><?=$main_page['name']?></option>
                                    <?php endforeach;?>
                                <?php endif;?>
                            </select>
                        </div>
                    </div>
                </div>
            <?php endif;?>

            <?php if(in_array('visible', $columns)):?>
                <div class="params_row display div_df">
                    <span class="par_name">Отображение на сайте</span>
                    <label class="custom_label div_aic" for="yes">
                        <input id="yes" type="radio" name="visible" value="1" checked>
                            <span class="custom_check backgr_bef">
                            </span>
                        <span class="label">Да</span>
                    </label>
                    <label class="custom_label div_aic" for="no">
                        <input id="no" type="radio" name="visible" value="0" <?php if($_SESSION['res']['visible'] === 0) echo 'checked';?>>
                            <span class="custom_check backgr_bef">
                            </span>
                        <span class="label">Нет</span>
                    </label>
                </div>
            <?php endif;?>

            <div class="controls_wrap div_df">
                <button class="btn_add btn_save btn_high color_light bord_light backgr_hov_light div_aic div_jcc">
                    <span>Сохранить</span>
                </button>
            </div>
        </form>