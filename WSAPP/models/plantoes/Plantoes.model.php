<?php

/**
 * Usuario.model [ MODEL USUARIO ]
 * Responsável por gerenciar os usuários no Admin do sistema!
 */
class PlantoesModel {

    private $Data;
    private $User; //idUsuario
    private $Error;
    private $Result;

    //Paginacao
    private $paginacao;


    //Nome da tabela no banco de dados
    const Entity = 'leads';


    /**
     * <b>Verificar Cadastro:</b> Retorna TRUE se o cadastro ou update for efetuado ou FALSE se não.
     * Para verificar erros execute um getError();
     * @return BOOL $Var = True or False
     */
    public function getResult() {
        return $this->Result;
    }

    /**
     * <b>Obter Erro:</b> Retorna um array associativo com um erro e um tipo.
     * @return ARRAY $Error = Array associatico com o erro
     */
    public function getError() {
        return $this->Error;
    }







    /**
     * Seleciona o Usuário
     * @param Int $idUsuario
     */
    public function getListaDePlantoesDoUsuario($post) {

        $data = ' >= CURRENT_DATE()';
        if(!empty($post['dataReferencia'])){
            if(!empty($post['tipoDeBusca']) &&  $post['tipoDeBusca']=="maisPlantoes" ){
                $data = " > '".$post['dataReferencia']."'";
            }elseif(!empty($post['tipoDeBusca']) &&  $post['tipoDeBusca']=="plantoesPassados" ){
                $data = " < '".$post['dataReferencia']."'";
            }
        }

       $sql = "SELECT plantao.*, usuario.inicioAtendimento, usuario.fimAtendimento, DATE_FORMAT(plantao.dataPlantao,'%d/%m/%Y') AS dataFormatada, DATE_FORMAT(plantao.dataPlantao,'%d') AS diaFormat, DATE_FORMAT(plantao.dataPlantao,'%m') AS mesFormat
               FROM plantao
               INNER JOIN usuario ON usuario.idUsuario = plantao.idUsuario  
               WHERE plantao.idUsuario=:idUsuario AND statusPlantao = 1 
               AND DATE(plantao.dataPlantao) ".$data."
               ORDER BY plantao.dataPlantao ASC
               LIMIT 8";

        $Select = new Select;
        $Select->FullSelect($sql, "idUsuario={$post['idUsuario']}");
        if (!empty($Select->getResult())):
            $this->Result = $Select->getResult();
        else:
            $this->Result = false;
        endif;
    }




}
