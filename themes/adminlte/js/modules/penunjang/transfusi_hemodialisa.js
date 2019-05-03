_RUN(function($) {
  window.mdl_patient_arr = {
    mdl_patient_nrm: $('#mdl_patient_nrm').val(),
    mdl_patient_poli: $('#mdl_patient_poli').val(),
    mdl_patient_date: $('#mdl_patient_date').val(),
  };
  var b = $('#patient_today_dt').DataTable({
    order: [
      [2, 'asc']
    ],
    processing: true,
    serverSide: true,
    searching: false,
    ajax: {
      url: window.base_url + 'penunjang/hemodialisa_ajax/patient_today',
      type: 'POST',
      data: function(a) {
        a.custom = window.mdl_patient_arr
      },
    },
    language: {
      url: base_url + 'themes/default/datatables.net/lang.json'
    },
  });
  $('#mdl_patient_nrm').keyup(function() {
    window.mdl_patient_arr.mdl_patient_nrm = $('#mdl_patient_nrm').val();
    b.ajax.reload()
  });
  $('#mdl_patient_poli').change(function() {
    window.mdl_patient_arr.mdl_patient_poli = $('#mdl_patient_poli').val();
    b.ajax.reload()
  });
  $('#mdl_patient_date').change(function() {
    window.mdl_patient_arr.mdl_patient_date = $('#mdl_patient_date').val();
    b.ajax.reload()
  });
  $('#add_patient').click(function(e) {
    d();
    $('#mdl_add_input_date').val($('#daily_dt_date').val());
    $('#mdl_add_title').html('Tambah Pasien');
    $('#modal-add').modal('show')
  });
  $('#mdl_add_search_patient').click(function(e) {
    $('#modal-patient').modal('show')
  });
  $('body').on('click', '.patient_today_btn_check', function() {
    $('#mdl_add_nrm').val($(this).data('nrm'));
    $('#mdl_add_patient').val($(this).data('patient'));
    $('#modal-patient').modal('hide')
  });
  window.daily_dt_arr = {
    daily_dt_date: $('#daily_dt_date').val(),
  };
  var d = function() {
    $('#mdl_add_nrm').val('');
    $('#mdl_add_patient').val('');
    $('#mdl_add_caption').val('');
    $('#mdl_add_id').val('');
    $('#mdl_add_input_date').val('');
    $('.mdl_add_result').prop('checked', false)
  };
  var c = $('#daily_dt').DataTable({
    order: [
      [1, 'asc']
    ],
    processing: true,
    serverSide: true,
    searching: false,
    ajax: {
      url: window.base_url + 'penunjang/hemodialisa_ajax/transfusi',
      type: 'POST',
      data: function(a) {
        a.custom = window.daily_dt_arr
      },
    },
    language: {
      url: base_url + 'themes/default/datatables.net/lang.json'
    },
  });
  $('#daily_dt_date').change(function() {
    window.daily_dt_arr.daily_dt_date = $('#daily_dt_date').val();
    c.ajax.reload()
  });
  $('#mdl_add_form').submit(function(e) {
    e.preventDefault();
    $.ajax({
      type: "POST",
      url: window.base_url + 'penunjang/hemodialisa_ajax/transfusi_form',
      dataType: 'json',
      data: $(this).serialize(),
      success: function(a) {
        if (a.success == 1) {
          c.ajax.reload();
          $('#modal-add').modal('hide')
        } else {
          alert(a.msg);
          if (a.success == 2) document.location.href = typeof(a.url) != 'undefined' ? a.url : window.base_url + 'user/logout'
        }
      }
    })
  });
  $('body').on('click', '.daily_btn_delete', function() {
    if (confirm('Anda yakin akan menghapus data?')) {
      $.ajax({
        type: "POST",
        url: window.base_url + 'penunjang/hemodialisa_ajax/transfusi_delete',
        dataType: 'json',
        data: {
          id: $(this).data('id')
        },
        success: function(a) {
          if (a.success == 1) {
            c.ajax.reload();
            $('#modal-add').modal('hide')
          } else {
            alert(a.msg);
            if (a.success == 2) document.location.href = typeof(a.url) != 'undefined' ? a.url : window.base_url + 'user/logout'
          }
        }
      })
    }
  });
  $('body').on('click', '.daily_btn_edit', function() {
    d();
    $('#mdl_add_nrm').val($(this).data('nrm'));
    $('#mdl_add_patient').val($(this).data('patient'));
    $('#mdl_add_blood').val($(this).data('blood_group'));
    $('#mdl_add_total').val($(this).data('type_total'));
    $('#mdl_add_money').val($(this).data('money'));
    $('#mdl_add_caption').val($(this).data('caption'));
    $('#mdl_add_id').val($(this).data('id'));
    $('#mdl_add_input_date').val($('#daily_dt_date').val());
    $('#mdl_add_title').html('Edit Data');
    $('#modal-add').modal('show')
  })
});