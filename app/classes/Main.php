<?php


class Main {


    public $config;


    public function __construct() {

        /**
         * @var Main
         */
        global $instance;
        $instance = $this;

        $this->config = array( );

        $this->load_yml_config('config.yml');

        // define constants
        define( 'BASE_URL', $this->config['base_url'] ? trim($this->config['base_url']) : null );
        define( 'BOWER_URL', BASE_URL ? BASE_URL.'/bower_components' : null );

    }


    public function index() {
        header('location: /join');
        return;
    }


    public function call404() {
        header('HTTP/1.0 404 Not Found');
    }


    public function join() {
        $this->load_page('join.phtml');
    }


    public function join_rest() {
        $interactor = new \Frlnc\Slack\Http\CurlInteractor();
        $interactor->setResponseFactory(new \Frlnc\Slack\Http\SlackResponseFactory());

        $commander = new \Frlnc\Slack\Core\Commander($this->config['slack_token'], $interactor);

        $input_email = isset($_POST['email']) ? trim($_POST['email']) : '';

        $response = $commander->execute('users.admin.invite', array(
            'email' => $input_email
        ));

        $rbody = $response->getBody();

        header('Content-type: application/json');
        if ($rbody['ok']) {
            echo json_encode(array(
                'success' => true,
                'message' => 'Success!',
                'data' => array(
                    'email' => $input_email
                )
            ));
        }
        else {
            echo json_encode(array(
                'success' => false,
                'message' => trim(ucfirst(strtolower(str_replace('_', ' ', $rbody['error'])))),
                'data' => array(
                    'email' => $input_email
                )
            ));
        }
    }


    protected function load_partial($name, $return = true) {
        ob_start();
        require_once ROOT.'/app/partials/'.$name;
        $contents = ob_get_clean();
        if ($return) {
            return $contents;
        }
        echo $contents;
    }


    protected function load_page($name) {
        require_once ROOT.'/app/pages/'.$name;
    }

    protected function load_yml_config($name) {
        $parsed = Spyc::YAMLLoadString(file_get_contents(ROOT.'/app/config/'.$name));
        foreach ($parsed as $key => $value) {
            $this->config[$key] = $value;
        }
        return $this->config;
    }


}