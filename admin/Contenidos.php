<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contenidos extends CI_Controller {
	
	public $controller = 'contenidos';
	public $mainTable = 'contenidos';
	public $W = 1200;
	public $H = 500;
	public $bW = 300;	// El Width y Height de esta parte son para los accesos directos de la página de inicio (imagen del acceso)
	public $bH = 300;

	public function __construct()
	{
		parent::__construct();	
		// verificar inicio de sesion
		if (!$this->Model_sesiones->confirmar_sesion()){ redirect('admin/login', 'refresh'); }//if	
		
		// Load model
		$this->load->model('admin/Model_contenidos');
		$this->load->model('admin/Model_global');
		$this->load->model('admin/Model_filesizes');
	}//
		
	public function agregar()
	{
		$vars["id"] = ( !is_numeric($this->uri->segment(4)) ) ? 0 : $this->uri->segment(4);

		$this->load->view('admin/header'); 
		$this->load->view('admin/menu');
		$this->load->view('admin/contenidos_formulario', $vars);
		$this->load->view('admin/footer'); //Agregamos la vista del pie de página
	}//agregar
	
	
	// Agregar y Actualizar
	public function addUpdate()
	{
		$data = array();

		// Load Library
		$this->load->library('upload_pictures');

		// Consultar el folder donde se van a poner las fotos
		$basePathFiles = 'upload/contenidos/';

		// Subir imágenes y asignación de la ruta de la imagen subida
		if (isset($_FILES["imagen"]["error"]) && $_FILES["imagen"]["error"] == 0) 
		{
			$funcion = "Single";

			// Validar si son los contenidos de los accesos directos de la página de inicio
			switch($this->input->post("id")) {
				case 7: 
					$this->W = $this->bW;
					$this->H = $this->bH;
					$funcion = "Fit";
					break;
				case 8: 
					$this->W = $this->bW;
					$this->H = $this->bH;
					$funcion = "Fit";
					break;
				case 9: 
					$this->W = $this->bW;
					$this->H = $this->bH;
					$funcion = "Fit";
					break;
				case 10: 
					$this->W = $this->bW;
					$this->H = $this->bH;
					$funcion = "Fit";
					break;
			}//switch

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
				
				$funcion = "subirImagen".$funcion;
				$data["imagen"] = $this->upload_pictures->$funcion($IMG);
			}//if
		}//if

		//print_r($data); exit();
		
		$result = $this->Model_contenidos->addMod($data);
		redirect('admin/'.$this->controller.'/agregar/'.$_POST["id"].'/msg/'.$result, 'refresh');

	}//addUpdate

	
	public function eliminar_imagen()
	{
		$result = $this->Model_global->eliminar_archivo($this->mainTable, 'imagen',  $this->uri->segment(4));
		redirect('admin/contenidos/agregar/'.$this->uri->segment(4).'/msg/'.$result, 'refresh');
	}//eliminar

	public function eliminar_imagen_superior()
	{
		$result = $this->Model_global->eliminar_archivo($this->mainTable, 'imagen_superior',  $this->uri->segment(4));
		redirect('admin/contenidos/agregar/'.$this->uri->segment(4).'/msg/'.$result, 'refresh');
	}//eliminar
	

			/**

			Función que da de alta las imágenes dentro del área de contenidos del CKEDITOR 

			*/
			public function addIma(){
				$message = "";
				if ($_FILES['upload']['size'] != 0){
					// Picture config file
						$config["name_file"] = $_FILES["upload"]["name"];
						$config["tmp_name"] = $_FILES["upload"]["tmp_name"];
						$config["img_size"] = $_FILES['upload']['size'];
						$config["types"] = array('jpg', 'gif', 'png');
						$config["max_file_size"] = '2000000';
						$config["new_width"] = 800;
						$config["new_height"] = 1200;
						$config["path"] = 'upload/textos/';
					$this->load->library('upload_pictures', $config);
					$fileName = $this->upload_pictures->single(1);
					
					$funcNum = $_GET['CKEditorFuncNum'] ;
					$url = site_url().'upload/textos/'.$fileName;
					echo $_FILES['upload']["name"];
					echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', '$message');</script>";
				} else {
				}//if
			}//addIma
	
}// class
