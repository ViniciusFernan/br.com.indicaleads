<?php

/**
 * MainController - Todos os controllers deverão estender essa classe
 *
 * Camada - Controladores ou Controllers
 *
 * @package Sistema de Lead
 * @author Inaweb
 * @version 1.0
 */
abstract class MainController {

    /**
     * Parametros passados por GET
     * @access protected
     */
    protected $parametros = array();

    /**
     * Parametros passados POST
     * @access protected
     */
    protected $parametrosPost = array();



    public function setParametros($parametros) {
        $this->parametros = $parametros;
    }

    public function setParametrosPost($parametrosPost) {
        $this->parametrosPost = $parametrosPost;
    }

}

// class MainController