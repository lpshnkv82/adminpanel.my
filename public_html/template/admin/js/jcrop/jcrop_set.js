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

var x1, y1, x2, y2, w, h, tbl, id, rat_val;
var  jcrop_api;

/*Обработка изображений*/
$(".main_img").on('click', function(e){
    e.preventDefault();
    var img = $(this).children("img").attr('src');
    if(typeof img != 'undefined'){
        $('<img src="'+img+'" id="main_img_crop">').appendTo(".modal_image");
        $(".modal_image_wrap").css('display', 'flex');
        rat_val = 'cut_width';
        tbl = $(this).closest('form').attr("table");
        id = $(this).closest('form').children("input[name=id]").val();
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

        jcrop_api.setOptions(vgRatios[rat_val] ?
            { aspectRatio: vgRatios[rat_val] } : {  aspectRatio: 0 });
        jcrop_api.focus();

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
            'data' : JSON.stringify({'x1': x1, 'x2': x2, 'y1': y1, 'y2': y2, 'w': w, 'h': h, 'img': img, 'tbl': tbl, 'ratio': rat_val, 'id': id})
        },
        function(file) {
            jcrop_api.release();
            //var target = $("a.cut[rel="+rat_val+"]");
            if(id || tbl == 'settings'){
                //$(target).empty();
                $.ajax({
                    url: file,
                    type:"GET",
                    success: function(){
                        $(".main_img > div").remove();
                        $(".main_img").append('<div><img class="vg_delete" src="'+file+'"></div>');
                        vgDelete(".main_img > div > img.vg_delete");

                        $(".modal_image").empty();
                        $(".modal_image_wrap").unbind('click').css("display", 'none');
                    }
                });
            }else{
                //$(target).css("display", 'none');
                $(".modal_image").empty();
                $(".modal_image_wrap").unbind('click').css("display", 'none');
            }
        }
    );
});


