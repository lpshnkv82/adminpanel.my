/*Подключение визуального редактора*/

tinymce.init({
    language : "ru",
    mode:'exact',
    elements:"content, short_content, content_main_page",
    gecko_spellcheck:true,
    /*forced_root_block : false,
    force_p_newlines : false,
    force_br_newlines : true,*/
    height: 300,
    plugins: [
        "advlist autolink lists link image charmap print preview hr anchor pagebreak",
        "searchreplace wordcount visualblocks visualchars code fullscreen",
        "insertdatetime media nonbreaking save table contextmenu directionality",
        "emoticons template paste textcolor colorpicker textpattern jbimages"
    ],
    toolbar: "insertfile undo redo | styleselect | bold italic | forecolor backcolor emoticons | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image jbimages | formatselect fontsizeselect |code",
    relative_urls: false
});
/*Подключение визуального редактора*/
