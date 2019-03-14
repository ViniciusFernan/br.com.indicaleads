<?php
/**
 * Created by PhpStorm.
 * User: Vinicius
 * Date: 27/02/2019
 * Time: 11:10
 */
require_once '../config.php';

require ABSPATH . "/models/notifications/Notifications.model.php";
require ABSPATH . "/models/notifications/NotificationsPush.model.php";


$Notification = new NotificationsModel;
$Push = new NotificationsPushModel;





$SQL="SELECT plantao.*, app_id_user.idAppIdUser FROM plantao  
      INNER JOIN app_id_user ON app_id_user.idUsuario = plantao.idUsuario
      WHERE DATE(plantao.dataPlantao) = CURRENT_DATE()   AND plantao.statusPlantao=1
      GROUP BY app_id_user.idAppIdUser, plantao.idPlantao";

$sel = new Select;
$sel->FullSelect($SQL);
$plantoesCorrente = $sel->getResult();

if(!empty($plantoesCorrente)){
    foreach ($plantoesCorrente as $key => $plantaoCorrente){
        if($Notification->checkSeNotificacaoJaFoiCadastrada( $plantaoCorrente['idUsuario'], $plantaoCorrente['idPlantao'], 5)==false ){
            $Notification->registraNotificacao($plantaoCorrente['idUsuario'], 5, 0, 0, $plantaoCorrente['idPlantao'], NULL, NULL, 'plantao');
        }
    }
}




$Push->SendNotificationsPlantaoDiario();

