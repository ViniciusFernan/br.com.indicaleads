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
        var html = '';
        var NotificLocal = JSON.parse( window.localStorage.getItem('notifyLocal'));

        if( NotificLocal != null && NotificLocal.notificacao != null ){
            $.each(NotificLocal.notificacao ,function(x, notific){
                var classIcon = ((notific.idTipoNotificacao ==10)? 'fa-ban' : 'fa-envelope' );
                html +='<div id="cardAvisos" class="card mb-3 shadow animated bounceInDown '+((notific.idTipoNotificacao ==10)? 'bloqueado' : '' ) +' " data-notification="'+notific.idNotificacao+'">';
                html +='    <i class="fas fa-times removeCard" ></i>';
                html +='    <div class="row no-gutters">';
                html +='        <div class="col-3 box-icon" ><i class="fas '+classIcon+' icon-item" ></i></div>';
                html +='        <div class="col-9">';
                html +='            <div class="card-body">';
                html +='                <h5 class="card-title mb-0">'+notific.tipoNotificacao+'</h5>';
                html +='                <hr class="mt-2 mb-3">';
                html +='                <p class="card-text">'+notific.descricao+'</p>';
                html +='            </div>';
                html +='        </div>';
                html +='    </div>';
                html +='</div>';
            });
            $('.conteudoBody').html(html);
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
    }

};
