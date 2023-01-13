<?php


class Wallet_model extends CI_model
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model("Func_model");
        $this->load->model("Pelanggan_model");
        $this->load->model("Driver_model");
        $this->load->model("Merchantapi_model");
        $this->load->model("Notification_model");
        $this->load->model('Appsettings_model');
        $this->load->model('Payment_model', 'payment');
    }

    public function getwallet()
    {
        $this->db->select('mitra.nama_mitra');
        $this->db->select('driver.nama_driver');
        $this->db->select('pelanggan.fullnama');
        $this->db->select('pelanggan.no_telepon');

      

        $this->db->select('wallet.*');

        $this->db->join('mitra', 'wallet.id_user = mitra.id_mitra', 'left');
        $this->db->join('driver', 'wallet.id_user = driver.id', 'left');
        $this->db->join('pelanggan', 'wallet.id_user = pelanggan.id', 'left');
        $this->db->join('payment_method', 'wallet.id_payment_method = payment_method.id', 'left');
        
        $this->db->order_by('wallet.id', 'DESC');
        return $this->db->get('wallet')->result_array();
    }

    public function gettokenmerchant($id_user)

    {
        $this->db->select('mitra.*');
        $this->db->select('merchant.token_merchant');
        $this->db->join('merchant', 'mitra.id_merchant = merchant.id_merchant', 'left');
        $this->db->where('mitra.id_mitra', $id_user);
        return $this->db->get('mitra')->row_array();
    }

    public function ubahsaldotopup($id_user, $amount, $saldo)
    {
        // $this->db->where('id_user', $dataC['id_pelanggan']);
        // $upd = $this->db->update('saldo', array('saldo' => ($saldo - $dataC['biaya_akhir'])));
        $this->db->set('saldo', $saldo['saldo'] + $amount);
        $this->db->where('id_user', $id_user);
        $this->db->update('saldo');
    }

    public function getwalletbyid($id)
    {
        $this->db->select('driver.nama_driver');
        $this->db->select('pelanggan.fullnama');
        $this->db->select('pelanggan.no_telepon');
        
        $this->db->select('wallet.*');
        $this->db->select('wallet.nama_pemilik AS rek_nama');
        $this->db->select('wallet.rekening AS rek_nomor');


        $this->db->join('driver', 'wallet.id_user = driver.id', 'left');
        $this->db->join('pelanggan', 'wallet.id_user = pelanggan.id', 'left');
        

        return $this->db->get_where('wallet', ['wallet.id' => $id])->row_array();
        
    }

    public function getallsaldo()
    {
        $this->db->select('SUM(jumlah)as total');
        return $this->db->get('wallet')->row_array();
    }

    public function getjumlahdiskon()
    {
        $this->db->select('SUM(kredit_promo) as diskon');
        return $this->db->get('transaksi')->row_array();
    }

    public function getallsaldouser()
    {
        $this->db->select('mitra.nama_mitra');
        $this->db->select('driver.nama_driver');
        $this->db->select('pelanggan.fullnama');
        $this->db->select('pelanggan.no_telepon');
        $this->db->select('saldo.*');
        $this->db->join('mitra', 'saldo.id_user = mitra.id_mitra', 'left');
        $this->db->join('driver', 'saldo.id_user = driver.id', 'left');
        $this->db->join('pelanggan', 'saldo.id_user = pelanggan.id', 'left');
        return $this->db->get('saldo')->result_array();
    }

    public function gettotaltopup()
    {
        $this->db->select('SUM(jumlah)as total');
        $this->db->where('status', 1);
        $this->db->where('type', 'topup');
        return $this->db->get('wallet')->row_array();
    }

    public function gettotalwithdraw()
    {
        $this->db->select('SUM(jumlah)as total');
        $this->db->where('type', 'withdraw');
        $this->db->where('status', 1);
        return $this->db->get('wallet')->row_array();
    }

    public function gettotalorderplus()
    {
        $this->db->select('SUM(jumlah)as total');
        $this->db->where('type', 'Order+');
        return $this->db->get('wallet')->row_array();
    }

    public function gettotalordermin()
    {
        $this->db->select('SUM(jumlah)as total');
        $this->db->where('type', 'Order-');
        return $this->db->get('wallet')->row_array();
    }

    public function ubahstatuswithdrawbyid($id)
    {
        $this->db->set('status', 1);
        $this->db->where('id', $id);
        $this->db->update('wallet');
    }

    public function cancelstatuswithdrawbyid($id)
    {
        $this->db->set('status', 2);
        $this->db->where('id', $id);
        $this->db->update('wallet');
    }

    public function gettoken($id_user)

    {
        $this->db->select('token');
        $this->db->where('id', $id_user);
        return $this->db->get('pelanggan')->row_array();
    }

    public function getregid($id_user)
    {
        $this->db->select('reg_id');
        $this->db->where('id', $id_user);
        return $this->db->get('driver')->row_array();
    }

    public function getsaldo($id_user)
    {
        $this->db->select('saldo');
        $this->db->where('id_user', $id_user);
        return $this->db->get('saldo')->row_array();
    }

    public function getwalletbyinvoice($invoice){
        
        return $this->db->get_where('wallet', ['invoice' => $invoice])->row_array();

    }

    public function ubahsaldo($id_user, $saldo_akhir)
    {
        // $this->db->where('id_user', $dataC['id_pelanggan']);
        // $upd = $this->db->update('saldo', array('saldo' => ($saldo - $dataC['biaya_akhir'])));
        $this->db->set('saldo', $saldo_akhir);
        $this->db->where('id_user', $id_user);
        $this->db->update('saldo');
    }

    public function send_notif($title, $message, $topic)
    {

        $data = array(
            'title' => $title,
            'message' => $message,
            'type' => 3
        );
        $senderdata = array(
            'data' => $data,
            'to' => $topic
        );

        $headers = array(
            'Content-Type : application/json',
            'Authorization: key=' . keyfcm
        );
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($senderdata),
            CURLOPT_HTTPHEADER => $headers,
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
    }

    //TOPUP GENERATOR
    public function updatesaldowallet($data)
    {

        $this->db->select('mitra.nama_mitra');
        $this->db->select('driver.nama_driver');
        $this->db->select('pelanggan.fullnama');
        $this->db->select('saldo.saldo as saldolama');
        $this->db->join('mitra', 'saldo.id_user = mitra.id_mitra', 'left');
        $this->db->join('driver', 'saldo.id_user = driver.id', 'left');
        $this->db->join('pelanggan', 'saldo.id_user = pelanggan.id', 'left');
        $this->db->where('id_user', $data['id_user']);
        $saldolama = $this->db->get('saldo')->row_array();

        $saldobaru = $saldolama['saldolama'] + $data['saldo'];

        $this->db->set('saldo', $saldobaru);
        $this->db->where('id_user', $data['id_user']);
        $this->db->update('saldo');

        if ($data['type_user'] == 'pelanggan') {
            $nama = $saldolama['fullnama'];
        } elseif ($data['type_user'] == 'mitra') {
            $nama = $saldolama['nama_mitra'];
        } else {
            $nama = $saldolama['nama_driver'];
        }


        $this->db->set('status', '1');
        $this->db->set('type', 'topup');
        $this->db->set('rekening', 'admin');
        $this->db->set('bank', 'admin');
        $this->db->set('nama_pemilik', $nama);
        $this->db->set('jumlah', $data['saldo']);
        $this->db->set('id_user', $data['id_user']);
        $this->db->insert('wallet');
    }




    public function updatesaldowalletwithdraw($data, $data2)
    {
        $this->db->select('mitra.nama_mitra');
        $this->db->select('driver.nama_driver');
        $this->db->select('pelanggan.fullnama');
        $this->db->select('saldo.saldo as saldolama');
        $this->db->join('mitra', 'saldo.id_user = mitra.id_mitra', 'left');
        $this->db->join('driver', 'saldo.id_user = driver.id', 'left');
        $this->db->join('pelanggan', 'saldo.id_user = pelanggan.id', 'left');
        $this->db->where('id_user', $data['id_user']);
        $saldolama = $this->db->get('saldo')->row_array();

        $saldobaru = $saldolama['saldolama'] - $data['saldo'];
        if ($saldobaru < 0) {
            $this->session->set_flashdata('salah', 'Not enaugh Balances');
            redirect('wallet/tambahwithdraw');
        } else {
            $this->db->set('saldo', $saldobaru);
            $this->db->where('id_user', $data['id_user']);
            $this->db->update('saldo');

            if ($data['type_user'] == 'pelanggan') {
                $nama = $saldolama['fullnama'];
            } elseif ($data['type_user'] == 'mitra') {
                $nama = $saldolama['nama_mitra'];
            } else {
                $nama = $saldolama['nama_driver'];
            }

            $this->db->set('status', '1');
            $this->db->set('type', 'withdraw');
            $this->db->set('rekening', $data2['rekening']);
            $this->db->set('bank', $data2['bank']);
            $this->db->set('nama_pemilik', $data2['nama_pemilik']);
            $this->db->set('jumlah', $data['saldo']);
            $this->db->set('id_user', $data['id_user']);
            $this->db->insert('wallet');
        }
    }

    public function getwalletall()
    {
        $this->db->select('wallet.*');
        $this->db->select('merchant.token_merchant');
        $this->db->select('driver.reg_id');
        $this->db->select('pelanggan.token');
        $this->db->select('pelanggan.id,driver.id,mitra.id_mitra,merchant.id_merchant');
        $this->db->join('mitra', 'wallet.id_user=mitra.id_mitra', 'left');
        $this->db->join('merchant', 'mitra.id_merchant=merchant.id_merchant', 'left');
        $this->db->join('driver', 'wallet.id_user=driver.id', 'left');
        $this->db->join('pelanggan', 'wallet.id_user=pelanggan.id', 'left');
        return $this->db->get('wallet')->result_array();
    }

    public function updatekadaluarsa()
    {
        $this->load->model('Notification_model');

        $wallet = $this->getwalletall();




        foreach ($wallet as $wl) {



            $date1 = date_create($wl['expired']);
            $date = new DateTime('now', new DateTimeZone('Asia/Makassar'));


            $tanggal = date_format($date1, "YmdHis");

            $tanggal2 = date_format($date, "YmdHis");
            // var_dump($tanggal);
            // var_dump($tanggal2);
            // die();

            if ($tanggal <= $tanggal2 && $wl['status'] == 0 && $wl['expired'] != "") {
                // var_dump($tanggal);
                // var_dump($tanggal2);
                // die();
                $this->db->set('status', '2');

                $this->db->Where('status', '0');
                $this->db->Where('invoice', $wl['invoice']);
                $this->db->update('wallet');

                $id = substr($wl['id_user'], '0', '1');
                if ($id == 'M') {
                    $token = $wl['token_merchant'];
                } else if ($id == 'P') {
                    $token = $wl['token'];
                } else {
                    $token = $wl['reg_id'];
                }

                $title = 'Dibatalkan';
                $desc = 'Pembayaran Kamu telah Dibatalkan';
                $this->Notification_model->send_notif_topup($title, $wl['id_user'], $desc, $wl['invoice'], $token);
            }
        }
    }

/*  UPDATE LOG 30 DESEMBER 2022 -----------------------------------------------------------*/

    public function updatestatusinvoicewallet($id, $invoice, $note){
        if($note == 'SUCCEEDED'){
            $status = 1;
        }elseif ($note == 'FAILED'){
            $status = 2;
        }else{
            $status = 0;
        }

        $this->db->set('invoice', $invoice);
		$this->db->set('status', $status);

		$this->db->where('id', $id);
		$this->db->update('wallet');
    }

    public function inserttransaksiwd($data){
        $data_insert = array(
            'invoice'       => $data['external_id'],
            'tipe'          => 4,
            'metode'        => $data['id_pm'],
            'regtime'       => date('Y-m-d H:i:s'),
            'id_user'       => $data['id_user'],
            'saldo_awal'    => $data['saldo_awal'],
            'nominal'       => $data['jumlah_wd'],
            'biaya'         => $data['biaya_pm'],
            'total'         => $data['amount'],
            'note'          => 'PENDING',
            'status'        => 1,
            'is_demo'       => 0,
            'bank_code'     => $data['bank_code'],
            'account_number'=> $data['account_number']
        );

        $this->db->insert('payment_transaksi', $data_insert);

    }

    public function updatetransaksiwd($invoice, $saldo_akhir, $note){
        if($note == 'SUCCEEDED'){
            $status = 2;
        }elseif ($note == 'FAILED'){
            $status = 3;
        }

        $this->db->set('saldo_akhir', $saldo_akhir);
        $this->db->set('note', $note);
        $this->db->set('status', $status);

        $this->db->where('invoice', $invoice);
        $this->db->update('payment_transaksi');
    }

    //INSERT LOG_CALLBACK
    public function insertcallback($data){
        $this->db->insert('log_callback', $data);
    }

    //UPDATE Payment_method WD
    public function update_pm_wd($id_wallet, $id_payment_method){
        $this->db->set('id_payment_method', $id_payment_method);
        $this->db->where('id', $id_wallet);
        $this->db->update('wallet');
    }

    //REQUEST Preparation Withdraw
    function create_wd($param)
    {
       $data = new stdClass();
       $exp_date = date("Y-m-d H:i:s", strtotime('+24 hours'));
    
       $no_trx = 'TRX-WD'.date('yHmids');
       
       $payload_xendit = array(
            'external_id'         => $no_trx,
            'id_wallet'           => $param['id_wallet'],
            'saldo_awal'          => $param['saldo_awal'],

            'amount'              => $param['amount'],
            'bank_code'           => $param['bank_code'],
            'account_holder_name' => $param['account_holder_name'],
            'account_number'      => $param['account_number'],
            'description'         => $param['description'],

            'key_production'      => $param['key_production'],
            'key_development'     => $param['key_development'],
            'is_demo'             => $param['is_demo'],

            'id_user'             => $param['id_user'],
            'id_pm'               => $param['id_pm'],
            'biaya_pm'            => $param['biaya_pm'],
            'jumlah_wd'           => $param['jumlah_wd']
        );

        $create_request = $this->Func_model->charge_wd($payload_xendit);
        
        $data_respon = json_decode($create_request['body'], true);
        
        

        if ($create_request['http_code'] == 200) {
            /*
            if($this->db->affected_rows() < 1){
                //log_message('error', '(1) resp xendit ' . $data_trx);
                $data->error = "Gagal meproses data (1)";
                return $data;
            }
            */

            //INSERT PAYMENT_TRANSAKSI
            $this->wallet->inserttransaksiwd($payload_xendit);
            
            $data->insert_id = $this->db->insert_id();
            $data->data = $data_respon;
            
            return $data;
            
        } else {
       
            $this->session->set_flashdata('error');
            $data->error = 'HTTP error -> gagal Request';
            return $data;
               
        }
    
      
    }
    
}
