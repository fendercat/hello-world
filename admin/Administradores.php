<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Administradores extends CI_Controller {
	
	public $controller = 'administradores';
	public $mainTable = 'administracion';

	public function __construct()
	{
		parent::__construct();
		// verificar inicio de sesion
		if (!$this->Model_sesiones->confirmar_sesion()){ redirect('admin/login', 'refresh'); }//if
		// Load model
		$this->load->model('admin/Model_global');
		$this->load->model('admin/Model_users');
		$this->load->model('admin/Model_administradores');
	}//
	
	

	public function index()
	{
		$vars["id"] = ( !is_numeric($this->uri->segment(4)) ) ? 0 : $this->uri->segment(4);
		$vars["query"] = $this->db->query("SELECT * FROM administracion WHERE idAdmin != 1 ORDER BY nomAdmin ASC");
		
		$this->load->view('admin/header'); 
		$this->load->view('admin/menu'); 
		$this->load->view('admin/administradores_control', $vars);
		$this->load->view('admin/footer');
	}//
	
	
	
	// Agregar y Actualizar
	public function addUpdate(){
		$result = $this->Model_administradores->addMod();
		if ($_POST["idAdmin"] == 0){
			redirect('admin/'.$this->controller.'/index/0/msg/'.$result, 'refresh');
		} else {
			redirect('admin/'.$this->controller.'/index/'.$_POST["idAdmin"].'/msg/'.$result, 'refresh');
		}//if
	}//addUpdate
	
	
	
	# @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ ELIMINAR @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
	public function eliminar()
	{
		$id = $this->uri->segment(4);
		$this->db->delete('administracion', array('idAdmin' => $id)); 
		redirect('admin/'.$this->controller.'/index/0/msg/3', 'refresh');
	}//eliminar
	
	
}//class
?>