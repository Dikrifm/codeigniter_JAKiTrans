<?php

class Advertorial_model extends CI_model
{
    public function getall_advertorial()
    {
        // $this->db->select('config_driver.status as status_job');
        // $this->db->select('driver_job.driver_job');
        // $this->db->select('driver.*');
        // $this->db->join('config_driver', 'driver.id = config_driver.id_driver', 'left');

        $this->db->join('kategori_advertorial', 'advertorial.id_kategori = kategori_advertorial.id_kategori_adv', 'left');

        return  $this->db->get('advertorial')->result_array();
    }

    public function getallkategori_advertorial()
    {
        return  $this->db->get('kategori_advertorial')->result_array();
    }

    public function tambahdata_advertorial($data)
    {
        return $this->db->insert('advertorial', $data);
    }

    public function getadvertorialbyid($id)
    {
        return $this->db->get_where('advertorial', ['id_advertorial' => $id])->row_array();
    }

    public function hapuskategoribyId($id)
    {
        $this->db->where('id_kategori_adv', $id);
        $this->db->delete('kategori_advertorial');
    }
    public function hapusadvertorialbyid($id)
    {
        $this->db->where('id_advertorial', $id);
        $this->db->delete('advertorial');
    }

    public function ubahdata_advertorial($data)
    {

        $this->db->set('foto_advertorial', $data['foto_advertorial']);
        $this->db->set('title', $data['title']);
        $this->db->set('content', $data['content']);
        $this->db->set('id_kategori', $data['id_kategori']);
        $this->db->set('status_advertorial', $data['status_advertorial']);

        $this->db->where('id_advertorial', $data['id_advertorial']);
        $this->db->update('advertorial', $data);
    }

    public function tambahdatakategori($data)
    {
        $this->db->set('kategori', $data['kategori']);
        $this->db->insert('kategori_advertorial', $data);
    }

    
}
