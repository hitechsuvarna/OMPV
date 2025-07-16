<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends CI_Controller {

	public function __construct()	{
		parent:: __construct();
		$this->load->database();
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->library('email');
		$this->load->model('distributors/Products_Model','product_model');
		$this->load->model('distributors/Contact_Model','contact_model');
		$this->load->dbforge();
	}
########## USER ACCOUNT, LOGIN, VERIFY AND LOGOUT ################
	public function index() {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			$rid = $sess_data['user_details'][0]->iu_ref;
			
			$block_cid = $sess_data['user_details'][0]->ic_id;
		    $block_q = $this->db->query("SELECT * FROM i_contacts WHERE ic_id='$block_cid' AND ic_owner='$oid'");
		    $block_r = $block_q->result();
		    
		    if($block_r[0]->ic_new_flag == "2") {
		        $this->session->unset_userdata('account_status');
        		$this->session->unset_userdata('user_details');
        		$this->session->unset_userdata('status');
        		
        		$data = array('account_status' => "blocked");
        	    $this->session->set_userdata($data);
        				
        		redirect(base_url().$this->config->item('dealer_login_red'));
		    }
			
			$query = $this->db->query("SELECT * FROM i_category WHERE ica_owner = '$oid' AND ica_parent_category=0");
			$result = $query->result();
			
			$data['category'] = $result;
			// for($i=0;$i<count($result);$i++) {
			//     $ct = $result[$i]->ica_id;
			//     $que = $this->db->query("SELECT * FROM i_products WHERE ip_category='$ct' LIMIT 1");
			//     $res = $que->result();
			    
			//     if(count($res) > 0) {
			//         array_push($data['category'], array('id' => $result[$i]->ica_id, 'name' => $result[$i]->ica_category_name, 'img' => base_url().'assets/uploads/'.$oid.'/'.$res[0]->ip_id.'/'.$res[0]->ip_image));
			//     }
			// }
			
			
			$data['oid'] = $oid;
			$data['ctid'] = 0;

			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Products";
			$ert['search'] = "true";
			$ert['search_placeholder'] = "Search Products";
			$ert['type'] = "dealers";
			
			$this->load->view('dealers/navbar', $ert);
			$this->load->view('dealers/products/products', $data);
		} else {
			redirect(base_url().$this->config->item('dealer_login_red'));
		}
	}

	public function load_category($ctid) {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$rid = $sess_data['user_details'][0]->iu_ref;
			
			$block_cid = $sess_data['user_details'][0]->ic_id;
		    $block_q = $this->db->query("SELECT * FROM i_contacts WHERE ic_id='$block_cid' AND ic_owner='$oid'");
		    $block_r = $block_q->result();
		    
		    if($block_r[0]->ic_new_flag == "2") {
		        $this->session->unset_userdata('account_status');
        		$this->session->unset_userdata('user_details');
        		$this->session->unset_userdata('status');
        		
        		$data = array('account_status' => "blocked");
        	    $this->session->set_userdata($data);
        				
        		redirect(base_url().$this->config->item('dealer_login_red'));
		    }
			
			
			$query = $this->db->query("SELECT * FROM i_products WHERE ip_owner='$oid' AND ip_category='$ctid'");
		    $result = $query->result();
		    
		    $data["products"] = [];
		    for($i=0;$i<count($result);$i++) {
		        $pid = $result[$i]->ip_id;
		        $que = $this->db->query("SELECT * FROM i_p_pricing WHERE ipp_c_id='$rid' AND ipp_p_id='$pid' AND ipp_owner='$oid'");
		        $res = $que->result();
		        if(count($res) > 0) {
		            array_push($data["products"], array("id" => $result[$i]->ip_id, "name" => $result[$i]->ip_name, "description" => '', "url" => base_url()."assets/uploads/".$oid."/".$result[$i]->ip_id."/".$result[$i]->ip_image, "price" => "Rs.".$res[0]->ipp_price."/-"));
		        } else {
		            array_push($data["products"], array("id" => $result[$i]->ip_id, "name" => $result[$i]->ip_name, "description" => '', "url" => base_url()."assets/uploads/".$oid."/".$result[$i]->ip_id."/".$result[$i]->ip_image, "price" => "N/A"));
		        }
		    }
		    
			$query = $this->db->query("SELECT * FROM i_category WHERE ica_parent_category='$ctid' AND ica_owner='$oid'");
            $data["category"] = $query->result();
            
            $query = $this->db->query("SELECT * FROM i_category WHERE ica_id='$ctid' AND ica_owner='$oid'");
            $data["current_category"] = $query->result();
            
            $data['oid'] = $oid;
            $data['ctid'] = $ctid;
            
			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Products";
			$ert['search'] = "true";
			$ert['search_placeholder'] = "Search Products";
			$ert['type'] = "dealers";
			
			$this->load->view('dealers/navbar', $ert);
			$this->load->view('dealers/products/products', $data);
		} else {
			redirect(base_url().'dealers/Account/login');
		}
    }
	
	public function update_cart() {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			
			$dt = date('Y-m-d H:m:s');

			$p = $this->input->post('p');
			$q = $this->input->post('q');
			for ($i=0; $i < count($p) ; $i++) {
				echo $this->product_model->update_cart($oid, $uid, $p[$i], $q[$i], null, $dt);
				echo "/";
			}
		} else {
			redirect(base_url().$this->config->item('dealer_login_red'));
		}
	}

	public function search_products($ctid=null) {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$rid = $sess_data['user_details'][0]->iu_ref;
			
			$name=$this->input->post('name');
			
			if($ctid==null) {
			    $query = $this->db->query("SELECT * FROM i_products WHERE ip_owner='$oid' AND ip_name LIKE '%$name%'");    
			} else {
			    $query = $this->db->query("SELECT * FROM i_products WHERE ip_owner='$oid' AND ip_name LIKE '%$name%' AND ip_category='$ctid'");
			}
		    
		    $result = $query->result();
		    
		    $data = [];
		    for($i=0;$i<count($result);$i++) {
		        $pid = $result[$i]->ip_id;
		        $que = $this->db->query("SELECT * FROM i_p_pricing WHERE ipp_c_id='$rid' AND ipp_p_id='$pid' AND ipp_owner='$oid'");
		        $res = $que->result();
		        if(count($res) > 0) {
		            array_push($data, array("id" => $result[$i]->ip_id, "name" => $result[$i]->ip_name, "description" => '', "url" => base_url()."assets/uploads/".$oid."/".$result[$i]->ip_id."/".$result[$i]->ip_image, "price" => "Rs.".$res[0]->ipp_price."/-"));
		        } else {
		            array_push($data, array("id" => $result[$i]->ip_id, "name" => $result[$i]->ip_name, "description" => '', "url" => base_url()."assets/uploads/".$oid."/".$result[$i]->ip_id."/".$result[$i]->ip_image, "price" => "N/A"));
		        }
		    }
		    
		    print_r(json_encode($data));
		} else {
			redirect(base_url().$this->config->item('dealer_login_red'));
		}
	}
	
	public function view_products($ctid) {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$rid = $sess_data['user_details'][0]->iu_ref;
			
			$block_cid = $sess_data['user_details'][0]->ic_id;
		    $block_q = $this->db->query("SELECT * FROM i_contacts WHERE ic_id='$block_cid' AND ic_owner='$oid'");
		    $block_r = $block_q->result();
		    
		    if($block_r[0]->ic_new_flag == "2") {
		        $this->session->unset_userdata('account_status');
        		$this->session->unset_userdata('user_details');
        		$this->session->unset_userdata('status');
        		
        		$data = array('account_status' => "blocked");
        	    $this->session->set_userdata($data);
        				
        		redirect(base_url().$this->config->item('dealer_login_red'));
		    }
			
			
		    $query = $this->db->query("SELECT * FROM i_products WHERE ip_owner='$oid' AND ip_category='$ctid'");
		    $result = $query->result();
		    
		    $data["products"] = [];
		    for($i=0;$i<count($result);$i++) {
		        $pid = $result[$i]->ip_id;
		        $que = $this->db->query("SELECT * FROM i_p_pricing WHERE ipp_c_id='$rid' AND ipp_p_id='$pid' AND ipp_owner='$oid'");
		        $res = $que->result();
		        if(count($res) > 0) {
		            array_push($data["products"], array("id" => $result[$i]->ip_id, "name" => $result[$i]->ip_name, "description" => '', "url" => base_url()."assets/uploads/".$oid."/".$result[$i]->ip_id."/".$result[$i]->ip_image, "price" => "Rs.".$res[0]->ipp_price."/-"));
		        } else {
		            array_push($data["products"], array("id" => $result[$i]->ip_id, "name" => $result[$i]->ip_name, "description" => '', "url" => base_url()."assets/uploads/".$oid."/".$result[$i]->ip_id."/".$result[$i]->ip_image, "price" => "N/A"));
		        }
		    }
		    
		    $data['ctid'] = $ctid;
		    $data['oid'] = $oid;
            
			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Products";
			$ert['search'] = "true";
			$ert['search_placeholder'] = "Search Products";
			$ert['type'] = "dealers";
			
			
			$this->load->view('dealers/navbar', $ert);
			$this->load->view('dealers/products/product_view', $data);
		} else {
			redirect(base_url().$this->config->item('dealer_login_red'));
		}
	}
}