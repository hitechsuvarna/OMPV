<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Portal extends CI_Controller {

	public function __construct()	{
		parent:: __construct();
		$this->load->database();
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->dbforge();
	}
########## LOGIN, VERIFY AND LOGOUT ################
	
	public function distributor_creation() {

		$ert['name'] = "Hitesh";			
		$ert['title'] = "Distributor Creation";
		$ert['search'] = "false";
		$ert['search_placeholder'] = "Search Products";
		$ert['type'] = "portal";
		
		
		$this->load->view('portal/navbar', $ert);
		$this->load->view('portal/distributor_creation');
	}

	public function save_distributor() {
        $dt = date('Y-m-d H:m:s');
        $uid = 0;
        
		$data = array(
			'ic_name' => $this->input->post('name'),
			'ic_company' => $this->input->post('company'),
			'ic_phone' => $this->input->post('phone'),
			'ic_email' => $this->input->post('email'),
			'ic_address' => $this->input->post('address'),
			'ic_gst_number' => $this->input->post('gst'),
			'ic_section' => 'Distributor'
		);
		$this->db->insert('i_contacts', $data);
		$refid = $this->db->insert_id();

		$data = array(
			'iu_username' => $this->input->post('email'),
			'iu_type' => 'distributor',
			'iu_ref' => $refid,
			'iu_status' => 'password_update',
			'iu_subscription_start' => $this->input->post('start'),
			'iu_subscription_end' => $this->input->post('end'),
			'iu_logo' => ''
		);
		$this->db->insert('i_users', $data);
		$oid = $this->db->insert_id();

		$data = array(
			'iua_u_id' => $oid,
			'iua_u_products' => 'true',
			'iua_u_pricing' => 'true',
			'iua_u_dealers' => 'true',
			'iua_u_vendors' => 'true',
			'iua_u_orders' => 'true',
			'iua_u_delivery' => 'true',
			'iua_u_inventory' => 'true',
			'iua_u_purchase' => 'true',
			'iua_u_expenses' => 'true',
			'iua_u_invoice' => 'true',
			'iua_u_credit_note' => 'true',
			'iua_u_debit_note' => 'true',
			'iua_u_payments' => 'true',
			'iua_u_tax' => 'true',
			'iua_u_users' => 'true',
			'iua_u_godown' => 'true',
			'iua_u_ledgers' => 'true',
			'iua_u_settings' => 'true',
			'iua_u_analyze' => 'true',
			'iua_owner' => $oid,
			'iua_created' => $dt,
			'iua_created_by' => $uid				
			 );
		$this->db->insert('i_u_access', $data);
		

		$data = array('iu_owner' => $oid);
		$this->db->where('iu_id', $oid);
		$this->db->update('i_users', $data);

		$data = array('ic_owner' => $oid);
		$this->db->where('ic_id', $refid);
		$this->db->update('i_contacts', $data);

	}

	public function list_all() {
		$query = $this->db->query("SELECT * FROM i_users");


		$ert['name'] = "Hitesh";			
		$ert['title'] = "Distributor Creation";
		$ert['search'] = "false";
		$ert['search_placeholder'] = "Search Products";
		$ert['type'] = "portal";
		
		
		$this->load->view('portal/navbar', $ert);
		$this->load->view('portal/distributor_creation');
	}

}