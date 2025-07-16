<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {

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

		$categories = array();

		array_push($categories, array('id' => '1', 'p' => '4 PCS', 'type' => "Curtain Regular Full", 'img' => ["C1IMG1.jpg", "C1IMG2.jpg", "C1IMG3.jpg"]));
		array_push($categories, array('id' => '3', 'p' => '4 PCS', 'type' => "Curtain Zipper Full", 'img' => ["C2IMG1.jpg", "C2IMG2.jpg"]));
		array_push($categories, array('id' => '5', 'p' => '4 PCS', 'type' => "Curtain Medium Half Silver", 'img' => ["C3IMG1.jpg", "C3IMG2.jpg", "C3IMG3.jpg"]));
		array_push($categories, array('id' => '7', 'p' => '4 PCS', 'type' => "Curtain Heavy Half Gold", 'img' => ["C3IMG1.jpg", "C3IMG2.jpg", "C3IMG3.jpg"]));
		array_push($categories, array('id' => '2', 'p' => '6 PCS', 'type' => "Curtain Regular Full", 'img' => ["C1IMG1.jpg", "C1IMG2.jpg", "C1IMG3.jpg"]));
		array_push($categories, array('id' => '4', 'p' => '6 PCS', 'type' => "Curtain Zipper Full", 'img' => ["C2IMG1.jpg", "C2IMG2.jpg"]));
		array_push($categories, array('id' => '6', 'p' => '6 PCS', 'type' => "Curtain Medium Half Silver", 'img' => ["C3IMG1.jpg", "C3IMG2.jpg", "C3IMG3.jpg"]));
		array_push($categories, array('id' => '8', 'p' => '6 PCS', 'type' => "Curtain Heavy Half Gold", 'img' => ["C3IMG1.jpg", "C3IMG2.jpg", "C3IMG3.jpg"]));

		$query = $this->db->query("SELECT * FROM tmp_prod");
		$result = $query->result();
        
        $this->db->truncate('i_products');
        $this->db->truncate('i_p_images');
        
        
		for ($i=0; $i < count($result) ; $i++) { 
			for ($j=0; $j < count($categories); $j++) { 
		        if($categories[$j]['p'] == $result[$i]->tp_unit) {
		            $tmpnm = $categories[$j]['type'].' '.$result[$i]->tp_unit.' '.$result[$i]->tp_name.' '.$result[$i]->tp_code;
    					$data = array(
    						'ip_name' => $tmpnm,
    						'ip_image' => $categories[$j]['img'][0],
    						'ip_hsn_code' => '87089900',
    						'ip_unit' => $result[$i]->tp_unit,
    						'ip_owner' => '1',
    						'ip_category' => $categories[$j]['id'],
    						'ip_alias' => $categories[$j]['type']
    					);
    
    					$this->db->insert('i_products', $data);
    					$ptid = $this->db->insert_id();
    
    					$catim = $categories[$j]['img'];
    					for ($k=0; $k < count($catim); $k++) {
    						$sourcePath = $this->config->item('document_rt')."assets/photo/".$catim[$k];
    						echo "Source:".$sourcePath." - ";
    						$upload_dir = $this->config->item('document_rt')."assets/uploads/1/".$ptid.'/';
    						echo "Destination: ".$upload_dir.$catim[$k]."<br>";
    						if(!file_exists($upload_dir)) {
    							mkdir($upload_dir, 0777, true);
    						}
    						$img_path = "";
    						if (is_dir($upload_dir) && is_writable($upload_dir)) {
    							$targetPath = $upload_dir.$catim[$k]; // Target path where file is to be stored
    							copy($sourcePath,$targetPath) ; // Moving Uploaded file		
    							$img_path = $catim[$k];
    						}
    
    
    						$secdata = array(
    							'ipi_p_id' => $ptid,
    							'ipi_img' => $catim[$k],
    							'ipi_owner' => '1'
    						);
    						$this->db->insert('i_p_images', $secdata);
    					}       
		        }
			}	
		}
	}
	
	public function index2() {

		$categories = array();

		array_push($categories, array('id' => '11', 'type' => "Footstep Blue", 'img' => ["F1B1.jpg", "F1B2.jpg"]));
		array_push($categories, array('id' => '10', 'type' => "Footstep White", 'img' => ["F2W1.jpg", "F2W2.jpg"]));
		array_push($categories, array('id' => '12', 'type' => "Footstep Rainbow", 'img' => ["F3R1.png", "F3R2.png", "F3R3.png", "F3R4.png"]));
		
		$query = $this->db->query("SELECT * FROM tmp_prod_2");
		$result = $query->result();
        
        // $this->db->truncate('i_products');
        // $this->db->truncate('i_p_images');
        
        
		for ($i=0; $i < count($result) ; $i++) { 
			for ($j=0; $j < count($categories); $j++) { 
	            $tmpnm = $categories[$j]['type'].' '.$result[$i]->tp_unit.' '.$result[$i]->tp_name;
				$data = array(
					'ip_name' => $tmpnm,
					'ip_image' => $categories[$j]['img'][0],
					'ip_hsn_code' => '87089900',
					'ip_unit' => $result[$i]->tp_unit,
					'ip_owner' => '1',
					'ip_category' => $categories[$j]['id'],
					'ip_alias' => $categories[$j]['type']
				);

				$this->db->insert('i_products', $data);
				$ptid = $this->db->insert_id();

				$catim = $categories[$j]['img'];
				for ($k=0; $k < count($catim); $k++) {
					$sourcePath = $this->config->item('document_rt')."assets/photo/".$catim[$k];
					echo "Source:".$sourcePath." - ";
					$upload_dir = $this->config->item('document_rt')."assets/uploads/1/".$ptid.'/';
					echo "Destination: ".$upload_dir.$catim[$k]."<br>";
					if(!file_exists($upload_dir)) {
						mkdir($upload_dir, 0777, true);
					}
					$img_path = "";
					if (is_dir($upload_dir) && is_writable($upload_dir)) {
						$targetPath = $upload_dir.$catim[$k]; // Target path where file is to be stored
						copy($sourcePath,$targetPath) ; // Moving Uploaded file		
						$img_path = $catim[$k];
					}


					$secdata = array(
						'ipi_p_id' => $ptid,
						'ipi_img' => $catim[$k],
						'ipi_owner' => '1'
					);
					$this->db->insert('i_p_images', $secdata);
				}       
			}	
		}
	}
	
	public function index3() {

		$categories = array();

		array_push($categories, array('id' => '13', 'type' => "Floor Laminate Small", 'tp' => 'SMALL', 'img' => ["L6.png", "L5.png","L4.png","L3.png","L2.png","L1.png"]));
		array_push($categories, array('id' => '14', 'type' => "Floor Laminate Big", 'tp' => 'BIG', 'img' => ["L6.png", "L5.png","L4.png","L3.png","L2.png","L1.png"]));
		
		$query = $this->db->query("SELECT * FROM tmp_prod_3");
		$result = $query->result();
        
        // $this->db->truncate('i_products');
        // $this->db->truncate('i_p_images');
        
        
		for ($i=0; $i < count($result) ; $i++) { 
		    if($result[$i]->tp_black == "1") {
		        for ($j=0; $j < count($categories); $j++) { 
		            if($categories[$j]['tp'] == $result[$i]->tp_type) {
    		            $tmpnm = $categories[$j]['type'].' '.$result[$i]->tp_name.' Black';
        				$data = array(
        					'ip_name' => $tmpnm,
        					'ip_image' => $categories[$j]['img'][0],
        					'ip_hsn_code' => '87089900',
        					'ip_unit' => $result[$i]->tp_unit,
        					'ip_owner' => '1',
        					'ip_category' => $categories[$j]['id'],
        					'ip_alias' => $categories[$j]['type']
        				);
        
        				$this->db->insert('i_products', $data);
        				$ptid = $this->db->insert_id();
        
        				$catim = $categories[$j]['img'];
        				
        				$er = array("0","5","4");
        				for ($k=0; $k < count($er); $k++) {
        				    
        				    
        					$sourcePath = $this->config->item('document_rt')."assets/photo/".$catim[$er[$k]];
        					echo "Source:".$sourcePath." - ";
        					$upload_dir = $this->config->item('document_rt')."assets/uploads/1/".$ptid.'/';
        					echo "Destination: ".$upload_dir.$catim[$k]."<br>";
        					if(!file_exists($upload_dir)) {
        						mkdir($upload_dir, 0777, true);
        					}
        					$img_path = "";
        					if (is_dir($upload_dir) && is_writable($upload_dir)) {
        						$targetPath = $upload_dir.$catim[$er[$k]]; // Target path where file is to be stored
        						copy($sourcePath,$targetPath) ; // Moving Uploaded file		
        						$img_path = $catim[$er[$k]];
        					}
        
        
        					$secdata = array(
        						'ipi_p_id' => $ptid,
        						'ipi_img' => $catim[$er[$k]],
        						'ipi_owner' => '1'
        					);
        					$this->db->insert('i_p_images', $secdata);
        				}   
		            }
    			}
		    }
		    if($result[$i]->tp_beige == "1") {
		        for ($j=0; $j < count($categories); $j++) { 
    	            if($categories[$j]['tp'] == $result[$i]->tp_type) {
    	                $tmpnm = $categories[$j]['type'].' '.$result[$i]->tp_name.' Beige';
        				$data = array(
        					'ip_name' => $tmpnm,
        					'ip_image' => $categories[$j]['img'][0],
        					'ip_hsn_code' => '87089900',
        					'ip_unit' => $result[$i]->tp_unit,
        					'ip_owner' => '1',
        					'ip_category' => $categories[$j]['id'],
        					'ip_alias' => $categories[$j]['type']
        				);
        
        				$this->db->insert('i_products', $data);
        				$ptid = $this->db->insert_id();
        
        				$catim = $categories[$j]['img'];
        				
        				$er = array("2","5","4");
        				for ($k=0; $k < count($er); $k++) {
        				    
        				    
        					$sourcePath = $this->config->item('document_rt')."assets/photo/".$catim[$er[$k]];
        					echo "Source:".$sourcePath." - ";
        					$upload_dir = $this->config->item('document_rt')."assets/uploads/1/".$ptid.'/';
        					echo "Destination: ".$upload_dir.$catim[$k]."<br>";
        					if(!file_exists($upload_dir)) {
        						mkdir($upload_dir, 0777, true);
        					}
        					$img_path = "";
        					if (is_dir($upload_dir) && is_writable($upload_dir)) {
        						$targetPath = $upload_dir.$catim[$er[$k]]; // Target path where file is to be stored
        						copy($sourcePath,$targetPath) ; // Moving Uploaded file		
        						$img_path = $catim[$er[$k]];
        					}
        
        
        					$secdata = array(
        						'ipi_p_id' => $ptid,
        						'ipi_img' => $catim[$er[$k]],
        						'ipi_owner' => '1'
        					);
        					$this->db->insert('i_p_images', $secdata);
        				}
    	            }
    			}
		    }
		    if($result[$i]->tp_coco == "1") {
		        for ($j=0; $j < count($categories); $j++) { 
    	            if($categories[$j]['tp'] == $result[$i]->tp_type) {
    	                $tmpnm = $categories[$j]['type'].' '.$result[$i]->tp_name.' Coco';
        				$data = array(
        					'ip_name' => $tmpnm,
        					'ip_image' => $categories[$j]['img'][0],
        					'ip_hsn_code' => '87089900',
        					'ip_unit' => $result[$i]->tp_unit,
        					'ip_owner' => '1',
        					'ip_category' => $categories[$j]['id'],
        					'ip_alias' => $categories[$j]['type']
        				);
        
        				$this->db->insert('i_products', $data);
        				$ptid = $this->db->insert_id();
        
        				$catim = $categories[$j]['img'];
        				
        				$er = array("1","5","4");
        				for ($k=0; $k < count($er); $k++) {
        				    
        				    
        					$sourcePath = $this->config->item('document_rt')."assets/photo/".$catim[$er[$k]];
        					echo "Source:".$sourcePath." - ";
        					$upload_dir = $this->config->item('document_rt')."assets/uploads/1/".$ptid.'/';
        					echo "Destination: ".$upload_dir.$catim[$k]."<br>";
        					if(!file_exists($upload_dir)) {
        						mkdir($upload_dir, 0777, true);
        					}
        					$img_path = "";
        					if (is_dir($upload_dir) && is_writable($upload_dir)) {
        						$targetPath = $upload_dir.$catim[$er[$k]]; // Target path where file is to be stored
        						copy($sourcePath,$targetPath) ; // Moving Uploaded file		
        						$img_path = $catim[$er[$k]];
        					}
        
        
        					$secdata = array(
        						'ipi_p_id' => $ptid,
        						'ipi_img' => $catim[$er[$k]],
        						'ipi_owner' => '1'
        					);
        					$this->db->insert('i_p_images', $secdata);
        				}
    	            }
    			}
		    }
		    if($result[$i]->tp_tgrey == "1") {
		        for ($j=0; $j < count($categories); $j++) { 
    	            if($categories[$j]['tp'] == $result[$i]->tp_type) {
    	                $tmpnm = $categories[$j]['type'].' '.$result[$i]->tp_name.' TGrey';
        				$data = array(
        					'ip_name' => $tmpnm,
        					'ip_image' => $categories[$j]['img'][0],
        					'ip_hsn_code' => '87089900',
        					'ip_unit' => $result[$i]->tp_unit,
        					'ip_owner' => '1',
        					'ip_category' => $categories[$j]['id'],
        					'ip_alias' => $categories[$j]['type']
        				);
        
        				$this->db->insert('i_products', $data);
        				$ptid = $this->db->insert_id();
        
        				$catim = $categories[$j]['img'];
        				
        				$er = array("3","5","4");
        				for ($k=0; $k < count($er); $k++) {
        				    
        				    
        					$sourcePath = $this->config->item('document_rt')."assets/photo/".$catim[$er[$k]];
        					echo "Source:".$sourcePath." - ";
        					$upload_dir = $this->config->item('document_rt')."assets/uploads/1/".$ptid.'/';
        					echo "Destination: ".$upload_dir.$catim[$k]."<br>";
        					if(!file_exists($upload_dir)) {
        						mkdir($upload_dir, 0777, true);
        					}
        					$img_path = "";
        					if (is_dir($upload_dir) && is_writable($upload_dir)) {
        						$targetPath = $upload_dir.$catim[$er[$k]]; // Target path where file is to be stored
        						copy($sourcePath,$targetPath) ; // Moving Uploaded file		
        						$img_path = $catim[$er[$k]];
        					}
        
        
        					$secdata = array(
        						'ipi_p_id' => $ptid,
        						'ipi_img' => $catim[$er[$k]],
        						'ipi_owner' => '1'
        					);
        					$this->db->insert('i_p_images', $secdata);
        				}       
    	            }
    			}
		    }
		    
		}
	}
	
	public function index4() {

		$categories = array();

		array_push($categories, array('id' => '9', 'p' => '4 PCS', 'type' => "Curtain Dikky", 'img' => ["C4IMG1.jpg"]));
		
		$query = $this->db->query("SELECT * FROM `tmp_prod_4` WHERE tp_unit = '6 PCS'");
		$result = $query->result();
        
        for ($i=0; $i < count($result) ; $i++) { 
			for ($j=0; $j < count($categories); $j++) { 
	            $tmpnm = $categories[$j]['type'].' '.$result[$i]->tp_name.' '.$result[$i]->tp_code;
				$data = array(
					'ip_name' => $tmpnm,
					'ip_image' => $categories[$j]['img'][0],
					'ip_hsn_code' => '87089900',
					'ip_unit' => $result[$i]->tp_unit,
					'ip_owner' => '1',
					'ip_category' => $categories[$j]['id'],
					'ip_alias' => $categories[$j]['type']
				);

				$this->db->insert('i_products', $data);
				$ptid = $this->db->insert_id();

				$catim = $categories[$j]['img'];
				for ($k=0; $k < count($catim); $k++) {
					$sourcePath = $this->config->item('document_rt')."assets/photo/".$catim[$k];
					echo "Source:".$sourcePath." - ";
					$upload_dir = $this->config->item('document_rt')."assets/uploads/1/".$ptid.'/';
					echo "Destination: ".$upload_dir.$catim[$k]."<br>";
					if(!file_exists($upload_dir)) {
						mkdir($upload_dir, 0777, true);
					}
					$img_path = "";
					if (is_dir($upload_dir) && is_writable($upload_dir)) {
						$targetPath = $upload_dir.$catim[$k]; // Target path where file is to be stored
						copy($sourcePath,$targetPath) ; // Moving Uploaded file		
						$img_path = $catim[$k];
					}


					$secdata = array(
						'ipi_p_id' => $ptid,
						'ipi_img' => $catim[$k],
						'ipi_owner' => '1'
					);
					$this->db->insert('i_p_images', $secdata);
				}
			}	
		}
	}
	
	public function index5() {
	    for($i=0;$i<67;$i++) {
	        $this->db->insert('i_txn_nums', array('itn_type' => 'Orders', 'itn_num' => $i, 'itn_owner' => '1', 'itn_date' => '2018-04-14' ));
	    }
	    
	    for($i=0;$i<64;$i++) {
	        $this->db->insert('i_txn_nums', array('itn_type' => 'Delivery', 'itn_num' => $i, 'itn_owner' => '1', 'itn_date' => '2018-04-14' ));
	    }
	    
	    for($i=0;$i<4;$i++) {
	        $this->db->insert('i_txn_nums', array('itn_type' => 'Sale', 'itn_num' => $i, 'itn_owner' => '1', 'itn_date' => '2018-04-14' ));
	    }
	}
	
	public function index6() {
	    $this->db->where("itn_date BETWEEN '2018-04-12' AND '2018-04-15'");
	    $query = $this->db->get('i_txn_nums');
	    print_r($query->result());
	}
	
	public function load_txns_journals() {
	    //id, txnno, details, date, type, credit, debit
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		    $query = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id WHERE a.it_owner='$oid'");
		    $result = $query->result();
		    $data = [];
		    for($i=0;$i<count($result);$i++) {
		        $credit = ["Sale", "Credit Note", "Delivery","Orders"];
		        if(in_array($result[$i]->it_type, $credit)) {
		            array_push($data, array('id' => $result[$i]->it_id, 'txnid' => $result[$i]->it_txn_no, 'details' => $result[$i]->it_type.' '.$result[$i]->ic_name, 'date' => $result[$i]->it_date, 'type' => $result[$i]->it_type, 'credit' => $result[$i]->it_amount, 'debit' => ''));
		        } else {
		            array_push($data, array('id' => $result[$i]->it_id, 'txnid' => $result[$i]->it_txn_no, 'details' => $result[$i]->it_type.' '.$result[$i]->ic_name, 'date' => $result[$i]->it_date, 'type' => $result[$i]->it_type, 'credit' => '', 'debit' => $result[$i]->it_amount));
		        }
		        
		        $txid=$result[$i]->it_id;
		        $que1=$this->db->query("SELECT * FROM i_txns_details AS a LEFT JOIN i_products AS b ON a.itd_p_id=b.ip_id WHERE a.itp_owner='$oid' AND a.itd_t_id='$txid'");
		        $res1=$que1->result();
		        
		        for($j=0;$j<count($res1);$j++) {
		            if(in_array($result[$i]->it_type, $credit)) {
    		            array_push($data, array('id' => $res1[$j]->itd_id, 'txnid' => $result[$i]->it_txn_no, 'details' => 'Products '.$res1[$j]->ip_name.' Qty:'.$res1[$j]->itp_qty, 'date' => $result[$i]->it_date, 'type' => $result[$i]->it_type, 'credit' => $res1[$j]->itp_value, 'debit' => ''));
    		        } else {
    		            array_push($data, array('id' => $res1[$j]->itd_id, 'txnid' => $result[$i]->it_txn_no, 'details' => 'Products '.$res1[$j]->ip_name.' Qty:'.$res1[$j]->itp_qty, 'date' => $result[$i]->it_date, 'type' => $result[$i]->it_type, 'credit' => '', 'debit' => $res1[$j]->itp_value));
    		        }
		        }
		        
		        $que2=$this->db->query("SELECT * FROM i_txn_product_taxes AS m LEFT JOIN i_taxes AS a ON m.itpt_tx_id=a.itx_id LEFT JOIN `i_tax_group_collection` AS b ON a.itx_id=b.itxgc_tx_id LEFT JOIN i_tax_group AS c ON b.itxgc_tg_id=c.ittxg_id WHERE a.itx_owner='$oid'");
		        $res2=$que2->result();
		        
		        for($k=0;$k < count($res2);$k++) {
		            if(in_array($result[$i]->it_type, $credit)) {
    		            array_push($data, array('id' => $res2[$k]->itpt_id, 'txnid' => $result[$i]->it_txn_no, 'details' => 'Tax '.$res2[$k]->itx_name, 'date' => $result[$i]->it_date, 'type' => $result[$i]->it_type, 'credit' => $res2[$k]->itpt_t_amount, 'debit' => ''));
    		        } else {
    		            array_push($data, array('id' => $res2[$k]->itpt_id, 'txnid' => $result[$i]->it_txn_no, 'details' => 'Tax '.$res2[$k]->itx_name, 'date' => $result[$i]->it_date, 'type' => $result[$i]->it_type, 'credit' => '', 'debit' => $res2[$k]->itpt_t_amount));
    		        }
		        }
		    }
		    $dt = date('Y-m-d H:m:s');
		    for($i=0;$i<count($data);$i++) {
		        $upd = array(
		            'iaj_type' => $data[$i]['type'],
		            'iaj_link_type' => $data[$i]['type'],
		            'iaj_link_id' => $data[$i]['id'],
		            'iaj_status' => 'pending',
		            'iaj_date' => $data[$i]['date'],
		            'iaj_description' => $data[$i]['details'],
		            'iaj_credit' => $data[$i]['credit'],
		            'iaj_debit' => $data[$i]['debit'],
		            'iaj_owner' => $oid,
		            'iaj_created' => $dt
		            );
		        $this->db->insert('i_acc_journal', $upd);
		    }
		}    
	}
	
	public function ledger2() {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		    $query = $this->db->query("SELECT * FROM i_acc_journal WHERE iaj_owner='$oid'");
		    $data['ledger'] = $query->result();
		    
		    $ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Add Ledger Filter";
			$ert['search'] = "false";
			$ert['search_placeholder'] = "Search Vendors";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('ledger_2', $data);
		}
	}
	
	public function filter_ledger_data() {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$filter = $this->input->post('filter')[0];
			$t="SELECT * FROM i_acc_journal WHERE iaj_owner='$oid' ";
		    if($filter['date']!="") $t.=" AND iaj_date LIKE '%".$filter['date']."%'";
		    if($filter['desc']!="") $t.=" AND iaj_description LIKE '%".$filter['desc']."%'";
		    if($filter['credit']!="")  $t.=" AND iaj_credit LIKE '%".$filter['credit']."%'";
		    if($filter['debit']!="") $t.=" AND iaj_debit LIKE '%".$filter['debit']."%'";
			
			$query = $this->db->query($t);
			print_r(json_encode($query->result()));
		}
	}
	
	public function ledger_create() {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$nm = $this->input->post('ledger');
			$query = $this->db->query("SELECT * FROM i_acc_ledger WHERE ial_name = '$nm' AND ial_owner='$oid'");
			if(count($query->result()) > 0) {
			    $lid = $query->result()[0]->ial_id;
			} else {
			    $this->db->insert('i_acc_ledger', array('ial_name' => $nm, 'ial_owner' => $oid));
		        $lid = $this->db->insert_id();
			}
		    
		    $filter = $this->input->post('filter')[0];
		    print_r($filter);
		    if($filter['date']!="") $this->db->insert('i_acc_ledger_filter', array('ialf_ledger' => $lid, 'ialf_owner' => $oid, 'ialf_filter' => "iaj_date LIKE '%".$filter['date']."%'"));
		    if($filter['desc']!="") $this->db->insert('i_acc_ledger_filter', array('ialf_ledger' => $lid, 'ialf_owner' => $oid, 'ialf_filter' => "iaj_description LIKE '%".$filter['desc']."%'"));
		    if($filter['credit']!="") $this->db->insert('i_acc_ledger_filter', array('ialf_ledger' => $lid, 'ialf_owner' => $oid, 'ialf_filter' => "iaj_credit = '".$filter['credit']."'"));
		    if($filter['debit']!="") $this->db->insert('i_acc_ledger_filter', array('ialf_ledger' => $lid, 'ialf_owner' => $oid, 'ialf_filter' => "iaj_debit = '".$filter['debit']."'"));
		}
	}
	
	public function show_ledgers() {
    	$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		    
    	    $query = $this->db->query("SELECT * FROM i_acc_ledger WHERE ial_owner='$oid'");
    	    $result = $query->result();
    	    $data['ledgers'] = $result;
    	    
    	    $ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Ledger";
			$ert['search'] = "false";
			$ert['search_placeholder'] = "Search Vendors";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('view_ledger', $data);
	    }
	}
	
	public function get_ledger_data() {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		    $ledger = $this->input->post('lid');
		    $query=$this->db->query("SELECT * FROM i_acc_ledger WHERE ial_name = '$ledger' AND ial_owner='$oid'");
		    $result=$query->result();
		    $lid = $result[0]->ial_id;
		    
    	    $query = $this->db->query("SELECT * FROM i_acc_ledger_filter WHERE ialf_ledger='$lid'");
    	    $result = $query->result();
    	    
    	    $data = [];
    	    for($i=0;$i<count($result);$i++) {
    	        array_push($data, $result[$i]->ialf_filter);
    	    }
    	    $whr = implode(' AND ', $data);
    	    $query = $this->db->query("SELECT * FROM i_acc_journal WHERE ".$whr);
    	    print_r(json_encode($query->result()));
	    }
	}
	
	public function tester() {
	    $this->load->view('tester');
	}
	
	public function remove_duplicate_products() {
	    $query = $this->db->query("SELECT COUNT(ip_id), ip_id, ip_name FROM `i_products` GROUP BY ip_name ORDER BY COUNT(ip_id) DESC");
	    $result = $query->result();
	    $pname="";$str="";
	    for($i=0;$i<count($result);$i++) {
	        $pname = $result[$i]->ip_name;
	        $que1 = $this->db->query("SELECT COUNT(ip_id), ip_id FROM i_txns_details AS a LEFT JOIN i_products AS b ON a.itd_p_id=b.ip_id WHERE ip_name='$pname' GROUP BY ip_id");
	        $res1 = $que1->result();
	        
	        $pid = 0;
	        for($j=0;$j<count($res1);$j++) {
	            if($j==0) {
	                $pid = $res1[$j]->ip_id;
	            } else {
	                $str .= 'UPDATE itd_p_id = '.$pid.' AND WHERE = '.$res1[$j]->ip_id.'<br>';
	                $this->db->where(array('itd_p_id' => $res1[$j]->ip_id));
	                $this->db->update('i_txns_details', array('itd_p_id' => $pid));
	            }
	        }
	        
	        $que2 = $this->db->query("SELECT * FROM `i_products` WHERE ip_name ='$pname'");
	        $res2 = $que2->result();
	        
	        for($k=0;$k<count($res2);$k++) {
	            if($res2[$k]->ip_id != $pid) {
	                $str.='DELETE ip_id = '.$res2[$k]->ip_id.'<br>';
	                $this->db->where(array('ip_id' => $res2[$k]->ip_id));
	                $this->db->delete('i_products');
	            }
	        }
	    }
	    echo $pname.'<br><br>'.$str;
	}
	
	public function remove_duplicate_08012019() {
	    $query = $this->db->query("SELECT ip_id, ip_name, ip_description, ip_alias, ip_unit, GROUP_CONCAT(ip_description) AS desc_values, COUNT(ip_description) AS desc_count FROM `i_products` WHERE ip_description LIKE '%ZIP%' GROUP BY ip_description HAVING COUNT(*) > 1");
	    $result = $query->result();
	    
	    for($i=0;$i<count($result); $i++) {
	        $desc = $result[$i]->ip_description;
	        
	        $q1 = $this->db->query("SELECT * FROM i_products WHERE ip_description='$desc'");
	        $r1 = $q1->result();
	        
	        for($j=0;$j<count($r1);$j++) {
	            if($r1[$j]->ip_unit == $result[$i]->ip_unit && $r1[count($r1) - 1]->ip_unit == $result[$i]->ip_unit) {
	                echo "Product: ".$r1[$j]->ip_name."<br>";
    	            echo "Code: ".$r1[$j]->ip_description."<br>";
    	            echo "Units: ".$r1[$j]->ip_unit."<br>";
    	            
    	            $pid = $r1[$j]->ip_id;
    	            $q2 = $this->db->query("SELECT * FROM `i_txns_details` WHERE itd_p_id='$pid'");
    	            $r2 = $q2->result();
    	            echo "Count of Txns: ".count($r2)."<br>";
    	            
    	            $pid2=$r1[count($r1) - 1]->ip_id;
    	            $q3 = $this->db->query("SELECT * FROM `i_txns_details` WHERE itd_p_id='$pid2'");
    	            $r3 = $q3->result();
    	            echo "Count of Txns Last: ".count($r3)."<br>";
    	            
    	            if(count($r3) > 0) {
    	                echo "UPDATE <u>".$r1[$j]->ip_id."</u> - ".$r1[$j]->ip_name." WITH <u>".$r1[count($r1) - 1]->ip_id."</u> - ".$r1[count($r1) - 1]->ip_name."<br>";
    	            }
    	            
	            } else {
	                echo "Skip: ".$r1[$j]->ip_name." & ".$r1[$j]->ip_description." & ".$r1[$j]->ip_unit." <br>";
	            }
	        }
	        echo "__________<br>";
	    }
	}
}
?>