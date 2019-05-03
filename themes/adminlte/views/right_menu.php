<aside class="control-sidebar control-sidebar-light">
  <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
    <li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-bars"></i> Menu</a></li>
  </ul>
  <div class="tab-content">
    <div class="tab-pane active" id="control-sidebar-home-tab">
      <h3 class="control-sidebar-heading">Penunjang</h3>
      <ul class="control-sidebar-menu">
        <li>
          <a href="<?php echo base_url();?>">
            <i class="menu-icon ion ion-home bg-black"></i>
            <div class="menu-info">
              <h4 class="control-sidebar-subheading">Beranda</h4>
              <p>Beranda Utama</p>
            </div>
          </a>
        </li>
      </ul>
      <h3 class="control-sidebar-heading">Poliklinik</h3>
      <ul class="control-sidebar-menu">
        <li>
          <a href="<?php echo base_url('poliklinik/tbdot');?>">
            <i class="menu-icon ion ion-bag bg-aqua"></i>
            <div class="menu-info">
              <h4 class="control-sidebar-subheading">Tb Dot</h4>
              <p>Menu untuk Bagian Tb Dot</p>
            </div>
          </a>
        </li>
      </ul>
      <h3 class="control-sidebar-heading">Penunjang</h3>
      <ul class="control-sidebar-menu">
        <li>
          <a href="<?php echo base_url('penunjang/laboratorium');?>">
            <i class="menu-icon ion ion-beaker bg-maroon"></i>
            <div class="menu-info">
              <h4 class="control-sidebar-subheading">Laboratorium</h4>
              <p>Menu Laboratorium</p>
            </div>
          </a>
        </li>
        <li>
          <a href="<?php echo base_url('penunjang/hemodialisa');?>">
            <i class="menu-icon ion ion-ribbon-b bg-maroon"></i>
            <div class="menu-info">
              <h4 class="control-sidebar-subheading">Hemodialisa</h4>
              <p>Menu Hemodialisa</p>
            </div>
          </a>
        </li>
      </ul>
      <h3 class="control-sidebar-heading">Konfigurasi</h3>
      <ul class="control-sidebar-menu">
        <li>
          <a href="<?php echo base_url('user/logout');?>">
            <i class="menu-icon ion ion-android-close bg-red"></i>
            <div class="menu-info">
              <h4 class="control-sidebar-subheading">Logout</h4>
              <p>Logout sistem</p>
            </div>
          </a>
        </li>
      </ul>
    </div>
  </div>
</aside>