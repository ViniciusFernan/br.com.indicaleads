var MEUSDADOS_DB = {
    initPage: function () {
        var usuario = JSON.parse( window.localStorage.getItem('usuario'));
        if( usuario==null ){
            window.localStorage.clear();
            window.location="index.html";
        };


        if(usuario){

            var item = usuario;
            var table="";
            table +='<form class="updateMeusDados" method="post" >';
            table += '<div class="row mb-3"><div class="col-6 pr-2 boximgUser"><img src="./img/avatar.png" class="imgUser" id="imgemPerfilMeusDados" > <i class="fas fa-camera novaImagem"></i> </div><div class="col-6 pl-0"><p class="card-title form-group" >Nome: <input type="text" name="nome" value="' + item.nome + '"  class="form-control" ></p> <p class="card-title form-group" >Apelido: <input type="text" name="apelido" value="' + ((item.apelido) ? item.apelido : '') + '"  class="form-control" ></p> </div></div>';

            table +='<p class="card-text form-group bold"><strong>Email:</strong> <b class="input-fake"> '+item.email+' </b> </p>';
            if(item.CPF){
                table +='<p class="card-text form-group bold">CPF: <b class="input-fake"> '+ item.CPF +' </b> </p>';
            }

            table +='<p class="card-text form-group">Telefone: <input type="tel" name="telefone" value="'+((item.telefone)? item.telefone : '')+'"  class="form-control" maxlength="25" placeholder="(99) 9 9999 9999"></p>';
            table +='<p class="card-title form-group boxSenha" >Alterar Senha: <input type="password" name="senha" value=""  class="form-control" > <i class="fa fa-eye verSenha"></i></p>';

            table +='<p class="card-text form-group bold"><strong>Periodo de atendimento: </strong>  <b class="input-fake">'+((item.inicioAtendimento) ? item.inicioAtendimento  : '' ) +  ((item.fimAtendimento) ? ' até '+item.fimAtendimento  : '' )+' </b> </p>';
            table +='<p class="card-text form-group"><strong>Status da conta: </strong> <b class="input-fake"> '+((item.status==1)? 'Ativo' :'Desativado')+' </b></p>';
            table +='<p class="card-text form-group text-right "> <button type="button" class="btn btn-primary salvar">Atualizar</button> </p>';
            table +='</form>';
            $('#conteudoBXf').append(table);
        };

    }

};
