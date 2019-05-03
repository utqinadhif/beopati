<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<ul class="nav navbar-nav navbar-right">
  <li><a href="<?php echo base_url();?>">Beranda <span class="badge" id="notif_count">0</span></a></li>
  <?php
  if($privilege != 4)
  {
    ?>
    <li><a href="<?php echo base_url('verif/process');?>">Verifikasi</a></li>
    <?php
    if($privilege == 1)
    {
      ?>
      <li><a href="<?php echo base_url('verif/user/lists');?>">User</a></li>
      <?php
    }
  }
  ?>
  <li><a href="<?php echo base_url('verif/user/logout');?>">Keluar</a></li>
</ul>