/*Обработка изображений*/
if($('div').is(".thumbnails_wrap")){

    // x1, y1, x2, y2 - Координаты для обрезки изображения
// crop - Папка для обрезанных изображений
// resolutions - разрешения

    var vgRatios = {};

    if(cropes){
        for(var key in cropes){
            vgRatios[key] = parseFloat(cropes[key][0]) / parseFloat(cropes[key][1]);
        }
    }
    /*vgRatios = {
     cut_width : 940/790,
     cut_height : 475/756
     }*/

    var x1, y1, x2, y2, w, h, tbl, id, id_row, thumb_name;
    var  jcrop_api, self;

    $(".toggle_menu").on('click', function(){
        $(".thumbnails_wrap .modal_image").empty();
        $(".thumbnails_wrap").css('display', 'none');
    });

    $(".main_img_show").on('click', function(e){
        e.preventDefault();
        self = $(this);
        var img = $(this).children("img").attr('src');
        if(typeof img != 'undefined'){
            $(".thumbnails_wrap .modal_image").empty();
            $('<img src="'+img+'" id="main_img_crop">').appendTo(".thumbnails_wrap .modal_image");
            $(".thumbnails_wrap").css('display', 'flex');
            thumb_name = $(this).parent().prev().find('input[type=file]').attr('name') + 'thumbnails';
            tbl = $(this).closest('form').find("input[name=table]").val();
            id = $(this).closest('form').children("input[type=hidden]:eq(0)").val();
            id_row = $(this).closest('form').children("input[type=hidden]:eq(0)").attr('name');
            cropImage();
            $(".modal_image_wrap").on('click', function(e){
                e.stopPropagation();
                if($(e.target).is(".modal_image_wrap")){
                    $(".modal_image").empty();
                    $(".modal_image_wrap").unbind('click').css("display", 'none');
                }
            });
        }
    });
    /*Обработка изображений*/

    function cropImage(){
        jQuery(function($){
            $('#main_img_crop').Jcrop({
                onChange: showCoords,
                onSelect: showCoords
            },function(){
                jcrop_api = this;
            });
            // Снять выделение
            $('#release').click(function(e) {
                release();
            });

            // Соблюдать пропорции
            $('.thumb_crop').change(function(e) {
                if(this.checked){
                    $('.thumb_crop').prop('checked', false);
                    this.checked = true;
                }
                jcrop_api.setOptions(this.checked?
                    { aspectRatio: vgRatios[this.value] }: {  aspectRatio: 0 });
                jcrop_api.focus();
            });

            jcrop_api.setOptions({aspectRatio: 0});
            jcrop_api.focus();
            // Соблюдать пропорции

            // Установка  минимальной/максимальной ширины и высоты
            $('#size_lock').change(function(e) {
                jcrop_api.setOptions(this.checked?  {
                    minSize:  [ 80, 80 ],
                    maxSize:  [ 350, 350 ]
                }: {
                    minSize:  [ 0, 0 ],
                    maxSize:  [ 0, 0 ]
                });
                jcrop_api.focus();
            });
            // Изменение координат
            function showCoords(c){
                x1 = c.x;  $('#x1').val(c.x);
                y1 = c.y;  $('#y1').val(c.y);
                x2 = c.x2;  $('#x2').val(c.x2);
                y2 = c.y2;  $('#y2').val(c.y2);
                w = c.w; $('#w').val(c.w);
                h = c.h; $('#h').val(c.h);
                if(c.w > 0 && c.h > 0){
                    $('#crop').show();
                }else{
                    $('#crop').hide();
                }
            }
        });
        function  release(){
            jcrop_api.release();
            $('#crop').hide();
        }
// Обрезка изображение и вывод результата
        jQuery(function($){

        });
    }
    $('#crop').click(function(e) {
        var img =  $('#main_img_crop').attr('src');
        $.post(path,
            {
                'ajax': 'crop',
                'data' : JSON.stringify({
                    'x1': x1, 'x2': x2, 'y1': y1, 'y2': y2, 'w': w, 'h': h,
                    'img': img, 'tbl': tbl, 'thumb_name': thumb_name, 'id': id, 'id_row': id_row})
            },
            function(file) {
                jcrop_api.release();
                //var target = $("a.cut[rel="+rat_val+"]");
                if(file != 'add_session'){
                    //$(target).empty();
                    $(".thumbnails_wrap .modal_image").empty();
                    $(self).closest('.img_container').find(".thumbnails_show").find('.img_show').empty().append('<img class="vg_delete" src="'+file+'?ver='+ Math.random() +'">');

                    vgDelete($(self).closest('.img_container').find(".thumbnails_show").find('.img_show').children('img'));

                    $(".thumbnails_wrap .modal_image").empty();
                    $(".thumbnails_wrap").unbind('click').css("display", 'none');
                }else{
                    $(".thumbnails_show").children().children().children('span').css('color', 'red').text('Шаблон сохранен');
                    $(".thumbnails_wrap .modal_image").empty();
                    $(".thumbnails_wrap").unbind('click').css("display", 'none');
                }
            }
        );
    });
}



