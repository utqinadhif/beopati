<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Verif extends MX_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->_sess = modules::run('verif/user/_check_session');
		$this->template->set_partial('menu', 'menu', $this->_sess);
	}

	public function index()
	{
		$this->home();
	}

	public function home()
	{
		if($this->_sess['privilege'] == 4)
		{
			$this->load->model('M_nursing');
		
			$this->template->build('nursing', array(
				'data' => $this->M_nursing->get_room()
			));
		}else{
			$this->template->build('home', $this->_sess);
		}
	}

	public function process()
	{
		modules::run('verif/user/_check_privilege', $this->_sess, array(1, 2, 3));

		$this->template->build('verif_lists', array_merge($this->_sess, array(
			'get' => $this->security->xss_clean($this->input->get('sep'))
		)));
	}

	public function js()
	{
		ob_start('ob_gzhandler');
		header('content-type: text/javascript; charset: UTF-8');
		header('cache-control: must-revalidate');
		$offset = 60 * 60 * 24 * 365;
		$expire = 'expires: ' . gmdate('D, d M Y H:i:s', time() + $offset) . ' GMT';
		header($expire);
		
		echo 'window.sess='.json_encode($this->_sess).';';

		include_once FCPATH . str_replace('/', DIRECTORY_SEPARATOR, 'themes/default/js/jquery.js');
    include_once FCPATH . str_replace('/', DIRECTORY_SEPARATOR, 'themes/default/bootstrap/js/bootstrap.min.js');
    include_once FCPATH . str_replace('/', DIRECTORY_SEPARATOR, 'themes/verif/js/script.js');
	}
}