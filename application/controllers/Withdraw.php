<?php
//defined('BASEPATH') or exit('No direct script access allowed');
class Withdraw extends CI_Controller{
    public function __construct()
    {
        parent::__construct();
        
        $this->load->helper("url");
        $this->load->database();
        $this->load->model('Pelanggan_model');
        $this->load->model('Payment_model', 'payment');
        $this->load->model('Merchantapi_model');
        $this->load->model('Driver_model');
        $this->load->model('Donasi_model');
        $this->load->model('Notification_model');
        $this->load->model('wallet_model', 'wallet');
        $this->load->model('func_model');
        date_default_timezone_set('Asia/Jakarta');

        if ($this->session->userdata('user_name') == NULL && $this->session->userdata('password') == NULL) {
            redirect(base_url() . "login");
        }
    }

    public function index(){
        $this->load->view('test_callback');
    }

    //EDIT detail_withdraw
    public function editpm($id_wallet){
        //$this->form_validation->set_rules('id_wallet', 'id_wallet', 'trim|prep_for_form');
        //$this->form_validation->set_rules('id_payment_method', 'id_payment_method', 'trim|prep_for_form');

        //GET Admin Session
        //$groupLevel = $this->session->userdata('role');
        //$userId = $this->session->userdata('id');
        /*
        $data['menu'] = $this->group->get_menu_user($groupLevel);
        $data['allmenu'] = $this->group->get_all_menu();

        //GET WALLET by ID_wallet
        $data_w               = $this->wallet->getwalletbyid($id_wallet);
        $data_wd['data_wd']   = $data_w; 

        //GET PAYMENT_METHOD
        $data_wd['data_pm']   = $this->payment->get_data_payment_method();

        //GET DATA_PELANGGAN
        $user_id   = [
            'id' => $data['id_user']
        ];
        $data_user = $this->Pelanggan_model->get_data_pelanggan($user_id); 
        /*
        if($this->form_validation->run() == TRUE){
            $dataupdate = [
                'id'                => html_escape($this->input->post('id', TRUE)),
                'id_payment_method' => html_escape($this->input->post('id_payment_method', TRUE))
            ];

            //UPDATE WALLET->id_payment_method WD
            $this->wallet->update_pm_wd($dataupdate);
            $this->session->set_flashdata('ubah', 'Data User : ' . $data_user['nama'] . ' => ' . 'Nama Pemilik Rekening : ' . $data['nama_pemilik'] . 'No. Rekening => ' . $data['rekening'] .' Has Been Edited');
            redirect(base_url('wallet'));


        }else{
        */
            $this->load->view('includes/header', $data);
            $this->load->view('withdraw/editdetailwd', $data);
            $this->load->view('includes/footer');
        //}
        
    }

    public function test_callback(){
        $this->load->view('test1');
    }

    //REQUEST->disbursement
    public function wd_post($id, $id_user, $jumlah){
        $title   = 'Request success';
        $message = 'Request Has Been sent';

        //GET Saldo->User
        $saldo = $this->wallet->getsaldo($id_user);

        //GET id_WALLET attribute
        $data = $this->wallet->getwalletbyid($id);

        //GET Payment_method
        $data_pm = $this->payment->get_data_payment_by_id($data['id_payment_method']);
        
        //GET & VALIDATION id_user
        $token         = $this->wallet->gettoken($id_user);
        $regid         = $this->wallet->getregid($id_user);
        $tokenmerchant = $this->wallet->gettokenmerchant($id_user);
        if ($token == NULL and $tokenmerchant == NULL and $regid != NULL) {
            $topic = $regid['reg_id'];
        } else if ($regid == NULL and $tokenmerchant == NULL and $token != NULL) {
            $topic = $token['token'];
        } else if ($regid == NULL and $token == NULL and $tokenmerchant != NULL) {
            $topic = $tokenmerchant['token_merchant'];
        }
        
        //SET Data Body API
        $bank_code           = $data_pm['channel_code'];
        $bank_account_name   = $data['rek_nama'];
        $bank_account_number = $data['rek_nomor'];
        $amount              = $jumlah - $data_pm['biaya'];

        //GET API key XENDIT at Payment_setting
        $this->db->where('id', 1);
        $this->db->where('status', 1);
        $setting = $this->db->get('payment_setting')->row();

        $key_production = $setting->key_production;
        $key_development = $setting->key_demo;
        $is_demo = $setting->is_demo;
        if($is_demo === TRUE){
            $demo = 'TRUE';
        }else{
            $demo = 'FALSE';
        }


        if($data_pm['status'] > 0){
            
            $datareq = array (
                'id_wallet'           => $id,
                'id_user'             => $id_user,
                'saldo_awal'          => $saldo['saldo'],

                'amount'              => $amount,
                'bank_code'           => $bank_code,
                'account_holder_name' => $bank_account_name,
                'account_number'      => $bank_account_number,
                'description'         => "disbursement from JAKiTrans",

                'key_production'      => $key_production,
                'key_development'     => $key_development,
                'is_demo'             => $demo,

                'id_pm'               => $data_pm['id'],
                'biaya_pm'            => $data_pm['biaya'],
                'jumlah_wd'           => $jumlah
            );
            
            //REQUEST Wd
            $data_respon           = $this->wallet->create_wd($datareq);
            

            $data_log['ceklog']    = $data_respon;
            $data_log['status_pm'] = $data_pm['status'];
            
            if(!empty($data_respon->error)){
                
                //echo 'error';
                $this->load->view('testerror', $data_log);
                
            }else{
                
                //$this->session->flashdata('ubah', 'Withdraw berhasil di proses, tunggu respon nya |' . $bank_code . ' nama rek : '. $bank_account_name);
                redirect(base_url('wallet'));
            }
        
        }else{

            $this->load->view('test1', $data);
        }


    }

    //GET REQUEST_WD
    public function get_wd($id_wallet){ 

        $respon  = $this->func_model->get_wd_xendit($id_wallet);
        $data['data_wd'] = json_decode($respon);

        $this->load->view('test1', $data);
    }

}