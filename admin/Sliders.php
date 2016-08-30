<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sliders extends CI_Controller {

	public $controller = 'sliders';
	public $mainTable = 'sliders';
	public $W = 1024;
	public $H = 680;
	
	public function __construct()
	{
		parent::__construct();
		// verificar inicio de sesion
		if (!$this->Model_sesiones->confirmar_sesion()){ 
			redirect('admin/login', 'refresh'); 
		}//if	
		
		// Load model
		$this->load->model('admin/Model_global');
		$this->load->model('admin/Model_sliders');
		$this->load->model('admin/Model_filesizes');
	}//
	
	
	public function index()
	{
		$vars["id"] = ( !is_numeric($this->uri->segment(4)) ) ? 0 : $this->uri->segment(4);
		$vars["query"] = $this->db->query("SELECT * FROM {$this->mainTable} ORDER BY pos DESC");
		
		$this->load->view('admin/header');
		$this->load->view('admin/menu');
		$this->load->view('admin/sliders_control', $vars);
		$this->load->view('admin/footer');
	}//agregar
	
	
	// Agregar y Actualizar
	public function addUpdate()
	{
		$data = array();

		// Load Library
		$this->load->library('upload_pictures');

		// Consultar el folder donde se van a poner las fotos
		$basePathFiles = 'upload/sliders/';

		// Subir imágenes y asignación de la ruta de la imagen subida
		if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] == 0) 
		{
			$IMG = array();
			$IMG["imagen"] = "imagen";
			$IMG["img"] = "img";
			$IMG["basePathFiles"] = $basePathFiles;
			$IMG["limiteArchivoBytes"] = $this->Model_filesizes->limitFileSizeValueB();
			$IMG["newName"] = false;
			$IMG["W"] = $this->W;
			$IMG["H"] = $this->H;
			
			$data["imagen"] = $this->upload_pictures->subirImagenFit($IMG);
		}//if
		
		$result = $this->Model_sliders->addMod($data);
		redirect('admin/'.$this->controller.'/index/'.$this->input->post("id").'/msg/'.$result, 'refresh');
		
	}//fn
	

	/**
	 * POSICIONAMIENTO
	 */
	public function pos_up()
	{
		$this->load->library('posicion_registros');
		$current = $this->uri->segment(4);
		$result = $this->posicion_registros->pos_up($this->mainTable, $current);
		redirect('admin/'.$this->controller.'/index/', 'refresh');
	}//
	
	public function pos_down()
	{
		$this->load->library('posicion_registros');
		$current = $this->uri->segment(4);
		$result = $this->posicion_registros->pos_down($this->mainTable, $current);
		redirect('admin/'.$this->controller.'/index/', 'refresh');
	}//
	
	/**
	 * ELIMINAR
	 */
	public function eliminar()
	{
		$id = $this->uri->segment(4);

		$result = $this->Model_global->eliminar_archivo($this->mainTable, 'imagen', $id);
		$this->db->delete($this->mainTable, array('id' => $id)); 
		redirect('admin/'.$this->controller.'/index/0/msg/3', 'refresh');
	}//

}//class
