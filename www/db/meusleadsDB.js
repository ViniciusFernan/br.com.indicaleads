var MEUSLEADS_DB = {
    initPage: function () {
        var usuario = JSON.parse( window.localStorage.getItem('usuario'));

        if( usuario==null ){
            window.localStorage.clear();
            window.location="index.html";
        };

        if(app.isOnline()===true){
            this.getListaLeadsMicroService(usuario.idUsuario, usuario.email, null);
        }else{
            navigator.notification.alert('Você não esta conectado à internet. \n Este recurso necessita de conexão com a internet. ', '','Desconectado', 'OK');
        }
    },


    getListaLeadsMicroService: function(idUsuario, email, idUltimoLead){
        if(app.isOnline()===true){
            alert(app.isOnline());
            var serial = window.localStorage.getItem('serial');
            $.ajax({
                url: urlWebservices+'/Leadservice/getListaLeadFromUsuario',
                type: 'POST',
                dataType: 'json',
                data:{ 'idUsuario': idUsuario, 'email': email, 'registroDeDispositivo': serial, ultimoLead: idUltimoLead },
                beforeSend: function(){
                    $("#deviceready").append('<img class="loader" src="./img/loader.gif">');
                },
                complete: function(){ },
                success: function(resp){
                    var table="";
                    if(resp.type=='success'){
                        $.each(resp.data ,function(x, lead){

                            table +='<div class="box-lead card mb-2 cardClose '+((lead.dataVisualizado != null && lead.idLeadStatus == null) ? 'noFeedback' : '')+'" data-idLead="'+lead.idLead+'">';
                                table +='<div class="row card-header" style="min-height: 46px;">';
                                    table +='<div class="col-6 col-md-6 p-0 "><h5 class="title ellipsis" ><i class="far fa-envelope icon-lead"></i>'+ lead.nome +'</h5></div>';
                                    table +='<div class="col-3 col-md-3 p-0 border-left border-right "><small class="small bold">DDD: '+ lead.dddTel +' </small></div>';
                                    var tipo = ((lead.idTipo==3) ? 'EMP' : ((lead.idTipo==2 ) ? 'FAM' : 'IND') );

                                    table +='<div class="col-2 col-md-2 p-0 "><i class="fas '+((lead.idTipo==3) ? 'fa-user-tie empresarial' : ((lead.idTipo==2 ) ? 'fa-users familiar' : 'fa-user individual') )+'  "></i> <small class="tipoitemsmall '+tipo+'" >'+tipo+'</small></div>';
                                    table +='<div class="col-1 col-md-1 p-0 border-left actionOpenLeadBox "><i class="fas fa-angle-double-down"></i></div>';
                                table +='</div>';
                                table +='<div class="row card-body" style="overflow-y: auto"></div>';
                                table +='<div class="row card-footer" style="min-height: 78px;" ></div>';
                            table +='</div>';
                        });
                    }else{
                        if(idUltimoLead==null){
                            table="<h4>Nenhum lead encontrado</h4>";
                        }else{
                            $('.maisLeads').fadeOut(200);
                        }

                    }

                    $('.loader').fadeOut().remove();
                    $('#litagemDeLeads').append(table);

                }
            });

        }else{
            navigator.notification.alert('Você não esta conectado à internet. \n Este recurso necessita de conexão com a internet. ', '','Desconectado', 'OK');
        }
    },




    getLeadsPorIdMicroService: function (idLead){

        if(app.isOnline()===true){
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
                        body='<div class="col-12 p-0" style="overflow-y: auto">';
                        body='<ul class="list-group" style="width: 100%">';
                        lead=resp.data;

                        var corTipo = (lead.idTipo == 3 ? "#d9534f" : (lead.idTipo == 2 ? "#f0ad4e" : "#5cb85c"));
                        body += '<li class="list-group-item d-flex linha-info-lead "> <i class="fas '+((lead.idTipo==3) ? 'fa-user-tie empresarial' : ((lead.idTipo==2 ) ? 'fa-users familiar' : 'fa-user individual') )+'  icon-lead "></i> <span style="color: ' + corTipo + '">' + lead.tipo + "<span></li>";

                        if(lead.operadora)
                            body += '<li class="list-group-item d-flex linha-info-lead ">Operadora: ' +lead.operadora + '</li>';

                        body +='<li class="list-group-item d-flex linha-info-lead "><i class="fas fa-address-card icon-lead"></i> '+ lead.nome +'</li>';

                        if (lead.email)
                            body += '<li class="list-group-item d-flex linha-info-lead "><i class="far fa-envelope icon-lead"></i> ' + lead.email + '</li>';

                        if (lead.dddTel && lead.tel)
                            body += '<li class="list-group-item d-flex linha-info-lead "><i class="fas fa-phone icon-lead"></i><a href="tel: '+lead.dddTel+lead.tel+'">(' + lead.dddTel + ') - ' + lead.tel + '</a></li>';

                        if (lead.dddCel && lead.cel)
                            body += '<li class="list-group-item d-flex linha-info-lead "><i class="fas fa-mobile-alt icon-lead"></i><a href="tel: '+lead.dddCel+lead.cel+'">(' + lead.dddCel + ') - ' + lead.cel + '</a></li>';


                        if (lead.empresa)
                            body += '<li class="list-group-item d-flex linha-info-lead "><i class="fas fa-building icon-lead"></i> ' + lead.empresa + '</li>';

                        if (lead.cidade)
                            body += '<li class="list-group-item d-flex linha-info-lead "><i class="fas fa-globe-americas icon-lead"></i> ' + lead.cidade + '</li>';

                        if (lead.qtdVidas)
                            body += '<li class="list-group-item d-flex linha-info-lead ">Quantidade de vidas: '+ lead.qtdVidas + '</li>';

                        if (lead.detalhes)
                            body += '<li class="list-group-item d-flex linha-info-lead "> <i class="fas fa-comments icon-lead"></i>'+ (lead.detalhes ? lead.detalhes.replace(/\n/g, "<br>") : "") + '</li>';


                        body+='</ul>';


                        if(lead.duplicado){

                            // LEAD REPETIDO
                            body += '<div class="panel-group view-repetidos" id="accordion">';
                            $.each(lead.duplicado, function (index, obj){

                                body += '<div class="panel panel-default">';
                                body += '<div class="panel-heading">';
                                body += '<h6 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapse-'+(index)+'"><i style="font-weight: bold; color: #f0ad4e;"   class="fas fa-window-restore" data-original-title="Lead Duplicado" title="Lead Duplicado"></i> Repetido '+(obj.dataCadastro)+' </a></h6>';
                                body += '</div>';
                                body += '<div id="collapse-'+(index)+'" class="panel-collapse collapse">';
                                body += '<div class="panel-body">';
                                body += '<ul class="to_do view_lead" style="border: 1px solid #d6d6d6; -webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px; ">';
                                if (obj.track)
                                    body += "<li><strong>Track: </strong>" + obj.track + "</li>";
                                if (obj.origem)
                                    body += "<li><strong>Origem: </strong>" + obj.origem + "</li>";

                                body += "<li><strong>Data: </strong>" + obj.dataCadastro + "</li>";
                                body += "<li><strong>Nome: </strong>" + obj.nome + "</li>";
                                body += "<li><strong>Email: </strong>" + obj.email + "</li>";
                                if (obj.dddTel && obj.tel)
                                    body += "<li><strong>Telefone: </strong>(" + obj.dddTel + ")" + obj.tel + "</li>";
                                if (obj.dddCel && obj.Cel)
                                    body += "<li><strong>Telefone Alt.: </strong>(" + obj.dddCel + ")" + obj.Cel + "</li>";
                                if (obj.qtdVidas)
                                    body += "<li><strong>Vidas: </strong>" + obj.qtdVidas + "</li>";
                                if (obj.empresa)
                                    body += "<li><strong>Empresa: </strong>" + obj.empresa + "</li>";
                                if (obj.cidade)
                                    body += "<li><strong>Cidade: </strong>" + obj.cidade + "</li>";
                                body += "<li><strong>Detalhes: </strong>";
                                if (obj.De_0_a_18_anos)
                                    body += "<p><strong>De 0 a 18 Anos: </strong>" + obj.De_0_a_18_anos + "</p>";
                                if (obj.De_19_a_23_anos)
                                    body += "<p><strong>De 19 a 23 Anos: </strong>" + obj.De_19_a_23_anos + "</p>";
                                if (obj.De_24_a_28_anos)
                                    body += "<p><strong>De 24 a 28 Anos: </strong>" + obj.De_24_a_28_anos + "</p>";
                                if (obj.De_29_a_33_anos)
                                    body += "<p><strong>De 29 a 33 Anos: </strong>" + obj.De_29_a_33_anos + "</p>";
                                if (obj.De_34_a_38_anos)
                                    body += "<p><strong>De 34 a 38 Anos: </strong>" + obj.De_34_a_38_anos + "</p>";
                                if (obj.De_39_a_43_anos)
                                    body += "<p><strong>De 39 a 43 Anos: </strong>" + obj.De_39_a_43_anos + "</p>";
                                if (obj.De_44_a_48_anos)
                                    body += "<p><strong>De 44 a 48 Anos: </strong>" + obj.De_44_a_48_anos + "</p>";
                                if (obj.De_49_a_53_anos)
                                    body += "<p><strong>De 49 a 53 Anos: </strong>" + obj.De_49_a_53_anos + "</p>";
                                if (obj.De_54_a_58_anos)
                                    body += "<p><strong>De 54 a 58 Anos: </strong>" + obj.De_54_a_58_anos + "</p>";
                                if (obj.De_59_anos_ou_mais)
                                    body += "<p><strong>Acima de 59 Anos: </strong>" + obj.De_59_anos_ou_mais + "</p>";

                                body += (obj.detalhes ? obj.detalhes.replace(/\n/g, "<br>") : "");
                                body+= '</li>';

                                if (obj.urlCadastro)
                                    body += "<li><strong>Origem: </strong>" + obj.urlCadastro + "</li>";
                                body += '</ul>';
                                body += '</div>';
                                body += '</div>';
                                body += '</div>';
                            });
                            body += '</div>';
                            // END LEAD REPETIDO
                        }



                        body+='</div>';



                        var footer='';

                        footer+='<label class="btn btn-sm btn-primary status-l   " data-idLeadStatus="1" data-idLead-btn="'+lead.idLead+'" >Aguardando Atendimento </label>';
                        footer+='<label class="btn btn-sm btn-warning status-l  " data-idLeadStatus="2" data-idLead-btn="'+lead.idLead+'" >Em Negociação </label>';
                        footer+='<label class="btn btn-sm btn-danger status-l  " data-idLeadStatus="3" data-idLead-btn="'+lead.idLead+'" >Lead Inválido </label>';
                        footer+='<label class="btn btn-sm btn-info status-l  " data-idLeadStatus="4" data-idLead-btn="'+lead.idLead+'" >Sem Interesse </label>';
                        footer+='<label class="btn btn-sm btn-success status-l  " data-idLeadStatus="5" data-idLead-btn="'+lead.idLead+'" >Finalizado </label>';
                        footer+='<label class="btn btn-sm btn-default btn-default-br status-l  " data-idLeadStatus="6" data-idLead-btn="'+lead.idLead+'" >Outros </label>';

                    }else{
                        body="<h4>OPS!</h4>";
                    }

                    $('[data-idLead="'+lead.idLead+'"]').addClass('noFeedback');
                    $('[data-idLead="'+lead.idLead+'"]').find('.card-body').html(body);
                    $('[data-idLead="'+lead.idLead+'"]').find('.card-footer').html(footer);
                    $('.loader').fadeOut().remove();

                    $('[data-idLead="'+lead.idLead+'"]').find('.card-header .actionOpenLeadBox .fas').removeClass('fa-angle-double-down').addClass('fa-times fecharLead');
                    $('[data-idLead="'+lead.idLead+'"]').addClass('flayCard').removeClass('cardClose');

                    //scroll para evitar recarregar a pagina com scrollto js
                    var top = $("#deviceready").scrollTop();
                    if(top==0){ $("#deviceready").animate({scrollTop:2}, '500'); }



                }
            });
        }else{
            navigator.notification.alert('Você não esta conectado à internet. \n Este recurso necessita de conexão com a internet. ', '','Desconectado', 'OK');
        }
    },




};
