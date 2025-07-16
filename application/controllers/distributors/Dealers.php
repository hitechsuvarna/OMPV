<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dealers extends CI_Controller {

	public function __construct()	{
		parent:: __construct();
		$this->load->database();
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->library('email');
		$this->load->model('distributors/Contact_Model','contact_model');
		$this->load->dbforge();
	}
########## PRODUCTS ################
	public function index() {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		    $uid = $sess_data['user_details'][0]->iu_id;
		    $q=$this->db->query("SELECT * FROM i_users WHERE iu_owner='$oid' AND iu_id='$uid'");
			if($q->result()[0]->iu_status=="blocked") {
			    $this->session->unset_userdata('user_details');
        		$this->session->unset_userdata('status');
        		$this->session->unset_userdata('fy');
        		redirect(base_url().'distributors/Account/login');
			}
			
		    $query = $this->db->query("SELECT * FROM i_contacts WHERE ic_owner='$oid' AND ic_section='Dealer' ORDER BY ic_new_flag DESC, ic_id");
			$data["dealers"] = $query->result();
			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Dealers";
			$ert['search'] = "true";
			$ert['search_placeholder'] = "Search Dealers";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/dealer/dealer', $data);
		} else {
			redirect(base_url().'distributors/Account/login');
		}

	}

	public function search_dealers() {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			print_r(json_encode($this->contact_model->search_contacts($oid, 'Dealer', $this->input->post('keywords'))));
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}

	public function add_dealer() {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;

			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Dealers";
			$ert['search'] = "true";
			$ert['search_placeholder'] = "Search Dealers";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/dealer/dealer_add');
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}

    public function dealer_exists() {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$d=$this->input->post('d');
			
			$query = $this->db->query("SELECT * FROM i_contacts WHERE ic_name='$d' AND ic_owner='$oid'");
			if(count($query->result()) > 0) {
			    echo 'true';
			} else {
			    echo 'false';
			}
		}
    }
	public function save_dealer() {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;

			$dt = date('Y-m-d H:m:s');
			$did = $this->contact_model->save_contact($oid, $uid, $dt, $this->input->post('name'), $this->input->post('company'), $this->input->post('email'), $this->input->post('phone'), $this->input->post('address'), $this->input->post('gst'), 'Dealer', $this->input->post('credit'));
			$this->contact_model->create_user($oid, $did, 'dealer', $this->input->post('email'), $sess_data['user_details'][0]->iu_subscription_start, $sess_data['user_details'][0]->iu_subscription_end, $dt);
			
			echo $did;
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}

	public function edit_dealer($did) {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		
			$data["edit_dealer"] = $this->contact_model->load_contacts($oid, 'Dealer', $did);
			
			$query = $this->db->query("SELECT * FROM i_users WHERE iu_ref='$did' AND iu_owner='$oid'");
			$result = $query->result();
			if(count($result) > 0) {
			    $uid = $result[0]->iu_id;    
			} else {
			    $uid = 0;
			}
			
			
			$query = $this->db->query("SELECT * FROM i_d_users WHERE idu_dealer='$uid' AND idu_owner='$oid'");
			$data["edit_sub_dealer"] = $query->result();
			$data['did'] = $did;

			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Edit Dealers";
			$ert['search'] = "true";
			$ert['search_placeholder'] = "Search Dealers";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/dealer/dealer_add', $data);
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}

	public function update_dealer($did) {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;

			$dt = date('Y-m-d H:m:s');
			$did = $this->contact_model->update_contact($oid, $uid, $did, $dt, $this->input->post('name'), $this->input->post('company'), $this->input->post('email'), $this->input->post('phone'), $this->input->post('address'), $this->input->post('gst'), 'Dealer', $this->input->post('credit'));
			echo $did;
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}
	
	public function update_flag($did) {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			
			$this->db->where(array('ic_id' => $did, 'ic_owner' => $oid, 'ic_section' => 'Dealer'));
			$this->db->update('i_contacts', array('ic_new_flag' => $this->input->post('f')));
			
			$this->db->where(array('iu_ref' => $did, 'iu_owner' => $oid, 'iu_type' => 'dealer'));
			if($this->input->post('f') == 0) {
			    $this->db->update('i_users', array('iu_status' => 'active'));    
			} else if($this->input->post('f') == 2) {
			    $this->db->update('i_users', array('iu_status' => 'blocked'));    
			} 
			
			
			echo $did;
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}

	public function delete_dealer($did) {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$cid = $this->contact_model->delete_contact($oid, $did, 'Dealer');
			echo $did;
		} else {
			redirect(base_url().'distributors/Account/login');
		}	
	}
}