<?php

/**
 * Controller Lead - Responsável por toda administração de lead do sistema
 * @package Sistema de Lead
 * @author Inaweb
 * @version 1.0
 */

class LeadserviceController extends MainController {

    public function indexAction() {
        $this->parametros;
        $this->parametrosPost;
        echo json_encode('NO Action');
    }

    public function getListaLeadFromUsuarioAction(){

        $resp['type'] = 'error';
        $resp['data'] = [];

        require ABSPATH . "/models/leads/Leads.model.php";
        $Leads = new LeadsModel;

        if(!empty($this->parametrosPost)):
            if(!empty($this->parametrosPost['idUsuario']) && !empty($this->parametrosPost['registroDeDispositivo']) && Check::checkRegistroDeDispositivoDoUsuario($this->parametrosPost['idUsuario'], $this->parametrosPost['registroDeDispositivo'] )):
                $Leads->getListaDeLeadDoUsuario($this->parametrosPost['idUsuario'], $this->parametrosPost['ultimoLead']);
                if(!empty($Leads->getResult())):
                    $resp['type'] = 'success';
                    $resp['data'] = $Leads->getResult();
                else:
                    $resp['type'] = 'error';
                    $resp['data'] = '';
                endif;
            endif;
        endif;
        echo json_encode($resp);
        exit;

    }


    public function getLeadPorIdAction(){

        $resp['type'] = 'error';
        $resp['data'] = [];

        require ABSPATH . "/models/leads/Leads.model.php";
        $Leads = new LeadsModel;

        if(!empty($this->parametrosPost)):
            if(!empty($this->parametrosPost['idLead']) && !empty($this->parametrosPost['idUsuario']) && !empty($this->parametrosPost['registroDeDispositivo']) && Check::checkRegistroDeDispositivoDoUsuario($this->parametrosPost['idUsuario'], $this->parametrosPost['registroDeDispositivo'] )):
                $Leads->getLeadDoUsuarioFromIdLead($this->parametrosPost['idLead'], $this->parametrosPost['idUsuario']);
                if(!empty($Leads->getResult()) && $Leads->setLeadVisualizado($this->parametrosPost['idLead'])==true ):
                    $resp['type'] = 'success';
                    $resp['data'] = $Leads->getResult();
                else:
                    $resp['type'] = 'error';
                    $resp['data'] = '';
                endif;
            endif;
        endif;
        echo json_encode($resp);
        exit;

    }


    public function setStatusLeadAction(){

        $resp['type'] = 'error';
        $resp['data'] = [];

        require ABSPATH . "/models/leads/Leads.model.php";
        $Leads = new LeadsModel;

        if(!empty($this->parametrosPost)):
            if(!empty($this->parametrosPost['idLead']) && !empty($this->parametrosPost['idUsuario']) && !empty($this->parametrosPost['idleadstatus']) && !empty($this->parametrosPost['registroDeDispositivo']) && Check::checkRegistroDeDispositivoDoUsuario($this->parametrosPost['idUsuario'], $this->parametrosPost['registroDeDispositivo'] )):
                $Leads->setStatusLead($this->parametrosPost);
                if(!empty($Leads->getResult())):
                    $resp['type'] = 'success';
                    $resp['data'] = $Leads->getResult();
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
