<?php defined('BASEPATH') OR exit('No direct script access allowed');

add_js('datatables/js/datatables.js');
add_css('datatables/css/datatables.css');

add_js('sweetalert/js/sweetalert.min.js');
add_css('sweetalert/css/sweetalert.min.css');

add_js('modules/nursing_lists.js');
?>
<div class="panel panel-success no_radius">
  <div class="panel-heading text-center">
	  <b class="h4">List Pasien Rawat Inap</b>
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
          <input name="nrm" class="form-control no_radius s_nursing" id="nrm" placeholder="Masukkan No Rekam Medik" type="text">
        </div>
        <div class="form-group">
          <input name="pname" class="form-control no_radius s_nursing" id="pname" placeholder="Masukkan Nama Pasien" type="text">
        </div>
        <div class="form-group">
          <select class="form-control no_radius s_nursing" id="room" name="room">
          	<option value="">:: Pilih Kamar ::</option>
          	<?php
          	foreach ($data as $room)
          	{
          		?>
	          	<option value="<?php echo $room['id'];?>"><?php echo $room['name'];?></option>
          		<?php
          	}
          	?>
          </select>
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
            <th>Nomor Transaksi</th>
            <th>Nomor RM</th>
            <th>Nama Pasien</th>
            <th>Kamar</th>
            <th>Kelompok</th>
            <th>#</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>
<div id="mdl" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content no_radius">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="mdl_title"></h4>
      </div>
      <div class="modal-body" id="mdl_body"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<script type="text/template" id="files_add">
  <div class="panel panel-default">
    <div class="panel-body">
      <form action="" id="files_add_form">
        <input type="hidden" id="files_add_form_reg" name="reg" value="{reg}">
        <div class="form-group">
          <label for="name">Nama Pendukung:</label>
          <input type="name" class="form-control no_radius" name="name" id="name">
        </div>
        <div class="form-group">
          <label for="file">File Pendukung:</label>
          <input type="file" class="form-control no_radius" name="file" id="file">
        </div>
        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
        <button type="reset" class="btn btn-default"><i class="fa fa-undo"></i> Reset</button>
      </form>
    </div>
  </div>
</script>
<script type="text/template" id="files_list">
  <div class="panel panel-default">
    <div class="panel-body">
      <div class="table-responsive">
        <table style="width:100%" class="table table-bordered table-hover">
          <thead>
            <tr class="info">
              <th class="text-center">File Pendukung</th>
              <th class="text-center">Opsi</th>
            </tr>
          </thead>
          <tbody id="files_list_body">
            <tr>
              <td colspan="2" class="text-center">Data tidak ditemukan</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</script>