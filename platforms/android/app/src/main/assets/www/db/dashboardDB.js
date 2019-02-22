var DASHBOARD_DB = {
    initialize: function () {
        this.bindEvents();
    },
    bindEvents: function () {
        document.addEventListener('deviceready', this.onDeviceReady, false);
    },

    onDeviceReady: function () {
        DASHBOARD_DB.initPage();
        var parentElement = document.getElementById('deviceready');
        parentElement.setAttribute('style', 'display:block;');
    },

    initPage: function () {
        var usuario = JSON.parse( window.localStorage.getItem('usuario'));
        if( usuario==null ){
            window.localStorage.clear();
            window.location="index.html";
        }

    }
};
