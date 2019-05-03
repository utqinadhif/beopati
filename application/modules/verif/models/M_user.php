<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_user extends CI_Model
{    
  function __construct()
  {
      parent::__construct();
  }

  public function get_user_byName($name = '')
  {
    $this->db->where('flag', 0);
    $this->db->where('username', $name);
    return $this->db->get('dev_verif_user')->row_array();
  }

  public function user_delete($id)
  {
    $this->db->where('id', $id);
    return $this->db->delete('dev_verif_user');
  }

  public function user_detail($id)
  {
    $r = $this->db->where('id', $id)->get('dev_verif_user')->row_array();
    unset($r['password']);
    return $r;
  }

  public function user_save($array)
  {
    return $this->db->insert('dev_verif_user', $array);
  }

  public function user_edit($array, $id)
  {
  	$this->db->where('id', $id);
    return $this->db->update('dev_verif_user', $array);
  }

  public function user_flag($id, $flag=0)
  {
    $this->db->where('id', $id);
    return $this->db->update('dev_verif_user', array('flag' => $flag));
  }
}