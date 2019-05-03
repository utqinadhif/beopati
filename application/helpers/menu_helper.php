<?php defined('BASEPATH') OR exit('No direct script access allowed');

if(!function_exists('create_menu'))
{
	function create_menu($menu)
	{
		foreach ($menu as $m)
    {
      if($m['type'] == 0)
      {
        ?>
        <li class="header"><?php echo $m['name'];?></li>
        <?php
      }else{
        if(array_key_exists('sub', $m) && !empty($m['sub']))
        {
          ?>
          <li class="treeview">
            <a href="<?php echo base_url($m['link']);?>">
              <i class="fa fa-<?php echo $m['icon'];?>"></i> <span><?php echo $m['name'];?></span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <?php
              create_menu($m['sub']);
              ?>
            </ul>
          </li>
          <?php
        }else{
          ?>
          <li>
            <a href="<?php echo base_url($m['link']);?>">
              <i class="fa fa-<?php echo $m['icon'];?>"></i> <span><?php echo $m['name'];?></span>
            </a>
          </li>
          <?php
        }
      }
    }
	}
}