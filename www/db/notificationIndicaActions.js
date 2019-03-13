var NOTIFICATIONS_INDICA = {
    constructor: function () {
        var usuario = JSON.parse( window.localStorage.getItem('usuario'));
        if( usuario==null ){
            window.localStorage.clear();
            window.location="index.html";
        };
    },

    getNotificationsMicroService: function(idUsuario, tipoNotificacao=null){
        var serial = window.localStorage.getItem('serial');
        if(app.isOnline()==true){
            return $.ajax({
                type: 'POST',
                dataType: 'json',
                url: urlWebservices+'/Notificationservice/getNotifications',
                data:{ 'idUsuario': idUsuario, 'tipoNotificacao': tipoNotificacao, 'registroDeDispositivo': serial },
                beforeSend: function(){ },
                complete: function(){ },
                success: function(x){
                    return x;
                },
                error: function (error) {
                    return false;
                }
            });
        }

    },

    notificationsDashboard: function(){
        //var html = '';
        var NotificLocal = JSON.parse( window.localStorage.getItem('notifyLocal'));

        if( NotificLocal != null && NotificLocal.notificacao != null ){
            $.each(NotificLocal.notificacao ,function(x, notific){
                var html = '';
                var classIcon = ((notific.idTipoNotificacao ==10)? 'fa-ban' : 'fa-envelope' );
                html +='<div id="cardAvisos" class="card mb-3 shadow animated bounceInDown '+((notific.idTipoNotificacao ==10)? 'bloqueado' : '' ) +' " data-notification="'+notific.idNotificacao+'">';
                html +='    <i class="fas fa-times removeCard" ></i>';
                html +='    <div class="row no-gutters">';
                html +='        <div class="col-3 box-icon" ><i class="fas '+classIcon+' icon-item" ></i></div>';
                html +='        <div class="col-9">';
                html +='            <div class="card-body">';
                if(notific.linkPage){
                    html +='            <h5 class="card-title mb-0"><a href="./'+notific.linkPage+'" rel="external" data-openItem="'+notific.idReferencia+'" >'+notific.tipoNotificacao+'</a></h5>';
                }else{
                    html +='            <h5 class="card-title mb-0">'+notific.tipoNotificacao+'</h5>';
                }

                html +='                <hr class="mt-1 mb-2">';
                if(notific.linkPage){
                    html +='            <p class="card-text"><a href="./'+notific.linkPage+'" rel="external" data-openItem="'+notific.idReferencia+'" >'+notific.descricao+'</a></p>';
                }else{
                    html +='            <p class="card-text">'+notific.descricao+'</p>';
                }

                html +='            </div>';
                html +='        </div>';
                html +='    </div>';
                html +='</div>';

                if(!$('[data-notification="'+notific.idNotificacao+'"]').length ){
                    $('.conteudoBody').prepend(html);
                }
            });
            //$('.conteudoBody').html(html);
        }
    },

    clearNotificationClick: function(idNotification){
        var serial = window.localStorage.getItem('serial');
        var usuario = JSON.parse( window.localStorage.getItem('usuario'));
        if(app.isOnline()==true){
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: urlWebservices+'/Notificationservice/setNotificationStatus',
                data:{ 'idUsuario': usuario.idUsuario, 'idNotificacao': idNotification, 'registroDeDispositivo': serial },
                beforeSend: function(){ },
                complete: function(){ },
                success: function(response){
                    window.localStorage.setItem('notifyLocal', JSON.stringify(response));
                }
            });
        }
    },


    notificationsConfiguracoes: function(configuracoes){
        var serial = window.localStorage.getItem('serial');
        var usuario = JSON.parse( window.localStorage.getItem('usuario'));

        if(app.isOnline()==true){
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: urlWebservices+'/Notificationservice/setNotificationsConfiguracoes',
                data:{ 'idUsuario': usuario.idUsuario, 'configuracoes': JSON.stringify(configuracoes), 'registroDeDispositivo': serial },
                beforeSend: function(){ },
                complete: function(){ },
                success: function(response){
                    window.localStorage.setItem('configNotificacoesApp', JSON.stringify(response));
                }
            });
        }
    }

};
