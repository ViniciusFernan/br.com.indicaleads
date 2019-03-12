<?php

/**
 * Verifica qual classe controlador (Controller) o usuário deseja chamar
 * Verifica qual método dessa classe (Action) deseja executar
 * Separa os parametros
 *
 * Caso o controlador (controller) não seja especificado, o IndexControllers será o padrão
 * Caso o método (Action) não seja especificado, o indexAction será o padrão
 *
 * Camada - Controller
 *
 * @package Sistema de Lead
 * @author Inaweb
 * @version 1.0
 * */
class Application {

    /**
     * Usada para guardar o nome da classe
     * de controle (Controller) a ser executada
     * @var string
     */
    protected $controller = "Index";

    /**
     * Usada para guardar o nome do metodo da
     * classe de controle (Action) que deverá ser executado
     * @var string
     */
    protected $action = "index";

    /**
     * Usada para guardar os parametros passados pela url
     * @var array
     */
    protected $parametros = array();

    /**
     * Parametros passados por Post
     * @var array
     */
    protected $parametrosPost = array();

    /**
     * Verifica se os parâmetros de controlador (Controller) e ação (Action) foram
     * passados via parâmetros "Post" ou "Get" e os carrega tais dados
     * nos respectivos atributos da classe
     * Parametros extras podem ser passados apos a posição 1 da url, ou via post livre
     * Posts serão armazenados em $parametros['_post']
     */
    private function loadRoute() {

        $path = filter_input(INPUT_GET, 'path', FILTER_DEFAULT);
        if (isset($path) && !empty($path)){

            // Limpa os dados
            $path = rtrim($path, '/');
            $path = filter_var($path, FILTER_SANITIZE_URL);

            // Cria um array de parâmetros
            $path = explode('/', $path);

            //Seta o Controller
            if (!empty($path[0]))
                $this->controller = ucfirst(strtolower(str_replace(['-', '_'], '', $path[0])));


            //Seta a Action
            if (!empty($path[1]))
                $this->action = ucfirst(strtolower(str_replace(['-', '_'], '', $path[1])));


            //Seta os Parametros
            if (!empty($path[2])) {
                unset($path[0], $path[1]);
                $this->parametros = Filter::SqlInjection(array_values($path));
            }

            // Apaga os parametros da url para acesso direto
            // Os parametros estão disponíveis nos atributos
            unset($_GET);

            //Apaga a variavel $path
            $path = array();
            unset($path);
            if (!file_exists(ABSPATH . "/controllers/{$this->controller}.controller.php")) {
                $this->controller;
                $this->controller = 'Page404';
                $this->action = 'index';
            }
        }

        // SETA OS PARAMETROS PASSADOS POR POST DENTRO DO $parametroPost
        if ($_POST && !empty($_POST)) {
            $this->parametrosPost = Filter::SqlInjection(filter_input_array(INPUT_POST, FILTER_DEFAULT));
            unset($_POST);
        }
    }

    /**
     * Instancia classe referente ao Controlador (Controller) e executa
     * método referente e  acao (Action)
     * @throws Exception
     */
    public function dispatch(){
        $this->loadRoute();

        //verificando se o arquivo de controle existe
        $ControllerFile = 'controllers/' . $this->controller . '.controller.php';
        if (file_exists($ControllerFile))
            require_once $ControllerFile;
        else
            trigger_error('Arquivo ' . $ControllerFile . ' nao encontrado', E_USER_ERROR);

        //verificando se a classe existe, se existir, estancia a classe
        $controller = $this->controller . 'Controller';
        if (class_exists($controller)):
            $class = new $controller();
            $class->setParametros($this->parametros);
            $class->setParametrosPost($this->parametrosPost);
        else:
            trigger_error("Classe '$controller' nao existe no arquivo '$ControllerFile'", E_USER_ERROR);
        endif;

        //verificando se o metodo existe
        $action = $this->action . 'Action';
        if (method_exists($class, $action))
            $class->$action();
        else
            trigger_error("Metodo '$action' nao existe na classe $controller'", E_USER_ERROR);
    }

}
