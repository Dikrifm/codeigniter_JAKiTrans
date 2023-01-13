<?php
defined('BASEPATH') or exit('No direct script access allowed');

class appnotification extends CI_Controller
{

    public function  __construct()
    {
        parent::__construct();



        if ($this->session->userdata('user_name') == NULL && $this->session->userdata('password') == NULL) {
            redirect(base_url() . "login");
        }
        $this->load->model('notification_model', 'notif');
        $this->load->model('Group_model', 'group');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $groupLevel = $this->session->userdata('role');
        $userId = $this->session->userdata('id');

        $data['menu'] = $this->group->get_menu_user($groupLevel);
        $data['allmenu'] = $this->group->get_all_menu();

        $this->load->view('includes/header', $data);
        $this->load->view('appnotification/index');
        $this->load->view('includes/footer');
    }

    public function send()
    {
        

        $topic = $this->input->post('topic');
        $title = $this->input->post('title');
        $message = $this->input->post('message');
        // FCM API Url
          $url = 'https://fcm.googleapis.com/fcm/send';
          $headers = array (
            'Authorization:key=' . $this->config->item('fcm_server'),
            'Content-Type:application/json'
          );
          $notifData = [
            'title' => $title,
            'body' => $message,
            'type' => 0
          ];
          $apiBody = [
            'notification' => $notifData,
            'data' => $notifData,
            "time_to_live" => 600,
            'to' => '/topics/'.$topic
          ];
          $ch = curl_init();
          curl_setopt ($ch, CURLOPT_URL, $url );
          curl_setopt ($ch, CURLOPT_POST, true );
          curl_setopt ($ch, CURLOPT_HTTPHEADER, $headers);
          curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true );
          curl_setopt ($ch, CURLOPT_POSTFIELDS, json_encode($apiBody));
          $result = curl_exec ( $ch );
          curl_close ( $ch );
       $this->notif->send_notif($title, $message, $topic);
        $this->session->set_flashdata('send', $result);
        redirect('appnotification/index');
    }
}
