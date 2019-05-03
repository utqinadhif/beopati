<?php defined('BASEPATH') OR exit('No direct script access allowed');

if(!function_exists('editor'))
{
	function editor($id, $value = '', $config = array())
	{
		$def = array(
			'language'                  => "en",
			'filebrowserImageBrowseUrl' => base_url('themes/default/kcfinder'),
			);

		if(!empty($config)) $def = array_merge($config, $def);

		$val = htmlentities($value, ENT_COMPAT, 'UTF-8', FALSE);
		if(!empty($val)) $val = $value;

		$output = '<textarea name="'.$id.'" id="'.$id.'">'.$value.'</textarea>';
		if(!defined('_CKEDITOR'))
		{
			define('_CKEDITOR', true);
			$output .= '<script type="text/javascript" src="'. base_url('themes/default/ckeditor/ckeditor.js'). '"></script>';
		}
		$output .= 
			'<script type="text/javascript">
				CKEDITOR.replace("'.$id.'", '.json_encode($def).');
			</script>';
		return $output;
	}
}