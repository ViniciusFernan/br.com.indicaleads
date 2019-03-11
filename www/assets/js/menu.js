$('.open-menu').on('click swiperight', function (){
    menu.openMenu();
});

$('.close-menu').on('click swipeleft', function (){
    menu.hideMenu();
});

$('.box-menu').on('swipeleft', function (){
    menu.hideMenu();
});
$('body').on('swiperight', function (avf){
    var window_width_action = $( window ).width() * 0.25;
    if( avf.swipestart.coords[0] < window_width_action) {
        menu.openMenu();
    }
});

$('body').on('tap', function(avf){
    var classes = $(avf.target).hasClass('open-menu');
    if(avf.pageX < 20 && classes==false){
        menu.alertMenu();
    }
 });


var menu = {
    openMenu: function() {
        $('.box-config').stop().css('right', '-100%');
        $('.box-menu').stop().css('left', '0%');
    },
    hideMenu: function() {
        $('.box-menu').stop().css('left', '-100%');
    },
    alertMenu:function(){
        $('.box-menu').stop().css('left', '-75%').delay(800).queue(function(nxt) {
            $('.box-menu').css('left', '-100%');
            nxt();
        })
    }
}



window.onload = function() {
    var elements = document.getElementsByTagName('*'),
        i;
    for (i in elements) {
        if (elements[i].hasAttribute && elements[i].hasAttribute('data-include')) {
            fragment(elements[i], elements[i].getAttribute('data-include'));
        }
    }
    function fragment(el, url) {
        var localTest = /^(?:file):/,
            xmlhttp = new XMLHttpRequest(),
            status = 0;

        xmlhttp.onreadystatechange = function() {
            /* if we are on a local protocol, and we have response text, we'll assume
 *  				things were sucessful */
            if (xmlhttp.readyState == 4) {
                status = xmlhttp.status;
            }
            if (localTest.test(location.href) && xmlhttp.responseText) {
                status = 200;
            }
            if (xmlhttp.readyState == 4 && status == 200) {
                el.outerHTML = xmlhttp.responseText;
            }
        }

        try {
            xmlhttp.open("GET", url, true);
            xmlhttp.send();
        } catch(err) {
            /* todo catch error */
        }
    }
}

