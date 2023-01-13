<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Wallet extends CI_Controller
{

    public function  __construct()
    {
        parent::__construct();
       
        if ($this->session->userdata('user_name') == NULL && $this->session->userdata('password') == NULL) {
            redirect(base_url() . "login");
        }
        // $this->load->library('form_validation');
        $this->load->model('wallet_model', 'wallet');
        $this->load->model('Group_model', 'group');
        $this->load->model('users_model', 'user');
    }

    public function index()
    {
        $groupLevel   = $this->session->userdata('role');
        $userId       = $this->session->userdata('id');

        $data['menu']    = $this->group->get_menu_user($groupLevel);
        $data['allmenu'] = $this->group->get_all_menu();

        $data['jumlahdiskon'] = $this->wallet->getjumlahdiskon();
        $data['orderplus']    = $this->wallet->gettotalorderplus();
        $data['ordermin']     = $this->wallet->gettotalordermin();
        $data['withdraw']     = $this->wallet->gettotalwithdraw();
        $data['topup']        = $this->wallet->gettotaltopup();
        $data['saldo']        = $this->wallet->getallsaldo();
        $data['currency']     = $this->user->getcurrency();
        $data['wallet']       = $this->wallet->getwallet();

        $this->load->view('includes/header', $data);
        $this->load->view('wallet/index', $data);
        $this->load->view('includes/footer');
    }

    //WITHDRAW GENERATOR
    public function wconfirm($id, $id_user, $amount)
    {
        $token = $this->wallet->gettoken($id_user);
        $regid = $this->wallet->getregid($id_user);
        $tokenmerchant = $this->wallet->gettokenmerchant($id_user);

        if ($token == NULL and $tokenmerchant == NULL and $regid != NULL) {
            $topic = $regid['reg_id'];
        } else if ($regid == NULL and $tokenmerchant == NULL and $token != NULL) {
            $topic = $token['token'];
        } else if ($regid == NULL and $token == NULL and $tokenmerchant != NULL) {
            $topic = $tokenmerchant['token_merchant'];
        }

        $title = 'Withdraw Success';
        $message_success = 'Withdraw Has Been Confirmed';
        $saldo = $this->wallet->getsaldo($id_user);

        //WITHDRAW GENERATOR
        $this->wallet->ubahsaldo($id_user, $amount, $saldo);
        $this->wallet->ubahstatuswithdrawbyid($id);
        $this->wallet->send_notif($title, $message_success, $topic);
        $this->session->set_flashdata('ubah', $message_success);
        redirect('wallet/index');
    }

    public function wcancel($id, $id_user)
    {
        $token = $this->wallet->gettoken($id_user);
        $regid = $this->wallet->getregid($id_user);
        $tokenmerchant = $this->wallet->gettokenmerchant($id_user);

        if ($token == NULL and $tokenmerchant == NULL and $regid != NULL) {
            $topic = $regid['reg_id'];
        } else if ($regid == NULL and $tokenmerchant == NULL and $token != NULL) {
            $topic = $token['token'];
        } else if ($regid == NULL and $token == NULL and $tokenmerchant != NULL) {
            $topic = $tokenmerchant['token_merchant'];
        }

        $title = 'Withdraw Cancel';
        $message = 'Withdraw Has Been Canceled';

        $this->wallet->cancelstatuswithdrawbyid($id);
        $this->wallet->send_notif($title, $message, $topic);
        $this->session->set_flashdata('ubah', 'Withdraw Has Been Canceled');
        redirect('wallet/index');
    }

    public function tconfirm($id, $id_user, $amount)
    {
        $token = $this->wallet->gettoken($id_user);
        $regid = $this->wallet->getregid($id_user);
        $tokenmerchant = $this->wallet->gettokenmerchant($id_user);

        if ($token == NULL and $tokenmerchant == NULL and $regid != NULL) {
            $topic = $regid['reg_id'];
        } else if ($regid == NULL and $tokenmerchant == NULL and $token != NULL) {
            $topic = $token['token'];
        } else if ($regid == NULL and $token == NULL and $tokenmerchant != NULL) {
            $topic = $tokenmerchant['token_merchant'];
        }

        $title = 'Topup success';
        $message = 'We Have Confirmed Your Topup';
        $saldo = $this->wallet->getsaldo($id_user);



        $this->wallet->ubahsaldotopup($id_user, $amount, $saldo);
        $this->wallet->ubahstatuswithdrawbyid($id);
        $this->wallet->send_notif($title, $message, $topic);
        $this->session->set_flashdata('ubah', 'topup has been confirmed');
        redirect('wallet/index');
    }

    public function tcancel($id, $id_user)
    {
        $token = $this->wallet->gettoken($id_user);
        $regid = $this->wallet->getregid($id_user);
        $tokenmerchant = $this->wallet->gettokenmerchant($id_user);

        if ($token == NULL and $regid != NULL) {
            $topic = $regid['reg_id'];
        }

        if ($regid == NULL and $token != NULL) {
            $topic = $token['token'];
        }

        if ($regid == NULL and $token == NULL and $tokenmerchant != NULL) {
            $topic = $tokenmerchant['token_merchant'];
        }

        $title = 'Topup canceled';
        $message = 'Sorry, topup has been canceled';

        $this->wallet->cancelstatuswithdrawbyid($id);
        $this->wallet->send_notif($title, $message, $topic);
        $this->session->set_flashdata('ubah', 'topup has been canceled');
        redirect('wallet/index');
    }

    //TOPUP GENERATOR
    public function tambahtopup()
    {
        $groupLevel = $this->session->userdata('role');
        $userId = $this->session->userdata('id');

        $data['menu'] = $this->group->get_menu_user($groupLevel);
        $data['allmenu'] = $this->group->get_all_menu();
        $data['currency'] = $this->user->getcurrency();
        $data['saldo'] = $this->wallet->getallsaldouser();


        if ($_POST != NULL) {


            if ($this->input->post('type_user') == 'pelanggan') {
                $id_user = $this->input->post('id_pelanggan');
            } elseif ($this->input->post('type_user') == 'mitra') {
                $id_user = $this->input->post('id_mitra');
            } else {
                $id_user = $this->input->post('id_driver');
            }

            $saldo = html_escape($this->input->post('saldo', TRUE));
            $remove = array(".", ",");
            $add = array("", "");

            $data = [
                'id_user'                       => $id_user,
                'saldo'                         => str_replace($remove, $add, $saldo),
                'type_user'                     => $this->input->post('type_user')
            ];


            $this->wallet->updatesaldowallet($data);
            $this->session->set_flashdata('ubah', 'Top Up Has Been Added');
            redirect('wallet');
        } else {
            $this->load->view('includes/header', $data);
            $this->load->view('wallet/tambahtopup', $data);
            $this->load->view('includes/footer');
        }
    }

    //WITHDRAW GENERATOR
    public function tambahwithdraw()
    {
        $groupLevel = $this->session->userdata('role');
        $userId = $this->session->userdata('id');

        $data['menu'] = $this->group->get_menu_user($groupLevel);
        $data['allmenu'] = $this->group->get_all_menu();
        $data['currency'] = $this->user->getcurrency();
        $data['saldo'] = $this->wallet->getallsaldouser();

        if ($_POST != NULL) {


            if ($this->input->post('type_user') == 'pelanggan') {
                $id_user = $this->input->post('id_pelanggan');
            } elseif ($this->input->post('type_user') == 'mitra') {
                $id_user = $this->input->post('id_mitra');
            } else {
                $id_user = $this->input->post('id_driver');
            }


            $saldo = html_escape($this->input->post('saldo', TRUE));
            $remove = array(".", ",");
            $add = array("", "");

            $data = [
                'id_user'                       => $id_user,
                'saldo'                         => str_replace($remove, $add, $saldo),
                'type_user'                     => $this->input->post('type_user')
            ];

            $data2 = [
                'bank'                          => $this->input->post('bank'),
                'nama_pemilik'                  => $this->input->post('nama_pemilik'),
                'rekening'                      => $this->input->post('rekening'),
            ];


            $this->wallet->updatesaldowalletwithdraw($data, $data2);
            $this->session->set_flashdata('ubah', 'Withdraw Has Been Added');
            redirect('wallet');
        } else {
            $this->load->view('includes/header', $data);
            $this->load->view('wallet/tambahwithdraw', $data);
            $this->load->view('includes/footer');
        }
    }

    //EDIT detail_withdraw
    public function editpm($id_wallet){
        //$data['id'] = $id_wallet;
        
        //$this->form_validation->set_rules('id_wallet', 'id_wallet', 'trim|prep_for_form');
        //$this->form_validation->set_rules('id_payment_method', 'id_payment_method', 'trim|prep_for_form');

        //GET Admin Session
        $groupLevel = $this->session->userdata('role');
        $userId = $this->session->userdata('id');
        
        $data_menu['menu']    = $this->group->get_menu_user($groupLevel);
        $data_menu['allmenu'] = $this->group->get_all_menu();

        //GET WALLET by ID_wallet
        $data_w               = $this->wallet->getwalletbyid($id_wallet);
        $data_page['data_wd'] = $data_w;

        //GET PAYMENT_METHOD
        $data_page['data_pm']   = $this->payment->get_data_payment_method();

        /*
        //GET DATA_PELANGGAN
        $user_id   = [
            'id' => $data_w['id_user']
        ];
        $data_page['user'] = $this->Pelanggan_model->get_data_pelanggan($user_id); 
        */
        if($_POST != NULL){
            
            $id_payment_method = $this->input->post('id_payment_method');

            //UPDATE WALLET->id_payment_method WD
            $this->wallet->update_pm_wd($id_wallet, $id_payment_method);

            //$this->session->set_flashdata('ubah', 'Data User : ' . $data_user['nama'] . ' => ' . 'Nama Pemilik Rekening : ' . $data['nama_pemilik'] . 'No. Rekening => ' . $data['rekening'] .' Has Been Edited');
            redirect(base_url('wallet'));

        }else{ 
            
            $this->load->view('includes/header', $data_menu);
            $this->load->view('wallet/editdetailwd', $data_page);
            $this->load->view('includes/footer');
        }
        
    }

    public function testcatch($id){
        $data_test['datacek'] = $this->input->post('id_payment_method');

        $this->load->view('includes/header');
        $this->load->view('testcatch', $data_test);
        $this->load->view('includes/footer');
    }
}
