<?php defined('BASEPATH') OR exit('No direct script access allowed');

add_js('datatables/js/datatables.js');
add_css('datatables/css/datatables.css');

add_js('modules/user_lists.js');
?>
<div class="row">
	<div class="col-md-4">
		<div class="panel panel-danger no_radius">
		  <div class="panel-heading text-center">
		  	<b class="h4">Kelola User</b>
		  </div>
		  <div class="panel-body">
		  	<form role="form" method="POST" action="" id="user_add_edit">
			    <div class="modal-body">
			    	<div class="form-group">
					    <label for="username"><span class="fa fa-user"></span> Username</label>
					    <input name="username" class="form-control no_radius" id="username" placeholder="Masukkan username" type="text">
					  </div>
					  <div class="form-group">
					    <label for="name"><span class="fa fa-users"></span> Nama</label>
					    <input name="name" class="form-control no_radius" id="name" placeholder="Masukkan Nama" type="text">
					  </div>
					  <div class="form-group">
					    <label for="password"><span class="fa fa-eye-slash"></span> Password</label>
					    <input name="password" class="form-control no_radius" id="password" placeholder="Masukkan password" type="password">
					  </div>
					  <div class="form-group">
					    <label for="privilege"><span class="fa fa-lock"></span> Privilege</label>
					    <select class="form-control no_radius" id="privilege" name="privilege">
					    	<option value="">:: Pilih Privilege ::</option>
					    	<option value="1">Administrator</option>
					    	<option value="2">RSI</option>
					    	<option value="3">BPJS</option>
					    	<option value="4">Perawatan</option>
					    </select>
					  </div>
					  <div class="form-group">
					    <label for="type"><span class="fa fa-book"></span> Tipe Pasien</label>
					    <select class="form-control no_radius" id="type" name="type">
					    	<option value="">:: Pilih Tipe Pasien ::</option>
					    	<option value="1">Rawat Inap</option>
					    	<option value="2">Rawat Jalan</option>
					    	<option value="3">Rawat Inap + Rawat Jalan</option>
					    </select>
					  </div>
			    </div>
			    <div class="modal-footer">
			    	<input type="text" name="id" id="id" style="display: none;">
			      <button type="reset" class="btn btn-default no_radius btn-sm" id="reset"><span class="fa fa-undo"></span> Reset</button>
			      <button type="submit" class="btn btn-warning no_radius btn-sm"><span class="fa fa-save"></span> Simpan</button>
			    </div>
				</form>
		  </div>
		</div>
	</div>
	<div class="col-md-8">
		<div class="panel panel-success no_radius">
		  <div class="panel-heading text-center">
			  <b class="h4">List User</b>
			</div>
		  <div class="panel-body">
		  	<div class="pull-left">
		  		<form class="form-inline" action="">
	          <div class="form-group">
	            <label class="text-red">
	              Search
	            </label>
	          </div>
	          <div class="form-group">
	            <input name="s_username" class="form-control no_radius s_user" id="s_username" placeholder="Masukkan username" type="text">
	            <input name="s_name" class="form-control no_radius s_user" id="s_name" placeholder="Masukkan nama" type="text">
	          </div>
	        </form>
		  	</div>
		  	<div class="pull-right">
		  		<button type="button" class="btn no_radius btn-default text-blue btn-xs" id="reload">
	          <i class="fa fa-refresh"></i> Refresh
	        </button>
		  	</div>
		  	<div class="clearfix"></div>
		  	<hr>
		  	<div class="table-responsive">
		      <table style="width:100%" class="table table-bordered table-hover" id="lists_datatables">
		        <thead>
		          <tr>
		            <th>ID</th>
		            <th>Username</th>
		            <th>Name</th>
		            <th>Privilege</th>
		            <th>Tipe</th>
		            <th>Login Terakhir</th>
		            <th>#</th>
		          </tr>
		        </thead>
		      </table>
		    </div>
		  </div>
		</div>
	</div>
</div>