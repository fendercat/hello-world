<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inicio extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // Models
		$this->load->model('web/Model_metatags');
		$this->load->model('web/Model_global');
		$this->load->model('web/Model_sliders');
    }//

    public function index()
	{
		$vars = array();
		$metas = $this->Model_metatags->meta_tags('inicio');

		// Comprobación de si está o no activo el sitio web
		if ($this->Model_global->confirmarSiActivoSitioWeb() || $this->Model_sesiones->confirmar_sesion()) 
		{
			$metas = $this->Model_metatags->meta_tags('inicio');
			$metas["scripts"] = '
			<!-- bxSlider -->
		    <script src="assets/bxslider/jquery.bxslider.min.js"></script>
		    <link href="assets/bxslider/jquery.bxslider.css" rel="stylesheet" />
		    <script src="assets/bxslider/jquery.bxslider.config.js"></script>
		    <script src="assets/bxslider/jquery.easing.1.3.js"></script>
		    <script src="assets/bxslider/jquery.easing.compatibility.js"></script>';
		    $metas["scripts"] = '';

		    $foot["scripts"] = '
		    <script src="assets/wb_target/js/jquery-1.9.1.min.js"></script>
			<script src="assets/wb_target/js/bootstrap.min.js"></script>
			<script src="assets/wb_target/js/jquery.touchSwipe.min.js"></script>
			<script src="assets/wb_target/js/jquery.placeholder.min.js"></script>

			<script src="assets/wb_target/js/jquery-easing-1.3.js"></script>
			<script src="assets/wb_target/js/jquery.animate-enhanced.min.js"></script>
			<script src="assets/wb_target/js/jquery.superslides.min.js"></script>

			<script src="http://maps.google.com/maps/api/js?sensor=true"></script>
			<script src="assets/wb_target/js/jquery.gmap.min.js"></script>

			<script defer="defer" src="assets/wb_target/js/modernizr.js"></script>
			<script defer="defer" src="assets/wb_target/js/custom.min.js"></script>
			';

			$vars["querySlider"] = $this->db->query("SELECT * FROM sliders WHERE activo=1 ORDER BY pos DESC");
			
			$this->load->view('web/head', $metas);
			$this->load->view('web/header');
			$this->load->view('web/inicio', $vars);
			$this->load->view('web/secciones', $vars);
			$this->load->view('web/footer', $foot);
		} else {
			$this->load->view('web/temporal', $metas);
		}//if
			
	}//fn



	public function recomendados()
	{
		$echo = '';

		// Primer producto
		$SQL = "SELECT p.*, c.id as cId, c.titulo as cTitulo, c.friendly_url as cFriendlyUrl, sc.id as scId, sc.titulo as scTitulo, sc.friendly_url as scFriendlyUrl
				FROM productos p LEFT JOIN categorias c 
				ON p.idCat = c.id LEFT JOIN subcategorias sc 
				ON p.idSubCat = sc.id 
				WHERE p.activo=1 AND p.idCat = 1
				ORDER BY RAND()
				LIMIT 1";
		$query = $this->db->query($SQL);
		$echo .= $this->productBoxRecommended($query);

		// Primer producto
		$SQL = "SELECT p.*, c.id as cId, c.titulo as cTitulo, c.friendly_url as cFriendlyUrl, sc.id as scId, sc.titulo as scTitulo, sc.friendly_url as scFriendlyUrl
				FROM productos p LEFT JOIN categorias c 
				ON p.idCat = c.id LEFT JOIN subcategorias sc 
				ON p.idSubCat = sc.id 
				WHERE p.activo=1 AND p.idCat = 2
				ORDER BY RAND()
				LIMIT 1";
		$query = $this->db->query($SQL);
		$echo .= $this->productBoxRecommended($query);

		// Primer producto
		$SQL = "SELECT p.*, c.id as cId, c.titulo as cTitulo, c.friendly_url as cFriendlyUrl, sc.id as scId, sc.titulo as scTitulo, sc.friendly_url as scFriendlyUrl
				FROM productos p LEFT JOIN categorias c 
				ON p.idCat = c.id LEFT JOIN subcategorias sc 
				ON p.idSubCat = sc.id 
				WHERE p.activo=1 AND p.idCat = 3
				ORDER BY RAND()
				LIMIT 1";
		$query = $this->db->query($SQL);
		$echo .= $this->productBoxRecommended($query);

		echo $echo;
	}//fn


		private function productBoxRecommended($query)
		{
			if ($query->num_rows() != 0) 
			{
				$r = $query->row();
				$LINKCategoria = 'productos/'.$r->cFriendlyUrl;
				$LINKLinea = $LINKCategoria.'/'.$r->scFriendlyUrl;
				$LINK = $LINKLinea.'/ver/'.$r->id.'/'.$r->friendly_url;

				return '
				<div class="boxRecommended">
					<div class="pic"><a href="'.$LINK.'"><img src="'.$r->m_imagen1.'" alt=""></a></div>
	                <div class="info">
	                    <div class="titulo"><a href="'.$LINK.'">'.$r->titulo.'</a></div>
	                    <div class="medidas"><strong>Medidas:</strong> '.$r->medidas.'</div>
	                    <div class="clave"><strong>Código:</strong> '.$r->clave.'</div>
	                    <div class="categoria"><a href="'.$LINKCategoria.'">'.$r->cTitulo.'</a></div>
	                    <div class="linea"><a href="'.$LINKLinea.'">'.$r->scTitulo.'</a></div>
	                    <a href="'.$LINK.'" class="button">VER PRODUCTO</a>
	                </div>
				</div>
				';
			}//if
		}//fn

    
}//class