<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vendors extends CI_Controller {

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
		
			$data["vendors"] = $this->contact_model->load_contacts($oid, 'Vendor');
			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Vendors";
			$ert['search'] = "true";
			$ert['search_placeholder'] = "Search Vendors";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/vendor/vendor', $data);
		} else {
			redirect(base_url().'distributors/Account/login');
		}

	}

	public function search_vendors() {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			print_r(json_encode($this->contact_model->search_contacts($oid, 'Vendor', $this->input->post('keywords'))));
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}

	public function add_vendor() {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;

			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Vendors";
			$ert['search'] = "true";
			$ert['search_placeholder'] = "Search Vendors";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/vendor/vendor_add');
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}

	public function save_vendor() {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;

			$dt = date('Y-m-d H:m:s');
			$did = $this->contact_model->save_contact($oid, $uid, $dt, $this->input->post('name'), $this->input->post('company'), $this->input->post('email'), $this->input->post('phone'), $this->input->post('address'), $this->input->post('gst'), 'Vendor');
			echo $did;
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}

	public function edit_vendor($did) {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		
			$data["edit_vendor"] = $this->contact_model->load_contacts($oid, 'Vendor', $did);
			$data['did'] = $did;

			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Edit Vendors";
			$ert['search'] = "true";
			$ert['search_placeholder'] = "Search Vendors";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/vendor/vendor_add', $data);
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}

	public function update_vendor($did) {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;

			$dt = date('Y-m-d H:m:s');
			$did = $this->contact_model->update_contact($oid, $uid, $did, $dt, $this->input->post('name'), $this->input->post('company'), $this->input->post('email'), $this->input->post('phone'), $this->input->post('address'), $this->input->post('gst'), 'Vendor');
			echo $did;
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}

	public function delete_vendor($did) {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$cid = $this->contact_model->delete_contact($oid, $did, 'Vendor');
			echo $did;
		} else {
			redirect(base_url().'distributors/Account/login');
		}	
	}
}