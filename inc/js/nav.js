$(document).ready(function() {
    $(window).scroll(function() {
        var headerH = $('header').outerHeight(true);
        console.log(headerH); //this will calculate header's full height, with borders, margins, paddings
        var scrollVal = $(this).scrollTop();
        if ( scrollVal > headerH ) {
            $('nav').css({'position':'fixed','top' :'0px'});
        } else {
            $('nav').css({'position':'static','top':'0px'});
        }
    });
});