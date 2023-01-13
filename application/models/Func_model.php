<?php

defined('BASEPATH') OR exit('No direct script access allowed');
class Func_model extends CI_Model {
    public function __construct() {
        parent::__construct();
		$this->load->library('email');

		$this->load->model('wallet_model', 'wallet');
    }
    
	 //REQUEST API PAYMENT TOPUP WALLET
     public function ChargesEwallet($param){
       
		$is_production = $param['is_demo'] == 0 ? true : false;
        $api_key = $is_production ?
            $param['key_production'] :
            $param['key_development'];
            
		$api_url = "https://api.xendit.co/ewallets/charges";
		$callback_url = base_url()."api/payment/callback_ewallet";
		$redirect_url = base_url()."xendit";
		
		if($param['channel_code'] == 'ID_OVO'){
		    $properties = array(
		        'mobile_number' => $param['redirect']
		    );
		}else{
		    $properties = array(
		        'success_redirect_url' => $redirect_url
		    );
		}
		
		$data = array(
		    'reference_id'  => $param['reference_id'],
		    'currency'=>'IDR',
		    'amount' => intval($param['amount']),
		    'checkout_method' => 'ONE_TIME_PAYMENT',
		    'channel_code' => $param['channel_code'],
		    'channel_properties' => $properties
		    
		);
	
	    
		$fields = json_encode($data);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
                                                   'Accept: application/json'));
		curl_setopt($ch, CURLOPT_USERPWD, $api_key.":");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
		curl_setopt($ch, CURLOPT_TIMEOUT, 600); //timeout in seconds

        $respon_xendit = curl_exec($ch);
        $info = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		log_message('error', "charge xendit : " . json_encode($respon_xendit)." http code:". $info);
		
		$result = array(
		    'body'=> $respon_xendit,
		    'http_code' => $info
		);
		
		curl_close($ch);
		
		$ins_log = array(
		    'tipe'=> 2,
		    'request'=> $fields.'apikey:'.$api_key.'demo:'.$is_production.$param['is_demo'],
		    'respon'=> json_encode($result),
		    'datetime' => date('Y-m-d H:i:s')
		);
		
		$this->db->insert('payment_log_service', $ins_log);

		return $result;
	}
	
	public function CreateCallbackVirtualAccount($param){

        $is_production = $param['is_demo'] == 0 ? true : false;
        $api_key = $is_production ?
            $param['key_production'] :
            $param['key_development'];
		$api_url = "https://api.xendit.co/callback_virtual_accounts";
		
		$data = array(
		    'external_id'  => $param['external_id'],
		    'expiration_date'=> $param['expiration_date'],
		    'expected_amount' => intval($param['expected_amount']),
		    'bank_code' => $param['bank_code'],
		    'name' => $param['name'],
		    'is_single_use' => true,
		    
		);

		$fields = json_encode($data);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
                                                   'Accept: application/json'));
		curl_setopt($ch, CURLOPT_USERPWD, $api_key.":");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $result = array(
            'body' => curl_exec($ch),
            'http_code' => curl_getinfo($ch, CURLINFO_HTTP_CODE),
		  );

        
		curl_close($ch);

      
		return $result;
	}
	
	public function CreateFixedPaymentCode($param){
	    $is_production = $param['is_demo'] == 0 ? true : false;
        $api_key = $is_production ?
            $param['key_production'] :
            $param['key_development'];
		$api_url = "https://api.xendit.co/fixed_payment_code";
		$data = array(
		    'external_id'  => $param['external_id'],
		    'expiration_date'=> $param['expiration_date'],
		    'expected_amount' => intval($param['expected_amount']),
		    'retail_outlet_name' => $param['retail_outlet_name'],
		    'name' => $param['name'],
		    'is_single_use' => true,
		    
		);

		$fields = json_encode($data);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
                                                   'Accept: application/json'));
		curl_setopt($ch, CURLOPT_USERPWD, $api_key.":");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $result = array(
            'body' => curl_exec($ch),
            'http_code' => curl_getinfo($ch, CURLINFO_HTTP_CODE),
		  );

        
		curl_close($ch);

      
		return $result;
	}
	
	public function insert_otp($id){
        $otp = $this->randomNumber();
    	$data_otp = array(
    		'regtime' => date('Y-m-d H:i:s'),
    		'id_user' => $id,
    		'kode' => $otp,
    		'status' => 0

    	);

    	$signup = $this->db->insert('kode_otp', $data_otp);
    	if($signup){
    	    $condition = array(
    		    'id_user' => $id
    	    );
			
    	    $resData = $this->get_data_otp($condition);
    	    $result = array(
    	        'status' => '00',
    	        'otp' => $resData->row('kode')
    	    );
        
    	}else{
    	    $result = array(
    	        'status' => '01',
    	        'otp' => ''
    	    );
    	    
    	}
    	return $result;
    }
    
    function get_data_otp($condition){
    	$this->db->select('*');
        $this->db->from('kode_otp');
        $this->db->where($condition);
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        return $this->db->get();
    }

    function update_otp($id, $data){
    	$this->db->where('id', $id);
        $this->db->update('kode_otp', $data);
        return true;

    }

    function randomNumber() {
	    $result = '';
	    for($i = 0; $i < 6; $i++) {
	        $result .= mt_rand(0, 9);
	    }
	    return $result;

	}

	//SEND REQUEST Withdraw->Xendit
	public function charge_wd($param){
       
		$param['is_demo'] === 'TRUE' ? $is_production = 'TRUE' : $is_production = 'FALSE';
        if($is_production == 'FALSE'){
		 	$api_key = $param['key_production'];
		}else{
            $api_key = $param['key_development'];
			}
        
		$note	= $param['id_wallet'];
		
		$api_url = "https://api.xendit.co/disbursements";
		$callback_url = base_url()."api/withdraw/callback_wd";
		$redirect_url = base_url()."xendit";
		
		$data = array(
		    'external_id'         => $param['external_id'],
		    'amount' 			  => intval($param['amount']),

		    'bank_code'	    	  => $param['bank_code'],
		    'account_holder_name' => $param['account_holder_name'],
		    'account_number'      => $param['account_number'],
			
		    'description'         => strval($note)
		);
	
		$fields = json_encode($data);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
                                                   'Accept: application/json'));
		curl_setopt($ch, CURLOPT_USERPWD, $api_key.":");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
		curl_setopt($ch, CURLOPT_TIMEOUT, 600); //timeout in seconds

		//GENERATE Request->Withdraw
        $respon_xendit = curl_exec($ch);

		//GET INFO HTTP_CODE
        $info = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		log_message('error', "charge xendit : " . json_encode($respon_xendit)." http code:". $info);
		
		//END REQUEST
		curl_close($ch);
		
		$result = array(
		    'body'=> $respon_xendit,
		    'http_code' => $info
		);

		$ins_log = array(
		    'tipe'=> 4,
		    'request'   => $fields . 'apikey:' . $api_key . 'demo:' . $is_production . $param['is_demo'],
		    'respon'	=> json_encode($result),
		    'datetime'  => date('Y-m-d H:i:s')
		);
		
		//INSERT LOG_PAYMENT_SERVICE
		$this->db->insert('payment_log_service', $ins_log);

		//UPDATE SET invoice WALLET
		$status_w = 'PENDING';
		$this->wallet->updatestatusinvoicewallet($param['id_wallet'], $param['external_id'], $status_w);
		
		return $result;
	}

	//GET DATA DISBURSEMENT by external_id
	public function get_wd_xendit($id_wallet){

		$data = new stdClass();
		//GET WALLET 
		$data_w = $this->wallet->getwalletbyid($id_wallet);

		//GET API KEY
		$this->db->where('id', 1);
		$setting = $this->db->get('payment_setting')->row();

		$api_key = $setting->key_production;

		$api_url = "https://api.xendit.co/disbursements";

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $api_url . '?external_id=' . $data_w['invoice']);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
                                                   'Accept: application/json'));
		curl_setopt($ch, CURLOPT_USERPWD, $api_key.":");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
		curl_setopt($ch, CURLOPT_TIMEOUT, 600); //timeout in seconds

		//GENERATE REQUSET GET_WD
		$respon_xendit = curl_exec($ch);

        $info = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		
		//END REQUEST
		curl_close($ch);
		
		$result = array(
		    'body'	    => $respon_xendit,
		    'http_code' => $info
		);

		$ins_log = array(
		    'tipe'		=> 45,
		    'request'   => $id_wallet . 'apikey:' . $api_key,
		    'respon'	=> json_encode($result),
		    'datetime'  => date('Y-m-d H:i:s')
		);

		//INSERT LOG_PAYMENT_SERVICE
		$this->db->insert('payment_log_service', $ins_log);

		if($info == 200){
			return $respon_xendit;
		}else{
			return $data->error = 'get wd request error';
		}

	}
}