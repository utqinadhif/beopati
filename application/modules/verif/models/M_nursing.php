<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_nursing extends CI_Model
{    
  function __construct()
  {
      parent::__construct();
  }

  public function get_room()
  {
  	$this->db->select('FMKAMAR_ID AS id, FMKAMARN AS name');
    return $this->db->get('KAMAR_INDUK')->result_array();
  }
}