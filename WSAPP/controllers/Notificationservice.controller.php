<?php

/**
 * Controller Usuarios - Responsável por toda administração de usuario do sistema
 * @package Sistema de Lead
 * @author Inaweb
 * @version 1.0
 */

class NotificationserviceController extends MainController {

    public function indexAction() {
        $this->parametros;
        $this->parametrosPost;
        echo json_encode('NO Action');
    }


    public function TesteGCMAction(){

        require ABSPATH . "/models/usuarios/Usuarios.model.php";
        require ABSPATH . "/models/notifications/NotificationsGCM.model.php";
        $usuario = new UsuariosModel;

        $GCMNotification = new NotificationsGCMModel;

        $usuario->getDispositivosDoUsuario('123');
        foreach($usuario->getResult() as $key => $value ){
            if(!empty($value['gcmId'])){
                $chaveDoDispositivo=$value['gcmId'];
                $titulo='Novo teste';
                $mensagem='Push funcionando legal! ;)';

                $GCMNotification->sendMesagemGCMAction($chaveDoDispositivo, $titulo, $mensagem, NULL, NULL);
            }
        }
        exit;
    }


    /**
     * NOTIFICAR O APP ENQUANTO ELE ESTIVER EM FIREGROUNG;
     */
    public function getNotificationsAction(){
        $resp=[];
        $data=[];
        $resp['type'] = 'error';
        $resp['notificacao'] = [];

        require ABSPATH . "/models/notifications/Notifications.model.php";
        $notifications = new NotificationsModel;

        if(!empty($this->parametrosPost)):
            if(!empty($this->parametrosPost['idUsuario']) && !empty($this->parametrosPost['registroDeDispositivo']) && Check::checkRegistroDeDispositivoDoUsuario($this->parametrosPost['idUsuario'], $this->parametrosPost['registroDeDispositivo'] ) ):
                $tipoNotification = (!empty($this->parametrosPost['tipoNotificacao']) ? $this->parametrosPost['tipoNotificacao'] : NULL);
                $notifications->getNotifications($this->parametrosPost['idUsuario'], $tipoNotification);
                if(!empty($notifications->getResult())):
                    $resp['type'] = 'success';
                    $resp['notificacao'] = $notifications->getResult();
                    $resp['hash'] = md5(json_encode($notifications->getResult()));
                endif;
            endif;
        endif;
        echo json_encode($resp);
        exit;
    }





    /**
     * Registra dispositivo na base de dados com o usuario ativo
     */
    public function  registroDeDispositivoAction(){

        $resp=[];
        $data=[];
        $resp['type'] = 'error';
        $resp['data'] = [];

        require ABSPATH . "/models/notifications/Notifications.model.php";
        $notifications = new NotificationsModel;

        require ABSPATH . "/models/dispositivo/Dispositivo.model.php";
        $dispositivo = new DispositivoModel;


        if(!empty($this->parametrosPost)):
            if(!empty($this->parametrosPost['idUsuario'])  && !empty($this->parametrosPost['registroDeDispositivo'])  &&  !empty($this->parametrosPost['gcmId'])  ):
                $dispositivo->registrarDispositivoNotifications($this->parametrosPost['idUsuario'], $this->parametrosPost['registroDeDispositivo'], $this->parametrosPost['gcmId']);
                if(!empty($dispositivo->getResult())):
                    $resp['type'] = 'success';
                    $resp['data'] = $dispositivo->getResult();
                endif;
            endif;
        endif;
        echo json_encode($resp);
        exit;

    }


    public function setNotificationStatusAction(){
        $resp=[];
        $data=[];
        $resp['type'] = 'error';
        $resp['notificacao'] = [];

        require ABSPATH . "/models/notifications/Notifications.model.php";
        $notifications = new NotificationsModel;

        if(!empty($this->parametrosPost)):
            if(!empty($this->parametrosPost['idNotificacao']) && !empty($this->parametrosPost['idUsuario']) && !empty($this->parametrosPost['registroDeDispositivo']) && Check::checkRegistroDeDispositivoDoUsuario($this->parametrosPost['idUsuario'], $this->parametrosPost['registroDeDispositivo'] ) ):
                $notifications->atualizaStatusNotificacao($this->parametrosPost['idNotificacao'], 3);
                if(!empty($notifications->getResult())):

                    $notifications->getNotifications($this->parametrosPost['idUsuario'], NULL);
                    if(!empty($notifications->getResult())):
                        $resp['type'] = 'success';
                        $resp['notificacao'] = $notifications->getResult();
                        $resp['hash'] = md5(json_encode($notifications->getResult()));
                    endif;

                endif;
            endif;
        endif;
        echo json_encode($resp);
        exit;
    }


    public function setNotificationsConfiguracoesAction(){
        $resp=[];
        $data=[];
        $resp['type'] = 'error';
        $resp['configuracoes'] = [];
        $CheckRegistroApp=[];

        require ABSPATH . "/models/notifications/Notifications.model.php";
        $notifications = new NotificationsModel;

        if(!empty($this->parametrosPost)):

            $CheckRegistroApp = Check::checkRegistroDeDispositivoDoUsuario($this->parametrosPost['idUsuario'], $this->parametrosPost['registroDeDispositivo']) ;

            if(!empty($this->parametrosPost['configuracoes']) && !empty($this->parametrosPost['idUsuario']) && !empty($this->parametrosPost['registroDeDispositivo']) && Check::checkRegistroDeDispositivoDoUsuario($this->parametrosPost['idUsuario'], $this->parametrosPost['registroDeDispositivo'] ) ):
                $config = json_decode(stripslashes($this->parametrosPost['configuracoes']), true);
                $notifications->addConfiguracaoNotificationAplicativo($CheckRegistroApp[0]['idAppIdUser'], serialize($config) );
                if(!empty($notifications->getResult())):
                    $resp['type'] = 'success';
                    $resp['configuracoes'] = $config;
                endif;
            endif;
        endif;
        echo json_encode($resp);
        exit;
    }



}
