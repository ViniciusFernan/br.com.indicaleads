var NOTIFICATIONS_INDICA = {
    constructor: function () {
        var usuario = JSON.parse( window.localStorage.getItem('usuario'));
        if( usuario==null ){
            window.localStorage.clear();
            window.location="index.html";
        };
    },

    getNotificationsMicroService: function(idUsuario, tipoNotificacao){
        if(app.isOnline()==true){
            return $.ajax({
                type: 'POST',
                dataType: 'json',
                url: urlWebservices+'/Notificationservice/getNotifications',
                data:{ 'idUsuario': idUsuario, 'tipoNotificacao': tipoNotificacao },
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

    }

};
