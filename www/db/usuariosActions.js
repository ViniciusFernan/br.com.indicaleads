var USUARIOS_ACTIONS = {

    getUsuario: function(){
        return JSON.parse(window.localStorage.getItem('usuario'));
    },

    setUsuario: function(usuario){
        //var senha = usuario.senha;
        window.localStorage.setItem('usuario', JSON.stringify(usuario));
        window.localStorage.setItem('imgPerfil', usuario.imgPerfil);
    },

    getUsuarioMicroService: function (email, senha){
        var serial = window.localStorage.getItem('serial');
        if(app.isOnline()==true){
            return $.ajax({
                type: 'POST',
                dataType: 'json',
                url: urlWebservices+'/Usuariosservice/getUsuarioFromEmailAndPassword',
                data:{ 'email': email, 'senha': senha, 'registroDeDispositivo': serial },
                beforeSend: function(){ },
                complete: function(){ },
                success: function(x){
                    return x;
                },
                error: function (error) {
                    return false;
                }
            });

        }else{
            navigator.notification.alert('Você não esta conectado à internet. \n Este recurso necessita de conexão com a internet. ', '','Desconectado', 'OK');
        }
    },



    atualizarUsuarioMicroService: function(Data){
        if(!Data){
            console.log("ERROR MEUSDADOS: Atualização inapropriada do usuario");
        }else{
            if(app.isOnline()==true){
                return $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: urlWebservices+'/Usuariosservice/usuarioUpdate',
                    data:Data,
                    beforeSend: function(){ },
                    complete: function(){ },
                    success: function(x){
                        return x;
                    },
                    error: function (error) {
                        return false;
                    }
                });
            }else{
                navigator.notification.alert('Você não esta conectado à internet. \n Este recurso necessita de conexão com a internet. ', '','Desconectado', 'OK');
            }
        }
    },


    updateUsuarioLocal: function(NewUser){
        var usuario = JSON.parse( window.localStorage.getItem('usuario'));
        for (var key in NewUser){
            usuario[key] = NewUser[key];
        }

        window.localStorage.setItem('usuario', JSON.stringify(usuario));
        window.localStorage.setItem('imgPerfil', usuario.imgPerfil);
        return true;
    },


};
