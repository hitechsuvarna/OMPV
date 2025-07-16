<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transactions extends CI_Controller {

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
########## ORDERS ################
	public function orders() {
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
			
			$data["txn"] = $this->txn_model->load_transactions($oid, 'Orders', 'pending', null);
			$data['oid'] = $oid;


			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "My Orders";
			$ert['search'] = "false";
			$ert['search_placeholder'] = "Search Vendors";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/transactions/orders', $data);
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}

	public function filter_orders() {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			print_r(json_encode($this->txn_model->load_transactions($oid, $this->input->post('type'), $this->input->post('status'), null)));
		} else {
			echo "Please login";
		}
	}
	
	public function filter_orders_by_date() {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			
			$fdt=$this->input->post('from'); 
			$tdt=$this->input->post('to');
			$type=$this->input->post('type');
			$amount=$this->input->post('amount');
			if($amount!="") {
			    $query = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id WHERE it_owner = '$oid' AND it_type='$type' AND it_date BETWEEN '$fdt' AND '$tdt' AND it_amount <= '$amount' ORDER BY it_id, it_date");
			} else {
			    $query = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id WHERE it_owner = '$oid' AND it_type='$type' AND it_date BETWEEN '$fdt' AND '$tdt'  ORDER BY it_id, it_date");
			}
			
			print_r(json_encode($query->result()));
		} else {
			echo "Please login";
		}
	}
	
	public function filter_orders_by_name() {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$type=$this->input->post('type');
			$keyword=$this->input->post('keyword');
			if($keyword!="") {
			    $query = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id WHERE it_owner = '$oid' AND it_type='$type' AND ic_name LIKE '%$keyword%' ORDER BY it_id, it_date");
			} else {
			    $query = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id WHERE it_owner = '$oid' AND it_type='$type' ORDER BY it_id, it_date");
			}
			
			print_r(json_encode($query->result()));
		} else {
			echo "Please login";
		}
	}
	
	public function order_get_inventory() {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			
			$p = $this->input->post('p');
			$query = $this->db->query("SELECT * FROM i_products WHERE ip_owner='$oid' AND ip_name='$p'");
			$result = $query->result();
			$pid =0;
			if(count($result) > 0) $pid = $result[0]->ip_id;
			
		    $que = $this->db->query("SELECT * FROM i_inventory_accounts WHERE iia_owner='$oid' AND iia_star='1'");
		    $res = $que->result();
		    
		    $y=[];
		    for($j=0;$j<count($res);$j++) {
		        $aid=$res[$j]->iia_id;
		        $que2 = $this->db->query("SELECT ((SELECT IFNULL(SUM(iin_inward),0) FROM i_inventory_new WHERE iin_p_id='$pid' AND iin_to_type='account' AND iin_to='$aid') - (SELECT IFNULL(SUM(iin_inward),0) FROM i_inventory_new WHERE iin_p_id='$pid' AND iin_from_type='account' AND iin_from='$aid')) AS bal FROM `i_inventory_new` WHERE iin_p_id='$pid' AND iin_owner='$oid' GROUP BY iin_p_id");
		        $res2 = $que2->result();
		        
		        if(count($res2) > 0) {
		            echo $res2[0]->bal;
		        } else {
		            echo "N/A";
		        }
		    }
	    }
	}
	
	
	public function get_order_list() {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			
			$que = $this->db->query("SELECT b.ip_name AS product, a.iino_qty AS qty FROM i_inventory_new_order AS a LEFT JOIN i_products AS b ON a.iino_p_id=b.ip_id WHERE a.iino_owner='$oid'");
			print_r(json_encode($que->result()));
	    }
	}
	
	public function clear_order_list() {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		    $this->db->where(array('iino_owner' => $oid));
		    $this->db->delete('i_inventory_new_order');
			echo "true";
	    }
	}
	
	public function add_item_to_list($pid) {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			$dt = date('Y-m-d H:i:s');
			
			$q = $this->input->post('q');
			$this->db->insert('i_inventory_new_order', array('iino_p_id' => $pid, 'iino_qty' => $q, 'iino_owner' => $oid, 'iino_date' => date('Y-m-d'), 'iino_created_by' => $uid, 'iino_created' => $dt));
			echo "true";
	    }
	}
	
	public function search_orders() {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			print_r(json_encode($this->txn_model->search_transactions($oid, 'Orders', $this->input->post('keyword'), ["pending", "approved"])));
		} else {
			echo "Please login";
		}
	}

    public function add_order() {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			
			$data["vendors"] = $this->contact_model->load_contacts($oid, 'Dealer');
			$data["products"] = $this->product_model->load_products($oid);
// 			$data["taxes"] = $this->product_model->load_tax_groups($oid);

            $query = $this->db->query("SELECT * FROM i_txn_nums WHERE itn_type='Orders' AND itn_owner='$oid' ORDER BY itn_id DESC");
            $data["orders"] = $query->result()[0]->itn_num + 1;
			$data['modes'] = $this->txn_model->get_order_modes($oid);
			$data['oid'] = $oid;
			
			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Add Order";
			$ert['search'] = "false";
			$ert['search_placeholder'] = "Search Vendors";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/transactions/order_add', $data);
		} else {
			redirect(base_url().'distributors/Account/login');
		}
    }
    
    public function new_order_notify($oid) {
        ## PENDING TO BE UPDATED AS FINANCIAL YEAR
	    $query = $this->db->query("SELECT * FROM i_txns WHERE it_owner='$oid' AND it_notify='pending' AND it_type='Orders'");
	    $result = $query->result();
	    
	    $cnt = count($result);
	    $amt = 0;
	    $prod = "";
	    $tids = [];
	    for($i=0;$i<$cnt;$i++) {
	        $tid = $result[$i]->it_id;
	        array_push($tids, $tid);
	        $que = $this->db->query("SELECT * FROM i_txns_details WHERE itp_owner='$oid' AND itd_t_id ='$tid'");
	        $res = $que->result();
	        $amt+=$result[$i]->it_amount;
	    }
	    
	    if(count($tids) > 0) {
	        $ttid = implode(",", $tids);
    	    $query = $this->db->query("SELECT COUNT(a.itd_p_id) AS count, b.ip_name AS name FROM `i_txns_details` AS a LEFT JOIN i_products AS b ON a.itd_p_id=b.ip_id WHERE itd_t_id IN ($ttid) GROUP BY b.ip_id ORDER BY count DESC");
    	    $result = $query->result();
    	    $prod = $result[0]->name." is in demand.";
    	    echo $cnt." Orders for approval. Total amount is ".$amt.". ".$prod;
    	        
	    }
	    $this->db->where(array('it_owner' => $oid, 'it_notify' => 'pending'));
	    $this->db->update('i_txns', array('it_notify' => 'done'));
    }
    
    public function save_order() {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			
			$dt = date('Y-m-d H:m:s');
		    $cid = $this->contact_model->save_contact_by_name($oid, $sess_data['user_details'][0]->iu_id, $dt, $this->input->post('vendor'), 'Dealer', null, null, null, null, null);
			$tid = $this->txn_model->save_orders($oid, $sess_data['user_details'][0]->iu_id, $cid, $this->input->post('date'), $this->input->post('txn'), $this->input->post('note'), 0, 'approved', $this->input->post('name'), $this->input->post('qty'), $this->input->post('rate'), $this->input->post('amt'), $dt, $this->input->post('mode'), 'Orders');
			
// 			$this->db->where(array('itn_type' => 'Orders', 'itn_owner' => $oid));
// 			$this->db->update('i_txn_nums', array('itn_num' => $this->input->post('txn'), 'itn_date' => $this->input->post('date')))
			
			$this->db->insert('i_txn_nums', array('itn_type' => 'Orders', 'itn_num' => $this->input->post('txn'), 'itn_owner' => $oid, 'itn_date' => $this->input->post('date')));
			
			$tx_new = $this->txn_model->copy_txn_data($oid, $tid, 'Delivery');
			$this->txn_model->update_inventory_txnwise($oid, $tx_new);
			echo $tid;
		} else {
			redirect(base_url().'distributors/Account/login');
		}
    }
    
    public function order_get_rates() {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		    $dt = date('Y-m-d');
		    $n = $this->contact_model->save_contact_by_name($oid, $sess_data['user_details'][0]->iu_id, $dt, $this->input->post('d'), 'Dealer', null, null, null, null, null);
		    $pro=$this->input->post('p');
		    $query = $this->db->query("SELECT * FROM i_products WHERE ip_owner='$oid' AND ip_name ='$pro'");
		    if(count($query->result()) > 0) {
		        $p = $query->result()[0]->ip_id;    
		    } else {
		        $p=0;
		    }
		    
		    if($p!=0) {
		        $q = $this->product_model->load_products_dealers($oid, $n, $p);
    			if(count($q) > 0) {
    			    echo $q[0]->ipp_price;
    			} else {
    			    echo 0;
    			}    
		    } else {
		        echo 0;
		    }
		    
			
		} else {
			redirect(base_url().'distributors/Account/login');
		}
    }
    
	public function order_details($id) {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			
			$data["txn"] = $this->txn_model->load_transactions_with_name($oid, $id);
			if($data['txn'][0]->it_sub_dealer != null) {
			    $dui = $data['txn'][0]->it_sub_dealer;
			    $qu = $this->db->query("SELECT * FROM i_d_users WHERE idu_id='$dui' AND idu_owner='$oid'");
			    $data['subuser'] = $qu->result();
			}

			$t = $this->txn_model->load_transaction_details($oid, $id);
			$data["txn_details"] = $t;
			$data['txn_product_balance'] = [];
			for ($i=0; $i < count($t) ; $i++) { 
				$p_id = $t[$i]->itd_p_id;
				$qw = $this->txn_model->get_inventory_status($oid, $uid, $p_id, 1);
				if(count($qw) > 0 ) {
					if($qw[0]->ii_balance != "") { array_push($data['txn_product_balance'], $qw[0]->ii_balance); } else { array_push($data['txn_product_balance'], "N/A"); }
				} else {
					array_push($data['txn_product_balance'], "N/A");
				}
				
			}
            $data['products'] = $this->product_model->load_products($oid);
			$data['cid'] = $data['txn'][0]->ic_id;
			$data['outstanding'] = $this->txn_model->total_outstanding($oid, $data['txn'][0]->ic_id);

			$data['oid'] = $oid;

			$data['txnid'] = $id;
			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Order Details: ".$data['txn'][0]->it_txn_no;
			$ert['search'] = "false";
			$ert['search_placeholder'] = "Search Vendors";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/transactions/order_details', $data);
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}

	public function approve_order() {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			$dt = date('Y-m-d H:m:s');
			
			$this->txn_model->update_orders($oid, $uid, $this->input->post('txnid'), 'approved', $this->input->post('txnrow'), $this->input->post('txnrate'), $this->input->post('txnrequest'), $this->input->post('txnapprove'), $dt, $this->input->post('est_pending'));
			$new_tid = $this->txn_model->copy_txn_data($oid, $this->input->post('txnid'), 'Delivery');
			$this->txn_model->update_inventory_txnwise($oid, $new_tid);
		} else {
			redirect(base_url().'distributors/Account/login');
		}	
	}
	
	public function delete_order() {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$tid = $this->input->post('txnid');
			$query = $this->db->query("SELECT * FROM i_txns WHERE it_id='$tid' AND it_owner='$oid'");
			$result = $query->result();
			$type = $result[0]->it_type;
			
			$this->db->where(array('it_id' => $tid,'it_owner' => $oid));
			$this->db->delete('i_txns');
			
			$this->db->where(array('itn_type' => $type, 'itn_owner' => $oid, 'itn_num' => $tid));
			$this->db->order_by('itn_id', 'DESC');
			$this->db->limit(1);
			$this->db->delete('i_txn_nums');
			
			$this->txn_model->update_inventory_txnwise($oid, $tid, true);
			
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}

########## DELIVERY ################
	public function delivery() {
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
			
			
			$data["txn"] = $this->txn_model->load_transactions($oid, 'Delivery', 'approved', null);
			$data['oid'] = $oid;


			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Delivery";
			$ert['search'] = "true";
			$ert['search_placeholder'] = "Search Vendors";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/transactions/delivery', $data);
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}

	public function delivery_details($id) {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['skip_edit'])) {
			$this->session->unset_userdata('skip_edit');
			redirect(base_url().'distributors/Transactions/delivery');	
		} else if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			
			$data["txn"] = $this->txn_model->load_transactions($oid, 'Delivery', null, $id);
            
            $t = $this->txn_model->load_transaction_details($oid, $id);
			$data["txn_details"] = $t;
			$data['txn_product_balance'] = [];
			for ($i=0; $i < count($t) ; $i++) { 
				$p_id = $t[$i]->itd_p_id;
				$qw = $this->txn_model->get_inventory_status($oid, $uid, $p_id, 1);
				if(count($qw) > 0 ) {
					if($qw[0]->ii_balance != "") { array_push($data['txn_product_balance'], $qw[0]->ii_balance); } else { array_push($data['txn_product_balance'], "N/A"); }
				} else {
					array_push($data['txn_product_balance'], "N/A");
				}
				
			}
			
			$data['products'] = $this->product_model->load_products($oid);

			$data['oid'] = $oid;
            
            $query = $this->db->query("SELECT * FROM i_cash_master WHERE icm_owner='$oid'");
            $data['cash'] = $query->result();
            
            $data['txnid'] = $id;
			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Order Details: ".$data['txn'][0]->it_txn_no;
			$ert['search'] = "false";
			$ert['search_placeholder'] = "Search Vendors";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/transactions/delivery_details', $data);
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}
	
	public function load_transaction_payment_records($tid) {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			
			
		    $que = $this->db->query("SELECT * FROM i_txn_payments_new AS a LEFT JOIN i_cash_master AS b ON a.itpn_mode_id=b.icm_id WHERE a.itpn_owner='$oid' AND a.itpn_txn_id='$tid'");
            print_r(json_encode($que->result()));
            
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}

    public function fetch_transaction_payment($ptid) {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			
			
		    $que = $this->db->query("SELECT * FROM i_txn_payments_new AS a LEFT JOIN i_cash_master AS b ON a.itpn_mode_id=b.icm_id WHERE a.itpn_owner='$oid' AND a.itpn_id='$ptid'");
            print_r(json_encode($que->result()));
            
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}

    public function records_transaction_payment($loc, $tid, $type=null, $ptid=null) {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			$dt = date('Y-m-d H:i:s');
			
			$rev=false;
			if($loc=='invoice'){
			    $loc='Invoice: ';
			} else if($loc =='delivery') {
			    $loc='Delivery: ';
			} else if($loc=='purchase') {
			    $rev=true;
			    $loc='Purchase: ';
			}
			
			if($type==null) {
			    $this->db->insert('i_txn_payments_new', array(
    			    'itpn_txn_id' => $tid,
    			    'itpn_mode_id' => $this->input->post('m'),
    			    'itpn_date' => $this->input->post('d'),
    			    'itpn_amount' => $this->input->post('a'),
    			    'itpn_narration' => $this->input->post('n'),
    			    'itpn_owner' => $oid,
    			    'itpn_created' => $dt
    			    ));
    		    $ptid=$this->db->insert_id();
    			
    			$q = $this->db->query("SELECT * FROM i_txns WHERE it_id='$tid' AND it_owner='$oid'");
    			$r = $q->result();
    			
    			if($rev==false) {
        			$this->db->insert('i_cash_transactions', array(
        			    'ict_from' => $r[0]->it_c_id,
        			    'ict_from_type' => 'contact',
        			    'ict_to' => $this->input->post('m'),
        			    'ict_to_type' => 'account',
        			    'ict_amount' => $this->input->post('a'),
        			    'ict_date' => $this->input->post('d'),
        			    'ict_narration' => $loc.$this->input->post('n'),
        			    'ict_owner' => $oid,
        			    'ict_created' => $dt,
        			    'ict_created_by' => $uid,
        			    'ict_flag_col' => 'cash',
        			    'ict_txn_id' => $ptid
        			    ));
    			} else {
    			    $this->db->insert('i_cash_transactions', array(
        			    'ict_from' => $this->input->post('m'),
        			    'ict_from_type' => 'account',
        			    'ict_to' => $r[0]->it_c_id,
        			    'ict_to_type' => 'contact',
        			    'ict_amount' => $this->input->post('a'),
        			    'ict_date' => $this->input->post('d'),
        			    'ict_narration' => $loc.$this->input->post('n'),
        			    'ict_owner' => $oid,
        			    'ict_created' => $dt,
        			    'ict_created_by' => $uid,
        			    'ict_flag_col' => 'cash',
        			    'ict_txn_id' => $ptid
        			    ));
    			}
			} else if($type=='e') {
			    $this->db->where(array('itpn_id' => $ptid, 'itpn_owner' => $oid, 'itpn_txn_id' => $tid));
			    $this->db->update('i_txn_payments_new', array(
    			    'itpn_mode_id' => $this->input->post('m'),
    			    'itpn_date' => $this->input->post('d'),
    			    'itpn_amount' => $this->input->post('a'),
    			    'itpn_narration' => $this->input->post('n'),
    			    'itpn_owner' => $oid,
    			    'itpn_created' => $dt
    			    ));

                if($rev==false) {
                    $this->db->where(array('ict_txn_id' => $ptid, 'ict_owner' => $oid));
        			$this->db->update('i_cash_transactions', array(
        			    'ict_to' => $this->input->post('m'),
        			    'ict_amount' => $this->input->post('a'),
        			    'ict_date' => $this->input->post('d'),
        			    'ict_narration' => $loc.$this->input->post('n'),
        			    'ict_modified' => $dt,
        			    'ict_modified_by' => $uid
        			    ));
                } else {
                    $this->db->where(array('ict_txn_id' => $ptid, 'ict_owner' => $oid));
        			$this->db->update('i_cash_transactions', array(
        			    'ict_from' => $this->input->post('m'),
        			    'ict_amount' => $this->input->post('a'),
        			    'ict_date' => $this->input->post('d'),
        			    'ict_narration' => $loc.$this->input->post('n'),
        			    'ict_modified' => $dt,
        			    'ict_modified_by' => $uid
        			    ));
                }
        			
			} else if($type=='d') {
			    
			    $this->db->where(array('ict_txn_id' => $ptid, 'ict_owner' => $oid));
			    $this->db->delete('i_txn_payments_new');
    			    
                $this->db->where(array('itpn_id' => $ptid, 'itpn_owner' => $oid, 'itpn_txn_id' => $tid));
			    $this->db->delete('i_txn_payments_new');
			}
			
			echo 'true';
		} else {
			redirect(base_url().'distributors/Account/login');
		}
    }

    public function update_delivery_qty($type=null) {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			$cid = $sess_data['user_details'][0]->iu_ref;
			$dt = date('Y-m-d H:m:s');
			
			$t= $this->input->post('t');
			$q= $this->input->post('q');
			$r= $this->input->post('r');
			$v= $q*$r;
			$tv = 0;
			
			$this->db->where(array('itd_id' => $t, 'itp_owner' => $oid));
			$this->db->update('i_txns_details', array('itp_qty' => $q, 'itp_rate' => $r, 'itp_value' => $v, 'itp_amount' => $v));
			
			$tx = $this->input->post('tx');
			$query=$this->db->query("SELECT * FROM i_txns_details WHERE itd_t_id='$tx' AND itp_owner='$oid'");
			$result=$query->result();
			
			for($i=0;$i<count($result);$i++) {
			    $tv+=$result[$i]->itp_amount;
			}
			
			$this->db->where(array('it_owner' => $oid, 'it_id' => $tx));
			$this->db->update('i_txns', array('it_amount' => $tv));
			
			if($type!=null) {
			    $que = $this->db->query("SELECT it_id FROM i_txns WHERE it_order_txn='$tx' AND it_owner='$oid'");
			    $res = $que->result();
			    if(count($res) > 0) {
			        echo "Here: ".$res[0]->it_id;
			        $this->txn_model->update_inventory_txnwise($oid, $res[0]->it_id);    
			    }
			} else {
		        $this->txn_model->update_inventory_txnwise($oid, $tx);	    
			}
			echo $tv;
		} else {
			redirect(base_url().'distributors/Account/login');
		}	
	}
	
	public function add_delivery_qty() {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			$cid = $sess_data['user_details'][0]->iu_ref;
			$dt = date('Y-m-d H:m:s');
			
			$tx = $this->input->post('tx');
			$q = $this->input->post('q');
			$r = $this->input->post('r');
			$t = $this->input->post('tx');
			$v = $q * $r;
			$tv=0;
			
			$pid = $this->product_model->save_product($oid, $this->input->post('p'), null, '87089900', null, null, null, null, null);
			$this->db->insert('i_txns_details', array('itd_t_id' => $tx, 'itd_p_id' => $pid, 'itp_qty' => $q, 'itp_rate' => $r, 'itp_value' => $v, 'itp_tax_group_id' => 0, 'itp_tax' => 0, 'itp_amount' => $v, 'itp_owner' => $oid, 'itp_created' => $uid, 'itp_created_by' => $dt ));
			
			$data['t'] = $this->txn_model->load_transaction_details($oid, $this->input->post('tx'));
			
			$query=$this->db->query("SELECT * FROM i_txns_details WHERE itd_t_id='$tx' AND itp_owner='$oid'");
			$result=$query->result();
			
			for($i=0;$i<count($result);$i++) {
			    $tv+=$result[$i]->itp_amount;
			}
			
			$this->db->where(array('it_owner' => $oid, 'it_id' => $tx));
			$this->db->update('i_txns', array('it_amount' => $tv));
			
			$data['tot'] = $tv;
			$this->txn_model->update_inventory_txnwise($oid, $tx);
			print_r(json_encode($data));
		} else {
			redirect(base_url().'distributors/Account/login');
		}	
	}
	
	public function delete_delivery_qty() {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			$cid = $sess_data['user_details'][0]->iu_ref;
			$dt = date('Y-m-d H:m:s');
			
			$t= $this->input->post('t');
			$this->db->where(array('itd_id' => $t, 'itp_owner' => $oid));
			$this->db->delete('i_txns_details');
			
			$data['t'] = $this->txn_model->load_transaction_details($oid, $this->input->post('tx'));
			
			$tv=0;
			$tx = $this->input->post('tx');
			$query=$this->db->query("SELECT * FROM i_txns_details WHERE itd_t_id='$tx' AND itp_owner='$oid'");
			$result=$query->result();
			
			for($i=0;$i<count($result);$i++) {
			    $tv+=$result[$i]->itp_amount;
			}
			
			$this->db->where(array('it_owner' => $oid, 'it_id' => $tx));
			$this->db->update('i_txns', array('it_amount' => $tv));
			
			$data['tot'] = $tv;
			
			$this->txn_model->update_inventory_txnwise($oid, $tx);
			print_r(json_encode($data));
		} else {
			redirect(base_url().'distributors/Account/login');
		}	
	}
	
	
	
	public function delivery_update($status) {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			$cid = $sess_data['user_details'][0]->iu_ref;
			$dt = date('Y-m-d H:m:s');
			
			$tid = $this->input->post('txnid');
			$txn = $this->txn_model->update_order_status($oid, $uid, $tid, $status, $dt);
			
			if($status == "shipping") {
			    $this->txn_model->update_inventory_new($oid, $uid, $dt, 'ndebit', $tid, 'true');
			}
			echo $txn;
		} else {
			redirect(base_url().'distributors/Account/login');
		}	
	}

	public function print_delivery($id) {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			$dt = date('Y-m-d H:m:s');
			$dat = array('skip_edit' => "true");
			$this->session->set_userdata($dat);

			$data["txn"] = $this->txn_model->load_transactions_with_name($oid, $id);
			$data['txn_details'] = $this->txn_model->load_transaction_details($oid, $id);
			$data['txn_transport'] = $this->txn_model->load_transaction_transport_details($oid, $id);
			$data['gst_num'] = $sess_data['user_details'][0]->ic_gst_number;
			$data['logo'] = base_url().'assets/uploads/'.$oid.'/logo/'.$sess_data['user_details'][0]->iu_logo_sec;
			
			$query = $this->db->query("SELECT itp_mode FROM i_txn_payments WHERE itp_owner = '$oid' GROUP BY itp_mode");
			$data["modes"] = $query->result();
			
			
			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$data['title_doc'] = "Order Details: ".$id;
			$ert['title'] = "Shipping Details: ".$data['txn'][0]->it_txn_no;
			$ert['search'] = "false";
			$ert['search_placeholder'] = "Search Vendors";
			$ert['type'] = "distributors";
			$ert['tid'] = $id;
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/transactions/delivery_print', $data);
		} else {
			redirect(base_url().'distributors/Account/login');
		}	
	}
	
	public function print_delivery_tester($id) {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			$dt = date('Y-m-d H:m:s');
			$dat = array('skip_edit' => "true");
			$this->session->set_userdata($dat);

			$data["txn"] = $this->txn_model->load_transactions_with_name($oid, $id);
			$data['txn_details'] = $this->txn_model->load_transaction_details($oid, $id);
			$data['txn_transport'] = $this->txn_model->load_transaction_transport_details($oid, $id);
			$data['gst_num'] = $sess_data['user_details'][0]->ic_gst_number;
			$data['logo'] = base_url().'assets/uploads/'.$oid.'/logo/'.$sess_data['user_details'][0]->iu_logo_sec;
			
			$query = $this->db->query("SELECT itp_mode FROM i_txn_payments WHERE itp_owner = '$oid' GROUP BY itp_mode");
			$data["modes"] = $query->result();
			
			
			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$data['title_doc'] = "Order Details: ".$id;
			$ert['title'] = "Shipping Details: ".$data['txn'][0]->it_txn_no;
			$ert['search'] = "false";
			$ert['search_placeholder'] = "Search Vendors";
			$ert['type'] = "distributors";
			$ert['tid'] = $id;
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/delivery_print', $data);
		} else {
			redirect(base_url().'distributors/Account/login');
		}	
	}
	
	public function delivery_delete() {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			
			$this->db->where(array('it_owner' => $oid, 'it_id' => $this->input->post('txnid')));
			$this->db->delete('i_txns');
			
			$this->db->where(array('itp_owner' => $oid, 'itd_t_id' => $this->input->post('txnid')));
			$this->db->delete('i_txns_details');
			
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}
	
	public function get_pdf($id) {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			$dt = date('Y-m-d H:m:s');
			$dat = array('skip_edit' => "true");
			$this->session->set_userdata($dat);

            $txn = $this->txn_model->load_transactions_with_name($oid, $id);
			$txn_details = $this->txn_model->load_transaction_details($oid, $id);
			$txn_transport = $this->txn_model->load_transaction_transport_details($oid, $id);
			$gst_num = $sess_data['user_details'][0]->ic_gst_number;
			$logo = base_url().'assets/uploads/'.$oid.'/logo/'.$sess_data['user_details'][0]->iu_logo_sec;
			
// 			$this->load->library('Pdf');
            
//             $pdf = new Pdf('L', 'mm', 'A4', true, 'UTF-8', false);
//             $pdf->SetTitle('Delivery Challan');
//             $pdf->SetHeaderMargin(10);
//             $pdf->SetTopMargin(10);
//             $pdf->setFooterMargin(10);
//             $pdf->SetAutoPageBreak(true);
//             $pdf->SetAuthor('Author');
//             $pdf->SetDisplayMode('real', 'default');
            
//             $pdf->AddPage('L', 'A4');
//             $pdf->Cell(0, 0, 'A4 LANDSCAPE', 1, 1, 'C');

            
            
        	$typearr = ["Original","Duplicate"]; 
        	$typeidarr = ["original", "duplicate"];
        	$content = "<style> .col_right {text-align: right; padding-right: 10px; } .col_left {text-align: left; width: 30%; } .item_header > th {font-weight: bold; border:0.5px solid; } .item_name > td { border: 0.5px solid; height: 20px;} @page {size: A4; size:landscape; } @media print { #duplicate {/*page-break-before: always;*/ } } </style><table style=\"width:100%;border:2px solid;\"><tr>";
        	for ($j=0; $j < count($typearr); $j++) { 
        		$content.= '<td>';
        		$content.= '<div style="border: 0px #999 solid;padding: 20px;" id="'.$typeidarr[$j].'">';
        		$content.= '<div style="border: 0px #000 solid;">';
        		$content.= '<img src="'.$logo.'" style="width: auto; height: 100px;margin-bottom:10px;">';
        		$content.= '<div style="text-align: center">Delivery Challan - '.$typearr[$j].'</div>';
        		$content.= '<table border="0" style="width: 100%;">';
        		$content.= '<tr>';
        		$content.= '<td style="width: 50%;">';
        		$content.= 'Client Name:';
        		$content.= '<b>'.$txn[0]->ic_name.'</b><br>';
        		$content.= $txn[0]->ic_address.'<br>';
        		if($txn[0]->ic_gst_number !== "") $content.= 'GST: '.$txn[0]->ic_gst_number;
        		$content.= '</td>';
        		$content.= '<td>';
        		$content.= '<div style="text-align: right;">';
        		$content.= '</div>';
        		$content.= '<div style="text-align: right;">';
        		
        		$content.= '<table style="width: 100%;padding: 10px;">';
        		$content.= '<tr>';
        		$content.= '<td class="col_right">';
        		$content.= '<b>Challan No:</b>';
        		$content.= '</td>';
        		$content.= '<td class="col_left">';
        		$content.= '<u>#'.$txn[0]->it_txn_no.'</u>';
        		$content.= '</td>';
        		$content.= '</tr>';
        		$content.= '<tr>';
        		$content.= '<td class="col_right">';
        		$content.= '<b>Date:</b>';
        		$content.= '</td>';
        		$content.= '<td class="col_left">';
        		$content.= '<u>'.date_format(date_create($txn[0]->it_date), 'd/m/Y').'</u>';
        		$content.= '</td>';
        		$content.= '</tr>';
        		$content.= '</table>';
        		
        		$content.= '</div>';
        		$content.= '</td>';
        		$content.= '</tr>';
        		$content.= '<tr>';
        		$content.= '<td colspan="2">';
        		if(count($txn_transport) > 0 ) $content.= 'Delivery through: <u>'.$txn_transport[0]->ittd_transporter.'</u> ';
        		if(count($txn_transport) > 0 ) $content.= 'L/R No.: <u>'.$txn_transport[0]->ittd_lrno.'</u> ';
        		if(count($txn_transport) > 0 ) $content.= 'Date: <u>'.$txn_transport[0]->ittd_date.'</u> ';
        		$content.= '</td>';
        		$content.= '</tr>';
        		$content.= '</table>';
        
        		$content.= '</div>';
        		$content.= '<div style="text-align: left;">';
        		$content.= '<hr>';
        		$content.= '<b style="font-size:1.1em;">Order Details</b>';
        		$content.= '</div>';
        		$content.= '<div style="border: 0px #000 solid;">';
        		$content.= '<table border="0" cellspacing=0 style="width: 100%;border:1px solid; border-radius:5px;">';
        		$content.= '<thead>';
        		$content.= '<tr class="item_header">';
        		$content.= '<th>Sr. No.</th>';
        		$content.= '<th>Particulars</th>';
        		$content.= '<th>Qty</th>';
        		$content.= '<th>Rate</th>';
        		$content.= '<th>Amount</th>';
        		$content.= '</tr>';
        		$content.= '</thead>';
        		$content.= '<tbody>';
        		
        		$line_cnt = count($txn_details);
        		
                $line_cnt_total = 14 - $line_cnt;
                $amt=0;
                for ($i=0; $i < count($txn_details) ; $i++) { 
                    $amt+=$txn_details[$i]->itp_amount;
        			$content.= '<tr class="item_name"><td>'.($i+1).'</td><td>'.$txn_details[$i]->ip_name.'</td><td class="detail_center" style="text-align:center;">'.$txn_details[$i]->itp_qty.'</td><td class="detail_right" style="text-align:right;">'.$txn_details[$i]->itp_rate.'</td><td class="detail_right" style="text-align:right;">'.$txn_details[$i]->itp_amount.'</td></tr>';
        		}
        		for ($i=0; $i < $line_cnt_total; $i++) {
        			$content.= '<tr class="item_name"><td> </td><td></td><td class="detail_center" style="text-align:center;"> </td><td class="detail_right" style="text-align:right;"></td><td class="detail_right" style="text-align:right;"></td></tr>';
        		}
        		$content.= '</tbody>';
        		$content.= '<tfoot>';
        		$content.= '<tr class="item_name"><td colspan="5">Receivers Signature</td></tr>';
        		$content.= '<tr class="item_name"><td colspan="5">Payment within: '.$txn[0]->ic_credit.' days</td></tr>';
        		$content.= '<tr class="item_name"><td colspan="3" rowspan="2">Note: '.$txn[0]->it_note.'</td><td>Subtotal</td><td>'.$amt.'</td></tr><tr class="item_name"><td>Grand Total</td><td>'.$amt.'</td></tr>';
        		$content.= '</tfoot>';
        		$content.= '</table>';
        		$content.= '</div>';
        		# $content.= '<div style="border: 0px #000 solid;">';
        		#$content.= '<table border="0" style="width: 100%;text-align: left;padding-top: 10px;">';
        		#$content.= '<tr>';
        		#$content.= '<th colspan="2">Terms:</th>';
        		#$content.= '</tr>';
        		#for ($i=0; $i < count($terms) ; $i++) {$content.= "<tr>"; $content.= "<td>".($i+1)."</td>"; $content.= "<td>".$terms[$i]->iextdt_term."</td>"; $content.= "</tr>"; }
        		#$content.= '</table>';
        		// $content.= '<b>GST No:'.$gst_num.'</b>';
        		// $content.= '</div>';
        		$content.= '<table>';
        		$content.= '<tr>';
        		$content.= '<td style="width:100%;"><div style="font-size:0.7em;font-weight:bold;">TERMS & CONDITIONS</div><div style="font-size:0.5em; line-height:1em;">1. Subject to mumbai Jurisdiction. 2. Goods supplied on order will not be accepted back. 3. Payment terms - Immidiate. Intrest @ 18% per annum will be charged on delayed payments. 4. Warranty/Service of purchased goods is the manufacturers/ importers responsibility under the warranty period. 5. I the undersigned have accepted the terms and conditions of the invoice. 6. O M Prime Ventures shall not be responsible for any expenses involving legal costs in case of a dispute 7. Received goods in order and good condition.</div><div style="font-size:0.6em;">E.&.O.E.</div></td>';
        		$content.= '<td>';
        		$content.= '<div style="text-align: right;margin-top: 4em;white-space:nowrap;">';
        		$content.= '<b style="border-top: 1px #000 solid;padding-top: 10px; font-size:0.9em;">Proprietor/ Authorized Signature</b>';
        		$content.= '</div>';
        		$content.= '</td>';
        		$content.= '</tr>';
        		$content.= '</table>';
        		$content.= '<hr>';
        		$content.= '</div>';
        
        		$content.= "</td>";
        	}
        	$content.= "</tr></table>";
        	
            
    
        	$file = 'challan-new.pdf';
        	$t = $_SERVER['DOCUMENT_ROOT'].'/ompv/assets/'.$file;
        // 	echo $t;
            // $pdf->writeHTML($html, true, false, true, false, '');
            try {
            $this->load->library('Wkhtmltopdf');
            $wkhtmltopdf = new Wkhtmltopdf(array('path' => '/'));
            $wkhtmltopdf->setTitle("Title");
            // $wkhtmltopdf->setHtml($content);
            $wkhtmltopdf->getUrl("http://www.evomata.com");
            $wkhtmltopdf->output(Wkhtmltopdf::MODE_DOWNLOAD, "file.pdf");
            } catch(Exception $e) {
                echo $e->getMessage();
            }
            
            
            // $pdf->Output($t, 'F');
			
// 			$ftp_server = "ftp.hsuvarna.com";
//             $ftp_conn = ftp_connect($ftp_server) or die("Could not connect to $ftp_server");
//             $login = ftp_login($ftp_conn, 'oned@onedynamics.in', 'QWE!@#123');
            
//             if (ftp_put($ftp_conn, "/".$oid."/".$file, $t, FTP_ASCII)) {
//                 // echo "Successfully uploaded $file.";
//             } else {
//                 ftp_mkdir($ftp_conn, $oid);
//                 if (ftp_put($ftp_conn, "/".$oid."/".$file, $t, FTP_ASCII)) {
//                     // echo "Successfully uploaded $file.";
//                 }
//             }
//             ftp_close($ftp_conn);
//             redirect('http://onedynamics.in/ompv/'.$oid.'/'.$file);
		} else {
			redirect(base_url().'distributors/Account/login');
		}	
	}
	
	

	public function update_transport_details($tid) {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			$data = array(
			    'it_note' => $this->input->post('note'),
			    'it_txn_no' => $this->input->post('challan')
			    );
			$this->db->where(array('it_id' => $tid, 'it_owner' => $oid));
			$this->db->update('i_txns', $data);
			
			$que = $this->db->query("SELECT * FROM i_expenses WHERE ie_owner='$oid' AND ie_order_id='$tid'");
			$res = $que->result();
			
			if(count($res) > 0) {
			    $data = array(
    			    'ie_description' => $this->input->post('transporter').' via L/R no.'.$this->input->post('lrno').' GST No: '.$this->input->post('gstno').' to '.$this->input->post('state'),
    			    'ie_amount' => $this->input->post('expenses'),
    			    'ie_order_id' => $tid,
    			    'ie_status' => 'done',
    			    'ie_date' => $this->input->post('date'),
    			    'ie_owner' => $oid,
    			    'ie_created' => date('Y-m-d H:m:s'),
    			    'ie_created_by' => $uid
    			    );
    			 $this->db->where(array('ie_owner' => $oid, 'ie_id' => $res[0]->ie_id));
    			 $this->db->update('i_expenses', $data);
    			 $eid = $res[0]->ie_id;
			} else {
			    $data = array(
    			    'ie_description' => $this->input->post('transporter').' via L/R no.'.$this->input->post('lrno').' GST No: '.$this->input->post('gstno').' to '.$this->input->post('state'),
    			    'ie_amount' => $this->input->post('expenses'),
    			    'ie_order_id' => $tid,
    			    'ie_status' => 'done',
    			    'ie_date' => $this->input->post('date'),
    			    'ie_owner' => $oid,
    			    'ie_created' => date('Y-m-d H:m:s'),
    			    'ie_created_by' => $uid
    			    );
    			 $this->db->insert('i_expenses', $data);
    			 $eid = $this->db->insert_id();
			}
			
			$que = $this->db->query("SELECT it_c_id, it_txn_no FROM i_txns WHERE it_owner='$oid' AND it_id='$tid'");
			$res = $que->result();
			
			$this->db->insert('i_txn_payments', array('itp_t_id' => $tid, 'itp_c_id' => $res[0]->it_c_id, 'itp_date' => $this->input->post('date'), 'itp_mode' => $this->input->post('acc'), 'itp_details' => 'Transport for Delivery challan '.$res[0]->it_txn_no, 'itp_owner' =>$oid, 'itp_created' => date('Y-m-d H:i:s'), 'itp_created_by' => $uid, 'itp_type' => 'delivery', 'itp_credit' => 0, 'itp_debit' => $this->input->post('expenses'), 'itp_agreement' => 'debit'));
			
			
			$que = $this->db->query("SELECT * FROM i_txn_transport_details WHERE ittd_owner='$oid' AND ittd_txn_id='$tid'");
			$res = $que->result();
			
			if(count($res) > 0) {
			    $data = array(
    				'ittd_txn_id' => $tid,
    				'ittd_transporter' => $this->input->post('transporter'),
    				'ittd_lrno' => $this->input->post('lrno'),
    				'ittd_date' => $this->input->post('date'),
    				'ittd_transporter_gstno' => $this->input->post('gstno'),
    				'ittd_state' => $this->input->post('state'),
    				'ittd_account' => $this->input->post('acc'),
    				'ittd_expense' => $eid,
    				'ittd_owner' => $oid);
    			$this->db->where(array('ittd_txn_id' => $tid, 'ittd_owner' => $oid));
    			$this->db->update('i_txn_transport_details', $data);   
			} else {
			    $data = array(
    				'ittd_txn_id' => $tid,
    				'ittd_transporter' => $this->input->post('transporter'),
    				'ittd_lrno' => $this->input->post('lrno'),
    				'ittd_date' => $this->input->post('date'),
    				'ittd_transporter_gstno' => $this->input->post('gstno'),
    				'ittd_state' => $this->input->post('state'),
    				'ittd_account' => $this->input->post('acc'),
    				'ittd_expense' => $eid,
    				'ittd_owner' => $oid);
    			$this->db->insert('i_txn_transport_details', $data);
			}
			echo "true";
		} else {
			redirect(base_url().'distributors/Account/login');
		}	
	}
	
	public function testingdata() {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		
			$this->load->model('Entity_Model','entity_model');
// 			print_r($this->entity_model->load_contacts($oid, null, 'ompv pur', null));

            $data = array('test'=>'1');
            
            $data = $data + array('test2' => '2');
            print_r($data);
			
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}

########## INVENTORY ################
	public function inventory() {
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
			
			
			$data["category"] = $this->product_model->load_categories($oid);
            $data["txn"] = $this->txn_model->load_inventory_summary($oid, $uid);
			$data['oid'] = $oid;
            $data["products"] = $this->product_model->load_products($oid);
            $data["vendors"] = $this->contact_model->load_contacts($oid);
            
			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Inventory";
			$ert['search'] = "true";
			$ert['search_placeholder'] = "Search Vendors";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/transactions/inventory', $data);
		} else {
			redirect(base_url().'distributors/Account/login');
		}		
	}

    public function filter_inventory() {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
		    print_r(json_encode($this->txn_model->load_inventory_summary($oid, $uid, $this->input->post('search'))));
		} else {
		    echo "Please login again";
		}
    }
    
    public function search_inventory() {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
		    print_r(json_encode($this->txn_model->load_inventory_summary($oid, $uid, null, $this->input->post('keywords'))));
		} else {
		    echo "Please login again";
		}
    }
    
    public function inventory_details($id) {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			
			$data["txn"] = $this->txn_model->get_inventory_all_details($oid, $uid, $id);
			
			$t = $data["txn"];
			$bal = 0;
			for($i=0; $i<count($t); $i++) {
			    $bal+= ($t[$i]->ii_inward) - ($t[$i]->ii_outward);
			}
			
			$data["bal"] = $bal;
			
			$data['oid'] = $oid;
			$data['pid'] = $id;


			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Inventory Details";
			$ert['search'] = "false";
			$ert['search_placeholder'] = "Search Vendors";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/transactions/inventory_details', $data);
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}
	
	public function add_inventory() {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		
			$data["vendors"] = $this->contact_model->load_contacts($oid);
			$data["products"] = $this->product_model->load_products($oid);
// 			$data["taxes"] = $this->product_model->load_tax_groups($oid);
            $data['oid'] = $oid;


			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Add Inventory";
			$ert['search'] = "false";
			$ert['search_placeholder'] = "Search Vendors";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/transactions/inventory_add', $data);
		} else {
			redirect(base_url().'distributors/Account/login');
		}
    }
    
    public function save_inventory() {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			
			$dt = date('Y-m-d H:m:s');
		    $cid = $this->contact_model->save_contact_by_name($oid, $uid, $dt, $this->input->post('vendor'), 'Vendor', null, null, null, null, null);
		    
		    $prod = $this->input->post('name'); $qty = $this->input->post('qty');
		    
		    for($i=0;$i<count($prod); $i++) {
		        
		        $pid = $this->product_model->save_product_by_name($oid, $uid, $dt, $prod[$i]);
		        
		        $query = $this->db->query("SELECT * FROM i_inventory WHERE ii_p_id='$pid' AND ii_owner='$oid'");
		        $result = $query->result();
		        
		        $bal=0;
		        if(count($result) > 0) {
		            $bal = ($qty[$i]) + ($result[count($result) - 1]->ii_balance);
		        } else {
		            $bal = ($qty[$i]);
		        }
		        
    		    $data = array(
    		        'ii_c_id' => $cid,
    		        'ii_txn_num' => $this->input->post('txn'),
    		        'ii_txn_date' => $this->input->post('date'),
    		        'ii_type' => 'credit',
    		        'ii_p_id' => $pid,
    		        'ii_inward' => $qty[$i],
    		        'ii_outward' => 0,
    		        'ii_balance' => $bal,
    		        'ii_owner' => $oid,
    		        'ii_created' => $uid,
    		        'ii_created_by' => $dt,
    		        );
    		    $this->db->insert('i_inventory', $data);
    		    
		    }
		    
		} else {
			redirect(base_url().'distributors/Account/login');
		}
    }
    
    public function save_inventory_return($irid=null) {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			
			$dt = date('Y-m-d H:i:s');
			
			$c = $this->input->post('c');
			$p = $this->input->post('p');
			$q = $this->input->post('q');
			$d = $this->input->post('d');
			$n = $this->input->post('n');
			
			$query = $this->db->query("SELECT * FROM i_contacts WHERE ic_name='$c' AND ic_owner='$oid'");
			$result = $query->result();
			
			if(count($result) > 0) {
			    $cid = $result[0]->ic_id;
			} else {
			    $cid=0;
			}
			
			if($irid!=null) {
			    $this->db->where(array('iir_owner' =>$oid, 'iir_id' => $irid));
			    $this->db->update('i_inventory_return', array(
    		        'iir_c_id' => $cid,
    		        'iir_date' => $d,
    		        'iir_note' => $n,
    		        'iir_owner' => $oid,
    		        'iir_modified' =>$dt,
    		        'iir_modified_by' => $uid
    		        ));
    		        
    		    $this->db->where(array('ii_order_txn' =>$irid, 'ii_owner' =>$oid, 'ii_type' => 'return'));
    		    $this->db->delete('i_inventory');
			} else {
			    $this->db->insert('i_inventory_return', array(
    		        'iir_c_id' => $cid,
    		        'iir_date' => $d,
    		        'iir_note' => $n,
    		        'iir_owner' => $oid,
    		        'iir_created' =>$dt,
    		        'iir_created_by' => $uid
    		        ));
    		    $irid = $this->db->insert_id();
    		    
			}
			
			
		    for($i=0;$i<count($p);$i++) {
			    $que = $this->db->query("SELECT * FROM i_products WHERE ip_name='$p[$i]' AND ip_owner='$oid'");
			    $res = $que->result();
			    
			    if(count($res) > 0) {
			        $pid = $res[0]->ip_id;
			    } else {
			        $pid = 0;
			    }
			    
			    $this->db->insert('i_inventory', array(
			        'ii_c_id' => $cid,
			        'ii_order_txn' => $irid,
			        'ii_type' => 'return',
			        'ii_p_id' => $pid,
			        'ii_inward' => $q[$i],
			        'ii_owner' =>$oid,
			        'ii_created' => $dt,
			        'ii_created_by' => $uid));
			}
			echo 'true';
			
		} else {
		    echo "logout";
		}
    }
    
    public function search_inventory_return() {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			
			$c = $this->input->post('c');
			$d = $this->input->post('d');
			
			$query = $this->db->query("SELECT * FROM i_inventory_return AS a LEFT JOIN i_contacts AS b ON a.iir_c_id=b.ic_id WHERE a.iir_owner='$oid' AND b.ic_name = '$c' AND a.iir_date='$d'");
	        print_r(json_encode($query->result()));
		} else {
		    echo 'logout';
		}
    }
    
    public function get_inventory_return() {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			
			$i = $this->input->post('i');
			
			$query = $this->db->query("SELECT * FROM i_inventory AS a LEFT JOIN i_products AS b ON a.ii_p_id=b.ip_id WHERE a.ii_owner='$oid' AND a.ii_order_txn = '$i' AND a.ii_type='return'");
	        $data['rec'] = $query->result();
	        
	        $query = $this->db->query("SELECT a.iir_date, b.ic_name FROM i_inventory_return AS a LEFT JOIN i_contacts AS b ON a.iir_c_id=b.ic_id  WHERE a.iir_id='$i' AND a.iir_owner='$oid'");
	        $data['main'] = $query->result();
	        
	        print_r(json_encode($data));
		} else {
		    echo 'logout';
		}
    }
    
    public function reconcile_inventory($pid=null) {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			
			$dt = date('Y-m-d H:i:s');
			
			if($pid!=null) {
			    $query = $this->db->query("SELECT * FROM i_inventory WHERE ii_p_id='$pid' AND ii_owner='$oid'");
    			$result = $query->result();
    			
    			$in=0;$out=0;$bal=0;
    			for($i=0;$i<count($result);$i++) {
    			    if($result[$i]->ii_inward == 0) {
    			        $out+=$result[$i]->ii_outward;
    			    } else if($result[$i]->ii_outward == 0) {
    			        $in+=$result[$i]->ii_inward;
    			    }
    			    $bal=$in-$out;
    			    $this->db->where(array('ii_owner' => $oid, 'ii_id' => $result[$i]->ii_id));
    			    $this->db->update('i_inventory', array('ii_balance' => $bal));
    			}
			} else {
			    $que = $this->db->query("SELECT * FROM i_products WHERE ip_owner='$oid'");
			    $res = $que->result();
			    
			    for($j=0;$j<count($res);$j++) {
			        $pid = $res[$j]->ip_id;
			        
			     //   $query = $this->db->query("SELECT it_id, it_txn_no, it_c_id, it_date, (SELECT itp_qty FROM i_txns_details WHERE itd_p_id='$pid' AND itd_t_id=a.it_id GROUP BY itd_p_id) AS qty FROM i_txns AS a WHERE it_date >= '2019-01-30' AND it_type='Delivery' AND it_owner='$oid'");
			     //   $result = $query->result();
			        
			     //   $this->db->where(array('ii_type' => 'ndebit', 'ii_owner' => $oid, 'ii_p_id' => $pid));
			     //   $this->db->delete('i_inventory');
			        
			     //   for($i=0;$i<count($result);$i++) {
			     //       $this->db->insert('i_inventory', array(
			     //           'ii_c_id' => $result[$i]->it_c_id,
			     //           'ii_order_id' => $result[$i]->it_txn_no,
			     //           'ii_order_txn' => $result[$i]->it_it,
			     //           'ii_type' => 'ndebit',
			     //           'ii_p_id' => $pid,
			     //           'ii_inward' => 0,
			     //           'ii_outward' => $result[$i]->qty,
			     //           'ii_balance' => 0,
			     //           'ii_owner' => $oid,
			     //           'ii_created' => $dt,
			     //           'ii_created_by' => $uid,
			     //           'ii_txn_date' => $result[$i]->it_date
			     //           ));
			     //   }
			        
			        
			        
			        $query = $this->db->query("SELECT * FROM i_inventory WHERE ii_p_id='$pid' AND ii_owner='$oid'");
        			$result = $query->result();
        			
        			$in=0;$out=0;$bal=0;
        			for($i=0;$i<count($result);$i++) {
        			    if($result[$i]->ii_inward == 0) {
        			        $out+=$result[$i]->ii_outward;
        			    } else if($result[$i]->ii_outward == 0) {
        			        $in+=$result[$i]->ii_inward;
        			    }
        			    $bal=$in-$out;
        			    $this->db->where(array('ii_owner' => $oid, 'ii_id' => $result[$i]->ii_id));
        			    $this->db->update('i_inventory', array('ii_balance' => $bal));
        			}
			    }
			}
			
			echo 'true';
		} else {
			redirect(base_url().'distributors/Account/login');
		}
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
			
			
			$query = $this->db->query("SELECT * FROM i_inventory_accounts WHERE iia_owner='$oid'");
			$data['accounts'] = $query->result();
			
			$query = $this->db->query("SELECT * FROM i_inventory_accounts WHERE iia_star='1' AND iia_owner='$oid'");
			$r = $query->result();
			if(count($r) > 0) {
			    $data['default_account'] = $r[0]->iia_name;
			    $data['default_account_id'] = $r[0]->iia_id;
			}
			
			$data['oid'] = $oid;
            
            $query = $this->db->query("SELECT ica_category_name FROM i_category WHERE ica_owner='$oid'");
            $data['category'] = $query->result();
            
            $query= $this->db->query("SELECT ip_name FROM i_products WHERE ip_owner='$oid'");
            $data["products"] = $query->result();
            
            $query = $this->db->query("SELECT * FROM i_contacts WHERE ic_section='Vendor'");
            $data["vendors"] = $query->result();
            
			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Inventory";
			$ert['search'] = "true";
			$ert['search_placeholder'] = "Search Vendors";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/inventory/inventory', $data);
		} else {
			redirect(base_url().'distributors/Account/login');
		}		
	}
	
	
    // ISSUE EXISTS HERE. QTY NOT MATCHING WITH SEARCH_INVENTORY_DETAILS
	public function search_inventory_by_category($ctid) {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
		    
		    $query = $this->db->query("SELECT * FROM i_category WHERE ica_parent_category='$ctid' AND ica_owner='$oid'");
			$data['category'] = $query->result();
			
		    $query = $this->db->query("SELECT ica_parent_category FROM i_category WHERE ica_id='$ctid' AND ica_owner='$oid'");
			if(count($query->result()) > 0) {
			    $data['parent'] = $query->result()[0]->ica_parent_category;
			} else {
			    $data['parent'] = 0;
			}
		    
		    $q = $this->db->query("SELECT IFNULL(GROUP_CONCAT(ip_id), 0) AS product_ids FROM i_products WHERE ip_category='$ctid' AND ip_owner='$oid'");
		    $r = $q->result();
		    
		    
		    $output=[];
		    if(count($r) > 0) {
		        $str = $r[0]->product_ids;
		        $q1 = $this->db->query("SELECT 
		p.ip_id, p.ip_name, p.ip_category, p.ip_description, p.ip_lower_limit,
		SUM(CASE
            WHEN a.iin_from = b.iia_id  THEN -a.iin_inward  
         	WHEN b.iia_id IS NULL THEN 0 
         	
            ELSE a.iin_inward  
        END) AS bal 
   FROM `i_inventory_new` AS a 
   LEFT JOIN i_inventory_accounts AS b ON 
        (CASE
         	WHEN a.iin_from_type='account' AND a.iin_to_type='account' AND a.iin_from='0' THEN a.iin_to=b.iia_id	
         	WHEN a.iin_from_type='account' AND a.iin_to_type='contact' THEN a.iin_from=b.iia_id
         	WHEN a.iin_from_type='contact' AND a.iin_to_type='account' THEN a.iin_to=b.iia_id
         	
         	ELSE 0=1
         END) 
       RIGHT JOIN i_products AS p ON a.iin_p_id=p.ip_id
        WHERE ip_id IN ($str) AND ip_owner='$oid'
        GROUP BY a.iin_p_id
ORDER BY `a`.`iin_id` ASC
");
                $output = $q1->result();
                // print_r($this->db->last_query());
		    }
//                 $q = $this->db->query("SELECT ip_id, ip_name, ip_category, ip_description, iin_from_type, iin_to_type, iia_id, iia_name, ip_lower_limit, total_available_quantity AS bal FROM (
// SELECT 
//     p.ip_id,
//     p.ip_name,
//     p.ip_category,
//     p.ip_description,
//     t.iin_from_type,
//     t.iin_to_type,
//     a.iia_id,
//     a.iia_name,
//     p.ip_lower_limit,
//     SUM(
//         CASE
//             WHEN t.iin_from = a.iia_id  THEN -t.iin_inward  
//          	WHEN a.iia_id IS NULL THEN 0 
//             ELSE t.iin_inward
//         END
//     ) AS total_available_quantity
// FROM i_products p
// LEFT JOIN i_inventory_new t ON p.ip_id = t.iin_p_id
// LEFT JOIN i_inventory_accounts a ON (CASE
//          	WHEN t.iin_from_type='account' AND t.iin_to_type='account' AND t.iin_from='0' THEN t.iin_to=a.iia_id	
//          	WHEN t.iin_from_type='account' AND t.iin_to_type='contact' THEN t.iin_from=a.iia_id
//          	WHEN t.iin_from_type='contact' AND t.iin_to_type='account' THEN t.iin_to=a.iia_id
         	
//          	ELSE 0=1
//          END)
// WHERE p.ip_category = '$ctid'
// -- AND t.iin_from_type = 'account'
// -- AND t.iin_to_type = 'account'  -- Ensuring only internal transfers
// GROUP BY p.ip_id, p.ip_name, a.iia_id, a.iia_name, p.ip_lower_limit
// HAVING total_available_quantity > 0  -- Only show accounts with inventory
// ORDER BY a.iia_name ASC) AS subquery

// WHERE subquery.iin_from_type='account' AND subquery.iin_to_type='account';");
            
                // for($j=0; $j<count($r); $j++) {
                //     array_push($product_array, array(
                //         'ip_id' => $r[$j]->ip_id,
                //         'ip_name' => $r[$j]->ip_name,
                //         'ip_category' => $r[$j]->ip_category,
                //         'ip_description' => $r[$j]->ip_description,
                //         'iia_id' => $r[$j]->iia_id,
                //         'iin_from_type' => $r[$j]->iin_from_type,
                //         'iin_to_type' => $r[$j]->iin_to_type,
                //         'iia_name' => $r[$j]->iia_name,
                //         'ip_lower_limit' => $r[$j]->ip_lower_limit,
                //         'bal' => $r[$j]->bal
                //     ));    
                // }
                
                $data['product'] = $output;
// 			}
// 			$data['product'] = $product_array;
    			
			print_r(json_encode($data));
		}
	}
	
	// DATA MATCHES TO SEARCH_INVENTORY_DETAILS
	public function search_inventory_by_name() {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			
			$pname = $this->input->post('p');
		    
		    $data['category'] = [];
			$data['parent'] = 0;
			
			$main_arr = [];

            $q = $this->db->query("
SELECT 
    p.ip_id,
    p.ip_name,
    p.ip_category,
    p.ip_description,
    t.iin_from_type,
    t.iin_to_type,
    a.iia_id,
    a.iia_name,
    p.ip_lower_limit,
    SUM(
        CASE
            WHEN t.iin_from = a.iia_id  THEN -t.iin_inward  
         	WHEN a.iia_id IS NULL THEN 0 
         	
            ELSE t.iin_inward 
        END
    ) AS bal
FROM i_products p
LEFT JOIN i_inventory_new t ON p.ip_id = t.iin_p_id
LEFT JOIN i_inventory_accounts a ON (CASE
         	WHEN t.iin_from_type='account' AND t.iin_to_type='account' AND t.iin_from='0' THEN t.iin_to=a.iia_id	
         	WHEN t.iin_from_type='account' AND t.iin_to_type='contact' THEN t.iin_from=a.iia_id
         	WHEN t.iin_from_type='contact' AND t.iin_to_type='account' THEN t.iin_to=a.iia_id
         	
         	ELSE 0=1
         END) 
WHERE p.ip_description LIKE '%$pname'
GROUP BY p.ip_id
-- HAVING bal > 0  -- Only show accounts with inventory
ORDER BY a.iia_name ASC");
            // $r = $q->result();
            
    //         for($i=0; $i<count($r); $i++) {
    //             array_push($main_arr, array(
			 //       'ip_id' => $r[$i]->ip_id,
			 //       'ip_name' => $r[$i]->ip_name,
			 //       'ip_lower_limit' => $r[$i]->ip_lower_limit,
			 //       'ip_category' => $r[$i]->ip_category,
			 //       'ip_description' => $r[$i]->ip_description,
			 //       'bal' => $r[$i]->total_available_quantity
			 //   ));        
    //         }
        
            $data['product'] = $q->result();
			
            print_r(json_encode($data));
		}
	}
	
	public function search_inventory_locations() {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			
			$aid = $this->input->post('a');
			if($aid) {
			    $query = $this->db->query("SELECT * FROM i_inventory_accounts WHERE iia_owner='$oid' AND iia_id='$aid' ORDER BY iia_name");    
			} else {
			    $query = $this->db->query("SELECT * FROM i_inventory_accounts WHERE iia_owner='$oid' ORDER BY iia_name");
			}
		    
            $data['location'] = $query->result();
			print_r(json_encode($data));
		}
	}
	
	public function search_inventory_by_category_name() {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$p_a = $this->input->post('keywords');
			$q = $this->db->query("SELECT ica_id, ica_category_name FROM i_category WHERE ica_category_name LIKE '%$p_a%' AND ica_owner='$oid'");
			$r = $q->result();
			print_r(json_encode($r));
		}
	}
	
	// PRIMARY FUNCTION FOR OUTPUT COMPARISION  
	public function search_inventory_details() {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
		    $pid = $this->input->post('p');
		    $type = $this->input->post('t');
		    
		    $low_limit=0;
		    if($type == "p") {
                $q=$this->db->query("
SELECT 
    p.ip_id,
    p.ip_name,
    t.iin_from_type,
    t.iin_to_type,
    a.iia_id,
    a.iia_name,
    p.ip_lower_limit,
    SUM(
        CASE
            WHEN t.iin_from = a.iia_id  THEN -t.iin_inward  
         	WHEN a.iia_id IS NULL THEN 0 
         	
            ELSE t.iin_inward 
        END
    ) AS total_available_quantity
FROM i_products p
LEFT JOIN i_inventory_new t ON p.ip_id = t.iin_p_id
LEFT JOIN i_inventory_accounts a ON (CASE
         	WHEN t.iin_from_type='account' AND t.iin_to_type='account' AND t.iin_from='0' THEN t.iin_to=a.iia_id	
         	WHEN t.iin_from_type='account' AND t.iin_to_type='contact' THEN t.iin_from=a.iia_id
         	WHEN t.iin_from_type='contact' AND t.iin_to_type='account' THEN t.iin_to=a.iia_id
         	
         	ELSE 0=1
         END) 
WHERE p.ip_id = '$pid'
GROUP BY p.ip_id, a.iia_id
HAVING total_available_quantity != 0  -- Only show accounts with inventory
ORDER BY a.iia_name ASC");
                
                $r = $q->result();
                $y=[];
                for($i=0;$i<count($r); $i++) {
                    $low_limit = $r[$i]->ip_lower_limit;
                    array_push($y, array('id' => $r[$i]->iia_id, 'account' => $r[$i]->iia_name, 'bal' => $r[$i]->total_available_quantity));
                }
		        
		    } else if($type=="l") {
		        $y=[];
		        $aid = $pid;
		        
		        $ctx=$this->input->post('c');
		        
		        // --------------------------------------------------------------------------------------------
		        //AI GEN NEW SECTION
		      
               $q = $this->db->query("SELECT * FROM (SELECT p.ip_id, p.ip_name, p.ip_description, p.ip_lower_limit, t.iin_from_type, t.iin_to_type, a.iia_id, a.iia_name,
                                SUM(
                                    CASE
                                        WHEN t.iin_from = a.iia_id THEN -t.iin_inward  -- Deduct inventory from source account
                                        ELSE t.iin_inward  -- Add inventory to destination account
                                    END
                                ) AS total_available_quantity
                        FROM i_products p
                        LEFT JOIN i_inventory_new t ON p.ip_id = t.iin_p_id
                        LEFT JOIN i_inventory_accounts a ON t.iin_from = a.iia_id OR t.iin_to = a.iia_id
                        WHERE 
                            a.iia_id = '$aid'
                            -- AND t.iin_from_type = 'account'
                            -- AND t.iin_to_type = 'account'  -- Ensure only internal transfers are considered
                        GROUP BY p.ip_id, p.ip_name, p.ip_description, p.ip_lower_limit, a.iia_id, a.iia_name
                        HAVING total_available_quantity > 0  -- Show only accounts with inventory
                        ORDER BY p.ip_id ASC) AS subquery WHERE subquery.iin_from_type='account' AND subquery.iin_to_type='account';
                    ");
                $r = $q->result();
                for($i=0; $i<count($r); $i++) {
                    if($r[$i]->total_available_quantity != 0) {
                        array_push($y, array('id' => $r[$i]->ip_id, 'product' => $r[$i]->ip_name, 'code' => $r[$i]->ip_description, 'bal' => $r[$i]->total_available_quantity, 'low' => $r[$i]->ip_lower_limit));       
                    }
                }
                
		    }
		    print_r(json_encode(array('id' => $pid, 'low' => $low_limit, 'stock' => $y )));
		}
	}
	
	public function get_inventory_log() {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			
			$pid = $this->input->post('p');
			$aid = $this->input->post('a');
			
			$q = $this->db->query("SELECT a.iin_from AS frm, a.iin_from_type AS frm_typ , b.ic_name AS frm_con, d.iia_name AS frm_acc, a.iin_to AS t, a.iin_to_type AS t_typ, c.ic_name AS t_con, e.iia_name AS t_acc, a.iin_inward AS qty, a.iin_date AS date FROM `i_inventory_new` AS a LEFT JOIN i_contacts AS b ON a.iin_from=b.ic_id LEFT JOIN i_contacts AS c ON a.iin_to=c.ic_id LEFT JOIN i_inventory_accounts AS d ON a.iin_from=d.iia_id LEFT JOIN i_inventory_accounts AS e ON a.iin_to=e.iia_id WHERE a.iin_p_id='$pid' ORDER BY iin_date DESC");
			print_r(json_encode($q->result()));
		} else {
		    echo "login";
		}
	}
	
	public function inventory_new_get_product_category_child($ctid) {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			
			$query = $this->db->query("SELECT * FROM i_category WHERE ica_parent_category='$ctid' AND ica_owner='$oid'");
			$data['category'] = $query->result();
			
			$query = $this->db->query("SELECT ica_parent_category FROM i_category WHERE ica_id='$ctid' AND ica_owner='$oid'");
			if(count($query->result()) > 0) {
			    $data['parent'] = $query->result()[0]->ica_parent_category;
			} else {
			    $data['parent'] = 0;
			}
			
			$query = $this->db->query("SELECT ip_id, ip_name, ip_lower_limit, ip_category FROM i_products AS a WHERE a.ip_category='$ctid' AND a.ip_owner='$oid'");
			$result = $query->result();
			
			$x=[];
			for($i=0;$i<count($result);$i++) {
			    $que = $this->db->query("SELECT * FROM i_inventory_accounts WHERE iia_owner='$oid'");
			    $res = $que->result();
			    
			    $pid = $result[$i]->ip_id;
			    $y=[];
			    for($j=0;$j<count($res);$j++) {
			        $aid=$res[$j]->iia_id;
			        $que2 = $this->db->query("SELECT ((SELECT IFNULL(SUM(iin_inward),0) FROM i_inventory_new WHERE iin_p_id='$pid' AND iin_to_type='account' AND iin_to='$aid') - (SELECT IFNULL(SUM(iin_inward),0) FROM i_inventory_new WHERE iin_p_id='$pid' AND iin_from_type='account' AND iin_from='$aid')) AS bal FROM `i_inventory_new` WHERE iin_p_id='$pid' GROUP BY iin_p_id");
			        $res2 = $que2->result();
			        
			        if(count($res2) > 0) {
			            array_push($y, array('account' => $res[$j]->iia_name, 'bal' => $res2[0]->bal));    
			        } else {
			            array_push($y, array('account' => $res[$j]->iia_name, 'bal' => 0));
			        }
			    }
			    
			    array_push($x,array(
			        'id' => $result[$i]->ip_id,
			        'name' => $result[$i]->ip_name,
			        'category' => $result[$i]->ip_category,
			        'limit' => $result[$i]->ip_lower_limit,
			        'stock' => $y
			        ));
			}
			$data['product'] = $x;
			print_r(json_encode($data));
		}
    }
    
    public function inventory_clear_stock($t) {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$pid = $this->input->post('p');
			
// 			if($t=='p') {
// 			    $this->db->where(array('iin_owner' => $oid, 'iin_p_id' => $pid));
// 			    $this->db->delete('i_inventory_new');
			    
// 			    echo 'true';
// 			} else if($t=='c') {
// 			    $q = $this->db->query("SELECT * FROM i_products WHERE ip_category='$pid' AND ip_owner='$oid'");
// 			    $r = $q->result();
// 			    if(count($r) > 0) {
// 			        for($i=0;$i<count($r);$i++) {
//     			        $x = $r[$i]->ip_id;
//     			        $this->db->where(array('iin_owner' => $oid, 'iin_p_id' => $x));
//     			        $this->db->delete('i_inventory_new');
//     			    }
//     			    echo 'true';
// 			    } else {
// 			        echo 'false';
// 			    }
// 			} else if($t=='l') {
// 			    $x="((iin_to='".$pid."' AND iin_to_type='account')) AND iin_owner='".$oid."'";
// 			    $this->db->where($x);
// 			    $this->db->delete('i_inventory_new');
			    
// 			    echo 'true';
			    
// 			}

            
		}
    }
    
    public function inventory_reset_records() {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			
			$c=$this->input->post('c');
			$p=$this->input->post('p');
			$l=$this->input->post('l');
			$in=$this->input->post('in');
			$out=$this->input->post('out');
			
			$c_arr=[];
			for($i=0;$i<count($c);$i++) {
			    $q = $this->db->query("SELECT ica_id FROM i_category WHERE ica_category_name='$c[$i]' AND ica_owner='$oid'");
			    array_push($c_arr, $q->result()[0]->ica_id);
			}
			
			$p_arr=[];
			for($i=0;$i<count($p);$i++) {
			    $q = $this->db->query("SELECT ip_id FROM i_products WHERE ip_name='$p[$i]' AND ip_owner='$oid'");
			    array_push($p_arr, $q->result()[0]->ip_id);
			}
			
			$l_arr=[];
			for($i=0;$i<count($l);$i++) {
			    $q = $this->db->query("SELECT iia_id FROM i_inventory_accounts WHERE iia_name='$l[$i]' AND iia_owner='$oid'");
			    array_push($l_arr, $q->result()[0]->iia_id);
			}
			$p_str=implode(',' , $p_arr);
			$l_str=implode(',', $l_arr);
			
			if($in == 'true') {
			 //   DELETE FROM LOCATION IF AVAILABLE ELSE DELETE ALL
			    if(count($l_arr) > 0) {
			     //   DELETE CATEGORY IF AVAILABLE OR PRODUCT
			        if(count($c_arr) > 0) {
			            $this->db->where('iin_to IN ("$l_str") AND iin_p_id IN (SELECT ip_id FROM i_products WHERE ip_category IN ('.implode(",", $c_arr).'))');        
			        } else if($p_str != '') {
			            $this->db->where('iin_to IN ('.$l_str.') AND iin_p_id IN ('.$p_str.')');    
			        } else {
			            $this->db->where('iin_to IN ('.$l_str.')');    
			        }
			    } else {
			        if(count($c_arr) > 0) {
			            $this->db->where('iin_p_id IN (SELECT ip_id FROM i_products WHERE ip_category IN ('.implode(",", $c_arr).'))');
			        } else {
			            $this->db->where('iin_p_id IN ('.$p_str.')');
			        }
			    }
			    $this->db->delete('i_inventory_new');
			}
			
			if($out == 'true') {
			    //   DELETE FROM LOCATION IF AVAILABLE ELSE DELETE ALL
			    if(count($l_arr) > 0) {
			        //   DELETE CATEGORY IF AVAILABLE OR PRODUCT
			        if(count($c_arr) > 0) {
			            $this->db->where('iin_from IN ('.$l_str.') AND iin_p_id IN (SELECT ip_id FROM i_products WHERE ip_category IN ('.implode(",", $c_arr).'))');        
			        } else if($p_str != '') {
			            $this->db->where('iin_from IN ('.$l_str.') AND iin_p_id IN ('.$p_str.')');    
			        } else {
			            $this->db->where('iin_from IN ('.$l_str.')');
			        }
			    } else {
			        if(count($c_arr) > 0) {
			            $this->db->where('iin_p_id IN (SELECT ip_id FROM i_products WHERE ip_category IN ('.implode(",", $c_arr).'))');
			        } else {
			            $this->db->where('iin_p_id IN ('.$p_str.')');
			        }
			    }
			    
			    $this->db->delete('i_inventory_new');
			}
			echo 'true';
		}
    }
    
    public function search_inventory_accounts() {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$p_a = $this->input->post('keywords');
			$q = $this->db->query("SELECT iia_id, iia_name FROM i_inventory_accounts WHERE iia_name LIKE '%$p_a%' AND iia_owner='$oid'");
			$r = $q->result();
			print_r(json_encode($r));
		}
    }
    
    public function inventory_new_search_product_category_child() {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			
			$data['category'] = [];
			
			$data['parent'] = 0;
			
			$pn = $this->input->post('pn');
			$query = $this->db->query("SELECT ip_id, ip_name, ip_lower_limit, ip_category FROM i_products AS a WHERE a.ip_name LIKE '%$pn%' AND a.ip_owner='$oid'");
			$result = $query->result();
			
			$x=[];
			for($i=0;$i<count($result);$i++) {
			    $que = $this->db->query("SELECT * FROM i_inventory_accounts WHERE iia_owner='$oid' ");
			    $res = $que->result();
			    
			    $pid = $result[$i]->ip_id;
			    $y=[];
			    for($j=0;$j<count($res);$j++) {
			        $aid=$res[$j]->iia_id;
			        $que2 = $this->db->query("SELECT ((SELECT IFNULL(SUM(iin_inward),0) FROM i_inventory_new WHERE iin_p_id='$pid' AND iin_to_type='account' AND iin_to='$aid') - (SELECT IFNULL(SUM(iin_inward),0) FROM i_inventory_new WHERE iin_p_id='$pid' AND iin_from_type='account' AND iin_from='$aid')) AS bal FROM `i_inventory_new` WHERE iin_p_id='$pid' GROUP BY iin_p_id");
			        $res2 = $que2->result();
			        
			        if(count($res2) > 0) {
			            array_push($y, array('account' => $res[$j]->iia_name, 'bal' => $res2[0]->bal));    
			        } else {
			            array_push($y, array('account' => $res[$j]->iia_name, 'bal' => 0));
			        }
			    }
			    
			    array_push($x,array(
			        'id' => $result[$i]->ip_id,
			        'name' => $result[$i]->ip_name,
			        'category' => $result[$i]->ip_category,
			        'limit' => $result[$i]->ip_lower_limit,
			        'stock' => $y
			        ));
			}
			$data['product'] = $x;
			print_r(json_encode($data));
		}
    }
    
    public function save_inventory_new_account($aid=null) {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			$dt = date('Y-m-d H:i:s');
			$p_a = $this->input->post('n_p');
			$q = $this->db->query("SELECT * FROM i_inventory_accounts WHERE iia_name='$p_a' AND iia_owner='$oid'");
			$r = $q->result();
			
			if(count($r) > 0) {
			    $pa_id = $r[0]->iia_id;
			} else {
			    $pa_id = 0;    
			}
		
			if($aid==null) {
			    $this->db->insert('i_inventory_accounts', array('iia_name' => $this->input->post('n'), 'iia_owner' => $oid, 'iia_created' => $dt, 'iia_created_by' => $uid, 'iia_parent' => $pa_id ));    
			} else {
			    $this->db->where(array('iia_owner' => $oid, 'iia_id' => $aid));
			    $this->db->update('i_inventory_accounts', array('iia_name' => $this->input->post('n'), 'iia_modified' => $dt, 'iia_modified_by' => $uid, 'iia_parent' => $pa_id ));
			}
			
			$query = $this->db->query("SELECT * FROM i_inventory_accounts WHERE iia_owner='$oid' ORDER BY iia_id DESC");
			print_r(json_encode($query->result()));
		}
    }
    
    public function delete_inventory_new_account($aid) {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			
		    $this->db->where(array('iia_owner' => $oid, 'iia_id' => $aid));
			$this->db->delete('i_inventory_accounts');
			
			$query = $this->db->query("SELECT * FROM i_inventory_accounts WHERE iia_owner='$oid'");
			print_r(json_encode($query->result()));
		}
    }
    
    public function star_inventory_new_account($aid) {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			
		    $this->db->where(array('iia_owner' => $oid));
			$this->db->update('i_inventory_accounts', array('iia_star' => 0));
			
			$this->db->where(array('iia_owner' => $oid, 'iia_id' => $aid));
			$this->db->update('i_inventory_accounts', array('iia_star' => 1));
			
			$query = $this->db->query("SELECT * FROM i_inventory_accounts WHERE iia_owner='$oid'");
			print_r(json_encode($query->result()));
		}
    }
    
    
    
    public function inventory_new_get_list($type) {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$term = $this->input->post('term');
			if($type == "c") {
			    $query = $this->db->query("SELECT ic_id AS value, ic_name AS label FROM i_contacts WHERE ic_owner='$oid' AND ic_name LIKE '%$term%'");    
			} else if($type == "i") {
			    $query = $this->db->query("SELECT iia_id AS value, iia_name AS label FROM i_inventory_accounts WHERE iia_owner='$oid' AND iia_name LIKE '%$term%'");    
			}
			print_r(json_encode($query->result()));
		}
    }
    
    public function save_inventory_new_records() {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			$dt = date('Y-m-d H:i:s');
			
			$f = $this->input->post('f');
			if($this->input->post('f_t') == "true") {
			    $f_t="contact";
			} else {
			    $f_t="account";
			}
			
			$t = $this->input->post('t');
			if($this->input->post('t_t') == "true") {
			    $t_t="contact";
			} else {
			    $t_t="account";
			}
			
			$d = $this->input->post('d');
			$l = $this->input->post('l');
			
			
			for($i=0;$i<count($l);$i++) {
			    $p=$l[$i]['p']; $q=$l[$i]['q'];
			    $que = $this->db->query("SELECT * FROM i_products WHERE ip_name='$p' AND ip_owner='$oid'");
			    $res = $que->result();
			    
			    if(count($res) > 0) {
			        $this->db->insert('i_inventory_new', array(
			            'iin_from' => $f,
			            'iin_from_type' => $f_t,
			            'iin_to' => $t,
			            'iin_to_type' => $t_t,
			            'iin_p_id' => $res[0]->ip_id,
			            'iin_inward' => $q,
			            'iin_outward' =>0,
			            'iin_order_id' => time(),
			            'iin_order_txn' => time(),
			            'iin_date' => $d,
			            'iin_owner' => $oid,
			            'iin_created' => $dt,
			            'iin_created_by' => $uid,
			            ));
			    }
			}
			echo 'true';
		} else {
		    echo 'logout';
		}
    }
    
    public function fetch_inventory_new_records() {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			
			$a = $this->input->post('a');
			$p = $this->input->post('p');
			$f = $this->input->post('f');
			$t = $this->input->post('t');
			if($this->input->post('a_t') == "true") {
			    $a_t = "contact";
			} else {
			    $a_t = "account";
			}
			
			$query = $this->db->query("SELECT * FROM i_products WHERE ip_owner='$oid' AND ip_name='$p'");
			$result = $query->result();
			
			$pid=0;
			if(count($result) > 0) {
			    $pid=$result[0]->ip_id;
			}
			
			$query = $this->db->query("SELECT a.iin_id AS id, a.iin_from_type AS from_type, a.iin_from AS frm, a.iin_to AS t_o, a.iin_to_type AS to_type, b.ic_name AS from_name, c.ic_name AS to_name, d.iia_name AS from_acc, e.iia_name AS to_acc, f.ip_name AS product, a.iin_inward AS qty, a.iin_date AS dt FROM i_inventory_new AS a LEFT JOIN i_contacts AS b ON a.iin_from=b.ic_id LEFT JOIN i_contacts AS c ON a.iin_to=c.ic_id LEFT JOIN i_inventory_accounts AS d ON a.iin_from=d.iia_id LEFT JOIN i_inventory_accounts AS e ON a.iin_to=e.iia_id LEFT JOIN i_products AS f ON a.iin_p_id=f.ip_id WHERE (a.iin_from_type='$a_t' AND a.iin_from='$a' OR a.iin_to_type='$a_t' AND a.iin_to='$a') AND a.iin_p_id='$pid' AND a.iin_date BETWEEN '$f' AND '$t' AND a.iin_owner='$oid' AND a.iin_parent='0'");
			print_r(json_encode($query->result()));
		}
    }
    
    public function inventory_new_order_list_update() {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			$dt = date('Y-m-d H:i:s');
			
			$p = $this->input->post('p');
			$q = $this->input->post('q');
			
			$this->db->insert('i_inventory_new_order', array('iino_p_id' => $p, 'iino_qty' => $q, 'iino_date' => date('Y-m-d'), 'iino_owner' => $oid, 'iino_created_by' => $uid, 'iino_created' => $uid ));
			echo 'true';
		}
    }
    
    public function inventory_new_fetch_order_list() {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			
			$query = $this->db->query("SELECT * FROM i_inventory_new_order AS a LEFT join i_products AS b ON a.iino_p_id=b.ip_id WHERE a.iino_owner='$oid'");
			print_r(json_encode($query->result()));
		}
    }
    
    public function inventory_new_delete_order_item() {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$i=$this->input->post('i');
			
			$this->db->where(array('iino_id' => $i, 'iino_owner' => $oid));
			$this->db->delete('i_inventory_new_order');
			
			$query = $this->db->query("SELECT * FROM i_inventory_new_order AS a LEFT join i_products AS b ON a.iino_p_id=b.ip_id WHERE a.iino_owner='$oid'");
			print_r(json_encode($query->result()));
		}
    }
    
    public function inventory_new_clear_order_list() {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$i=$this->input->post('i');
			
			$this->db->where(array('iino_owner' => $oid));
			$this->db->delete('i_inventory_new_order');
		}
    }
    
    public function inventory_new_clear_single_product() {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			
			$this->db->where(array('iin_owner' => $oid, 'iin_p_id' => $this->input->post('p')));
			$this->db->delete('i_inventory_new');
		}
    }
    

########## PURCHASE ################
	public function purchase() {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			
			$data["txn"] = $this->txn_model->load_transactions($oid, 'Purchase', 'pending', null);
			$data['oid'] = $oid;

            $q=$this->db->query("SELECT * FROM i_users WHERE iu_owner='$oid' AND iu_id='$uid'");
			if($q->result()[0]->iu_status=="blocked") {
			    $this->session->unset_userdata('user_details');
        		$this->session->unset_userdata('status');
        		$this->session->unset_userdata('fy');
        		redirect(base_url().'distributors/Account/login');
			}
			

			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Purchase";
			$ert['search'] = "true";
			$ert['search_placeholder'] = "Search Vendors";
			$ert['type'] = "distributors";
			
			$q = $this->db->query("SELECT * FROM i_u_access WHERE iua_u_id='$uid' AND iua_owner='$oid'");
			if(count($q->result()) > 0) { if($q->result()[0]->iua_u_purchase_pricing == 'true') {
			    $data['price_display'] = 'true';    
			} else {
			    $data['price_display'] = 'false';
			} } else {
			    $data['price_display'] = 'false';
			}
			
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/transactions/purchase', $data);
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}

	public function filter_purchase() {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			
			$s = $this->input->post('status');
			$c = $this->input->post('client');
			
			if($c!="") {
			    $query = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id WHERE a.it_type='Purchase' AND a.it_status='$s' AND b.ic_name LIKE '%$c%' AND a.it_owner='$oid'");
			} else {
			    $query = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id WHERE a.it_type='Purchase' AND a.it_status='$s' AND a.it_owner='$oid'");
			}
 			
			print_r(json_encode($query->result()));
		} else {
			echo "Please login";
		}
	}

	public function purchase_add() {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		    $uid = $sess_data['user_details'][0]->iu_id;
		    
		    $data["vendors"] = $this->contact_model->load_contacts($oid, 'Vendor');
			$data["products"] = $this->product_model->load_products($oid);
			
			$query = $this->db->query("SELECT * FROM i_inventory AS a LEFT JOIN i_products AS b ON a.ii_p_id=b.ip_id LEFT JOIN i_contacts AS c ON a.ii_c_id=c.ic_id WHERE a.ii_owner='$oid' GROUP BY a.ii_order_id, a.ii_txn_num, a.ii_id ORDER BY a.ii_id DESC");
		    $data["inventory"] = $query->result();
			$data['oid'] = $oid;

            $query = $this->db->query("SELECT * FROM i_taxes WHERE itx_owner = '$oid'");
			$data['taxes'] = $query->result();

			$query = $this->db->query("SELECT * FROM i_tax_group WHERE ittxg_owner = '$oid'");
			$data['tax_groups'] = $query->result();
			
			$query = $this->db->query("SELECT * FROM i_ac_ledgers WHERE iacl_owner='$oid'");
            $data['ledgers'] = $query->result();

            $query = $this->db->query("SELECT * FROM i_modules WHERE im_name='Purchase'");
            $data['default_ledger'] = $query->result();
            
            $q = $this->db->query("SELECT * FROM i_u_access WHERE iua_u_id='$uid' AND iua_owner='$oid'");
			if(count($q->result()) > 0) { if($q->result()[0]->iua_u_purchase_pricing == 'true') {
			    $data['price_display'] = 'true';    
			} else {
			    $data['price_display'] = 'false';
			} } else {
			    $data['price_display'] = 'false';
			}
			
			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Add Purchase";
			$ert['search'] = "false";
			$ert['search_placeholder'] = "Search Vendors";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/transactions/purchase_add', $data);
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}
	
	public function purchase_get_rates() {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		    $dt = date('Y-m-d');
		    $n = $this->contact_model->save_contact_by_name($oid, $sess_data['user_details'][0]->iu_id, $dt, $this->input->post('d'), 'Vendor', null, null, null, null, null);
		    $pro=$this->input->post('p');
		    $query = $this->db->query("SELECT * FROM i_products WHERE ip_owner='$oid' AND ip_name ='$pro'");
		    if(count($query->result()) > 0) {
		        $p = $query->result()[0]->ip_id;    
		    } else {
		        $p=0;
		    }
		    
		    if($p!=0) {
		        $q = $this->product_model->load_products_dealers($oid, $n, $p);
    			if(count($q) > 0) {
    			    echo $q[0]->ipp_price;
    			} else {
    			    echo 0;
    			}    
		    } else {
		        echo 0;
		    }
		    
			
		} else {
			redirect(base_url().'distributors/Account/login');
		}
    }
	
	public function purchase_load_inventory() {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		    $txnid = $this->input->post('txnid');
		    $query = $this->db->query("SELECT * FROM i_inventory WHERE ii_owner='$oid' AND ii_id='$txnid'");
		    $result = $query->result();
		    
		    $cid = $result[0]->ii_c_id;
		    $txn = $result[0]->ii_txn_num;
		    $txdt = $result[0]->ii_txn_date;
		    
		    $query = $this->db->query("SELECT * FROM i_inventory AS a LEFT JOIN i_products AS b ON a.ii_p_id=b.ip_id WHERE a.ii_owner='$oid' AND a.ii_c_id='$cid' AND a.ii_txn_num='$txn' AND a.ii_txn_date='$txdt'");
		    $result = $query->result();
		    
		    print_r(json_encode($result));
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}

	public function save_purchase() {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			$dt = date('Y-m-d H:i:s');

            $this->db->where(array('im_name'=>'Purchase'));
            $this->db->update('i_modules', array('im_secondary_ledger' => $this->input->post('default_ledger'), 'im_secondary_ledger_to' => $this->input->post('default_ledger_to')));
            
            $this->load->model('distributors/Accounting_Model','acc_model');
			$cid = $this->contact_model->save_contact_by_name($oid, $uid, $dt, $this->input->post('vendor'), 'Vendor', null, null, null, null, null);
            $tid = $this->acc_model->save_purchase($oid, $cid, $uid, $dt, $this->input->post('date'), $this->input->post('txn'), null, null, "pending", $this->input->post('name'), $this->input->post('qty'), $this->input->post('rate'), $this->input->post('tax_group'), null, null, $this->input->post('discount'), $this->input->post('freight'), $this->input->post('credit'), $this->input->post('transporter'), $this->input->post('lrno'), $this->input->post('t_tdate'), $this->input->post('t_gstno'), $this->input->post('t_state'), $this->input->post('t_tamt'), $this->input->post('inv_link'), $this->input->post('sec_type'));
            $this->txn_model->update_inventory_txnwise_purchase($oid, $tid);
            echo $tid;
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}
	
	public function purchase_ticket_upload($pid) {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			$upload_dir = $this->config->item('document_rt')."assets/uploads/".$oid."/purchase/".$pid."/";
			if(!file_exists($upload_dir)) {
				mkdir($upload_dir, 0777, true);
			}
			$img_path = "";
			if (is_dir($upload_dir) && is_writable($upload_dir)) {
				$sourcePath = $_FILES['use']['tmp_name']; // Storing source path of the file in a variable
				$targetPath = $upload_dir.$_FILES['use']['name']; // Target path where file is to be stored
				// $img_path = $targetPath;
				move_uploaded_file($sourcePath,$targetPath) ; // Moving Uploaded file		
				$img_path = $_FILES['use']['name'];
			}

			$data = array('it_file' => $img_path);
			$this->db->where(array('it_owner'=>$oid, 'it_id'=>$pid));
			$this->db->update('i_txns', $data);

			echo $eid;
		}
	}

	public function purchase_edit($pid) {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			
		    $data["inventory"] = [];
			$data["vendors"] = $this->contact_model->load_contacts($oid, 'Vendor');
			$data["products"] = $this->product_model->load_products($oid);
			$data["edit_txn"] = $this->txn_model->load_transactions($oid, 'Purchase', null, $pid);
			$data["edit_txn_details"] = $this->txn_model->load_transaction_details($oid, $pid);
            $data["txn"] = $this->txn_model->load_transactions($oid, 'Purchase', null, $pid);
			$data["txn_details"] = $this->txn_model->load_transaction_details($oid, $pid);
			$data['txn_transport'] = $this->txn_model->load_transaction_transport_details($oid, $pid);
			$data['txn_prod_tax'] = $this->txn_model->load_transaction_prod_tax_details($oid, $pid);
			$data['gst_num'] = $sess_data['user_details'][0]->ic_gst_number;
			$data['bank'] = $sess_data['user_details'][0]->ic_bank_name;
			$data['branch'] = $sess_data['user_details'][0]->ic_bank_branch;
			$data['ifsc'] = $sess_data['user_details'][0]->ic_bank_ifsc;
			$data['acc'] = $sess_data['user_details'][0]->ic_bank_accno;
			$data['title_doc'] = "Purchase";
            $query = $this->db->query("SELECT * FROM i_taxes WHERE itx_owner = '$oid'");
			$data['taxes'] = $query->result();
			
			$query = $this->db->query("SELECT * FROM i_ac_ledgers WHERE iacl_owner='$oid'");
            $data['ledgers'] = $query->result();

            $query = $this->db->query("SELECT * FROM i_modules WHERE im_name='Purchase'");
            $data['default_ledger'] = $query->result();
            

			$query = $this->db->query("SELECT * FROM i_tax_group WHERE ittxg_owner = '$oid'");
			$data['tax_groups'] = $query->result();
			
			$query = $this->db->query("SELECT * FROM i_cash_master WHERE icm_owner='$oid'");
            $data['cash'] = $query->result();
            
			$data['oid'] = $oid;
			$data['tid'] = $pid;

			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Edit Purchase";
			$ert['search'] = "false";
			$ert['search_placeholder'] = "Search Vendors";
			$ert['type'] = "distributors";
			
			$q = $this->db->query("SELECT * FROM i_u_access WHERE iua_u_id='$uid' AND iua_owner='$oid'");
			if(count($q->result()) > 0) { if($q->result()[0]->iua_u_purchase_pricing == 'true') {
			    $data['price_display'] = 'true';    
			} else {
			    $data['price_display'] = 'false';
			} } else {
			    $data['price_display'] = 'false';
			}
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/transactions/purchase_add', $data);
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}

	public function update_purchase($tid) {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			$dt = date('Y-m-d H:m:s');

            $this->db->where(array('im_name'=>'Purchase'));
            $this->db->update('i_modules', array('im_secondary_ledger' => $this->input->post('default_ledger'), 'im_secondary_ledger_to' => $this->input->post('default_ledger_to')));
            
			$this->load->model('distributors/Accounting_Model','acc_model');
			$cid = $this->contact_model->save_contact_by_name($oid, $uid, $dt, $this->input->post('vendor'), 'Vendor', null, null, null, null, null);
            
            $tid = $this->acc_model->save_purchase($oid, $cid, $uid, $dt, $this->input->post('date'), $this->input->post('txn'), null, null, "pending", $this->input->post('name'), $this->input->post('qty'), $this->input->post('rate'), $this->input->post('tax_group'), null, $tid, $this->input->post('discount'), $this->input->post('freight'), $this->input->post('credit'), $this->input->post('transporter'), $this->input->post('lrno'), $this->input->post('t_tdate'), $this->input->post('t_gstno'), $this->input->post('t_state'), $this->input->post('t_tamt'), $this->input->post('inv_link'), $this->input->post('sec_type'));
            
            $this->txn_model->update_inventory_txnwise_purchase($oid, $tid);
            echo $tid;
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}

    public function delete_purchase($tid) {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		    $uid = $sess_data['user_details'][0]->iu_id;
		    $dt = date('Y-m-d H:i:s');
		    
		    $this->db->where(array('it_id' => $tid, 'it_owner' => $oid, 'it_type' => 'Purchase'));
		    $this->db->delete('i_txns');
		    
		    $this->db->where(array('itd_t_id' => $tid, 'itp_owner' => $oid));
		    $this->db->delete('i_txns_details');
		    
		    $this->load->model('distributors/Accounting_Model','acc_model');
		    $this->acc_model->delete_journal_entries($oid, null, 'Purchase', $tid);
		    
		  //  $this->txn_model->update_inventory_txnwise_purchase($oid, $tid, true);
		    
		    redirect(base_url().'distributors/Transactions/purchase');
		} else {
		    redirect(base_url().'distributors/Account/login');
		}
    }
########## EXPENSES ################
	public function expenses() {
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
			
			$data['expense'] = $this->txn_model->load_expenses($oid, null, 'done', null);
		
			$data['oid'] = $oid;
		
			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Expenses";
			$ert['search'] = "true";
			$ert['search_placeholder'] = "Search Vendors";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/transactions/expense', $data);
		} else {
			redirect(base_url().'distributors/Account/login');
		}	
	}

	public function expense_add() {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			
			$data['oid'] = $oid;
			$data['orders'] = $this->txn_model->load_transactions($oid, 'Invoice', 'shipping', null);

			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Add Expense";
			$ert['search'] = "false";
			$ert['search_placeholder'] = "Search Vendors";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/transactions/expense_add', $data);
		} else {
			redirect(base_url().'distributors/Account/login');
		}	
	}

	public function save_expense() {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			$dt = date('Y-m-d H:m:s');

			echo $this->txn_model->save_expense($oid, $uid, $this->input->post('description'), $this->input->post('amt'), $this->input->post('date'), 'done', $this->input->post('order'), $dt);
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}
	
	public function expense_ticket_upload($eid) {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			$upload_dir = $this->config->item('document_rt')."assets/uploads/".$oid."/expenses/".$uid."/".$eid."/";
			if(!file_exists($upload_dir)) {
				mkdir($upload_dir, 0777, true);
			}
			$img_path = "";
			if (is_dir($upload_dir) && is_writable($upload_dir)) {
				$sourcePath = $_FILES['use']['tmp_name']; // Storing source path of the file in a variable
				$targetPath = $upload_dir.$_FILES['use']['name']; // Target path where file is to be stored
				// $img_path = $targetPath;
				move_uploaded_file($sourcePath,$targetPath) ; // Moving Uploaded file		
				$img_path = $_FILES['use']['name'];
			}

			$data = array('ie_tickets' => $img_path);
			$this->db->where(array('ie_owner'=>$oid, 'ie_id'=>$eid));
			$this->db->update('i_expenses', $data);

			echo $eid;
		}
	}

	public function expense_edit($eid) {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			
			$data['edit_txn'] = $this->txn_model->load_expenses($oid, null, 'done', $eid);
			$data['oid'] = $oid;
			$data['uid'] = $uid;
			$data['orders'] = $this->txn_model->load_transactions($oid, 'Invoice', 'shipping', null);
			$data['eid'] = $eid;

			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Add Expense";
			$ert['search'] = "false";
			$ert['search_placeholder'] = "Search Vendors";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/transactions/expense_add', $data);
		} else {
			redirect(base_url().'distributors/Account/login');
		}	
	}

	public function update_expense($eid) {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			$dt = date('Y-m-d H:m:s');

			echo $this->txn_model->update_expense($oid, $uid, $this->input->post('description'), $this->input->post('amt'), $this->input->post('date'), 'done', $this->input->post('order'), $dt, $eid);
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}

########## INVOICE ################
	public function invoice() {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			$this->session->unset_userdata('act_challans');
			
			$q=$this->db->query("SELECT * FROM i_users WHERE iu_owner='$oid' AND iu_id='$uid'");
			if($q->result()[0]->iu_status=="blocked") {
			    $this->session->unset_userdata('user_details');
        		$this->session->unset_userdata('status');
        		$this->session->unset_userdata('fy');
        		redirect(base_url().'distributors/Account/login');
			}
			
		  //  $query = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id WHERE it_type='Delivery' AND it_date BETWEEN '$fy' AND '$ty' AND it_status IN ('shipping','delivered')");
		    $query = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id WHERE it_type='Delivery' AND it_status IN ('shipping','delivered') AND it_owner='$oid' ORDER BY a.it_id, a.it_date");
		    $data["txn"] = $query->result();
			$data['oid'] = $oid;

            $ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Invoice";
			$ert['search'] = "false";
			$ert['search_placeholder'] = "Search Vendors";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/transactions/invoice', $data);
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}

	public function search_invoice_records() {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			
			
			$s = $this->input->post('s');
			$c = $this->input->post('c');
			
			if($s == "payment_pending") {
			    $query = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id WHERE a.it_owner='$oid' AND a.it_type='Delivery' AND it_status IN ('shipping','delivered') AND b.ic_name LIKE '%$c%' ORDER BY a.it_id DESC, a.it_date DESC");
			} else if($s == "hold") {
			    $query = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id WHERE a.it_owner='$oid' AND it_type='Hold' AND it_status IN ('hold') AND b.ic_name LIKE '%$c%' ORDER BY a.it_id DESC, a.it_date DESC");
			} else {
			    $query = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id WHERE a.it_owner='$oid' AND a.it_type='Invoice' AND a.it_status IN ('$s') AND b.ic_name LIKE '%$c%' ORDER BY a.it_id DESC, a.it_date DESC");
			}
			
			print_r(json_encode($query->result()));
		} else {
			echo "Please login";
		}
	}
	


	public function invoice_add() {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		    $yr=$sess_data['fy'][0]->ify_year_code;
		    $yrst = $sess_data['fy'][0]->ify_start_date;
		    $yren = $sess_data['fy'][0]->ify_end_date;
		    
		    
			$data["vendors"] = $this->contact_model->load_contacts($oid, 'Dealer');
			$data["products"] = $this->product_model->load_products($oid);
			$query = $this->db->query("SELECT * FROM i_taxes WHERE itx_owner = '$oid'");
			$data['taxes'] = $query->result();

			$query = $this->db->query("SELECT * FROM i_tax_group WHERE ittxg_owner = '$oid'");
			$data['tax_groups'] = $query->result();
			
			$query = $this->db->query("SELECT * FROM i_txn_nums WHERE itn_owner='$oid' AND itn_type='Invoice'");
		    $result = $query->result();
		    if(count($result) > 0) {
		        $x = $result[0]->itn_num + 1;    
		    } else {
		        $x=1;
		    }
		    
		  //  $data['tid'] = null;
		    
		    $data['invoice_number'] = $yr."/INV/".$x;
			
			$data['oid'] = $oid;
            
            $data['logo'] = base_url().'assets/uploads/'.$oid.'/logo/'.$sess_data['user_details'][0]->iu_logo;
			
            $query = $this->db->query("SELECT * FROM i_ac_ledgers WHERE iacl_owner='$oid'");
            $data['ledgers'] = $query->result();

            $query = $this->db->query("SELECT * FROM i_modules WHERE im_name='Invoice'");
            $data['default_ledger'] = $query->result();
            
			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Add Invoice";
			$ert['search'] = "false";
			$ert['search_placeholder'] = "Search Vendors";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/transactions/invoice_add', $data);
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}

// 	public function save_invoice() {
// 		$sess_data = $this->session->userdata();
// 		if(isset($sess_data['user_details'][0])) {
// 			$oid = $sess_data['user_details'][0]->iu_owner;
// 			$uid = $sess_data['user_details'][0]->iu_id;
// 			$dt = date('Y-m-d H:i:s');
            
//             $this->db->where(array('im_name'=>'Invoice'));
//             $this->db->update('i_modules', array('ipurcm_secondary_ledger' => $this->input->post('default_ledger'), 'im_secondary_ledger_to' => $this->input->post('default_ledger_to')));
            
//             $this->load->model('distributors/Accounting_Model','acc_model');
// 			$cid = $this->contact_model->save_contact_by_name($oid, $uid, $dt, $this->input->post('vendor'), 'Vendor', null, null, null, null, null);
//             $tid = $this->acc_model->save_invoice($oid, $cid, $uid, $dt, $this->db->post('date'), $this->input->post('txn'), $this->input->post('note'), null, "pending", $this->input->post('name'), $this->input->post('qty'), $this->input->post('rate'), null, null, null);
//             $this->session->unset_userdata('act_challans');
// 		} else {
// 			redirect(base_url().'distributors/Account/login');
// 		}
// 	}
	
	public function merge_create_invoice() {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			$dt = date('Y-m-d H:i:s');
			
			$x = $this->input->post('i');
			$d=[];
			
			for($i=0;$i<count($x);$i++) {
			    array_push($d, $x[$i]);
			    $this->db->insert('i_txn_invoice_merge', array('itim_primary' => $x[0], 'itim_secondary' => $x[$i], 'itim_owner' => $oid, 'itim_status' => 'active', 'itim_created' => $dt, 'itim_created_by' => $uid));
			}
			$da = array("act_challans" =>$d);
			$this->session->set_userdata($da);
			echo $x[0];
		} else {
		    echo "Please login";
		}
	}

	public function invoice_edit($pid) {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			
			$data["vendors"] = $this->contact_model->load_contacts($oid, 'Dealer');
			$data["products"] = $this->product_model->load_products($oid);
			$tx = $this->txn_model->load_transactions($oid, 'Invoice', null, $pid);
			
			if(count($tx) > 0) {
			    $data["txn"] = $tx;
			    $data['invoice_number'] = $tx[0]->it_txn_no;
			} else {
			    $data["txn"] = $this->txn_model->load_transactions($oid, 'Delivery', null, $pid);
			    $query = $this->db->query("SELECT * FROM i_txn_nums WHERE itn_owner='$oid' AND itn_type='Invoice'");
			    $result = $query->result();
			    if(count($result) > 0) {
			        $x = $result[0]->itn_num + 1;    
			    } else {
			        $x=1;
			    }
			    
			    $query = $this->db->query("SELECT * FROM i_txn_invoice_merge WHERE itim_owner='$oid' AND itim_primary='$pid'");
			    $result = $query->result();
			    $d=[];
			    for($j=0;$j<count($result);$j++) {
			        array_push($d, $result[$j]->itim_secondary);
			    }
			    $da = array("act_challans" =>$d);
    			$this->session->set_userdata($da);
    			
    			$sess_data = $this->session->userdata();
			    
			    $yr=$sess_data['fy'][0]->ify_year_code;
			    $data['invoice_number'] = $yr."/INV/".$x;
			} 
			
			if(count($data['txn']) > 0) {
			    $ordrid = $data['txn'][0]->it_order_txn;
			} else {
			    $data["txn"] = $this->txn_model->load_transactions($oid, 'Hold', null, $pid);
			    if(count($data['txn']) > 0) {
			        $ordrid = $data['txn'][0]->it_order_txn;    
			    }
			    
			    $query = $this->db->query("SELECT * FROM i_txns WHERE it_owner='$oid' AND it_type='Invoice'");
			    $result = $query->result();
			    if(count($result) > 0) {
			        $x = $result[0]->itn_num + 1;    
			    } else {
			        $x=1;
			    }
			}
			
		    $query = $this->db->query("SELECT * FROM i_txns WHERE it_order_txn='$ordrid' AND it_owner='$oid'");
		    $result = $query->result();
		    
		    
		    for($i=0;$i<count($result);$i++) {
		        if($result[$i]->it_type == "Orders") {
		            $data['txn_order'] = $result[$i]->it_txn_no;
		        } else if($result[$i]->it_type == "Delivery") {
		            $data['txn_delivery'] = $result[$i]->it_txn_no;
		        }
		    }
			
			$query = $this->db->query("SELECT * FROM i_txn_invoice_merge WHERE itim_primary='$pid' AND itim_owner='$oid' AND itim_status='active'");
			$result = $query->result();
			
			$data['txn_detail_merge'] = [];
			$data['txn_details'] = [];
			if(count($result) > 0) {
			    for($i=0;$i<count($result);$i++) {
    			    array_push($data['txn_detail_merge'], $this->txn_model->load_transaction_details($oid, $result[$i]->itim_secondary));
    			}
			} else {
    			$data["txn_details"] = $this->txn_model->load_transaction_details($oid, $pid);
			}
			
			$query = $this->db->query("SELECT b.it_txn_no, b.it_date FROM i_txn_invoice_challans AS a LEFT JOIN i_txns AS b ON a.itic_chln_id=b.it_id WHERE itic_inv_id='$pid' AND itic_owner='$oid'");
			$result = $query->result(); $xx=[];
			for($i=0;$i<count($result);$i++) {
			    array_push($xx, $result[$i]->it_txn_no.' - '.$result[$i]->it_date);
			}
			$data['txn_challans']=$xx;
			
			$query = $this->db->query("SELECT * FROM i_cash_master WHERE icm_owner='$oid'");
            $data['cash'] = $query->result();
            
			$data['txn_transport'] = $this->txn_model->load_transaction_transport_details($oid, $pid);
			$data['txn_prod_tax'] = $this->txn_model->load_transaction_prod_tax_details($oid, $pid);
			
			$data['gst_num'] = $sess_data['user_details'][0]->ic_gst_number;
			$data['bank'] = $sess_data['user_details'][0]->ic_bank_name;
			$data['branch'] = $sess_data['user_details'][0]->ic_bank_branch;
			$data['ifsc'] = $sess_data['user_details'][0]->ic_bank_ifsc;
			$data['acc'] = $sess_data['user_details'][0]->ic_bank_accno;

			$query = $this->db->query("SELECT * FROM i_taxes WHERE itx_owner = '$oid'");
			$data['taxes'] = $query->result();

			$query = $this->db->query("SELECT * FROM i_tax_group WHERE ittxg_owner = '$oid'");
			$data['tax_groups'] = $query->result();

            $query = $this->db->query("SELECT * FROM i_tax_group WHERE ittxg_owner = '$oid'");
			$data['tax_groups'] = $query->result();

            
			$data['logo'] = base_url().'assets/uploads/'.$oid.'/logo/'.$sess_data['user_details'][0]->iu_logo;
			$data['title_doc'] = "Invoice No: ".$pid;
			
			$data['oid'] = $oid;
			$data['tid'] = $pid;

            $query = $this->db->query("SELECT * FROM i_ac_ledgers WHERE iacl_owner='$oid'");
            $data['ledgers'] = $query->result();
            
            $query = $this->db->query("SELECT * FROM i_modules WHERE im_name='Invoice'");
            $data['default_ledger'] = $query->result();
            
            $query = $this->db->query("SELECT * FROM i_cash_master WHERE icm_owner='$oid'");
            $data['cash'] = $query->result();
            
			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Edit Invoice";
			$ert['search'] = "false";
			$ert['search_placeholder'] = "Search Vendors";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/transactions/invoice_add', $data);
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}

	public function update_invoice($tid=null) {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			$dt = date('Y-m-d H:m:s');

            $this->db->where(array('im_name'=>'Invoice'));
            $this->db->update('i_modules', array('im_secondary_ledger' => $this->input->post('default_ledger'), 'im_secondary_ledger_to' => $this->input->post('default_ledger_to')));
            
            $this->load->model('distributors/Accounting_Model','acc_model');
            
			$cid = $this->contact_model->save_contact_by_name($oid, $uid, $dt, $this->input->post('vendor'), 'Dealer', null, null, null, null, null);
			$tid = $this->acc_model->save_invoice($oid, $cid, $uid, $dt, $this->input->post('date'), $this->input->post('txn'), $this->input->post('note'), null, "invoiced", $this->input->post('name'), $this->input->post('qty'), $this->input->post('rate'), $this->input->post('tax_group'), $this->input->post('alias'), $tid, $this->input->post('discount'), $this->input->post('freight'), $this->input->post('credit'), $this->input->post('transport'), $this->input->post('lrno'), $this->input->post('transport_date'), $this->input->post('transport_gst'), $this->input->post('transport_state'), $this->input->post('transport_expense'));
            
            $this->db->where(array('itim_primary' => $tid, 'itim_owner' => $oid, 'itim_status' => 'active'));
			$this->db->update('i_txn_invoice_merge', array('itim_status' => 'merged'));
			
			if(isset($sess_data['act_challans'])) {
			    $vb = $sess_data['act_challans'];
    			for($i=0;$i<count($vb);$i++) {
    			    $this->db->insert('i_txn_invoice_challans', array('itic_inv_id' => $tid, 'itic_chln_id' => $vb[$i], 'itic_owner' => $oid));
    			}
    			$this->session->unset_userdata('act_challans');
			}
			
			$hold = $this->input->post('hold');
			$hq=0;
			for($i=0;$i<count($hold);$i++) {
			    $hq+=$hold[$i];
			}
			
			if($hq > 0) {
			    $query = $this->db->query("SELECT * FROM i_txns WHERE it_type='Hold' AND it_owner='$oid'");
			    $result=$query->result();
			    
			    $txid=count($result) + 1;
			    
			    $data = array(
			        'it_type' => 'Hold',
			        'it_c_id' => $cid,
			        'it_date' => $this->input->post('date'),
			        'it_txn_no' => $txid,
			        'it_amount' => 0,
			        'it_status' => 'hold',
			        'it_owner' => $oid,
			        'it_created' => $dt,
			        'it_created_by' => $uid,
			        );
			        
			    $query = $this->db->query("SELECT * FROM i_txns WHERE it_type='Hold' AND it_owner='$oid' AND it_c_id='$cid'");
			    $result=$query->result();
			    
			    if(count($result) > 0) {
			        $htid = $result[0]->it_id;
			        $this->db->where(array('it_id' => $htid, 'it_owner' => $oid));
			        $this->db->update('i_txns', $data);
			    } else {
			        $this->db->insert('i_txns', $data);
			        $htid = $this->db->insert_id();
			    }
			    
			    $prod = $this->input->post('name');
			    for($j=0;$j<count($prod); $j++) {
			        $pid = $this->product_model->save_product_by_name($oid, $uid, $dt, $prod[$j]);
			        if($hold[$j] != 0) {
			            $data = array(
            				'itd_t_id' => $htid,
            				'itd_p_id' => $pid,
            				'itp_qty' => $hold[$j],
            				'itp_rate' => $this->input->post('rate')[$j],
            				'itp_value' => $this->input->post('amt')[$j],
            				'itp_tax_group_id' => $this->input->post('tax_group')[$j],
            				'itp_tax' => 0,
            				'itp_amount' => $this->input->post('amt')[$j],
            				'itp_alias' => $this->input->post('alias')[$j],
            				'itp_owner' => $oid,
            				'itp_created' => $dt,
            				'itp_created_by' => $uid );
            			$this->db->insert('i_txns_details', $data);
			        }
        			    
            			
			    }
			} else {
			    $this->db->where(array('it_type' => 'Hold', 'it_c_id' => $cid, 'it_owner' => $oid));
			    $this->db->delete('i_txns');
			}
			echo $tid;
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}
	
	public function delete_invoice() {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			
			$tid = $this->input->post('txnid');
			
			$this->db->where(array('it_id' => $tid,'it_owner' => $oid));
			$this->db->delete('i_txns');
			
			$query = $this->db->query("SELECT * FROM i_txn_nums WHERE itn_type='Invoice' AND itn_owner='$oid'");
			$result = $query->result();
			if(count($result) > 0) {
		        $x = $result[0]->itn_num - 1;    
		    } else {
		        $x=0;
		    }
		    
			$this->db->where(array('itn_type' => 'Invoice','itn_owner' => $oid));
			$this->db->update('i_txn_nums', array('itn_num'=> $x));
					
		}
	}

	

########## PAYMENTS ################
// 	public function payments_sec() {
// 		$sess_data = $this->session->userdata();
// 		if(isset($sess_data['user_details'][0])) {
// 			$oid = $sess_data['user_details'][0]->iu_owner;
		
		    
// 		    $query = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id WHERE it_owner = '$oid' AND it_status IN ('invoiced', 'payment_pending', 'pending', 'paid')");
// 		    $data["txn"] = $query->result();
			
// 		    $query = $this->db->query("SELECT * FROM i_txn_payments AS a LEFT JOIN i_contacts AS b ON a.itp_c_id=b.ic_id LEFT JOIN i_txns AS c ON a.itp_t_id=c.it_id WHERE a.itp_owner = '$oid'");
// 		    $data["payments"] = $query->result();
			
// 			$data['oid'] = $oid;

// 			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
// 			$ert['title'] = "Payments";
// 			$ert['search'] = "true";
// 			$ert['search_placeholder'] = "Search Vendors";
// 			$ert['type'] = "distributors";
			
// 			$this->load->view('distributors/navbar', $ert);
// 			$this->load->view('distributors/transactions/payments', $data);
// 		} else {
// 			redirect(base_url().'distributors/Account/login');
// 		}
// 	}

    public function payments() {
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
			
			$query = $this->db->query("SELECT * FROM i_contacts WHERE ic_owner='$oid'");
			$data["contacts"] = $query->result();
			
			$query = $this->db->query("SELECT itp_mode FROM i_txn_payments WHERE itp_owner = '$oid' GROUP BY itp_mode");
			$data["modes"] = $query->result();
			
			$data['oid'] = $oid;
            $data['uid'] = $uid;

			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Payments";
			$ert['search'] = "false";
			$ert['search_placeholder'] = "Search Vendors";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/transactions/payment_add', $data);
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}
	
	public function get_payment_contact_txns() {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		    $type = $this->input->post('t');
		    $contact = $this->input->post('c');
		    $from = $this->input->post('fr');
		    $to = $this->input->post('to');
		    $data=[];
		    if($type == "delivery") {
		        $query = $this->db->query("SELECT id, date, descr, credit, debit, cid, info, pay_amt, type, txnno FROM ((SELECT a.it_id AS id, a.it_date AS date, CONCAT(a.it_type, ' Txn No: ', a.it_txn_no) AS descr, 0 AS credit, (a.it_amount + IFNULL(bn.ie_amount,0)) AS debit, a.it_c_id AS cid, a.it_type AS info, 'Delivery' AS type, a.it_txn_no AS txnno, IFNULL(SUM(cn.itpl_amt),0) AS pay_amt FROM i_txns AS a LEFT JOIN i_expenses AS bn ON a.it_id=bn.ie_order_id LEFT JOIN i_txn_payments_links AS cn ON a.it_id=cn.itpl_txn_id WHERE a.it_type IN ('Delivery') AND a.it_owner='$oid' AND a.it_date BETWEEN '$from' AND '$to' GROUP BY a.it_id, cn.itpl_txn_id) UNION (SELECT b.itp_id AS id, b.itp_date AS date, CONCAT(b.itp_mode, ' - ', b.itp_details) AS descr, b.itp_credit AS credit, b.itp_debit AS debit, b.itp_c_id AS cid, 'payment' AS info, 'Payment Voucher' AS type, b.itp_id AS txnno, 0 AS pay_amt FROM i_txn_payments AS b WHERE itp_owner='$oid' AND itp_type='delivery' AND itp_date BETWEEN '$from' AND '$to' ) UNION (SELECT c.it_id AS id, c.it_date AS date, CONCAT(c.it_type, ' - Txn No: ', c.it_txn_no) AS descr, (c.it_amount) AS credit, 0 AS debit, c.it_c_id AS cid, 'payment' AS info, 'Credit Note' AS type, c.it_txn_no AS txnno, 0 AS pay_amt FROM i_txns AS c WHERE c.it_owner='$oid' AND c.it_type='Credit Note' AND c.it_date BETWEEN '$from' AND '$to' AND c.it_type_sec='Delivery' )) AS results LEFT JOIN i_contacts AS y ON results.cid=y.ic_id WHERE y.ic_name LIKE '%$contact%' ORDER BY date ASC");    
		        $data['records'] = $query->result();
		        
		      //  $query = $this->db->query("SELECT (SELECT SUM(a.it_amount + IFNULL(bn.ie_amount,0)) AS balance FROM i_txns AS a LEFT JOIN i_expenses AS bn ON a.it_id=bn.ie_order_id LEFT JOIN i_contacts AS vb ON a.it_c_id=vb.ic_id WHERE a.it_type IN ('Delivery') AND a.it_owner='$oid' AND vb.ic_name LIKE '%$contact%') AS results");
		        $query = $this->db->query("SELECT ((SELECT IFNULL(SUM(a.it_amount + IFNULL(bn.ie_amount,0)),0) AS balance FROM i_txns AS a LEFT JOIN i_expenses AS bn ON a.it_id=bn.ie_order_id LEFT JOIN i_contacts AS vb ON a.it_c_id=vb.ic_id WHERE a.it_type IN ('Delivery') AND a.it_owner='$oid' AND vb.ic_name LIKE '%$contact%') - (SELECT IFNULL(SUM(b.itp_credit - b.itp_debit),0) AS balance FROM i_txn_payments AS b LEFT JOIN i_contacts AS vb ON b.itp_c_id=vb.ic_id WHERE b.itp_owner='$oid' AND b.itp_type='delivery' AND vb.ic_name LIKE '%$contact%') - (SELECT SUM(c.it_amount) AS balance FROM i_txns AS c LEFT JOIN i_contacts AS vb ON c.it_c_id=vb.ic_id WHERE c.it_owner='$oid' AND c.it_type='Credit Note' AND c.it_type_sec='Delivery' AND vb.ic_name LIKE '%$contact%')) AS results");
		        $data['main'] = $query->result();
		    } else if($type == "delivery_purchase") {
		        $query = $this->db->query("SELECT id, date, descr, credit, debit, cid, info, pay_amt FROM ((SELECT a.it_id AS id, a.it_date AS date, CONCAT(a.it_type, ' Txn No: ', a.it_txn_no) AS descr, 0 AS credit, (a.it_amount + IFNULL(bn.ie_amount,0)) AS debit, a.it_c_id AS cid, a.it_type AS info, IFNULL(SUM(cn.itpl_amt),0) AS pay_amt FROM i_txns AS a LEFT JOIN i_expenses AS bn ON a.it_id=bn.ie_order_id LEFT JOIN i_txn_payments_links AS cn ON a.it_id=cn.itpl_txn_id WHERE (a.it_type IN ('Purchase') AND a.it_type_sec IN ('Delivery')) AND a.it_owner='$oid' AND a.it_date BETWEEN '$from' AND '$to' GROUP BY a.it_id, cn.itpl_txn_id) UNION ALL (SELECT b.itp_id AS id, b.itp_date AS date, CONCAT(b.itp_mode, ' - ', b.itp_details) AS descr, b.itp_credit AS credit, b.itp_debit AS debit, b.itp_c_id AS cid, 'payment' AS info, 0 AS pay_amt FROM i_txn_payments AS b WHERE itp_owner='$oid' AND itp_type='delivery' AND itp_date BETWEEN '$from' AND '$to' )) results LEFT JOIN i_contacts AS y ON results.cid=y.ic_id WHERE y.ic_name LIKE '%$contact%' ORDER BY date ASC");    
		        $data['records'] = $query->result();
		        $query = $this->db->query("SELECT ((SELECT IFNULL(SUM(a.it_amount + IFNULL(bn.ie_amount,0)),0) AS balance FROM i_txns AS a LEFT JOIN i_expenses AS bn ON a.it_id=bn.ie_order_id LEFT JOIN i_contacts AS vb ON a.it_c_id=vb.ic_id WHERE (a.it_type IN ('Purchase') AND a.it_type_sec IN ('Delivery')) AND a.it_owner='$oid' AND vb.ic_name LIKE '%$contact%') - (SELECT IFNULL(SUM(b.itp_credit - b.itp_debit),0) AS balance FROM i_txn_payments AS b LEFT JOIN i_contacts AS vb ON b.itp_c_id=vb.ic_id WHERE b.itp_owner='$oid' AND b.itp_type='delivery' AND vb.ic_name LIKE '%$contact%') - (SELECT SUM(c.it_amount) AS balance FROM i_txns AS c LEFT JOIN i_contacts AS vb ON c.it_c_id=vb.ic_id WHERE c.it_owner='$oid' AND c.it_type='Debit Note' AND c.it_type_sec='Delivery' AND vb.ic_name LIKE '%$contact%')) AS results");
		        $data['main'] = $query->result();
		    } else if($type == "invoice") {
		        $query = $this->db->query("SELECT id, date, descr, credit, debit, cid, info, pay_amt FROM ((SELECT a.it_id AS id, a.it_date AS date, CONCAT(a.it_type, ' Txn No: ', a.it_txn_no) AS descr, 0 AS credit, (a.it_amount + IFNULL(bn.ie_amount,0)) AS debit, a.it_c_id AS cid, a.it_type AS info, IFNULL(SUM(cn.itpl_amt),0) AS pay_amt FROM i_txns AS a LEFT JOIN i_expenses AS bn ON a.it_id=bn.ie_order_id LEFT JOIN i_txn_payments_links AS cn ON a.it_id=cn.itpl_txn_id WHERE (a.it_type IN ('Invoice') OR (a.it_type='Credit Note' AND a.it_type_sec='Invoice')) AND a.it_owner='$oid' AND a.it_date BETWEEN '$from' AND '$to' GROUP BY a.it_id, cn.itpl_txn_id) UNION ALL (SELECT b.itp_id AS id, b.itp_date AS date, CONCAT(b.itp_mode, ' - ', b.itp_details) AS descr, b.itp_credit AS credit, b.itp_debit AS debit, b.itp_c_id AS cid, 'payment' AS info, 0 AS pay_amt FROM i_txn_payments AS b WHERE itp_owner='$oid' AND itp_type = 'invoice' AND itp_date BETWEEN '$from' AND '$to')) results LEFT JOIN i_contacts AS y ON results.cid=y.ic_id WHERE y.ic_name LIKE '%$contact%' ORDER BY date ASC");
		        $data['records'] = $query->result();
		        $query = $this->db->query("SELECT ((SELECT IFNULL(SUM(a.it_amount + IFNULL(bn.ie_amount,0)),0) AS balance FROM i_txns AS a LEFT JOIN i_expenses AS bn ON a.it_id=bn.ie_order_id LEFT JOIN i_contacts AS vb ON a.it_c_id=vb.ic_id WHERE (a.it_type IN ('Invoice') OR (a.it_type='Credit Note' AND a.it_type_sec='Invoice')) AND a.it_owner='$oid' AND vb.ic_name LIKE '%$contact%') - (SELECT IFNULL(SUM(b.itp_credit - b.itp_debit),0) AS balance FROM i_txn_payments AS b LEFT JOIN i_contacts AS vb ON b.itp_c_id=vb.ic_id WHERE b.itp_owner='$oid' AND b.itp_type='invoice' AND vb.ic_name LIKE '%$contact%')) AS results");
		        $data['main'] = $query->result();
		    } else if($type == "purchase") {
		        $query = $this->db->query("SELECT id, date, descr, credit, debit, cid, info, pay_amt FROM ((SELECT a.it_id AS id, a.it_date AS date, CONCAT(a.it_type, ' Txn No: ', a.it_txn_no) AS descr, (a.it_amount + IFNULL(bn.ie_amount,0)) AS credit, 0 AS debit, a.it_c_id AS cid, a.it_type AS info, IFNULL(SUM(cn.itpl_amt),0) AS pay_amt FROM i_txns AS a LEFT JOIN i_expenses AS bn ON a.it_id=bn.ie_order_id LEFT JOIN i_txn_payments_links AS cn ON a.it_id=cn.itpl_txn_id WHERE (a.it_type IN ('Purchase') OR (a.it_type='Debit Note' AND a.it_type_sec='Invoice')) AND a.it_owner='$oid' AND a.it_date BETWEEN '$from' AND '$to' GROUP BY a.it_id, cn.itpl_txn_id) UNION ALL (SELECT b.itp_id AS id, b.itp_date AS date, CONCAT(b.itp_mode, ' - ', b.itp_details) AS descr, b.itp_credit AS credit, b.itp_debit AS debit, b.itp_c_id AS cid, 'payment' AS info, 0 AS pay_amt FROM i_txn_payments AS b WHERE itp_owner='$oid' AND itp_type = 'purchase' AND itp_date BETWEEN '$from' AND '$to')) results LEFT JOIN i_contacts AS y ON results.cid=y.ic_id WHERE y.ic_name LIKE '%$contact%' ORDER BY date ASC");
		        $data['records'] = $query->result();
		        $query = $this->db->query("SELECT ((SELECT IFNULL(SUM(a.it_amount + IFNULL(bn.ie_amount,0)),0) AS balance FROM i_txns AS a LEFT JOIN i_expenses AS bn ON a.it_id=bn.ie_order_id LEFT JOIN i_contacts AS vb ON a.it_c_id=vb.ic_id WHERE (a.it_type IN ('Purchase') OR (a.it_type='Debit Note' AND a.it_type_sec='Invoice')) AND a.it_owner='$oid' AND vb.ic_name LIKE '%$contact%') - (SELECT IFNULL(SUM(b.itp_credit - b.itp_debit),0) AS balance FROM i_txn_payments AS b LEFT JOIN i_contacts AS vb ON b.itp_c_id=vb.ic_id WHERE b.itp_owner='$oid' AND b.itp_type='purchase' AND vb.ic_name LIKE '%$contact%')) AS results");
		        $data['main'] = $query->result();
		    }
		    
		    print_r(json_encode($data));
		} else {
		    echo "login";
		}
	}
	
	
	
	public function get_payment_history() {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		    $pid = $this->input->post('i');
		    $query = $this->db->query("SELECT CONCAT(b.it_type, ' Txn No: ', b.it_txn_no) AS info, b.it_date AS date, (b.it_amount + (SELECT IFNULL(ie_amount,0) AS amt FROM i_expenses WHERE ie_owner='1' AND ie_order_id=b.it_id) )AS amount, a.itpl_amt AS paid_amount FROM i_txn_payments_links AS a LEFT JOIN i_txns AS b ON a.itpl_txn_id=b.it_id WHERE a.itpl_owner='$oid' AND a.itpl_pay_id='$pid'");
		    print_r(json_encode($query->result()));
		} else {
		    echo "login";
		}
	}
	
	public function get_payment_txns($pid) {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		    
		    $query = $this->db->query("SELECT * FROM i_txn_payments WHERE itp_owner='$oid' AND itp_id='$pid'");
		    print_r(json_encode($query->result()));
		} else {
		    echo "login";
		}
	}
	
	public function update_payment_txn($pid=null) {
	   $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		    $uid = $sess_data['user_details'][0]->iu_id;

            
            $query = $this->db->query("SELECT * FROM i_contacts WHERE ic_name='$contact' AND ic_owner='$oid'");
            $result = $query->result();
            
            $cid=0;
            if(count($result) > 0) {
                $cid=$result[0]->ic_id;
            }
            
            
            if($pid == null) {
                if($this->input->post('agreement') == "credit") {
                    $this->db->insert('i_txn_payments', array(
                        'itp_c_id' => $cid,
                        'itp_date' => $this->input->post('date'),
                        'itp_mode' => $this->input->post('mode'),
                        'itp_details' => $this->input->post('detail'),
                        'itp_credit' => $this->input->post('amt'),
                        'itp_debit' => 0,
                        'itp_owner' => $oid,
                        'itp_created' => date('Y-m-d H:i:s'),
                        'itp_created_by' => $uid,
                        'itp_type' => $this->input->post('type'),
                        'itp_agreement' => $this->input->post('agreement'),
                        )
                    );
                    $pid = $this->db->insert_id();
                } else {
                    $this->db->insert('i_txn_payments', array(
                        'itp_c_id' => $cid,
                        'itp_date' => $this->input->post('date'),
                        'itp_mode' => $this->input->post('mode'),
                        'itp_details' => $this->input->post('detail'),
                        'itp_credit' => 0,
                        'itp_debit' => $this->input->post('amt'),
                        'itp_owner' => $oid,
                        'itp_created' => date('Y-m-d H:i:s'),
                        'itp_created_by' => $uid,
                        'itp_type' => $this->input->post('type'),
                        'itp_agreement' => $this->input->post('agreement'),
                        )
                    );
                    $pid = $this->db->insert_id();
                }
                    
            } else {
                $this->db->where(array('itp_owner' => $oid, 'itp_id' => $pid));
                if($this->input->post('agreement') == "credit") {
                    $this->db->update('i_txn_payments', array(
                        'itp_c_id' => $cid,
                        'itp_date' => $this->input->post('date'),
                        'itp_mode' => $this->input->post('mode'),
                        'itp_details' => $this->input->post('detail'),
                        'itp_credit' => $this->input->post('amt'),
                        'itp_debit' => 0,
                        'itp_modified' => date('Y-m-d H:i:s'),
                        'itp_modified_by' => $uid,
                        'itp_type' => $this->input->post('type'),
                        'itp_agreement' => $this->input->post('agreement'),
                        )
                    );
                } else {
                    $this->db->update('i_txn_payments', array(
                        'itp_c_id' => $cid,
                        'itp_date' => $this->input->post('date'),
                        'itp_mode' => $this->input->post('mode'),
                        'itp_details' => $this->input->post('detail'),
                        'itp_credit' => 0,
                        'itp_debit' => $this->input->post('amt'),
                        'itp_modified' => date('Y-m-d H:i:s'),
                        'itp_modified_by' => $uid,
                        'itp_type' => $this->input->post('type'),
                        'itp_agreement' => $this->input->post('agreement'),
                        )
                    );
                }
            }
            
            $this->db->where(array('itpl_pay_id' => $pid, 'itpl_owner' => $oid));
            $this->db->delete('i_txn_payments_links');
            
            $links = $this->input->post('sel_txns');
            $links_calc = $this->input->post('sel_amt_cal');
            
            $amt = $this->input->post('amt'); $bal=0;
            for($i=0;$i<count($links);$i++){
                if($this->input->post('agreement') == "credit") {
                                
                }
                $bal1=$amt;
                $amt=$amt+$links_calc[$i]['c']-$links_calc[$i]['d'];
                echo $i." - ".$amt;
                if($amt > 0) {
                    if($links_calc[$i]['c'] == 0) {
                        $this->db->insert('i_txn_payments_links', array('itpl_pay_id' => $pid, 'itpl_txn_id' => $links[$i], 'itpl_owner' =>$oid, 'itpl_amt' => $links_calc[$i]['d'] ));
                    } else {
                        $this->db->insert('i_txn_payments_links', array('itpl_pay_id' => $pid, 'itpl_txn_id' => $links[$i], 'itpl_owner' =>$oid, 'itpl_amt' => $links_calc[$i]['c'] ));
                    }
                    echo "Greater/";
                    $bal=$amt;
                } else if($amt == 0) {
                    if($links_calc[$i]['c'] == 0) {
                        $this->db->insert('i_txn_payments_links', array('itpl_pay_id' => $pid, 'itpl_txn_id' => $links[$i], 'itpl_owner' =>$oid, 'itpl_amt' => $bal1 ));
                    } else {
                        $this->db->insert('i_txn_payments_links', array('itpl_pay_id' => $pid, 'itpl_txn_id' => $links[$i], 'itpl_owner' =>$oid, 'itpl_amt' => $bal1 ));
                    }
                    echo "Equal/";
                    $bal=$amt;
                }else {
                    echo "Less/";
                    $this->db->insert('i_txn_payments_links', array('itpl_pay_id' => $pid, 'itpl_txn_id' => $links[$i], 'itpl_owner' =>$oid, 'itpl_amt' => $bal ));
                }
            }
            
		    echo "true";
		} else {
		    echo "login";
		}
	}

    public function get_payment_transactions() {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		    $type = $this->input->post('type');
		    $keyword = $this->input->post('keywords');
		    
		    $query = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id WHERE a.it_owner = '$oid' AND a.it_type='$type' AND b.ic_name='$keyword'");
		    // print_r(json_encode($query->result()));
		} else {
			redirect(base_url().'distributors/Account/login');
		}
    }
    
    public function delete_payment_transaction() {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		    
		    $data = array('itp_id' => $this->input->post('ptid'), 'itp_owner' => $oid);
		    $this->db->where($data);
		    $this->db->delete('i_txn_payments');
		    
		    $data = array('itpl_pay_id' => $this->input->post('ptid'), 'itpl_owner' => $oid);
		    $this->db->where($data);
		    $this->db->delete('i_txn_payments_links');
		    
		    echo "true";
		} else {
			redirect(base_url().'distributors/Account/login');
		}
    }
    
    
########## BANK ACCOUNTS ################
	public function bank_accounts() {
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
			
		    $query = $this->db->query("SELECT itp_mode FROM i_txn_payments WHERE itp_owner = '$oid' GROUP BY itp_mode");
			$data["modes"] = $query->result();
			
			$data['oid'] = $oid;
            $data['uid'] = $uid;
            
			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Bank Accounts";
			$ert['search'] = "false";
			$ert['search_placeholder'] = "Search Vendors";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/transactions/bank_accounts', $data);
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}
	
	public function get_payment_bank_txns() {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		    $account = $this->input->post('a');
		    $from = $this->input->post('fr');
		    $to = $this->input->post('to');
		    
		  //  SQL QUERY WITH TXN DETAILS
		    $query = $this->db->query("SELECT a.itp_id AS id, a.itp_date AS date, IFNULL((SELECT GROUP_CONCAT(a.itp_details, ' ', c.it_type,' ',d.ic_name) FROM i_txn_payments_links AS b LEFT JOIN i_txns AS c ON b.itpl_txn_id=c.it_id LEFT JOIN i_contacts AS d ON c.it_c_id=d.ic_id WHERE b.itpl_pay_id=id), a.itp_details) AS descr,  a.itp_credit AS credit, a.itp_debit AS debit, a.itp_c_id AS cid, 'payment' AS info, bn.ic_name AS cname FROM `i_txn_payments` AS a LEFT JOIN i_contacts AS bn ON a.itp_c_id=bn.ic_id WHERE a.itp_owner='$oid' AND a.itp_mode='$account'");
		  //  SQL QUERY WITHOUT TXN DETAILS
		  //  $query = $this->db->query("SELECT a.itp_id AS id, a.itp_date AS date, a.itp_details AS descr, a.itp_credit AS debit, a.itp_debit AS credit, a.itp_c_id AS cid, 'payment' AS info FROM `i_txn_payments` AS a WHERE a.itp_owner='$oid' AND a.itp_mode='$account'");
		    print_r(json_encode($query->result()));
		} else {
		    echo "login";
		}
	}
	
    
########## GODOWN ################
	public function godowns() {
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
			
		
		    $query = $this->db->query("SELECT * FROM i_godowns WHERE ig_owner='$oid'");
		    $result = $query->result();
		    
		    $data['txn'] = [];
		    for($i=0; $i<count($result); $i++) {
		        $gid = $result[$i]->ig_id;
		        $que = $this->db->query("SELECT * FROM i_g_transactions WHERE igt_owner = '$oid' AND (igt_from='$gid' OR igt_to='$gid')");
		        $res = $que->result();
		        $bal = 0;
		        for($j=0; $j<count($res); $j++) {
		            if($res[$j]->igt_from == $gid) {
		                $bal+=($res[$j]->igt_qty * -1);
		            } else if($res[$j]->igt_to == $gid) {
		                $bal+=$res[$j]->igt_qty;
		            }
		        }
		        $data['txn'][$i] = array('g' => $result[$i]->ig_godown_name, 'bal' => $bal );
		    }
		    
		  //  print_r($data['txn']);
		    $data['godown'] = $query->result();
		    $data['oid'] = $oid;

			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Godowns";
			$ert['search'] = "true";
			$ert['search_placeholder'] = "Search Vendors";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/transactions/godowns', $data);
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}

    public function manage_godowns() {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		
		    $query = $this->db->query("SELECT * FROM i_godowns WHERE ig_owner='$oid'");
		    $data['txn'] = $query->result();
		    $data['oid'] = $oid;

			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Manage Godowns";
			$ert['search'] = "true";
			$ert['search_placeholder'] = "Search Vendors";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/transactions/godowns_manage', $data);
		} else {
			redirect(base_url().'distributors/Account/login');
		}
    }
    
    public function load_godowns($keyword=null) {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		
		    if($keyword != null) {
		        $query = $this->db->query("SELECT * FROM i_godowns WHERE ig_owner='$oid' AND ig_godown_name LIKE '%$keyword%'");    
		    } else {
		        $query = $this->db->query("SELECT * FROM i_godowns WHERE ig_owner='$oid'");
		    }
		    print_r(json_encode($query->result()));
		} else {
			redirect(base_url().'distributors/Account/login');
		}
    }
    
    public function save_godown() {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		    $uid = $sess_data['user_details'][0]->iu_id;
		    $dt = date('Y-m-d H:m:s');
		    
		    $data = array(
		        'ig_godown_name' => $this->input->post('godown'),
		        'ig_owner' => $oid,
		        'ig_created' => $dt,
		        'ig_created_by' => $uid);
		    $this->db->insert('i_godowns', $data);
		    echo $this->db->insert_id();
		} else {
			redirect(base_url().'distributors/Account/login');
		}
    }
    
    public function edit_godown($gid) {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		
		    $query = $this->db->query("SELECT * FROM i_godowns WHERE ig_owner='$oid' AND ig_id='$gid'");
		    $data['edit_godown'] = $query->result();
		    $data['oid'] = $oid;
		    $data['gid'] = $gid;

			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Edit Godown";
			$ert['search'] = "true";
			$ert['search_placeholder'] = "Search Vendors";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/transactions/godowns_manage', $data);
		} else {
			redirect(base_url().'distributors/Account/login');
		}
    }
    
    public function update_godown($gid) {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		    $uid = $sess_data['user_details'][0]->iu_id;
		    $dt = date('Y-m-d H:m:s');
		    
		    $data = array(
		        'ig_godown_name' => $this->input->post('godown'),
		        'ig_owner' => $oid,
		        'ig_created' => $dt,
		        'ig_created_by' => $uid);
		    $this->db->where(array('ig_id' => $gid, 'ig_owner' => $oid));
		    $this->db->update('i_godowns', $data);
		    echo $gid;
		} else {
			redirect(base_url().'distributors/Account/login');
		}
    }
    
    public function delete_godown($gid) {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		    
		    $this->db->where(array('ig_id' => $gid, 'ig_owner' => $oid));
		    $this->db->delete('i_godowns');
		    
		    redirect(base_url().'distributors/Transactions/manage_godowns');
		} else {
			redirect(base_url().'distributors/Account/login');
		}
    }
    
    public function manage_materials() {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		
		    $query = $this->db->query("SELECT * FROM i_godowns WHERE ig_owner='$oid'");
		    $data['godowns'] = $query->result();
		    
		    $query = $this->db->query("SELECT * FROM i_contacts WHERE ic_owner='$oid'");
		    $data['contacts'] = $query->result();
		    
		    $data["products"] = $this->product_model->load_products($oid);
			
		    $data['oid'] = $oid;

			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Manage Materials";
			$ert['search'] = "true";
			$ert['search_placeholder'] = "Search Vendors";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/transactions/godowns_materials', $data);
		} else {
			redirect(base_url().'distributors/Account/login');
		}
    }
    
    public function save_godown_transaction() {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			$dt = date('Y-m-d H:m:s');
			
			$from = $this->input->post('from');
			$to = $this->input->post('to');
			
			if($this->input->post('from_type') == "contact") {
			    $from_id = $this->contact_model->save_contact_by_name($oid, $uid, $dt, $from, 'Allow', null, null, null, null, null);
			} else {
			    $query = $this->db->query("SELECT * FROM i_godowns WHERE ig_godown_name='$from' AND ig_owner='$oid'");
			    $result = $query->result();
			    
			    if(count($result) > 0) {
			        $from_id = $result[0]->ig_id;
			    } else {
			        $data = array(
			            'ig_godown_name' => $from,
			            'ig_owner' => $oid,
			            );
			        $this->db->insert('i_godowns', $data);
			        $from_id = $this->db->insert_id();
			    }
			}
			
			if($this->input->post('to_type') == "contact") {
			    $to_id = $this->contact_model->save_contact_by_name($oid, $uid, $dt, $to, 'Allow', null, null, null, null, null);
			} else {
			    $query = $this->db->query("SELECT * FROM i_godowns WHERE ig_godown_name='$to' AND ig_owner='$oid'");
			    $result = $query->result();
			    
			    if(count($result) > 0) {
			        $to_id = $result[0]->ig_id;
			    } else {
			        $data = array(
			            'ig_godown_name' => $to,
			            'ig_owner' => $oid,
			            );
			        $this->db->insert('i_godowns', $data);
			        $to_id = $this->db->insert_id();
			    }
			}
			
			
			
			
			$prod = $this->input->post('name');
			$qty = $this->input->post('qty');
			$box = $this->input->post('box');
			
			for($i=0; $i<count($prod); $i++) {
			    $pid = $this->product_model->save_product_by_name($oid, $uid, $dt, $prod[$i]);
			    
			    $data = array(
    		        'igt_f_type' => $this->input->post('from_type'),
    		        'igt_from' => $from_id,
    		        'igt_t_type' => $this->input->post('to_type'),
    		        'igt_to' => $to_id,
    		        'igt_p_id' => $pid,
    		        'igt_qty' => $qty[$i],
    		        'igt_date' => $this->input->post('date'),
    		        'igt_owner' => $oid,
    		        'igt_created' => $dt,
    		        'igt_created_by' => $uid,
    		        'igt_box' => $box[$i]
    		        );
                $this->db->insert('i_g_transactions', $data);
			}
		} else {
			redirect(base_url().'distributors/Account/login');
		}
    }
    
########## CREDIT & DEBIT NOTE ################
	public function cd_note() {
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
			
		    $query = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id WHERE it_owner='$oid' AND (it_type='Credit Note' OR it_type='Debit Note') ORDER BY it_date DESC, it_id DESC");
		    $data["txn"] = $query->result();
			
		    $data['oid'] = $oid;

			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Credit & Debit Notes";
			$ert['search'] = "true";
			$ert['search_placeholder'] = "Search Vendors";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/transactions/credit_debit_notes', $data);
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}
	
	public function filter_cd_note() {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			print_r(json_encode($this->txn_model->load_transactions($oid, 'Invoice', $this->input->post('status'))));
		} else {
			echo "Please login";
		}
	}
	
	public function search_cd_note() {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$k = $this->input->post('keyword');
			$query = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id WHERE a.it_owner='$oid' AND b.ic_name LIKE '%$k%' AND a.it_type IN ('Credit Note','Debit Note')");
			print_r(json_encode($query->result()));
		} else {
			echo "Please login";
		}
	}

	public function cd_note_add() {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		    
			$query = $this->db->query("SELECT * FROM i_contacts WHERE ic_owner='$oid'");
			$data["vendors"] = $query->result();
			$data["products"] = $this->product_model->load_products($oid);
			$data["taxes"] = $this->product_model->load_tax_groups($oid);
			$query = $this->db->query("SELECT * FROM i_taxes WHERE itx_owner = '$oid'");
			$data['taxes'] = $query->result();
			$query = $this->db->query("SELECT * FROM i_tax_group WHERE ittxg_owner = '$oid'");
			$data['tax_groups'] = $query->result();
            $query = $this->db->query("SELECT * FROM i_tax_group WHERE ittxg_owner = '$oid'");
			$data['tax_groups'] = $query->result();
			$data['logo'] = base_url().'assets/uploads/'.$oid.'/logo/'.$sess_data['user_details'][0]->iu_logo;
			$data['logo_sec'] = base_url().'assets/uploads/'.$oid.'/logo/'.$sess_data['user_details'][0]->iu_logo_sec;
			$data['title_doc'] = "Order Details: ";#.$pid;
			
			$query = $this->db->query("SELECT * FROM i_txns WHERE it_type='Credit Note' AND it_owner='$oid'");
			$data['txn_num'] = (count($query->result()) + 1);
			$data['yrcode'] = $sess_data['fy'][0]->ify_year_code;
			$data['oid'] = $oid;


			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Add Credit/Debit Note";
			$ert['search'] = "false";
			$ert['search_placeholder'] = "Search Vendors";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/transactions/credit_debit_note_add', $data);
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}
	
	public function cd_note_get_txns($main_tid=null) {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		    $type = $this->input->post('type');
		    $contact = $this->input->post('contact');
		    $fr = $this->input->post('for');
		    
		    $query = $this->db->query("SELECT * FROM i_contacts WHERE ic_name = '$contact' AND ic_owner='$oid'");
	        $result = $query->result();
	        $cid = $result[0]->ic_id;
	        
		    if($type=="cn") {
		        if($fr == "Delivery") {
		            $query=$this->db->query("SELECT * FROM i_txns WHERE it_c_id='$cid' AND it_owner='$oid' AND it_type='Delivery'");
		        } else {
		            $query=$this->db->query("SELECT * FROM i_txns WHERE it_c_id='$cid' AND it_owner='$oid' AND it_type='Invoice'");    
		        }
		    } else if($type=="dn") {
		        $query=$this->db->query("SELECT * FROM i_txns WHERE it_c_id='$cid' AND it_owner='$oid' AND it_type='Purchase'");
		    }
		    $data['main'] =$query->result();
		    if($main_tid!=null) {
		        $query = $this->db->query("SELECT * FROM i_txn_payments_links WHERE itpl_pay_id='$main_tid' AND itpl_owner='$oid' AND itpl_type='credit_note'");
		        $data['select'] = $query->result();
		    } else {
		        $data['select'] =[];
		    }
	        print_r(json_encode($data));
		} else {
		    echo "Please login";
		}
	}
	
	public function get_txn_items() {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		    $t=$this->input->post('t');
		    
		    $query = $this->db->query("SELECT * FROM i_txns_details AS a LEFT JOIN i_products AS b ON a.itd_p_id=b.ip_id WHERE itp_owner='$oid' AND itd_t_id='$t'");
		    print_r(json_encode($query->result()));
		}
	}
	
	public function cd_note_get_txn_num() {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		    $type = $this->input->post('t');
		    $for = $this->input->post('f');
		    
		    if($type=="CN") {
		        if($for == 'Delivery') {
		            $query=$this->db->query("SELECT * FROM i_txn_nums WHERE itn_owner='$oid' AND itn_type='Credit Delivery'");
		        } else if ($for == 'Invoice') {
		            $query=$this->db->query("SELECT * FROM i_txn_nums WHERE itn_owner='$oid' AND itn_type='Credit Invoice'");
		        }
		    } else if($type=="DN") {
		        if($for == 'Delivery') {
		            $query=$this->db->query("SELECT * FROM i_txn_nums WHERE itn_owner='$oid' AND itn_type='Debit Delivery'");
		        } else if ($for == 'Invoice') {
		            $query=$this->db->query("SELECT * FROM i_txn_nums WHERE itn_owner='$oid' AND itn_type='Debit Invoice'");
		        }
		    }
		    
		    $x="";
		    if($for == "Invoice" || $for =="Purchase") {
		        $x.=$sess_data['fy'][0]->ify_year_code.'/';
		    }
		    if(count($query->result()) > 0) {
		        $x.=$type.'/'.($query->result()[0]->itn_num + 1);    
		    } else {
		        $x.=$type.'/1';
		    }
	        
            echo $x;
		} else {
		    echo "Please login";
		}
	}
	
	

	public function save_cd_note() {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			$dt = date('Y-m-d H:m:s');

			$cid = $this->contact_model->save_contact_by_name($oid, $uid, $dt, $this->input->post('vendor'), 'Allow', null, null, null, null, null);

			$tid = $this->txn_model->save_cd_notes($oid, $uid, $cid, $this->input->post('date'), $this->input->post('txn'), $this->input->post('note'), null, 'done', $this->input->post('name'), $this->input->post('qty'), $this->input->post('rate'), $this->input->post('amt'), $this->input->post('tax_group'), $this->input->post('alias'), $dt, $this->input->post('type'), $this->input->post('txn_link'), $this->input->post('type_sec'));
			print_r($tid);
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}

	public function cd_note_edit($pid) {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		
			$query = $this->db->query("SELECT * FROM i_contacts WHERE ic_owner='$oid'");
			$data["vendors"] = $query->result();
			
			$data["products"] = $this->product_model->load_products($oid);
			$data["taxes"] = $this->product_model->load_tax_groups($oid);
			$query = $this->db->query("SELECT * FROM i_taxes WHERE itx_owner = '$oid'");
			$data['taxes'] = $query->result();
			$query = $this->db->query("SELECT * FROM i_tax_group WHERE ittxg_owner = '$oid'");
			$data['tax_groups'] = $query->result();
            $query = $this->db->query("SELECT * FROM i_tax_group WHERE ittxg_owner = '$oid'");
			$data['tax_groups'] = $query->result();
			$data['logo'] = base_url().'assets/uploads/'.$oid.'/logo/'.$sess_data['user_details'][0]->iu_logo;
			$data['logo_sec'] = base_url().'assets/uploads/'.$oid.'/logo/'.$sess_data['user_details'][0]->iu_logo_sec;
			$data['title_doc'] = "Order Details: ";#.$pid;
			
			$query = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id WHERE it_owner='$oid' AND (it_type='Credit Note' OR it_type='Debit Note') AND it_id='$pid'");
			$data["txn"] = $query->result();
			$data["txn_details"] = $this->txn_model->load_transaction_details($oid, $pid);
			$data['txn_transport'] = $this->txn_model->load_transaction_transport_details($oid, $pid);
			$data['txn_prod_tax'] = $this->txn_model->load_transaction_prod_tax_details($oid, $pid);
			
			$data['gst_num'] = $sess_data['user_details'][0]->ic_gst_number;
			$data['bank'] = $sess_data['user_details'][0]->ic_bank_name;
			$data['branch'] = $sess_data['user_details'][0]->ic_bank_branch;
			$data['ifsc'] = $sess_data['user_details'][0]->ic_bank_ifsc;
			$data['acc'] = $sess_data['user_details'][0]->ic_bank_accno;

			$query = $this->db->query("SELECT * FROM i_taxes WHERE itx_owner = '$oid'");
			$data['taxes'] = $query->result();

			$query = $this->db->query("SELECT * FROM i_tax_group WHERE ittxg_owner = '$oid'");
			$data['tax_groups'] = $query->result();

            $query = $this->db->query("SELECT * FROM i_tax_group WHERE ittxg_owner = '$oid'");
			$data['tax_groups'] = $query->result();

            
			$data['logo'] = base_url().'assets/uploads/'.$oid.'/logo/'.$sess_data['user_details'][0]->iu_logo;
			$data['title_doc'] = "Order Details: ".$pid;
			
			$data['oid'] = $oid;
			$data['tid'] = $pid;

			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Edit Credit/Debit Note";
			$ert['search'] = "false";
			$ert['search_placeholder'] = "Search Vendors";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/transactions/credit_debit_note_add', $data);
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}

	public function update_cd_note($tid) {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			$dt = date('Y-m-d H:m:s');

			$cid = $this->contact_model->save_contact_by_name($oid, $uid, $dt, $this->input->post('vendor'), 'Dealer', null, null, null, null, null);
            
            $tid = $this->txn_model->update_cd_notes($oid, $tid, $uid, $cid, $this->input->post('date'), $this->input->post('txn'), $this->input->post('note'), null, 'done', $this->input->post('name'), $this->input->post('qty'), $this->input->post('rate'), $this->input->post('amt'), $this->input->post('tax_group'), $this->input->post('alias'), $dt, $this->input->post('type'), $this->input->post('txn_link'),$this->input->post('type_sec'));
            print_r($tid);
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}
	
	public function delete_cd_note($tid) {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			
			$tid = $this->input->post('txnid');
			
			$this->db->where(array('it_id' => $tid,'it_owner' => $oid));
			$this->db->delete('i_txns');
			
			$query = $this->db->query("SELECT * FROM i_txn_nums WHERE itn_type='Invoice' AND itn_owner='$oid'");
			$result = $query->result();
			if(count($result) > 0) {
		        $x = $result[0]->itn_num - 1;    
		    } else {
		        $x=0;
		    }
		    
			$this->db->where(array('itn_type' => 'Invoice','itn_owner' => $oid));
			$this->db->update('i_txn_nums', array('itn_num'=> $x));
					
		}
	}

########## LEDGERS ################
	public function ledgers() {
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
			
		
			$query = $this->db->query("SELECT * FROM i_contacts WHERE ic_owner='$oid'");
			$data["contacts"] = $query->result();
			
			$data["products"] = $this->product_model->load_products($oid);
			
			$query = $this->db->query("SELECT itp_mode FROM i_txn_payments WHERE itp_owner = '$oid' GROUP BY itp_mode");
			$data["modes"] = $query->result();
			
			$data['oid'] = $oid;


			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Ledgers";
			$ert['search'] = "false";
			$ert['search_placeholder'] = "Search Vendors";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/transactions/ledgers', $data);
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}
	
	public function get_ledger_data() {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		    $type = $this->input->post('type');
		    $c = $this->input->post('c');
		    $from = $this->input->post('from');
		    $to = $this->input->post('to');
		    $type = $this->input->post('type');
		    $payment = $this->input->post('payment');
		    $filter = $this->input->post('filter');
		    
		    $query = $this->db->query("SELECT * FROM i_contacts WHERE ic_owner='$oid' AND ic_name='$c'");
		    $result = $query->result();
		    
		    if(count($result) > 0 ) {
		        if($filter == false) {
		            $cid = $result[0]->ic_id;
    		        
    		        $que = $this->db->query("SELECT * FROM i_txns WHERE it_owner='$oid' AND it_c_id='$cid' AND it_type IN ('Invoice','Purchase','Credit Note','Debit Note','Delivery')");
    		        $res = $que->result();
    		        
    		        $data = [];
    		        for($i=0;$i<count($res); $i++) {
    		            array_push($data, array("txdate" => $res[$i]->it_date, "type" => $res[$i]->it_type, "txno" => $res[$i]->it_txn_no, "txdetails" => "", "credit" => $res[$i]->it_amount, "debit" => 0));
    		        }
    		        
    		        $que = $this->db->query("SELECT * FROM i_txn_payments WHERE itp_owner='$oid' AND itp_c_id='$cid' GROUP BY itp_amt, itp_created");
    		        $res = $que->result();
    		        
    		        for($i=0;$i<count($res); $i++) {
    		            array_push($data, array("txdate" => $res[$i]->itp_date, "type" => "Payment", "txno" => "", "txdetails" => $res[$i]->itp_mode.' '.$res[$i]->itp_details, "credit" => 0, "debit" => $res[$i]->itp_amt));
    		        }
    		        
    		        $que = $this->db->query("SELECT * FROM i_ledgers WHERE il_owner='$oid' AND il_c_id='$cid'");
    		        $res = $que->result();
    		        
    		        for($i=0;$i<count($res); $i++) {
    		            if($res[$i]->il_type == "credit") {
    		                array_push($data, array("txid" => $res[$i]->il_id, "txdate" => $res[$i]->il_date, "type" => "Entry", "txno" => "", "txdetails" => $res[$i]->il_detail, "credit" => $res[$i]->il_amt, "debit" => 0));    
    		            } else {
    		                array_push($data, array("txid" => $res[$i]->il_id, "txdate" => $res[$i]->il_date, "type" => "Entry", "txno" => "", "txdetails" => $res[$i]->il_detail, "credit" => 0, "debit" => $res[$i]->il_amt));   
    		            }
    		            
    		        }
    		        
    		        array_multisort($data, SORT_DESC, SORT_REGULAR);
    		        print_r(json_encode($data));
    		            
		        } else {
		            $cid = $result[0]->ic_id;
    		        if($from!=null && $to!=null) {
		                if($type != null || $type != []) {
		                    $types = implode($type, "','");
		                    $que = $this->db->query("SELECT * FROM i_txns WHERE it_owner='$oid' AND it_c_id='$cid' AND (it_date BETWEEN '$from' AND '$to') AND it_type IN ('$types')");
		                } else {
		                    $que = $this->db->query("SELECT * FROM i_txns WHERE it_owner='$oid' AND it_c_id='$cid' AND (it_date BETWEEN '$from' AND '$to')");
		                }
    		        } else {
    		            if($type != null || $type != []) {
		                    $types = implode($type, "','");
		                  //  echo "SELECT * FROM i_txns WHERE it_owner='$oid' AND it_c_id='$cid' AND it_type IN ('$types')";
		                    $que = $this->db->query("SELECT * FROM i_txns WHERE it_owner='$oid' AND it_c_id='$cid' AND it_type IN ('$types')");
		                } else {
		                    $que = $this->db->query("SELECT * FROM i_txns WHERE it_owner='$oid' AND it_c_id='$cid'");
		                }
    		        }
    		        $res = $que->result();
    		        
    		        $data = [];
    		        for($i=0;$i<count($res); $i++) {
    		            array_push($data, array("txdate" => $res[$i]->it_date, "type" => $res[$i]->it_type, "txno" => $res[$i]->it_txn_no, "txdetails" => "", "credit" => $res[$i]->it_amount, "debit" => 0));
    		        }
    		        
    		        if($from!=null && $to!=null) {
		                if($payment != null || $payment != []) {
		                    $pays = implode($payment, "','");
		                    $que = $this->db->query("SELECT * FROM i_txn_payments WHERE itp_owner='$oid' AND itp_c_id='$cid' AND (itp_date BETWEEN '$from' AND '$to') AND itp_mode IN ('$pays')");
		                } else {
		                    $que = $this->db->query("SELECT * FROM i_txn_payments WHERE itp_owner='$oid' AND itp_c_id='$cid' AND (itp_date BETWEEN '$from' AND '$to')");
		                }
    		        } else {
    		            if($payment != null || $payment != []) {
		                    $pays = implode($payment, "','");
		                    $que = $this->db->query("SELECT * FROM i_txn_payments WHERE itp_owner='$oid' AND itp_c_id='$cid' AND itp_mode IN ('$pays')");
		                } else {
		                    $que = $this->db->query("SELECT * FROM i_txn_payments WHERE itp_owner='$oid' AND itp_c_id='$cid'");
		                }
    		        }
    		        $res = $que->result();
    		        
    		        for($i=0;$i<count($res); $i++) {
    		            array_push($data, array("txdate" => $res[$i]->itp_date, "type" => "Payment", "txno" => "", "txdetails" => $res[$i]->itp_mode.' '.$res[$i]->itp_details, "credit" => 0, "debit" => $res[$i]->itp_amt));
    		        }
    		        
    		        if($this->input->post('entry') == "true") {
    		            if($from!=null && $to!=null) {
        		            $que = $this->db->query("SELECT * FROM i_ledgers WHERE il_owner='$oid' AND il_c_id='$cid' AND (il_date BETWEEN '$from' AND '$to')");
        		        } else {
                            $que = $this->db->query("SELECT * FROM i_ledgers WHERE il_owner='$oid' AND il_c_id='$cid'");
        		        }
        		        
        		        $res = $que->result();
        		        
        		        for($i=0;$i<count($res); $i++) {
        		            if($res[$i]->il_type == "credit") {
        		                array_push($data, array("txid" => $res[$i]->il_id, "txdate" => $res[$i]->il_date, "type" => "Entry", "txno" => "", "txdetails" => $res[$i]->il_detail, "credit" => $res[$i]->il_amt, "debit" => 0));    
        		            } else {
        		                array_push($data, array("txid" => $res[$i]->il_id, "txdate" => $res[$i]->il_date, "type" => "Entry", "txno" => "", "txdetails" => $res[$i]->il_detail, "credit" => 0, "debit" => $res[$i]->il_amt));   
        		            }
        		            
        		        }
        		            
    		        }
    		        
    		        array_multisort($data, SORT_DESC, SORT_REGULAR);
    		        print_r(json_encode($data));
    		        
		        }
		        
		    } else {
		        echo "No";
		    } 
		    
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}
	
	public function save_ledger_entry() {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		    $uid = $sess_data['user_details'][0]->iu_id;
		    $c = $this->input->post('c');
		    
		    $query = $this->db->query("SELECT * FROM i_contacts WHERE ic_owner='$oid' AND ic_name='$c'");
		    $result = $query->result();
		    
		    if(count($result) > 0 ) {
		        $cid = $result[0]->ic_id;
		        $dt = date('Y-m-d H:m:s');
		        $data = array(
		            'il_c_id' => $cid,
		            'il_date' => $this->input->post('date'),
		            'il_detail' => $this->input->post('details'),
		            'il_type' => $this->input->post('type'),
		            'il_amt' => $this->input->post('amt'),
		            'il_owner' => $oid,
		            'il_created' => $dt,
		            'il_created_by' => $uid
		            );
		        $this->db->insert('i_ledgers', $data);
		        echo "true";
		    } else {
		        echo "false";
		    }
		} else {
			redirect(base_url().'distributors/Account/login');
		}
    }
    

    public function get_ledger_transactions() {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		    $type = $this->input->post('type');
		    $keyword = $this->input->post('keywords');
		    
		    $query = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id WHERE a.it_owner = '$oid' AND b.ic_name='$keyword'");
		    print_r(json_encode($query->result()));
		} else {
			redirect(base_url().'distributors/Account/login');
		}
    }
    
    public function delete_ledger_transaction() {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		    
		    $data = array('itp_id' => $this->input->post('ptid'), 'itp_t_id' => $this->input->post('tid'), 'itp_owner' => $oid);
		    $this->db->where($data);
		    $this->db->delete('i_txn_payments');
		    echo "true";
		} else {
			redirect(base_url().'distributors/Account/login');
		}
    }
    
    public function get_ledger_history() {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		    $tid = implode($this->input->post('tid'), "','");
		    
		    $query = $this->db->query("SELECT * FROM i_txn_payments AS a LEFT JOIN i_txns AS b ON a.itp_t_id=b.it_id WHERE itp_t_id IN  ('$tid')");
		    print_r(json_encode($query->result()));
		} else {
			redirect(base_url().'distributors/Account/login');
		}
    }
    
    
	
	public function search_ledger_txns() {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		    
		    $keyword = $this->input->post('keyword');
		    $status = $this->input->post('status');
		    
		    $query = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id WHERE a.it_owner = '$oid'");
		    $data["txn"] = $query->result();
			
			$data['oid'] = $oid;

			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Payments";
			$ert['search'] = "true";
			$ert['search_placeholder'] = "Search Vendors";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/transactions/invoice', $data);
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}
	
	public function save_ledger() {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		    $uid = $sess_data['user_details'][0]->iu_id;
		    $dt = date('Y-m-d');
		    
		    $tid = $this->input->post('tid');
		    
		    for($i=0;$i<count($tid);$i++) {
		        $query = $this->db->query("SELECT * FROM i_txns WHERE it_id='$tid[$i]' AND it_owner='$oid'");
                $result = $query->result();
    		    $data = array(
    		        'itp_t_id' => $tid[$i],
    		        'itp_c_id' => $result[0]->it_c_id,
    		        'itp_date' => $this->input->post('date'),
    		        'itp_mode' => $this->input->post('mode'),
    		        'itp_details' => $this->input->post('details'),
    		        'itp_amt' => $this->input->post('amt'),
    		        'itp_owner' => $oid,
    		        'itp_created' => $dt,
    		        'itp_created_by' => $uid
    		        );
    		    $this->db->insert('i_txn_payments', $data);
    		    echo $this->db->insert_id();   
		    }
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}
	
	public function ledger_edit($tid) {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		
		    $query = $this->db->query("SELECT * FROM i_txn_payments AS a LEFT JOIN i_txns AS b ON a.itp_t_id=b.it_id WHERE itp_id='$tid' AND itp_owner='$oid'");
		    $data["edit_txn"] = $query->result();
		    
			$data["vendors"] = $this->contact_model->load_contacts($oid, 'Vendor');
			$data["products"] = $this->product_model->load_products($oid);
			
			$query = $this->db->query("SELECT itp_mode FROM i_txn_payments WHERE itp_owner = '$oid' GROUP BY itp_mode");
			$data["modes"] = $query->result();
			
			$data['oid'] = $oid;
            $data['tid'] = $tid;

			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Edit Payment";
			$ert['search'] = "false";
			$ert['search_placeholder'] = "Search Vendors";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/transactions/payment_add', $data);
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}

    public function update_ledger($t_id) {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		    $uid = $sess_data['user_details'][0]->iu_id;
		    $dt = date('Y-m-d');
		    
		    $tid = $this->input->post('tid');
            $query = $this->db->query("SELECT * FROM i_txns WHERE it_id='$tid' AND it_owner='$oid'");
            $result = $query->result();
		    $data = array(
		        'itp_t_id' => $this->input->post('tid'),
		        'itp_c_id' => $result[0]->it_c_id,
		        'itp_date' => $this->input->post('date'),
		        'itp_mode' => $this->input->post('mode'),
		        'itp_details' => $this->input->post('details'),
		        'itp_amt' => $this->input->post('amt'),
		        );
		        
		    $updata = array('itp_id' => $t_id, 'itp_owner' => $oid);
		    
		    $this->db->where($updata);
		    $this->db->update('i_txn_payments', $data);
		    echo $t_id;
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}

########## ANALYZE ################
	public function analyze() {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;

			$data['oid'] = $oid;
			
			$q=$this->db->query("SELECT * FROM i_users WHERE iu_owner='$oid' AND iu_id='$uid'");
			if($q->result()[0]->iu_status=="blocked") {
			    $this->session->unset_userdata('user_details');
        		$this->session->unset_userdata('status');
        		$this->session->unset_userdata('fy');
        		redirect(base_url().'distributors/Account/login');
			}
			
		
			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Analyze";
			$ert['search'] = "false";
			$ert['search_placeholder'] = "Search Vendors";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/transactions/analyze', $data);
		} else {
			redirect(base_url().'distributors/Account/login');
		}	
	}
	
	public function search_analyze() {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;

			$type = $this->input->post('type');
			$from = $this->input->post('from');
			$to = $this->input->post('to');
			$filter = $this->input->post('filter');
			$keyword = $this->input->post('keyword');
 			
			if($type == "total_orders") {
			    if($filter != "0") {
			        if($filter == "client") {
    			        $query = $this->db->query("SELECT COUNT(it_id) AS count, SUM(it_amount) AS amt FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id WHERE a.it_owner = '$oid' AND a.it_date BETWEEN '$from' AND '$to' AND a.it_type IN ('Delivery') AND a.it_status IN ('approved', 'shipping','delivered') AND b.ic_name LIKE '%$keyword%'");
        			    $result['sum'] = $query->result();
        			    
        			    $query = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id LEFT JOIN i_d_users AS c ON a.it_sub_dealer=c.idu_id WHERE a.it_owner='$oid' AND a.it_date BETWEEN '$from' AND '$to' AND a.it_type IN ('Delivery') AND a.it_status IN ('approved','shipping','delivered') AND b.ic_name LIKE '%$keyword%' ORDER BY a.it_id, a.it_date");
        			    $result['txntable'] = $query->result();
			        } else if($filter == "amt_greater") {
			            $query = $this->db->query("SELECT COUNT(it_id) AS count, SUM(it_amount) AS amt FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id WHERE a.it_owner = '$oid' AND a.it_date BETWEEN '$from' AND '$to' AND a.it_type IN ('Delivery') AND a.it_status IN ('approved', 'shipping','delivered') AND a.it_amount >= '$keyword'");
        			    $result['sum'] = $query->result();
        			    
        			    $query = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id  LEFT JOIN i_d_users AS c ON a.it_sub_dealer=c.idu_id WHERE a.it_owner='$oid' AND a.it_date BETWEEN '$from' AND '$to' AND a.it_type IN ('Delivery') AND a.it_status IN ('approved','shipping','delivered') AND a.it_amount >= '$keyword' ORDER BY a.it_id, a.it_date");
        			    $result['txntable'] = $query->result();
			        } else if($filter == "amt_less") {
			            $query = $this->db->query("SELECT COUNT(it_id) AS count, SUM(it_amount) AS amt FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id WHERE a.it_owner = '$oid' AND a.it_date BETWEEN '$from' AND '$to' AND a.it_type IN ('Delivery') AND a.it_status IN ('approved', 'shipping','delivered') AND a.it_amount <= '$keyword'");
        			    $result['sum'] = $query->result();
        			    
        			    $query = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id  LEFT JOIN i_d_users AS c ON a.it_sub_dealer=c.idu_id WHERE a.it_owner='$oid' AND a.it_date BETWEEN '$from' AND '$to' AND a.it_type IN ('Delivery') AND a.it_status IN ('approved','shipping','delivered') AND a.it_amount <= '$keyword' ORDER BY a.it_id, a.it_date");
        			    $result['txntable'] = $query->result();
			        } else if($filter == "txnid") {
			            $query = $this->db->query("SELECT COUNT(it_id) AS count, SUM(it_amount) AS amt FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id WHERE a.it_owner = '$oid' AND a.it_date BETWEEN '$from' AND '$to' AND a.it_type IN ('Delivery') AND a.it_status IN ('approved', 'shipping','delivered') AND a.it_txn_no = '$keyword'");
        			    $result['sum'] = $query->result();
        			    
        			    $query = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id  LEFT JOIN i_d_users AS c ON a.it_sub_dealer=c.idu_id WHERE a.it_owner='$oid' AND a.it_date BETWEEN '$from' AND '$to' AND a.it_type IN ('Delivery') AND a.it_status IN ('approved','shipping','delivered') AND a.it_txn_no = '$keyword' ORDER BY a.it_id, a.it_date");
        			    $result['txntable'] = $query->result();
			        } else if($filter == "txnstatus") {
			            $query = $this->db->query("SELECT COUNT(it_id) AS count, SUM(it_amount) AS amt FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id WHERE a.it_owner = '$oid' AND a.it_date BETWEEN '$from' AND '$to' AND a.it_type IN ('Delivery') AND a.it_status IN ('pending','approved', 'shipping','delivered') AND a.it_status LIKE '%$keyword%'");
        			    $result['sum'] = $query->result();
        			    
        			    $query = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id  LEFT JOIN i_d_users AS c ON a.it_sub_dealer=c.idu_id WHERE a.it_owner='$oid' AND a.it_date BETWEEN '$from' AND '$to' AND a.it_type IN ('Delivery') AND a.it_status IN ('pending','approved','shipping','delivered') AND a.it_status LIKE '%$keyword%' ORDER BY a.it_id, a.it_date");
        			    $result['txntable'] = $query->result();
			        } else if($filter == "txntype") {
			            $query = $this->db->query("SELECT COUNT(it_id) AS count, SUM(it_amount) AS amt FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id WHERE a.it_owner = '$oid' AND a.it_date BETWEEN '$from' AND '$to' AND a.it_type IN ('Delivery') AND a.it_status IN ('approved', 'shipping','delivered') AND a.it_type LIKE '%$keyword%'");
        			    $result['sum'] = $query->result();
        			    
        			    $query = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id  LEFT JOIN i_d_users AS c ON a.it_sub_dealer=c.idu_id WHERE a.it_owner='$oid' AND a.it_date BETWEEN '$from' AND '$to' AND a.it_type IN ('Delivery') AND a.it_status IN ('approved','shipping','delivered') AND a.it_type LIKE '%$keyword%' ORDER BY a.it_id, a.it_date");
        			    $result['txntable'] = $query->result();
			        } else if($filter == "txnmode") {
			            $query = $this->db->query("SELECT COUNT(it_id) AS count, SUM(it_amount) AS amt FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id WHERE a.it_owner = '$oid' AND a.it_date BETWEEN '$from' AND '$to' AND a.it_type IN ('Delivery') AND a.it_status IN ('approved', 'shipping','delivered') AND a.it_mode LIKE '%$keyword%'");
        			    $result['sum'] = $query->result();
        			    
        			    $query = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id  LEFT JOIN i_d_users AS c ON a.it_sub_dealer=c.idu_id WHERE a.it_owner='$oid' AND a.it_date BETWEEN '$from' AND '$to' AND a.it_type IN ('Delivery') AND a.it_status IN ('approved','shipping','delivered') AND a.it_mode LIKE '%$keyword%' ORDER BY a.it_id, a.it_date");
        			    $result['txntable'] = $query->result();
			        }
    			    print_r(json_encode($result));
			    } else {
			        $query = $this->db->query("SELECT COUNT(it_id) AS count, SUM(it_amount) AS amt FROM i_txns WHERE it_owner = '$oid' AND it_date BETWEEN '$from' AND '$to' AND it_type IN ('Delivery') AND it_status IN ('approved', 'shipping','delivered')");
    			    $result['sum'] = $query->result();
    			    
    			    $query = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id  LEFT JOIN i_d_users AS c ON a.it_sub_dealer=c.idu_id WHERE a.it_owner='$oid' AND a.it_date BETWEEN '$from' AND '$to' AND a.it_type IN ('Delivery') AND a.it_status IN ('approved','shipping','delivered') ORDER BY a.it_id, a.it_date");
    			    $result['txntable'] = $query->result();
    			    
    			    print_r(json_encode($result));   
			    }
			} else if($type == "total_sale") {
			    if($filter != "0") {
			        if($filter == "client") {
			            $query = $this->db->query("SELECT COUNT(it_id) AS count, SUM(it_amount) AS amt FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id WHERE a.it_owner = '$oid' AND a.it_date BETWEEN '$from' AND '$to' AND a.it_type IN ('Invoice','Delivery') AND a.it_status IN ('shipping','delivered','invoiced','paid','cancelled','hold') AND b.ic_name LIKE '%$keyword%'");
        			    $result['sum'] = $query->result();
        			    
        			    $query = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id  LEFT JOIN i_d_users AS c ON a.it_sub_dealer=c.idu_id  LEFT JOIN i_txn_transport_details AS d ON a.it_id=d.ittd_txn_id LEFT JOIN i_expenses AS e ON d.ittd_expense=e.ie_id WHERE a.it_owner='$oid' AND a.it_date BETWEEN '$from' AND '$to' AND a.it_type IN ('Invoice','Delivery') AND a.it_status IN ('shipping','delivered','invoiced','paid','cancelled','hold') AND b.ic_name LIKE '%$keyword%'  ORDER BY a.it_id, a.it_date");
        			    $result['txntable'] = $query->result();
			        } else if($filter == "amt_greater") {
			            $query = $this->db->query("SELECT COUNT(it_id) AS count, SUM(it_amount) AS amt FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id WHERE a.it_owner = '$oid' AND a.it_date BETWEEN '$from' AND '$to' AND a.it_type IN ('Invoice','Delivery') AND a.it_status IN ('shipping','delivered','invoiced','paid','cancelled','hold') AND a.it_amount >= '$keyword'");
        			    $result['sum'] = $query->result();
        			    
        			    $query = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id  LEFT JOIN i_d_users AS c ON a.it_sub_dealer=c.idu_id  LEFT JOIN i_txn_transport_details AS d ON a.it_id=d.ittd_txn_id LEFT JOIN i_expenses AS e ON d.ittd_expense=e.ie_id  WHERE a.it_owner='$oid' AND a.it_date BETWEEN '$from' AND '$to' AND a.it_type IN ('Invoice','Delivery') AND a.it_status IN ('shipping','delivered','invoiced','paid','cancelled','hold') AND a.it_amount >= '$keyword'  ORDER BY a.it_id, a.it_date");
        			    $result['txntable'] = $query->result();
			        } else if($filter == "amt_less") {
			            $query = $this->db->query("SELECT COUNT(it_id) AS count, SUM(it_amount) AS amt FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id WHERE a.it_owner = '$oid' AND a.it_date BETWEEN '$from' AND '$to' AND a.it_type IN ('Invoice','Delivery') AND a.it_status IN ('shipping','delivered','invoiced','paid','cancelled','hold') AND a.it_amount <= '$keyword'");
        			    $result['sum'] = $query->result();
        			    
        			    $query = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id  LEFT JOIN i_d_users AS c ON a.it_sub_dealer=c.idu_id  LEFT JOIN i_txn_transport_details AS d ON a.it_id=d.ittd_txn_id LEFT JOIN i_expenses AS e ON d.ittd_expense=e.ie_id  WHERE a.it_owner='$oid' AND a.it_date BETWEEN '$from' AND '$to' AND a.it_type IN ('Invoice','Delivery') AND a.it_status IN ('shipping','delivered','invoiced','paid','cancelled','hold') AND a.it_amount <= '$keyword'  ORDER BY a.it_id, a.it_date");
        			    $result['txntable'] = $query->result();
			        } else if($filter == "txnid") {
			            $query = $this->db->query("SELECT COUNT(it_id) AS count, SUM(it_amount) AS amt FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id WHERE a.it_owner = '$oid' AND a.it_date BETWEEN '$from' AND '$to' AND a.it_type IN ('Invoice','Delivery') AND a.it_status IN ('shipping','delivered','invoiced','paid','cancelled','hold') AND a.it_txn_no = '$keyword'");
        			    $result['sum'] = $query->result();
        			    
        			    $query = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id  LEFT JOIN i_d_users AS c ON a.it_sub_dealer=c.idu_id  LEFT JOIN i_txn_transport_details AS d ON a.it_id=d.ittd_txn_id LEFT JOIN i_expenses AS e ON d.ittd_expense=e.ie_id  WHERE a.it_owner='$oid' AND a.it_date BETWEEN '$from' AND '$to' AND a.it_type IN ('Invoice','Delivery') AND a.it_status IN ('shipping','delivered','invoiced','paid','cancelled','hold') AND a.it_txn_no = '$keyword'  ORDER BY a.it_id, a.it_date");
        			    $result['txntable'] = $query->result();
			        } else if($filter == "txnstatus") {
			            $query = $this->db->query("SELECT COUNT(it_id) AS count, SUM(it_amount) AS amt FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id WHERE a.it_owner = '$oid' AND a.it_date BETWEEN '$from' AND '$to' AND a.it_type IN ('Invoice','Delivery') AND a.it_status IN ('pending','shipping','delivered','invoiced','paid','cancelled','hold') AND a.it_status LIKE '%$keyword%'");
        			    $result['sum'] = $query->result();
        			    
        			    $query = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id  LEFT JOIN i_d_users AS c ON a.it_sub_dealer=c.idu_id  LEFT JOIN i_txn_transport_details AS d ON a.it_id=d.ittd_txn_id LEFT JOIN i_expenses AS e ON d.ittd_expense=e.ie_id  WHERE a.it_owner='$oid' AND a.it_date BETWEEN '$from' AND '$to' AND a.it_type IN ('Invoice','Delivery') AND a.it_status IN ('pending','shipping','delivered','invoiced','paid','cancelled','hold') AND a.it_status LIKE '%$keyword%'  ORDER BY a.it_id, a.it_date");
        			    $result['txntable'] = $query->result();
			        } else if($filter == "txntype") {
			            $query = $this->db->query("SELECT COUNT(it_id) AS count, SUM(it_amount) AS amt FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id WHERE a.it_owner = '$oid' AND a.it_date BETWEEN '$from' AND '$to' AND a.it_type IN ('Invoice','Delivery') AND a.it_status IN ('shipping','delivered','invoiced','paid','cancelled','hold') AND a.it_type LIKE '%$keyword%'");
        			    $result['sum'] = $query->result();
        			    
        			    $query = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id  LEFT JOIN i_d_users AS c ON a.it_sub_dealer=c.idu_id  LEFT JOIN i_txn_transport_details AS d ON a.it_id=d.ittd_txn_id LEFT JOIN i_expenses AS e ON d.ittd_expense=e.ie_id  WHERE a.it_owner='$oid' AND a.it_date BETWEEN '$from' AND '$to' AND a.it_type IN ('Invoice','Delivery') AND a.it_status IN ('shipping','delivered','invoiced','paid','cancelled','hold') AND a.it_type LIKE '%$keyword%'  ORDER BY a.it_id, a.it_date");
        			    $result['txntable'] = $query->result();
			        } else if($filter == "txnmode") {
			            $query = $this->db->query("SELECT COUNT(it_id) AS count, SUM(it_amount) AS amt FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id WHERE a.it_owner = '$oid' AND a.it_date BETWEEN '$from' AND '$to' AND a.it_type IN ('Invoice','Delivery') AND a.it_status IN ('shipping','delivered','invoiced','paid','cancelled','hold') AND a.it_mode LIKE '%$keyword%'");
        			    $result['sum'] = $query->result();
        			    
        			    $query = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id  LEFT JOIN i_d_users AS c ON a.it_sub_dealer=c.idu_id  LEFT JOIN i_txn_transport_details AS d ON a.it_id=d.ittd_txn_id LEFT JOIN i_expenses AS e ON d.ittd_expense=e.ie_id  WHERE a.it_owner='$oid' AND a.it_date BETWEEN '$from' AND '$to' AND a.it_type IN ('Invoice','Delivery') AND a.it_status IN ('shipping','delivered','invoiced','paid','cancelled','hold') AND a.it_mode LIKE '%$keyword%'  ORDER BY a.it_id, a.it_date");
        			    $result['txntable'] = $query->result();
			        }
    			    print_r(json_encode($result));
			    } else {
			        $query = $this->db->query("SELECT COUNT(it_id) AS count, SUM(it_amount) AS amt FROM i_txns WHERE it_owner = '$oid' AND it_date BETWEEN '$from' AND '$to' AND it_type IN ('Invoice','Delivery') AND it_status IN ('shipping','delivered','invoiced','paid','cancelled','hold')");
    			    $result['sum'] = $query->result();
    			    
    			    $query = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id  LEFT JOIN i_d_users AS c ON a.it_sub_dealer=c.idu_id LEFT JOIN i_txn_transport_details AS d ON a.it_id=d.ittd_txn_id LEFT JOIN i_expenses AS e ON d.ittd_expense=e.ie_id  WHERE a.it_owner='$oid' AND a.it_date BETWEEN '$from' AND '$to' AND a.it_type IN ('Invoice','Delivery') AND a.it_status IN ('shipping','delivered','invoiced','paid','cancelled','hold')  ORDER BY a.it_id, a.it_date");
    			    $result['txntable'] = $query->result();
    			    
    			    print_r(json_encode($result));   
			    }
			
			} else if($type == "product_trend") {
			    if($filter != "0") {
			        if($filter == "client") {
			            $query = $this->db->query("SELECT COUNT(it_id) AS count, SUM(it_amount) AS amt FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id WHERE a.it_owner = '$oid' AND a.it_date BETWEEN '$from' AND '$to' AND a.it_type IN ('Invoice','Delivery') AND a.it_status IN ('shipping','delivered','invoiced','paid','cancelled','hold') AND b.ic_name LIKE '%$keyword%'");
        			    $result['sum'] = $query->result();
        			    
        			    $query = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id  LEFT JOIN i_d_users AS c ON a.it_sub_dealer=c.idu_id WHERE a.it_owner='$oid' AND a.it_date BETWEEN '$from' AND '$to' AND a.it_type IN ('Invoice','Delivery') AND a.it_status IN ('shipping','delivered','invoiced','paid','cancelled','hold') AND b.ic_name LIKE '%$keyword%' ORDER BY a.it_id, a.it_date");
        			    $result['txntable'] = $query->result();
			        } else if($filter == "amt_greater") {
			            $query = $this->db->query("SELECT COUNT(it_id) AS count, SUM(it_amount) AS amt FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id WHERE a.it_owner = '$oid' AND a.it_date BETWEEN '$from' AND '$to' AND a.it_type IN ('Invoice','Delivery') AND a.it_status IN ('shipping','delivered','invoiced','paid','cancelled','hold') AND a.it_amount >= '$keyword'");
        			    $result['sum'] = $query->result();
        			    
        			    $query = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id  LEFT JOIN i_d_users AS c ON a.it_sub_dealer=c.idu_id WHERE a.it_owner='$oid' AND a.it_date BETWEEN '$from' AND '$to' AND a.it_type IN ('Invoice','Delivery') AND a.it_status IN ('shipping','delivered','invoiced','paid','cancelled','hold') AND a.it_amount >= '$keyword' ORDER BY a.it_id, a.it_date");
        			    $result['txntable'] = $query->result();
			        } else if($filter == "amt_less") {
			            $query = $this->db->query("SELECT COUNT(it_id) AS count, SUM(it_amount) AS amt FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id WHERE a.it_owner = '$oid' AND a.it_date BETWEEN '$from' AND '$to' AND a.it_type IN ('Invoice','Delivery') AND a.it_status IN ('shipping','delivered','invoiced','paid','cancelled','hold') AND a.it_amount <= '$keyword'");
        			    $result['sum'] = $query->result();
        			    
        			    $query = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id  LEFT JOIN i_d_users AS c ON a.it_sub_dealer=c.idu_id WHERE a.it_owner='$oid' AND a.it_date BETWEEN '$from' AND '$to' AND a.it_type IN ('Invoice','Delivery') AND a.it_status IN ('shipping','delivered','invoiced','paid','cancelled','hold') AND a.it_amount <= '$keyword' ORDER BY a.it_id, a.it_date");
        			    $result['txntable'] = $query->result();
			        } else if($filter == "txnid") {
			            $query = $this->db->query("SELECT COUNT(it_id) AS count, SUM(it_amount) AS amt FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id WHERE a.it_owner = '$oid' AND a.it_date BETWEEN '$from' AND '$to' AND a.it_type IN ('Invoice','Delivery') AND a.it_status IN ('shipping','delivered','invoiced','paid','cancelled','hold') AND a.it_txn_no = '$keyword'");
        			    $result['sum'] = $query->result();
        			    
        			    $query = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id  LEFT JOIN i_d_users AS c ON a.it_sub_dealer=c.idu_id WHERE a.it_owner='$oid' AND a.it_date BETWEEN '$from' AND '$to' AND a.it_type IN ('Invoice','Delivery') AND a.it_status IN ('shipping','delivered','invoiced','paid','cancelled','hold') AND a.it_txn_no = '$keyword' ORDER BY a.it_id, a.it_date");
        			    $result['txntable'] = $query->result();
			        } else if($filter == "txnstatus") {
			            $query = $this->db->query("SELECT COUNT(it_id) AS count, SUM(it_amount) AS amt FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id WHERE a.it_owner = '$oid' AND a.it_date BETWEEN '$from' AND '$to' AND a.it_type IN ('Invoice','Delivery') AND a.it_status IN ('pending','shipping','delivered','invoiced','paid','cancelled','hold') AND a.it_status LIKE '%$keyword%'");
        			    $result['sum'] = $query->result();
        			    
        			    $query = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id  LEFT JOIN i_d_users AS c ON a.it_sub_dealer=c.idu_id WHERE a.it_owner='$oid' AND a.it_date BETWEEN '$from' AND '$to' AND a.it_type IN ('Invoice','Delivery') AND a.it_status IN ('pending','shipping','delivered','invoiced','paid','cancelled','hold') AND a.it_status LIKE '%$keyword%' ORDER BY a.it_id, a.it_date");
        			    $result['txntable'] = $query->result();
			        } else if($filter == "txntype") {
			            $query = $this->db->query("SELECT COUNT(it_id) AS count, SUM(it_amount) AS amt FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id WHERE a.it_owner = '$oid' AND a.it_date BETWEEN '$from' AND '$to' AND a.it_type IN ('Invoice','Delivery') AND a.it_status IN ('shipping','delivered','invoiced','paid','cancelled','hold') AND a.it_type LIKE '%$keyword%'");
        			    $result['sum'] = $query->result();
        			    
        			    $query = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id  LEFT JOIN i_d_users AS c ON a.it_sub_dealer=c.idu_id WHERE a.it_owner='$oid' AND a.it_date BETWEEN '$from' AND '$to' AND a.it_type IN ('Invoice','Delivery') AND a.it_status IN ('shipping','delivered','invoiced','paid','cancelled','hold') AND a.it_type LIKE '%$keyword%' ORDER BY a.it_id, a.it_date");
        			    $result['txntable'] = $query->result();
			        } else if($filter == "txnmode") {
			            $query = $this->db->query("SELECT COUNT(it_id) AS count, SUM(it_amount) AS amt FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id WHERE a.it_owner = '$oid' AND a.it_date BETWEEN '$from' AND '$to' AND a.it_type IN ('Invoice','Delivery') AND a.it_status IN ('shipping','delivered','invoiced','paid','cancelled','hold') AND a.it_mode LIKE '%$keyword%'");
        			    $result['sum'] = $query->result();
        			    
        			    $query = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id  LEFT JOIN i_d_users AS c ON a.it_sub_dealer=c.idu_id WHERE a.it_owner='$oid' AND a.it_date BETWEEN '$from' AND '$to' AND a.it_type IN ('Invoice','Delivery') AND a.it_status IN ('shipping','delivered','invoiced','paid','cancelled','hold') AND a.it_mode LIKE '%$keyword%' ORDER BY a.it_id, a.it_date");
        			    $result['txntable'] = $query->result();
			        }
    			    print_r(json_encode($result));
			    } else {
			        $query = $this->db->query("SELECT SUM(a.itp_qty) AS sum, a.itd_p_id AS pid, b.ip_name FROM `i_txns_details` AS a LEFT JOIN i_products AS b ON a.itd_p_id=b.ip_id WHERE b.ip_name IS NOT NULL AND a.itp_owner='$oid' GROUP BY a.itd_p_id ORDER BY sum DESC LIMIT 1");
			        $result['prodhigh'] = $query->result();
			        
			        $query = $this->db->query("SELECT SUM(a.itp_qty) AS sum, a.itd_p_id AS pid, b.ip_name FROM `i_txns_details` AS a LEFT JOIN i_products AS b ON a.itd_p_id=b.ip_id WHERE b.ip_name IS NOT NULL AND a.itp_owner='$oid' GROUP BY a.itd_p_id ORDER BY sum LIMIT 1");
			        $result['prodlow'] = $query->result();
			        
			        $query = $this->db->query("SELECT SUM(a.itp_qty) AS sum, a.itd_p_id AS pid, b.ip_name FROM `i_txns_details` AS a LEFT JOIN i_products AS b ON a.itd_p_id=b.ip_id WHERE b.ip_name IS NOT NULL AND a.itp_owner='$oid' GROUP BY a.itd_p_id ORDER BY sum DESC");
			        $result['prod'] = $query->result();
			        print_r(json_encode($result));
			    }
			
			} else if($type == "txn_trend") {
		        $query = $this->db->query("SELECT SUM(it_amount) AS sum, it_date AS date FROM i_txns WHERE it_owner='$oid' AND it_date BETWEEN '$from' AND '$to' GROUP BY it_date");
		        $result['txntrend'] = $query->result();
		        print_r(json_encode($result));
			} else if($type == "txn_tax") {
			    if($filter=="client" && $keyword!="") {
			        $query = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id LEFT JOIN (SELECT IFNULL(SUM(d.ie_amount), 0) AS transport_amount, d.ie_id, c.ittd_txn_id AS txn_id FROM i_txn_transport_details AS c LEFT JOIN i_expenses AS d ON d.ie_id=c.ittd_expense GROUP BY d.ie_id, c.ittd_txn_id) AS e ON e.txn_id=a.it_id WHERE (it_type='Invoice' OR it_type='Credit Note' AND it_type_sec='Invoice') AND b.ic_name LIKE '%$keyword%' AND it_owner='$oid' AND it_date BETWEEN '$from' AND '$to' ORDER BY it_date");
			    } else {
			        $query = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id LEFT JOIN (SELECT IFNULL(SUM(d.ie_amount), 0) AS transport_amount, d.ie_id, c.ittd_txn_id AS txn_id FROM i_txn_transport_details AS c LEFT JOIN i_expenses AS d ON d.ie_id=c.ittd_expense GROUP BY d.ie_id, c.ittd_txn_id) AS e ON e.txn_id=a.it_id WHERE (it_type='Invoice' OR it_type='Credit Note' AND it_type_sec='Invoice') AND it_owner='$oid' AND it_date BETWEEN '$from' AND '$to' ORDER BY it_date");
			    }
		        
		        $data = $query->result();
		        
		        $result['txntax']=[];
		        for($i=0; $i<count($data);$i++) {
		            $tid = $data[$i]->it_id;
		            $que = $this->db->query("SELECT * FROM i_txns_details WHERE itp_owner='$oid' AND itd_t_id='$tid'");
		            $res = $que->result();
		            $amt=0;$tamt=0;$tax=[];
	                for($j=0;$j<count($res);$j++){
	                    $amt+=$res[$j]->itp_value;
	                    $tamt+=$res[$j]->itp_amount;
	                }
	                $que1=$this->db->query("SELECT a.itpt_id AS id, SUM(a.itpt_t_amount) AS amt, b.itx_id AS taxid, b.itx_name AS tax FROM i_txn_product_taxes AS a LEFT JOIN i_taxes AS b ON a.itpt_tx_id=b.itx_id WHERE a.itpt_owner='$oid' AND a.itpt_txn_id='$tid' GROUP BY a.itpt_tx_id");
	                $res1=$que1->result();
	                for($j=0;$j<count($res1);$j++){
	                    array_push($tax, array('name' => $res1[$j]->tax, 'amt' => round($res1[$j]->amt, 2), 'taxid' => $res1[$j]->taxid));
	                }
	                
	                array_push($result['txntax'], array(
		                'id' => $data[$i]->it_txn_no,
		                'date' => $data[$i]->it_date,
		                'name' => $data[$i]->ic_name,
		                'gstno' => $data[$i]->ic_gst_number,
		                'transport' => $data[$i]->transport_amount,
		                'amt' => $amt,
		                'tax' => $tax,
		                'total' => $data[$i]->it_amount
		            ));
		        }
		        $query = $this->db->query("SELECT * FROM i_taxes WHERE itx_owner='$oid'");
		        $result['taxes'] = $query->result();
		        
		        print_r(json_encode($result));
			} else if($type == "txn_tax_purchase") {
		        $query = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id WHERE (it_type='Purchase' OR it_type='Debit Note' AND it_type_sec='Purchase') AND it_owner='$oid' AND it_date BETWEEN '$from' AND '$to' GROUP BY it_date");
		        $data = $query->result();
		        
		        $result['txntax']=[];
		        for($i=0; $i<count($data);$i++) {
		            $tid = $data[$i]->it_id;
		            $que = $this->db->query("SELECT * FROM i_txns_details WHERE itp_owner='$oid' AND itd_t_id='$tid'");
		            $res = $que->result();
		            $amt=0;$tamt=0;$tax=[];
	                for($j=0;$j<count($res);$j++){
	                    $amt+=$res[$j]->itp_value;
	                    $tamt+=$res[$j]->itp_amount;
	                }
	                $que1=$this->db->query("SELECT a.itpt_id AS id, SUM(a.itpt_t_amount) AS amt, b.itx_id AS taxid, b.itx_name AS tax FROM i_txn_product_taxes AS a LEFT JOIN i_taxes AS b ON a.itpt_tx_id=b.itx_id WHERE a.itpt_owner='$oid' AND a.itpt_txn_id='$tid' GROUP BY a.itpt_tx_id");
	                $res1=$que1->result();
	                for($j=0;$j<count($res1);$j++){
	                    array_push($tax, array('name' => $res1[$j]->tax, 'amt' => $res1[$j]->amt, 'taxid' => $res1[$j]->taxid));
	                }
	                
	                array_push($result['txntax'], array(
		                'id' => $data[$i]->it_txn_no,
		                'date' => $data[$i]->it_date,
		                'name' => $data[$i]->ic_name,
		                'gstno' => $data[$i]->ic_gst_number,
		                'amt' => $amt,
		                'tax' => $tax,
		                'total' => $data[$i]->it_amount
		            ));
		        }
		        $query = $this->db->query("SELECT * FROM i_taxes WHERE itx_owner='$oid'");
		        $result['taxes'] = $query->result();
		        
		        print_r(json_encode($result));
			} else if($type == "txn_income_delivery") {
	            $this->db->select('IFNULL(a.it_amount,0) AS it_amount, a.it_type, a.it_txn_no, a.it_date, a.it_status, a.it_type_sec, b.ic_name, a.it_mode, a.it_note, c.idu_name, d.ittd_expense, d.ittd_transporter, d.ittd_date, IFNULL(e.ie_amount,0) AS ie_amount');
	            $this->db->from('i_txns AS a');
	            $this->db->join('i_contacts AS b', 'a.it_c_id=b.ic_id','left');
	            $this->db->join('i_d_users AS c', 'a.it_sub_dealer=c.idu_id','left');
	            $this->db->join('i_txn_transport_details AS d', 'a.it_id=d.ittd_txn_id','left');
	            $this->db->join('i_expenses AS e', 'd.ittd_expense=e.ie_id','left');
	            $this->db->where('a.it_owner', $oid);
	            $this->db->where("a.it_date BETWEEN '$from' AND '$to'");
	            $this->db->where('(a.it_type="Delivery" OR a.it_type="Credit Note" AND a.it_type_sec="Delivery")');
	            $this->db->where_in('a.it_status',array('shipping','delivered','invoiced','paid','cancelled','hold','done'));
	            $this->db->like('b.ic_name',$keyword);
	            $this->db->order_by('a.it_date','a.it_id');
	            $query = $this->db->get();
			    $result['income']['sale'] = $query->result();
			    
			    $this->db->select('IFNULL(a.it_amount,0) AS it_amount, a.it_type, a.it_txn_no, a.it_date, a.it_status, a.it_type_sec, b.ic_name, a.it_mode, a.it_note, c.idu_name, d.ittd_expense, d.ittd_transporter, d.ittd_date, IFNULL(e.ie_amount,0) AS ie_amount');
	            $this->db->from('i_txns AS a');
	            $this->db->join('i_contacts AS b', 'a.it_c_id=b.ic_id','left');
	            $this->db->join('i_d_users AS c', 'a.it_sub_dealer=c.idu_id','left');
	            $this->db->join('i_txn_transport_details AS d', 'a.it_id=d.ittd_txn_id','left');
	            $this->db->join('i_expenses AS e', 'd.ittd_expense=e.ie_id','left');
	            $this->db->where('a.it_owner', $oid);
	            $this->db->where("a.it_date BETWEEN '$from' AND '$to'");
	            $this->db->where_in('a.it_type',array('Purchase','Debit Note'));
	            $this->db->where_in('a.it_status',array('shipping','delivered','invoiced','paid','cancelled','hold','done'));
	            $this->db->like('b.ic_name',$keyword);
	            $this->db->order_by('a.it_date','a.it_id');
	            $query = $this->db->get();
			    $result['income']['purchase'] = $query->result();
			    
			    $this->db->select('a.ie_description, a.ie_date, IFNULL(a.ie_amount, 0) AS ie_amount');
	            $this->db->from('i_expenses AS a');
	            $this->db->where('a.ie_owner', $oid);
	            $this->db->where("a.ie_date BETWEEN '$from' AND '$to'");
	            $this->db->where("a.ie_order_id","0");
	            $this->db->order_by('a.ie_date','a.ie_id');
	            $query = $this->db->get();
			    $result['income']['expenses'] = $query->result();
			    print_r(json_encode($result));
			
			} else if($type == "txn_income") {
	            $this->db->select('IFNULL(a.it_amount,0) AS it_amount, a.it_type, a.it_txn_no, a.it_date, a.it_status, a.it_type_sec, b.ic_name, a.it_mode, a.it_note, c.idu_name, d.ittd_expense, d.ittd_transporter, d.ittd_date, IFNULL(e.ie_amount,0) AS ie_amount');
	            $this->db->from('i_txns AS a');
	            $this->db->join('i_contacts AS b', 'a.it_c_id=b.ic_id','left');
	            $this->db->join('i_d_users AS c', 'a.it_sub_dealer=c.idu_id','left');
	            $this->db->join('i_txn_transport_details AS d', 'a.it_id=d.ittd_txn_id','left');
	            $this->db->join('i_expenses AS e', 'd.ittd_expense=e.ie_id','left');
	            $this->db->where('a.it_owner', $oid);
	            $this->db->where("a.it_date BETWEEN '$from' AND '$to'");
	            $this->db->where('(a.it_type="Invoice" OR a.it_type="Credit Note" AND a.it_type_sec="Invoice")');
	            $this->db->where_in('a.it_status',array('shipping','delivered','invoiced','paid','cancelled','hold','done'));
	            $this->db->like('b.ic_name',$keyword);
	            $this->db->order_by('a.it_date','a.it_id');
	            $query = $this->db->get();
			    $result['income']['sale'] = $query->result();
			    
			    $this->db->select('IFNULL(a.it_amount,0) AS it_amount, a.it_type, a.it_txn_no, a.it_date, a.it_status, a.it_type_sec, b.ic_name, a.it_mode, a.it_note, c.idu_name, d.ittd_expense, d.ittd_transporter, d.ittd_date, IFNULL(e.ie_amount,0) AS ie_amount');
	            $this->db->from('i_txns AS a');
	            $this->db->join('i_contacts AS b', 'a.it_c_id=b.ic_id','left');
	            $this->db->join('i_d_users AS c', 'a.it_sub_dealer=c.idu_id','left');
	            $this->db->join('i_txn_transport_details AS d', 'a.it_id=d.ittd_txn_id','left');
	            $this->db->join('i_expenses AS e', 'd.ittd_expense=e.ie_id','left');
	            $this->db->where('a.it_owner', $oid);
	            $this->db->where("a.it_date BETWEEN '$from' AND '$to'");
	            $this->db->where_in('a.it_type',array('Purchase','Debit Note'));
	            $this->db->where_in('a.it_status',array('shipping','delivered','invoiced','paid','cancelled','hold','done'));
	            $this->db->like('b.ic_name',$keyword);
	            $this->db->order_by('a.it_date','a.it_id');
	            $query = $this->db->get();
			    $result['income']['purchase'] = $query->result();
			    
			    $this->db->select('a.ie_description, a.ie_date, IFNULL(a.ie_amount, 0) AS ie_amount');
	            $this->db->from('i_expenses AS a');
	            $this->db->where('a.ie_owner', $oid);
	            $this->db->where("a.ie_date BETWEEN '$from' AND '$to'");
	            $this->db->where("a.ie_order_id","0");
	            $this->db->order_by('a.ie_date','a.ie_id');
	            $query = $this->db->get();
			    $result['income']['expenses'] = $query->result();
			    print_r(json_encode($result));
			}
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}

}