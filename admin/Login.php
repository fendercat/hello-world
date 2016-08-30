<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		if ( !$this->Model_sesiones->confirmar_sesion()){
			$this->load->view('admin/login');
		} else {
			redirect('admin/control/index', 'refresh');
		}//if
	}//fn
	

	public function validar()
	{
		if (
			$this->input->post("usuario") && 
			$this->input->post("usuario") != '' && 
			$this->input->post("password") && $this->input->post("password") != '' )
		{

			$result = $this->Model_sesiones->validar_usuario($this->input->post("usuario"), $this->input->post("password"));
			if ($result == 1)
			{
				redirect('admin/control/index', 'refresh');
			} 
			else 
			{
				$this->uri->segment(2) == 'denegado';
				redirect('admin/login/index/denegado', 'refresh');
				
			}//if
		}else{
			$this->cerrar_sesion();
		}
	}//fn
	
	public function cerrar_sesion()
	{
		$result = $this->Model_sesiones->cerrar_sesion();
		if ($result == 1){
			redirect('admin/login', 'refresh');
		} else {
			show_error('No se pudo cerrar la sesion'); exit();
		}//if
	}//fn
	
}//class

?>