// JavaScript Document
/*验证提示*/
function popup(text) {
    $(".popup").html(text);
    $(".popupDom").stop().animate({
        "top": "0px"
    }, 400);
    setTimeout(function () {
        $(".popupDom").stop().animate({
            "top": "-40px"
        }, 400);
    }, 2000);
}
/*翻转*/
var x, y, n = 0, ny = 0, nx = 180, rotYINT
function rotateYDIV()
{
    x = document.getElementById("register")
    y = document.getElementById("login")
    clearInterval(rotYINT)
    rotYINT = setInterval("startYRotate()", 1)
}


function startYRotate()
{
    ny = ny + 1
    nx = nx + 1
    y.style.transform = "rotateY(" + ny + "deg)"
    y.style.webkitTransform = "rotateY(" + ny + "deg)"
    y.style.OTransform = "rotateY(" + ny + "deg)"
    y.style.MozTransform = "rotateY(" + ny + "deg)"
    x.style.transform = "rotateY(" + nx + "deg)"
    x.style.webkitTransform = "rotateY(" + nx + "deg)"
    x.style.OTransform = "rotateY(" + nx + "deg)"
    x.style.MozTransform = "rotateY(" + nx + "deg)"
    if (ny == 180 || ny == 360 || nx == 540 || nx == 360)
    {
        clearInterval(rotYINT)
        if (ny >= 360 || nx >= 540) {
            ny = 0;
            nx = 180;
        }
    }
    if (ny == 90 || ny == 270) {
        x.style.display = "block";
        y.style.display = "none";
    }
    if (nx == 450) {
        x.style.display = "none";
        y.style.display = "block";
    }

}
/*ie中placeholder*/
$(function () {
    if (!placeholderSupport()) {   // 判断浏览器是否支持 placeholder
        $('[placeholder]').focus(function () {
            var input = $(this);
            if (input.val() == input.attr('placeholder')) {
                input.val('');
                input.removeClass('placeholder');
            }
        }).blur(function () {
            var input = $(this);
            if (input.val() == '' || input.val() == input.attr('placeholder')) {
                input.addClass('placeholder').css("color", "#999").css("line-height", "60px");
                input.val(input.attr('placeholder'));
            }
        }).blur();
    }
    ;
})
function placeholderSupport() {
    return 'placeholder' in document.createElement('input');
}

