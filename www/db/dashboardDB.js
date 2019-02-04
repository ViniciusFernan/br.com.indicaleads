var DASHBOARD_DB = {
    initPage: function () {
        var usuario = JSON.parse( window.localStorage.getItem('usuario'));
        if( usuario==null ){
            window.localStorage.clear();
            window.location="index.html";
        }

    }
};
