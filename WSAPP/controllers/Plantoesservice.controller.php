<?php

/**
 * Controller Lead - Responsável por toda administração de lead do sistema
 * @package Sistema de Lead
 * @author Inaweb
 * @version 1.0
 */

class PlantoesserviceController extends MainController {

    public function indexAction() {
        $this->parametros;
        $this->parametrosPost;
        echo json_encode('NO Action');
    }

    public function getListaPlantoesFromUsuarioAction(){

        $resp['type'] = 'error';
        $resp['data'] = [];

        require ABSPATH . "/models/plantoes/Plantoes.model.php";
        $plantoes = new PlantoesModel;

        if(!empty($this->parametrosPost)):
            if(!empty($this->parametrosPost['idUsuario']) && !empty($this->parametrosPost['registroDeDispositivo']) && Check::checkRegistroDeDispositivoDoUsuario($this->parametrosPost['idUsuario'], $this->parametrosPost['registroDeDispositivo'] )):
                $plantoes->getListaDePlantoesDoUsuario($this->parametrosPost);
                if(!empty($plantoes->getResult())):
                    $resp['type'] = 'success';
                    $resp['data'] = $plantoes->getResult();
                else:
                    $resp['type'] = 'error';
                    $resp['data'] = '';
                endif;
            endif;
        endif;
        echo json_encode($resp);
        exit;

    }


}
