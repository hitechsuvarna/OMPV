<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inventory extends CI_Controller {

	public function __construct()	{
		parent:: __construct();
		$this->load->database();
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->library('email');
		$this->load->model('distributors/Contact_Model','contact_model');
		$this->load->model('distributors/Products_Model','product_model');
		$this->load->model('distributors/Transaction_Model','txn_model');
		$this->load->dbforge();
	}
	
########## INVENTORY NEW ################
	public function inventory_new() {
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
			
			
			$query = $this->db->query("SELECT * FROM i_category WHERE ica_owner='$oid' AND ica_parent_category='0'");
			$data['categories'] = $query->result();
			
			$query = $this->db->query("SELECT * FROM i_products WHERE ip_owner='$oid'");
			$data['products'] = $query->result();
			
			$query = $this->db->query("SELECT * FROM i_inventory_accounts WHERE iia_owner='$oid'");
			$data['accounts'] = $query->result();
			
			$query = $this->db->query("SELECT * FROM i_inventory_accounts WHERE iia_star='1' AND iia_owner='$oid'");
			$r = $query->result();
			if(count($r) > 0) {
			    $data['default_account'] = $r[0]->iia_name;
			    $data['default_account_id'] = $r[0]->iia_id;
			}
			
			
			
			$data['oid'] = $oid;
            $data["products"] = $this->product_model->load_products($oid);
            $data["vendors"] = $this->contact_model->load_contacts($oid);
            
			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Inventory";
			$ert['search'] = "true";
			$ert['search_placeholder'] = "Search Vendors";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/inventory/inventory2', $data);
		} else {
			redirect(base_url().'distributors/Account/login');
		}		
	}

	
} ?>