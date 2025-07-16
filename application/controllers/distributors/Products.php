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
########## PRODUCTS ################
	public function index() {
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
			
		
		    $query = $this->db->query("SELECT * FROM i_products AS a WHERE a.ip_owner='$oid' AND (a.ip_category IS NULL OR a.ip_category='0') ORDER BY a.ip_id");
			$data["products"] = $query->result();
			$data['products'] = [];
            $query = $this->db->query("SELECT * FROM i_category WHERE ica_parent_category='0' AND ica_owner='$oid'");
            $data["category"] = $query->result();
            
            $data['curr_cat'] = 0;
            
            $query = $this->db->query("SELECT * FROM i_category WHERE ica_owner='$oid'");
            $data['cat_list'] = $query->result();
            $data['units'] = $this->product_model->load_product_units($oid);
			$data['taxes'] = $this->product_model->load_tax_groups($oid);
            
            $data['oid'] = $oid;
			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Products";
			$ert['search'] = "true";
			$ert['search_placeholder'] = "Search Products";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/products/products', $data);
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}

    public function load_category($ctid) {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		
			$query = $this->db->query("SELECT * FROM i_products AS a LEFT JOIN i_category AS b ON a.ip_category=b.ica_id LEFT JOIN i_p_taxes AS c ON a.ip_id=c.ipt_p_id LEFT JOIN i_tax_group AS d ON c.ipt_t_id=d.ittxg_id WHERE a.ip_owner='$oid' AND a.ip_category='$ctid'");
			$data["products"] = $query->result();
            
			$query = $this->db->query("SELECT * FROM i_category WHERE ica_parent_category='$ctid' AND ica_owner='$oid'");
            $data["category"] = $query->result();
            
            $query = $this->db->query("SELECT * FROM i_category WHERE ica_owner='$oid'");
            $data['cat_list'] = $query->result();
            $data['units'] = $this->product_model->load_product_units($oid);
			$data['taxes'] = $this->product_model->load_tax_groups($oid);
			
            $data['oid'] = $oid;
            
            $data['curr_cat'] = $ctid;
            
			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Products";
			$ert['search'] = "true";
			$ert['search_placeholder'] = "Search Products";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/products/products', $data);
		} else {
			redirect(base_url().'distributors/Account/login');
		}
    }
	
	public function search_products($ctid) {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$k=$this->input->post('keywords');
			$query = $this->db->query("SELECT * FROM i_products AS a LEFT JOIN i_category AS b ON a.ip_category=b.ica_id LEFT JOIN i_p_taxes AS c ON a.ip_id=c.ipt_p_id LEFT JOIN i_tax_group AS d ON c.ipt_t_id=d.ittxg_id WHERE a.ip_owner='$oid' AND a.ip_name LIKE '%$k%'");
			if(count($query->result()) > 0) {
			    $d=$query->result();
			} else {
			    $query = $this->db->query("SELECT * FROM i_products AS a LEFT JOIN i_category AS b ON a.ip_category=b.ica_id LEFT JOIN i_p_taxes AS c ON a.ip_id=c.ipt_p_id LEFT JOIN i_tax_group AS d ON c.ipt_t_id=d.ittxg_id WHERE a.ip_owner='$oid' AND a.ip_description LIKE '%$k%'");
			    $d=$query->result();
			}
			print_r(json_encode($d));
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}
	
	public function search_products_by_code($ctid) {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$k=$this->input->post('keywords');
			$query = $this->db->query("SELECT ip_name FROM i_products WHERE ip_owner='$oid' AND ip_description LIKE '%$k%' LIMIT 15");
			if(count($query->result()) > 0) {
			    $d=$query->result();   
			} else {
			    $query = $this->db->query("SELECT ip_name FROM i_products WHERE ip_owner='$oid' AND ip_name LIKE '%$k%'");    
			    $d=$query->result();
			}
			
			print_r(json_encode($d));
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}
	
	public function search_product_name() {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$k=$this->input->post('keywords');
			$query = $this->db->query("SELECT ip_id, ip_name FROM i_products WHERE ip_owner='$oid' AND ip_name LIKE '%$k%' LIMIT 15");
			if(count($query->result()) > 0) {
			    $d=$query->result();   
			}
			print_r(json_encode($d));
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}
	
	public function product_exist() {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$p=$this->input->post('p');
			$query = $this->db->query("SELECT * FROM i_products WHERE ip_owner='$oid' AND ip_name = '$p'");
			if(count($query->result()) > 0) {
			    echo 'true';
			} else {
			    echo 'false';
			}
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}
	
	public function load_products($ctid) {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			if($ctid==0) {
			    $query = $this->db->query("SELECT * FROM i_products AS a LEFT JOIN i_p_taxes AS c ON a.ip_id=c.ipt_p_id LEFT JOIN i_tax_group AS d ON c.ipt_t_id=d.ittxg_id WHERE a.ip_owner='$oid' AND (a.ip_category='0' OR a.ip_category IS NULL)");
			} else {
			    $query = $this->db->query("SELECT * FROM i_products AS a LEFT JOIN i_category AS b ON a.ip_category=b.ica_id LEFT JOIN i_p_taxes AS c ON a.ip_id=c.ipt_p_id LEFT JOIN i_tax_group AS d ON c.ipt_t_id=d.ittxg_id WHERE a.ip_owner='$oid' AND a.ip_category='$ctid'");
			}
			print_r(json_encode($query->result()));
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}
	
	public function filter_products() {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			print_r(json_encode($this->product_model->filter_products($oid, $this->input->post('search'))));
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}

	public function add_product($ctid) {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			
			$data['curr_cat'] = $ctid;
            $data['category'] = $this->product_model->load_categories($oid);
			$data['units'] = $this->product_model->load_product_units($oid);
			$data['taxes'] = $this->product_model->load_tax_groups($oid);


			$ert['name'] = $sess_data['user_details'][0]->ic_name;
			$ert['title'] = "Add Product";
			$ert['search'] = "false";
			$ert['search_placeholder'] = "Search Products";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/products/product_add', $data);
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}

	public function save_product() {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$dt = date('Y-m-d H:m:s');

			$pid = $this->product_model->save_product($oid, $this->input->post('product'), $this->input->post('description'), $this->input->post("hsn"), $this->input->post("unit"), $this->input->post('tax'), $this->input->post('limit'), $this->input->post('category'), $this->input->post('alias'));
			echo $pid;
		} else {
			redirect(base_url().'distributors/Account/login');
		}	
	}

	public function image_upload($pid, $flg) {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;	
			
			$upload_dir = $this->config->item('document_rt')."assets/uploads/".$oid."/".$pid."/";
			if(!file_exists($upload_dir)) {
				mkdir($upload_dir, 0777, true);
			}
			$img_path = "";
			
			if (is_dir($upload_dir) && is_writable($upload_dir)) {
			    
			    for($ij=0; $ij < count($_FILES); $ij++) {
			        $sourcePath = $_FILES[$ij]['tmp_name']; // Storing source path of the file in a variable
    				$targetPath = $upload_dir.$_FILES[$ij]['name']; // Target path where file is to be stored
    				// $img_path = $targetPath;
    				move_uploaded_file($sourcePath,$targetPath) ; // Moving Uploaded file		
    				$img_path = $_FILES[$ij]['name'];
    				
            		if($flg == 1) {
        			    $this->product_model->update_image($oid, $pid, $img_path);
        			    $this->product_model->update_image_list($oid, $pid, $img_path, $flg);
        			    $flg = 0;
        			} else {
        			    $this->product_model->update_image_list($oid, $pid, $img_path, $flg);
        			}
			    }
			}
			echo $pid;
		}
	}

	public function edit_product($ctid, $pid) {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;

			$data['curr_cat'] = $ctid;
            $data["edit_product"] = $this->product_model->load_products($oid, $pid);
			$data["edit_product_taxes"] = $this->product_model->load_product_taxes($oid, $pid);
            $data["edit_product_image"] = $this->product_model->load_product_images($oid, $pid);
            
			$data['category'] = $this->product_model->load_categories($oid);
			$data['oid'] = $oid; $data['pid'] = $pid;
			$data['units'] = $this->product_model->load_product_units($oid);
			$data['taxes'] = $this->product_model->load_tax_groups($oid);
			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Edit Product";
			$ert['search'] = "false";
			$ert['search_placeholder'] = "Search Products";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/products/product_add', $data);
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}

	public function update_product($pid) {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$dt = date('Y-m-d H:m:s');
            if($this->input->post('category') == null || $this->input->post('category') == "null") {
                $c=0;
            } else {
                $c=$this->input->post('category');
            }
			$pid = $this->product_model->update_product($oid, $pid, $this->input->post('product'), $this->input->post('description'), $this->input->post("hsn"), $this->input->post("unit"), $this->input->post('tax'), $this->input->post('limit'), $c, $this->input->post('alias'));
			echo $pid;
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}
	
	public function update_products_common() {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$dt = date('Y-m-d H:i:s');
            
            $p=$this->input->post('p');
            $ct = $this->input->post('category');
            
            $dx = [];
            
            $ctid=0;
            if($ct!="") {
                $query = $this->db->query("SELECT * FROM i_category WHERE ica_category_name='$ct' AND ica_owner='$oid'");
                $result = $query->result();
                
                
                if(count($result) > 0) {
                    $ctid=$result[0]->ica_id;
                } else {
                    $this->db->insert('i_category', array('ica_category_name' => $ct, 'ica_owner' => $oid, 'ica_parent_category' => 0));
                    $ctid = $this->db->insert_id();
                }
                
                $dx['ip_category'] = $ctid;
            }
            
            if($this->input->post('alias') != "") {
                $dx['ip_alias'] = $this->input->post('alias');
            }
            
            if($this->input->post('hsn')!="") {
                $dx['ip_hsn_code'] = $this->input->post('hsn');
            }
            
            if($this->input->post('unit')!="") {
                $dx['ip_unit'] = $this->input->post('unit');
            }
            
            if($this->input->post('limit')!="") {
                $dx['ip_lower_limit'] = $this->input->post('limit');
            }
            
            for($i=0;$i<count($p);$i++) {
                    
                $this->db->where(array('ip_owner' => $oid, 'ip_id' => $p[$i]));
                $this->db->update('i_products', $dx);
                
                if($this->input->post('tax')!='') {
                    $this->db->where(array('ipt_p_id' => $p[$i], 'ipt_oid' => $oid));
                    $this->db->update('i_p_taxes', array('ipt_t_id' => $this->input->post('tax')));   
                }
            }
            
            echo $pid;
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}

	public function delete_product($pid) {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$dt = date('Y-m-d H:m:s');

			$pid = $this->product_model->delete_product($oid, $pid);
			echo $pid;
		} else {
			redirect(base_url().'distributors/Account/login');
		}	
	}
########## MANAGE CATEGORIES ################
	public function manage_categories() {
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
			
			
			$query = $this->db->query("SELECT * FROM i_category WHERE ica_owner='$oid'");
			$data['category'] = $query->result();

            $ert['oid'] = $oid;
			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Manage Categories";
			$ert['search'] = "true";
			$ert['search_placeholder'] = "Search Products";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/products/manage_categories', $data);
		} else {
			redirect(base_url().'distributors/Account/login');
		}	
	}

	public function search_categories($name=null) {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$k=$this->input->post('keyword');
			if($name==null) {
			    $query = $this->db->query("SELECT * FROM i_category WHERE ica_owner='$oid' AND ica_category_name LIKE '%$k%'");    
			} else {
			    $query = $this->db->query("SELECT ica_category_name AS label, ica_category_name AS value FROM i_category WHERE ica_owner='$oid' AND ica_category_name LIKE '%$k%'");
			}
			
			print_r(json_encode($query->result()));
		} else {
			redirect(base_url().'distributors/Account/login');
		}	
	}

	public function load_categories($cid) {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$query = $this->db->query("SELECT * FROM i_category WHERE ica_owner='$oid' AND ica_id = '$cid'");
			print_r(json_encode($query->result()));
		} else {
			redirect(base_url().'distributors/Account/login');
		}	
	}

	public function update_categories($ctid=null) {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			if ($ctid == null) {
				$this->db->insert('i_category', array('ica_category_name' => $this->input->post('c'), 'ica_parent_category' => $this->input->post('p'), 'ica_owner' => $oid));
				$ctid = $this->db->insert_id();
			} else {
				$this->db->where(array('ica_id' => $ctid, 'ica_owner' => $oid));
				$this->db->update('i_category', array('ica_category_name' => $this->input->post('c'), 'ica_parent_category' => $this->input->post('p')));
			}
			echo $ctid;
		} else {
			redirect(base_url().'distributors/Account/login');
		}	
	}
	
	public function delete_category($ctid=null) {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			
			$this->db->where(array('ica_id' => $ctid, 'ica_owner' => $oid));
			$this->db->delete('i_category');
			
			$this->db->where(array('ip_owner' => $oid, 'ip_category' => $ctid));
			$this->db->update('i_products', array('ip_category' => '0'));
			
			redirect(base_url().'distributors/Products/manage_categories');
		} else {
			redirect(base_url().'distributors/Account/login');
		}	
	}
	
	public function category_image_upload($pid, $flg) {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;	
			$upload_dir = $this->config->item('document_rt')."assets/uploads/".$oid."/c/".$pid."/";
			if(!file_exists($upload_dir)) {
				mkdir($upload_dir, 0777, true);
			}
			$img_path = "";
			$xs = '';
			if (is_dir($upload_dir) && is_writable($upload_dir)) {
			    for($ij=0; $ij < count($_FILES); $ij++) {
			     //   $xs .= $_FILES[$ij]['name'];
			        if($ij==0) {
			            $sourcePath = $_FILES[$ij]['tmp_name']; // Storing source path of the file in a variable
        				$targetPath = $upload_dir.$_FILES[$ij]['name']; // Target path where file is to be stored
        				// $img_path = $targetPath;
        				move_uploaded_file($sourcePath,$targetPath) ; // Moving Uploaded file		
        				$img_path = $_FILES[$ij]['name'];
        				
            		    $this->db->where(array('ica_id' => $pid, 'ica_owner' => $oid));
        			    $this->db->update('i_category', array('ica_img' => $img_path));    
			        } else if($ij==1) {
			            $sourcePath = $_FILES[$ij]['tmp_name']; // Storing source path of the file in a variable
        				$targetPath = $upload_dir.$_FILES[$ij]['name']; // Target path where file is to be stored
        				// $img_path = $targetPath;
        				move_uploaded_file($sourcePath,$targetPath) ; // Moving Uploaded file		
        				$img_path = $_FILES[$ij]['name'];
        				
            		    $this->db->where(array('ica_id' => $pid, 'ica_owner' => $oid));
        			    $this->db->update('i_category', array('ica_img2' => $img_path));
			        } else if($ij==2) {
			            $sourcePath = $_FILES[$ij]['tmp_name']; // Storing source path of the file in a variable
        				$targetPath = $upload_dir.$_FILES[$ij]['name']; // Target path where file is to be stored
        				// $img_path = $targetPath;
        				move_uploaded_file($sourcePath,$targetPath) ; // Moving Uploaded file		
        				$img_path = $_FILES[$ij]['name'];
        				
            		    $this->db->where(array('ica_id' => $pid, 'ica_owner' => $oid));
        			    $this->db->update('i_category', array('ica_img3' => $img_path));
			        } else if($ij==3) {
			            $sourcePath = $_FILES[$ij]['tmp_name']; // Storing source path of the file in a variable
        				$targetPath = $upload_dir.$_FILES[$ij]['name']; // Target path where file is to be stored
        				// $img_path = $targetPath;
        				move_uploaded_file($sourcePath,$targetPath) ; // Moving Uploaded file		
        				$img_path = $_FILES[$ij]['name'];
        				
            		    $this->db->where(array('ica_id' => $pid, 'ica_owner' => $oid));
        			    $this->db->update('i_category', array('ica_img4' => $img_path));
			        } else if($ij==4) {
			            $sourcePath = $_FILES[$ij]['tmp_name']; // Storing source path of the file in a variable
        				$targetPath = $upload_dir.$_FILES[$ij]['name']; // Target path where file is to be stored
        				// $img_path = $targetPath;
        				move_uploaded_file($sourcePath,$targetPath) ; // Moving Uploaded file		
        				$img_path = $_FILES[$ij]['name'];
        				
            		    $this->db->where(array('ica_id' => $pid, 'ica_owner' => $oid));
        			    $this->db->update('i_category', array('ica_img5' => $img_path));
			        } else if($ij==5) {
			            $sourcePath = $_FILES[$ij]['tmp_name']; // Storing source path of the file in a variable
        				$targetPath = $upload_dir.$_FILES[$ij]['name']; // Target path where file is to be stored
        				// $img_path = $targetPath;
        				move_uploaded_file($sourcePath,$targetPath) ; // Moving Uploaded file		
        				$img_path = $_FILES[$ij]['name'];
        				
            		    $this->db->where(array('ica_id' => $pid, 'ica_owner' => $oid));
        			    $this->db->update('i_category', array('ica_img6' => $img_path));
			        }
			        
			    }
			}
			echo $pid;
		}
	}


########## PRODUCT PRICING ################
	public function product_pricing() {
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
			
		
			$query=$this->db->query("SELECT * FROM i_category WHERE ica_owner='$oid' AND ica_parent_category='0'");
			$data["category"] = $query->result();
			$data["products"] = $this->product_model->load_products($oid);
			$data["dealers"] = $this->contact_model->load_contacts($oid, 'Dealer');
			$data['oid'] = $oid;


			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Product Pricing";
			$ert['search'] = "false";
			$ert['search_placeholder'] = "Search Products";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/products/product_pricing', $data);
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}

    public function load_prod_cat($ctid) {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		
		    $code = $this->input->post('code');
		    if($code == null) {
		        $query=$this->db->query("SELECT * FROM i_category WHERE ica_owner='$oid' AND ica_parent_category='$ctid'");
    			$data['category']=$query->result();
    			
    			$query=$this->db->query("SELECT * FROM i_products WHERE ip_owner='$oid' AND ip_category='$ctid'");
    			$data['product']=$query->result();
		    } else {
    			$query=$this->db->query("SELECT * FROM i_products WHERE ip_owner='$oid' AND ip_description LIKE '%$code%'");
    			$data['product']=$query->result();
		    }
    			
			
			print_r(json_encode($data));
		} else {
			redirect(base_url().'distributors/Account/login');
		}
    }
    
    public function get_dealer() {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		    $dl = $this->input->post('d');
		    
			$query=$this->db->query("SELECT * FROM i_contacts WHERE ic_owner='$oid' AND ic_name LIKE '%$dl%'");
			print_r(json_encode($query->result()));
		} else {
			redirect(base_url().'distributors/Account/login');
		}
    }
    
    
    
	public function get_dealer_pricing() {
		$sess_data = $this->session->userdata();

		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$data['dealers'] = $this->contact_model->load_contacts($oid,'Dealer');
			$data['dealer_pricing'] = $this->contact_model->load_contact_pricing($oid, $this->input->post('pid'), 'Dealer');
			print_r(json_encode($data));
		} else {
			echo "Please login again";
		}	
	}

	public function save_product_pricing() {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$dt = date('Y-m-d H:m:s');
			$ct=$this->input->post('ct_id');
			$dl=$this->input->post('d_id'); $dl_arr=[];
			for($j=0;$j<count($dl);$j++) {
			    array_push($dl_arr, $dl[$j]['id']);
			}
			for($i=0;$i<count($ct);$i++) {
			    if($ct[$i]['t'] == 'cat') {
			        $p = $this->product_model->filter_products($oid, $ct[$i]['p']);
                    for ($j=0; $j<count($p); $j++) {
                        $pid = $p[$j]->ip_id;
                        $this->product_model->update_product_pricing($oid, $dt, $pid, $dl_arr, $this->input->post('price'));
                        echo "cat";
                    }
			    } else if($ct[$i]['t'] == 'prod') {
			        $this->product_model->update_product_pricing($oid, $dt, $ct[$i]['p'], $dl_arr, $this->input->post('price'));
			        echo "prod";
			    }
 			}
			echo true;
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}

########## TAX ################
	public function tax() {
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
			
			$query = $this->db->query("SELECT * FROM i_taxes WHERE itx_owner = '$oid'");
			$result = $query->result();

			$data['tax'] = $result;

			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Taxes";
			$ert['search'] = "true";
			$ert['search_placeholder'] = "Search Products";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/tax/tax', $data);
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}

	public function tax_add() {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;

			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Tax Add";
			$ert['search'] = "false";
			$ert['search_placeholder'] = "Search Products";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/tax/tax_add');
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}

	public function save_tax() {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {

			$name = $this->input->post('name');
			$percent = $this->input->post('percent');
			
			$dt = date('Y-m-d H:m:s');
			$oid = $sess_data['user_details'][0]->iu_owner;

			$data = array(
				'itx_name' => $name,
				'itx_percent' => $percent,
				'itx_owner' => $oid);

			$this->db->insert('i_taxes', $data);
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}

	public function tax_edit($t_id) {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;

			$query = $this->db->query("SELECT * FROM i_taxes WHERE itx_id = '$t_id' AND itx_owner='$oid'");
			$result = $query->result();

			$data['edit_tax'] = $result;
			$data['tid'] =$t_id;
			
			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Tax Edit";
			$ert['search'] = "false";
			$ert['search_placeholder'] = "Search Products";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/tax/tax_add', $data);	
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}

	public function update_tax($tid) {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {

			$name = $this->input->post('name');
			$percent = $this->input->post('percent');
			
			$dt = date('Y-m-d H:m:s');
			$oid = $sess_data['user_details'][0]->iu_owner;

			$data = array(
				'itx_name' => $name,
				'itx_percent' => $percent);
			$this->db->where('itx_id', $tid);
			$this->db->update('i_taxes', $data);
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}

	public function delete_tax($prid) {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {

			$this->db->where('itx_id', $prid);
			$this->db->delete('i_taxes');

			redirect(base_url().'distributors/Products/tax');
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}

	public function tax_group() {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;

			$data['tax_group'] = $this->product_model->load_tax_groups($oid);

			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Tax Groups";
			$ert['search'] = "false";
			$ert['search_placeholder'] = "Search Products";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/tax/tax_group', $data);
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}

	public function tax_group_add() {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;

			$query = $this->db->query("SELECT * FROM i_taxes WHERE itx_owner = '$oid'");
			$result = $query->result();

			$data['tax'] = $result;

			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Tax Group Add";
			$ert['search'] = "false";
			$ert['search_placeholder'] = "Search Products";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/tax/tax_group_add', $data);
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}

	public function save_tax_group() {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {

			$name = $this->input->post('name');
			$taxes = $this->input->post('taxes');
			
			$dt = date('Y-m-d H:m:s');
			$oid = $sess_data['user_details'][0]->iu_owner;

			$data = array(
				'ittxg_group_name' => $name,
				'ittxg_owner' => $oid);
			$this->db->insert('i_tax_group', $data);
			$tg_id = $this->db->insert_id();

			for ($i=0; $i < count($taxes) ; $i++) { 
				$tname = $taxes[$i];
				$query = $this->db->query("SELECT * FROM i_taxes WHERE itx_name = '$tname' AND itx_owner = '$oid'");
				$result = $query->result();

				if(count($result) > 0) {
					$txid = $result[0]->itx_id;
				} else {
					$data = array(
						'itx_name' => $tname,
						'itx_owner' => $oid);
					$this->db->insert('i_taxes', $data);
					$txid = $this->db->insert_id();
				}

				$data1 = array(
					'itxgc_tg_id' => $tg_id,
					'itxgc_tx_id' => $txid );
				$this->db->insert('i_tax_group_collection', $data1);
			}

			
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}

	public function tax_group_edit($t_id) {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;

			$query = $this->db->query("SELECT * FROM i_tax_group WHERE ittxg_id = '$t_id' AND ittxg_owner='$oid'");
			$result = $query->result();
			$data['edit_tax_group'] = $result;
			
			$query = $this->db->query("SELECT * FROM i_tax_group_collection WHERE itxgc_tg_id = '$t_id'");
			$result = $query->result();
			$data['edit_tax_group_item'] = $result;

			$query = $this->db->query("SELECT * FROM i_taxes WHERE itx_owner = '$oid'");
			$result = $query->result();

			$data['tax'] = $result;
			
			$data['tid'] =$t_id;
			
			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Tax Group Edit";
			$ert['search'] = "false";
			$ert['search_placeholder'] = "Search Products";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/tax/tax_group_add', $data);	
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}

	public function update_tax_group($tid) {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {

			$name = $this->input->post('name');
			$taxes = $this->input->post('taxes');
			
			$dt = date('Y-m-d H:m:s');
			$oid = $sess_data['user_details'][0]->iu_owner;

			$data = array(
				'ittxg_group_name' => $name,
				'ittxg_owner' => $oid);
			$this->db->where('ittxg_id', $tid);
			$this->db->update('i_tax_group', $data);
			
			$this->db->where('itxgc_tg_id', $tid);
			$this->db->delete('i_tax_group_collection');

			for ($i=0; $i < count($taxes) ; $i++) { 
				$tname = $taxes[$i];
				$query = $this->db->query("SELECT * FROM i_taxes WHERE itx_name = '$tname' AND itx_owner = '$oid'");
				$result = $query->result();

				if(count($result) > 0) {
					$txid = $result[0]->itx_id;
				} else {
					$data = array(
						'itx_name' => $tname,
						'itx_owner' => $oid);
					$this->db->insert('i_taxes', $data);
					$txid = $this->db->insert_id();
				}

				$data1 = array(
					'itxgc_tg_id' => $tid,
					'itxgc_tx_id' => $txid );
				$this->db->insert('i_tax_group_collection', $data1);
			}

			
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}

	public function delete_tax_group($prid) {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {

			$this->db->where('ittxg_id', $prid);
			$this->db->delete('i_tax_group');

			$this->db->where('itxgc_tg_id', $prid);
			$this->db->delete('i_tax_group_collection');



			redirect(base_url().'distributors/Products/tax_group');
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}


}