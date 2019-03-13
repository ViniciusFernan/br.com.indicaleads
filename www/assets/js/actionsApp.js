$(function () {
    $('body').on('tap', '.removeCard', function () {
        var $this = $(this).closest('#cardAvisos');
        $($this).removeClass('bounceInDown').addClass('bounceOutUp').delay(1000).queue(function() {
            $($this).remove();
        });
    });
});



$('body').on('tap', '.open-menu-config', function (){
    $('.box-menu').stop().css('left', '-100%');
    menuConfig.openMenuConfig();

});

$('body').on('tap', '.close-menu-config', function (){
    menuConfig.hideMenuConfig();
});




var menuConfig = {
    openMenuConfig: function() {
        $('.box-config').stop().css('right', '0%');
    },
    hideMenuConfig: function() {
        $('.box-config').stop().css('right', '-100%');
    },

    alertMenuConfig:function(){
        $('.box-config').stop().css('right', '-75%').delay(800).queue(function(nxt) {
            $('.box-config').css('right', '-100%');
            nxt();
        });
    }
}


$('body').on('change', '.notificatioStatus', function(){
    var objSend=[];
    $.each($('.notificatioStatus'), function(x, objeto){
        var item={};
        item['key'] = $(objeto).attr('name');
        item['val'] = (  ($(objeto).is(':checked')) ? 1 : 0  );
        objSend.push(item);
    });

    NOTIFICATIONS_INDICA.notificationsConfiguracoes(objSend);

});


$(window).on('tap', function (){
    var configNotificacoesApp =  ( window.localStorage.getItem('configNotificacoesApp') ? window.localStorage.getItem('configNotificacoesApp') : null );

    if(configNotificacoesApp != null){
        $.each(JSON.parse(configNotificacoesApp) , function (i, item){
            $('.notificatioStatus[name="'+item['key']+'"]').attr('checked',  (item['val']=='0')? false : true );
        });
    }
})