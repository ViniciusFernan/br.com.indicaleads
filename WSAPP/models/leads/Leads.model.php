<?php

/**
 * Usuario.model [ MODEL USUARIO ]
 * Responsável por gerenciar os usuários no Admin do sistema!
 */
class LeadsModel {

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
    public function getLeadDoUsuarioFromIdLead($idLead, $idUsuario){

        $sql = 'SELECT lead.idLead, lead.nome, lead.empresa, lead.cidade, lead.email, lead.dddTel, lead.tel, lead.dddCel, lead.cel, lead.detalhes, lead.qtdVidas,  lead.idTipo, lead.idOperadora, lead.duplicado, lead.dataCadastro, lead.dataVisualizado, lead.idLeadStatus,  
operadora.nomeOperadora AS operadora, tipo.tipo
                FROM  lead 
                INNER JOIN operadora  ON  operadora.idOperadora = lead.idOperadora
                INNER JOIN tipo  ON  tipo.idTipo = lead.idTipo 
                WHERE lead.idLead=:idLead AND lead.idUsuario=:idUsuario';

        $Select = new Select;
        $Select->FullSelect($sql, "idLead={$idLead}&idUsuario={$idUsuario}");
        if (!$Select->getResult()):
            $this->Error = ["Usuário não encontrado", ERR_ERROR, true];
            $this->Result = false;
        else:
            $resposta = $Select->getResult()[0];
            if (!empty($resposta['duplicado'])) {
                $resposta['duplicado'] = unserialize($resposta['duplicado']);
                foreach ($resposta['duplicado'] as $key => $val) {
                    $resposta['duplicado'][$key]['dataCadastro'] = date('d/m/Y h:i:s', strtotime($val['dataCadastro']));
                    unset($resposta['duplicado'][$key]['track']);
                    unset($resposta['duplicado'][$key]['origem']);
                    unset($resposta['duplicado'][$key]['urlCadastro']);
                    unset($resposta['duplicado'][$key]['urlPouso']);
                    unset($resposta['duplicado'][$key]['dataCadastroReal']);
                }
            }

            // SET HISTORICO COM HORARIO DE VISUALIZAÇÃO DO LEAD
            require_once ABSPATH . "/models/leads/RegistroLeadHistorico.model.php";
            $HS = new RegistroLeadHistoricoModel();
            $HS->registraLeadVisualizado($idLead,$idUsuario, ' [APLICATIVO]', date('Y-m-d H:i:s'));

            $this->Result = $resposta;
        endif;
    }


    /**
     * Seleciona o Usuário
     * @param Int $idUsuario
     */
    public function getListaDeLeadDoUsuario($idUsuario, $leadLimit) {

        $leadLimitSQL = ( !empty($leadLimit) ? ' AND lead.idLead<:leadLimite': '' );
        $leadLimitPARSE = ( !empty($leadLimit) ? '&leadLimite='.$leadLimit : '' );
        $sql = "SELECT lead.idLead, lead.nome, lead.dddTel, lead.idTipo, lead.dataCadastro, lead.dataCadastro, lead.idLeadStatus, lead.dataVisualizado,  lead.comentario  
                FROM  lead 
                WHERE  lead.idUsuario=:idUsuario  {$leadLimitSQL}
                ORDER BY lead.dataCadastro DESC 
                LIMIT 30";

        $Select = new Select;
        $Select->FullSelect($sql, "idUsuario={$idUsuario}{$leadLimitPARSE}");
        if (!empty($Select->getResult())):
            $this->Result = $Select->getResult();
        else:
            $this->Result = false;
        endif;
    }


    /**
     * Seta o Lead como vizualizado
     * @param Int $idLead
     */
    public function setLeadVisualizado($idLead) {

        //Verifica se o lead ja está visualizado
        $sel = new Select;
        $sel->FullSelect("SELECT dataVisualizado FROM lead WHERE idLead=:idL", "idL=$idLead");
        if (empty($sel->getResult()[0]['dataVisualizado'])):
            $data['dataVisualizado'] = date('Y-m-d H:i:s');
            $setviewLead = new Update;
            $setviewLead->ExeUpdate('lead', $data, "WHERE idLead=$idLead", "dataVisualizado={$data['dataVisualizado']}");

            if ($setviewLead->getResult()):
                return true;

                // SET HISTORICO COM HORARIO DE VISUALIZAÇÃO DO LEAD

            else:
                return false;
            endif;
        else:
            return true;
        endif;
    }



    /*     * *********
     * Vinicius
     * UPDATE GENERIC - ALTERA STATUS DA INDICAÇAO E OU FEEDBACK SOBRE A INDICAÇAO
     * @param idLead ;
     * @param array  column -> value ;
     */

    public function setStatusLead($post) {
        $this->Result=[];

        if(!empty($post['idLead']) && !empty($post['idUsuario']) && !empty($post['idleadstatus']) ):
            $updateLead = new Update();
            $updateLead->ExeUpdate('lead', ['idLeadStatus'=>$post['idleadstatus']], "WHERE idLead=:idL", "idL={$post['idLead']}");
            if ($updateLead->getRowCount()):
                $this->Result = $updateLead->getResult();

                $sel = new Select;
                $sel->FullSelect("SELECT status FROM lead_status WHERE idLeadStatus=:idLS", "idLS={$post['idleadstatus']}");
                $descricaoPersonalizada = (!empty($sel->getResult()[0]['status']) ? $sel->getResult()[0]['status'] : '' )."  [APLICATIVO]";


                // SET HISTORICO COM HORARIO E STATUS DO LEAD
                require_once ABSPATH . "/models/leads/RegistroLeadHistorico.model.php";
                $HS = new RegistroLeadHistoricoModel();
                $HS->registraAlteracoesDeStatusLead($post['idLead'], $post['idUsuario'], $post['idleadstatus'], $descricaoPersonalizada, date('Y-m-d H:i:s'));


            endif;
        endif;
    }

}
