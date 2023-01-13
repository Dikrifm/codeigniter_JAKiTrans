<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Advertorial extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        
        if ($this->session->userdata('user_name') == NULL && $this->session->userdata('password') == NULL) {
            redirect(base_url() . "login");
        }
        $this->load->model('Advertorial_model', 'advertorial');
        $this->load->model('Group_model', 'group');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $groupLevel = $this->session->userdata('role');
        $userId = $this->session->userdata('id');

        $data['menu'] = $this->group->get_menu_user($groupLevel);
        $data['allmenu'] = $this->group->get_all_menu();
        $data['advertorial'] = $this->advertorial->getall_advertorial();
        $data['kategori'] = $this->advertorial->getallkategori_advertorial();
        
        $this->load->view('includes/header', $data);
        $this->load->view('advertorial/index', $data);
        $this->load->view('includes/footer');
    }

    public function hapuscategory($id)
    {
        if (demo == TRUE) {
            $this->session->set_flashdata('demo', 'NOT ALLOWED FOR DEMO');
            redirect('advertorial/index');
        } else {
            $this->advertorial->hapuskategoribyId($id);
            $this->session->set_flashdata('hapus', 'Category Advertorial Has Been Deleted');
            redirect('advertorial');
        }
    }


    public function tambah()
    {
        $groupLevel = $this->session->userdata('role');
        $userId = $this->session->userdata('id');

        $data['menu'] = $this->group->get_menu_user($groupLevel);
        $data['allmenu'] = $this->group->get_all_menu();
        $this->form_validation->set_rules('title', 'title', 'trim|prep_for_form');
        $this->form_validation->set_rules('id_kategori', 'id_kategori', 'trim|prep_for_form');


        if ($this->form_validation->run() == TRUE) {
            $config['upload_path']      = './images/berita/';
            $config['allowed_types']    = 'gif|jpg|png|jpeg';
            $config['max_size']         = '10000';
            $config['file_name']        = 'name';
            $config['encrypt_name']     = true;
            $this->load->library('upload', $config);

            if ($this->upload->do_upload('foto_advertorial')) {
                $gambar = html_escape($this->upload->data('file_name'));
            } else {
                $gambar = 'noimage.jpg';
            }

            $data = [
                'foto_advertorial'           => $gambar,
                'title'                      => html_escape($this->input->post('title', TRUE)),
                'content'                    => $this->input->post('content', TRUE),
                'id_kategori'                => html_escape($this->input->post('id_kategori', TRUE)),
                'status_advertorial'         => html_escape($this->input->post('status_advertorial', TRUE))
            ];

            if (demo == TRUE) {
                $this->session->set_flashdata('demo', 'NOT ALLOWED FOR DEMO');
                redirect('advertorial/tambah');
            } else {

                $this->advertorial->tambahdata_advertorial($data);
                $this->session->set_flashdata('tambah', 'Advertorial Has Been Added');
                redirect('advertorial');
            }
        } else {
            $data['kadv'] = $this->advertorial->getallkategori_advertorial();
            $this->load->view('includes/header', $data);
            $this->load->view('advertorial/addadvertorial', $data);
            $this->load->view('includes/footer');
        }
    }

    /*
    public function tambah()
    {
        $groupLevel = $this->session->userdata('role');
        $userId = $this->session->userdata('id');

        $data['menu'] = $this->group->get_menu_user($groupLevel);
        $data['allmenu'] = $this->group->get_all_menu();
        $this->form_validation->set_rules('title', 'title', 'trim|prep_for_form');
        $this->form_validation->set_rules('id_kategori', 'id_kategori', 'trim|prep_for_form');


        if ($this->form_validation->run() == TRUE) {
            $config['upload_path']     = './images/advertorial/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size']         = '10000';
            $config['file_name']     = 'name';
            $config['encrypt_name']     = true;
            $this->load->library('upload', $config);

            if ($this->upload->do_upload('foto_advertorial')) {
                $gambar = html_escape($this->upload->data('file_name'));
            } else {
                $gambar = 'noimage.jpg';
            }

            $data             = [
                'foto_advertorial'           => $gambar,
                'title'                      => html_escape($this->input->post('title', TRUE)),
                'content'                    => $this->input->post('content', TRUE),
                'id_kategori'                => html_escape($this->input->post('id_kategori', TRUE)),
                'status_advertorial'         => html_escape($this->input->post('status_advertorial', TRUE))
            ];

            if (demo == TRUE) {
                $this->session->set_flashdata('demo', 'NOT ALLOWED FOR DEMO');
                redirect('advertorial/tambah');
            } else {

                $this->advertorial->tambahdata_advertorial($data);
                $this->session->set_flashdata('tambah', 'advertorial Has Been Added');
                redirect('advertorial');
            }
        } else {
            $data['advertorial'] = $this->advertorial->getallkategori_advertorial();
            $this->load->view('includes/header', $data);
            $this->load->view('advertorial/addadvertorial', $data);
            $this->load->view('includes/footer');
        }
    }
    */

    public function hapus($id)
    {
        if (demo == TRUE) {
            $this->session->set_flashdata('demo', 'NOT ALLOWED FOR DEMO');
            redirect('advertorial/index');
        } else {
            $data = $this->advertorial->getadvertorialbyid($id);
            if ($data['foto_advertorial'] != 'noimage.jpg') {
                $gambar = $data['foto_advertorial'];
                unlink('images/advertorial/' . $gambar);
            }
            $this->advertorial->hapusadvertorialbyid($id);
            $this->session->set_flashdata('hapus', 'Advertorial Has Been Deleted');
            redirect('advertorial');
        }
    }

    public function ubah($id)
    {
        $groupLevel = $this->session->userdata('role');
        $userId = $this->session->userdata('id');

        $data['menu'] = $this->group->get_menu_user($groupLevel);
        $data['allmenu'] = $this->group->get_all_menu();
        $this->form_validation->set_rules('title', 'title', 'trim|prep_for_form');
        $this->form_validation->set_rules('id_kategori', 'id_kategori', 'trim|prep_for_form');


        $data['advertorial'] = $this->advertorial->getadvertorialbyid($id);
        $id  = html_escape($this->input->post('id_advertorial', TRUE));

        
        if ($this->form_validation->run() == TRUE) {
            $config['upload_path']     = './images/berita/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size']         = '10000';
            $config['file_name']     = 'name';
            $config['encrypt_name']     = true;
            $this->load->library('upload', $config);

            if ($this->upload->do_upload('foto_advertorial')) {
                if ($data['advertorial']['foto_advertorial'] != 'noimage.jpg') {
                    $gambar = $data['advertorial']['foto_advertorial'];
                    unlink('images/berita/' . $gambar);
                }

                $gambar = html_escape($this->upload->data('file_name'));
            } else {
                $gambar = $data['advertorial']['foto_advertorial'];
            }
            $data             = [
                'id_advertorial'                     => html_escape($this->input->post('id_advertorial', TRUE)),
                'foto_advertorial'                   => $gambar,
                'title'                              => html_escape($this->input->post('title', TRUE)),
                'content'                            => $this->input->post('content'),
                'id_kategori'                        => html_escape($this->input->post('id_kategori', TRUE)),
                'status_advertorial'                 => html_escape($this->input->post('status_advertorial', TRUE))
            ];
            //CHECKPOINT!!!
            if (demo == TRUE) {
                $this->session->set_flashdata('demo', 'NOT ALLOWED FOR DEMO');
                redirect('advertorial/index');
            } else {

                $this->advertorial->ubahdata_advertorial($data);
                $this->session->set_flashdata('ubah', 'Advertorial Has Been Changed');
                redirect('advertorial');
            }
        } else {
            $data['kadv'] = $this->advertorial->getallkategori_advertorial();


            $this->load->view('includes/header', $data);
            $this->load->view('advertorial/editadvertorial', $data);
            $this->load->view('includes/footer');
        }
    }

    public function tambahcategory()
    {
        $groupLevel = $this->session->userdata('role');
        $userId = $this->session->userdata('id');

        $data['menu'] = $this->group->get_menu_user($groupLevel);
        $data['allmenu'] = $this->group->get_all_menu();
        $this->form_validation->set_rules('kategori', 'kategori', 'trim|prep_for_form');

        if ($this->form_validation->run() == TRUE) {

            $data             = [
            'kategori'                => html_escape($this->input->post('kategori', TRUE))
            ];

            if (demo == TRUE) {
                $this->session->set_flashdata('demo', 'NOT ALLOWED FOR DEMO');
                redirect('advertorial/index');
            } else {
                $this->advertorial->tambahdatakategori($data);
                $this->session->set_flashdata('tambah', 'Has Been added');
                redirect('advertorial');
            }
        } else {
            $this->load->view('includes/header', $data);
            $this->load->view('advertorial/addcategory');
            $this->load->view('includes/footer');
        }
    }
}
