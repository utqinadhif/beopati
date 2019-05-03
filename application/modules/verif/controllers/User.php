<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MX_Controller {
	
	function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		modules::run('user/login');
	}

	public function login()
	{
		$dt = $this->session->userdata('user');
		if(!empty($dt)) redirect(base_url());

		$this->template->set_layout('t_blank');

		$redirect = $this->security->xss_clean($this->input->get('_'));
		$action   = !empty($redirect) ? base_url('verif/user/login_action?_=' . $redirect) : base_url('verif/user/login_action');

		$this->template->build('user_login', array(
			'msg'    => $this->session->flashdata('user_login'),
			'action' => $action
		));
	}

	public function lists()
	{
		$this->_sess = modules::run('user/_check_session');

		modules::run('data/user/_check_privilege', $this->_sess, 1);

		$this->template->set_partial('menu', 'menu', $this->_sess);

		$this->template->build('user_lists');
	}

	public function logout()
	{
		$this->session->unset_userdata('user');
		redirect(base_url('verif/user/login'));
	}

	public function _check_session($is_return = FALSE)
	{
    $dt = $this->session->userdata('user');
    if(empty($dt))
    {
    	if($is_return) return array();
    	else
    	{
    		if($this->input->is_ajax_request())
	    	{
	    		echo json_encode(array(
						'success' => 2,
						'msg'     => 'Silahkan login untuk melanjutkan.',
						'login'   => base_url('verif/user/login?_='.actual_link())
	    		));
	    		die;
				}else redirect(base_url('verif/user/login?_='.actual_link()));
    	}
    }else return $dt;
	}

	public function _check_privilege($sess, $allow)
	{
		$bool = false;
		if(!empty($sess))
		{
			$bool = is_array($allow) ? in_array($sess['privilege'], $allow) : $sess['privilege'] == $allow;
			if(!$bool)
			{
				if($this->input->is_ajax_request())
				{
					$msg = array(
						'draw'            => 1,
						'recordsTotal'    => 0,
						'recordsFiltered' => 0,
						'data'            => array(),
						'success'         => 0,
						'msg'             => 'Anda tidak memiliki hak akses.',
					);
					echo json_encode($msg);
					die;
				}else{
					redirect(base_url());
				}
			}
		}else{
			if($this->input->is_ajax_request())
			{
				$msg = array(
					'draw'            => 1,
					'recordsTotal'    => 0,
					'recordsFiltered' => 0,
					'data'            => array(),
					'success'         => 2,
					'msg'             => 'Anda harus login.',
					'login'           =>base_url()
				);
				echo json_encode($msg);
				die;
			}else{
				redirect(base_url());
			}
		}
		return $bool;
	}

	public function login_action()
	{
		$sess = modules::run('user/_check_session', TRUE);
		if(!empty($sess)) redirect(base_url());

		$username = $this->security->xss_clean($this->input->post('uname'));
		$password = $this->security->xss_clean($this->input->post('upass'));

		$redirect = $this->security->xss_clean($this->input->get('_'));

		if(!empty($username) && !empty($password))
		{
			$this->load->model('M_user');
			$userdata = $this->M_user->get_user_byName($username);

			if(!empty($userdata))
			{
				if(password_verify($password, $userdata['password']))
				{
					if(!empty($userdata['privilege']))
					{
						$this->M_user->user_edit(array('lastlogin' => date("Y-m-d H:i:s")), $userdata['id']);

						$sess = array(
							'username'  => $userdata['username'],
							'name'      => $userdata['name'],
							'privilege' => $userdata['privilege'],
							'id'        => $userdata['id'],
							'type'      => $userdata['type'],
							);
						$this->session->set_userdata('user', $sess);

						if(empty($redirect)) redirect(base_url());
						else redirect(urldecode(base64_decode($redirect)));
					}else{
						$this->session->set_flashdata('user_login', 'Anda tidak memiliki akses pada aplikasi ini!');
					}
				}else{
					$this->session->set_flashdata('user_login', 'Password Salah!!');
				}
			}else{
				$this->session->set_flashdata('user_login', 'Data tidak ditemukan!');
			}

			redirect(base_url('verif/user/login?_='.actual_link()));
		}		
	}
}