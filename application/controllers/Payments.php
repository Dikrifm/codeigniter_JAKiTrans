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
        $this->load->library('Ciqrcode');

        $this->load->helper('download');
        $this->load->helper('url');
        //$this->load->helper('file');
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
            $config['upload_path']      = './images/promo/';
            $config['allowed_types']    = 'gif|jpg|png|jpeg';
            $config['max_size']         = '10000';
            $config['file_name']        = 'name';
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

    //QR Event Payment Method ----------------------------------------------------------------------------
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

    function print_qr($id){
        $groupLevel = $this->session->userdata('role');
        $userId = $this->session->userdata('id');

        $data['menu'] = $this->group->get_menu_user($groupLevel);
        $data['allmenu'] = $this->group->get_all_menu();

        $data = $this->payment->get_qr_event_by_id($id);

        $this->load->view('includes/header', $data);
        $this->load->view('payment/zoom_qr', $data);
        $this->load->view('includes/footer');
    }

    function insert_qr(){
    
        if(!empty($_POST)){
            
            $id_qr = 'qr-'. date('yHmids');
            $image_name = $id_qr . '.png';
            
            $data_ins = array(
                'id'            => $id_qr,
                'nama_event'    => $this->input->post('nama_event'),
                'nominal'       => $this->input->post('nominal'),        
                'tipe'          => $this->input->post('tipe'),
                'status'        => $this->input->post('status'),
                'expired_date'  => $this->input->post('expired_date'),
                'image_path'    => $image_name
            );

            $this->payment->insert_qr_payment($data_ins); //simpan ke database

            $qr_json = json_encode($data_ins);

            $params['data'] = $qr_json; 
            $params['level'] = 'H'; //H=High
            $params['size'] = 10;
            $params['savename'] = FCPATH.'images/qr/'.$image_name; 
            
            $this->ciqrcode->generate($params); 
            

            redirect('payments/qr');
        }else{
            $this->load->view('includes/header', $data);
            $this->load->view('payment/addqr', $data);
            $this->load->view('includes/footer');
        }
    }

    function edit_qr($id){
        $data = $this->payment->get_qr_event_by_id($id);

        if(!empty($_POST)){

            $id_qr = html_escape($this->input->post('id'), TRUE);

            $data_update = array(
                
                'nama_event'    => $this->input->post('nama_event'),
                'nominal'       => $this->input->post('nominal'),        
                'tipe'          => $this->input->post('tipe'),
                'status'        => $this->input->post('status'),
                'expired_date'  => $this->input->post('expired_date')
                
            );
            $this->session->set_flashdata('ubah', 'Data QR Event berhasil di ubah');
            $this->payment->update_qr_payment($id_qr, $data_update);

            redirect('payments/qr');
        }else{
            $this->load->view('includes/header', $data);
            $this->load->view('payment/editqr', $data);
            $this->load->view('includes/footer');
        }
    }

    function delete_qr($id){
        $image_status = "";

        $data = $this->payment->get_qr_event_by_id($id);
        unlink('images/qr/'. $data['image_path']);
        
        if(file_exists(base_url().'images/qr/'.$data['image_path'])){
            unlink('images/qr/'. $data['image_path']);
            
        }else{
            $image_status =  "x101";
        }

        $this->payment->delete_qr_payment_event($id);
        
        $this->session->set_flashdata('hapus', 'QR Event :' . $data['nama_event'] . '( '. $data['id']. ' )' .' Berhasil dihapus! '.$image_status);
        redirect('payments/qr');
    }
    
    function qrcode(){
        /* 
        param 
        (1)qrcontent,
        (2)filename,
        (3)errorcorrectionlevel,
        (4)pixelwidth,
        (5)margin,
        (6)saveandprint,
        (7)forecolor,
        (8)backcolor 
        */

        QRcode::png(
            "cekcek",
            base_url('images/test1.png'),
            "H",
            20, 
            4,
            0,
            "0,255,0",
            "255,255,255",
            false
        );
    }
    
    function reportqr(){
        $groupLevel = $this->session->userdata('role');
        $userId = $this->session->userdata('id');

        $data['menu'] = $this->group->get_menu_user($groupLevel);
        $data['allmenu'] = $this->group->get_all_menu();
        $data['qr_data'] = $this->payment->get_data_qr_payment();

        $this->load->view('includes/header', $data);
        $this->load->view('payment/report_qr', $data);
        $this->load->view('includes/footer');
    }
}