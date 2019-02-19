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
    receivedEvent: function(id) {
        var parentElement = document.getElementById(id);
        parentElement.setAttribute('style', 'display:block;');

        var usuario = JSON.parse( window.localStorage.getItem('usuario'));
        var infoUsuario = document.getElementById("infoUsuario");

        if(usuario !=null && infoUsuario ){
            var html="";
            html+="<p class='infoNome'>" + usuario.nome + "</p>";
            html+="<p class='infoEmail'>" + usuario.email + "</p>";

            document.getElementById('infoUsuario').innerHTML = '<p class="infoNome">' + html +'</p>';
        }

        var devicePlatform = device.platform;
        var serial = ((devicePlatform === 'browser') ? '123456-AVF' : device.serial);
        localStorage.setItem('serial', serial);

        var imgPerfil = (window.localStorage.getItem('imgPerfil') ?  urlUploads +"/"+ usuario.idUsuario +"/perfil/"+ window.localStorage.getItem('imgPerfil') : './img/avatar.png');
        ((document.getElementById('imgemPerfilMeusDados')) ? document.getElementById('imgemPerfilMeusDados').src = imgPerfil : '' );
        ((document.getElementById('imgemPerfilMeusDadosMenu')) ? document.getElementById('imgemPerfilMeusDadosMenu').src = imgPerfil : '' );

        setTimeout(function(){
            document.getElementById('deviceready').classList.remove('animated');
        }, 800);

        document.addEventListener("backbutton", this.exitApp, false);


    },



    avisos: function(title, text, icon){
       var html = '';
       var iconL = icon ? icon : 'fa-bullhorn';
        html +='<div id="cardAvisos" class="card mb-3 shadow animated bounceInDown">';
        html +='    <i class="fas fa-times removeCard" ></i>';
        html +='    <div class="row no-gutters">';
        html +='        <div class="col-4 box-icon" ><i class="fas '+iconL+' icon-item" ></i></div>';
        html +='        <div class="col-8">';
        html +='            <div class="card-body">';
        html +='                <h5 class="card-title">'+title+'</h5>';
        html +='                <hr>';
        html +='                <p class="card-text">'+text+'</p>';
        html +='            </div>';
        html +='        </div>';
        html +='    </div>';
        html +='</div>';

        $('.conteudoBody').prepend(html);

    },


    isOnline: function(){
        var rede =  navigator.connection.type;
        alert(rede);
        return rede === "none" || rede === null ||  rede === "unknown" ? false : true;
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
