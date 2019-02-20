var PLANTOES_DB = {
    initPage: function () {
        var usuario = JSON.parse( window.localStorage.getItem('usuario'));

        if( usuario==null ){
            window.localStorage.clear();
            window.location="index.html";
        };

        this.getListaPlantoesMicroService(usuario.idUsuario, usuario.email);

    },

    getListaPlantoesMicroService: function(idUsuario, email){
        if(app.isOnline()==true){
            var serial = window.localStorage.getItem('serial');
            $.ajax({
                url: urlWebservices+'/Plantoesservice/getListaPlantoesFromUsuario',
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
                        $.each(resp.data ,function(x, plantao){
                            table+='<div class="card mb-3 card-plantao '+( PLANTOES_DB.checkDateCurrenteDate(plantao.dataPlantao) ? 'currentPlantao' : '' )+' "  data-plantao="'+ plantao.dataPlantao +'">';
                            table+='    <div class="row no-gutters">';
                            table+='        <div class="col-4 cart-left-box">';
                            table+='            <div class="data-box"><span class="diaSize">'+plantao.diaFormat+'</span><br/>'+PLANTOES_DB.formateMes(plantao.mesFormat)+'</div>';
                            table+='        </div>';
                            table+='        <div class="col-8">';
                            table+='            <div class="card-body section-box">';
                            table+='                <h5 class="card-title title"> Plantão - <small class="text-muted">'+plantao.dataFormatada+'</small></h5> <hr>';
                            table+='                <p class="card-text"><small >Atendimento: '+ PLANTOES_DB.fomateHora(plantao.inicioAtendimento) +' as '+ PLANTOES_DB.fomateHora(plantao.fimAtendimento) +' </small></p>';
                            table+='            </div>';
                            table+='        </div>';
                            table+='    </div>';
                            table+='</div>';
                        });
                    }else{
                        table="<h4>Nenhum plantão encontrado</h4>";
                    }

                    $('.loader').fadeOut().remove();
                    $('#litagemDePlantao').append(table);

                }
            });
        }else{
            navigator.notification.alert('Você não esta conectado à internet. \n Este recurso necessita de conexão com a internet. ', '','Desconectado', 'OK');
        }
    },

    fomateHora:function(date){
        if(date){
            var dateIni = date.split(':');
            return dateIni[0]+':'+dateIni[1];
        }else{
            return '00:00:';
        }
    },
    formateMes: function(mes){
        var MesExtenso = {1:'Janeiro', 2:'Fevereiro', 3:'Março', 4:'Abril', 5:'Maio', 6:'Junho', 7:'Julho', 8:'Agosto', 9:'Setembro', 10:'Outubro', 11:'Novembro', 12:'Dezembro'};
        if(mes){
            return MesExtenso[parseInt(mes)];
        }else{
            return 'error';
        }
    },

    checkDateCurrenteDate: function (date){
        var datePast = date.replace(/-/g, '/');
        var inputDate = new Date(datePast);
        var todaysDate = new Date();

        if(inputDate.setHours(0,0,0,0) == todaysDate.setHours(0,0,0,0)) {
            return true;
        }else{
            return false;
        }

    }

};
