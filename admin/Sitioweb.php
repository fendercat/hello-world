<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sitioweb extends CI_Controller {
	
	public $controller = 'sitioweb';
	public $mainTable = 'sitioweb';
	public $W = 200;
	public $H = 200;

	public function __construct()
	{
		parent::__construct();	
		// verificar inicio de sesion
		if (!$this->Model_sesiones->confirmar_sesion()){ redirect('admin/login', 'refresh'); }//if
		// Load model
		$this->load->model('admin/Model_sitioweb');
		$this->load->model('admin/Model_global');
		$this->load->model('admin/Model_filesizes');
	}
		
	public function index()
	{		
		$this->load->view('admin/header'); 
		$this->load->view('admin/menu');
		$this->load->view('admin/sitioweb_formulario');
		$this->load->view('admin/footer');
	}//fn
	
	
	// Agregar y Actualizar
	public function addUpdate()
	{
		$data = array();

		// Load Library
		$this->load->library('upload_pictures');

		// Consultar el folder donde se van a poner las fotos
		$basePathFiles = 'upload/sitioweb/';

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
		
		$result = $this->Model_sitioweb->addMod($data);
		redirect('admin/'.$this->controller.'/index/msg/'.$result, 'refresh');
	}//fn
	
	
}// class
