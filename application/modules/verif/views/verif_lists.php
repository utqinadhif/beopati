<?php defined('BASEPATH') OR exit('No direct script access allowed');

add_js('datatables/js/datatables.js');
add_css('datatables/css/datatables.css');

add_js('sweetalert/js/sweetalert.min.js');
add_css('sweetalert/css/sweetalert.min.css');

add_js('modules/verif_lists.js');
?>
<div class="panel panel-info no_radius" id="panel_list">
  <div class="panel-heading">
    <b class="h4">Verifikasi</b>
  </div>
  <div class="panel-body">
		<form action="#" id="verif_search_form">
      <div class="input-group">
        <input type="text" class="form-control no_radius verif_search" id="verif_search_name" placeholder="Cari No. RM / No. SEP / Nama / No Kartu" value="<?php echo $get;?>">
        <input type="hidden" id="verif_search_view_list" value="10">
        <div class="input-group-btn">
          <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Tampilkan <span id="view_list">10 data</span> <span class="caret"></span></button>
          <ul class="dropdown-menu pull-right">
            <li><a class="view_lists" href="" data-list="10">10 data</a></li>
            <li><a class="view_lists" href="" data-list="20">20 data</a></li>
            <li><a class="view_lists" href="" data-list="50">50 data</a></li>
            <li><a class="view_lists" href="" data-list="100">100 data</a></li>
          </ul>
        </div>
        <div class="input-group-btn">
          <button class="btn btn-default" type="submit">
            <i class="glyphicon glyphicon-search"></i> Cari
          </button>
        </div>
      </div>
    </form>
    <hr>
  	<div class="table-responsive">
      <table style="width:100%" class="table table-bordered table-hover">
        <thead>
          <tr class="warning">
            <th class="text-center">No Transaksi</th>
            <th class="text-center">No RM</th>
            <th class="text-center">Nama Pasien</th>
            <th class="text-center">No SEP</th>
            <th class="text-center">No Kartu</th>
            <th class="text-center">Jenis</th>
            <th class="text-center">Tanggal</th>
          </tr>
        </thead>
        <tbody id="table_list">
          <tr>
            <td colspan="7" class="text-center">Data tidak ditemukan</td>
          </tr>
        </tbody>
      </table>
    </div>
    <center>
      <button id="next" class="no_radius btn btn-default" style="display: none">Muat Lainnya</button>
    </center>
  </div>
</div>
<div class="panel panel-success no_radius" id="panel_detail" style="display: none">
  <div class="panel-heading">
    <button class="btn btn-danger btn-xs show_list"><i class="fa fa-angle-left"></i> Kembali</button> :: 
    <b class="h4">Detail - <strong id="verif_sep"></strong></b>
  </div>
  <div class="panel-body">
    <div class="row" id="detail_fill"></div>
    <div class="table-responsive">
      <table style="width:100%" class="table table-bordered table-hover">
        <thead>
          <tr class="info">
            <th class="text-center">No</th>
            <th class="text-center">Uraian</th>
            <th class="text-center">Biaya</th>
          </tr>
        </thead>
        <tbody id="table_detail">
          <tr>
            <td colspan="4">Data tidak ditemukan</td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="table-responsive">
      <table style="width:100%" class="table table-bordered table-hover">
        <tbody>
          <tr>
            <th colspan="2">Total Biaya</th>
            <th id="tot" class="text-right">0</th>
          </tr>
          <tr>
            <th>Total Inacbg</th>
            <th id="code" class="text-center warning">xxxxxxx</th>
            <th id="inacbg" class="text-right">0</th>
          </tr>
          <tr>
            <th colspan="2">Selisih</th>
            <th id="grand" class="text-right">0</th>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="pull-left">
      <div class="btn-group">
        <button class="btn btn-danger show_list"><i class="fa fa-angle-left"></i> Kembali</button>
        <button class="btn btn-warning subdetail" id="btn_message" data-type="message"><?php echo $privilege == 3 ? '<i class="fa fa-envelope"></i> Pesan' : '<i class="fa fa-envelope"></i> Inbox';?></button>
      </div>
    </div>
    <div class="pull-right">
      <div class="dropup">
        <button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown">
          <i class="fa fa-question-circle"></i> Data Penunjang
          <span class="caret"></span>
        </button>
        <ul class="dropdown-menu no_radius">
          <li><a class="subdetail" data-type="laborat" href=""><i class="fa fa-flask"></i> Laborat</a></li>
          <li><a class="subdetail" data-type="radiologi" href=""><i class="fa fa-arrows-alt"></i> Radiologi/ USG</a></li>
          <li><a class="subdetail" data-type="farmasi" href=""><i class="fa fa-plus-square"></i> Farmasi</a></li>
          <li class="divider"></li>
          <li><a class="subdetail" data-type="ekg" href=""><i class="fa fa-eye"></i> Bacaan ECG/ EKG</a></li>
          <li><a class="subdetail" data-type="poli" href=""><i class="fa fa-thermometer-full"></i> Tindakan Poliklinik</a></li>
          <li class="divider"></li>
          <li><a class="subdetail" data-type="diagnosa" href=""><i class="fa fa-hotel"></i> Lap Individual</a></li>
          <li><a class="subdetail" data-type="okvk" href=""><i class="fa fa-cut"></i> Lap OK/ VK</a></li>
          <li class="divider"></li>
          <li><a class="subdetail" data-type="file" href="#"><i class="fa fa-paperclip"></i> Lampiran</a></li>
        </ul>
      </div>
    </div>
    <div class="clearfix"></div>
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
<script type="text/template" id="res_lab">
  <div class="panel panel-danger">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#res_lab_parent" href="#res_lab_{numb}">{res_title}</a>
      </h4>
    </div>
    <div id="res_lab_{numb}" class="panel-collapse collapse">
      <div class="panel-body">
        <p>Dokter: <b>{doctor}</b></p>
        <p>Tanggal: <b>{date}</b></p>
        <div class="table-responsive">
          <table style="width:100%" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th class="text-center">Nama Pemeriksaan</th>
                <th class="text-center">Hasil</th>
                <th class="text-center">Angka Normal</th>
                <th class="text-center">Satuan</th>
              </tr>
            </thead>
            <tbody>{res_body}</tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</script>
<script type="text/template" id="res_rad">
  <div class="panel panel-info">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#res_rad_parent" href="#res_rad_{numb}">{res_title}</a>
      </h4>
    </div>
    <div id="res_rad_{numb}" class="panel-collapse collapse">
      <div class="panel-body">
        <p>Dokter: <b>{doctor}</b></p>
        <p>Tanggal: <b>{date}</b></p>
        <div class="form-group">
          <label><u>{produk}</u></label>
          <textarea style="height:450px;" class="fittextarea form-control no_radius" readonly>{result}</textarea>
        </div>
      </div>
    </div>
  </div>
</script>
<script type="text/template" id="res_far">
  <div class="panel panel-warning">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#res_far_parent" href="#res_far_{numb}">{res_title}</a>
      </h4>
    </div>
    <div id="res_far_{numb}" class="panel-collapse collapse">
      <div class="panel-body">
        <p>Dokter: <b>{doctor}</b></p>
        <p>Tanggal: <b>{date}</b></p>
        <div class="table-responsive">
          <table style="width:100%" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th class="text-center">Nama Obat</th>
                <th class="text-center">QTY</th>
                <th class="text-center">Satuan</th>
                <th class="text-center">Total</th>
              </tr>
            </thead>
            <tbody>{res_body}</tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</script>
<script type="text/template" id="res_retur">
  <div class="panel panel-danger">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#res_retur_parent" href="#res_retur_{numb}">{res_title}</a>
      </h4>
    </div>
    <div id="res_retur_{numb}" class="panel-collapse collapse">
      <div class="panel-body">
        <p>Dokter: <b>{doctor}</b></p>
        <p>Tanggal: <b>{date}</b></p>
        <div class="table-responsive">
          <table style="width:100%" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th class="text-center">Nama Obat</th>
                <th class="text-center">QTY</th>
                <th class="text-center">Satuan</th>
                <th class="text-center">Total</th>
              </tr>
            </thead>
            <tbody>{res_body}</tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</script>
<script type="text/template" id="res_patient">
  <table style="width:100%" class="table table-bordered table-hover">
    <tbody>
      <tr>
        <td>No RM</td>
        <td>:</td>
        <th>{patient_rm}</th>
      </tr>
      <tr>
        <td>Nama Pasien</td>
        <td>:</td>
        <th>{patient_name}</th>
      </tr>
      <tr>
        <td>Alamat</td>
        <td>:</td>
        <th>{patient_address}</th>
      </tr>
    </tbody>
  </table>
</script>
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
<script type="text/template" id="msg_bpjs">
  <div class="panel panel-default">
    <div class="panel-body">
      <form action="" id="msg_form" method="POST">
        <input type="hidden" id="msg_form_sep" name="sep" value="{sep}">
        <input type="hidden" id="msg_form_reg" name="reg" value="{reg}">
        <input type="hidden" id="msg_form_user_id" name="user_id" value="{user_id}">
        <input type="hidden" id="msg_form_type" name="type" value="{type}">
        <div class="form-group">
          <label for="msg">Pesan:</label>
          <textarea class="form-control no_radius" name="msg" id="msg">{msg}</textarea>
        </div>
        <div class="form-group">
          <label for="status">Status:</label>
          <input type="status" class="form-control no_radius" id="status" readonly="true" value="{status}">
        </div>
        <button type="submit" class="btn btn-success"><i class="fa fa-paper-plane"></i> Submit</button>
        <button type="reset" class="btn btn-default"><i class="fa fa-undo"></i> Reset</button>
      </form>
    </div>
  </div>
</script>
<script type="text/template" id="diagnosa">
  <div class="row">{patient}</div>
  <strong>Diagnosa</strong>
  <table style="width:100%" class="table table-bordered table-hover">
    <thead>
      <tr class="success">
        <th class="text-center">Diagnosa</th>
        <th class="text-center">Kode</th>
        <th class="text-center">Description</th>
      </tr>
    </thead>
    <tbody>
      {diagnosa_body}
    </tbody>
  </table>
  <strong>Prosedur</strong>
  <table style="width:100%" class="table table-bordered table-hover">
    <thead>
      <tr class="warning">
        <th class="text-center">Kode</th>
        <th class="text-center">Description</th>
      </tr>
    </thead>
    <tbody>
      {procedure_body}
    </tbody>
  </table>
  <strong>Hasil Grouping</strong>
  <table style="width:100%" class="table table-bordered table-hover">
    <tbody>
      <tr class="danger">
        <th>{inacode}</th>
        <th class="text-center">{inacaption}</th>
        <th class="text-right">{inaprice}</th>
      </tr>
      <tr class="danger">
        <th colspan="2">Tambah Biaya</b></th>
        <th class="text-right">{add_pay}</th>
      </tr>
    </tbody>
  </table>
</script>
<script type="text/template" id="detail_template">
  <div class="col-md-6">
    <table style="width:100%" class="table">
      <tbody>
        <tr>
          <td>No Peserta</td>
          <td>:</td>
          <td>{npatient}</td>
        </tr>
        <tr>
          <td>No RM</td>
          <td>:</td>
          <td id="nrm">{nrm}</td>
        </tr>
        <tr>
          <td>Nama Pasien</td>
          <td>:</td>
          <td id="patient_name">{patient_name}</td>
        </tr>
        <tr>
          <td>Alamat</td>
          <td>:</td>
          <td id="address">{address}</td>
        </tr>
        <tr>
          <td>Umur Tahun</td>
          <td>:</td>
          <td>{years}</td>
        </tr>
        <tr>
          <td>Umur Hari</td>
          <td>:</td>
          <td>{days}</td>
        </tr>
        <tr>
          <td>Tanggal Lahir</td>
          <td>:</td>
          <td>{birthday}</td>
        </tr>
        <tr>
          <td>Jenis Kelamin</td>
          <td>:</td>
          <td>{gender}</td>
        </tr>
        <tr>
          <td>Kelas Perawatan</td>
          <td>:</td>
          <td>{nursing}</td>
        </tr>
        <tr>
          <td>Hak Kelas</td>
          <td>:</td>
          <td>{mclass}</td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="col-md-6">
    <table style="widtd:100%" class="table">
      <tbody>
        <tr>
          <td>No Kwitansi</td>
          <td>:</td>
          <td>{kwitansi}</td>
        </tr>
        <tr>
          <td>Kelompok Pasien</td>
          <td>:</td>
          <td>{gpatient}</td>
        </tr>
        <tr>
          <td>Tgl Masuk</td>
          <td>:</td>
          <td>{pin}</td>
        </tr>
        <tr>
          <td>Tgl Keluar</td>
          <td>:</td>
          <td>{pout}</td>
        </tr>
        <tr>
          <td>Jenis Perawatan</td>
          <td>:</td>
          <td>{nursing_type}</td>
        </tr>
        <tr>
          <td>Cara Pulang</td>
          <td>:</td>
          <td>{home_back}</td>
        </tr>
        <tr>
          <td>LOS</td>
          <td>:</td>
          <td>{los}</td>
        </tr>
        <tr>
          <td>Berat Lahir</td>
          <td>:</td>
          <td>{weigth}</td>
        </tr>
        <tr>
          <td>Ruang/ Kamar</td>
          <td>:</td>
          <td>{room}</td>
        </tr>
        <tr>
          <td>Kelas</td>
          <td>:</td>
          <td>{class}</td>
        </tr>
      </tbody>
    </table>
  </div>
</script>
<script type="text/template" id="res_ekg">
  <div class="panel panel-info">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#res_ekg_parent" href="#res_ekg_{numb}">{res_title}</a>
      </h4>
    </div>
    <div id="res_ekg_{numb}" class="panel-collapse collapse">
      <div class="panel-body">
        <label>{name}</label>
        <table style="width:100%" class="table table-bordered table-hover">
          <tbody>
            {tbody}
          </tbody>
        </table>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Kesimpulan</label>
              <textarea class="fittextarea form-control no_radius" disabled="disabled">{h17}</textarea>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Anjuran</label>
              <textarea class="fittextarea form-control no_radius" disabled="disabled">{h18}</textarea>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</script>
<script type="text/template" id="res_poli">
  <div class="panel panel-info">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#res_poli_parent" href="#res_poli_{numb}">{res_title}</a>
      </h4>
    </div>
    <div id="res_poli_{numb}" class="panel-collapse collapse">
      <div class="panel-body">
        <p>Dokter: <b>{doctor}</b></p>
        <p>Tanggal: <b>{date}</b></p>
        <div class="form-group">
          <label><u>{produk}</u></label>
          <textarea style="height:450px;" class="fittextarea form-control no_radius" readonly>{result}</textarea>
        </div>
      </div>
    </div>
  </div>
</script>
<script type="text/template" id="ok">
  <div class="panel panel-success">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#ok_parent" href="#ok_{numb}">{res_title}</a>
      </h4>
    </div>
    <div id="ok_{numb}" class="panel-collapse collapse">
      <div class="panel-body">
        <label>Detail Pasien</label>
        <div class="row">
          <div class="col-md-6">
            <table style="width:100%" border="0">
              <tbody>
                <tr>
                  <td>Nama Pasien</td>
                  <td>:</td>
                  <td>{name}</td>
                </tr>
                <tr>
                  <td>Umur</td>
                  <td>:</td>
                  <td>{age}</td>
                </tr>
                <tr>
                  <td>Jenis Kelamin</td>
                  <td>:</td>
                  <td>{gender}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="col-md-6">
            <table style="width:100%" border="0">
              <tbody>
                <tr>
                  <td>No RM</td>
                  <td>:</td>
                  <td>{nrm}</td>
                </tr>
                <tr>
                  <td>Ruang</td>
                  <td>:</td>
                  <td>{room}</td>
                </tr>
                <tr>
                  <td>Kamar Bedah</td>
                  <td>:</td>
                  <td>{room_ok}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <hr>
        <label>Detail Dokter & Perawat</label>
        <div class="table-responsive">
          <table style="width:100%" class="table table-bordered table-hover">
            <thead>
              <tr class="danger">
                <th class="text-center">Nama Dokter</th>
                <th class="text-center">Tugas</th>
              </tr>
            </thead>
            <tbody>{tbodydoctor}</tbody>
          </table>
        </div>
        <hr>
        <label>Detail Operasi</label>
        <div class="row">
          <div class="col-md-6">
            <table style="width:100%" border="0">
              <tbody>
                <tr>
                  <td>Tanggal Operasi</td>
                  <td>:</td>
                  <td>{date}</td>
                </tr>
                <tr>
                  <td>Operasi Mulai</td>
                  <td>:</td>
                  <td>{start}</td>
                </tr>
                <tr>
                  <td>Durasi</td>
                  <td>:</td>
                  <td>{dur}</td>
                </tr>
                <tr>
                  <td>Diagnosa PRE Operasi</td>
                  <td>:</td>
                  <td>{pre}</td>
                </tr>
                <tr>
                  <td>Diagnosa POST Operasi</td>
                  <td>:</td>
                  <td>{post}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="col-md-6">
            <table style="width:100%" border="0">
              <tbody>
                <tr>
                  <td>Pembiusan</td>
                  <td>:</td>
                  <td>{anest}</td>
                </tr>
                <tr>
                  <td>Speciment</td>
                  <td>:</td>
                  <td>{speciment}</td>
                </tr>
                <tr>
                  <td>Jumlah Pendarahan</td>
                  <td>:</td>
                  <td>{bleeding}</td>
                </tr>
                <tr>
                  <td>Komplikasi</td>
                  <td>:</td>
                  <td>{complication}</td>
                </tr>
                <tr>
                  <td>Penyulit</td>
                  <td>:</td>
                  <td>{harder}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <hr>
        <label>Detail Tindakan</label>
        <div class="table-responsive">
          <table style="width:100%" class="table table-bordered table-hover">
            <thead>
              <tr class="info">
                <th class="text-center">Nama Tindakan</th>
                <th class="text-center">Tanggal Tindakan</th>
              </tr>
            </thead>
            <tbody>{tbodyact}</tbody>
          </table>
        </div>
        <hr>
        <label>Laporan Tindakan</label>
        <textarea class="form-control no_radius" style="height:450px;" readonly>{report}</textarea>
      </div>
    </div>
  </div>
</script>
<script type="text/template" id="vk">
  <div class="panel panel-success">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#vk_parent" href="#vk_{numb}">{res_title}</a>
      </h4>
    </div>
    <div id="vk_{numb}" class="panel-collapse collapse">
      <div class="panel-body">
        <label>Detail Pasien</label>
        <div class="row">
          <div class="col-md-6">
            <table style="width:100%" border="0">
              <tbody>
                <tr>
                  <td>Nama Pasien</td>
                  <td>:</td>
                  <td>{name}</td>
                </tr>
                <tr>
                  <td>Umur</td>
                  <td>:</td>
                  <td>{age}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="col-md-6">
            <table style="width:100%" border="0">
              <tbody>
                <tr>
                  <td>No RM</td>
                  <td>:</td>
                  <td>{nrm}</td>
                </tr>
                <tr>
                  <td>Jenis Kelamin</td>
                  <td>:</td>
                  <td>{gender}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <hr>
        <label>Detail Dokter & Perawat</label>
        <div class="table-responsive">
          <table style="width:100%" class="table table-bordered table-hover">
            <thead>
              <tr class="danger">
                <th class="text-center">Nama Dokter</th>
                <th class="text-center">Tugas</th>
              </tr>
            </thead>
            <tbody>{tbodydoctor}</tbody>
          </table>
        </div>
        <hr>
        <label>Detail Operasi</label>
        <div class="row">
          <div class="col-md-6">
            <table style="width:100%" border="0">
              <tbody>
                <tr>
                  <td>Tanggal Operasi</td>
                  <td>:</td>
                  <td>{date}</td>
                </tr>
                <tr>
                  <td>Operasi Mulai</td>
                  <td>:</td>
                  <td>{start}</td>
                </tr>
                <tr>
                  <td>Diagnosa PRE Operasi</td>
                  <td>:</td>
                  <td>{pre}</td>
                </tr>
                <tr>
                  <td>Diagnosa POST Operasi</td>
                  <td>:</td>
                  <td>{post}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="col-md-6">
            <table style="width:100%" border="0">
              <tbody>
                <tr>
                  <td>Pembiusan</td>
                  <td>:</td>
                  <td>{anest}</td>
                </tr>
                <tr>
                  <td>Speciment</td>
                  <td>:</td>
                  <td>{speciment}</td>
                </tr>
                <tr>
                  <td>Jumlah Pendarahan</td>
                  <td>:</td>
                  <td>{bleeding}</td>
                </tr>
                <tr>
                  <td>Komplikasi</td>
                  <td>:</td>
                  <td>{complication}</td>
                </tr>
                <tr>
                  <td>Penyulit</td>
                  <td>:</td>
                  <td>{harder}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <hr>
        <label>Detail Tindakan</label>
        <div class="table-responsive">
          <table style="width:100%" class="table table-bordered table-hover">
            <thead>
              <tr class="info">
                <th class="text-center">Nama Tindakan</th>
                <th class="text-center">Tanggal Tindakan</th>
              </tr>
            </thead>
            <tbody>{tbodyact}</tbody>
          </table>
        </div>
        <hr>
        <label>Laporan Tindakan</label>
        <textarea class="form-control no_radius" style="height:450px;" readonly>{report}</textarea>
      </div>
    </div>
  </div>
</script>