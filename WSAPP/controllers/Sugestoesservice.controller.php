<?php

/**
 * Controller Usuarios - Responsável por toda administração de usuario do sistema
 * @package Sistema de Lead
 * @author Inaweb
 * @version 1.0
 */

class SugestoesserviceController extends MainController {

    public function indexAction() {
        $this->parametros;
        $this->parametrosPost;
        echo json_encode('NO Action');
    }

    public function enviarSugestoesAction(){

        $resp['type'] = 'error';
        $resp['data'] = [];

        require ABSPATH . "/models/sugestoes/Sugestoes.model.php";
        $sugestoes = new SugestoesModel;

        if( !empty($this->parametrosPost) && Check::checkRegistroDeDispositivoDoUsuario($this->parametrosPost['idUsuario'], $this->parametrosPost['registroDeDispositivo'] ) ):
            $sugestoes->createFeedbackDoSistema($this->parametrosPost);

            if(!empty($sugestoes->getResult())):
                $resp['type'] = 'success';
                $resp['data'] = $sugestoes->getResult();

            else:
                $resp['type'] = 'error';
                $resp['data'] = '';
            endif;
        endif;
        echo json_encode($resp);
        exit;
    }


}
