<?php defined('BASEPATH') OR exit('No direct script access allowed');

if(!function_exists('tab'))
{
	function tab($data)
	{
		$ci =& get_instance();
		if(empty($ci->beo)) $ci->beo = new stdClass();
		if(empty($ci->beo->tab)) $ci->beo->tab = 0;

		add_js('tab.js');
		$ci->beo->tab++;
		$tablink    = '';
		$tabcontent = '';
		$no         = 0;

		foreach ($data as $title => $view)
		{
			$tablink    .= '<li><a class="no_radius" data-toggle="tab" href="#tab'.$ci->beo->tab.'-'.$no.'" aria-expanded="false">'.$title.'</a></li>';
			$tabcontent .= '<div id="tab'.$ci->beo->tab.'-'.$no.'" class="padtop10 tab-pane fade">'.$view.'</div>';
			$no++;
		}

		return <<<EOT
<div class="nav-tabs-custom tab_beo" data-tabs="{$ci->beo->tab}">
	<ul class="nav nav-tabs">
		{$tablink}
	</ul>
	<div class="tab-content">
		{$tabcontent}
	</div>
</div>
EOT;
	}
}