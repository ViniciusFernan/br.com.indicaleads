<?php

/**
 * Controller Usuarios - Responsável por toda administração de usuario do sistema
 * @package Sistema de Lead
 * @author Inaweb
 * @version 1.0
 */

class UsuariosserviceController extends MainController {

    public function indexAction() {
        $this->parametros;
        $this->parametrosPost;
        echo json_encode('NO Action');
    }

    public function getUsuarioFromEmailAndPasswordAction(){

        $resp['type'] = 'error';
        $resp['data'] = [];

        require ABSPATH . "/models/usuarios/Usuarios.model.php";
        $usuario = new UsuariosModel;


        if(!empty($this->parametrosPost)):
            if(!empty($this->parametrosPost['email']) && !empty($this->parametrosPost['senha'])):
                $usuario->getUserFromEmailAndPassword($this->parametrosPost['email'], $this->parametrosPost['senha'] );
                $usuario->getResult();
                if(!empty($usuario->getResult())):
                    $registroDispositivo = $usuario->registrarDispositivoUsuario($usuario->getResult()['idUsuario'], $this->parametrosPost['registroDeDispositivo']);

                    if(!empty($registroDispositivo) ):
                        $resp['type'] = 'success';
                        $resp['data'] = $usuario->getResult();
                    else:
                        $resp['type'] = 'error';
                        $resp['data'] = '';
                    endif;
                else:
                    $resp['type'] = 'error';
                    $resp['data'] = '';
                endif;
            endif;
        endif;
        echo json_encode($resp);
        exit;
    }



    public function UsuarioupdateAction(){
        $resp['type'] = 'error';
        $resp['data'] = [];

        require ABSPATH . "/models/usuarios/Usuarios.model.php";
        $usuario = new UsuariosModel;

        if(!empty($this->parametrosPost['passID'])):
            $usuario->updateUsuario($this->parametrosPost);
            if(!empty($usuario->getResult())):
                $resp['type'] = 'success';
                $resp['data'] = $usuario->getResult();
            endif;
        endif;
        echo json_encode($resp);
        exit;
    }

    public function updateImagemPerfilAction(){
        $resp['type'] = 'error';
        $resp['data'] = [];

        require ABSPATH . "/models/usuarios/Usuarios.model.php";
        $usuario = new UsuariosModel;

        if(!empty($this->parametrosPost['appID']) && !empty($_FILES)):
            $RSP = $usuario->updateImagemPerfilUsuario($this->parametrosPost, $_FILES["file"]);
            if($RSP){
                $resp['type'] = 'success';
                $resp['data'] = $RSP;
            }else{
                $resp['type'] = 'error';
                $resp['data'] = 'error interno ops!';
            }
        endif;
        echo json_encode($resp);
        exit;


    }


}
