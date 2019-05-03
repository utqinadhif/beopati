<?php defined('BASEPATH') OR exit('No direct script access allowed');

if(!function_exists('add_css'))
{
	function add_css($css, $is_return = FALSE)
	{
		$ci      = & get_instance();
		if(empty($ci->beo)) $ci->beo = new stdClass();
		$css_url = '';

		if(is_url($css))
		{
			$css_url = $css;
		}else
		{
			foreach (array(
				'themes/' . $ci->template->get_theme() . '/css/' . $css,
				'themes/default/css/' . $css,
				'themes/default/' . $css,
				) AS $fcss)
			{
				if(file_exists(dirname(APPPATH) . DIRECTORY_SEPARATOR . $fcss))
				{
					$css_url = base_url($fcss);
					break;
				}
			}
		}

		if(!empty($css_url) && empty($ci->beo->css[$css_url]))
		{
			$ci->beo->css[$css_url] = $css_url;

			if($is_return) return '<link href="'.$css_url.'" rel="stylesheet" type="text/css" />';
			else echo '<link href="'.$css_url.'" rel="stylesheet" type="text/css" />';
		}else{
			if($is_return) return '';
			else echo '';
		}
	}
}
if(!function_exists('add_js'))
{
	function add_js($js)
	{
		$ci     = & get_instance();
		if(empty($ci->beo)) $ci->beo = new stdClass();
		$js_url = '';

		if(is_url($js))
		{
			$js_url = $js;
		}else
		{
			foreach (array(
				'themes/' . $ci->template->get_theme() . '/js/' . $js,
				'themes/default/js/' . $js,
				'themes/default/' . $js,
				) AS $fjs)
			{
				if(file_exists(dirname(APPPATH) . DIRECTORY_SEPARATOR . $fjs))
				{
					$js_url = base_url($fjs);
					break;
				}
			}
		}

		if(!empty($js_url) && empty($ci->beo->js[$js_url]))
		{
			$ci->beo->js[$js_url] = $js_url;
		}
	}
}
if(!function_exists('show_js'))
{
	function show_js()
	{
		$ci =& get_instance();
		if(empty($ci->beo)) $ci->beo = new stdClass();
		
		$out = '';
		if(!empty($ci->beo->js))
		{
			foreach ($ci->beo->js as $js)
			{
				$out .= '<script src="'.$js.'" type="text/javascript"></script>'."\n\t";
			}
		}
		return $out;
	}
}