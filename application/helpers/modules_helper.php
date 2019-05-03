<?php defined('BASEPATH') OR exit('No direct script access allowed');

if(!function_exists('user_privilege'))
{
  function user_privilege($status)
  {
    $sts = array('Administrator', 'RSI', 'BPJS', 'Perawatan');
    return ucfirst($sts[$status - 1]);
  }
}

if(!function_exists('user_type'))
{
  function user_type($type)
  {
    $sts = array('Rawat Inap', 'Rawat Jalan', 'Rawat Jalan + Rawat Inap');
    return ucfirst($sts[$type - 1]);
  }
}