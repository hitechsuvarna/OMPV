<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	public function __construct()	{
		parent:: __construct();
		$this->load->database();
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->library('email');
		$this->load->model('distributors/Transaction_Model','txn_model');
		$this->load->dbforge();
	}
########## USER ACCOUNT, LOGIN, VERIFY AND LOGOUT ################
	public function index() {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$rid = $sess_data['user_details'][0]->iu_ref;
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
		    $q=$this->db->query("SELECT * FROM i_users WHERE iu_owner='$oid' AND iu_id='$uid'");
			if($q->result()[0]->iu_status=="blocked") {
			    $this->session->unset_userdata('user_details');
        		$this->session->unset_userdata('status');
        		$this->session->unset_userdata('fy');
        		redirect(base_url().'distributors/Account/login');
			}
			
			if($q->result()[0]->iu_logout=="true") {
			    $this->session->unset_userdata('user_details');
        		$this->session->unset_userdata('status');
        		$this->session->unset_userdata('fy');
        		redirect(base_url().'distributors/Account/login');
			}
			
			if(!$sess_data['fy']) {
				redirect(base_url().'distributors/Account/settings');
			}
			$unm = $sess_data['user_details'][0]->iu_username;
			
			$query = $this->db->query("SELECT * FROM i_users WHERE iu_username='$unm' AND iu_owner='$oid'");
			$result = $query->result();
			
			if(count($result) > 0) {
			    
			} else {
			    redirect(base_url().'distributors/Account/logout');
			}

			$fy = $sess_data['fy'][0]->ify_start_date;
			$ty = $sess_data['fy'][0]->ify_end_date;
			

			$data['oid'] = $oid;
			$data['rid'] = $rid;
			$dt = date('Y-m-d');
			
			$query = $this->db->query("SELECT it_id, it_amount, it_order_txn FROM i_txns WHERE it_type='Sale' AND it_status IN ('invoiced','paid') AND it_owner='$oid' AND it_date BETWEEN '$fy' AND '$ty' AND it_date='$dt'");
			$result = $query->result(); $tot=0;
			for($i=0;$i<count($result); $i++) {
			    $tot+=$result[$i]->it_amount;
			}
			$data['sale_amount'] = $tot;
			
			$tot=0;
			$query = $this->db->query("SELECT it_id, it_amount FROM i_txns WHERE it_type='Delivery' AND it_status IN ('delivery','shipping','approved') AND it_owner='$oid' AND it_date BETWEEN '$fy' AND '$ty' AND it_date='$dt'");
			$result1 = $query->result();
			for($i=0;$i<count($result1); $i++) {
			    if(count($result) > 0) {
			        for($j=0;$j<count($result);$j++){
    			        if($result1[$i]->it_id != $result[$j]->it_order_txn) {
    			            $tot+=$result1[$i]->it_amount;
    			        }  
    			    }       
			    } else {
			        $tot+=$result1[$i]->it_amount;
			    }
			}
			
			$data['delivery_amount'] = $tot;
			
			$query = $this->db->query("SELECT SUM(it_amount) AS a FROM i_txns WHERE it_type='Orders' AND it_status IN ('pending') AND it_owner='$oid' AND it_date BETWEEN '$fy' AND '$ty'");
			$data['order_pending_amount'] = $query->result();
			
			$data['pending'] = $this->txn_model->load_transactions($oid, 'Sale', 'pending', null, $fy, $ty);
			$x = $this->txn_model->load_inventory_summary($oid, $rid, null, null, null);
			$data['order_products'] = [];

			for ($i=0; $i < count($x); $i++) { 
				if ($x[$i]['balance'] <= $x[$i]['threshold']) {
					array_push($data['order_products'], $x[$i]);
				}
			}
			
			$query = $this->db->query("SELECT ica_id, ica_category_name FROM i_category WHERE ica_id IN (SELECT a.ip_category FROM i_products AS a LEFT JOIN i_inventory AS b ON a.ip_id=b.ii_p_id WHERE a.ip_lower_limit > b.ii_balance AND a.ip_owner='$oid')");
			$data['categories'] = $query->result();

			$ert['name'] = $sess_data['user_details'][0]->ic_name;
			$ert['title'] = "Home";
			$ert['search'] = "true";
			$ert['search_placeholder'] = "Search Products";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/home/home', $data);
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}

    public function new_order_notify() {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$query = $this->db->query("SELECT * FROM i_txns WHERE it_type = 'Orders' AND it_owner='$oid' AND it_notify='pending'");
			echo count($query->result());
		}
    }
    
    public function new_dealer_notify() {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$query = $this->db->query("SELECT * FROM i_contacts WHERE ic_section = 'Dealer' AND ic_owner='$oid' AND ic_new_flag='1'");
			echo count($query->result());
		}
    }
    
    public function get_product_category_child($ctid) {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			
			if($ctid == 0) {
			    $query = $this->db->query("SELECT ica_id, ica_category_name FROM i_category WHERE ica_id IN (SELECT a.ip_category FROM i_products AS a LEFT JOIN i_inventory AS b ON a.ip_id=b.ii_p_id WHERE a.ip_lower_limit > b.ii_balance AND a.ip_owner='$oid')");
			} else {
			    $query = $this->db->query("SELECT * FROM i_category WHERE ica_parent_category='$ctid' AND ica_owner='$oid'");
			}
		
			$data['category'] = $query->result();
			
			$query = $this->db->query("SELECT ica_parent_category FROM i_category WHERE ica_id='$ctid' AND ica_owner='$oid'");
			if(count($query->result()) > 0) {
			    $data['parent'] = $query->result()[0]->ica_parent_category;
			} else {
			    $data['parent'] = 0;
			}
			
			$query = $this->db->query("SELECT ip_id, ip_name, ip_lower_limit, ip_category, (SELECT (SUM(ii_inward) - SUM(ii_outward)) AS balance FROM i_inventory WHERE ii_p_id=a.ip_id) AS bal FROM `i_products` AS a WHERE a.ip_category='$ctid' AND a.ip_owner='$oid'");
			$data['product'] = $query->result();
			
			
			print_r(json_encode($data));
		}
    }
    
    
}