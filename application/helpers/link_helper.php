<?php defined('BASEPATH') OR exit('No direct script access allowed');

if(!function_exists('create_link'))
{
	function create_link($txt, $replace='', $add = '')
	{
		if(empty($replace)) $replace = '-';
		if(!empty($add)) $add = '.'.$add;
		
		$output = strtolower(preg_replace( array('~\&~i','~[^a-z0-9\-_]~i','~'.preg_quote($replace, '~').'{2,}~i'), array('n',$replace,$replace), trim($txt)));
		return substr(trim($output, $replace), 0, 50).$add;
	}
}

if(!function_exists('create_check_link'))
{
	function create_check_link($link, $table, $field)
	{
		$ci   = get_instance();
		$x    = 0;
		$link = create_link($link);
		$ci->load->model('config/admin/M_config');
		do {
			$nlink      = $x == 0 ? $link : $link.$x;
			$true_link  = $ci->M_config->check_link($nlink, $table, $field);
			$x++;
		} while ($true_link);

		return $nlink;
	}
}