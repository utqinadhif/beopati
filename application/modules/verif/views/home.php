<?php defined('BASEPATH') OR exit('No direct script access allowed');

add_js('modules/home.js');
?>
<b id="ud" data-sess="<?php echo $privilege;?>">Selamat datang, <?php echo $name;?></b>
<hr>
<div class="row">
	<div class="col-md-5">
		<div class="panel panel-warning no_radius">
		  <div class="panel-heading">
		    <b class="h4">Informasi</b>
		  </div>
		  <div class="panel-body">
		  	<?php echo alert('Belum ada informasi', 'danger');?>
		  </div>
		</div>			
	</div>
	<div class="col-md-7">
		<div class="panel panel-success no_radius">
		  <div class="panel-heading">
		    <button class="btn btn-xs btn-warning" id="notif_refresh">Refresh</button> ::
		    <b class="h4">Inbox</b>
		  </div>
		  <div class="panel-body">
		  	<div class="table-responsive">
		      <table style="width:100%" class="table table-bordered table-hover">
		        <thead>
		          <tr class="info">
		            <th class="text-center">Opsi</th>
		            <th class="text-center">No SEP</th>
		            <th class="text-center">Nama Pasien</th>
		            <th class="text-center">Pesan</th>
		          </tr>
		        </thead>
		        <tbody id="notif_body">
		          <tr>
		            <td colspan="4" class="text-center">Data tidak ditemukan</td>
		          </tr>
		        </tbody>
		      </table>
		    </div>
		  </div>
		</div>			
	</div>
</div>