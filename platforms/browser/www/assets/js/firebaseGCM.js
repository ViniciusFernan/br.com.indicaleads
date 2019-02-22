var GOOGLE_SENDER_ID = '396611735164';
var firebaseGCM={

    initialize: function () {
        this.bindEvents();
    },

    bindEvents: function () {
        document.addEventListener('deviceready', this.onDeviceReady, false);
    },

    onDeviceReady: function () {
        firebaseGCM.setupPush();
    },

    setupPush: function () {
        try {
            var push = PushNotification.init({
                "android" : {
                    "senderID" : GOOGLE_SENDER_ID ,
                    "vibrate": true,
                    "sound": true,
                    "forceShow": true,
                    "icon": 'icon',
                    "iconColor": '#a4b1e2'
                },
                "browser" : {
                    "pushServiceURL" : 'http://push.api.phonegap.com/v1/push'
                },
                "ios" : {
                    "sound" : true,
                    "vibration" : true,
                    "badge" : true
                }
            });



            push.on('registration', function (data) {
                var currentRegId = localStorage.getItem('gcmId');
                if (currentRegId !== data.registrationId) {
                    currentRegId = data.registrationId;
                }
                localStorage.setItem('gcmId', currentRegId);
                firebaseGCM.registroDoDispositivo(currentRegId);

            });

            push.on('error', function (e) {
                navigator.notification.alert(e.message, '','Push error', 'FECHAR');
            });

            push.on('notification', function (data) {
                navigator.notification.alert(
                    data.message,       // message
                    null,               // callback
                    data.title,         // title
                    'Ok'                // buttonName
                );
            });


        } catch (exception) {
            navigator.notification.alert(exception, '','ERROR', 'FECHAR');
        }
    },

    registroDoDispositivo: function(gcmId){

        var usuario = JSON.parse( window.localStorage.getItem('usuario'));
        var serial = window.localStorage.getItem('serial');

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: urlWebservices+'/Notificationservice/registroDeDispositivo',
            data:{ 'gcmId': gcmId, 'idUsuario': usuario.idUsuario, 'registroDeDispositivo': serial },
            beforeSend: function(){ },
            complete: function(){ },
            success: function(x){

            },
            error: function (error) {
                navigator.notification.alert(error, '','ERROR', 'FECHAR');
            }
        });

    }
};