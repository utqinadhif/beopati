<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_verif extends CI_Model
{    
  function __construct()
  {
      parent::__construct();
  }

  public function get_lists($search, $limit, $sess)
  {
    $type = $sess['type'];
    $priv = $sess['privilege'];

    if($priv == 3)
    {
      // BPJS
    	$this->db->select('FMNOTRANSAKSI AS reg,FMPASIEN_ID AS patient_rm,FMNAMA_PESERTA AS patient_name,FMNOSEP AS patient_sep,FMNO_KARTU AS patient_card,FMJENISRAWAT AS patient_type,FMTGL_SEP AS date');
      
      $this->db->where('FMNOSEP!=', '');

      $this->db->where('FMNOSEP', $search);
      $this->db->or_where('FMPASIEN_ID', $search);
      $this->db->or_where('FMNO_KARTU', $search);
      $this->db->or_like('FMNAMA_PESERTA', $search);

      switch ($type)
      {
        case 1:
          $this->db->where('FMJENISRAWAT', 1);
          $this->db->join('(SELECT FTNO_TRANSAKSI FROM TRANSAKSIPASIENINAP,FTTGL_TRANSAKSI) AS transaksi', 'FTNO_TRANSAKSI=FMNOTRANSAKSI', 'right');
          break;

        case 2:
          $this->db->where('FMJENISRAWAT', 2);
          $this->db->join('(SELECT FTNO_TRANSAKSI FROM TRANSAKSIPASIEN,FTTGL_TRANSAKSI) AS transaksi', 'FTNO_TRANSAKSI=FMNOTRANSAKSI', 'right');
          break;
        
        default:
          $this->db->join('(SELECT FTNO_TRANSAKSI,FTTGL_TRANSAKSI FROM TRANSAKSIPASIEN UNION SELECT FTNO_TRANSAKSI,FTTGL_TRANSAKSI FROM TRANSAKSIPASIENINAP) AS transaksi', 'FTNO_TRANSAKSI=FMNOTRANSAKSI', 'right');
          break;
      }

    	$this->db->order_by('FMTGL_SEP', 'desc');
      $this->db->limit($limit);

      $arr = $this->db->get('BPJS_SEP')->result_array();
    }else{
      // LOCAL
      $this->db->select('reg,patient_rm,patient_name,FMNOSEP AS patient_sep,FMNO_KARTU AS patient_card,patient_type,date');

      $this->db->where('FMNOSEP', $search);
      $this->db->or_where('patient_rm', $search);
      $this->db->or_where('FMNO_KARTU', $search);
      $this->db->or_like('patient_name', $search);

      switch ($type)
      {
        case 1:
          $this->db->where('FMJENISRAWAT', 1);
          $this->db->join('(SELECT FTNO_TRANSAKSI AS reg,FTKD_PASIEN AS patient_rm,NAMAPASIEN AS patient_name,1 AS patient_type,FTTGL_TRANSAKSI AS date FROM TRANSAKSIPASIENINAP LEFT JOIN PASIEN ON FTKD_PASIEN=KD_PASIEN) AS transaksi', 'reg=FMNOTRANSAKSI', 'right');
          break;

        // not recommend
        case 2:
          $this->db->where('FMJENISRAWAT', 2);
          $this->db->join("(SELECT KPNO_TRANSAKSI AS reg, KPKD_PASIEN AS patient_rm,NAMAPASIEN AS patient_name, 2 AS patient_type, KPTGL_PERIKSA AS date FROM KUNJUNGANPASIEN LEFT JOIN PASIEN ON KPKD_PASIEN=KD_PASIEN WHERE KPJENISTRANSAKSI <> 'PK018' AND KPJENISTRANSAKSI <> 'PK019') AS transaksi", 'reg=FMNOTRANSAKSI', 'right');
          break;

        // not recommend
        default:
          $this->db->join("(SELECT KPNO_TRANSAKSI AS reg, KPKD_PASIEN AS patient_rm,NAMAPASIEN AS patient_name, 2 AS patient_type, KPTGL_PERIKSA AS date FROM KUNJUNGANPASIEN LEFT JOIN PASIEN ON KPKD_PASIEN=KD_PASIEN WHERE KPJENISTRANSAKSI <> 'PK018' AND KPJENISTRANSAKSI <> 'PK019'
            UNION
            SELECT FTNO_TRANSAKSI AS reg,FTKD_PASIEN AS patient_rm,NAMAPASIEN AS patient_name,1 AS patient_type,FTTGL_TRANSAKSI AS date FROM TRANSAKSIPASIENINAP LEFT JOIN PASIEN ON FTKD_PASIEN=KD_PASIEN) AS transaksi", 'reg=FMNOTRANSAKSI', 'right');
          break;
      }

      $this->db->order_by('date', 'desc');
      $this->db->limit($limit);

      $arr = $this->db->get('BPJS_SEP')->result_array();
    }

  	foreach ($arr as &$ls)
    {
      $ls['patient_type_name'] = $ls['patient_type'] == 1 ? 'Rawat Inap' : 'Rawat Jalan';
      $ls['date']              = date_ind($ls['date']);
      $ls['patient_sep']       = !empty($ls['patient_sep']) ? $ls['patient_sep'] : '-';
      $ls['patient_card']      = !empty($ls['patient_card']) ? $ls['patient_card'] : '-';

      $ls['sep']               = !empty($ls['patient_sep']) ? $ls['patient_sep'] : '';
    }      

    $detail = array();
    if(count($arr) == 1)
    {
      $detail = $this->get_detail($arr[0]['reg'], $arr[0]['sep']);
    }

  	return array(
      'list'   => $arr,
      'detail' => $detail
    );
  }

  public function get_detail($reg, $sep)
  {
    // list
    $this->db->select('FDTKD_PRODUK,FDTKDPRODUKN,FDTDEBET,FDTKREDIT,FTGKODE,FTGNAMA');
    $this->db->join('PRODUK', 'FDTKD_PRODUK=FMPPRODUK_ID', 'left');
    $this->db->join('PRODUK_GOLONGAN', 'FTGKODE=FMPUNITKLASPRODUK', 'left');
    $this->db->order_by('FTGKODE', 'asc');
    $this->db->where('FDTNO_TRANSAKSI', $reg);
    
    $type = $this->_get_type($reg);
      
		if($type == 1)
		{
			$r = $this->db->get('TRANSAKSIPASIENINAPD')->result_array();
		}else{
			$r = $this->db->get('TRANSAKSIPASIEND')->result_array();
		}

		$arr = array();
		$tot  = 0;
		foreach ($r as $transaction)
		{
			if(!in_array($transaction['FDTKD_PRODUK'], array(1, 14, 7)))
			{
				$ntot = !empty($transaction['FDTDEBET']) ? $transaction['FDTDEBET'] : ($transaction['FDTKREDIT'] * -1);
				$tot  += $ntot;

				if(!empty($arr[$transaction['FTGKODE']]))
				{
					$arr[$transaction['FTGKODE']]['price'] += $ntot;
				}else{
					$arr[$transaction['FTGKODE']] = array(
						'name'  => $transaction['FTGNAMA'],
						'price' => $ntot
					);
				}
			}
		}

    // update inacbg
    $this->_update_inacbg($sep, $reg);

		// rinci
    $rinc = $this->_get_rinci($reg, $type);

    // home back jalan
    $hback = array('dirawat','dirujuk','pulang','meninggal');

		// patient
		$patient = array();
		if($type == 1)
		{
			$this->db->select("FTNO_TRANSAKSI AS reg,FMNO_KARTU AS npatient,FTKD_PASIEN AS nrm,NAMAPASIEN AS patient_name,ALAMAT AS address,'0' AS years,'0' AS days,TGL_LAHIR AS birthday,JENIS_KELAMIN AS gender,'Regular' AS nursing,FTNO_KWITANSI AS kwitansi,NAME AS gpatient,PRWITGL_MASUK AS pin,PRWITGL_KELUAR AS pout,'1' AS nursing_type,FMCKRSKODE AS home_back_id,FMCKRSKETERANGAN AS home_back,PRWIKEADAANKELUAR AS back,'0' AS los,PPNBERAT_LAHIR AS weigth,FMKNAMA_KAMAR AS room,FMKKAMARN AS class,add_pay,kelas_rawat");
			$this->db->join('PASIEN', 'FTKD_PASIEN=KD_PASIEN', 'left');
			$this->db->join('PASIENRAWATINAP', 'FTNO_TRANSAKSI=PRWINO_TRANSAKSI', 'left');
			$this->db->join('KAMAR', 'PRWIKD_KAMAR=FMKKAMAR_ID', 'left');
			$this->db->join('KAMAR_KELAS', 'PRWIKD_KELAS=FMKKODEKLAS', 'left');
			$this->db->join('CUSTOMER', 'PRWIKD_CUSTOMER=CUSID', 'left');
      $this->db->join('BPJS_SEP', 'FMNOTRANSAKSI=FTNO_TRANSAKSI', 'left');
      $this->db->join('PASIENPERINATAL', 'PPNKD_PASIEN=FTKD_PASIEN', 'left');
      $this->db->join('MR_CARA_KELUAR_RS', 'PRWICARAKELUAR=FMCKRSKODE', 'left');
      $this->db->where('FTNO_TRANSAKSI', $reg);
      $this->db->order_by('PRWINO_URUT', 'desc');
      $this->db->limit(1);
			$patient = $this->db->get('TRANSAKSIPASIENINAP')->row_array();
		}else{
			$this->db->select("FTNO_TRANSAKSI AS reg,FMNO_KARTU AS npatient,FTKD_PASIEN AS nrm,NAMAPASIEN AS patient_name,ALAMAT AS address,'0' AS years,'0' AS days,TGL_LAHIR AS birthday,JENIS_KELAMIN AS gender,'Regular' AS nursing,FTNO_KWITANSI AS kwitansi,NAME AS gpatient,KPTGL_PERIKSA AS pin,KPTGL_PERIKSA AS pout,'2' AS nursing_type,KPPERAWATAN AS home_back_id,KPPERAWATAN AS home_back,'-' AS back,'0' AS los,PPNBERAT_LAHIR AS weigth,'-' AS room, '-' AS class,add_pay,kelas_rawat");
      $this->db->join('KUNJUNGANPASIEN', 'KPNO_TRANSAKSI=FTNO_TRANSAKSI', 'left');
      $this->db->join('PASIEN', 'FTKD_PASIEN=KD_PASIEN', 'left');
      $this->db->join('CUSTOMER', 'KD_CUSTOMER=CUSID', 'left');
      $this->db->join('BPJS_SEP', 'FMNOTRANSAKSI=FTNO_TRANSAKSI', 'left');
			$this->db->join('PASIENPERINATAL', 'PPNKD_PASIEN=FTKD_PASIEN', 'left');
			$this->db->where('FTNO_TRANSAKSI', $reg);
			$patient = $this->db->get('TRANSAKSIPASIEN')->row_array();
    }

    if(!empty($patient))
    {
      if(!empty($patient['home_back']))
      {
        // not recomend
        if($type == 1)
        {
          if($patient['home_back_id'] == 1 && in_array($patient['back'], array(3, 4)))
          {
            $patient['home_back'] = 'Meninggal';
          }else{
            $patient['home_back'] = ucfirst(strtolower($patient['home_back']));
          }
        }else
        {
          $patient['home_back']   = ucfirst($hback[$patient['home_back_id']]);
          $patient['kelas_rawat'] = '-';
        }
      }else{
        $patient['home_back']   = '-';  
        $patient['kelas_rawat'] = $type == 1 ? $patient['kelas_rawat'] : '-';
      }


      $patient['years']        = $this->_getdiff(date('Y-m-d'), $patient['birthday']);
      $patient['days']         = $this->_getdiff(date('Y-m-d'), $patient['birthday'], 'days');
      $patient['los']          = $this->_getdiff($patient['pout'], $patient['pin'], 'days') + 1;
      
      $patient['pin']          = date_ind($patient['pin']);
      $patient['pout']         = date_ind($patient['pout']);
      $patient['birthday']     = date_ind($patient['birthday']);
      $patient['gender']       = $patient['gender'] == 1 ? 'Laki-laki' : 'Perempuan';
      $patient['nursing_type'] = $patient['nursing_type'] == 1 ? 'Rawat Inap' : 'Rawat Jalan';
      $patient['kwitansi']     = !empty($patient['kwitansi']) ? $patient['kwitansi'] : '-';
      $patient['weigth']       = !empty($patient['weigth']) ? $patient['weigth'] : '-';
      $patient['npatient']     = !empty($patient['npatient']) ? $patient['npatient'] : '-';
		}

		return array(
      'list'      => $arr,
      'tot'       => $tot,
      'incbg_kd'  => @$rinc['FTKODEINACBG'],
      'incbg_tot' => @intval($rinc['FTTARIPINACBG']),
      'grand'     => @intval($rinc['FTTARIPINACBG'] - $tot),
      'patient'   => $patient,
      'type'      => $type
		);
  }

  private function _update_inacbg($sep, $reg)
  {
    $arr = array();

    $type = $this->_get_type($reg);

    $table = $type == 1 ? 'TRANSAKSIPASIENINAP' : 'TRANSAKSIPASIEN';
      
    $this->db->select('kelas_rawat');
    $this->db->where('FTNO_TRANSAKSI', $reg);
    $r = $this->db->get($table)->row_array();

    if(!empty($sep) && empty($r['kelas_rawat']))
    {
      $url = 'http://2.0.0.44/inacbg_bridge/api/index.php?request={"metadata":{"method":"get_claim_data"},"data":{"nomor_sep":"' . $sep . '"}}';

      $ch  = curl_init();
      $opt = array(
        CURLOPT_URL            => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false
      );
      curl_setopt_array($ch, $opt);
      $result = curl_exec($ch);
      curl_close($ch);
      $arr = json_decode($result, true);

      $kelas_rawat = @intval($arr['response']['data']['kelas_rawat']);
      $add_pay     = @intval($arr['response']['data']['add_payment_amt']);

      // update
      $this->db->where('FTNO_TRANSAKSI', $reg);
      $this->db->update($table, array(
        'kelas_rawat' => $kelas_rawat,
        'add_pay'     => $add_pay,
      ));
    }
  }

  private function _get_rinci($reg, $type)
  {
    // rinci
    $this->db->select('FTTARIPINACBG, FTKODEINACBG, add_pay, kelas_rawat');
    $this->db->where('FTNO_TRANSAKSI', $reg);

    if($type == 1)
    {
      $rinc = $this->db->get('TRANSAKSIPASIENINAP')->row_array();
    }else{
      $rinc = $this->db->get('TRANSAKSIPASIEN')->row_array();
    }

    return $rinc;
  }

  private function _get_type($reg)
  {
    $q = $this->db->query("
      SELECT 1 AS type FROM TRANSAKSIPASIENINAP WHERE FTNO_TRANSAKSI='$reg'
      UNION
      SELECT 2 AS type FROM TRANSAKSIPASIEN WHERE FTNO_TRANSAKSI='$reg'
      ");

    $r = $q->row_array();

    return $r['type'];
  }

  private function _getdiff($dt1, $dt2, $type = 'y')
  {
    $d1 = new DateTime($dt1);
    $d2 = new DateTime($dt2);

    $diff = $d2->diff($d1);

    return $diff->$type;
  }

  private function _get_parent($reff)
  {
    $this->db->select('KPNO_TRANSAKSI AS trans_id,KPTGL_PERIKSA AS date,FMDDOKTERN AS doctor_name');
    $this->db->where_in('KPNO_TRANSAKSI', $reff);
    $this->db->join('DOKTER', 'KPKD_DOKTER=FMDDOKTER_ID', 'left');
    $r = $this->db->get('KUNJUNGANPASIEN')->result_array();

    $this->db->select('PRWINO_TRANSAKSI AS trans_id, PRWITGL_MASUK AS date, FMDDOKTERN AS doctor_name');
    $this->db->where_in('PRWINO_TRANSAKSI', $reff);
    $this->db->join('DOKTER', 'PRWIKD_DOKTER=FMDDOKTER_ID', 'left');
    $r2 = $this->db->get('PASIENRAWATINAP')->result_array();

    $r = array_merge_recursive($r, $r2);

    $arr = array();
    foreach ($r as $temp) 
    {
      $temp['date']           = date_ind($temp['date']);
      $arr[$temp['trans_id']] = $temp;
    }

    return $arr;
  }

  public function get_reff($trans_id)
  {
    $ret = array();
    if(!empty($trans_id))
    {
      $this->db->select('FDTNO_FAKTUR');

      if(is_array($trans_id))
      {
        $this->db->where_in('FDTNO_TRANSAKSI', $trans_id);
        $rawat = 2;
      }else{
        $this->db->where('FDTNO_TRANSAKSI', $trans_id);
        $rawat = $this->_get_type($trans_id);
      }

      $this->db->where('FDTNO_FAKTUR!=', '');

      $r = $rawat == 1 ? 'TRANSAKSIPASIENINAPD' : 'TRANSAKSIPASIEND';

      $arr = array();
      foreach ($this->db->get($r)->result_array() as $reff)
      {
        $arr[] = $reff['FDTNO_FAKTUR'];
      }

      $ret = array_merge($arr, $this->get_reff($arr));
    }
    return $ret;    
  }

  public function get_laborat($reff)
  {
  	$par = $this->_get_parent($reff);

  	// detail
  	$this->db->select('MLHNO_TRANSAKSI,FMKKLAS_ID,FMKKLASN,MTLLABN,MLHHASIL,MTLSATUAN,MTLNORMAL_LAKI2,MTLNORMAL_WANITA,MLHJENIS_KELAMIN');
  	$this->db->where_in('MLHNO_TRANSAKSI', $reff);
  	$this->db->join('KLAS_PRODUK_RAD_LAB', 'MLHKKD_PRODUK=FMKKLAS_ID', 'left');
  	$this->db->join('LAB_TEST', 'MTLKD_LAB=MLHKD_LAB', 'left');
    $this->db->order_by('MLHTGL_MASUK', 'desc');

  	$r = $this->db->get('LAB_HASIL')->result_array();

  	$arr = array();
  	foreach ($r as $temp)
  	{
			$arr[$temp['MLHNO_TRANSAKSI']]['sum']                         = $par[$temp['MLHNO_TRANSAKSI']];
			$arr[$temp['MLHNO_TRANSAKSI']]['detail'][$temp['FMKKLASN']][] = array(
				'caption' => $temp['MTLLABN'],
				'result'  => $temp['MLHHASIL'],
				'unit'    => $temp['MTLSATUAN'],
				'normal'  => $temp['MLHJENIS_KELAMIN'] == 2 ?  $temp['MTLNORMAL_WANITA'] : $temp['MTLNORMAL_LAKI2']
			);
  	}
  	return $arr;
  }

  public function get_ekg($id, $reff)
  {
    array_push($reff, $id);
    $par = $this->_get_parent($reff);
    // detail
    $this->db->select('MRHNO_TRANSAKSI,FMPPRODUKN,MRH1,MRH2,MRH3,MRH4,MRH5,MRH6,MRH7,MRH8,MRH9,MRH10,MRH11,MRH12,MRH13,MRH14,MRH15,MRH16,MRH17,MRH18');
    $this->db->where_in('MRHNO_TRANSAKSI', $reff);
    $this->db->join('PRODUK', 'MRHKD_PRODUK=FMPPRODUK_ID', 'left');
    $this->db->order_by('MRHTGL_MASUK', 'desc');

    $r = $this->db->get('HASIL_EKG')->result_array();

    $arr = array();
    foreach ($r as $temp)
    {
      $arr[$temp['MRHNO_TRANSAKSI']]['sum']    = $par[$temp['MRHNO_TRANSAKSI']];
      $arr[$temp['MRHNO_TRANSAKSI']]['detail'] = array(
        'name' => 'Bacaan ECG/ EKG',
        'h1'   => $temp['MRH1'],
        'h2'   => $temp['MRH2'],
        'h3'   => $temp['MRH3'],
        'h4'   => $temp['MRH4'],
        'h5'   => $temp['MRH5'],
        'h6'   => $temp['MRH6'],
        'h7'   => $temp['MRH7'],
        'h8'   => $temp['MRH8'],
        'h9'   => $temp['MRH9'],
        'h10'  => $temp['MRH10'],
        'h11'  => $temp['MRH11'],
        'h12'  => $temp['MRH12'],
        'h13'  => $temp['MRH13'],
        'h14'  => $temp['MRH14'],
        'h15'  => $temp['MRH15'],
        'h16'  => $temp['MRH16'],
        'h17'  => $temp['MRH17'],
        'h18'  => $temp['MRH18']
      );
    }

    return $arr;
  }

  public function get_poli($id, $reff)
  {
    array_push($reff, $id);
    $par = $this->_get_parent($reff);
    // detail
    $this->db->select('MRHNO_TRANSAKSI,FMPPRODUKN,MRHHASIL');
    $this->db->where_in('MRHNO_TRANSAKSI', $reff);
    $this->db->join('PRODUK', 'MRHKD_PRODUK=FMPPRODUK_ID', 'left');
    $this->db->order_by('MRHTGL_MASUK', 'desc');

    $r = $this->db->get('HASIL_TINDAKAN')->result_array();

    $arr = array();
    foreach ($r as $temp)
    {
      $arr[$temp['MRHNO_TRANSAKSI']]['sum']    = $par[$temp['MRHNO_TRANSAKSI']];
      $arr[$temp['MRHNO_TRANSAKSI']]['detail'] = array(
        'name'   => $temp['MRHNO_TRANSAKSI'] == $id ? 'Tindakan Poliklinik' : $temp['FMPPRODUKN'],
        'result' => $temp['MRHHASIL']
      );
    }

    return $arr;
  }

  public function get_radiologi($id, $reff)
  {
    array_push($reff, $id);
  	$par = $this->_get_parent($reff);
  	// detail
  	$this->db->select('MRHNO_TRANSAKSI,FMPPRODUKN,MRHHASIL');
  	$this->db->where_in('MRHNO_TRANSAKSI', $reff);
    $this->db->join('PRODUK', 'MRHKD_PRODUK=FMPPRODUK_ID', 'left');
  	$this->db->order_by('MRHTGL_MASUK', 'desc');

  	$r = $this->db->get('RAD_HASIL')->result_array();

  	$arr = array();
  	foreach ($r as $temp)
  	{
			$arr[$temp['MRHNO_TRANSAKSI']]['sum']    = $par[$temp['MRHNO_TRANSAKSI']];
			$arr[$temp['MRHNO_TRANSAKSI']]['detail'] = array(
				'name'   => $temp['MRHNO_TRANSAKSI'] == $id ? 'Bacaan USG' : $temp['FMPPRODUKN'],
				'result' => $temp['MRHHASIL']
			);
  	}

  	return $arr;
  }

  public function get_farmasi($reff)
  {
    // grand detail
  	$this->db->select('FHFJBUKTI_ID,FHFJRACIK,FHFJBULAT,FHFJJUMLAH,FHFJTOTAL,FHFJDOKTERN,FHFJDATE');
    $this->db->where_in('FHFJBUKTI_ID', $reff);
  	$this->db->order_by('FHFJDATE', 'desc');
  	$r = $this->db->get('FJINKOTA')->result_array();

  	$arr = array();
  	foreach ($r as $temp) 
  	{
			$temp['FHFJDATE']                  = date_ind($temp['FHFJDATE']);
			$arr[$temp['FHFJBUKTI_ID']]['sum'] = array(
				'racik'       => @intval($temp['FHFJRACIK']),
				'bulat'       => @intval($temp['FHFJBULAT']),
				'total'       => @intval($temp['FHFJJUMLAH']),
				'grand'       => @intval($temp['FHFJTOTAL']),
				'doctor_name' => $temp['FHFJDOKTERN'],
				'date'        => $temp['FHFJDATE']
			);
  	}

  	// detail
  	$this->db->select('FDFJBUKTI_ID,FDFJBRGN,FDFJQTY,FDFJSATUAN,FDFJTOTAL');
  	$this->db->where_in('FDFJBUKTI_ID', $reff);

  	$r = $this->db->get('FJINKOTAD')->result_array();		

  	foreach ($r as $temp) 
		{
			$arr[$temp['FDFJBUKTI_ID']]['detail'][] = array(
				'name'  => $temp['FDFJBRGN'],
				'qty'   => $temp['FDFJQTY'],
				'unit'  => $temp['FDFJSATUAN'],
				'total' => $temp['FDFJTOTAL']
			);
		}

    // grand retur
    $this->db->select('FHRJBUKTI_ID,FHRJRACIK,FHRJBULAT,FHRJJUMLAH,FHRJTOTAL,FHRJDOKTERN,FHRJDATE');
    $this->db->where_in('FHRJBUKTI_ID', $reff);
    $this->db->order_by('FHRJDATE', 'desc');
    $r = $this->db->get('RETURJIN')->result_array();

    $arr2 = array();
    foreach ($r as $temp) 
    {
      $temp['FHRJDATE']                  = date_ind($temp['FHRJDATE']);
      $arr2[$temp['FHRJBUKTI_ID']]['sum'] = array(
        'racik'       => @intval($temp['FHRJRACIK']),
        'bulat'       => @intval($temp['FHRJBULAT']),
        'total'       => @intval($temp['FHRJJUMLAH']),
        'grand'       => @intval($temp['FHRJTOTAL']),
        'doctor_name' => $temp['FHRJDOKTERN'],
        'date'        => $temp['FHRJDATE']
      );
    }

    // retur
    $this->db->select('FDRJBUKTI_ID,FDRJBRGN,FDRJQTYT,FDRJSATUAN,FDRJTOTAL');
    $this->db->where_in('FDRJBUKTI_ID', $reff);

    $r = $this->db->get('RETURJIND')->result_array();

    foreach ($r as $temp) 
    {
      $arr2[$temp['FDRJBUKTI_ID']]['detail'][] = array(
        'name'  => $temp['FDRJBRGN'],
        'qty'   => $temp['FDRJQTYT'],
        'unit'  => $temp['FDRJSATUAN'],
        'total' => $temp['FDRJTOTAL']
      );
    }

  	return array(
      'list'  => $arr,
      'retur' => $arr2
    );
  }

  public function get_diagnosa($reg, $inacbgcode)
  {
    $type = $this->_get_type($reg); 

    $this->db->select('MRPKD_PENYAKIT AS id_disease, PENYAKIT AS disease');
    $this->db->join('PENYAKIT', 'KD_PENYAKIT=MRPKD_PENYAKIT');
    $this->db->where('MRPNO_TRANSAKSI', $reg);

    if($type == 1) $this->db->order_by('MRPSTAT_DIAG', 'desc');
    else $this->db->order_by('MRPURUT_MASUK', 'asc');
    
    $q1 = $this->db->get('MR_PENYAKIT')->result_array();

    $this->db->select('MRTKD_TINDAKAN AS id_action,FMI9KETERANGAN AS action');
    $this->db->join('MR_ICD9', 'MRTKD_TINDAKAN=FMI9KODE');
    $this->db->where('MRTNOTRANSAKSI', $reg);
    $q2 = $this->db->get('MR_TINDAKAN')->result_array();

    $this->db->select('FMINAKODE AS inacode, FMINAKETERANGAN AS inacaption');
    $this->db->where('FMINAKODE', $inacbgcode);
    $q3 = $this->db->get('INACBG_TARIP_LAMA')->row_array();

    $q4 = $this->_get_rinci($reg, $type);

    return array(
      'q1' => $q1,
      'q2' => $q2,
      'q3' => $q3,
      'q4' => $q4,
    );
  }

  public function get_okvk($reff)
  {
    // doctor
    $this->db->select('okid AS id,doktern AS doctor,tugasn AS task');
    $this->db->where_in('okid', $reff);

    $doctor = $this->db->get('ok_hasil_dokter')->result_array();

    $ndoctor = array();
    foreach ($doctor as $dc) $ndoctor[$dc['id']][] = $dc;

    // tindakan
    $this->db->select('okidt AS id,tindatakn AS act,tanggal AS date');
    $this->db->where_in('okidt', $reff);

    $act = $this->db->get('ok_hasil_tindakan')->result_array();

    $nact = array();
    foreach ($act as $dca)
    {
      $dca['date'] = date_ind($dca['date']);

      $nact[$dca['id']][] = $dca;    
    }

    // detail ok
    $this->db->select("FJOKNO_TRANSAKSI AS id,FJOKKD_PASIEN AS nrm,NAMAPASIEN AS name,TGL_LAHIR AS age,JENIS_KELAMIN AS gender,r1.FMKNAMA_KAMAR AS room,r2.FMKNAMA_KAMAR AS room_ok,p1.penyakit AS pre,p2.penyakit AS post,FJOKTGL_OP AS date,FJOKJAM_OP AS start,FJOKDURASI AS dur, jenis_anestesi AS anest,specimen AS speciment,jumlah_pendarahan AS bleeding,komplikasi AS complication,penyulit AS harder,laporanOP AS report");
    $this->db->join('OK_JADWAL', 'FJOKNO_TRANSAKSI=okidh', 'left');
    $this->db->join('PENYAKIT p1', 'diag_pre=p1.KD_PENYAKIT', 'left');
    $this->db->join('PENYAKIT p2', 'diag_post=p2.KD_PENYAKIT', 'left');
    $this->db->join('PASIEN', 'FJOKKD_PASIEN=KD_PASIEN', 'left');
    $this->db->join('KAMAR r1', 'r1.FMKKAMAR_ID=FJOKKD_UNIT', 'left');
    $this->db->join('KAMAR r2', 'r2.FMKKAMAR_ID=FJOKNO_KAMAR', 'left');
    $this->db->where_in('okidh', $reff);

    $detail = $this->db->get('OK_hasil')->result_array();

    $ret = array();
    foreach ($detail as $dt)
    {
      if(!empty($dt['id']))
      {
        $dt['age']    = $this->_getdiff(date('Y-m-d'), $dt['age']);
        $dt['gender'] = $dt['gender'] ==  1 ? 'Laki-laki' : 'Perempuan';
        $dt['date']   = date_ind($dt['date']);
        $dt['start']  = clock_ind($dt['start']);

        $ret[$dt['id']] = array(
          'doctor' => !empty($ndoctor[$dt['id']]) ? $ndoctor[$dt['id']] : array(),
          'act'    => !empty($nact[$dt['id']]) ? $nact[$dt['id']] : array(),
          'detail' => $dt,
          'type'   => 1,
        );
      }
    }

    // detail vk
    $this->db->select("FJOBNO_TRANSAKSI AS id,FJOBKD_PASIEN AS nrm,NAMAPASIEN AS name,TGL_LAHIR AS age,JENIS_KELAMIN AS gender,p1.penyakit AS pre,p2.penyakit AS post,FJOBJAMTINDAKAN AS date,FJOBJAMTINDAKAN AS start,jenis_anestesi AS anest,specimen AS speciment,jumlah_pendarahan AS bleeding,komplikasi AS complication,penyulit AS harder,laporanOP AS report");
    $this->db->join('OBSGIEN_JADWAL', 'FJOBNO_TRANSAKSI=okidh', 'left');
    $this->db->join('PENYAKIT p1', 'diag_pre=p1.KD_PENYAKIT', 'left');
    $this->db->join('PENYAKIT p2', 'diag_post=p2.KD_PENYAKIT', 'left');
    $this->db->join('PASIEN', 'FJOBKD_PASIEN=KD_PASIEN', 'left');
    $this->db->where_in('okidh', $reff);

    $detail = $this->db->get('OK_hasil')->result_array();

    foreach ($detail as $dt)
    {
      if(!empty($dt['id']))
      {
        $dt['age']    = $this->_getdiff(date('Y-m-d'), $dt['age']);
        $dt['gender'] = $dt['gender'] ==  1 ? 'Laki-laki' : 'Perempuan';
        $dt['date']   = date_ind($dt['date']);
        $dt['start']  = clock_ind($dt['start']);

        $ret[$dt['id']] = array(
          'doctor' => !empty($ndoctor[$dt['id']]) ? $ndoctor[$dt['id']] : array(),
          'act'    => !empty($nact[$dt['id']]) ? $nact[$dt['id']] : array(),
          'detail' => $dt,
          'type'   => 2,
        );
      }
    }

    return $ret;
  }

  public function save_files($array)
  {
    return $this->db->insert('dev_verif_files', $array);
  }

  public function get_files($reg)
  {
    $this->db->where('reg', $reg);
    $this->db->where('flag', 0);

    $r = $this->db->get('dev_verif_files')->result_array();

    foreach ($r as &$temp) $temp['files'] = base_url($temp['files']);

    return $r;
  }

  public function delete_files($id, $flag)
  {
    $this->db->where('id', $id);
    return $this->db->update('dev_verif_files', array(
      'flag' => $flag
    ));
  }

  public function get_msg($reg)
  {
    $this->db->where('reg', $reg);
    return $this->db->get('dev_verif_message')->row_array();
  }

  public function save_msg($array)
  {
    return $this->db->insert('dev_verif_message', $array);
  }

  public function update_msg($array, $reg)
  {
    $this->db->where('reg', $reg);
    return $this->db->update('dev_verif_message', $array);
  }

  public function confirm_msg($reg, $status = 1)
  {
    $this->db->where('reg', $reg);
    return $this->db->update('dev_verif_message', array(
      'status' => $status
    ));
  }

  public function notif($status, $type)
  {
    $this->db->select('count(*) as count');
    $this->db->where('status', $status);
    
    if($type != 3) $this->db->where('type', $type);

    return $this->db->get('dev_verif_message')->row_array();
  }

  public function notif_show($status, $type)
  {
    $this->db->select('sep,reg,NAMAPASIEN as patient_name,message');
    $this->db->join('BPJS_SEP', 'FMNOTRANSAKSI=reg', 'left');
    $this->db->join('PASIEN', 'KD_PASIEN=FMPASIEN_ID', 'left');
    $this->db->where('dev_verif_message.status', $status);
    
    if($type != 3) $this->db->where('type', $type);

    return $this->db->get('dev_verif_message')->result_array();
  }
}