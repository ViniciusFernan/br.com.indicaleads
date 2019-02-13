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


    getListaLeadsMicroService: function(idUsuario, email){
        var serial = window.localStorage.getItem('serial');
        $.ajax({
            url: urlWebservices+'/Leadservice/getListaLeadFromUsuario',
            type: 'POST',
            dataType: 'json',
            data:{ 'idUsuario': idUsuario, 'email': email, 'registroDeDispositivo': serial },
            beforeSend: function(){
                $("#deviceready").append('<img class="loader" src="./img/loader.gif">');
            },
            complete: function(){ },
            success: function(resp){
                var table="";
                if(resp.type=='success'){
                    $.each(resp.data ,function(x, lead){
                        table +='<div class="box-lead card mb-2 cardClose" data-idLead="'+lead.idLead+'">';
                            table +='<div class="row card-header">';
                                table +='<div class="col-6 col-md-6 p-0 "><h5 class="title ellipsis" ><i class="far fa-envelope icon-lead"></i>'+ lead.nome +'</h5></div>';
                                table +='<div class="col-3 col-md-3 p-0 border-left border-right "><small class="small bold">DDD: '+ lead.dddTel +' </small></div>';
                                var tipo = ((lead.idTipo==3) ? 'EMP' : ((lead.idTipo==2 ) ? 'FAM' : 'IND') );

                                table +='<div class="col-2 col-md-2 p-0 "><i class="fas '+((lead.idTipo==3) ? 'fa-user-tie empresarial' : ((lead.idTipo==2 ) ? 'fa-users familiar' : 'fa-user individual') )+'  "></i> <small class="tipoitemsmall '+tipo+'" >'+tipo+'</small></div>';
                                table +='<div class="col-1 col-md-1 p-0 border-left actionOpenLeadBox "><i class="fas fa-angle-double-down"></i></div>';
                            table +='</div>';
                            table +='<div class="row card-body" style="overflow-y: auto"></div>';
                        table +='</div>';
                    });
                }else{
                    table="<h4>Nenhum lead encontrado</h4>";
                }

                $('.loader').fadeOut().remove();
                $('#litagemDeLeads').append(table);

            }
        });
    },




    getLeadsPorIdMicroService: function (idLead){
        var serial = window.localStorage.getItem('serial');
        var usuarioL = JSON.parse( window.localStorage.getItem('usuario'));
        $.ajax({
            url: urlWebservices+'/Leadservice/getLeadPorId',
            type: 'POST',
            dataType: 'json',
            data:{ 'idUsuario': usuarioL.idUsuario, 'idLead': idLead, 'registroDeDispositivo': serial },
            beforeSend: function(){
                $("#deviceready").append('<img class="loader" src="./img/loader.gif">');
            },
            complete: function(){ },
            success: function(resp){
                var body='';
                if(resp.type=='success'){
                    var body='<div class="col-12 p-0" style="overflow-y: auto"><ul class="list-group">';
                    lead=resp.data;

                    var corTipo = (lead.idTipo == 3 ? "#d9534f" : (lead.idTipo == 2 ? "#f0ad4e" : "#5cb85c"));
                    body += '<li class="list-group-item d-flex linha-info-lead "> <i class="fas '+((lead.idTipo==3) ? 'fa-user-tie empresarial' : ((lead.idTipo==2 ) ? 'fa-users familiar' : 'fa-user individual') )+'  icon-lead "></i> <span style="color: ' + corTipo + '">' + lead.tipo + "<span></li>";

                    if(lead.operadora)
                        body += '<li class="list-group-item d-flex linha-info-lead ">Operadora: ' +lead.operadora + '</li>';

                    body +='<li class="list-group-item d-flex linha-info-lead "><i class="fas fa-address-card icon-lead"></i> '+ lead.nome +'</li>';

                    if (lead.email)
                        body += '<li class="list-group-item d-flex linha-info-lead "><i class="far fa-envelope icon-lead"></i> ' + lead.email + '</li>';

                    if (lead.dddTel && lead.tel)
                        body += '<li class="list-group-item d-flex linha-info-lead "><i class="fas fa-phone icon-lead"></i>(' + lead.dddTel + ') - ' + (lead.tel ? lead.tel : '') + '</li>';

                    if (lead.dddCel && lead.cel)
                        body += '<li class="list-group-item d-flex linha-info-lead "><i class="fas fa-mobile-alt icon-lead"></i>(' + lead.dddCel + ') - ' + (lead.cel ? lead.cel : '') + '</li>';


                    if (lead.empresa)
                        body += '<li class="list-group-item d-flex linha-info-lead "><i class="fas fa-building icon-lead"></i> ' + lead.empresa + '</li>';

                    if (lead.cidade)
                        body += '<li class="list-group-item d-flex linha-info-lead "><i class="fas fa-globe-americas icon-lead"></i> ' + lead.cidade + '</li>';

                    if (lead.qtdVidas)
                        body += '<li class="list-group-item d-flex linha-info-lead ">Quantidade de vidas: '+ lead.qtdVidas + '</li>';


                    body += '<li class="list-group-item d-flex linha-info-lead "> <i class="fas fa-comments icon-lead"></i>'+ (lead.detalhes ? lead.detalhes.replace(/\n/g, "<br>") : "") + '</li>';


                    body+='</ul></div>';

                }else{
                    body="<h4>OPS!</h4>";
                }
                $('[data-idLead="'+lead.idLead+'"]').find('.card-body').html(body);
                $('.loader').fadeOut().remove();

                $('[data-idLead="'+lead.idLead+'"]').find('.card-header .actionOpenLeadBox .fas').removeClass('fa-angle-double-down').addClass('fa-times fecharLead');
                $('[data-idLead="'+lead.idLead+'"]').addClass('flayCard').removeClass('cardClose');

                //scroll para evitar recarregar a pagina com scrollto js
                var top = $("#deviceready").scrollTop();
                if(top==0){ $("#deviceready").animate({scrollTop:2}, '500'); }



            }
        });
    },




};
