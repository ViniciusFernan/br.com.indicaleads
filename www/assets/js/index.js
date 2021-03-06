/*
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 */
var urlWebservices="http://backend.com.br/WSAPP";
var urlUploads="http://backend.com.br/_uploads";
var GOOGLE_SENDER_ID='1046179569753';
var app = {
    // Application Constructor
    initialize: function() {
        this.bindEvents();
    },

    // Bind Event Listeners
    // Bind any events that are required on startup. Common events are:
    // 'load', 'deviceready', 'offline', and 'online'.
    bindEvents: function() {
        document.addEventListener('deviceready', this.onDeviceReady, false);
    },
    // deviceready Event Handler
    // The scope of 'this' is the event. In order to call the 'receivedEvent'
    // function, we must explicitly call 'app.receivedEvent(...);'
    onDeviceReady: function() {
        app.receivedEvent('deviceready');
    },
    // Update DOM on a Received Event
    receivedEvent: function(idPage) {

        var usuario = JSON.parse( window.localStorage.getItem('usuario'));
        var infoUsuario = document.getElementById("infoUsuario");

        if(usuario !=null && infoUsuario ){
            var html="";
            html+="<p class='infoNome'>" + usuario.nome + "</p>";
            html+="<p class='infoEmail'>" + usuario.email + "</p>";

            document.getElementById('infoUsuario').innerHTML = '<p class="infoNome">' + html +'</p>';

            var imagemPerfil = ((usuario.imgPerfil !== null)  ?  urlUploads +"/"+ usuario.idUsuario +"/perfil/"+usuario.imgPerfil : './img/avatar.png');
            ((document.getElementById('imgemPerfilMeusDadosMenu')) ? document.getElementById('imgemPerfilMeusDadosMenu').src = imagemPerfil : '' );
        }

        var devicePlatform = device.platform;
        var serial = ((devicePlatform === 'browser') ? '123456-AVF' : device.serial);
        window.localStorage.setItem('serial', serial);


        setTimeout(function(){
            document.getElementById('deviceready').classList.remove('animated');
        }, 800);


        //document.addEventListener("backbutton", this.exitApp, false);

    },



    avisos: function(title, text, icon, data){
       var html = '';
       var iconL = icon ? icon : 'fa-bullhorn';
        var dataL = data ? 'data-notification="'+data+'"' : '';
        html +='<div id="cardAvisos" class="card mb-3 shadow animated bounceInDown" '+dataL+'>';
        html +='    <i class="fas fa-times removeCard" ></i>';
        html +='    <div class="row no-gutters">';
        html +='        <div class="col-4 box-icon" ><i class="fas '+iconL+' icon-item" ></i></div>';
        html +='        <div class="col-8">';
        html +='            <div class="card-body">';
        html +='                <h5 class="card-title mb-0">'+title+'</h5>';
        html +='                <hr class="mt-2 mb-3">';
        html +='                <p class="card-text">'+text+'</p>';
        html +='            </div>';
        html +='        </div>';
        html +='    </div>';
        html +='</div>';

        $('.conteudoBody').prepend(html);

    },


    isOnline: function(){
        var rede =  navigator.connection.type;
        return ((rede==='none' || rede===null ||  rede==='unknown') ? false : true);
    },

    appKey: function(){
        return ( window.localStorage.getItem('serial') ? window.localStorage.getItem('serial') : null );
    },

    logout: function () {
        window.localStorage.clear();
        window.location="index.html";
    },


    exitApp: function(){
        navigator.app.exitApp();
    }





};

app.initialize();
