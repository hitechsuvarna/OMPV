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
		$this->load->model('distributors/Products_Model','product_model');
		$this->load->dbforge();
	}
########## USER ACCOUNT, LOGIN, VERIFY AND LOGOUT ################
// 	public function index($oid) {
// 		$sess_data = $this->session->userdata();
// 		if(isset($sess_data['user_details'][0])) {
		    
// 		    $block_cid = $sess_data['user_details'][0]->ic_id;
// 		    $block_q = $this->db->query("SELECT * FROM i_contacts WHERE ic_id='$block_cid' AND ic_owner='$oid'");
// 		    $block_r = $block_q->result();
		    
// 		    if($block_r[0]->ic_new_flag == "2") {
// 		        $this->session->unset_userdata('account_status');
//         		$this->session->unset_userdata('user_details');
//         		$this->session->unset_userdata('status');
        		
//         		$data = array('account_status' => "blocked");
//         	    $this->session->set_userdata($data);
        				
//         		redirect(base_url().$this->config->item('dealer_login_red'));
// 		    }
// 			$rid = $sess_data['user_details'][0]->iu_ref;
// 			$uid = $sess_data['user_details'][0]->iu_id;

//             $oid = $sess_data['user_details'][0]->iu_owner;
            
            
//             $query = $this->db->query("SELECT SUM(it_amount) AS amt FROM i_txns WHERE it_owner='$oid' AND it_type IN ('Sale', 'Delivery','Credit Note') AND it_c_id='$rid'");
//             $sl_amt = $query->result()[0]->amt;
//             $data['sale_amount'] = $query->result();
			
// 			$query = $this->db->query("SELECT (SUM(itp_credit) - SUM(itp_debit)) AS amt FROM i_txn_payments WHERE itp_owner='$oid' AND itp_c_id='$rid'");
// 			$pd_amt = $query->result()[0]->amt;
//             $data['paid_amt'] = $query->result();
			
// 			$data['outstanding'] = $sl_amt - $pd_amt;
// // 			print_r($data);
// 			$data['pending'] = $this->txn_model->load_transactions_dealer($oid, 'done', $rid);
			
// 			$query = $this->db->query("SELECT * FROM `i_products` AS a LEFT JOIN i_category AS b ON a.ip_category=b.ica_id WHERE ip_category IS NOT NULL GROUP BY ip_category ORDER BY ip_created DESC LIMIT 10");
// 			$data['new_arrival'] = $query->result();
			
// 			$data['oid'] = $oid;

// 			$ert['name'] = $sess_data['user_details'][0]->ic_name;
			
// 			$ert['title'] = "Home";
// 			$ert['search'] = "false";
// 			$ert['search_placeholder'] = "Search Products";
// 			$ert['type'] = "dealers";
		
// 			$this->load->view('dealers/navbar', $ert);
// 			$this->load->view('dealers/home/home', $data);
// 		} else {
// 			redirect(base_url().$this->config->item('dealer_login_red'));
// 		}
// 	}
	
	public function index($oid) {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
		    
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
			$rid = $sess_data['user_details'][0]->iu_ref;
			$uid = $sess_data['user_details'][0]->iu_id;
			$oid = $sess_data['user_details'][0]->iu_owner;
			
			$q = $this->db->query("SELECT * FROM i_category WHERE ica_parent_category='0' AND ica_owner='$oid'");
            $r = $q->result();
            
            $data['category'] = [];
            for($i=0;$i<count($r);$i++) {
                $c = $r[$i]->ica_id;
                $q1 = $this->db->query("SELECT * FROM i_category WHERE ica_parent_category = '$c' AND ica_owner='$oid'");
                $r1 = $q1->result();
                $arr=[];
                for($j=0;$j<count($r1);$j++) {
                    array_push($arr, array(
                            'child_id' => $r1[$j]->ica_id,
                            'child_name' => $r1[$j]->ica_category_name,
                            'child_image' => $r1[$j]->ica_img
                            ));
                }
                array_push($data['category'], array(
                    'parent_id' => $r[$i]->ica_id,
                    'parent_name' => $r[$i]->ica_category_name,
                    'parent_image' => $r[$i]->ica_img,
                    'child' => $arr
                    ));
            }
            $data['oid'] = $oid;
            
            $data['orders']=[];
            $q = $this->db->query("SELECT * FROM i_txns WHERE it_type='Orders' AND it_owner='$oid' AND it_c_id='$rid' AND it_status='pending'");
            $r = $q->result();
            for($i=0;$i<count($r);$i++) {
                array_push($data['orders'], array(
                    'id' => $r[$i]->it_id,
                    'txn_no' => $r[$i]->it_txn_no,
                    'date' => $r[$i]->it_date,
                    'amount' => $r[$i]->it_amount,
                    'status' => $r[$i]->it_status,
                ));
            }
            
            $data['delivery']=[];
            $q = $this->db->query("SELECT * FROM i_txns WHERE it_type='Delivery' AND it_owner='$oid' AND it_c_id='$rid'");
            $r = $q->result();
            for($i=0;$i<count($r);$i++) {
                array_push($data['delivery'], array(
                    'id' => $r[$i]->it_id,
                    'txn_no' => $r[$i]->it_txn_no,
                    'date' => $r[$i]->it_date,
                    'amount' => $r[$i]->it_amount,
                    'status' => $r[$i]->it_status,
                ));
            }
            
            
            
            $ert['name'] = $sess_data['user_details'][0]->ic_name;
			$ert['title'] = "Home";
			$ert['search'] = "false";
			$ert['search_placeholder'] = "Search Products";
			$ert['type'] = "dealers";
            
            $this->load->view('dealers/navbar', $ert);
            $this->load->view('dealers/loader');
            $this->load->view('dealers/home/home_new', $data);
		} else {
			redirect(base_url().$this->config->item('dealer_login_red'));
		}
	}
		
    public function load_category($cid=null) {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
		    $rid = $sess_data['user_details'][0]->iu_ref;
			$uid = $sess_data['user_details'][0]->iu_id;
			$oid = $sess_data['user_details'][0]->iu_owner;
			
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
		    } else {
		        if($cid==null) {
		            $k = $sess_data['search'];
		            $data['category'] = [];
                    
		            $data['products'] = [];
                    $q = $this->db->query("SELECT * FROM i_products WHERE (ip_name LIKE '%$k%' OR ip_description LIKE '%$k%') AND ip_owner='$oid'");
		            $result = $q->result();
                    for($i=0;$i<count($result);$i++) {
        		        $pid = $result[$i]->ip_id;
        		        $que = $this->db->query("SELECT * FROM i_u_cart WHERE iuc_u_id='$uid' AND iuc_u_owner='$oid' AND iuc_u_p_id='$pid' AND iuc_owner='$oid'");
        		        if(count($que->result()) > 0) {
        		            $bal = $que->result()[0]->iuc_u_qty;
        		        } else {
        		            $bal = 0;
        		        }
        		        
        		        $que = $this->db->query("SELECT * FROM i_p_pricing WHERE ipp_c_id='$rid' AND ipp_p_id='$pid' AND ipp_owner='$oid'");
        		        $res = $que->result();
        		        if(count($res) > 0) {
        		            if($result[$i]->ip_image == "") {
        		                array_push($data["products"], array("id" => $result[$i]->ip_id, "cart" => $bal, "name" => $result[$i]->ip_name, "description" => '', "url" => base_url()."assets/images/placeholder-car.png", "price" => "Rs.".$res[0]->ipp_price."/-"));    
        		            } else {
        		                array_push($data["products"], array("id" => $result[$i]->ip_id, "cart" => $bal, "name" => $result[$i]->ip_name, "description" => '', "url" => base_url()."assets/uploads/".$oid."/".$result[$i]->ip_id."/".$result[$i]->ip_image, "price" => "Rs.".$res[0]->ipp_price."/-"));
        		            }
        		            
        		        } else {
        		            if($result[$i]->ip_image == "") {
        		                array_push($data["products"], array("id" => $result[$i]->ip_id, "cart" => $bal, "name" => $result[$i]->ip_name, "description" => '', "url" => base_url()."assets/images/placeholder-car.png", "price" => "N/A"));    
        		            } else {
        		                array_push($data["products"], array("id" => $result[$i]->ip_id, "cart" => $bal, "name" => $result[$i]->ip_name, "description" => '', "url" => base_url()."assets/uploads/".$oid."/".$result[$i]->ip_id."/".$result[$i]->ip_image, "price" => "N/A"));
        		            }
        		        }
        		    }
        		    $data['search_query'] = $k;
		            
		            $data['oid'] = $oid;
                    
                    $ert['name'] = $sess_data['user_details'][0]->ic_name;
        			$ert['title'] = "Home";
        			$ert['search'] = "false";
        			$ert['search_placeholder'] = "Search Products";
        			$ert['type'] = "dealers";
                    
                    $this->load->view('dealers/navbar', $ert);
        			$this->load->view('dealers/loader');
        			$this->load->view('dealers/home/category_new', $data);   
		        } else {
		            $q = $this->db->query("SELECT * FROM i_category WHERE ica_id='$cid' AND ica_owner='$oid'");
                    $r = $q->result();
                    
                    $data['category'] = [];
                    for($i=0;$i<count($r);$i++) {
                        $c = $r[$i]->ica_id;
                        $q1 = $this->db->query("SELECT * FROM i_category WHERE ica_parent_category = '$cid' AND ica_owner='$oid'");
                        $r1 = $q1->result();
                        $arr=[];
                        for($j=0;$j<count($r1);$j++) {
                            array_push($arr, array(
                                    'child_id' => $r1[$j]->ica_id,
                                    'child_name' => $r1[$j]->ica_category_name,
                                    'child_image' => $r1[$j]->ica_img
                                    ));
                        }
                        array_push($data['category'], array(
                            'parent_id' => $r[$i]->ica_id,
                            'parent_name' => $r[$i]->ica_category_name,
                            'parent_image' => $r[$i]->ica_img,
                            'child' => $arr
                            ));
                    }
                    
                    $data['products'] = [];
                    $q = $this->db->query("SELECT * FROM i_products WHERE ip_category='$cid' AND ip_owner='$oid'");
                    $result = $q->result();
                    for($i=0;$i<count($result);$i++) {
        		        $pid = $result[$i]->ip_id;
        		        $que = $this->db->query("SELECT * FROM i_u_cart WHERE iuc_u_id='$uid' AND iuc_u_owner='$oid' AND iuc_u_p_id='$pid' AND iuc_owner='$oid'");
        		        if(count($que->result()) > 0) {
        		            $bal = $que->result()[0]->iuc_u_qty;
        		        } else {
        		            $bal = 0;
        		        }
        		        
        		        $que = $this->db->query("SELECT * FROM i_p_pricing WHERE ipp_c_id='$rid' AND ipp_p_id='$pid' AND ipp_owner='$oid'");
        		        $res = $que->result();
        		        if(count($res) > 0) {
        		            if($result[$i]->ip_image == "") {
        		                array_push($data["products"], array("id" => $result[$i]->ip_id, "cart" => $bal, "name" => $result[$i]->ip_name, "description" => '', "url" => base_url()."assets/images/placeholder-car.png", "price" => "Rs.".$res[0]->ipp_price."/-"));    
        		            } else {
        		                array_push($data["products"], array("id" => $result[$i]->ip_id, "cart" => $bal, "name" => $result[$i]->ip_name, "description" => '', "url" => base_url()."assets/uploads/".$oid."/".$result[$i]->ip_id."/".$result[$i]->ip_image, "price" => "Rs.".$res[0]->ipp_price."/-"));
        		            }
        		            
        		        } else {
        		            if($result[$i]->ip_image == "") {
        		                array_push($data["products"], array("id" => $result[$i]->ip_id, "cart" => $bal, "name" => $result[$i]->ip_name, "description" => '', "url" => base_url()."assets/images/placeholder-car.png", "price" => "N/A"));    
        		            } else {
        		                array_push($data["products"], array("id" => $result[$i]->ip_id, "cart" => $bal, "name" => $result[$i]->ip_name, "description" => '', "url" => base_url()."assets/uploads/".$oid."/".$result[$i]->ip_id."/".$result[$i]->ip_image, "price" => "N/A"));
        		            }
        		        }
        		    }
        		    
                    $data['oid'] = $oid;
                    
                    
                    $ert['name'] = $sess_data['user_details'][0]->ic_name;
        			$ert['title'] = "Home";
        			$ert['search'] = "false";
        			$ert['search_placeholder'] = "Search Products";
        			$ert['type'] = "dealers";
                    
                    $this->load->view('dealers/navbar', $ert);
        			$this->load->view('dealers/loader');
        			$this->load->view('dealers/home/category_new', $data);   
		        }
		    }
		} else {
			redirect(base_url().$this->config->item('dealer_login_red'));
		}
    }
    
    public function load_product_details($pid) {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
		    $rid = $sess_data['user_details'][0]->iu_ref;
			$uid = $sess_data['user_details'][0]->iu_id;
			$oid = $sess_data['user_details'][0]->iu_owner;
			
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
		    } else {
		        $data['products'] = [];
                $q = $this->db->query("SELECT * FROM i_products WHERE ip_id='$pid' AND ip_owner='$oid'");
	            $result = $q->result();
	            
	            if(count($result) > 0) {
                    $pid = $result[0]->ip_id;
    		        $que = $this->db->query("SELECT * FROM i_u_cart WHERE iuc_u_id='$uid' AND iuc_u_owner='$oid' AND iuc_u_p_id='$pid' AND iuc_owner='$oid'");
    		        if(count($que->result()) > 0) {
    		            $bal = $que->result()[0]->iuc_u_qty;
    		        } else {
    		            $bal = 0;
    		        }
    		        
    		        $que = $this->db->query("SELECT * FROM i_p_images WHERE ipi_p_id='$pid' AND ipi_owner='$oid'");
    		        $res = $que->result();
    		        
    		        $arx = [];
    		        for ($i=0;$i<count($res);$i++) {
    		            array_push($arx, base_url()."assets/uploads/".$oid."/".$pid."/".$res[$i]->ipi_img);
    		        }
    		        
    		        
    		        $que = $this->db->query("SELECT * FROM i_p_pricing WHERE ipp_c_id='$rid' AND ipp_p_id='$pid' AND ipp_owner='$oid'");
    		        $res = $que->result();
    		        if(count($res) > 0) {
    		            if($result[0]->ip_image == "") {
    		                array_push($data["products"], array("id" => $result[0]->ip_id, "cart" => $bal, "name" => $result[0]->ip_name, "description" => '', "url" => base_url()."assets/images/placeholder-car.png", "price" => "Rs.".$res[0]->ipp_price."/-", 'images' => $arx));    
    		            } else {
    		                array_push($data["products"], array("id" => $result[0]->ip_id, "cart" => $bal, "name" => $result[0]->ip_name, "description" => '', "url" => base_url()."assets/uploads/".$oid."/".$result[0]->ip_id."/".$result[0]->ip_image, "price" => "Rs.".$res[0]->ipp_price."/-", 'images' => $arx));
    		            }
    		            
    		        } else {
    		            if($result[0]->ip_image == "") {
    		                array_push($data["products"], array("id" => $result[0]->ip_id, "cart" => $bal, "name" => $result[0]->ip_name, "description" => '', "url" => base_url()."assets/images/placeholder-car.png", "price" => "N/A", 'images' => $arx));    
    		            } else {
    		                array_push($data["products"], array("id" => $result[0]->ip_id, "cart" => $bal, "name" => $result[0]->ip_name, "description" => '', "url" => base_url()."assets/uploads/".$oid."/".$result[0]->ip_id."/".$result[0]->ip_image, "price" => "N/A", 'images' => $arx));
    		            }
    		        }
    		    }
    		    
	            $data['oid'] = $oid;
                
                $ert['name'] = $sess_data['user_details'][0]->ic_name;
    			$ert['title'] = "Product Details";
    			$ert['search'] = "false";
    			$ert['search_placeholder'] = "Search Products";
    			$ert['type'] = "dealers";
                
                $this->load->view('dealers/navbar', $ert);
    			$this->load->view('dealers/loader');
    			$this->load->view('dealers/home/products_view_new', $data);   
		    }
		} else {
			redirect(base_url().$this->config->item('dealer_login_red'));
		}
    }
    
    public function search_products($state=null) {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
		    $rid = $sess_data['user_details'][0]->iu_ref;
			$uid = $sess_data['user_details'][0]->iu_id;
			$oid = $sess_data['user_details'][0]->iu_owner;
			
		    $block_cid = $sess_data['user_details'][0]->ic_id;
		    $block_q = $this->db->query("SELECT * FROM i_contacts WHERE ic_id='$block_cid' AND ic_owner='$oid'");
		    $block_r = $block_q->result();
		    
		    if($block_r[0]->ic_new_flag == "2") {
		        $this->session->unset_userdata('account_status');
        		$this->session->unset_userdata('user_details');
        		$this->session->unset_userdata('status');
        		
        		$data = array('account_status' => "blocked");
        	    $this->session->set_userdata($data);
        				
        		print_r(json_encode(array('error' => 'login')));
		    } else {
		        if($state == null) {
		            $this->session->set_userdata(array('search' => $this->input->post('k')));    
		        } else if($state == 'page') {
		            $data['products'] = [];
		            $k = $this->input->post('k');
                    $q = $this->db->query("SELECT * FROM i_products WHERE (ip_name LIKE '%$k%' OR ip_description LIKE '%$k%') AND ip_owner='$oid'");
                    $result = $q->result();
                    for($i=0;$i<count($result);$i++) {
        		        $pid = $result[$i]->ip_id;
        		        
        		        $que = $this->db->query("SELECT * FROM i_u_cart WHERE iuc_u_id='$uid' AND iuc_u_owner='$oid' AND iuc_u_p_id='$pid' AND iuc_owner='$oid'");
        		        if(count($que->result()) > 0) {
        		            $bal = $que->result()[0]->iuc_u_qty;
        		        } else {
        		            $bal = 0;
        		        }
        		        
        		        $que = $this->db->query("SELECT * FROM i_p_pricing WHERE ipp_c_id='$rid' AND ipp_p_id='$pid' AND ipp_owner='$oid'");
        		        $res = $que->result();
        		        if(count($res) > 0) {
        		            if($result[$i]->ip_image == "") {
        		                array_push($data["products"], array("id" => $result[$i]->ip_id, "cart" => $bal, "name" => $result[$i]->ip_name, "description" => '', "url" => base_url()."assets/images/placeholder-car.png", "price" => "Rs.".$res[0]->ipp_price."/-"));    
        		            } else {
        		                array_push($data["products"], array("id" => $result[$i]->ip_id, "cart" => $bal, "name" => $result[$i]->ip_name, "description" => '', "url" => base_url()."assets/uploads/".$oid."/".$result[$i]->ip_id."/".$result[$i]->ip_image, "price" => "Rs.".$res[0]->ipp_price."/-"));
        		            }
        		            
        		        } else {
        		            if($result[$i]->ip_image == "") {
        		                array_push($data["products"], array("id" => $result[$i]->ip_id, "cart" => $bal, "name" => $result[$i]->ip_name, "description" => '', "url" => base_url()."assets/images/placeholder-car.png", "price" => "N/A"));    
        		            } else {
        		                array_push($data["products"], array("id" => $result[$i]->ip_id, "cart" => $bal, "name" => $result[$i]->ip_name, "description" => '', "url" => base_url()."assets/uploads/".$oid."/".$result[$i]->ip_id."/".$result[$i]->ip_image, "price" => "N/A"));
        		            }
        		        }
        		    }
        		    print_r(json_encode($data));
		        }
		    }
		} else {
		    print_r(json_encode(array('error' => 'login')));
		}
    }
    
    public function product_to_cart($state) {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
		    $rid = $sess_data['user_details'][0]->iu_ref;
			$uid = $sess_data['user_details'][0]->iu_id;
			$oid = $sess_data['user_details'][0]->iu_owner;
			
		    $block_cid = $sess_data['user_details'][0]->ic_id;
		    $block_q = $this->db->query("SELECT * FROM i_contacts WHERE ic_id='$block_cid' AND ic_owner='$oid'");
		    $block_r = $block_q->result();
		    
		    if($block_r[0]->ic_new_flag == "2") {
		        $this->session->unset_userdata('account_status');
        		$this->session->unset_userdata('user_details');
        		$this->session->unset_userdata('status');
        		
        		$data = array('account_status' => "blocked");
        	    $this->session->set_userdata($data);
        				
        		print_r(json_encode(array('error' => 'login')));
		    } else {
		        $p = $this->input->post('p');
		        if($state=='add') {
		            $q = $this->db->query("SELECT * FROM i_u_cart WHERE iuc_u_id='$uid' AND iuc_u_owner='$oid' AND iuc_u_p_id='$p' AND iuc_owner='$oid'");
		            if(count($q->result()) > 0) {
		                $bal = $q->result()[0]->iuc_u_qty;
		                $bal++;
		                $this->db->where(array('iuc_u_id' => $uid, 'iuc_u_owner' => $oid, 'iuc_u_p_id' => $p, 'iuc_owner' => $oid ));
		                $this->db->update('i_u_cart', array('iuc_u_qty' => $bal, 'iuc_modified' => date('Y-m-d H:i:s'), 'iuc_modified_by' => $uid));
		            } else {
		                $bal = 1;
		                $this->db->insert('i_u_cart', array(
		                    'iuc_u_id' => $uid,
		                    'iuc_u_owner' => $oid,
		                    'iuc_u_p_id' => $p,
		                    'iuc_u_qty' => $bal, 
		                    'iuc_owner' => $oid,
		                    'iuc_created' => date('Y-m-d H:i:s'), 
		                    'iuc_created_by' => $uid));
		            }
		            echo $bal;
		        } else if ($state=='remove') {
		            $q = $this->db->query("SELECT * FROM i_u_cart WHERE iuc_u_id='$uid' AND iuc_u_owner='$oid' AND iuc_u_p_id='$p' AND iuc_owner='$oid'");
		            if(count($q) > 0) {
		                $bal = $q->result()[0]->iuc_u_qty;
		                if($bal > 1) {
		                    $bal--;
    		                $this->db->where(array('iuc_u_id' => $uid, 'iuc_u_owner' => $oid, 'iuc_u_p_id' => $p, 'iuc_owner' => $oid ));
    		                $this->db->update('i_u_cart', array('iuc_u_qty' => $bal, 'iuc_modified' => date('Y-m-d H:i:s'), 'iuc_modified_by' => $uid));    
		                } else {
		                    $this->db->where(array('iuc_u_id' => $uid, 'iuc_u_owner' => $oid, 'iuc_u_p_id' => $p, 'iuc_owner' => $oid ));
    		                $this->db->delete('i_u_cart');    
		                    $bal=-1;
		                }
		            }
		            echo $bal;
		        }
		    }
		} else {
		    print_r(json_encode(array('error' => 'login')));
		}
    }
    
    public function load_cart() {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
		    $rid = $sess_data['user_details'][0]->iu_ref;
			$uid = $sess_data['user_details'][0]->iu_id;
			$oid = $sess_data['user_details'][0]->iu_owner;
			
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
		    } else {
		        
		        $data['oid'] = $oid;
                    
                $ert['name'] = $sess_data['user_details'][0]->ic_name;
    			$ert['title'] = "Cart";
    			$ert['search'] = "false";
    			$ert['search_placeholder'] = "Search Products";
    			$ert['type'] = "dealers";
                
                $this->load->view('dealers/navbar', $ert);
    			$this->load->view('dealers/loader');
    			$this->load->view('dealers/home/cart_new', $data);
		    }
		} else {
			redirect(base_url().$this->config->item('dealer_login_red'));
		}
    }
	
    public function load_cart_values($state) {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
		    $rid = $sess_data['user_details'][0]->iu_ref;
			$uid = $sess_data['user_details'][0]->iu_id;
			$oid = $sess_data['user_details'][0]->iu_owner;
			
		    $block_cid = $sess_data['user_details'][0]->ic_id;
		    $block_q = $this->db->query("SELECT * FROM i_contacts WHERE ic_id='$block_cid' AND ic_owner='$oid'");
		    $block_r = $block_q->result();
		    
		    if($block_r[0]->ic_new_flag == "2") {
		        $this->session->unset_userdata('account_status');
        		$this->session->unset_userdata('user_details');
        		$this->session->unset_userdata('status');
        		
        		$data = array('account_status' => "blocked");
        	    $this->session->set_userdata($data);
        				
        		print_r(json_encode(array('error' => 'login')));
		    } else {
		        $q = $this->db->query("SELECT * FROM i_u_cart AS a LEFT JOIN i_products AS b ON a.iuc_u_p_id=b.ip_id WHERE a.iuc_u_id='$uid' AND a.iuc_u_owner='$oid' AND a.iuc_owner='$oid'");
		        if($state=='count') {
		            echo count($q->result());
		        } else {
		            if(count($q->result()) > 0) {
		                $r = $q->result(); $arr=[]; $qty=0;
		                for($i=0;$i<count($r);$i++) {
		                    $pid = $r[$i]->ip_id;
		                    $qty+= $r[$i]->iuc_u_qty;
		                    
                            if($r[$i]->ip_image == "") {
                                $img = base_url()."assets/images/placeholder-car.png";
                            } else {
                                $img = base_url()."assets/uploads/".$oid."/".$r[$i]->ip_id."/".$r[$i]->ip_image;
                            }
                            
                            $que = $this->db->query("SELECT * FROM i_p_pricing WHERE ipp_c_id='$rid' AND ipp_p_id='$pid' AND ipp_owner='$oid'");
            		        $res = $que->result();
            		        if(count($res) > 0) {
                                $price = $res[0]->ipp_price;    
        		            } else {
        		                $price = "";
        		            }
            		        
		                    array_push($arr, array(
		                        'id' => $r[$i]->ip_id,
		                        'name' => $r[$i]->ip_name,
		                        'url' => $img,
		                        'price' => $price,
		                        'qty' => $r[$i]->iuc_u_qty,
		                        'description' => $r[$i]->ip_description
	                        ));
		                }
		                
		                print_r(json_encode(array(
    		                'item_count' => count($q->result()),
    		                'qty_count' => $qty,
    		                'cart' => $arr
		                )));
		            }
		        }
		    }
		}
    }
    
    
    public function orders_new() {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$cid = $sess_data['user_details'][0]->iu_ref;

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
			
			$data['orders'] = [];
            $query = $this->db->query("SELECT * FROM i_txns WHERE it_owner='$oid' AND it_c_id='$cid' AND it_type IN ('Delivery') ORDER BY it_date DESC");
			$result = $query->result();
			for($i=0;$i<count($result); $i++) {
			    array_push($data['orders'], array('id' => $result[$i]->it_id, 'txntype' => $result[$i]->it_type, 'txnno' => $result[$i]->it_txn_no, 'txndt' => $result[$i]->it_date, 'amount' => $result[$i]->it_amount, 'status' => $result[$i]->it_status));    
			}
			
            $query1 = $this->db->query("SELECT * FROM i_txns WHERE it_owner='$oid' AND it_c_id='$cid' AND it_type IN ('Orders') AND it_status IN ('pending','approved') ORDER BY it_date DESC");
			$result1 = $query1->result();
			for($i=0;$i<count($result1); $i++) {
			    $flg=false;
			    for($j=0;$j<count($result); $j++) {
			        if($result1[$i]->it_id == $result[$j]->it_order_txn) {
			            $flg=true; break;
			        }
			    }
			    if($flg==false) {
			        array_push($data['orders'], array('id' => $result1[$i]->it_id, 'txntype' => $result1[$i]->it_type, 'txnno' => $result1[$i]->it_txn_no, 'txndt' => $result1[$i]->it_date, 'amount' => $result1[$i]->it_amount, 'status' => $result1[$i]->it_status));       
			    }
			}
			
			
		
			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Orders";
			$ert['search'] = "false";
			$ert['search_placeholder'] = "Search Products";
			$ert['type'] = "dealers";
			
			$this->load->view('dealers/navbar', $ert);
			$this->load->view('dealers/loader');
			$this->load->view('dealers/home/orders_new', $data);
		} else {
			redirect(base_url().$this->config->item('dealer_login_red'));
		}
    }
    
    public function fetch_order_list() {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$cid = $sess_data['user_details'][0]->iu_ref;

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
		    } else {
		        $f=$this->input->post('f'); $t=$this->input->post('t');
		        if($f!=null || $t!=null) {
		            $data['orders'] = [];
                    $query = $this->db->query("SELECT * FROM i_txns WHERE it_owner='$oid' AND it_c_id='$cid' AND it_date BETWEEN '$f' AND '$t' AND it_type IN ('Delivery') ORDER BY it_date DESC");
        			$result = $query->result();
        			for($i=0;$i<count($result); $i++) {
        			    array_push($data['orders'], array('id' => $result[$i]->it_id, 'txntype' => $result[$i]->it_type, 'txnno' => $result[$i]->it_txn_no, 'txndt' => $result[$i]->it_date, 'amount' => $result[$i]->it_amount, 'status' => $result[$i]->it_status));    
        			}
        			
                    $query1 = $this->db->query("SELECT * FROM i_txns WHERE it_owner='$oid' AND it_c_id='$cid' AND it_date BETWEEN '$f' AND '$t' AND it_type IN ('Orders') AND it_status IN ('pending','approved') ORDER BY it_date DESC");
        			$result1 = $query1->result();
        			for($i=0;$i<count($result1); $i++) {
        			    $flg=false;
        			    for($j=0;$j<count($result); $j++) {
        			        if($result1[$i]->it_id == $result[$j]->it_order_txn) {
        			            $flg=true; break;
        			        }
        			    }
        			    if($flg==false) {
        			        array_push($data['orders'], array('id' => $result1[$i]->it_id, 'txntype' => $result1[$i]->it_type, 'txnno' => $result1[$i]->it_txn_no, 'txndt' => $result1[$i]->it_date, 'amount' => $result1[$i]->it_amount, 'status' => $result1[$i]->it_status));       
        			    }
        			}
		        } else {
		            $data['orders'] = [];
                    $query = $this->db->query("SELECT * FROM i_txns WHERE it_owner='$oid' AND it_c_id='$cid' AND it_type IN ('Delivery') ORDER BY it_date DESC");
        			$result = $query->result();
        			for($i=0;$i<count($result); $i++) {
        			    array_push($data['orders'], array('id' => $result[$i]->it_id, 'txntype' => $result[$i]->it_type, 'txnno' => $result[$i]->it_txn_no, 'txndt' => $result[$i]->it_date, 'amount' => $result[$i]->it_amount, 'status' => $result[$i]->it_status));    
        			}
        			
                    $query1 = $this->db->query("SELECT * FROM i_txns WHERE it_owner='$oid' AND it_c_id='$cid' AND it_type IN ('Orders') AND it_status IN ('pending','approved') ORDER BY it_date DESC");
        			$result1 = $query1->result();
        			for($i=0;$i<count($result1); $i++) {
        			    $flg=false;
        			    for($j=0;$j<count($result); $j++) {
        			        if($result1[$i]->it_id == $result[$j]->it_order_txn) {
        			            $flg=true; break;
        			        }
        			    }
        			    if($flg==false) {
        			        array_push($data['orders'], array('id' => $result1[$i]->it_id, 'txntype' => $result1[$i]->it_type, 'txnno' => $result1[$i]->it_txn_no, 'txndt' => $result1[$i]->it_date, 'amount' => $result1[$i]->it_amount, 'status' => $result1[$i]->it_status));       
        			    }
        			}
		        }
		        
    		        
    			print_r(json_encode($data));   
		    }
		} else {
			redirect(base_url().$this->config->item('dealer_login_red'));
		}
	}
    
    public function order_details_new($id) {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			$cid = $sess_data['user_details'][0]->iu_ref;

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
			
			
			$data['oid'] = $oid;
			$data['id'] = $id;

			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Order Details";
			$ert['search'] = "true";
			$ert['search_placeholder'] = "Search Products";
			$ert['type'] = "dealers";
			
			$this->load->view('dealers/navbar', $ert);
			$this->load->view('dealers/loader');
			$this->load->view('dealers/home/order_details_new', $data);
		} else {
			redirect(base_url().$this->config->item('dealer_login_red'));
		}
	}
	
	public function load_order_values($tid) {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			$cid = $sess_data['user_details'][0]->iu_ref;

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
		    } else {
		        $data = [];
		        $q = $this->db->query("SELECT * FROM i_txns WHERE it_id='$tid' AND it_owner='$oid'");
		        $data['order'] = $q->result();
		        
		        $q = $this->db->query("SELECT * FROM i_txns_details AS a LEFT JOIN i_products AS b ON a.itd_p_id=b.ip_id WHERE a.itd_t_id='$tid' AND a.itp_owner='$oid'");
		        $r = $q->result();
		        
		        $data['order_details'] = []; $qty=0;
		        for($i=0;$i<count($r);$i++) {
		            $qty+= $r[$i]->itp_qty;
		                    
		            if($r[$i]->ip_image == "") {
                        $img = base_url()."assets/images/placeholder-car.png";
                    } else {
                        $img = base_url()."assets/uploads/".$oid."/".$r[$i]->ip_id."/".$r[$i]->ip_image;
                    }
                    
                    
		            array_push($data['order_details'], array(
                        'id' => $r[$i]->ip_id,
                        'name' => $r[$i]->ip_name,
                        'url' => $img,
                        'price' => $r[$i]->itp_rate,
                        'qty' => $r[$i]->itp_qty,
                        'description' => $r[$i]->ip_description
                    ));
		        }
		        
		        $data['item_count'] = count($q->result());
		        $data['qty_count'] = $qty;

    			$q = $this->db->query("SELECT itpn_date AS date, itpn_amount AS amount, itpn_narration AS narration FROM i_txn_payments_new WHERE itpn_txn_id='$tid' AND itpn_owner='$oid'");
    			$data['payment'] = $q->result();
    			
    			print_r(json_encode($data));
		    }
		} else {
			redirect(base_url().$this->config->item('dealer_login_red'));
		}
	}
	
	public function product_to_order($state, $tid) {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
		    $rid = $sess_data['user_details'][0]->iu_ref;
			$uid = $sess_data['user_details'][0]->iu_id;
			$oid = $sess_data['user_details'][0]->iu_owner;
			
		    $block_cid = $sess_data['user_details'][0]->ic_id;
		    $block_q = $this->db->query("SELECT * FROM i_contacts WHERE ic_id='$block_cid' AND ic_owner='$oid'");
		    $block_r = $block_q->result();
		    
		    if($block_r[0]->ic_new_flag == "2") {
		        $this->session->unset_userdata('account_status');
        		$this->session->unset_userdata('user_details');
        		$this->session->unset_userdata('status');
        		
        		$data = array('account_status' => "blocked");
        	    $this->session->set_userdata($data);
        				
        		print_r(json_encode(array('error' => 'login')));
		    } else {
		        $p = $this->input->post('p');
		        if($state=='add') {
		            $q = $this->db->query("SELECT * FROM i_txns_details WHERE itd_t_id='$tid' AND itp_owner='$oid' AND itd_p_id='$p'");
		            if(count($q->result()) > 0) {
		                $bal = $q->result()[0]->itp_qty;
		                $bal++;
		                $this->db->where(array('itd_t_id' => $tid, 'itp_owner' => $oid, 'itd_p_id' => $p));
		                $this->db->update('i_txns_details', array('itp_qty' => $bal, 'itp_modified' => date('Y-m-d H:i:s'), 'itp_modified_by' => $uid));
		            }
		            echo $bal;
		        } else if ($state=='remove') {
		            $q = $this->db->query("SELECT * FROM i_txns_details WHERE itd_t_id='$tid' AND itp_owner='$oid' AND itd_p_id='$p'");
		            if(count($q) > 0) {
		                $bal = $q->result()[0]->itp_qty;
		                if($bal > 1) {
		                    $bal--;
    		                $this->db->where(array('itd_t_id' => $tid, 'itp_owner' => $oid, 'itd_p_id' => $p));
		                    $this->db->update('i_txns_details', array('itp_qty' => $bal, 'itp_modified' => date('Y-m-d H:i:s'), 'itp_modified_by' => $uid));
		                } else {
		                    $this->db->where(array('itd_t_id' => $tid, 'itp_owner' => $oid, 'itd_p_id' => $p));
    		                $this->db->delete('i_txns_details');
		                    $bal=-1;
		                }
		            }
		            echo $bal;
		        }
		    }
		} else {
		    print_r(json_encode(array('error' => 'login')));
		}
    }
    
    
    
    public function invoice_new() {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$cid = $sess_data['user_details'][0]->iu_ref;

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
			
			$data['orders'] = [];
            $query = $this->db->query("SELECT * FROM i_txns WHERE it_owner='$oid' AND it_c_id='$cid' AND (it_type ='Invoice' OR (it_type = 'Credit Note' AND it_type_sec='Invoice'))  ORDER BY it_date DESC");
			$result = $query->result();
			for($i=0;$i<count($result); $i++) {
			    array_push($data['orders'], array('id' => $result[$i]->it_id, 'txntype' => $result[$i]->it_type, 'txnno' => $result[$i]->it_txn_no, 'txndt' => $result[$i]->it_date, 'amount' => $result[$i]->it_amount, 'status' => $result[$i]->it_status));    
			}
			
		
			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Invoice";
			$ert['search'] = "false";
			$ert['search_placeholder'] = "Search Products";
			$ert['type'] = "dealers";
			
			$this->load->view('dealers/navbar', $ert);
			$this->load->view('dealers/loader');
			$this->load->view('dealers/home/invoice_new', $data);
		} else {
			redirect(base_url().$this->config->item('dealer_login_red'));
		}
    }
    
    public function fetch_invoice_list() {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$cid = $sess_data['user_details'][0]->iu_ref;

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
		    } else {
		        $f=$this->input->post('f'); $t=$this->input->post('t');
		        if($f!=null || $t!=null) {
		            $data['orders'] = [];
                    $query = $this->db->query("SELECT * FROM i_txns WHERE it_owner='$oid' AND it_c_id='$cid' AND it_date BETWEEN '$f' AND '$t' AND (it_type ='Invoice' OR (it_type = 'Credit Note' AND it_type_sec='Invoice')) ORDER BY it_date DESC");
        			$result = $query->result();
        			for($i=0;$i<count($result); $i++) {
        			    array_push($data['orders'], array('id' => $result[$i]->it_id, 'txntype' => $result[$i]->it_type, 'txnno' => $result[$i]->it_txn_no, 'txndt' => $result[$i]->it_date, 'amount' => $result[$i]->it_amount, 'status' => $result[$i]->it_status));    
        			}
		        } else {
		            $data['orders'] = [];
                    $query = $this->db->query("SELECT * FROM i_txns WHERE it_owner='$oid' AND it_c_id='$cid' AND (it_type ='Invoice' OR (it_type = 'Credit Note' AND it_type_sec='Invoice')) ORDER BY it_date DESC");
        			$result = $query->result();
        			for($i=0;$i<count($result); $i++) {
        			    array_push($data['orders'], array('id' => $result[$i]->it_id, 'txntype' => $result[$i]->it_type, 'txnno' => $result[$i]->it_txn_no, 'txndt' => $result[$i]->it_date, 'amount' => $result[$i]->it_amount, 'status' => $result[$i]->it_status));    
        			}
		        }
		        
    		        
    			print_r(json_encode($data));   
		    }
		} else {
			redirect(base_url().$this->config->item('dealer_login_red'));
		}
	}
    
    public function invoice_details_new($id) {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			$cid = $sess_data['user_details'][0]->iu_ref;

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
		    
			$this->load->model('distributors/Transaction_Model','txn_model');
			$tx = $this->txn_model->load_transactions($oid, 'Invoice', null, $id);
			
			if(count($tx) > 0) {
			    $data["txn"] = $tx;
			    $data['invoice_number'] = $tx[0]->it_txn_no;
			}
			
			$q = $this->db->query("SELECT iu_logo FROM i_users WHERE iu_id='$oid' AND iu_owner='$oid'");
			$data['logo'] = base_url().'assets/uploads/'.$oid.'/logo/'.$q->result()[0]->iu_logo;
			$data['txn_transport'] = $this->txn_model->load_transaction_transport_details($oid, $id);
			
			$query = $this->db->query("SELECT * FROM i_txn_invoice_merge WHERE itim_primary='$id' AND itim_owner='$oid' AND itim_status='active'");
			$result = $query->result();
			
			$data['txn_detail_merge'] = [];
			$data['txn_details'] = [];
			if(count($result) > 0) {
			    for($i=0;$i<count($result);$i++) {
    			    array_push($data['txn_detail_merge'], $this->txn_model->load_transaction_details($oid, $result[$i]->itim_secondary));
    			}
			} else {
    			$data["txn_details"] = $this->txn_model->load_transaction_details($oid, $id);
			}
			
			$query = $this->db->query("SELECT b.it_txn_no, b.it_date FROM i_txn_invoice_challans AS a LEFT JOIN i_txns AS b ON a.itic_chln_id=b.it_id WHERE itic_inv_id='$id' AND itic_owner='$oid'");
			$result = $query->result(); $xx=[];
			for($i=0;$i<count($result);$i++) {
			    array_push($xx, $result[$i]->it_txn_no.' - '.$result[$i]->it_date);
			}
			$data['txn_challans']=$xx;
			
			$data['txn_prod_tax'] = $this->txn_model->load_transaction_prod_tax_details($oid, $id);
			
			$query = $this->db->query("SELECT * FROM i_taxes WHERE itx_owner = '$oid'");
			$data['taxes'] = $query->result();
			
			$data['gst_num'] = $sess_data['user_details'][0]->ic_gst_number;
			$data['bank'] = $sess_data['user_details'][0]->ic_bank_name;
			$data['branch'] = $sess_data['user_details'][0]->ic_bank_branch;
			$data['ifsc'] = $sess_data['user_details'][0]->ic_bank_ifsc;
			$data['acc'] = $sess_data['user_details'][0]->ic_bank_accno;
			
			$data['oid'] = $oid;
			$data['pid'] = $id;

			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Invoice Details";
			$ert['search'] = "false";
			$ert['search_placeholder'] = "Search Products";
			$ert['type'] = "dealers";
			
			$this->load->view('dealers/navbar', $ert);
			$this->load->view('dealers/loader');
			$this->load->view('dealers/home/invoice_details_new', $data);
		} else {
			redirect(base_url().$this->config->item('dealer_login_red'));
		}
	}
	
	public function load_invoice_values($tid) {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			$cid = $sess_data['user_details'][0]->iu_ref;

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
		    } else {
		        $data = [];
		        $q = $this->db->query("SELECT * FROM i_txns WHERE it_id='$tid' AND it_owner='$oid'");
		        $data['order'] = $q->result();
		        
		        $q = $this->db->query("SELECT * FROM i_txns_details AS a LEFT JOIN i_products AS b ON a.itd_p_id=b.ip_id WHERE a.itd_t_id='$tid' AND a.itp_owner='$oid'");
		        $r = $q->result();
		        
		        $data['order_details'] = []; $qty=0;
		        for($i=0;$i<count($r);$i++) {
		            $qty+= $r[$i]->itp_qty;
		                    
		            if($r[$i]->ip_image == "") {
                        $img = base_url()."assets/images/placeholder-car.png";
                    } else {
                        $img = base_url()."assets/uploads/".$oid."/".$r[$i]->ip_id."/".$r[$i]->ip_image;
                    }
                    
                    
		            array_push($data['order_details'], array(
                        'id' => $r[$i]->ip_id,
                        'name' => $r[$i]->ip_name,
                        'url' => $img,
                        'price' => $r[$i]->itp_rate,
                        'qty' => $r[$i]->itp_qty,
                        'description' => $r[$i]->ip_description
                    ));
		        }
		        
		        $data['item_count'] = count($q->result());
		        $data['qty_count'] = $qty;

    			$q = $this->db->query("SELECT itpn_date AS date, itpn_amount AS amount, itpn_narration AS narration FROM i_txn_payments_new WHERE itpn_txn_id='$tid' AND itpn_owner='$oid'");
    			$data['payment'] = $q->result();
    			
    			print_r(json_encode($data));
		    }
		} else {
			redirect(base_url().$this->config->item('dealer_login_red'));
		}
	}
	
	
	public function ledger_new() {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$cid = $sess_data['user_details'][0]->iu_ref;

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
		    
		    $data['pid'] = $sess_data['user_details'][0]->ic_id;
			
			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Ledger";
			$ert['search'] = "false";
			$ert['search_placeholder'] = "Search Products";
			$ert['type'] = "dealers";
			
			
			$this->load->view('dealers/navbar', $ert);
			$this->load->view('dealers/loader');
			$this->load->view('dealers/home/ledger_new', $data);
		} else {
			redirect(base_url().$this->config->item('dealer_login_red'));
		}
    }
    
    public function fetch_ledger_details() {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$cid = $sess_data['user_details'][0]->iu_ref;

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
		    } else {
	            $f=$this->input->post('f');$t=$this->input->post('t');$tx=$this->input->post('tx');
	            $pid = $sess_data['user_details'][0]->ic_id;
		        if($f!=null && $t!=null) {
		            if($tx=='delivery'){
		                $q = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id WHERE (it_type IN ('Delivery') OR it_type IN ('Credit Note') AND it_type_sec IN ('Delivery')) AND it_date BETWEEN '$f' AND '$t' AND it_c_id='$pid' AND it_owner='$oid'");    
		            } else if($tx=='invoice') {
		                $q = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id WHERE (it_type IN ('Invoice','Purchase') OR it_type IN ('Credit Note', 'Debit Note') AND it_type_sec IN ('Delivery','Invoice')) AND it_date BETWEEN '$f' AND '$t' AND it_c_id='$pid' AND it_owner='$oid'");
		            } else {
		                $q = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id WHERE it_type IN ('Delivery','Invoice','Purchase','Credit Note','Debit Note') AND it_c_id='$pid' AND it_date BETWEEN '$f' AND '$t' AND it_owner='$oid'");
		            }
		            
		        } else {
		            if($tx=='delivery'){
		                $q = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id WHERE (it_type IN ('Delivery') OR it_type IN ('Credit Note') AND it_type_sec IN ('Delivery')) AND it_c_id='$pid' AND it_owner='$oid'");    
		            } else if($tx=='invoice') {
		                $q = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id WHERE (it_type IN ('Invoice','Purchase') OR it_type IN ('Credit Note', 'Debit Note') AND it_type_sec IN ('Delivery','Invoice')) AND it_c_id='$pid' AND it_owner='$oid'");
		            } else {
		                $q = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id WHERE it_type IN ('Delivery','Invoice','Purchase','Credit Note','Debit Note') AND it_c_id='$pid' AND it_owner='$oid'");
		            }
		            
		        }
		        $r=$q->result(); $x=[];
		        for($i=0;$i<count($r);$i++) {
		            $tx=$r[$i]->it_id;
		            $q1 = $this->db->query("SELECT * FROM i_txn_payments_new AS a LEFT JOIN i_cash_master AS b ON a.itpn_mode_id=b.icm_id WHERE a.itpn_txn_id='$tx' AND itpn_owner='$oid'");
		            $data['txn'] = array('txn' => $r, 'pay' => $q1->result());    
		        }
		        
		        if($f!=null && $t!=null) {
		            if($tx=='delivery'){
		                $q = $this->db->query("SELECT * FROM i_txns WHERE (it_type IN ('Delivery') OR it_type IN ('Credit Note') AND it_type_sec IN ('Delivery')) AND it_date < '$f' AND it_c_id='$pid' AND it_owner='$oid'");    
		            } else if($tx=='invoice') {
		                $q = $this->db->query("SELECT * FROM i_txns WHERE (it_type IN ('Invoice','Purchase') OR it_type IN ('Credit Note', 'Debit Note') AND it_type_sec IN ('Delivery','Invoice')) AND it_date < '$f' AND it_c_id='$pid' AND it_owner='$oid'");
		            } else {
		                $q = $this->db->query("SELECT * FROM i_txns WHERE it_type IN ('Delivery','Invoice','Purchase','Credit Note','Debit Note') AND it_c_id='$pid' AND it_date < '$f' AND it_owner='$oid'");
		            }
		            
		            $cr=0;$dr=0;
		            $r=$q->result();
		            for($i=0;$i<count($r); $i++) {
		                if($r[$i]->it_type=='Purchase' || $r[$i]->it_type =='Credit Note'){
		                    $cr+=$r[$i]->it_amount;
		                } else {
		                    $dr+=$r[$i]->it_amount;
		                }
		            }
		            
		            $q = $this->db->query("SELECT * FROM i_cash_transactions AS a WHERE ((ict_from='$pid' AND ict_from_type='contact') OR (ict_to='$pid' AND ict_to_type='contact')) AND ict_flag_col='cash' AND ict_date BETWEEN '$f' AND '$t' AND  ict_owner='$oid'");
		            $r=$q->result();
		            $arr=[];
		            for($i=0;$i<count($r);$i++) {
		                if($r[$i]->ict_from_type == 'account') {
		                    $x1 = $r[$i]->ict_from;
		                    $q1 = $this->db->query("SELECT icm_id AS id, icm_name AS name FROM i_cash_master WHERE icm_id='$x1' AND icm_owner='$oid'");
		                    $r1 = $q1->result();
		                } else if($r[$i]->ict_from_type == 'ledger') {
		                    $x1 = $r[$i]->ict_from;
		                    $q1 = $this->db->query("SELECT ilm_id AS id, ilm_name AS name FROM i_ledger_master WHERE ilm_id='$x1' AND ilm_owner='$oid'");
		                    $r1 = $q1->result();
		                } else if($r[$i]->ict_from_type == 'contact') {
		                    $x1 = $r[$i]->ict_from;
		                    $q1 = $this->db->query("SELECT ic_id AS id, ic_name AS name FROM i_contacts WHERE ic_id='$x1' AND ic_owner='$oid'");
		                    $r1 = $q1->result();
		                }
		                
		                if($r[$i]->ict_to_type == 'account') {
		                    $x2 = $r[$i]->ict_to;
		                    $q2 = $this->db->query("SELECT icm_id AS id, icm_name AS name FROM i_cash_master WHERE icm_id='$x2' AND icm_owner='$oid'");
		                    $r2 = $q2->result();
		                } else if($r[$i]->ict_to_type == 'ledger') {
		                    $x2 = $r[$i]->ict_to;
		                    $q2 = $this->db->query("SELECT ilm_id AS id, ilm_name AS name FROM i_ledger_master WHERE ilm_id='$x2' AND ilm_owner='$oid'");
		                    $r2 = $q2->result();
		                } else if($r[$i]->ict_to_type == 'contact') {
		                    $x2 = $r[$i]->ict_to;
		                    $q2 = $this->db->query("SELECT ic_id AS id, ic_name AS name FROM i_contacts WHERE ic_id='$x2' AND ic_owner='$oid'");
		                    $r2 = $q2->result();
		                }
		                
		                array_push($arr, array(
		                    'id' => $r[$i]->ict_id,
		                    'from_id' => $r1[0]->id,
		                    'from_name' => $r1[0]->name,
		                    'from_type' => $r[$i]->ict_from_type,
		                    'to_id' => $r2[0]->id,
		                    'to_name' => $r2[0]->name,
		                    'to_type' => $r[$i]->ict_to_type,
		                    'date' => $r[$i]->ict_date,
		                    'amount' => $r[$i]->ict_amount,
		                    'narration' => $r[$i]->ict_narration,
		                    ));
		            }
		            $data['transaction'] = $arr;
		            
		            $q = $this->db->query("SELECT * FROM i_cash_transactions AS a WHERE ((ict_from='$pid' AND ict_from_type='contact') OR (ict_to='$pid' AND ict_to_type='contact')) AND ict_flag_col='cash' AND ict_date < '$f' AND ict_owner='$oid'");
		            $r = $q->result();
		            
		            for($i=0;$i<count($r); $i++) {
		                if($r[$i]->ict_from == $pid && $r[$i]->ict_from_type=='contact') {
		                    $cr+=$r[$i]->ict_amount;
		                } else {
		                    $dr+=$r[$i]->ict_amount;
		                }
		            }
		            
			        $q = $this->db->query("SELECT * FROM i_opening_balance WHERE iob_e_id='$pid' AND iob_e_type='contact' AND iob_owner='$oid'");
			        $r=$q->result();
			        if(count($r) > 0) {
			            if($r[0]->iob_amount_type == 'credit') {
			                $cr+=$r[0]->iob_amount;
			            } else {
			                $dr+=$r[0]->iob_amount;
			            }
			        }
			        
			        $bal=$cr-$dr;
			        if($bal > 0) {
			            $data['opening'] = array('type' => 'credit', 'amt' => $bal);   
			        } else {
			            $data['opening'] = array('type' => 'debit', 'amt' => ($bal * -1));
			        }
		        } else {
		            $q = $this->db->query("SELECT * FROM i_cash_transactions AS a WHERE ((ict_from='$pid' AND ict_from_type='contact') OR (ict_to='$pid' AND ict_to_type='contact')) AND ict_flag_col='cash' AND  ict_owner='$oid'");
		            $r=$q->result();
		            $arr=[];
		            for($i=0;$i<count($r);$i++) {
		                if($r[$i]->ict_from_type == 'account') {
		                    $x1 = $r[$i]->ict_from;
		                    $q1 = $this->db->query("SELECT icm_id AS id, icm_name AS name FROM i_cash_master WHERE icm_id='$x1' AND icm_owner='$oid'");
		                    $r1 = $q1->result();
		                } else if($r[$i]->ict_from_type == 'ledger') {
		                    $x1 = $r[$i]->ict_from;
		                    $q1 = $this->db->query("SELECT ilm_id AS id, ilm_name AS name FROM i_ledger_master WHERE ilm_id='$x1' AND ilm_owner='$oid'");
		                    $r1 = $q1->result();
		                } else if($r[$i]->ict_from_type == 'contact') {
		                    $x1 = $r[$i]->ict_from;
		                    $q1 = $this->db->query("SELECT ic_id AS id, ic_name AS name FROM i_contacts WHERE ic_id='$x1' AND ic_owner='$oid'");
		                    $r1 = $q1->result();
		                }
		                
		                if($r[$i]->ict_to_type == 'account') {
		                    $x2 = $r[$i]->ict_to;
		                    $q2 = $this->db->query("SELECT icm_id AS id, icm_name AS name FROM i_cash_master WHERE icm_id='$x2' AND icm_owner='$oid'");
		                    $r2 = $q2->result();
		                } else if($r[$i]->ict_to_type == 'ledger') {
		                    $x2 = $r[$i]->ict_to;
		                    $q2 = $this->db->query("SELECT ilm_id AS id, ilm_name AS name FROM i_ledger_master WHERE ilm_id='$x2' AND ilm_owner='$oid'");
		                    $r2 = $q2->result();
		                } else if($r[$i]->ict_to_type == 'contact') {
		                    $x2 = $r[$i]->ict_to;
		                    $q2 = $this->db->query("SELECT ic_id AS id, ic_name AS name FROM i_contacts WHERE ic_id='$x2' AND ic_owner='$oid'");
		                    $r2 = $q2->result();
		                }
		                
		                array_push($arr, array(
		                    'id' => $r[$i]->ict_id,
		                    'from_id' => $r1[0]->id,
		                    'from_name' => $r1[0]->name,
		                    'from_type' => $r[$i]->ict_from_type,
		                    'to_id' => $r2[0]->id,
		                    'to_name' => $r2[0]->name,
		                    'to_type' => $r[$i]->ict_to_type,
		                    'date' => $r[$i]->ict_date,
		                    'amount' => $r[$i]->ict_amount,
		                    'narration' => $r[$i]->ict_narration,
		                    ));
		            }
		            $data['transaction'] = $arr;
		            
		            $q = $this->db->query("SELECT * FROM i_opening_balance WHERE iob_e_id='$pid' AND iob_e_type='contact' AND iob_owner='$oid'");
			        if(count($q->result()) > 0) {
			            $data['opening'] = array('type' => $q->result()[0]->iob_amount_type, 'amt' => $q->result()[0]->iob_amount);    
			        } else {
			            $data['opening'] = array();
			        }
		        }    
                
                
    			print_r(json_encode($data));   
		    }
		} else {
			redirect(base_url().$this->config->item('dealer_login_red'));
		}
	}
}