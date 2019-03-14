<?php
/**
 * Created by PhpStorm.
 * User: Vinicius
 * Date: 27/02/2019
 * Time: 11:10
 */

require_once '../config.php';

require ABSPATH . "/models/notifications/NotificationsPush.model.php";
$Push = new NotificationsPushModel;



$Push->SendNotificationsNovoLead();

$Push->SendNotifications10MinutosParaPerder();

$Push->SendNotificationsBloqueadoPorPerderLead();


