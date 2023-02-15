<?php

class Payment_model extends CI_model
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("Func_model");
        $this->load->model("Pelanggan_model");
        $this->load->model("Driver_model");
        $this->load->model("Merchantapi_model");
        $this->load->model("Notification_model");
        $this->load->model('Appsettings_model');
    }
    
    function get_saldo($param)
    {
        $this->db->select('*')
                ->from('saldo')
                ->where($param)
                ->limit(1);
        return $this->db->get()->row();
    }
    
    function get_data_payment_method()
    {
        $this->db->select('*');
        $this->db->order_by('id', 'ASC');
        return  $this->db->get('payment_method')->result_array();
    }

    function get_data_payment_method_by_tipe($tipe)
    {
        $this->db->select('*');
        $this->db->order_by('id', 'ASC');
        $this->db->where('tipe', $tipe);
        return  $this->db->get('payment_method')->result_array();
    }
    
    function get_data_payment_by_id($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('payment_method')->row_array();
    }
    
    function insert_data_payment($data)
    {
        return $this->db->insert('payment_method', $data);
    }
    
    function update_data_payment($data)
    {   
        if($data['tipe'] < 2){
            $this->db->set('image', $data['image']);
            $this->db->set('nama', $data['nama']);
            $this->db->set('keterangan', $data['keterangan']);
            $this->db->set('tipe', $data['tipe']);
            $this->db->set('jenis', $data['jenis']);
            $this->db->set('bank', $data['bank']);
            $this->db->set('no_rekening', $data['no_rekening']);
            $this->db->set('nama_rekening', $data['nama_rekening']);
            $this->db->set('channel_code', $data['channel_code']);
            $this->db->set('biaya', $data['biaya']);
            $this->db->set('status', $data['status']);
        }else{
            
            $this->db->set('nama',      $data['nama']);
            $this->db->set('tipe',      $data['tipe']);
            $this->db->set('jenis',     $data['jenis']);

            $this->db->set('bank',      $data['bank']);
            $this->db->set('channel_code', $data['channel_code']);
            $this->db->set('biaya',     $data['biaya']);

            $this->db->set('keterangan',$data['keterangan']);
            $this->db->set('status', $data['status']);
            $this->db->set('image',     $data['image']);
        }
        
        $this->db->where('id',      $data['id']);

        return $this->db->update('payment_method');
    }
    
    function hapus_data_payment($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('payment_method');
    }
    
    function get_all_jenis_payment()
    {   
        $res = $this->db->get('payment_jenis')->result();

        if(count($res) > 0){

            foreach($res as $value){

                if($value->id_jenis == 1 || $value->id_jenis == 2){
                    
                    $data[] = [
                        'jenis'  => $value->nama_jenis,
                        'method' => $this->get_data_payment_group($value->id_jenis)
                    ];
                }
            }

        }else{
            $data = [];
        }
        
        return $data;
    }

    function get_all_wd_method(){
        $this->where('tipe', 2);
        $this->where('status', 1);
        $this->where('jenis', 4);
        $this->db->order_by('id', 'ASC');
        $res = $this->db->get('payment_method');
        if($res->num_rows() > 0){
            foreach($res->result() as $value){
                $data[] = [
                    'id'         => $value->id,
                    'tipe'       => $value->tipe,
                    'jenis'      => $value->jenis,
                    'nama'       => $value->nama,
                    'keterangan' => $value->keterangan,
                    'bank'       => $value->bank,
                    'noRekening' => $value->no_rekening,
                    'namaRekening' => $value->nama_rekening,
                    'image'      => base_url().'images/promo/'.$value->image,
                    'biaya'      => $value->biaya,
                    'code'       => $value->channel_code
                ];
            }
            
        }else{
            $data = [];
        }
        
        return $data;
    }

    
    function get_data_payment_group($jenis)
    {
        $this->db->where('tipe', 1);
        $this->db->where('status', 1);
        $this->db->where('jenis', $jenis);
        $this->db->order_by('id', 'ASC');
        $this->db->order_by('jenis', 'ASC');
        $res = $this->db->get('payment_method');
        if($res->num_rows() > 0){
            foreach($res->result() as $value){
                $data[] = [
                    'id' => $value->id,
                    'tipe' => $value->tipe,
                    'jenis' => $value->jenis,
                    'nama' => $value->nama,
                    'keterangan' => $value->keterangan,
                    'bank' => $value->bank,
                    'noRekening' => $value->no_rekening,
                    'namaRekening' => $value->nama_rekening,
                    'image' => base_url().'images/promo/'.$value->image,
                    'biaya' => $value->biaya,
                    'code' => $value->channel_code
                ];
            }
            
       
            
        }else{
            $data = [];
        }
        
        return $data;
    }
    /*
    function get_valid_trf($param)
    {
        $data = new stdClass();
        $cond = array(
            'no_telepon'=> $param->no_penerima
        );
        $penerima = $this->Pelanggan_model->get_data_pelanggan($cond)->row();
            
        if(!empty($penerima)){
            if($penerima->id == $param->id_user){
                $data->error = "Tidak bisa melakukan transfer ke akun sendiri";
                    //  return;
            }else{
                $data->nama_penerima = $penerima->fullnama;
                $data->id = $penerima->id;
            }
                 
        }else{
            $data->error = "Data penerima tidak ditemukan";
        }
        
        return $data;
        
    }
    */
    
    function get_valid_trf($param)
    {
        $data = new stdClass();
        

        //VALIDASI tipe PENERIMA
        if($param->tipe == 1){ //tipe Customer
            $cond = array(
                'no_telepon'=> $param->no_penerima
            );
            $penerima_c = $this->Pelanggan_model->get_data_pelanggan($cond)->row();

        }elseif($param->tipe == 2){//tipe Driver
            $cond = array(
                'no_telepon'=> $param->no_penerima
            );
            $penerima_d = $this->Driver_model->get_data_pelanggan($cond)->row();
        
        }elseif($param->tipe == 3){//tipe merchant
            $cond = array(
                'telepon_mitra'=> $param->no_penerima
            );
            $penerima_m = $this->Merchantapi_model->get_data_merchant($cond)->row();
        }else{
            $data->error = "Tipe nya belum masuk euyy";
        }

            
        if(!empty($penerima_c)){
            
            if($penerima_c->id == $param->id_user){
                $data->error = "Tidak bisa melakukan transfer ke akun sendiri";
            
            }else{
            
                $data->nama_penerima = $penerima_c->fullnama;
                $data->id            = $penerima_c->id;
            }
                 
        }elseif(!empty($penerima_d)){

            if($penerima_d->id == $param->id_user){
                $data->error = "Tidak bisa melakukan transfer ke akun sendiri";

            }else{
                $data->nama_penerima = $penerima_d->nama_driver;
                $data->id            = $penerima_d->id;
            }

        }elseif(!empty($penerima_m)){
            
            if($penerima_m->id == $param->id_user){
                $data->error = "Tidak bisa melakukan transfer ke akun sendiri";

            }else{
                $data->nama_penerima = $penerima_m->nama_mitra;
                $data->id            = $penerima_m->id_mitra;
            }
            
        }else{
            $data->error = "Data penerima tidak ditemukan";
        }
        
        return $data;
        
    }
    //cek1122
    function add_transfer($param)
    {
        $data = new stdClass();
        $cond = array('id_user' => $param->id_user);
        $condrec = array('id_user' => $param->id_receiver);
        $id_user = $param->id_user;
        $id_receiver = $param->id_receiver;
        
       
        
         // validation receiver
        if($id_receiver[0] == 'D'){
             $conds = array(
                        'id'=> $param->id_receiver
                    );
             $receiver = $this->Driver_model->get_data_pelanggan($conds)->row();
             $nama_penerima = $receiver->nama_driver;
             $token = $receiver->reg_id;
             $phone = $receiver->no_telepon;
             
        }else if($id_receiver[0] == 'M'){
            
            $conds = array(
                        'id_mitra'=> $param->id_receiver
                    );
            $receiver = $this->Merchantapi_model->get_data_merchant($conds)->row();
            
            $nama_penerima = $receiver->nama_merchant;
            $token = $receiver->token_merchant;
            $phone = $receiver->telepon_mitra;
            
            
        }else{
            $conds = array(
                        'id'=> $param->id_receiver
                    );
            $receiver = $this->Pelanggan_model->get_data_pelanggan($conds)->row();
            $nama_penerima = $receiver->fullnama;
            $token = $receiver->token;
            $phone = $receiver->no_telepon;
            // $data->error = "Mohon maaf, transfer pelanggan saat ini tidak bisa dilakukan. Kami akan info kembali ketika fitur ini sudah aktif lagi";
          
           
        }
        // $conds = array(
        //     'id'=> $param->id_receiver
        // );
        // $receiver = $this->Pelanggan_model->get_data_pelanggan($conds)->row();
        // $nama_penerima = $receiver->fullnama;
        // $token = $receiver->token;
        // $phone = $receiver->no_telepon;
        
        
        
        // validation sender
        if($id_user[0] == 'D'){
             $conds = array(
                        'id'=> $param->id_user
                    );
             $sender = $this->Driver_model->get_data_pelanggan($conds)->row();
             $nama_pengirim = $sender->nama_driver;
             $tokens = $sender->reg_id;
             $phones = $sender->no_telepon;
             $sender_password = $sender->password;
             
        }else if($id_user[0] == 'M'){
            $conds = array(
                        'id_mitra'=> $param->id_user
                    );
            $sender = $this->Merchantapi_model->get_data_merchant($conds)->row();
            $nama_pengirim = $sender->nama_merchant;
            $tokens = $sender->token_merchant;
            $phones = $sender->telepon_mitra;
            $sender_password = $sender->password;
            
        }else if($id_user[0] == 'K'){
            
            $conds = array(
                        'id_agen'=> $param->id_user
                    );
            $sender = $this->Agen_model->get_data_agen($conds)->row();
            $nama_pengirim = $sender->nama;
            $tokens = $sender->token;
            $phones = $sender->hp;
            $sender_password = $sender->passwd;
            
            
        }else{
            $conds = array(
                        'id'=> $param->id_user
                    );
            $sender = $this->Pelanggan_model->get_data_pelanggan($conds)->row();
            $nama_pengirim = $sender->fullnama;
            $tokens = $sender->token;
            $phones = $sender->no_telepon;
            $sender_password = $sender->password;
        }
        // $conds = array(
        //     'id'=> $param->id_user
        // );
        // $sender = $this->Pelanggan_model->get_data_pelanggan($conds)->row();
        // $nama_pengirim = $sender->fullnama;
        // $tokens = $sender->token;
        // $phones = $sender->no_telepon;
        // $sender_password = $sender->password;
        
        if(empty($sender)){
            $data->error = 'Data akun anda tidak ditemukan. silahkan hubungi customer service';
            return $data;
        }
        
        if($id_user == $id_receiver){
            $data->error = "Tidak bisa melakukan transfer saldo ke akun sendiri";
            return $data;
        }
        
        if($sender_password != sha1($param->password)){
            $data->error = "Password yang anda masukkan salah";
            return $data;
        }
        
        
        $saldoSender = $this->get_saldo($cond);
        $saldoReceiver = $this->get_saldo($condrec);
        
        // validation saldo sender
        if($saldoSender->saldo < $param->nominal){
            $data->error = 'Saldo anda tidak mencukupi untuk melakukan transaksi.';
            return $data;
        }
        
        $no_trx = 'TRF-'.date('yHmids');
        $data_trx = array(
            'invoice' => $no_trx,
            'tipe' => '1',
            'sender_wallet_id' => $saldoSender->nomor,
            'receiver_wallet_id' => $saldoReceiver->nomor,
            'sender_user_id' => $param->id_user,
            'receiver_user_id' => $param->id_receiver,
            'saldo_sender_awal' => $saldoSender->saldo,
            'saldo_receiver_awal' => $saldoReceiver->saldo,
            'nominal' => $param->nominal,
            'fee' => 0,
            'note' => $param->note,
            'status' => 1,
            'regtime' => date('Y-m-d H:i:s')
        );
             

        $verify = $this->db->insert('transaksi_saldo', $data_trx);
        if($this->db->affected_rows() < 1){
                // log_message('error', '(1) resp xendit ' . $data_trx);
                $data->error = "Gagal meproses data (1)";
                return $data;
        }
        
        $insert_id = $this->db->insert_id();
        $param_trx = array('id'=> $insert_id);
        
      
        
        $data_saldo_sender = array(
            'saldo' => intval($saldoSender->saldo) - intval($param->nominal),
            'update_at' => date('Y-m-d H:i:s')
        );
        
        $this->db->where('nomor', $saldoSender->nomor);
        $updateSaldoSender = $this->db->update('saldo', $data_saldo_sender);
        if($this->db->affected_rows() < 1){
            $data->error = "Gagal meproses data (2)";
            return $data;
        }
        
        
        $data_saldo_receiver = array(
            'saldo' => intval($saldoReceiver->saldo) + intval($param->nominal),
            'update_at' => date('Y-m-d H:i:s')
        );

        $this->db->where('nomor', $saldoReceiver->nomor);
        $updateReceiver = $this->db->update('saldo', $data_saldo_receiver);
        if($this->db->affected_rows() < 1){
            $data->error = "Gagal meproses data (3)";
            return $data;
        }
        
        $data = $this->get_transaksi($param_trx);
        
        $title = 'JAKiTrans';
        $desc = 'Anda mengirim saldo sebesar Rp'.$param->nominal. ' ke '. $nama_penerima;
        $this->Notification_model->send_notif_topup($title, $param->id_user, $desc, $no_trx, $tokens);

        
        $title1 = 'JAKiTrans';
        $desc1 = $nama_pengirim.' mengirim saldo sebesar Rp'.$param->nominal;
        $this->Notification_model->send_notif_topup($title1, $param->id_receiver, $desc1, $no_trx, $token);
        
      
        
        return $data;
        
    }
    
    function get_transaksi($param)
    {
        $this->db->select('*');
        $this->db->from('transaksi_saldo');
        $this->db->where($param);
        $this->db->limit(1);
        return $this->db->get()->row();
    }
    
    function get_data_payment_transaksi()
    {
        $this->db->select('payment_transaksi.*, payment_method.nama as metode, payment_jenis.nama_jenis as jenis_nama');
        $this->db->join('payment_method', 'payment_transaksi.metode = payment_method.id', 'LEFT');
        $this->db->join('payment_jenis', 'payment_method.jenis = payment_jenis.id_jenis', 'LEFT');
        $this->db->order_by('payment_transaksi.id', 'DESC');
        return $this->db->get('payment_transaksi')->result_array();
    }
    
    function get_data_payment_by_invoice($id)
    {
        $this->db->select('payment_transaksi.*,
        payment_method.jenis as metode_jenis,
        payment_method.nama as metode_nama, 
        payment_jenis.nama_jenis as jenis_nama, 
        payment_method.image as metode_image');
        $this->db->join('payment_method', 'payment_transaksi.metode = payment_method.id', 'LEFT');
        $this->db->join('payment_jenis', 'payment_method.jenis = payment_jenis.id_jenis', 'LEFT');
        $this->db->where('invoice', $id);
        return $this->db->get('payment_transaksi')->row_array();
    }
    
    function get_data_payment_by_cond($cond)
    {
        $this->db->select('payment_transaksi.*,
        payment_method.jenis as metode_jenis,
        payment_method.nama as metode_nama, 
        payment_jenis.nama_jenis as jenis_nama, 
        payment_method.image as metode_image');
        $this->db->join('payment_method', 'payment_transaksi.metode = payment_method.id', 'LEFT');
        $this->db->join('payment_jenis', 'payment_method.jenis = payment_jenis.id_jenis', 'LEFT');
        $this->db->where($cond);
        $this->db->order_by('payment_transaksi.id', 'DESC');
        return $this->db->get('payment_transaksi')->result_array();
    }
    
    function get_setting_payment()
    {
        $this->db->where('id', 1);
        return $this->db->get('payment_setting')->row_array();
    }
    
    function update_data_payment_setting($data)
    {
        $this->db->where('id', 1);
        return $this->db->update('payment_setting', $data);
    }
    
    //GENERATOR API Topup XENDIT->ewallet
    function charge_ewallet_xendit($param)
    {
       $data = new stdClass();
       $cond = array('id_user' => $param['id_user']);
       $saldo_user = $this->get_saldo($cond);
       
       $no_trx = 'TRX-'.date('yHmids');
       $exp_date = date("Y-m-d H:i:s", strtotime('+24 hours'));
       
       $payload_xendit = array(
            'reference_id' => $no_trx,
            'amount' => $param['amount'] + $param['fee'],
            'channel_code'=> $param['channel_code'],
            'redirect' => $param['phone'],
            'key_production' => $param['key_production'],
            'key_development' => $param['key_development'],
            'is_demo' => $param['is_demo']
        );
        $create_xendit = $this->Func_model->ChargesEwallet($payload_xendit);
        $data_respon = json_decode($create_xendit['body'], true);
        
        if ($create_xendit['http_code'] == 202) {
            $data_trx = array(
                'invoice' => $no_trx,
                'tipe' => '1',
                'metode' => $param['channel_id'],
                'regtime' => date('Y-m-d H:i:s'),
                'id_user' => $param['id_user'],
                'saldo_awal' => $saldo_user->saldo,
                'nominal' => $param['amount'],
                'biaya' => $param['fee'],
                'total' => $param['amount'] + $param['fee'],
                'note' => $data_respon['status'],
                'reff' => $data_respon['business_id'],
                'status' => '1',
                'is_demo' => $param['is_demo'],
                
            );
            
            
            $verify = $this->db->insert('payment_transaksi', $data_trx);
            if($this->db->affected_rows() < 1){
                // log_message('error', '(1) resp xendit ' . $data_trx);
                $data->error = "Gagal meproses data (1)";
                return $data;
            }
                // echo $xendit->checkout_url;die();
            
            
            $data->insert_id = $this->db->insert_id();
            $data->data = $data_respon;
            
            return $data;
            
        } else {
       
            $data->error = $data_respon['error_code'];
            $data->message = $data_respon['message'];
            
            return $data;
               
        }
      
    }
    
    function update_trans_ewallet_callback($param)
    {
        $data = new stdClass();
        $data_callback = $param->data;
        
        log_message('error','data update:'. json_encode($param).' trx:'.$data_callback->reference_id);
        
        $this->db->where('invoice', $data_callback->reference_id);
        $data_trans = $this->db->get('payment_transaksi');
        
        if($data_trans->num_rows() > 0){
            $trx = $data_trans->row();
            if($data_callback->status == 'SUCCEEDED'){
                $cond_saldo = array('id_user' => $trx->id_user);
                $saldo = $this->get_saldo($cond_saldo);
                $saldo_akhir = $saldo->saldo + $trx->nominal;
                
                $data_update_trx = array(
                    'status' => 2,
                    'note'=> $data_callback->status,
                    'modified' => date('Y-m-d H:i:s'),
                    'saldo_awal' => $saldo->saldo,
                    'saldo_akhir' => $saldo_akhir
                );
                // update status payment transaksi
                $verify = $this->db->where('invoice', $data_callback->reference_id)
                                    ->update('payment_transaksi', $data_update_trx);
                if($this->db->affected_rows() < 1){
                    $data->error = "Gagal meproses data (1)";
                    return $data;
                }
                
                // insert tabel wallet
                $datatopup = array(
                            'id_user' => $trx->id_user,
                            'rekening' => $data_callback->channel_code,
                            'bank' => 'dextrans',
                            'nama_pemilik' => 'wallet',
                            'type' => 'topup',
                            'jumlah' => $trx->nominal,
                            'status' => 1
                );
                        
                $this->Pelanggan_model->insertwallet($datatopup);
                // update saldo
                $data_update = array(
                        'saldo' => $saldo_akhir,
                        'update_at' => date('Y-m-d H:i:s')
                );
                
                $this->db->where('nomor', $saldo->nomor);
                $this->db->update('saldo', $data_update);
                if($this->db->affected_rows() < 1){
                    $data->error = "Gagal meproses data (2)";
                    return $data;
                }
                
                // send notif
                // $title = 'Saldoku';
                // $desc = 'Topup saldo anda sebesar Rp'.number_format($trx->amount,0,".",",").' berhasil.';
                // $this->Notification_model->send_notif_topup($title, $trx->id_user, $desc, $trx->invoice, $tokens);
                
               
            }else{
                $data_update = array(
                    'status' => 3, 
                    'note'=> $data_callback->failure_code
                );
                $this->db->where('invoice', $data_callback->reference_id);
                $this->db->update('payment_transaksi', $data_update);
                
                // $title = 'Saldoku';
                // $desc = 'Topup saldo anda gagal. silahkan ulangi atau hubungi customer service kami.';
                // $this->Notification_model->send_notif_topup($title, $trx->id_user, $desc, $trx->invoice, $tokens);
                
            }
            
        }else{
            log_message('error', ' trx not found');
            echo 'Error: trx not found';
        }
    }
    
    function pending_top_va_xendit($param)
    {
       $data = new stdClass();
       $cond = array('id_user' => $param['id_user']);
       $saldo_user = $this->get_saldo($cond);
       
       $no_trx = 'TRX-'.date('yHmids');
       $exp_date = date("Y-m-d H:i:s", strtotime('+24 hours'));
       
        $payload_xendit = array(
            'external_id' => $no_trx,
            'expected_amount' => $param['amount'] + $param['fee'],
            'bank_code'=> $param['channel_code'],
            'name' => $param['name'],
            'expiration_date' => $exp_date,
            'key_production' => $param['key_production'],
            'key_development' => $param['key_development'],
            'is_demo' => $param['is_demo']
        );
        $create_xendit = $this->Func_model->CreateCallbackVirtualAccount($payload_xendit);
       
       
        if ($create_xendit['http_code'] == 200) {
            $data_respon = json_decode($create_xendit['body'], true);
            //  echo json_encode($data_respon);die();
            $data_trx = array(
                'invoice' => $no_trx,
                'tipe' => '1',
                'metode' => $param['channel_id'],
                'regtime' => date('Y-m-d H:i:s'),
                'id_user' => $param['id_user'],
                'saldo_awal' => $saldo_user->saldo,
                'nominal' => $param['amount'],
                'biaya' => $param['fee'],
                'total' => $param['amount'] + $param['fee'],
                'note' => $data_respon['status'],
                'reff' => $data_respon['id'],
                'status' => 0,
                'is_demo' => $param['is_demo'],
                'bank_code' => $data_respon['bank_code'],
                'account_number' => $data_respon['account_number'],
                'payment_reff' => $data_respon['id'],
                'expired_date' => $data_respon['expiration_date']
            );
            
            
            $verify = $this->db->insert('payment_transaksi', $data_trx);
            if($this->db->affected_rows() < 1){
                // log_message('error', '(1) resp xendit ' . $data_trx);
                $data->error = "Gagal meproses data (1)";
                return $data;
            }
                // echo $xendit->checkout_url;die();
            
            
            // $data->insert_id = $this->db->insert_id();
            $data = $data_respon;
            
            return $data;
            
        } else {
       
            $data->error = $create_xendit['body'];
            return $data;
            
            
        }
    }
    
    function update_status_va($param)
    {
        $data = new stdClass();
        $this->db->select('trx.id_user, trx.nominal')
                ->from('payment_transaksi trx')
                ->where('invoice', $param->external_id)
                ->limit(1);

        $trx = $this->db->get()->row();
        if(count($trx) > 0){
            $data_trx = array(
                'status' => 1,
                'note'=> 'VA Has Been Success Created',
                'modified' => date('Y-m-d H:i:s'),
                'bank_code' => $param->bank_code,
                'account_number' => $param->account_number
            );
    
            $verify = $this->db->where('invoice', $param->external_id)
                            ->update('payment_transaksi', $data_trx);
            if($this->db->affected_rows() < 1){
                $data->error = "Gagal meproses data (1)";
                return $data;
            }
        }else{
            log_message('error', ' trx not found');
            echo 'Error: trx not found';
        }
        
        return $data;
    }
    
    function success_top_xendit($param)
    {
        $data = new stdClass();
        $this->db->select('trx.id_user, trx.nominal')
                ->from('payment_transaksi trx')
                ->where('invoice', $param->external_id)
                ->limit(1);

        $trx = $this->db->get()->row();
        if(count($trx) > 0){
            $cond_saldo = array('id_user' => $trx->id_user);
            $saldo = $this->get_saldo($cond_saldo);
            $saldo_akhir = $saldo->saldo + $trx->nominal;
            
            $cond = array('id' => $trx->id_user);
            $data_trx = array(
                'status' => 2,
                'note'=> json_encode($param),
                'modified' => date('Y-m-d H:i:s'),
                'saldo_awal' => $saldo->saldo,
                'saldo_akhir' => $saldo_akhir
            );
    
            $verify = $this->db->where('invoice', $param->external_id)
                            ->update('payment_transaksi', $data_trx);
            if($this->db->affected_rows() < 1){
                $data->error = "Gagal meproses data (1)";
                return $data;
            }
            $data->insert_id = $this->db->insert_id();
            
            
            
            // insert tabel wallet
            $datatopup = array(
                'id_user' => $trx->id_user,
                'rekening' => $param->bank_code,
                'bank' => 'dextrans',
                'nama_pemilik' => 'wallet',
                'type' => 'topup',
                'jumlah' => $trx->nominal,
                'status' => 1
            );
                            
            $this->Pelanggan_model->insertwallet($datatopup);
            // update saldo
            $data_update = array(
                    'saldo' => $saldo_akhir,
                    'update_at' => date('Y-m-d H:i:s')
            );
                    
            $this->db->where('nomor', $saldo->nomor);
            $this->db->update('saldo', $data_update);
            if($this->db->affected_rows() < 1){
                    $data->error = "Gagal meproses data (2)";
                    return $data;
            }
                    
            // send notif
            // $title = 'Saldoku';
            // $desc = 'Topup saldo anda sebesar Rp'.number_format($trx->amount,0,".",",").' berhasil.';
            // $this->Notification_model->send_notif_topup($title, $trx->id_user, $desc, $trx->invoice, $tokens);        
            
            if($this->db->affected_rows() < 1){
                $data->error = "Gagal meproses data (2)";
                return $data;
            }
        }else{
            log_message('error', ' trx not found');
            echo 'Error: trx not found';
        }
        


        return $data;
    }
    
    function deposit_retail_xendit($param)
    {
       $data = new stdClass();
       $cond = array('id_user' => $param['id_user']);
       $saldo_user = $this->get_saldo($cond);
       
       $no_trx = 'TRX-'.date('yHmids');
       $exp_date = date("Y-m-d H:i:s", strtotime('+24 hours'));
       
        $payload_xendit = array(
            'external_id' => $no_trx,
            'expected_amount' => $param['amount'] + $param['fee'],
            'retail_outlet_name'=> $param['channel_code'],
            'name' => $param['name'],
            'expiration_date' => $exp_date,
            'key_production' => $param['key_production'],
            'key_development' => $param['key_development'],
            'is_demo' => $param['is_demo']
        );
        $create_xendit = $this->Func_model->CreateFixedPaymentCode($payload_xendit);

        $data_respon = json_decode($create_xendit['body'], true);
        if ($create_xendit['http_code'] == 200) {
           
            //  echo json_encode($data_respon);die();
            $data_trx = array(
                'invoice' => $no_trx,
                'tipe' => '1',
                'metode' => $param['channel_id'],
                'regtime' => date('Y-m-d H:i:s'),
                'id_user' => $param['id_user'],
                'saldo_awal' => $saldo_user->saldo,
                'nominal' => $param['amount'],
                'biaya' => $param['fee'],
                'total' => $param['amount'] + $param['fee'],
                'note' => $data_respon['status'],
                'reff' => $data_respon['channel_code'] . ' - '. $data_respon['payment_code'],
                'status' => 1,
                'is_demo' => $param['is_demo'],
                'payment_code' => $data_respon['payment_code'],
                'payment_reff' => $data_respon['id'],
                'expired_date' => $data_respon['expiration_date']
            );
            
            
            $verify = $this->db->insert('payment_transaksi', $data_trx);
            if($this->db->affected_rows() < 1){
                // log_message('error', '(1) resp xendit ' . $data_trx);
                $data->error = "Gagal meproses data (1)";
                return $data;
            }
                // echo $xendit->checkout_url;die();
            
            
            $data->insert_id = $this->db->insert_id();
            $data->data = $data_respon;
            
            return $data;
            
        } else {
       
            $data->error = $create_xendit['body'];
            return $data;
            
            
        }
    }
    
    function retail_paid_xendit($param)
    {
        $data = new stdClass();
        $this->db->select('trx.id_user, trx.nominal')
                ->from('payment_transaksi trx')
                ->where('invoice', $param->external_id)
                ->limit(1);

        $trx = $this->db->get()->row();
        if(count($trx) > 0){
            $cond_saldo = array('id_user' => $trx->id_user);
            $saldo = $this->get_saldo($cond_saldo);
            $saldo_akhir = $saldo->saldo + $trx->nominal;
            
            $cond = array('id' => $trx->id_user);
            $data_trx = array(
                'status' => 2,
                'note'=> $param->status,
                'modified' => date('Y-m-d H:i:s'),
                'saldo_awal' => $saldo->saldo,
                'saldo_akhir' => $saldo_akhir
            );
    
            $verify = $this->db->where('invoice', $param->external_id)
                            ->update('payment_transaksi', $data_trx);
            if($this->db->affected_rows() < 1){
                $data->error = "Gagal meproses data (1)";
                return $data;
            }
            $data->insert_id = $this->db->insert_id();
            
            
            
            // insert tabel wallet
            $datatopup = array(
                'id_user' => $trx->id_user,
                'rekening' => $param->retail_outlet_name,
                'bank' => 'dextrans',
                'nama_pemilik' => 'wallet',
                'type' => 'topup',
                'jumlah' => $trx->nominal,
                'status' => 1
            );
                            
            $this->Pelanggan_model->insertwallet($datatopup);
            // update saldo
            $data_update = array(
                    'saldo' => $saldo_akhir,
                    'update_at' => date('Y-m-d H:i:s')
            );
                    
            $this->db->where('nomor', $saldo->nomor);
            $this->db->update('saldo', $data_update);
            if($this->db->affected_rows() < 1){
                    $data->error = "Gagal meproses data (2)";
                    return $data;
            }
                    
            // send notif
            // $title = 'Saldoku';
            // $desc = 'Topup saldo anda sebesar Rp'.number_format($trx->amount,0,".",",").' berhasil.';
            // $this->Notification_model->send_notif_topup($title, $trx->id_user, $desc, $trx->invoice, $tokens);        
            
            if($this->db->affected_rows() < 1){
                $data->error = "Gagal meproses data (2)";
                return $data;
            }
        }else{
            log_message('error', ' trx not found');
            echo 'Error: trx not found';
        }
        


        return $data;
    }
    //-FUNCTION QUERY SALDO--------------------------------------------------------
    function min_saldo($id_user, $jumlah){
        $cond_id = array(
            'id_user' => $id_user
        );
        $bef_saldo = $this->get_saldo($cond_id);
        $aft_saldo = $bef_saldo->saldo-$jumlah;

        $this->db->set('saldo', $aft_saldo);
        $this->db->where('id_user', $id_user);

        return $this->db->update('saldo');
    }

    function plus_saldo($id_user, $jumlah){
        $cond_id = array(
            'id_user' => $id_user
        );
        $bef_saldo = $this->get_saldo($cond_id);
        $aft_saldo = $bef_saldo->saldo+$jumlah;

        $this->db->set('saldo', $aft_saldo);
        $this->db->where('id_user', $id_user);
        return $this->db->update('saldo');
    }

    //QR Payment Model ----------------------------------------------------------------------------------------------------------
    function get_data_qr_payment()
    {
        $this->db->select('qr_event.*, saldo.id_user, saldo.saldo');
        $this->db->join('saldo', 'qr_event.id = saldo.id_user');
        $this->db->order_by('qr_event.created_date', 'ASC');
        return  $this->db->get('qr_event')->result_array();
    }

    function get_qr_event_by_id($id){
        $this->db->where('id', $id);
        return $this->db->get('qr_event')->row_array();
    }

    function insert_qr_payment($data){
        /*
        $dataq = array(
        
            'id' => $data['id_qris'],
            'nama_event' => $data['nama_event'],
            $data['nominal'],
            $data['tipe'],
            $data['image_path'],
            $data['expired_path']
        );
        */
        $data_saldo = array(
            'id_user' => $data['id'],
            'saldo'   => 0
        );

        $this->db->insert('saldo', $data_saldo);
        return $this->db->insert('qr_event', $data);
    }

    function update_qr_payment($id, $data){
        $this->db->where('id', $id);
        return $this->db->update('qr_event', $data);
    }

    function delete_qr_payment_event($id){
        $this->db->where('id_user', $id);
        $this->db->delete('saldo');

        $this->db->where('id', $id);
        return $this->db->delete('qr_event');
    }

    function last_qr_item(){
        $this->db->get('qr_event');

        return $this->db->last_query();
    }

    function pay_qr_payment($id_user, $id_qris, $invoice){
        $data_qr = $this->get_qr_event_by_id($id_qris);
        $type_w  = 'QR Payment : '. $data_qr['nama_event'];
        
        $data_ins = array(
            'uuid'              => $id_qris,
            'invoice'           => $invoice,
            'id_user'           => $id_user,

            'jumlah'            => $data_qr['nominal'],
            'tujuan'            => $type_w,

            'bank'              => 'Saldo JPay',
            'rekening'          => 'JPay',
            'type'              => 'QR Payment',
            'status'            => 1
        );

        $this->db->insert('wallet', $data_ins);

        return TRUE;
    }

    function add_log_qr_payment($data){
        $this->db->insert('qr_event_history', $data);

    }

    function get_qr_event_history(){
        $this->db->select('qr_event_history.*, saldo.id_user, saldo.saldo, qr_event.id, qr_event.nama_event, pelanggan.id AS id_user_p, pelanggan.fullnama AS nama_user_p');
        
        $this->db->join('qr_event', 'qr_event_history.id_qr_event = qr_event.id', 'LEFT');
        $this->db->join('pelanggan', 'qr_event_history.id_user = pelanggan.id');
        $this->db->join('saldo', 'qr_event_history.id_user = saldo.id_user');

        $this->db->order_by('qr_event_history.regtime', DESC);

        return $this->db->get('qr_event_history')->result_array();
    }

    function get_detail_qr_event($id_qr){
        $this->db->select('qr_event_history.*, wallet.id_user as id_user_w, wallet.invoice as invoice_w,wallet.jumlah as jumlah_w pelanggan.id, pelanggan.fullnama as nama_user');

        $this->db->join('wallet', 'qr_event_history.invoice = wallet.invoice');
        $this->db->join('pelanggan', 'wallet.id_user = pelanggan.id');

        $this->db->where('qr_event_history.id_qr_event', $id_qr);
        $this->db->order_by('qr_event_history.regtime', DESC);

        return $this->db->get('qr_event_history')->result_array();
    }

    function get_report_qr_event(){
        $data_qr = $this->get_qr_event_history();
        foreach($data_qr as $qr){
            $data_report = array(
                'invoice'        => $qr['invoice'],
                'id_user_p'      => $qr['id_user_p'],
                'nama_user_p'    => $qr['nama_user_p'],
                'id_qr_event'    => $qr['id_qr_event'],
                'nama_event'     => $qr['nama_event'],
                'regtime'        => $qr['regtime']
            );
            
        }

        return $data_report;
    }
}