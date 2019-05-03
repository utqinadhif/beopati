<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends MX_Controller {
	
	function __construct()
	{
		parent::__construct();

		if(!$this->input->is_ajax_request())
		{
			die('No direct script access allowed');
		}
	}

	public function verif_lists()
	{
		$this->_sess = modules::run('verif/user/_check_session', true);
		modules::run('verif/user/_check_privilege', $this->_sess, array(1, 2, 3));

		$limit = $this->security->xss_clean($this->input->post('limit'));

		if(!empty($search = $this->security->xss_clean($this->input->post('search'))))
		{
			if(empty($limit)) $limit = 10;

			$this->load->model('M_verif');
			$list = $this->M_verif->get_lists($search, $limit, $this->_sess);

			if(!empty($list))
			{
				$ret = array(
					'success' => 1,
					'msg'     => 'Sukses.',
					'data'    => $list
				);
			}else{
				$ret = array(
					'success' => 0,
					'msg'     => 'Data tidak ditemukan.',
					'data'		=> array()
				);
			}
		}else{
			$ret = array(
				'success' => 0,
				'msg'     => 'Data harus diisi.',
				'data'		=> array()
			);
		}

		echo json_encode($ret);
	}

	public function verif_detail()
	{
		$this->_sess = modules::run('verif/user/_check_session', true);
		modules::run('verif/user/_check_privilege', $this->_sess, array(1, 2, 3));

		if(!empty($reg = $this->security->xss_clean($this->input->post('reg'))))
		{
			// update db
			$sep = $this->security->xss_clean($this->input->post('sep'));

			$this->load->model('M_verif');
			$detail = $this->M_verif->get_detail($reg, $sep);

			if(!empty($detail['patient']))
			{
				$ret = array(
					'success' => 1,
					'msg'     => 'Sukses.',
					'data'    => $detail
				);
			}else{
				$ret = array(
					'success' => 0,
					'msg'     => 'Data tidak memiliki detail.',
					'data'		=> array()
				);
			}
		}else{
			$ret = array(
				'success' => 0,
				'msg'     => 'Data harus diisi.',
				'data'		=> array()
			);
		}

		echo json_encode($ret);
	}

	public function verif_sub()
	{
		$this->_sess = modules::run('verif/user/_check_session', true);
		modules::run('verif/user/_check_privilege', $this->_sess, array(1, 2, 3));

		if(
			!empty($type = $this->security->xss_clean($this->input->post('type'))) && 
			!empty($id   = $this->security->xss_clean($this->input->post('id')))
		)
		{
			$this->load->model('M_verif');
			$reff = $this->M_verif->get_reff($id);

			$mix = array('radiologi', 'ekg');

			if(!empty($reff) || in_array($type, $mix))
			{
				switch($type)
				{
					case 'radiologi':
						$r = $this->M_verif->get_radiologi($id, $reff);
					break;
					case 'laborat':
						$r = $this->M_verif->get_laborat($reff);
					break;
					case 'farmasi':
						$r = $this->M_verif->get_farmasi($reff);
					break;
					case 'ekg':
						$r = $this->M_verif->get_ekg($id, $reff);
					break;
					case 'poli':
						$r = $this->M_verif->get_poli($id, $reff);
					break;
					default:
						$r = array();
					break;
				}

				if(!empty($r))
				{
					$ret = array(
						'success' => 1,
						'msg'     => 'Sukses.',
						'data'    => $r
					);
				}else{
					$ret = array(
						'success' => 0,
						'msg'     => 'Data tidak ditemukan atau pasien tidak memiliki catatan terkait.',
						'data'		=> array()
					);
				}				
			}else{
				$ret = array(
					'success' => 0,
					'msg'     => 'Referensi tidak ditemukan.',
					'data'		=> array()
				);
			}
		}else{
			$ret = array(
				'success' => 0,
				'msg'     => 'Data harus diisi.',
				'data'		=> array()
			);
		}

		echo json_encode($ret);
	}

	public function verif_diagnosa()
	{
		$this->_sess = modules::run('verif/user/_check_session', true);
		modules::run('verif/user/_check_privilege', $this->_sess, array(1, 2, 3));

		if(
			!empty($reg        = $this->security->xss_clean($this->input->post('reg'))) && 
			!empty($inacbgcode = $this->security->xss_clean($this->input->post('inacbgcode')))
		)
		{
			$this->load->model('M_verif');

			$ret = array(
				'success' => 1,
				'msg'     => 'Sukses.',
				'data'    => $this->M_verif->get_diagnosa($reg, $inacbgcode)
			);
		}else{
			$ret = array(
				'success' => 0,
				'msg'     => 'Data tidak lengkap.',
				'data'		=> array()
			);
		}

		echo json_encode($ret);
	}

	public function verif_okvk()
	{
		$this->_sess = modules::run('verif/user/_check_session', true);
		modules::run('verif/user/_check_privilege', $this->_sess, array(1, 2, 3));

		if(!empty($reg = $this->security->xss_clean($this->input->post('reg'))))
		{
			$this->load->model('M_verif');

			$reff = $this->M_verif->get_reff($reg);

			if(!empty($reff))
			{
				$okvk = $this->M_verif->get_okvk($reff);

				if(!empty($okvk))
				{
					$ret = array(
						'success' => 1,
						'msg'     => 'Sukses.',
						'data'    => $okvk
					);
				}else{
					$ret = array(
						'success' => 0,
						'msg'     => 'Pasien tidak memiliki detail OK/ VK.',
						'data'		=> array()
					);		
				}
			}else{
				$ret = array(
					'success' => 0,
					'msg'     => 'Referensi tidak ditemukan.',
					'data'		=> array()
				);
			}

		}else{
			$ret = array(
				'success' => 0,
				'msg'     => 'Data tidak lengkap.',
				'data'		=> array()
			);
		}

		echo json_encode($ret);
	}

	public function user_lists()
	{
		$this->_sess = modules::run('verif/user/_check_session');
		modules::run('verif/user/_check_privilege', $this->_sess, 1);
		
		$this->load->library('Datatables');
		$this->load->helper('modules');

		$s_username = $this->security->xss_clean($this->input->post('custom[s_username]'));
		$this->datatables->like('username', $s_username);
		
		$s_name = $this->security->xss_clean($this->input->post('custom[s_name]'));
		$this->datatables->like('name', $s_name);

		$this->datatables->where('flag', 0);

    $this->datatables->select('id, username, name, privilege, type, lastlogin');
    $this->datatables->from('dev_verif_user');

    $this->datatables->add_column('action', '
        <button class="btn btn-warning btn-xs no_radius action" data-action="edit" data-id="$1"><i class="fa fa-edit"></i> Edit</button>
        <button class="btn btn-danger btn-xs no_radius action" data-action="delete" data-id="$1"><i class="fa fa-trash"></i> Hapus</button>
        ', 'id');
    $this->datatables->edit_column('lastlogin', '$1', 'date_ind(lastlogin)');
    $this->datatables->edit_column('privilege', '$1', 'user_privilege(privilege)');
    $this->datatables->edit_column('type', '$1', 'user_type(type)');
    $ignited = $this->datatables->generate();

    echo $ignited;
	}

	public function user_delete()
	{
		$this->_sess = modules::run('verif/user/_check_session');
		modules::run('verif/user/_check_privilege', $this->_sess, 1);

		if(!empty($id = $this->security->xss_clean($this->input->post('id'))))
		{
			$this->load->model('M_user');
			if($this->M_user->user_flag($id, 1))
			{
				$msg = array(
					'success' => 1,
					'msg' 		=> 'berhasil menghapus data',
				);
			}else{
				$msg = array(
					'success' => 0,
					'msg' 		=> 'penghapusan data gagal.',
				);
			}
		}else{
			$msg = array(
				'success' => 0,
				'msg' 		=> 'Data tidak ditemukan.'
			);
		}

    echo json_encode($msg);
	}

	public function user_detail($id)
	{
		$this->_sess = modules::run('verif/user/_check_session');
		modules::run('verif/user/_check_privilege', $this->_sess, 1);

		if(!empty($id))
		{
			$this->load->model('M_user');
			$msg = array(
				'success' => 1,
				'msg' 		=> 'Success.',
				'datas'		=> $this->M_user->user_detail($id)
			);
		}else{
			$msg = array(
				'success' => 0,
				'msg' 		=> 'Data tidak ditemukan.'
			);
		}

    echo json_encode($msg);
	}

	public function user_add_edit()
	{
		$this->_sess = modules::run('verif/user/_check_session');
		modules::run('verif/user/_check_privilege', $this->_sess, 1);

		if(
			!empty($username  = $this->security->xss_clean($this->input->post('username'))) &&
			!empty($name      = $this->security->xss_clean($this->input->post('name'))) &&
			!empty($password  = $this->security->xss_clean($this->input->post('password'))) &&
			!empty($privilege = $this->security->xss_clean($this->input->post('privilege')))&&
			!empty($type      = $this->security->xss_clean($this->input->post('type')))
		)
		{
			$array = array(
				'username'  => $username,  
				'name'      => $name,          
				'password'  => password_hash($password, PASSWORD_BCRYPT),  
				'privilege' => $privilege,
				'type'      => $type,
			);
			$this->load->model('M_user');

			if(!empty($id = $this->security->xss_clean($this->input->post('id'))))
			{
				$res = $this->M_user->user_edit($array, $id);
			}else{
				$res = $this->M_user->user_save($array);
			}

			if($res)
			{
				$msg = array(
					'success' => 1,
					'msg' 		=> 'Proses berhasil.'
				);
			}else{
				$msg = array(
					'success' => 0,
					'msg' 		=> 'Kesalahan sistem.'
				);
			}
		}else{
			$msg = array(
				'success' => 0,
				'msg' 		=> 'Lengkapi data anda.'
			);
		}

		echo json_encode($msg);
	}

	public function verif_files_list()
	{
		$this->_sess = modules::run('verif/user/_check_session');

		if(!empty($reg = $this->security->xss_clean($this->input->post('reg'))))
		{
			$this->load->model('M_verif');

			$msg = array(
				'success' => 1,
				'msg'     => 'sukses',
				'data'    => $this->M_verif->get_files($reg)
			);
		}else{
			$msg = array(
				'success' => 0,
				'msg' 		=> 'Lengkapi data anda.'
			);
		}

		echo json_encode($msg);
	}

	public function verif_files_add()
	{
		$this->_sess = modules::run('verif/user/_check_session', true);
		modules::run('verif/user/_check_privilege', $this->_sess, array(1, 2, 4));

		if(
			!empty($reg  = $this->security->xss_clean($this->input->post('reg'))) &&
			!empty($name = $this->security->xss_clean($this->input->post('name'))) &&
			!empty($_FILES['file']['name'])
		)
		{
			$file = '';

			$config['upload_path']   = './image/verif';
			$config['allowed_types'] = '*';
			$config['encrypt_name']  = TRUE;
       
      $this->load->library('upload', $config);

      if($this->upload->do_upload('file'))
      {
        $data = $this->upload->data();
        $file = $data['file_name'];
      }else{
      	$error = $this->upload->display_errors();
      }

      if(!empty($file))
      {
				$array = array(
					'reg'   => $reg,
					'name'  => $name,
					'files' => './image/verif/' . $file,
				);
				$this->load->model('M_verif');

				$res = $this->M_verif->save_files($array);

				if($res)
				{
					$msg = array(
						'success' => 1,
						'msg'     => 'Proses berhasil.',
						'data'    => array()
					);
				}else{
					$msg = array(
						'success' => 0,
						'msg'     => 'Kesalahan sistem.',
						'data'    => array()
					);
				}	
      }else{
      	$msg = array(
					'success' => 0,
					'msg'     => $error,
					'data'    => array()
				);
      }
		}else{
			$msg = array(
				'success' => 0,
				'msg'     => 'Lengkapi data anda.',
				'data'    => array()
			);
		}

		echo json_encode($msg);
	}

	public function verif_files_delete()
	{
		$this->_sess = modules::run('verif/user/_check_session', true);
		modules::run('verif/user/_check_privilege', $this->_sess, array(1, 2, 4));

		if(!empty($id = $this->security->xss_clean($this->input->post('id'))))
		{
			$this->load->model('M_verif');
			if($this->M_verif->delete_files($id, 1))
			{
				$msg = array(
					'success' => 1,
					'msg' 		=> 'Berhasil menghapus data',
				);
			}else{
				$msg = array(
					'success' => 0,
					'msg' 		=> 'Penghapusan data gagal.',
				);
			}
		}else{
			$msg = array(
				'success' => 0,
				'msg' 		=> 'Data tidak ditemukan.'
			);
		}

    echo json_encode($msg);
	}

	public function verif_message_view()
	{
		$this->_sess = modules::run('verif/user/_check_session', true);
		modules::run('verif/user/_check_privilege', $this->_sess, array(1, 2, 3));

		if(!empty($reg = $this->security->xss_clean($this->input->post('reg'))))
		{
			$this->load->model('M_verif');
			$data = $this->M_verif->get_msg($reg);

			$msg = array(
				'success' => 1,
				'msg'     => 'Berhasil',
				'data'    => array(
					'msg'    => !empty($data['message']) ? $data['message'] : '',
					'status' => @intval($data['status'])
				)
			);
		}else{
			$msg = array(
				'success' => 0,
				'msg' 		=> 'Data tidak ditemukan.'
			);
		}

    echo json_encode($msg);
	}

	public function verif_message_add()
	{
		$this->_sess = modules::run('verif/user/_check_session', true);
		modules::run('verif/user/_check_privilege', $this->_sess, 3);

		if(
			!empty($sep     = $this->security->xss_clean($this->input->post('sep'))) &&
			!empty($reg     = $this->security->xss_clean($this->input->post('reg'))) &&
			!empty($user_id = $this->security->xss_clean($this->input->post('user_id'))) &&
			!empty($type    = $this->security->xss_clean($this->input->post('type'))) &&
			!empty($msg     = $this->security->xss_clean($this->input->post('msg')))
		)
		{
			$this->load->model('M_verif');
			$data = $this->M_verif->get_msg($reg);

			$array = array(
				'sep'     => $sep,
				'user_id' => $user_id,
				'message' => $msg,
				'status'  => 0
			);

			if(!empty($data))
			{
				$res = $this->M_verif->update_msg($array, $reg);
			}else{
				$array = array_merge($array, array(
					'reg'  => $reg,
					'type' => $type
				));
				$res = $this->M_verif->save_msg($array);
			}
	
			if($res)
			{
				$msg = array(
					'success' => 1,
					'msg'     => 'Berhasil',
					'data'    => array(
						'msg'    => $msg,
						'status' => 0
					)
				);
			}else{
				$msg = array(
					'success' => 0,
					'msg'     => 'Kesalahan sistem.',
					'data'    => array()
				);
			}
		}else{
			$msg = array(
				'success' => 0,
				'msg'     => 'Lengkapi data anda.',
				'data'    => array()
			);
		}

		echo json_encode($msg);
	}

	public function verif_message_confirm()
	{
		$this->_sess = modules::run('verif/user/_check_session', true);
		modules::run('verif/user/_check_privilege', $this->_sess, array(1, 2));

		if(!empty($reg = $this->security->xss_clean($this->input->post('reg'))))
		{
			$this->load->model('M_verif');
			$data = $this->M_verif->confirm_msg($reg);

			$msg = array(
				'success' => 1,
				'msg'     => 'Berhasil',
				'data'    => array(
					'msg'    => !empty($data['message']) ? $data['message'] : '',
					'status' => @intval($data['status'])
				)
			);
		}else{
			$msg = array(
				'success' => 0,
				'msg' 		=> 'Data tidak ditemukan.'
			);
		}

    echo json_encode($msg);
	}

	public function verif_message_notif()
	{
		$this->_sess = modules::run('verif/user/_check_session', true);
		modules::run('verif/user/_check_privilege', $this->_sess, array(1, 2, 3));

		$this->load->model('M_verif');

		if($this->_sess['privilege'] == 3)
		{			
			$data = $this->M_verif->notif(1, $this->_sess['type']);
		}else{
			$data = $this->M_verif->notif(0, $this->_sess['type']);
		}

		echo json_encode(array(
			'success' => 1,
			'msg'     => 'Sukses.',
			'data'    => @intval($data['count'])
		));
	}

	public function verif_message_show()
	{
		$this->_sess = modules::run('verif/user/_check_session', true);
		modules::run('verif/user/_check_privilege', $this->_sess, array(1, 2, 3));

		$this->load->model('M_verif');

		if($this->_sess['privilege'] == 3)
		{			
			$data = $this->M_verif->notif_show(1, $this->_sess['type']);
		}else{
			$data = $this->M_verif->notif_show(0, $this->_sess['type']);
		}

		echo json_encode(array(
			'success' => 1,
			'msg'     => 'Sukses.',
			'data'    => $data
		));
	}

	public function verif_message_close()
	{
		$this->_sess = modules::run('verif/user/_check_session', true);
		modules::run('verif/user/_check_privilege', $this->_sess, 3);

		if(!empty($reg = $this->security->xss_clean($this->input->post('reg'))))
		{
			$this->load->model('M_verif');
			$data = $this->M_verif->confirm_msg($reg, 2);

			$msg = array(
				'success' => 1,
				'msg'     => 'Berhasil',
				'data'    => $this->M_verif->notif_show(1, $this->_sess['type'])
			);
		}else{
			$msg = array(
				'success' => 0,
				'msg' 		=> 'Data tidak ditemukan.'
			);
		}

    echo json_encode($msg);
	}

	public function nursing_lists()
	{
		$this->_sess = modules::run('verif/user/_check_session');
		modules::run('verif/user/_check_privilege', $this->_sess, array(1, 4));
		
		$this->load->library('Datatables');

		$this->datatables->select('PRWINO_TRANSAKSI,PRWIKD_PASIEN,NAMAPASIEN,FMKNAMA_KAMAR,FMKAMARN');

		$nrm = $this->security->xss_clean($this->input->post('custom[nrm]'));
		$this->datatables->like('PRWIKD_PASIEN', $nrm);
		
		$name = $this->security->xss_clean($this->input->post('custom[pname]'));
		$this->datatables->like('NAMAPASIEN', $name);

		$room = $this->security->xss_clean($this->input->post('custom[room]'));
		$this->datatables->like('FMKAMAR_ID', $room);

    $this->datatables->where('PRWITGL_KELUAR IS NULL');

    $this->datatables->join('KAMAR', 'PRWIKD_KAMAR=FMKKAMAR_ID', 'left');
    $this->datatables->join('KAMAR_INDUK', 'FMKKAMARINDUK=FMKAMAR_ID', 'left');
    $this->datatables->join('PASIEN', 'PRWIKD_PASIEN=KD_PASIEN', 'left');

    $this->datatables->from('PASIENRAWATINAP');

    $this->datatables->add_column('action', '
        <button class="btn btn-info btn-xs no_radius action" data-reg="$1" data-nrm="$2"><i class="fa fa-file"></i> File Penunjang</button>
        ', 'PRWINO_TRANSAKSI,PRWIKD_PASIEN');
		
    $ignited = $this->datatables->generate();

    echo $ignited;
	}
}