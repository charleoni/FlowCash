<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        // Your own constructor code
        $this->load->model('Flujo_model');
        $this->load->helper('url');
        $this->load->database('default');
	}
	
	public function index()
	{
		$data['titulo'] = 'Tio esto lo edite desde mi PC y lo subi a Git';
		$data['flujos'] = $this->Flujo_model->flujoIngreso();
		//var_dump($data);
		$this->load->view('welcome_message', $data);		
	}

	public function evitda()
	{
		$this->load->view('evitda');
	}
}
