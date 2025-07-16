<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transactions extends CI_Controller {

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
	public function cart() {
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
			
			$data["products"] = $this->product_model->load_cart_details($oid, $uid, $cid);
			$data['oid'] = $oid;

			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "My Cart";
			$ert['search'] = "true";
			$ert['search_placeholder'] = "Search Products";
			$ert['type'] = "dealers";
			
			$this->load->view('dealers/navbar', $ert);
			$this->load->view('dealers/transactions/cart', $data);
		} else {
			redirect(base_url().$this->config->item('dealer_login_red'));
		}
	}

	public function confirm_cart() {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			$cid = $sess_data['user_details'][0]->iu_ref;
			$dt = date('Y-m-d H:m:s');
			$tdt = date('Y-m-d');

            $this->db->insert('i_txn_nums', array('itn_type' => 'Orders', 'itn_num' => $this->input->post('txn'), 'itn_owner' => $oid));
			$query = $this->db->query("SELECT * FROM i_txn_nums WHERE itn_owner='$oid' AND itn_type='Orders'");
			$orderid = count($query->result()) + 1;

			$all_info = $this->input->post('products');
			$prod=[];$qty=[];$rate=[];$amt=[];
			for ($i=0; $i < count($all_info) ; $i++) { 
				array_push($prod, $all_info[$i]['id']);
				array_push($qty, $all_info[$i]['qty']);
				array_push($rate, $all_info[$i]['rate']);
				array_push($amt, $all_info[$i]['qty'] * $all_info[$i]['rate']);
			}
			// echo $this->input->post('total');
			// echo $orderid;
			if(isset($sess_data['sub'])) {
			    $this->product_model->confirm_cart($oid, $uid, $cid, $tdt, $orderid, null, $this->input->post('total'), 'pending', $prod, $qty, $rate, $amt, $dt, 'Application', $sess_data['sub'][0]->idu_id);
			} else {
			    $this->product_model->confirm_cart($oid, $uid, $cid, $tdt, $orderid, null, $this->input->post('total'), 'pending', $prod, $qty, $rate, $amt, $dt, 'Application', null);    
			}
			
			$this->product_model->clear_cart($oid,$uid);
			echo $orderid;
		} else {
			redirect(base_url().$this->config->item('dealer_login_red'));
		}
	}

	public function confirm($orderid) {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			
			$data['orderid'] = $orderid;
			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Order Confirmed";
			$ert['search'] = "true";
			$ert['search_placeholder'] = "Search Products";
			$ert['type'] = "dealers";
			
			$this->load->view('dealers/navbar', $ert);
			$this->load->view('dealers/transactions/confirm', $data);
		} else {
			redirect(base_url().$this->config->item('dealer_login_red'));
		}
	}

	public function orders() {
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
			$ert['title'] = "Your Orders";
			$ert['search'] = "true";
			$ert['search_placeholder'] = "Search Products";
			$ert['type'] = "dealers";
			
			$this->load->view('dealers/navbar', $ert);
			$this->load->view('dealers/transactions/orders', $data);
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
	
	public function order_details($id) {
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
			
			$data['order'] = $this->product_model->load_orders($oid, $cid, $id);
			$data['order_details'] = $this->product_model->load_order_details($oid, $uid, $id);
			
			$query = $this->db->query("SELECT * FROM i_txn_payments AS a LEFT JOIN i_txn_payments_links AS b ON a.itp_id=b.itpl_pay_id WHERE b.itpl_txn_id='$id' AND itpl_owner='$oid'");
			$data['payment'] = $query->result();
			
			$data['oid'] = $oid;
			$data['id'] = $id;

			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Order No: ".$data['order'][0]->it_txn_no;
			$ert['search'] = "true";
			$ert['search_placeholder'] = "Search Products";
			$ert['type'] = "dealers";
			
			$this->load->view('dealers/navbar', $ert);
			$this->load->view('dealers/transactions/order_details', $data);
		} else {
			redirect(base_url().$this->config->item('dealer_login_red'));
		}
	}
	
    public function order_update($orderid) {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			$cid = $sess_data['user_details'][0]->iu_ref;
			$dt = date('Y-m-d H:m:s');
			$tdt = date('Y-m-d');

			$all_info = $this->input->post('products');
			$prod=[];$qty=[];$rate=[];$amt=[];
			for ($i=0; $i < count($all_info) ; $i++) { 
				array_push($prod, $all_info[$i]['id']);
				array_push($qty, $all_info[$i]['qty']);
				array_push($rate, $all_info[$i]['rate']);
				array_push($amt, $all_info[$i]['qty'] * $all_info[$i]['rate']);
			}
			
			$query = $this->db->query("SELECT * FROM i_txns WHERE it_id='$orderid' AND it_owner='$oid'");
			$result = $query->result();
			
			if($result[0]->it_status == "pending") {
			    $data = array(
        			'it_amount' => $this->input->post('total'),
        			);
        		$this->db->where(array('it_owner' => $oid, 'it_id' => $orderid));
        		$this->db->update('i_txns', $data);
        		
        		$this->db->where(array('itd_t_id' => $orderid, 'itp_owner' => $oid));
        		$this->db->delete('i_txns_details');
        		
        		for ($i=0; $i < count($prod) ; $i++) { 
        			$data = array(
        				'itd_t_id' => $orderid,
        				'itd_p_id' => $prod[$i],
        				'itp_qty' => $qty[$i],
        				'itp_rate' => $rate[$i],
        				'itp_value' => $amt[$i],
        				'itp_tax_group_id' => 0,
        				'itp_tax' => 0,
        				'itp_amount' => $amt[$i],
        				'itp_owner' => $oid,
        				'itp_created' => $dt,
        				'itp_created_by' => $uid );
        			$this->db->insert('i_txns_details', $data);
        		}
        	    echo "true";
			} else {
			    echo "false";
			}
			
			
		} else {
			redirect(base_url().$this->config->item('dealer_login_red'));
		}
	}
	
	public function invoices() {
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
			
			$query = $this->db->query("SELECT * FROM i_txns WHERE it_owner='$oid' AND it_c_id='$cid' AND it_type IN ('Sale') ORDER BY it_date DESC");
			$data['orders'] = $query->result();

			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Your Invoices";
			$ert['search'] = "true";
			$ert['search_placeholder'] = "Search Products";
			$ert['type'] = "dealers";
			
			$this->load->view('dealers/navbar', $ert);
			$this->load->view('dealers/transactions/invoices', $data);
		} else {
			redirect(base_url().$this->config->item('dealer_login_red'));
		}
	}

	public function invoice_details($id) {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			$cid = $sess_data['user_details'][0]->iu_ref;

            $this->load->model('distributors/Transaction_Model', 'txn_model');
            
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
			
            $query = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id WHERE it_id='$id' AND it_owner='$oid' AND it_type='Sale'");
            $data["txn"] = $query->result();
            $data["txn_details"] = $this->txn_model->load_transaction_details($oid, $id);
			$data['txn_transport'] = $this->txn_model->load_transaction_transport_details($oid, $id);
			$data['txn_prod_tax'] = $this->txn_model->load_transaction_prod_tax_details($oid, $id);
			
			$data['gst_num'] = $sess_data['user_details'][0]->ic_gst_number;
			$data['bank'] = $sess_data['user_details'][0]->ic_bank_name;
			$data['branch'] = $sess_data['user_details'][0]->ic_bank_branch;
			$data['ifsc'] = $sess_data['user_details'][0]->ic_bank_ifsc;
			$data['acc'] = $sess_data['user_details'][0]->ic_bank_accno;

			$query = $this->db->query("SELECT * FROM i_taxes WHERE itx_owner = '$oid'");
			$data['taxes'] = $query->result();

			$query = $this->db->query("SELECT * FROM i_tax_group WHERE ittxg_owner = '$oid'");
			$data['tax_groups'] = $query->result();

            // $data['logo'] = base_url().'assets/uploads/'.$oid.'/logo/'.$sess_data['user_details'][0]->iu_logo;
			$data['title_doc'] = "Invoice No: ".$id;
			
			$data['oid'] = $oid;
			$data['tid'] = $id;

			$data['order'] = $this->product_model->load_orders($oid, $cid, $id);
			$data['order_details'] = $this->product_model->load_order_details($oid, $uid, $id);
			$data['oid'] = $oid;
			$data['id'] = $id;

			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Invoice No: ".$data['order'][0]->it_txn_no;
			$ert['search'] = "true";
			$ert['search_placeholder'] = "Search Products";
			$ert['type'] = "dealers";
			
			$this->load->view('dealers/navbar', $ert);
			$this->load->view('dealers/transactions/invoice_details', $data);
		} else {
			redirect(base_url().$this->config->item('dealer_login_red'));
		}
	}
	
	public function ledger() {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$cid = $sess_data['user_details'][0]->iu_ref;

            $query = $this->db->query("SELECT * FROM i_txns WHERE it_owner='$oid' AND it_c_id='$cid' AND it_type IN ('Sale') ORDER BY it_date DESC");
			$data['orders'] = $query->result();

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
			
			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Your Ledger";
			$ert['search'] = "false";
			$ert['search_placeholder'] = "Search Products";
			$ert['type'] = "dealers";
			
			$this->load->view('dealers/navbar', $ert);
			$this->load->view('dealers/transactions/ledger', $data);
		} else {
			redirect(base_url().$this->config->item('dealer_login_red'));
		}
	}
	
	public function get_payment_contact_txns() {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$cid = $sess_data['user_details'][0]->iu_ref;
			
		    $type = $this->input->post('t');
		    $from = $this->input->post('fr');
		    $to = $this->input->post('to');
		    
		    if($type == "delivery") {
		        $query = $this->db->query("SELECT id, date, descr, credit, debit, cid, info FROM ((SELECT a.it_id AS id, a.it_date AS date, CONCAT(a.it_type, ' Txn No: ', a.it_txn_no) AS descr, 0 AS credit, (a.it_amount + IFNULL(bn.ie_amount,0)) AS debit, a.it_c_id AS cid, a.it_type AS info FROM i_txns AS a LEFT JOIN i_expenses AS bn ON a.it_id=bn.ie_order_id WHERE a.it_type IN ('Delivery') AND a.it_owner='$oid' AND a.it_date BETWEEN '$from' AND '$to') UNION ALL (SELECT b.itp_id AS id, b.itp_date AS date, CONCAT(b.itp_mode, ' - ', b.itp_details) AS descr, b.itp_credit AS credit, b.itp_debit AS debit, b.itp_c_id AS cid, 'payment' AS info FROM i_txn_payments AS b WHERE itp_owner='$oid' AND itp_type='delivery' AND itp_date BETWEEN '$from' AND '$to')) results LEFT JOIN i_contacts AS y ON results.cid=y.ic_id WHERE y.ic_id = '$cid' ORDER BY date ASC");    
		    } else if($type == "invoice") {
		        $query = $this->db->query("SELECT id, date, descr, credit, debit, cid, info FROM ((SELECT a.it_id AS id, a.it_date AS date, CONCAT(a.it_type, ' Txn No: ', a.it_txn_no) AS descr, 0 AS credit, (a.it_amount + IFNULL(bn.ie_amount,0)) AS debit, a.it_c_id AS cid, a.it_type AS info FROM i_txns AS a LEFT JOIN i_expenses AS bn ON a.it_id=bn.ie_order_id WHERE a.it_type IN ('Invoice') AND a.it_owner='$oid' AND a.it_date BETWEEN '$from' AND '$to') UNION ALL (SELECT b.itp_id AS id, b.itp_date AS date, CONCAT(b.itp_mode, ' - ', b.itp_details) AS descr, b.itp_credit AS credit, b.itp_debit AS debit, b.itp_c_id AS cid, 'payment' AS info FROM i_txn_payments AS b WHERE itp_owner='$oid' AND itp_type = 'invoice' AND itp_date BETWEEN '$from' AND '$to')) results LEFT JOIN i_contacts AS y ON results.cid=y.ic_id WHERE y.ic_id = '$cid' ORDER BY date ASC");
		    }
		    
		    print_r(json_encode($query->result()));
		} else {
		    echo "login";
		}
	}

	public function get_payment_history() {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		    $pid = $this->input->post('i');
		    $query = $this->db->query("SELECT CONCAT(b.it_type, ' Txn No: ', b.it_txn_no) AS info, b.it_date AS date, b.it_amount AS amount, a.itpl_amt AS paid_amount FROM i_txn_payments_links AS a LEFT JOIN i_txns AS b ON a.itpl_txn_id=b.it_id WHERE a.itpl_owner='$oid' AND a.itpl_pay_id='$pid'");
		    print_r(json_encode($query->result()));
		} else {
		    echo "login";
		}
	}
	
}