<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends CI_Controller {

	public function __construct()	{
		parent:: __construct();
		$this->load->database();
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->library('email');
		$this->load->library('excel_reader');
		$this->load->dbforge();
	}
########## LOGIN, VERIFY AND LOGOUT ################
	public function index() {
		redirect(base_url().'distributors/Account/account');
	}

	public function login() {
		$sess_data = $this->session->userdata();
		if(!isset($sess_data['user_details'][0])) {	
			
			$ert['title'] = "Login";
			$ert['search'] = "false";
			$ert['oid'] = 1;
			$ert['type'] = "distributors";
			// $this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/account/login', $ert);
		} else {
			redirect(base_url().'distributors/Home');
		}
	}

	public function verify() {
		$uname = $this->input->post('uname');
		$upass = $this->input->post('upass');

		$query = $this->db->query("SELECT * FROM i_users AS a LEFT JOIN i_contacts AS b ON a.iu_ref=b.ic_id LEFT JOIN i_u_access AS c ON a.iu_id=c.iua_u_id WHERE iu_username = '$uname'");
		$result = $query->result();

		if (count($result) > 0) {
		    if ($result[0]->iu_type == "distributor") {
		        $this->send_login_activity($result[0]->iu_username, $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']);
		        
		        if ($result[0]->iu_status == 'password_u') {
    				$id = $result[0]->iu_id;
    
    				echo $id;
    			} else if ($result[0]->iu_status == 'blocked') {
    				echo 'blocked';
    			} elseif ($result[0]->iu_password==$upass) {
    				$oid = $result[0]->iu_owner;
    				
    				if($result[0]->iu_logout == 'true') {
    				    $this->db->where(array('iu_id' => $result[0]->iu_id, 'iu_owner' => $oid));
    				    $this->db->update('i_users', array('iu_logout' => 'false'));
    				    
    				    $query = $this->db->query("SELECT * FROM i_users AS a LEFT JOIN i_contacts AS b ON a.iu_ref=b.ic_id LEFT JOIN i_u_access AS c ON a.iu_id=c.iua_u_id WHERE iu_username = '$uname'");
		                $result = $query->result();
    				}
    				
    				$que = $this->db->query("SELECT * FROM i_financial_year WHERE ify_owner='$oid' AND ify_active='true'");
    				$data = array('status' => "user" , "user_details" => $result, "fy" => $que->result());
    				$this->session->set_userdata($data);
    				echo "true";
    			} else {
    				echo "false";
    			}   
		    } else {
		        echo "false";
		    }
		} else {
			echo "false";
		}
	}

	public function logout() {
		$this->session->unset_userdata('user_details');
		$this->session->unset_userdata('status');
		$this->session->unset_userdata('fy');
		redirect(base_url().'distributors/Account/login');
	}

	public function reset_password($oid, $uid) {
		$data['oid'] = $oid;
		$data['uid'] = $uid;
		$data['type'] = 'distributors';
		$this->load->view('distributors/account/reset', $data);
	}

	public function reset_update($oid, $uid) {
		$data = array('iu_password' => $this->input->post('upass'), 'iu_status' => 'active');
		$this->db->where(array('iu_id' => $uid, 'iu_owner' => $oid));
		$this->db->update('i_users', $data);
		echo "true";

	}

########## ACCOUNT DETAILS ################
	public function account() {
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
			
			$rid = $sess_data['user_details'][0]->iu_ref;
			$data['rid'] = $rid;
			
			$oid = $sess_data['user_details'][0]->iu_owner;
			$data['oid'] = $oid;

			$data['section'] = $sess_data['user_details'][0]->ic_section;
			$data['logo'] = $sess_data['user_details'][0]->iu_logo;
			$data['logo_sec'] = $sess_data['user_details'][0]->iu_logo_sec;

			$query = $this->db->query("SELECT * FROM i_contacts WHERE ic_id='$rid' AND ic_owner='$oid'");
			$result = $query->result();
			$data['user_info'] = $result;

			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Account";
			$ert['search'] = "false";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/account/account', $data);
			
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}

	public function logo_upload($rid) {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$upload_dir = $this->config->item('document_rt')."assets/uploads/".$oid."/logo/";
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

			$data = array('iu_logo' => $img_path);
			$this->db->where(array('iu_owner'=>$oid, 'iu_ref'=>$rid, 'iu_id' => $sess_data['user_details'][0]->iu_id ));
			$this->db->update('i_users', $data);

			$sess_data['user_details'][0]->iu_logo = $img_path;

			$this->session->set_userdata($sess_data);

			echo $rid;
		}
	}
	
	public function logo_upload_secondary($rid) {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$upload_dir = $this->config->item('document_rt')."assets/uploads/".$oid."/logo/";
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

			$data = array('iu_logo_sec' => $img_path);
			$this->db->where(array('iu_owner'=>$oid, 'iu_ref'=>$rid, 'iu_id' => $sess_data['user_details'][0]->iu_id ));
			$this->db->update('i_users', $data);

			$sess_data['user_details'][0]->iu_logo = $img_path;

			$this->session->set_userdata($sess_data);

			echo $rid;
		}
	}
	
	

	public function update_details($rid) {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$uid = $sess_data['user_details'][0]->iu_id;
			$oid = $sess_data['user_details'][0]->iu_owner;
			$dt = date('Y-m-d H:m:s');
			
			$data = array(
				'ic_name' => $this->input->post('name'),
				'ic_company' => $this->input->post('company'),
				'ic_email' => $this->input->post('email'),
				'ic_phone' => $this->input->post('phone'),
				'ic_address' => $this->input->post('address'),
				'ic_gst_number' => $this->input->post('gst'),
				'ic_bank_name' => $this->input->post('bank'),
				'ic_bank_branch' => $this->input->post('branch'),
				'ic_bank_accno' => $this->input->post('acc'),
				'ic_bank_ifsc' => $this->input->post('ifsc'),

				 );
			$this->db->where(array('ic_id' => $rid, 'ic_owner' => $oid));
			$this->db->update('i_contacts', $data);

			$data = array(
				'iu_username' => $this->input->post('email'),
			);
			$this->db->where(array('iu_id' => $uid, 'iu_ref' => $rid, 'iu_owner' => $oid));
			$this->db->update('i_users', $data);

			echo "true";
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}

########## USERS AND ACCESS ################

	public function users() {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$uid = $sess_data['user_details'][0]->iu_ref;
			$data['uid'] = $uid;
			
			$oid = $sess_data['user_details'][0]->iu_owner;
			$data['oid'] = $oid;
            
            $q=$this->db->query("SELECT * FROM i_users WHERE iu_owner='$oid' AND iu_id='$uid'");
            if(count($q->result()) > 0) {
                if($q->result()[0]->iu_status=="blocked") {
    			    $this->session->unset_userdata('user_details');
            		$this->session->unset_userdata('status');
            		$this->session->unset_userdata('fy');
            		redirect(base_url().'distributors/Account/login');
    			}
            }
            
			$query = $this->db->query("SELECT * FROM i_users AS a LEFT JOIN i_u_access AS b ON a.iu_id=b.iua_u_id LEFT JOIN i_contacts AS c ON a.iu_ref=c.ic_id WHERE a.iu_type='distributor' AND a.iu_owner='$oid'");
			$result = $query->result();
			$data['users'] = $result;

			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Users and Access";
			$ert['search'] = "false";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/account/users', $data);
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}

	public function search_user() {

	}

	public function add_user() {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$uid = $sess_data['user_details'][0]->iu_ref;
			$data['uid'] = $uid;
			
			$oid = $sess_data['user_details'][0]->iu_owner;
			$data['oid'] = $oid;

			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Users and Access";
			$ert['search'] = "false";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/account/user_add', $data);
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}

	public function save_user() {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$uid = $sess_data['user_details'][0]->iu_ref;
			$oid = $sess_data['user_details'][0]->iu_owner;
			$dt = date('Y-m-d H:m:s');
			
			$email = $this->input->post('email');
            $query = $this->db->query("SELECT * FROM i_contacts WHERE ic_email='$email'");
            $result = $query->result();
            
            if(count($result) > 0) {
                echo "exists";
            } else {
                
    			$data = array(
    				'ic_name' => $this->input->post('name'),
    				'ic_company' => $sess_data['user_details'][0]->ic_company,
    				'ic_email' => $this->input->post('email'),
    				'ic_phone' => $this->input->post('phone'),
    				'ic_address' => $this->input->post('address'),
    				'ic_owner' => $oid,
    				'ic_section' => 'Distributor User',
    				'ic_gst_number' => $sess_data['user_details'][0]->ic_gst_number,
    				'ic_bank_name' => $sess_data['user_details'][0]->ic_bank_name,
    				'ic_bank_branch' => $sess_data['user_details'][0]->ic_bank_branch,
    				'ic_bank_accno' => $sess_data['user_details'][0]->ic_bank_accno,
    				'ic_bank_ifsc' => $sess_data['user_details'][0]->ic_bank_ifsc
    				 );
    			$this->db->insert('i_contacts', $data);
    			$cid = $this->db->insert_id();
    
    			$data = array(
    				'iu_username' => $this->input->post('email'),
    				'iu_type' => 'distributor',
    				'iu_ref' => $cid,
    				'iu_owner' => $oid,
    				'iu_status' => 'password_u',
    				'iu_logo' => $sess_data['user_details'][0]->iu_logo,
    				'iu_logo_sec' => $sess_data['user_details'][0]->iu_sec_logo,
    				'iu_subscription_start' => $sess_data['user_details'][0]->iu_subscription_start,
    				'iu_subscription_end' => $sess_data['user_details'][0]->iu_subscription_end,
    				'iu_logout' => 'false',
    				'iu_created' => $dt,
    				'iu_created_by' => $oid
    			);
    			$this->db->insert('i_users', $data);
    			$user_uid = $this->db->insert_id();
    
    			$data = array(
    				'iua_u_id' => $user_uid,
    				'iua_u_products' => $this->input->post('per_product'),
    				'iua_u_pricing' => $this->input->post('per_pricing'),
    				'iua_u_dealers' => $this->input->post('per_dealers'),
    				'iua_u_vendors' => $this->input->post('per_vendors'),
    				'iua_u_orders' => $this->input->post('per_orders'),
    				'iua_u_delivery' => $this->input->post('per_delivery'),
    				'iua_u_inventory' => $this->input->post('per_inventory'),
    				'iua_u_purchase' => $this->input->post('per_purchase'),
    				'iua_u_expenses' => $this->input->post('per_expense'),
    				'iua_u_invoice' => $this->input->post('per_invoice'),
    				'iua_u_credit_note' => $this->input->post('per_credit'),
    				'iua_u_payments' => $this->input->post('per_payments'),
    				'iua_u_tax' => $this->input->post('per_tax'),
    				'iua_u_users' => $this->input->post('per_users'),
    				'iua_u_godown' => $this->input->post('per_godown'),
    				'iua_u_ledgers' => $this->input->post('per_ledgers'),
    				'iua_u_settings' => $this->input->post('per_settings'),
    				'iua_u_analyze' => $this->input->post('per_analyze'),
    				'iua_bank_accounts' => $this->input->post('per_bank'),
    				'iua_owner' => $oid,
    				'iua_created' => $dt,
    				'iua_created_by' => $uid				
    				 );
    			$this->db->insert('i_u_access', $data);
    			echo $user_uid;
            }
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}

    public function image_upload($uid) {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;	
			
			$upload_dir = $this->config->item('document_rt')."assets/uploads/".$oid."/users/".$uid."/";
			if(!file_exists($upload_dir)) {
				mkdir($upload_dir, 0777, true);
			}
			$img_path = "";
			
			if (is_dir($upload_dir) && is_writable($upload_dir)) {
			    $this->db->where(array('iui_u_id' => $uid, 'iui_owner' => $oid));
			    $this->db->delete('i_u_images');
			    
			    for($ij=0; $ij < count($_FILES); $ij++) {
			        $sourcePath = $_FILES[$ij]['tmp_name']; // Storing source path of the file in a variable
    				$targetPath = $upload_dir.$_FILES[$ij]['name']; // Target path where file is to be stored
    				// $img_path = $targetPath;
    				move_uploaded_file($sourcePath,$targetPath) ; // Moving Uploaded file		
    				$img_path = $_FILES[$ij]['name'];

            		$picdata = array(
            		    'iui_u_id' => $uid,
            		    'iui_img' => $img_path,
            		    'iui_owner' => $oid
            		    );
            		$this->db->insert('i_u_images', $picdata);
			    }
			}
			echo $pid;
		}
	}

	
	public function edit_user($id) {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
		    $uid = $sess_data['user_details'][0]->iu_ref;
			$data['uid'] = $uid;
			
			$oid = $sess_data['user_details'][0]->iu_owner;
			$data['oid'] = $oid;

			$query = $this->db->query("SELECT * FROM i_users AS a LEFT JOIN i_u_access AS b ON a.iu_id=b.iua_u_id LEFT JOIN i_contacts AS c ON a.iu_ref=c.ic_id WHERE a.iu_type='distributor' AND a.iu_owner='$oid' AND a.iu_id='$id'");
			$result = $query->result();
			$data['user_info'] = $result;
			
			$query = $this->db->query("SELECT * FROM i_u_images WHERE iui_u_id='$id' AND iui_owner='$oid'");
			$result = $query->result();
			$data['user_img'] = $result;
			
			$data['id'] = $id;
			
			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Users and Access";
			$ert['search'] = "false";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/account/user_add', $data);
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}

	public function update_user($id) {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$uid = $sess_data['user_details'][0]->iu_ref;
			$oid = $sess_data['user_details'][0]->iu_owner;
			$dt = date('Y-m-d H:m:s');
			
			$query = $this->db->query("SELECT * FROM i_users WHERE iu_id='$id' AND iu_owner='$oid'");
			$result = $query->result();
			
			$cid = $result[0]->iu_ref;
			
			$data = array(
				'ic_name' => $this->input->post('name'),
				'ic_company' => $sess_data['user_details'][0]->ic_company,
				'ic_email' => $this->input->post('email'),
				'ic_phone' => $this->input->post('phone'),
				'ic_address' => $this->input->post('address'),
				'ic_owner' => $oid,
				'ic_section' => 'Distributor User',
				'ic_gst_number' => $sess_data['user_details'][0]->ic_gst_number,
				'ic_bank_name' => $sess_data['user_details'][0]->ic_bank_name,
				'ic_bank_branch' => $sess_data['user_details'][0]->ic_bank_branch,
				'ic_bank_accno' => $sess_data['user_details'][0]->ic_bank_accno,
				'ic_bank_ifsc' => $sess_data['user_details'][0]->ic_bank_ifsc
				 );
            $this->db->where(array('ic_id' => $cid, 'ic_owner' => $oid ));				 
			$this->db->update('i_contacts', $data);

			$data = array(
				'iu_username' => $this->input->post('email'),
				'iu_type' => 'distributor',
				'iu_ref' => $cid,
				'iu_owner' => $oid,
				'iu_status' => $result[0]->iu_status,
				'iu_logo' => $sess_data['user_details'][0]->iu_logo,
				'iu_logo_sec' => $sess_data['user_details'][0]->iu_logo_sec,
				'iu_subscription_start' => $sess_data['user_details'][0]->iu_subscription_start,
				'iu_subscription_end' => $sess_data['user_details'][0]->iu_subscription_end,
				'iu_created' => $dt,
				'iu_created_by' => $oid
			);
			
			$this->db->where(array('iu_id' => $id, 'iu_owner' => $oid));
			$this->db->update('i_users', $data);
			
			$data = array(
				'iua_u_products' => $this->input->post('per_product'),
				'iua_u_pricing' => $this->input->post('per_pricing'),
				'iua_u_dealers' => $this->input->post('per_dealers'),
				'iua_u_vendors' => $this->input->post('per_vendors'),
				'iua_u_orders' => $this->input->post('per_orders'),
				'iua_u_delivery' => $this->input->post('per_delivery'),
				'iua_u_inventory' => $this->input->post('per_inventory'),
				'iua_u_godown' => $this->input->post('per_godown'),
				'iua_u_purchase' => $this->input->post('per_purchase'),
				'iua_u_purchase_pricing' => $this->input->post('per_purchase_pricing'),
				'iua_u_invoice' => $this->input->post('per_invoice'),
				'iua_u_credit_note' => $this->input->post('per_credit'),
				'iua_u_ledgers' => $this->input->post('per_ledgers'),
				'iua_u_expenses' => $this->input->post('per_expense'),
				'iua_u_payments' => $this->input->post('per_payments'),
				'iua_u_tax' => $this->input->post('per_tax'),
				'iua_u_users' => $this->input->post('per_users'),
				'iua_u_settings' => $this->input->post('per_settings'),
				'iua_u_analyze' => $this->input->post('per_analyze'),
    			'iua_bank_accounts' => $this->input->post('per_bank'),
    			'iua_u_inventory_reset' => $this->input->post('per_inventory_reset'),
    		);
			$this->db->where(array('iua_u_id' => $id, 'iua_owner' => $oid));
			$this->db->update('i_u_access', $data);
			echo $id;
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}
	
	public function delete_user($id) {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$dt = date('Y-m-d H:m:s');
			
			$query = $this->db->query("SELECT * FROM i_users WHERE iu_id='$id' AND iu_owner='$oid'");
			$result = $query->result();
			
			$cid = $result[0]->iu_ref;
			
			$this->db->where(array('ic_id' => $cid, 'ic_owner' => $oid));
			$this->db->delete('i_contacts');
			
			$this->db->where(array('iu_id'=>$id, 'iu_owner' => $oid ));
			$this->db->delete('i_users');
			
			$this->db->where(array('iua_u_id' => $id, 'iua_owner' => $oid));
			$this->db->delete('i_u_access');
			
			redirect(base_url().'distributors/Account/users');
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}

    public function block_user($id, $state) {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$dt = date('Y-m-d H:m:s');
			
			$query = $this->db->query("SELECT * FROM i_users WHERE iu_id='$id' AND iu_owner='$oid'");
			$result = $query->result();
			
			$cid = $result[0]->iu_ref;
			
			if($state == 0) {
			    $this->db->where(array('iu_id'=>$id, 'iu_owner' => $oid ));
    			$this->db->update('i_users', array('iu_status' => 'blocked'));
			} else {
			    $this->db->where(array('iu_id'=>$id, 'iu_owner' => $oid ));
    			$this->db->update('i_users', array('iu_status' => 'active'));
			}
			
			redirect(base_url().'distributors/Account/users');
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}
	
	public function logout_user($id) {
	   $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$dt = date('Y-m-d H:m:s');
			
			$query = $this->db->query("SELECT * FROM i_users WHERE iu_id='$id' AND iu_owner='$oid'");
			$result = $query->result();
			
			$cid = $result[0]->iu_ref;
		
		    $this->db->where(array('iu_id'=>$id, 'iu_owner' => $oid ));
			$this->db->update('i_users', array('iu_logout' => 'true'));
			
			redirect(base_url().'distributors/Account/users');
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}

    public function settings() {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			
			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Settings";
			$ert['search'] = "false";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/account/settings');
		} else {
			redirect(base_url().'distributors/Account/login');
		}
    }
    

    public function tre() {
        $query = $this->db->select('*')->from('i_txns')->where(array('it_type' => 'Sale', 'it_date >' => '2018-04-04', 'it_date<' => '2018-04-20'));
        $q = $this->db->get();
        print_r($q->result());
    }
    
    public function delete_txns() {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
		    
		    $oid = $sess_data['user_details'][0]->iu_owner;
		    $from = $this->input->post('from');
            $to = $this->input->post('to');
            
		    if($this->input->post('ps') == $sess_data['user_details'][0]->iu_password) {
		        if($this->input->post('or') == "true") {
		            
		            $query = $this->db->query("SELECT * FROM i_txns WHERE it_owner='$oid' AND it_type='Orders' AND it_date BETWEEN '$from' AND '$to'");
		            $result = $query->result();
		            
		            for($i=0; $i< count($result);$i++) {
		                $tid = $result[$i]->it_id;
		                $this->db->where(array('itp_owner' => $oid, 'itd_t_id' => $tid));
		                $this->db->delete('i_txns_details');
		                
		                $this->db->where(array('itpt_owner' => $oid, 'itpt_txn_id' => $tid));
    		            $this->db->delete('i_txn_product_taxes');
    		            
    		            $this->db->where(array('ittd_owner' => $oid, 'ittd_txn_id' => $tid));
    		            $this->db->delete('i_txn_transport_details');
    		            
    		            $this->db->where(array('it_owner' => $oid, 'it_id' => $tid));
		                $this->db->delete('i_txns');
		                
		                $this->db->where(array('ie_owner' => $oid, 'ie_order_id' => $tid));
		                $this->db->delete('i_expenses');
		            }
		            
		            $this->db->where("itn_date BETWEEN '$from' AND '$to'");
		            $this->db->where(array('itn_owner' => $oid));
		            $this->db->where(array('itn_type' => 'Orders'));
		            $this->db->delete('i_txn_nums');
		            
		            $this->db->where(array('ith_owner' => $oid));
		            $this->db->delete('i_txns_history');
		        }
		        
		        if($this->input->post('de') == "true") {
		            
		            $query = $this->db->query("SELECT * FROM i_txns WHERE it_owner='$oid' AND it_type='Delivery' AND it_date BETWEEN '$from' AND '$to'");
		            $result = $query->result();
		            
		            for($i=0; $i< count($result);$i++) {
		                $tid = $result[$i]->it_id;
		                $this->db->where(array('itp_owner' => $oid, 'itd_t_id' => $tid));
		                $this->db->delete('i_txns_details');
		                
		                $this->db->where(array('itpt_owner' => $oid, 'itpt_txn_id' => $tid));
    		            $this->db->delete('i_txn_product_taxes');
    		            
    		            $this->db->where(array('ittd_owner' => $oid, 'ittd_txn_id' => $tid));
    		            $this->db->delete('i_txn_transport_details');
    		            
    		            $this->db->where(array('it_owner' => $oid, 'it_id' => $tid));
		                $this->db->delete('i_txns');
		                
		                $this->db->where(array('ie_owner' => $oid, 'ie_order_id' => $tid));
		                $this->db->delete('i_expenses');
		            }
		            
		            $this->db->where("itn_date BETWEEN '$from' AND '$to'");
		            $this->db->where(array('itn_owner' => $oid));
		            $this->db->where(array('itn_type' => 'Delivery'));
		            $this->db->delete('i_txn_nums');
		            
		            $this->db->where(array('ith_owner' => $oid));
		            $this->db->delete('i_txns_history');
		        }
		        
		        if($this->input->post('pu') == "true") {
		            
		            $query = $this->db->query("SELECT * FROM i_txns WHERE it_owner='$oid' AND it_type='Purchase' AND it_date BETWEEN '$from' AND '$to'");
		            $result = $query->result();
		            
		            for($i=0; $i< count($result);$i++) {
		                $tid = $result[$i]->it_id;
		                $this->db->where(array('itp_owner' => $oid, 'itd_t_id' => $tid));
		                $this->db->delete('i_txns_details');
		                
		                $this->db->where(array('itpt_owner' => $oid, 'itpt_txn_id' => $tid));
    		            $this->db->delete('i_txn_product_taxes');
    		            
    		            $this->db->where(array('ittd_owner' => $oid, 'ittd_txn_id' => $tid));
    		            $this->db->delete('i_txn_transport_details');
    		            
    		            $this->db->where(array('it_owner' => $oid, 'it_id' => $tid));
		                $this->db->delete('i_txns');
		                
		                $this->db->where(array('ie_owner' => $oid, 'ie_order_id' => $tid));
		                $this->db->delete('i_expenses');
		            }
		            
		            $this->db->where("itn_date BETWEEN '$from' AND '$to'");
		            $this->db->where(array('itn_owner' => $oid));
		            $this->db->where(array('itn_type' => 'Purchase'));
		            $this->db->delete('i_txn_nums');
		            
		            $this->db->where(array('ith_owner' => $oid));
		            $this->db->delete('i_txns_history');
		        }
		        
		        if($this->input->post('in') == "true") {
		            
		            $query = $this->db->query("SELECT * FROM i_txns WHERE it_owner='$oid' AND it_type='Invoice' AND it_date BETWEEN '$from' AND '$to'");
		            $result = $query->result();
		            
		            for($i=0; $i< count($result);$i++) {
		                $tid = $result[$i]->it_id;
		                $this->db->where(array('itp_owner' => $oid, 'itd_t_id' => $tid));
		                $this->db->delete('i_txns_details');
		                
		                $this->db->where(array('itpt_owner' => $oid, 'itpt_txn_id' => $tid));
    		            $this->db->delete('i_txn_product_taxes');
    		            
    		            $this->db->where(array('ittd_owner' => $oid, 'ittd_txn_id' => $tid));
    		            $this->db->delete('i_txn_transport_details');
    		            
    		            $this->db->where(array('it_owner' => $oid, 'it_id' => $tid));
		                $this->db->delete('i_txns');
		                
		                $this->db->where(array('ie_owner' => $oid, 'ie_order_id' => $tid));
		                $this->db->delete('i_expenses');
		            }
		            
		            $this->db->where("itn_date BETWEEN '$from' AND '$to'");
		            $this->db->where(array('itn_owner' => $oid));
		            $this->db->where(array('itn_type' => 'Invoice'));
		            $this->db->delete('i_txn_nums');
		            
		            $this->db->where(array('ith_owner' => $oid));
		            $this->db->delete('i_txns_history');
		        }
		        
		        if($this->input->post('crd') == "true") {
		            
		            $query = $this->db->query("SELECT * FROM i_txns WHERE it_owner='$oid' AND it_type='Credit Note' AND it_type_sec='Delivery' AND it_date BETWEEN '$from' AND '$to'");
		            $result = $query->result();
		            
		            for($i=0; $i< count($result);$i++) {
		                $tid = $result[$i]->it_id;
		                $this->db->where(array('itp_owner' => $oid, 'itd_t_id' => $tid));
		                $this->db->delete('i_txns_details');
		                
		                $this->db->where(array('itpt_owner' => $oid, 'itpt_txn_id' => $tid));
    		            $this->db->delete('i_txn_product_taxes');
    		            
    		            $this->db->where(array('ittd_owner' => $oid, 'ittd_txn_id' => $tid));
    		            $this->db->delete('i_txn_transport_details');
    		            
    		            $this->db->where(array('it_owner' => $oid, 'it_id' => $tid));
		                $this->db->delete('i_txns');
		                
		                $this->db->where(array('ie_owner' => $oid, 'ie_order_id' => $tid));
		                $this->db->delete('i_expenses');
		            }
		            
		            $this->db->where("itn_date BETWEEN '$from' AND '$to'");
		            $this->db->where(array('itn_owner' => $oid));
		            $this->db->where(array('itn_type' => 'Credit Note'));
		            $this->db->delete('i_txn_nums');
		            
		            $this->db->where(array('ith_owner' => $oid));
		            $this->db->delete('i_txns_history');
		        }
		        
		        if($this->input->post('cr') == "true") {
		            
		            $query = $this->db->query("SELECT * FROM i_txns WHERE it_owner='$oid' AND it_type='Credit Note' AND it_type_sec='Invoice' AND it_date BETWEEN '$from' AND '$to'");
		            $result = $query->result();
		            
		            for($i=0; $i< count($result);$i++) {
		                $tid = $result[$i]->it_id;
		                $this->db->where(array('itp_owner' => $oid, 'itd_t_id' => $tid));
		                $this->db->delete('i_txns_details');
		                
		                $this->db->where(array('itpt_owner' => $oid, 'itpt_txn_id' => $tid));
    		            $this->db->delete('i_txn_product_taxes');
    		            
    		            $this->db->where(array('ittd_owner' => $oid, 'ittd_txn_id' => $tid));
    		            $this->db->delete('i_txn_transport_details');
    		            
    		            $this->db->where(array('it_owner' => $oid, 'it_id' => $tid));
		                $this->db->delete('i_txns');
		                
		                $this->db->where(array('ie_owner' => $oid, 'ie_order_id' => $tid));
		                $this->db->delete('i_expenses');
		            }
		            
		            $this->db->where("itn_date BETWEEN '$from' AND '$to'");
		            $this->db->where(array('itn_owner' => $oid));
		            $this->db->where(array('itn_type' => 'Credit Note'));
		            $this->db->delete('i_txn_nums');
		            
		            $this->db->where(array('ith_owner' => $oid));
		            $this->db->delete('i_txns_history');
		        }
		        
		        if($this->input->post('drd') == "true") {
		            
		            $query = $this->db->query("SELECT * FROM i_txns WHERE it_owner='$oid' AND it_type='Debit Note' AND it_type_sec='Delivery' AND it_date BETWEEN '$from' AND '$to'");
		            $result = $query->result();
		            
		            for($i=0; $i< count($result);$i++) {
		                $tid = $result[$i]->it_id;
		                $this->db->where(array('itp_owner' => $oid, 'itd_t_id' => $tid));
		                $this->db->delete('i_txns_details');
		                
		                $this->db->where(array('itpt_owner' => $oid, 'itpt_txn_id' => $tid));
    		            $this->db->delete('i_txn_product_taxes');
    		            
    		            $this->db->where(array('ittd_owner' => $oid, 'ittd_txn_id' => $tid));
    		            $this->db->delete('i_txn_transport_details');
    		            
    		            $this->db->where(array('it_owner' => $oid, 'it_id' => $tid));
		                $this->db->delete('i_txns');
		                
		                $this->db->where(array('ie_owner' => $oid, 'ie_order_id' => $tid));
		                $this->db->delete('i_expenses');
		            }
		            
		            $this->db->where("itn_date BETWEEN '$from' AND '$to'");
		            $this->db->where(array('itn_owner' => $oid));
		            $this->db->where(array('itn_type' => 'Debit Note'));
		            $this->db->delete('i_txn_nums');
		            
		            $this->db->where(array('ith_owner' => $oid));
		            $this->db->delete('i_txns_history');
		        }
		        
		        if($this->input->post('dr') == "true") {
		            
		            $query = $this->db->query("SELECT * FROM i_txns WHERE it_owner='$oid' AND it_type='Debit Note' AND it_type_sec='Purchase' AND it_date BETWEEN '$from' AND '$to'");
		            $result = $query->result();
		            
		            for($i=0; $i< count($result);$i++) {
		                $tid = $result[$i]->it_id;
		                $this->db->where(array('itp_owner' => $oid, 'itd_t_id' => $tid));
		                $this->db->delete('i_txns_details');
		                
		                $this->db->where(array('itpt_owner' => $oid, 'itpt_txn_id' => $tid));
    		            $this->db->delete('i_txn_product_taxes');
    		            
    		            $this->db->where(array('ittd_owner' => $oid, 'ittd_txn_id' => $tid));
    		            $this->db->delete('i_txn_transport_details');
    		            
    		            $this->db->where(array('it_owner' => $oid, 'it_id' => $tid));
		                $this->db->delete('i_txns');
		                
		                $this->db->where(array('ie_owner' => $oid, 'ie_order_id' => $tid));
		                $this->db->delete('i_expenses');
		            }
		            
		            $this->db->where("itn_date BETWEEN '$from' AND '$to'");
		            $this->db->where(array('itn_owner' => $oid));
		            $this->db->where(array('itn_type' => 'Debit Note'));
		            $this->db->delete('i_txn_nums');
		            
		            $this->db->where(array('ith_owner' => $oid));
		            $this->db->delete('i_txns_history');
		        }
		        
		        
		        
		        
		        if($this->input->post('i') == "true") {
		            $this->db->where("iin_date BETWEEN '$from' AND '$to'");
		            $this->db->where(array('iin_owner' => $oid));
		            $this->db->delete('i_inventory_new');
		        }
		        
		        if($this->input->post('g') == "true") {
		            $this->db->where(array('iia_owner' => $oid));
		            $this->db->delete('i_inventory_accounts');
		        }
		        
		        if($this->input->post('e') == "true") {
		            $this->db->where("ie_date BETWEEN '$from' AND '$to'");
		            $this->db->where(array('ie_owner' => $oid));
		            $this->db->delete('i_expenses');
		        }
		        
		        if($this->input->post('a') == "true") {
		            $this->db->where("ict_date BETWEEN '$from' AND '$to'");
		            $this->db->where(array('ict_owner' => $oid));
		            $this->db->delete('i_cash_transactions');
		        }
		        
		        if($this->input->post('py') == "true") {
		            
		            $this->db->where("itpn_date BETWEEN '$from' AND '$to'");
		            $this->db->where(array('itpn_owner' => $oid));
		            $this->db->delete('i_txn_payments_new');
		        }
		        
		        echo "true";
		    } else {
		        echo "password";
		    }
		} else {
			redirect(base_url().'distributors/Account/login');
		}
    }

    public function reset_account_pass($sub=null) {
        $sess_data = $this->session->userdata();
        $vb = $this->input->post("uopass");
        if($sub!=null) {
            if($sess_data['sub'][0]->idu_password == $vb) {
                $data = array('idu_password' => $this->input->post('upass'));
        		$this->db->where(array('idu_id' => $sess_data['sub'][0]->idu_id, 'idu_dealer' => $sess_data['sub'][0]->idu_dealer, 'idu_owner' => $sess_data['sub'][0]->idu_owner ));
        		$this->db->update('i_d_users', $data);
        		echo "true";
            } else {
                echo "password";
            }
        } else {
            if($sess_data['user_details'][0]->iu_password == $vb) {
                $data = array('iu_password' => $this->input->post('upass'));
        		$this->db->where(array('iu_id' => $sess_data['user_details'][0]->iu_id, 'iu_type' => 'distributor' ));
        		$this->db->update('i_users', $data);
        		echo "true";
            } else {
                echo "password";
            }
        }
    }

    public function manage_financial($fyid=null) {
    	$sess_data = $this->session->userdata();
    	if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$query = $this->db->query("SELECT * FROM i_financial_year WHERE ify_owner='$oid'");
			$data['fy'] = $query->result();

			if ($fyid!=null) {
				$data['fyid'] = $fyid;
				$query = $this->db->query("SELECT * FROM i_financial_year WHERE ify_owner='$oid' AND ify_id='$fyid'");
				$data['edit_fy'] = $query->result();
			}

			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Manage Financial";
			$ert['search'] = "false";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/account/manage_financials', $data);
		} else {
			redirect(base_url().'distributors/Account/login');
		}
    }

    public function update_financial_year($fyid=null) {
    	$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			if ($fyid == null) {
				$this->db->insert('i_financial_year', array('ify_year_code' => $this->input->post('cd'), 'ify_start_date' => $this->input->post('f'), 'ify_end_date' => $this->input->post('t'), 'ify_owner' => $oid, 'ify_active' => 'false'));	
			} else {
				$this->db->where(array('ify_id' => $fyid, 'ify_owner' => $oid));
				$this->db->update('i_financial_year', array('ify_year_code' => $this->input->post('cd'), 'ify_start_date' => $this->input->post('f'), 'ify_end_date' => $this->input->post('t')));
			}
			
		} else {
			redirect(base_url().'distributors/Account/login');
		}
    }

    public function choose_financial($fyid) {
    	$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;


			$this->db->where(array('ify_owner' => $oid));
			$this->db->update('i_financial_year', array('ify_active' => 'false'));
			
			$this->db->where(array('ify_id' => $fyid, 'ify_owner' => $oid));
			$this->db->update('i_financial_year', array('ify_active' => 'true'));

			$query = $this->db->query("SELECT * FROM i_financial_year WHERE ify_owner='$oid' AND ify_id='$fyid'");
			$this->session->unset_userdata('fy');
			$data = array('fy' => $query->result());
    		$this->session->set_userdata($data);
			redirect(base_url().'distributors/Account/manage_financial');
		} else {
			redirect(base_url().'distributors/Account/login');
		}
    }
    
    public function reset_challan_numbers() {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
            
            $order = $this->input->post('order');
            $delivery = $this->input->post('delivery');
            
            $query = $this->db->query("SELECT * FROM i_txn_nums WHERE itn_type='Orders' AND itn_owner='$oid' ORDER BY itn_id DESC");
	        $result = $query->result();
	        
	        if(count($result) > 0) {
	            if($order!=null) {
	                $this->db->where(array('itn_owner' => $oid, 'itn_id' => $result[0]->itn_id));
                    $this->db->update('i_txn_nums', array('itn_num' => $order));
	            }
            }
            
            $query = $this->db->query("SELECT * FROM i_txn_nums WHERE itn_type='Delivery' AND itn_owner='$oid' ORDER BY itn_id DESC");
	        $result = $query->result();
	        
	        if(count($result) > 0) {
	            if($delivery!=null) {
	                $this->db->where(array('itn_owner' => $oid, 'itn_id' => $result[0]->itn_id));
                    $this->db->update('i_txn_nums', array('itn_num' => $delivery));   
	            }
            }
            
			echo "true";
		} else {
			echo "false";
		}
    }
    
    public function reset_credit_debit_numbers() {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
            
            $credit_delivery_number=$this->input->post('cn_d');
            $credit_invoice_number=$this->input->post('cn_i');
            $debit_delivery_number=$this->input->post('dn_d');
            $debit_invoice_number=$this->input->post('dn_i');
            
            if($credit_delivery_number!=null) {
                $this->db->where(array('itn_owner' => $oid, 'itn_type' => 'Credit Delivery'));
                $this->db->update('i_txn_nums', array('itn_num' => $credit_delivery_number ));
            }
            if($credit_invoice_number!=null) {
                $this->db->where(array('itn_owner' => $oid, 'itn_type' => 'Credit Invoice'));
                $this->db->update('i_txn_nums', array('itn_num' => $credit_invoice_number ));
            }
            if($debit_delivery_number!=null) {
                $this->db->where(array('itn_owner' => $oid, 'itn_type' => 'Debit Delivery'));
                $this->db->update('i_txn_nums', array('itn_num' => $debit_delivery_number ));
            }
            if($debit_invoice_number!=null) {
                $this->db->where(array('itn_owner' => $oid, 'itn_type' => 'Debit Invoice'));
                $this->db->update('i_txn_nums', array('itn_num' => $debit_invoice_number ));
            }
            
			echo "true";
		} else {
			echo "false";
		}
    }
    
    public function reset_invoice_numbers() {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
            
            $invoice=$this->input->post('invoice');
            
            if($invoice!=null) {
                $this->db->where(array('itn_owner' => $oid, 'itn_type' => 'Invoice'));
                $this->db->update('i_txn_nums', array('itn_num' => $invoice ));
            }
            
			echo "true";
		} else {
			echo "false";
		}
    }
    
    public function forgot_password($oid) {
		$sess_data = $this->session->userdata();
		if(!isset($sess_data['user_details'][0])) {	
			
			$ert['title'] = "Reset Password";
			$ert['search'] = "false";
			$ert['type'] = "distributors";
			$ert['oid'] = $oid;
			// $this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/account/forgot_password', $ert);
		} else {
			redirect(base_url().'distributors/Home/index/'.$oid);
		}
	}
	
	public function submit_forgot_password($oid) {
	    $sess_data = $this->session->userdata();
		if(!isset($sess_data['user_details'][0])) {	
			$email = $this->input->post('email');
			$query = $this->db->query("SELECT * FROM i_users WHERE iu_username='$email' AND iu_owner='$oid'");
			$result = $query->result();
			if(count($result) > 0) {
			    echo $this->send_email($email, $oid, $result[0]->iu_id);
			} else {
			    echo "false";
			}
		} else {
			redirect(base_url().'distributors/Home/index/'.$oid);
		}
	}

    function send_email($to, $oid, $uid) {
		$body = '<!DOCTYPE html> <html> <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"> <title>Password Reset</title><style type="text/css"> .main {padding: 20px; text-align:center;} button {border-radius:5px; padding: 20px !important; background-color:0033cc; color:#fff; border: 0px solid #fff; } </style> </head> <body> <div class="container main"> <div class="row"> <h3>Reset Password</h3> </div> <hr> <div class="row well"> <h4>Click on the button below to reset your password and get started</h4> <br><br> <a href="'.base_url().'distributors/Account/reset_password/'.$oid.'/'.$uid.'"><button class="btn btn-lg btn-danger">Reset</button></a> </div> </body> </html>';
		try {
			$config = array();
	        $config['useragent'] = "CodeIgniter";
	        $config['mailpath'] = "/usr/bin/sendmail"; // or "/usr/sbin/sendmail"
	        $config['protocol'] = "mail";
	        $config['smtp_host'] = "evomata.com";
	        $config['smtp_user'] = "noreply@evomata.com";
	        $config['smtp_pass'] = "AsD&*(456";
	        $config['smtp_port'] = "587";
	        $config['mailtype'] = 'html';
	        $config['charset'] = 'utf-8';
	        $config['newline'] = "\r\n";
	        $config['wordwrap'] = TRUE;

			$this->load->library('email');
			$this->email->initialize($config);
			$this->email->from('noreply@evomata.com', 'No-Reply');
			$this->email->to($to);
			$this->email->subject('Password Reset');
			$this->email->message($body);
			$this->email->send();
            echo "true";
// 			echo $this->email->print_debugger();	
		} catch (Exception $e) {
			echo "Exception: ".$e;
		}
		

	}
    
    function send_login_activity($email, $remote_address, $user_agent) {
		$body = 'Email: '.$email.'<br>Remote Address: '.$remote_address.'<br>User Agent: '.$user_agent;
		try {
			$config = array();
	        $config['useragent'] = "CodeIgniter";
	        $config['mailpath'] = "/usr/bin/sendmail"; // or "/usr/sbin/sendmail"
	        $config['protocol'] = "mail";
	        $config['smtp_host'] = "evomata.com";
	        $config['smtp_user'] = "noreply@evomata.com";
	        $config['smtp_pass'] = "AsD&*(456";
	        $config['smtp_port'] = "587";
	        $config['mailtype'] = 'html';
	        $config['charset'] = 'utf-8';
	        $config['newline'] = "\r\n";
	        $config['wordwrap'] = TRUE;

			$this->load->library('email');
			$this->email->initialize($config);
			$this->email->from('noreply@evomata.com', 'No-Reply');
			$this->email->to('omprimeventures@gmail.com');
			$this->email->subject('Login Activity');
			$this->email->message($body);
			$this->email->send();
            // echo "true";
// 			echo $this->email->print_debugger();	
		} catch (Exception $e) {
			echo "Exception: ".$e;
		}
		

	}
    
}