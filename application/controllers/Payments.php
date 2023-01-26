<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Payments extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        if ($this->session->userdata('user_name') == NULL && $this->session->userdata('password') == NULL) {
            redirect(base_url() . "login");
        }
        $this->load->model('Payment_model', 'payment');
        $this->load->model('Group_model', 'group');
        $this->load->library('form_validation');

        $this->load->library('ciqrcode');
    }
    
    public function index()
    {
        $groupLevel = $this->session->userdata('role');
        $userId = $this->session->userdata('id');

        $data['menu'] = $this->group->get_menu_user($groupLevel);
        $data['allmenu'] = $this->group->get_all_menu();
        $data['transaksi'] = $this->payment->get_data_payment_transaksi();
        // echo json_encode($data);die();
        $this->load->view('includes/header', $data);
        $this->load->view('payment/index', $data);
        $this->load->view('includes/footer');
    }
    
    public function success(){
       $this->load->view('payment/success_payment');
    }
    
    public function method()
    {
        $groupLevel = $this->session->userdata('role');
        $userId = $this->session->userdata('id');

        $data['menu'] = $this->group->get_menu_user($groupLevel);
        $data['allmenu'] = $this->group->get_all_menu();
    
        $data['payment'] = $this->payment->get_data_payment_method();
        $this->load->view('includes/header', $data);
        $this->load->view('payment/method', $data);
        $this->load->view('includes/footer');
    }
    
    public function tambah()
    {
        $this->form_validation->set_rules('keterangan', 'keterangan', 'trim|prep_for_form');
        $this->form_validation->set_rules('nama', 'nama', 'trim|prep_for_form');
        $this->form_validation->set_rules('tipe', 'tipe', 'trim|prep_for_form');
        $this->form_validation->set_rules('status', 'status', 'trim|prep_for_form');
        
        $groupLevel = $this->session->userdata('role');
        $userId = $this->session->userdata('id');

        $data['menu'] = $this->group->get_menu_user($groupLevel);
        $data['allmenu'] = $this->group->get_all_menu();
        if ($this->form_validation->run() == TRUE) {
            $config['upload_path']     = './images/promo/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size']         = '10000';
            $config['file_name']     = 'name';
            $config['encrypt_name']     = true;
            $this->load->library('upload', $config);

            if ($this->upload->do_upload('logo')) {
                $gambar = html_escape($this->upload->data('file_name'));
            } else {
                $gambar = 'noimage.png';
            }

            $tipe       = html_escape($this->input->post('tipe', TRUE));
            $jenis      = html_escape($this->input->post('jenis', TRUE));
            $nama       = html_escape($this->input->post('nama', TRUE));
            $keterangan = html_escape($this->input->post('keterangan', TRUE));
            $biaya      = html_escape($this->input->post('biaya', TRUE));
            $channel    = html_escape($this->input->post('channel_code', TRUE));
            $bank       = html_escape($this->input->post('nama_bank', TRUE));
            $rekening   = html_escape($this->input->post('no_rekening', TRUE));
            $atasnama   = html_escape($this->input->post('nama_rekening', TRUE));
            
            if($tipe < 2 ){
                $data = [
                    'tipe'          => $tipe,
                    'jenis'         => $jenis,
                    'nama'          => $nama,
                    
                    'bank'          => $bank,
                    'no_rekening'   => $rekening,
                    'nama_rekening' => $atasnama,
                    'channel_code'  => $channel,
                    'biaya'         => $biaya,

                    'keterangan'    => $keterangan,
                    'image'         => $gambar,
                    
                    'status'        => html_escape($this->input->post('status', TRUE)),
                    'regtime'       => date('Y-m-d H:i:s')
                ];
            }else{
                $data = [
                    'tipe'          => $tipe,
                    'jenis'         => 4,
                    'nama'          => $nama,
                    
                    'bank'          => $bank,
                    'channel_code'  => $channel,
                    'biaya'         => $biaya,
    
                    'keterangan'    => $keterangan,
                    'image'         => $gambar,
                    
                    'status'        => html_escape($this->input->post('status', TRUE)),
                    'regtime'       => date('Y-m-d H:i:s')
                ];  
            }
            
            $this->payment->insert_data_payment($data);
            $this->session->set_flashdata('ubah', 'Data' . $nama . ' => ' . $channel . 'Has Been Added');
            redirect('payments/method');
            
        }else{
            $this->load->view('includes/header', $data);
            $this->load->view('payment/addpayment');
            $this->load->view('includes/footer');
        }
    }
    
    public function ubah($id)
    {
        $data = $this->payment->get_data_payment_by_id($id);
        //$id = html_escape($this->input->post('id', TRUE));

        $this->form_validation->set_rules('keterangan', 'keterangan', 'trim|prep_for_form');
        $this->form_validation->set_rules('nama', 'nama', 'trim|prep_for_form');
        $this->form_validation->set_rules('tipe', 'tipe', 'trim|prep_for_form');
        $this->form_validation->set_rules('status', 'status', 'trim|prep_for_form');

        $groupLevel = $this->session->userdata('role');
        $userId = $this->session->userdata('id');

        $data['menu'] = $this->group->get_menu_user($groupLevel);
        $data['allmenu'] = $this->group->get_all_menu();
        
        if ($this->form_validation->run() == TRUE) {
            $config['upload_path']      = './images/promo/';
            $config['allowed_types']    = 'gif|jpg|png|jpeg';
            $config['max_size']         = '10000';
            $config['file_name']        = 'name';
            $config['encrypt_name']     = true;

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('logo')) {
                if ($data['image'] != 'noimage.png') {
                    $gambar = $data['image'];
                    unlink('images/promo/' . $gambar);
                }

                $gambar = html_escape($this->upload->data('file_name'));
            } else {
                $gambar = $data['image'];
            }
            
            $tipe       = html_escape($this->input->post('tipe', TRUE));
            $jenis      = html_escape($this->input->post('jenis', TRUE));
            $nama       = html_escape($this->input->post('nama', TRUE));
            $keterangan = html_escape($this->input->post('keterangan', TRUE));
            $biaya      = html_escape($this->input->post('biaya', TRUE));
            $channel    = html_escape($this->input->post('channel_code', TRUE));
            $bank       = html_escape($this->input->post('nama_bank', TRUE));
            $rekening   = html_escape($this->input->post('no_rekening', TRUE));
            $atasnama   = html_escape($this->input->post('nama_rekening', TRUE));
            
            if($tipe <2 ){
                $dataupdate = [
                    'id'            => $id,
                    'tipe'          => $tipe,
                    'jenis'         => $tipe == '1' ? $jenis : 0,
                    'nama'          => $nama,
                    'keterangan'    => $keterangan,
                    'bank'          => $bank,
                    'no_rekening'   => $rekening,
                    'nama_rekening' => $atasnama,
                    'image'         => $gambar,
                    'channel_code'  => $channel,
                    'biaya'         => $biaya,
                    'status'        => html_escape($this->input->post('status', TRUE)),
                ];
            }else{
                $dataupdate = [
                    'id'            => $id,
                    'tipe'          => $tipe,
                    'jenis'         => 4,
                    'nama'          => $nama,
                    
                    'bank'          => $bank,
                    'channel_code'  => $channel,
                    'biaya'         => $biaya,
    
                    'keterangan'    => $keterangan,
                    'image'         => $gambar,
                    
                    'status'        => html_escape($this->input->post('status', TRUE)),
                    'regtime'       => date('Y-m-d H:i:s')
                ];
            }

            // echo json_encode($data);die();
            $this->payment->update_data_payment($dataupdate);
            $this->session->set_flashdata('ubah', 'Data ' . $nama . ' => ' . $channel . ' Has Been Edited');
            redirect('payments/method');
            
            
        }else{
            $this->load->view('includes/header', $data);
            $this->load->view('payment/editpayment', $data);
            $this->load->view('includes/footer');
        }
    }
    
    function hapus($id)
    {
        $data = $this->payment->get_data_payment_by_id($id);
        if ($data['image'] != 'noimage.jpg') {
            $gambar = $data['image'];
            unlink('images/promo/' . $gambar);
        }

        $this->payment->hapus_data_payment($id);
        $this->session->set_flashdata('hapus', 'Data Has Been deleted');
        redirect('payments/method');
    }
    
    function setting()
    {
        
        $data = $this->payment->get_setting_payment();
        $groupLevel = $this->session->userdata('role');
        $userId = $this->session->userdata('id');

        $data['menu'] = $this->group->get_menu_user($groupLevel);
        $data['allmenu'] = $this->group->get_all_menu();
        $this->form_validation->set_rules('nama', 'nama', 'trim|prep_for_form');
        $this->form_validation->set_rules('key_demo', 'key_demo', 'trim|prep_for_form');
        $this->form_validation->set_rules('key_production', 'key_production', 'trim|prep_for_form');
        if ($this->form_validation->run() == TRUE) {
            $data = [
                'nama' => html_escape($this->input->post('nama', TRUE)),
                'key_demo' => html_escape($this->input->post('key_demo', TRUE)),
                'key_production' => html_escape($this->input->post('key_production', TRUE)),
                'is_demo' => html_escape($this->input->post('is_demo', TRUE)),
                'status' => html_escape($this->input->post('status', TRUE))
            ];
            
            $this->payment->update_data_payment_setting($data);
            $this->session->set_flashdata('ubah', 'Data Has Been Edited');
            redirect('payments/setting');
            
        }else{
            $this->load->view('includes/header', $data);
            $this->load->view('payment/setting', $data);
            $this->load->view('includes/footer');
        }
    }

    //QR Payment Method ----------------------------------------------------------------------------
    function qr(){
        $groupLevel = $this->session->userdata('role');
        $userId = $this->session->userdata('id');

        $data['menu'] = $this->group->get_menu_user($groupLevel);
        $data['allmenu'] = $this->group->get_all_menu();
        $data['qr_data'] = $this->payment->get_data_qr_payment();

        $this->load->view('includes/header', $data);
        $this->load->view('payment/qr', $data);
        $this->load->view('includes/footer');
    }

    function insert_qr(){
    
    if(!empty($_POST)){

        $data_ins = array(
            'nama_event'    => $this->input->post('nama_event'),
            'nominal'       => $this->input->post('nominal'),        
            'tipe'          => $this->input->post('tipe'),
            'status'        => $this->input->post('status'),
            'expired_date'  => $this->input->post('expired_date') 
        );
        $this->payment_model->insert_qr_payment($data_ins); //simpan ke database
        /*
        $qr = $this->payment_model->last_qr_item()->result();

        $image_name = $qr->id . '.png'; //buat name dari qr code sesuai dengan nim
        
        $qr_body = array(
            "id"           => $qr->id,
            "nama_event"   => $qr->nama_event,
            "nominal"      => $qr->nominal,
            "tipe"         => $qr->tipe,
            "status"       => $qr->status,
            "expired_date" => $qr->expired_date 
        );

        $qr_json = json_encode($qr_body);

        $params['data'] = $qr_json; //data yang akan di jadikan QR CODE
        $params['level'] = 'H'; //H=High
        $params['size'] = 10;
        $params['savename'] = FCPATH.'asset/images/qr/'.$image_name; //simpan image QR CODE ke folder assets/images/
        $this->ciqrcode->generate($params); // fungsi untuk generate QR CODE
        */

        redirect('payments/qr');
    }else{
        $this->load->view('includes/header', $data);
        $this->load->view('payment/addqr', $data);
        $this->load->view('includes/footer');
    }

    }

    function QRcode(){

        $kodenya = '{
            "id" : "2",
            "id_saldo" : "1000", 
            "nama_event" : "test QR 1",
            "nominal" : "100023",	
            "tipe" : "STATIS",
            "status" : "1",
            "qrstring" : "test QRSTR",
            "expired_date" : "2024-01-01"
        }';

        QRcode::png(

            $kodenya,
            $outfile = false,
            $level = QR_ECLEVEL_H,
            $size = 5,
            $margin = 2

        );
    }
}