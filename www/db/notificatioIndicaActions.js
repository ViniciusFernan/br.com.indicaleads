var NOTIFICATIONS_INDICA = {
    constructor: function () {
        var usuario = JSON.parse( window.localStorage.getItem('usuario'));
        if( usuario==null ){
            window.localStorage.clear();
            window.location="index.html";
        };

        NOTIFICATIONS_INDICA.getNotificationsMicroService(usuario.idUsuario, 1).done(function(response){
            if(response.type=='success'){
                notify('Novo Lead', 'Você recebeu novo lead.', 'success', true);
            }
        });
    },

    getNotificationsMicroService: function(idUsuario, tipoNotificacao){
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

};
