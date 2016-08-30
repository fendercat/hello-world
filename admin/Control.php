<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Control extends CI_Controller {

	public function __construct(){
		parent::__construct();	
		// verificar inicio de sesion
		if (!$this->Model_sesiones->confirmar_sesion()){ redirect('admin/login', 'refresh'); }//if	
	}

	public function index()
	{
		$this->load->view('admin/header');
		$this->load->view('admin/menu');
		$this->load->view('admin/inicio');
		$this->load->view('admin/footer');
	}//fn
		
		
	public function validar(){
		$this->load->model('Model_sesiones');
		$result = $this->Model_sesiones->validar_usuario($_POST["usuario"], $_POST["password"]);
		if ($result == 1){
			redirect('inicio', 'refresh');
		} else {
			redirect('inicio', 'refresh');
		}//if
	}//fn
	
	public function cerrar_sesion()
	{
		$this->load->model('Model_sesiones');
		$result = $this->Model_sesiones->cerrar_sesion();
		if ($result == 1){
			redirect('inicio', 'refresh');
		} else {
			echo 'No se pudo cerrar la sesion'; exit();
		}
	}//fn
	
}//class
