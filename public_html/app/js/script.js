$(document).ready(function () {
    $("#hideButton").click(function () {
        $(".vg-carcass").hasClass("vg-hide") ? $(".vg-carcass").removeClass("vg-hide") : $(".vg-carcass").addClass("vg-hide");
    })
    $("#searchButton").click(function () {
        $(".vg-search").addClass("vg-search-reverse");
        $(".vg-search").children("input").focus();
        $(".vg-search").focusout(function () { $(".vg-search").removeClass("vg-search-reverse") });
    });
})