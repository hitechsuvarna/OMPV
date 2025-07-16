<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends CI_Controller {

	public function __construct()	{
		parent:: __construct();
		$this->load->database();
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->library('email');
		$this->load->model('distributors/Contact_Model','contact_model');
		$this->load->dbforge();
	}
########## USER ACCOUNT, LOGIN, VERIFY AND LOGOUT ################
	public function index($oid) {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$uid = $sess_data['user_details'][0]->iu_id;
			$ert['uid'] = $uid;
			
			$oid = $sess_data['user_details'][0]->iu_owner;
			$ert['oid'] = $oid;
    
            $rid = $sess_data['user_details'][0]->iu_ref;
			$data['rid'] = $rid;

			$data['user_info'] = $this->contact_model->load_contacts($oid, 'Dealer', $rid);
			
			$ert['name'] = $sess_data['user_details'][0]->ic_name;
			$ert['section'] = $sess_data['user_details'][0]->ic_section;
			$ert['logo'] = $sess_data['user_details'][0]->iu_logo;

			$ert['title'] = "Account";
			$ert['search'] = "false";
			$ert['type'] = "dealers";
			
			$this->load->view('dealers/navbar', $ert);
			$this->load->view('dealers/account/account', $data);
			
		} else {
			redirect(base_url().$this->config->item('dealer_login_red'));
		}
	}
	
	public function update_details($oid, $rid) {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$uid = $sess_data['user_details'][0]->iu_id;
			$oid = $sess_data['user_details'][0]->iu_owner;
			$dt = date('Y-m-d H:m:s');
			$this->contact_model->update_contact($oid, $uid, $rid, $dt, $this->input->post('name'), $this->input->post('company'), $this->input->post('email'), $this->input->post('phone'), $this->input->post('address'), $this->input->post('gst'), 'Dealer', $sess_data['user_details'][0]->ic_credit);
			echo $rid;
		} else {
			redirect(base_url().$this->config->item('dealer_login_red'));
		}
	}

	public function login($oid) {
		$sess_data = $this->session->userdata();

		if(!isset($sess_data['user_details'][0])) {	
			
			$ert['title'] = "Login";
			$ert['search'] = "false";
			$ert['type'] = "dealers";
			$ert['oid'] = $oid;
			// $this->load->view('distributors/navbar', $ert);
			$this->load->view('dealers/account/login', $ert);
		} else {
			redirect(base_url().'dealers/Home/index/'.$oid);
		}
	}

	public function verify() {
		$uname = $this->input->post('uname');
		$upass = $this->input->post('upass');

		$query = $this->db->query("SELECT * FROM i_users AS a LEFT JOIN i_contacts AS b ON a.iu_ref=b.ic_id  WHERE iu_username = '$uname'");
		$result = $query->result();

		if (count($result) > 0) {
		    if ($result[0]->iu_type == "dealer") {
		        if($result[0]->ic_new_flag == "2") {
		            echo "blocked";
		        } else if ($result[0]->iu_status == 'password_u') {
    				$id = $result[0]->iu_id;
    				echo $id;
    			} else if ($result[0]->iu_status == 'submitted') {
    				echo 'submitted';
    			} else if($result[0]->iu_status == "approval") {
    				echo "approve";
    			} else if ($result[0]->iu_password==$upass) {
    			    if($result[0]->ic_id != null) {
    			        $data = array('status' => "user" , "user_details" => $result, "price_display" => "block", 'account_status' => "active");
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
		} else {
			$que = $this->db->query("SELECT * FROM i_d_users WHERE idu_username = '$uname'");
			$res = $que->result();
			if(count($res) > 0) {
			    if($res[0]->idu_status == "inactive") {
			        echo $res[0]->idu_id.'/sub';
			    } else if($res[0]->idu_password == $upass) {
			        $d = $res[0]->idu_dealer;
			        $qu = $this->db->query("SELECT * FROM i_users AS a LEFT JOIN i_contacts AS b ON a.iu_ref=b.ic_id WHERE iu_id = '$d'");
			        $rs = $qu->result();
			        if($rs[0]->ic_new_flag == "2") {
			            echo "blocked";
			        } else {
			            $rs[0]->ic_name = $res[0]->idu_name;
    			        $data = array('status' => "user" , "user_details" => $rs, "price_display" => "block", "sub" => $res);
        				$this->session->set_userdata($data);
        				echo "true";   
			        }
			    } else {
			        echo "false";
			    }
			} else {
			    echo "false";   
			}
		}
	}

	public function logout($oid) {
		$this->session->unset_userdata('account_status');
		$this->session->unset_userdata('user_details');
		$this->session->unset_userdata('status');
		redirect(base_url().'dealers/Account/login/'.$oid);
	}

	public function reset_password($oid, $id, $acc=null) {
		$sess_data = $this->session->userdata();
		if($acc == "sub") {
		    $data['type'] = 'dealers';
			$data['uid'] = $id;
			$data['oid'] = $oid;
			$data['sub'] = "true";
			echo "Hey";
			$this->load->view('dealers/account/reset', $data);
		} else if(!isset($sess_data['user_details'][0]) || $acc == "r") {	
			$data['type'] = 'dealers';
			echo "NANNA";
			$data['uid'] = $id;
			$data['oid'] = $oid;
			$this->load->view('dealers/account/reset', $data);
		} else {
			redirect(base_url().'dealers/Home/index/'.$oid);
		}
	}

	public function reset_update($oid, $id, $sub=null) {
	    if($sub!= null) {
	        $data = array('idu_password' => $this->input->post('upass'), 'idu_status' => 'active');
    		$this->db->where('idu_id', $id);
    		$this->db->update('i_d_users', $data);
	    } else {
	        $data = array('iu_password' => $this->input->post('upass'), 'iu_status' => 'active');
    		$this->db->where('iu_id', $id);
    		$this->db->update('i_users', $data);   
	    }
		echo "true";
	}


	public function register($oid) {
		$ert['title'] = "Signup";
		$ert['search'] = "false";
		$ert['type'] = "dealers";
		$ert['oid'] = $oid;
		// $this->load->view('distributors/navbar', $ert);
		$this->load->view('dealers/account/register', $ert);
	}

    public function settings() {
        $sess_data = $this->session->userdata();
        if(isset($sess_data['user_details'][0])) {
			$uid = $sess_data['user_details'][0]->iu_id;
			$oid = $sess_data['user_details'][0]->iu_owner;
			
			$data['oid'] = $oid;
			$data['uid'] = $uid;
			
			$query = $this->db->query("SELECT * FROM i_d_users WHERE idu_owner='$oid' AND idu_dealer='$uid'");
			$data['usrs'] = $query->result();
			
			$ert['name'] = $sess_data['user_details'][0]->ic_name;
			$ert['section'] = $sess_data['user_details'][0]->ic_section;
			$ert['logo'] = $sess_data['user_details'][0]->iu_logo;

			$ert['title'] = "Settings";
			$ert['search'] = "false";
			$ert['type'] = "dealers";
			
			$this->load->view('dealers/navbar', $ert);
			$this->load->view('dealers/account/settings', $data);
			
		} else {
		    redirect(base_url().$this->config->item('dealer_login_red'));
		}
    }
    
    public function display_price($sub=null) {
        $sess_data = $this->session->userdata();
        $vb = $this->input->post("ps");
        if($sub!=null) {
            if($sess_data['sub'][0]->idu_password == $vb) {
                if(isset($sess_data['price_display'])) {
        		    if($sess_data['price_display'] == "block") {
        		        $this->session->unset_userdata('price_display');
        		        $data = array('price_display' => "none");
        		        $this->session->set_userdata($data);
        		        echo "false";
        		    } else {
        		        $this->session->unset_userdata('price_display');
        		        $data = array('price_display' => "block");
        		        $this->session->set_userdata($data);
        		        echo "true";
        		    }
        		}
            } else {
                echo "password";
            }
        } else {
            if($sess_data['user_details'][0]->iu_password == $vb) {
                if(isset($sess_data['price_display'])) {
        		    if($sess_data['price_display'] == "block") {
        		        $this->session->unset_userdata('price_display');
        		        $data = array('price_display' => "none");
        		        $this->session->set_userdata($data);
        		        echo "false";
        		    } else {
        		        $this->session->unset_userdata('price_display');
        		        $data = array('price_display' => "block");
        		        $this->session->set_userdata($data);
        		        echo "true";
        		    }
        		}
            } else {
                echo "password";
            }
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
        		$this->db->where(array('iu_id' => $sess_data['user_details'][0]->iu_id, 'iu_type' => 'dealer' ));
        		$this->db->update('i_users', $data);
        		echo "true";
            } else {
                echo "password";
            }
        }
    }
    
    

	public function submit_register($oid) {
		$dt = date('Y-m-d H:m:s');

        $email = $this->input->post('email');
        $query = $this->db->query("SELECT * FROM i_users WHERE iu_username='$email'");
        $result = $query->result();
        
        if(count($result) > 0) {
            echo "exists";
        } else {
            $data = array(
    			'ic_name' => $this->input->post('name'),
    			'ic_company' => $this->input->post('company'),
    			'ic_email' => $email,
    			'ic_phone' => $this->input->post('phone'),
    			'ic_address' => $this->input->post('address'),
    			'ic_gst_number' => $this->input->post('gst'),
    			'ic_owner' => $oid,
    			'ic_section' => 'Dealer',
    			'ic_new_flag' => 1
    			 );
    		$this->db->insert('i_contacts', $data);
    		$cid = $this->db->insert_id();
    
    		$query = $this->db->query("SELECT iu_subscription_start, iu_subscription_end FROM i_users WHERE iu_id = '$oid'");
    		$result = $query->result();
    
    
    		$data = array(
    			'iu_username' => $this->input->post('email'),
    			'iu_type' => 'dealer',
    			'iu_ref' => $cid,
    			'iu_owner' => $oid,
    			'iu_password' => $this->input->post('password'),
    			'iu_status' => 'submitted',
    			'iu_subscription_start' => $result[0]->iu_subscription_start,
    			'iu_subscription_end' => $result[0]->iu_subscription_end,
    			'iu_created' => $dt,
    			'iu_created_by' => $oid
    		);
    		$this->db->insert('i_users', $data);
    		
    		echo "true";   
        }
	}
	
	public function save_user() {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$uid = $sess_data['user_details'][0]->iu_id;
			$oid = $sess_data['user_details'][0]->iu_owner;

		    $data = array(
    	        'idu_name' => $this->input->post('name'),
    	        'idu_username' => $this->input->post('email'),
    	        'idu_dealer' => $uid,
    	        'idu_status' => 'inactive',
    	        'idu_owner' => $oid);
    	    $this->db->insert('i_d_users', $data);
    	    
    	    $query = $this->db->query("SELECT * FROM i_d_users WHERE idu_owner='$oid' AND idu_dealer='$uid'");
    	    print_r(json_encode($query->result()));
    	    
		} else {
			redirect(base_url().$this->config->item('dealer_login_red'));
		}
	}
	
	public function delete_user() {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$uid = $sess_data['user_details'][0]->iu_id;
			$oid = $sess_data['user_details'][0]->iu_owner;
            $did = $this->input->post('did');
		    $data = array(
    	        'idu_id' => $did,
    	        'idu_dealer' => $uid,
    	        'idu_owner' => $oid);
    	    $this->db->where($data);
    	    $this->db->delete('i_d_users');
    	    
    	    $query = $this->db->query("SELECT * FROM i_d_users WHERE idu_owner='$oid' AND idu_dealer='$uid'");
    	    print_r(json_encode($query->result()));
    	    
		} else {
			redirect(base_url().$this->config->item('dealer_login_red'));
		}
	}
	
	public function terms() {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			
			$ert['name'] = $sess_data['user_details'][0]->ic_name;
			$ert['section'] = $sess_data['user_details'][0]->ic_section;
			$ert['logo'] = $sess_data['user_details'][0]->iu_logo;

			$ert['title'] = "Terms";
			$ert['search'] = "false";
			$ert['type'] = "dealers";
			
			$this->load->view('dealers/navbar', $ert);
			$this->load->view('dealers/account/terms');
			
		} else {
			redirect(base_url().$this->config->item('dealer_login_red'));
		}
	}
	
	public function forgot_password($oid) {
		$sess_data = $this->session->userdata();
		if(!isset($sess_data['user_details'][0])) {	
			
			$ert['title'] = "Reset Password";
			$ert['search'] = "false";
			$ert['type'] = "dealers";
			$ert['oid'] = $oid;
			// $this->load->view('distributors/navbar', $ert);
			$this->load->view('dealers/account/forgot_password', $ert);
		} else {
			redirect(base_url().'dealers/Home/index/'.$oid);
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
			redirect(base_url().'dealers/Home/index/'.$oid);
		}
	}

	public function company() {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {	
			$oid = $sess_data['user_details'][0]->iu_owner;
			
			$ert['name'] = $sess_data['user_details'][0]->ic_name;
			$ert['section'] = $sess_data['user_details'][0]->ic_section;
			$ert['logo'] = $sess_data['user_details'][0]->iu_logo;

			$ert['title'] = "About";
			$ert['search'] = "false";
			$ert['type'] = "dealers";
			
			$this->load->view('dealers/navbar', $ert);
			$this->load->view('dealers/account/company');
		} else {
			redirect(base_url().$this->config->item('dealer_login_red'));
		}	
	}
	
    function send_email($to, $oid, $uid) {
		$body = '<!DOCTYPE html> <html> <head> <title>Password Reset</title><style type="text/css"> .main {padding: 20px; text-align:center;} button {border-radius:5px; padding: 20px !important; background-color:0033cc; color:#fff; border: 0px solid #fff; } </style> </head> <body> <div class="container main"> <div class="row"> <h3>Reset Password</h3> </div> <hr> <div class="row well"> <h4>Click on the button below to reset your password and get started</h4> <br><br> <a href="'.base_url().'dealers/Account/reset_password/'.$oid.'/'.$uid.'"><button class="btn btn-lg btn-danger">Reset</button></a> </div> </body> </html>';
		try {
			$config = array();
	        $config['useragent'] = "CodeIgniter";
	        $config['mailpath'] = "/usr/bin/sendmail"; // or "/usr/sbin/sendmail"
	        $config['protocol'] = "smtp";
	        $config['smtp_host'] = "evomata.com";
	        $config['smtp_user'] = "noreply@dhristi.evomata.com";
	        $config['smtp_pass'] = "ASD789456";
	        $config['smtp_port'] = "587";
	        $config['mailtype'] = 'html';
	        $config['charset'] = 'utf-8';
	        $config['newline'] = "\r\n";
	        $config['wordwrap'] = TRUE;

			$this->load->library('email');
			$this->email->initialize($config);
			$this->email->from('noreply@dhristi.evomata.com', 'No-Reply');
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
}