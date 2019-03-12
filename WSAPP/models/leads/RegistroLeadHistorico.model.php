<?php

/**
 * RegistroLeadHistorico.model [ MODEL REGISTROLEADHISTORICO ]
 * Responsavel por gerenciar o historico do lead
 */
class RegistroLeadHistoricoModel {

    private $result;

    function __construct() {
        
    }

    function getResult() {
        return $this->result;
    }



    /**
     * Registra o momento em que o usuario Visualizou o lead
     * @param INTEGER $idLead
     * @param INTEGER $idUsuario - Id do usuario que recebeu a notificacao
     * @param STRING $descricaoPersonalizada - Descrição da notificacao do usuario
     * @param STRING $dateTime - Data do registro (Null para deixar automatico)
     * @return BOOLEAN - True para cadastrado com sucesso!
     */
    public function registraLeadVisualizado($idLead, $idUsuario, $descricaoPersonalizada = NULL, $dateTime = NULL) {

        //Validacoes
        if (empty($idUsuario)):
            $this->result = ['resp' => 'error', 'msg' => 'idUsuario não preenchido'];
            return false;
        endif;

        //Montando dados
        $dados = [
            'idLead' => $idLead,
            'idUsuario' => $idUsuario,
            'idHistoricoCategoria' => 9,
            'descricao' => 'Lead Visualizado'.(!empty($descricaoPersonalizada) ? $descricaoPersonalizada : ''),
            'dateTime' => (!empty($dateTime) ? $dateTime : date('Y-m-d H:i:s'))
        ];

        //Efetuando o registro
        return $this->cadastraHistoricoDoLead($dados);
    }


    /**
     * Registra o momento em que o usuario Insere ou altera o estatus do lead
     * @param INTEGER $idLead
     * @param INTEGER $idUsuario - Id do usuario que recebeu a notificacao
     * @param STRING $descricaoPersonalizada - Descrição da notificacao do usuario
     * @param STRING $dateTime - Data do registro (Null para deixar automatico)
     * @return BOOLEAN - True para cadastrado com sucesso!
     */
    public function registraAlteracoesDeStatusLead($idLead, $idUsuario, $status, $descricaoPersonalizada = NULL, $dateTime = NULL) {

        //Validacoes
        if (empty($idUsuario)):
            $this->result = ['resp' => 'error', 'msg' => 'idUsuario não preenchido'];
            return false;
        endif;

        //Montando dados
        $dados = [
            'idLead' => $idLead,
            'idUsuario' => $idUsuario,
            'idHistoricoCategoria' => 10,
            'idReferencia' => $status,
            'descricao' => 'Inserido Status ' .(!empty($descricaoPersonalizada) ? $descricaoPersonalizada : ''),
            'dateTime' => (!empty($dateTime) ? $dateTime : date('Y-m-d H:i:s'))
        ];

        //Efetuando o registro
        return $this->cadastraHistoricoDoLead($dados);
    }


    /**
     * Registra o momento em que o usuario Insere ou altera o estatus do lead
     * @param INTEGER $idLead
     * @param INTEGER $idUsuario - Id do usuario que recebeu a notificacao
     * @param STRING $descricaoPersonalizada - Descrição da notificacao do usuario
     * @param STRING $dateTime - Data do registro (Null para deixar automatico)
     * @return BOOLEAN - True para cadastrado com sucesso!
     */
    public function registraComentariosDoLead($idLead, $idUsuario, $descricaoPersonalizada = NULL, $dateTime = NULL) {

        //Validacoes
        if (empty($idUsuario)):
            $this->result = ['resp' => 'error', 'msg' => 'idUsuario não preenchido'];
            return false;
        endif;

        //Montando dados
        $dados = [
            'idLead' => $idLead,
            'idUsuario' => $idUsuario,
            'idHistoricoCategoria' => 11,
            'descricao' => 'Comentado '.(!empty($descricaoPersonalizada) ? $descricaoPersonalizada : ''),
            'dateTime' => (!empty($dateTime) ? $dateTime : date('Y-m-d H:i:s'))
        ];

        //Efetuando o registro
        return $this->cadastraHistoricoDoLead($dados);
    }
    



    /**
     * Cadastra o historico do lead
     * @param ARRAY $arrayRegistro - Dados por coluna do lead_historico
     * @return BOOLEAN - True para cadastrado com sucesso!
     */
    private function cadastraHistoricoDoLead($arrayRegistro) {

        if (empty($arrayRegistro['idLead'])):
            $this->result = ['resp' => 'error', 'msg' => 'idLead Obrigatorio'];
            return false;
        endif;
        if (empty($arrayRegistro['idHistoricoCategoria'])):
            $this->result = ['resp' => 'error', 'msg' => 'idHistoricoCategoria Obrigatorio'];
            return false;
        endif;
        if (empty($arrayRegistro['dateTime'])):
            $arrayRegistro['dateTime'] = date('Y-m-d H:i:s');
        endif;

        $cr = new Create;
        $cr->ExeCreate('lead_historico', $arrayRegistro);
        if ($cr->getResult()):
            $this->result = ['resp' => 'success', 'msg' => 'Historico registrado com sucesso!', 'idLeadHistorico' => $cr->getResult()];
            return true;
        else:
            $this->result = ['resp' => 'error', 'msg' => 'Erro ao cadastrar o historico!'];
            return false;
        endif;
    }

}
