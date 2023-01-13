<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
class Withdraw extends REST_Controller{
    public function __construct()
    {
        parent::__construct();
        
        $this->load->helper("url");
        $this->load->database();
        $this->load->model('Wallet_model', 'wallet');
        $this->load->model('Pelanggan_model');
        $this->load->model('Payment_model', 'payment');
        $this->load->model('Merchantapi_model');
        $this->load->model('Driver_model');
        $this->load->model('Donasi_model');
        $this->load->model('Notification_model');
        $this->load->model('func_model');
        date_default_timezone_set('Asia/Jakarta');

    }

    public function index(){
        $this->load->view('nodata');
    }

    

    public function callback_wd_post(){

        $input = file_get_contents("php://input");
        $data  = json_decode($input, true);
        $data_wd['callback_wd'] = $data;
        
        $invoice = $data['external_id'];

        log_message('error','Callback Withdraw (Disbursement) : ' . json_encode($data));

        $callback_log= array(
            'data'    => $input,
            'regtime' => date('Y-m-d H:i:s')
        );

        //INSERT DATA CALLBACK
        $this->wallet->insertcallback($callback_log);
        
        //GET WALLET USER
        $data_w = $this->wallet->getwalletbyinvoice($invoice);
        $id_user = $data_w['id_user'];

        //GET SALDO USER
        $saldo = $this->wallet->getsaldo($id_user);

        $id_wallet = intval($data['disbursement_description']);
        
        //GET DATA PAYMENT_METHOD->BIAYA;
        $data_pm = $this->payment->get_data_payment_by_id($data_w['id_payment_method']);
        
        
        if($data['status'] == 'COMPLETED'){
            $note = 'SUCCEEDED';
            //$status = 1;

            //TOTAL WITHDRAW
            $amount      = $data['amount'];
            $saldo_akhir = $saldo['saldo'] - ($amount + $data_pm['biaya']);

            //UPDATE SALDO USER
            $this->wallet->ubahsaldo($id_user, $saldo_akhir);

            //UPDATE STATUS & SALDO_AKHIR PAYMENT_TRANSKASI
            $this->wallet->updatetransaksiwd($invoice, $saldo_akhir, $note);

            //UPDATE STATUS WALLET  
            $this->wallet->updatestatusinvoicewallet($id_wallet, $invoice, $note);
            
            $this->load->view('test1', $data_wd);
            //redirect(base_url('withdraw'));
            
        }elseif($data['status'] == 'FAILED'){
            $status = 2;

            $note = 'FAILED';

            //UPDATE STATUS WALLET
            $this->wallet->updatestatusinvoicewallet($id_wallet, $invoice, $note);

            $this->load->view('test1', $data_wd);
            //redirect(base_url('withdraw'));
        }else{
            $this->load->view('test1', $data_wd);
            //redirect(base_url('withdraw/test_callback'));
        }
        // $update_trans['data_callback'] = $this->Payment_model->update_trans_ewallet_callback($req);
        // $data = $this->Payment_model->success_top_ewallet_xendit($req);
        // $resp['message'] = 'success';
        // $resp['data'] = $data;

        // if(!empty($data->error)){
        //     $resp['message'] = $data->error;
        //     $resp['data'] = null;
        // }

        // echo $input;
        //$this->wallet->ubahsaldo($id_user, $amount, $saldo);
        //$this->load->view('welcome_message', $update_trans);
        
    }

    
    
}