<?php

/**
 * Usuario.model [ MODEL USUARIO ]
 * Responsável por gerenciar os usuários no Admin do sistema!
 */
class UsuariosModel {

    private $Data;
    private $User; //idUsuario
    private $Error;
    private $Result;

    //Paginacao
    private $paginacao;


    //Nome da tabela no banco de dados
    const Entity = 'usuario';


    /**
     * <b>Verificar Cadastro:</b> Retorna TRUE se o cadastro ou update for efetuado ou FALSE se não.
     * Para verificar erros execute um getError();
     * @return BOOL $Var = True or False
     */
    public function getResult() {
        return $this->Result;
    }

    /**
     * <b>Obter Erro:</b> Retorna um array associativo com um erro e um tipo.
     * @return ARRAY $Error = Array associatico com o erro
     */
    public function getError() {
        return $this->Error;
    }




    /*
     * ***************************************
     * **********  PRIVATE METHODS  **********
     * ***************************************
     */

    //Gera a senha combinando com o HASH
    private function geraSenha($senha) {
        return md5(HASH . $senha);
    }


    private function criptarSenhaParaApp($senha) {
        return base64_encode(HASH ."__". $senha);
    }

    private function deCriptarSenhaParaApp($senha){
        $pw = base64_decode($senha);
        $pw = explode('__', $pw);
        if(is_array($pw)){
            return $pw[1];
        }else{
            return NULL;
        }

    }




    /**
     * Seleciona o Usuário
     * @param Int $idUsuario
     */
    public function getUserFromId($idUsuario) {

        $sql = 'SELECT * FROM  usuario WHERE usuario.idUsuario =:idUsuario AND usuario.del = 0';

        $Select = new Select;
        $Select->FullSelect($sql, "idUsuario={$idUsuario}");
        if (!$Select->getResult()):
            $this->Error = ["Usuário não encontrado", ERR_ERROR, true];
            $this->Result = false;
        else:
            $this->Result = $Select->getResult()[0];
        endif;
    }


    /**
     * Seleciona o Usuário
     * @param Int $idUsuario
     */
    public function getUserFromEmailAndPassword($email, $senha) {

        $sql = 'SELECT usuario.idUsuario, usuario.idCorretora, usuario.idEquipe, usuario.idPerfil, usuario.nome, usuario.apelido, usuario.email, usuario.senha, usuario.telefone, usuario.CPF, usuario.inicioAtendimento, usuario.fimAtendimento, usuario.status, usuario.imgPerfil
                FROM  usuario
                WHERE usuario.email=:email
                AND usuario.senha=:senha ';

        $Select = new Select;
        $Select->FullSelect($sql, "email={$email}&senha={$this->geraSenha($senha)}");
        if (!empty($Select->getResult())):
            $this->Result = $Select->getResult()[0];
            $this->Result['senha']="";
        else:
            $this->Result = false;
        endif;
    }

    /**
     * Update o Usuário
     * @param Int $idUsuario
     */
    public function updateUsuario($data){
        $usuarioAtivo = [];
        $serialAtivo = [];
        $idUsuario = $data['idUsuario'];
        $serialApp = $data['passID'];

        $data['telefone'] = (!empty($data['telefone']) ? str_replace(['(', ')', '-'], '', $data['telefone']) : NULL );
        $data['senha'] = (!empty($data['senha']) ?  $this->geraSenha($data['senha'])  : NULL );

        // Remove os valores nulos
        unset($data['idUsuario'],  $data['email'], $data['idEquipe'], $data['idPerfil'], $data['passID']);
        $data = array_filter($data);
        

        $this->getUserFromId($idUsuario);
        $usuarioAtivo = $this->getResult();
        $serialAtivo = Check::checkRegistroDeDispositivoDoUsuario($idUsuario, $serialApp);

        if(!empty($usuarioAtivo) && !empty($serialAtivo)){
            $update = new Update();
            $update->ExeUpdate('usuario', $data, "WHERE idUsuario=:idUsuario", "idUsuario={$idUsuario}");
            if (!empty($update->getResult())):
                $this->Result = $update->getResult();
            else:
                $this->Result = false;
            endif;
        }else{
            $this->Result = false;
        }

    }



    public function getTodosUsuariosAplicativoInstalado(){
        $this->Result=[];

        $sql = 'SELECT * FROM  app_id_user
                WHERE app_id_user.gcmid IS NOT NULL';

        $Select = new Select;
        $Select->FullSelect($sql);
        if (!empty($Select->getResult())):
            $this->Result = $Select->getResult();
        endif;

    }



    public function getDispositivosDoUsuario($idUsuario){
        $this->Result=[];

        $sql = 'SELECT gcmId FROM  app_id_user
                WHERE app_id_user.idUsuario=:idUsuario';

        $Select = new Select;
        $Select->FullSelect($sql, "idUsuario={$idUsuario}");
        if (!empty($Select->getResult())):
            $this->Result = $Select->getResult();
        endif;

    }



    public function registrarDispositivoUsuario($idUsuario, $idDispositivo){
        $idRegistroApp = Check::checkDispositivo($idDispositivo);
        if(empty($idRegistroApp)){
            $ct = new Create;
            $ct->ExeCreate('app_id_user',['idUsuario'=>$idUsuario, 'idDispositivo' => $idDispositivo ]);
            return $ct->getResult();
        }else{
            $updateDispositivo = new Update;
            $updateDispositivo->ExeUpdate('app_id_user', ['idUsuario'=>$idUsuario, 'idDispositivo' => $idDispositivo ], 'WHERE idAppIdUser=:idAppIdUser', "idAppIdUser={$idRegistroApp[0]['idAppIdUser']}");
            return $updateDispositivo->getResult();
        }

    }


    public function updateImagemPerfilUsuario($post, $files){

        $newImageUploaded=NULL;
        if(!empty($post) && !empty($files)){
            //verificar se usuario existe e app esta liberado para usar metodo
            $usuarioApp = NULL;
            $usuarioApp = Check::checkRegistroDeDispositivoDoUsuario($post['idUsuario'], $post['appID']);

            //UPLOAD DE IMAGEM
            if(!empty($usuarioApp)){
                //_upload/usuarios/idUsuario/foto.jpeg => 800 x auto
                $NewName = 'imgPerfil';
                $newImageUploaded = $this->processaImagensUsuarios($post['idUsuario'], $files, $NewName, 800, 800, true,'perfil',true);
                if($newImageUploaded){
                    //_upload/usuarios/idUsuario/foto-min.jpeg => 250 x 250
                    $NewName = 'min-imgPerfil';
                    $this->processaImagensUsuarios($post['idUsuario'], $files, $NewName, 250, 250, true, 'perfil',true);
                }

                //atualizar usuario com imagem de perfil
                $dataIMG['imgPerfil'] = $newImageUploaded;
                $dataIMG['idUsuario'] = $post['idUsuario'];
                $dataIMG['passID'] = $post['appID'];
                $this->updateUsuario($dataIMG);
            }
        }
        return $newImageUploaded;
    }



    private function processaImagensUsuarios($idUsuario, $FILES, $NewName, $x=NULL, $y=NULL, $crop=false, $pacote='', $rename=false){
        $dir = $_SERVER['DOCUMENT_ROOT']."/_uploads/".$idUsuario."/".(!empty($pacote)? $pacote.'/' : '' );
        require_once ABSPATH.'/lib/uploadVerot/class.upload.php';

        $handle = new upload($FILES);
        $handle->file_new_name_body  = $NewName;
        $handle->dir_auto_create     = true;
        $handle->image_convert       = 'jpg';
        $handle->image_resize        = true;
        $handle->image_ratio         = true;
        $handle->image_ratio_fill    = true;
        $handle->image_ratio_crop    = $crop;

        if($x){ $handle->image_x     = $x; }

        if($y){ $handle->image_y     = $y;}

        $handle->file_overwrite      = ($rename==true ? false : true );
        $handle->file_auto_rename    = $rename; //rename arquivo se ja existir
        $handle->allowed             = array('image/jpeg','image/jpg','image/gif','image/png');
        $handle->process($dir);
        $newImage = $handle->file_dst_name;

        if($handle->processed) {
            return $newImage;
        }else{
            return false;
        }

    }


}
