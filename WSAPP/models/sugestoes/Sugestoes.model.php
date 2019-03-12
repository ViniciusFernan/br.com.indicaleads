<?php

/**
 * FeedbackDoSistema.model [ MODEL LEADS ]
 * Respons�vel por gerenciar os Feedbacks
 */
class SugestoesModel {

    private $idCorretora;
    private $Data;
    private $Error;
    private $Result;
    private $PostFiltros;
    private $resumo;
    private $rowCount;

    /**  Dados extras do filtro na str sql e parse str **/
    private $where;
    private $parse;
    private $unicos = false;
    private $atribuidos = false;

    /**
     * Recebe a pagina atual e devolve a paginacao em html
     */
    private $pagination;
    private $colunasCsv;
    private $limitePorPagina;

    //Nome da tabela no banco de dados
    const Entity = 'feedback_do_sistema';

    public function __construct() {

    }




    /**
     * Restata o resultado da acao atual
     * @return type
     */
    function getResult() {
        return $this->Result;
    }

    /**
     * Contagem total dos resultados
     * @return Int
     */
    function getRowCount() {
        return $this->rowCount;
    }

    /**
     * Seta os posts enviados para filtragem dos leads
     * @param Array $PostFiltros
     */
    function setPostFiltros($PostFiltros) {
        $this->PostFiltros = $PostFiltros;
    }

    /**
     * Resgata o array passado pelpost
     * @return type
     */
    function getPostFiltros() {
        return $this->PostFiltros;
    }

    /**
     * resgata a paginacao em html
     * @return type
     */
    function getPagination() {
        return $this->pagination;
    }





    /***********
     * Vinicius
     * CADASTRA FEEDBACK DO SISTEMA
     * @param idLead ;
     * @param array  column -> value ;
     */
    public function createFeedbackDoSistema($post){
        $createFeedback = new Create;

        $paramsCreate['idUsuario'] = $post['idUsuario'];
        $paramsCreate['idCorretora'] = $post['idCorretora'];
        $paramsCreate['dataFeedback'] = date('Y-m-d H:m:s');
        $paramsCreate['mensagem'] = $post['mensagem'];
        $paramsCreate['imgPrintScreen'] = (!empty($post['screenshot']) ? $post['screenshot'] : NULL);
        $paramsCreate['dispositivo'] = 'APLICATIVO';

        $createFeedback->ExeCreate('feedback_do_sistema',  $paramsCreate);

        if($createFeedback->getResult()):
            $this->Result = true;
        else:
            $this->Result= false;
        endif;
    }

}
