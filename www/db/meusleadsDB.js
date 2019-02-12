var MEUSLEADS_DB = {
    initPage: function () {
        var usuario = JSON.parse( window.localStorage.getItem('usuario'));

        if( usuario==null ){
            window.localStorage.clear();
            window.location="index.html";
        };


        if(app.isOnline()==true){
            this.getListaLeadsMicroService(usuario.idUsuario, usuario.email);
        }else{
            navigator.notification.alert('Você não esta conectado à internet. \n Este recurso necessita de conexão com a internet. ', '','Desconectado', 'OK');
        }






    },


    getListaLeadsMicroService: function (idUsuario, email){
        var serial = window.localStorage.getItem('serial');
        $.ajax({
            url: urlWebservices+'/Leadservice/getListaLeadFromUsuario',
            type: 'POST',
            dataType: 'json',
            data:{ 'idUsuario': idUsuario, 'email': email, 'registroDeDispositivo': serial },
            beforeSend: function(){ },
            complete: function(){ },
            success: function(resp){
                var table="";
                if(resp.type=='success'){
                    $.each(resp.data ,function(x, lead){
                        table +='<div class="box-lead card mb-2 lead-close" data-idLead="'+lead.idLead+'">';
                            table +='<div class="row card-header">';
                                table +='<div class="col-6 col-md-6 p-0 "><h5 class="title ellipsis" ><i class="far fa-envelope icon-lead"></i>'+ lead.nome +'</h5></div>';
                                table +='<div class="col-3 col-md-3 p-0 border-left border-right "><small class="small bold">DDD: '+ lead.dddTel +' </small></div>';
                                table +='<div class="col-2 col-md-2 p-0 "><i class="fas '+((lead.idTipo==3) ? 'fa-user-tie empresarial' : ((lead.idTipo==2 ) ? 'fa-users familiar' : 'fa-user individual') )+'  "></i></div>';
                                table +='<div class="col-1 col-md-1 p-0 border-left actionOpenLeadBox "><i class="fas fa-angle-double-down"></i></div>';
                            table +='</div>';
                            table +='<div class="row card-body" ></div>';

                            table +='<div class="row rating-desc card-footer">';
                                table +='<div class="col-md-12">';
                                    table +='<a href="">Ver todas as informações</a>';
                                table +='</div>';
                            table +='</div>';
                        table +='</div>';
                    });
                }else{
                    table="<h4>Nenhum lead encontrado</h4>";
                }

                $('#litagemDeLeads').append(table);

            }
        });
    },




    getLeadsPorIdMicroService: function (idUsuario, idLead){
        var serial = window.localStorage.getItem('serial');
        $.ajax({
            url: urlWebservices+'/Leadservice/getListaLeadFromUsuario',
            type: 'POST',
            dataType: 'json',
            data:{ 'idUsuario': idUsuario, 'idLead': idLead, 'registroDeDispositivo': serial },
            beforeSend: function(){ },
            complete: function(){ },
            success: function(resp){
                var table="";
                if(resp.type=='success'){
                    $.each(resp.data ,function(x, lead){
                        table +='<div class="box-lead card mb-2 lead-close" data-idLead="'+lead.idLead+'">';
                        table +='<div class="row card-header">';
                        table +='<div class="col-6 col-md-6 p-0 "><h5 class="title ellipsis" ><i class="far fa-envelope icon-lead"></i>'+ lead.nome +'</h5></div>';
                        table +='<div class="col-3 col-md-3 p-0 border-left border-right "><small class="small bold">DDD: '+ lead.dddTel +' </small></div>';
                        table +='<div class="col-2 col-md-2 p-0 "><i class="fas '+((lead.idTipo==3) ? 'fa-user-tie empresarial' : ((lead.idTipo==2 ) ? 'fa-users familiar' : 'fa-user individual') )+'  "></i></div>';
                        table +='<div class="col-1 col-md-1 p-0 border-left actionOpenLeadBox "><i class="fas fa-angle-double-down"></i></div>';
                        table +='</div>';
                        table +='<div class="row card-body" ></div>';

                        table +='<div class="row rating-desc card-footer">';
                        table +='<div class="col-md-12">';
                        table +='<a href="">Ver todas as informações</a>';
                        table +='</div>';
                        table +='</div>';
                        table +='</div>';
                    });
                }else{
                    table="<h4>Nenhum lead encontrado</h4>";
                }

                $('#litagemDeLeads').append(table);

            }
        });
    },




};
