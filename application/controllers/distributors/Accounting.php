<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accounting extends CI_Controller {

	public function __construct()	{
		parent:: __construct();
		$this->load->database();
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->dbforge();
		$this->load->model('distributors/Accounting_Model','acc_model');
	}
	
	public function index() {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		
		   	$uid = $sess_data['user_details'][0]->iu_id;
		   	$query = $this->db->query("SELECT * FROM i_ac_ledgers WHERE iacl_owner='$oid' AND iacl_starred='1'");
			$data['ledgers'] = $query->result();
			$data['oid'] = $oid;
			
			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Accounting";
			$ert['search'] = "false";
			$ert['search_placeholder'] = "Search Ledgers";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/accounting/accounting', $data);
		} else {
		    redirect(base_url().'distributors/Account/login');
		}
	}
	
	public function journal_entries() {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$fy = $sess_data['fy'][0]->ify_start_date;
			$ty = $sess_data['fy'][0]->ify_end_date;
			
		   	$uid = $sess_data['user_details'][0]->iu_id;
		   	
		   	$query = $this->db->query("SELECT a.iacje_id AS id, b.iacl_name AS account_from, c.iacl_name AS account_to, a.iacje_description AS account_description, a.iacje_date AS date,  a.iacje_amount AS amount FROM i_ac_journal_entries AS a LEFT JOIN i_ac_ledgers AS b ON a.iacje_from=b.iacl_id LEFT JOIN i_ac_ledgers AS c ON a.iacje_to=c.iacl_id WHERE a.iacje_owner='$oid' AND a.iacje_date BETWEEN '$fy' AND '$ty' ORDER BY iacje_date, iacje_id");
		   	$data['txn'] = $query->result();
		   	
		   	$query = $this->db->query("SELECT * FROM i_ac_ledgers WHERE iacl_owner='$oid'");
		   	$data['ledgers'] = $query->result();
		   	
		   	$data['oid'] = $oid;
			
			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Journal Entries";
			$ert['search'] = "no_text";
			$ert['search_placeholder'] = "Search Vendors";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/accounting/journal_entries', $data);
		} else {
		    redirect(base_url().'distributors/Account/login');
		}
	}

	public function journal_entry_details($ref,$jid=null,$refid=null) {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$fy = $sess_data['fy'][0]->ify_start_date;
			$ty = $sess_data['fy'][0]->ify_end_date;
			
		   	$uid = $sess_data['user_details'][0]->iu_id;

		   	if ($jid!=null && $jid!='null') {
		   		$query = $this->db->query("SELECT  a.iacje_id AS id, a.iacje_date AS date, b.iacl_name AS account_from, a.iacje_from AS from_id, c.iacl_name AS account_to, a.iacje_to AS to_id, a.iacje_description AS account_description, a.iacje_amount AS amount FROM i_ac_journal_entries AS a LEFT JOIN i_ac_ledgers AS b ON a.iacje_from=b.iacl_id LEFT JOIN i_ac_ledgers AS c ON a.iacje_to=c.iacl_id  WHERE a.iacje_id = '$jid' AND a.iacje_owner='$oid'");
			   	$data['detail'] = $query->result();
			   	$ert['title'] = "Edit Journal Entry";
		   	} else {
		   		$ert['title'] = "Add Journal Entry";
		   	}
		   	
		   	$query = $this->db->query("SELECT * FROM i_ac_ledgers WHERE iacl_owner='$oid'");
		   	$data['ledgers'] = $query->result();
		   	
		   	$data['oid'] = $oid;
			$data['jid'] = $jid;
			$data['ref'] = $ref;
			$data['refid'] = $refid;
			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			
			$ert['search'] = "false";
			$ert['search_placeholder'] = "Search Vendors";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/accounting/add_filter_journal_entry', $data);
		
		} else {
		    redirect(base_url().'distributors/Account/login');
		}
	}
	
	public function filter_journal_entries() {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		    $uid = $sess_data['user_details'][0]->iu_id;
		   	$fy = $sess_data['fy'][0]->ify_start_date;
			$ty = $sess_data['fy'][0]->ify_end_date;
			
		   	$query = $this->db->query("SELECT a.iacje_id AS id, a.iacje_date AS date, b.iacl_name AS account_from, a.iacje_from AS from_id, c.iacl_name AS account_to, a.iacje_to AS to_id, a.iacje_description AS account_description, a.iacje_amount AS amount FROM `i_ac_journal_entries` AS a LEFT JOIN i_ac_ledgers AS b ON a.iacje_from=b.iacl_id LEFT JOIN i_ac_ledgers AS c ON a.iacje_to=c.iacl_id  WHERE a.iacje_date BETWEEN '$fy' AND '$ty' AND a.iacje_owner='$oid' AND b.iacl_name LIKE '%".$this->input->post('f')."%' AND c.iacl_name LIKE '%".$this->input->post('t')."%' AND a.iacje_date BETWEEN '".$this->input->post('f_dt')."' AND '".$this->input->post('t_dt')."' AND a.iacje_description LIKE '%".$this->input->post('d')."%' ORDER BY a.iacje_date DESC");
		   	print_r(json_encode($query->result()));
		} else {
		    redirect(base_url().'distributors/Account/login');
		}
	}

	public function delete_journal_entry($jid, $type=null, $typeid=null) {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		    $uid = $sess_data['user_details'][0]->iu_id;
		   	$fy = $sess_data['fy'][0]->ify_start_date;
			$ty = $sess_data['fy'][0]->ify_end_date;
			
			$this->db->where(array('iacje_id' => $jid, 'iacje_owner' => $oid));
			$this->db->delete('i_ac_journal_entries');

			if ($type==null) {
				$query = $this->db->query("SELECT a.iacje_id AS id, a.iacje_date AS date, b.iacl_name AS account_from, a.iacje_from AS from_id, c.iacl_name AS account_to, a.iacje_to AS to_id, a.iacje_description AS account_description, a.iacje_amount AS amount FROM `i_ac_journal_entries` AS a LEFT JOIN i_ac_ledgers AS b ON a.iacje_from=b.iacl_id LEFT JOIN i_ac_ledgers AS c ON a.iacje_to=c.iacl_id  WHERE a.iacje_date BETWEEN '$fy' AND '$ty' AND a.iacje_owner='$oid' ORDER BY a.iacje_date DESC");
		   		print_r(json_encode($query->result()));
			} else if($type == "l") {
			    if($typeid==null) {
			        $query = $this->db->query("SELECT a.iacje_id AS id, a.iacje_date AS date, b.iacl_name AS account_from, a.iacje_from AS from_id, c.iacl_name AS account_to, a.iacje_to AS to_id, a.iacje_description AS account_description, a.iacje_amount AS amount FROM `i_ac_journal_entries` AS a LEFT JOIN i_ac_ledgers AS b ON a.iacje_from=b.iacl_id LEFT JOIN i_ac_ledgers AS c ON a.iacje_to=c.iacl_id  WHERE a.iacje_owner='$oid' ORDER BY a.iacje_date DESC, a.iacje_id");    
			    } else {
			        $query = $this->db->query("SELECT a.iacje_id AS id, a.iacje_date AS date, b.iacl_name AS account_from, a.iacje_from AS from_id, c.iacl_name AS account_to, a.iacje_to AS to_id, a.iacje_description AS account_description, a.iacje_amount AS amount FROM `i_ac_journal_entries` AS a LEFT JOIN i_ac_ledgers AS b ON a.iacje_from=b.iacl_id LEFT JOIN i_ac_ledgers AS c ON a.iacje_to=c.iacl_id  WHERE a.iacje_from='$typeid' OR a.iacje_to='$typeid' AND a.iacje_owner='$oid' ORDER BY a.iacje_date DESC, a.iacje_id");
			    }
				print_r(json_encode($query->result()));
			} else if($type == "g") {
				// $query = $this->db->query("SELECT a.inaje_id AS id, a.inaje_date AS date, b.inal_ledger AS account_from, a.inaje_from AS from_id, c.inal_ledger AS account_to, a.inaje_to AS to_id, a.inaje_description AS account_description, a.inaje_amt AS amount FROM i_n_ac_journal_entries AS a LEFT JOIN i_n_ac_ledgers AS b ON a.inaje_from=b.inal_id LEFT JOIN i_n_ac_ledgers AS c ON a.inaje_to=c.inal_id WHERE a.inaje_from IN (SELECT inagl_l_id FROM i_n_ac_group_ledgers WHERE inagl_g_id='$typeid' AND inagl_owner='$oid' GROUP BY inagl_l_id) OR a.inaje_to IN (SELECT inagl_l_id FROM i_n_ac_group_ledgers WHERE inagl_g_id='$typeid' AND inagl_owner='$oid' GROUP BY inagl_l_id) AND a.inaje_owner='$oid'");
				// print_r(json_encode($query->result()));
			}
		   	
		} else {
		    redirect(base_url().'distributors/Account/login');
		}
	}
	
	public function edit_journal_entry($jid) {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		    
		   	$uid = $sess_data['user_details'][0]->iu_id;
		   	$query = $this->db->query("SELECT  a.inaje_id AS id, a.inaje_date AS date, b.inal_ledger AS account_from, a.inaje_from AS from_id, c.inal_ledger AS account_to, a.inaje_to AS to_id, a.inaje_description AS account_description, a.inaje_amt AS amount FROM i_n_ac_journal_entries AS a LEFT JOIN i_n_ac_ledgers AS b ON a.inaje_from=b.inal_id LEFT JOIN i_n_ac_ledgers AS c ON a.inaje_to=c.inal_id  WHERE a.inaje_id = '$jid' AND a.inaje_owner='$oid'");
		   	$data['detail'] = $query->result();
		   	$query = $this->db->query("SELECT * FROM i_n_ac_je_groups AS a LEFT JOIN i_n_ac_groups AS b ON a.inajg_g_id=b.inag_id WHERE a.inajg_je_id='$jid' AND a.inajg_owner='$oid'");
		   	$data['group'] = $query->result();
		   	print_r(json_encode($data));
		} else {
		    redirect(base_url().'distributors/Account/login');
		}
	} 
	
	public function record_journal_entry($type=null, $id=null) {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			$fy = $sess_data['fy'][0]->ify_start_date;
			$ty = $sess_data['fy'][0]->ify_end_date;
			
			$dt = date('Y-m-d H:m:s');
			
			$r = $this->input->post('r');
			$sel = $this->input->post('selected');
			
			for($i=0;$i<count($r);$i++) {
				if(isset($r[$i]['g'])) {
					$g = $r[$i]['g'];	
				} else {
					$g = [];
				}
			    
			    $f = $r[$i]['f'];
    			$t = $r[$i]['t'];
    			
    			if($f == null) {
    			    $fid = 0;
    			} else {
    			    $query = $this->db->query("SELECT * FROM i_ac_ledgers WHERE iacl_owner = '$oid' AND iacl_name='$f'");
        			$result = $query->result();
        			
        			if(count($result) > 0) {
        			    $fid = $result[0]->iacl_id;
        			} else {
        			    $data1 = array('iacl_name' => $f, 'iacl_owner' => $oid, 'iacl_created' => $dt, 'iacl_created_by' => $uid);
        			    $this->db->insert('i_ac_ledgers', $data1);
        			    $fid = $this->db->insert_id();
        			}
    			}
    			
    			if($t==null) {
    			    $tid=0;
    			} else {
    			    $query = $this->db->query("SELECT * FROM i_ac_ledgers WHERE iacl_owner = '$oid' AND iacl_name='$t'");
        			$result = $query->result();
        			
        			if(count($result) > 0) {
        				$tid = $result[0]->iacl_id;
        			} else {
        			    $data1 = array('iacl_name' => $t, 'iacl_owner' => $oid, 'iacl_created' => $dt, 'iacl_created_by' => $uid);
        			    $this->db->insert('i_ac_ledgers', $data1);
        			    $tid = $this->db->insert_id();
        			}
    			}
        			
    			
    			if($sel == "true") {
    			    $this->db->where(array('iacje_id' => $r[$i]['id'], 'iacje_owner' => $oid ));
    			    $this->db->delete('i_ac_journal_entries');
    			} 
    			
    			$data = array('iacje_date' => $r[$i]['dt'], 'iacje_from' => $fid, 'iacje_to' => $tid, 'iacje_description' => $r[$i]['d'], 'iacje_amount' => $r[$i]['a'], 'iacje_link_type' => 'Manual', 'iacje_link_id' => 0, 'iacje_owner' => $oid, 'iacje_created' => $dt, 'iacje_created_by' => $uid);
    			$this->db->insert('i_ac_journal_entries', $data);
			}
			
// 			if($type=="group") {
// 			    print_r(json_encode($this->acc_model->load_group_details($oid, $id)));    
// 			} else if($type=="ledger") {
// 				print_r(json_encode($this->acc_model->load_ledger_details($oid, $id, $fy, $ty)));    
// 			} else {
// 			    print_r(json_encode($this->acc_model->load_ledger_details($oid, null, $fy, $ty)));
// 			}
		} else {
		    redirect(base_url().'distributors/Account/login');
		}
	}
	
################# LEDGERS ########################################################

	public function ledgers() {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		
		   	$uid = $sess_data['user_details'][0]->iu_id;
		   	
		   	$query = $this->db->query("SELECT * FROM i_ac_ledgers WHERE iacl_owner = '$oid'");
			$data['ledgers'] = $query->result();
			$data['oid'] = $oid;
			
			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Ledgers";
			$ert['search'] = "true";
			$ert['search_placeholder'] = "Search Ledgers";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/accounting/ledgers', $data);
		} else {
		    redirect(base_url().'distributors/Account/login');
		}
	}
	
	public function search_ledgers() {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		   	$uid = $sess_data['user_details'][0]->iu_id;

		   	$l=$this->input->post('keywords');
		   	$query = $this->db->query("SELECT * FROM i_ac_ledgers WHERE iacl_name LIKE '%$l%' AND iacl_owner='$oid'");
		   	print_r(json_encode($query->result()));
		} else {
		    redirect(base_url().'distributors/Account/login');
		}
	}
	
	public function fetch_link_type($t) {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			
			$g=$this->input->get('term');
			
			if($t=="contact") {
			    $query = $this->db->query("SELECT ic_name AS name FROM i_contacts WHERE ic_owner='$oid' AND ic_name LIKE '%$g%' LIMIT 10");
			} else if($t=="tax") {
			    $query = $this->db->query("SELECT itx_name AS name FROM i_taxes WHERE itx_owner='$oid' AND itx_name LIKE '%$g%' LIMIT 10");
			} else if($t=="module") {
			    $query = $this->db->query("SELECT im_name AS name FROM i_modules WHERE im_name LIKE '%$g%' LIMIT 10");
			}
			$result = $query->result();
			$arr = [];
			for($i=0;$i<count($result);$i++) {
			    array_push($arr, array('label' => $result[$i]->name, 'value' => $result[$i]->name));
			}
			print_r(json_encode($arr));
		}
	}
	
	public function add_ledger() {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		
			$query = $this->db->query("SELECT * FROM i_ac_groups WHERE iacg_owner='$oid'");
			$data['groups'] = $query->result();

		   	$query=$this->db->query("SELECT inald_property FROM i_n_ac_l_details WHERE inald_owner='$oid' GROUP BY inald_property");
			$data['prop'] = $query->result();

			$uid = $sess_data['user_details'][0]->iu_id;
			$data['oid'] = $oid;
			
			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Add Ledgers";
			$ert['search'] = "false";
			$ert['search_placeholder'] = "Search Vendors";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/accounting/ledger_add', $data);
		
		} else {
		    redirect(base_url().'distributors/Account/login');
		}
	}
	
	public function edit_ledger($lid) {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		
		    $query = $this->db->query("SELECT * FROM i_ac_groups WHERE iacg_owner='$oid'");
			$data['groups'] = $query->result();

		   	$query=$this->db->query("SELECT a.iacl_id AS id, a.iacl_name AS ledger_name, a.iacl_link AS type, a.iacl_link_id AS link_id, b.ic_name AS contact, c.itx_name AS tax, e.im_name AS module, d.iacg_name AS grp FROM i_ac_ledgers AS a LEFT JOIN i_contacts AS b ON a.iacl_link_id=b.ic_id LEFT JOIN i_taxes AS c ON a.iacl_link_id=c.itx_id LEFT JOIN i_ac_groups AS d ON a.iacl_group_id=d.iacg_id LEFT JOIN i_modules AS e ON a.iacl_link_id=e.im_id WHERE a.iacl_id='$lid' AND a.iacl_owner='$oid'");
			$data['detail'] = $query->result();

			$query=$this->db->query("SELECT inald_property FROM i_n_ac_l_details WHERE inald_owner='$oid' GROUP BY inald_property");
			$data['prop'] = $query->result();

			$query=$this->db->query("SELECT * FROM i_n_ac_l_details WHERE inald_l_id='$lid' AND inald_owner='$oid'");
			$data['detail_prop'] = $query->result();

			$uid = $sess_data['user_details'][0]->iu_id;
			$data['oid'] = $oid;
			$data['lid'] = $lid;
			
			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Edit Ledger";
			$ert['search'] = "false";
			$ert['search_placeholder'] = "Search Vendors";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/accounting/ledger_add', $data);
		
		} else {
		    redirect(base_url().'distributors/Account/login');
		}
	}
	
	public function save_ledger($lid=null) {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			
		    $dt = date('Y-m-d H:i:s');
		    $g=$this->input->post('groups');
		    $query = $this->db->query("SELECT * FROM i_ac_groups WHERE iacg_name='$g' AND iacg_owner='$oid'");
		    $result = $query->result();
		    
		    $gid=0;
		    if(count($result) > 0) {
		        $gid=$result[0]->iacg_id;
		    }
		    $cname=$this->input->post('link_name');
		    $cid=0;
		    if($this->input->post('link_type') == 'contact') {
		        $q1 = $this->db->query("SELECT * FROM i_contacts WHERE ic_name='$cname' AND ic_owner='$oid'");
		        $r1 = $q1->result();
		        if(count($r1) > 0) {
		            $cid=$r1[0]->ic_id;
		        }
		    } else if($this->input->post('link_type') == 'tax') {
		        $q1 = $this->db->query("SELECT * FROM i_taxes WHERE itx_name='$cname' AND itx_owner='$oid'");
		        $r1 = $q1->result();
		        if(count($r1) > 0) {
		            $cid=$r1[0]->itx_id;
		        }
		    } else if($this->input->post('link_type') == 'module') {
		        $q1 = $this->db->query("SELECT * FROM i_modules WHERE im_name='$cname'");
		        $r1 = $q1->result();
		        print_r($r1);
		        if(count($r1) > 0) {
		            $cid=$r1[0]->im_id;
		        }
		    }
		    
		    if($lid==null) {
		        $this->db->insert('i_ac_ledgers', array('iacl_name' => $this->input->post('name'), 'iacl_group_id' => $gid, 'iacl_owner' => $oid, 'iacl_created_by' => $uid, 'iacl_created' => $dt, 'iacl_link' => $this->input->post('link_type'), 'iacl_link_id' => $cid, 'iacl_starred' => 0 ));
		    } else {
		        $this->db->where(array('iacl_id' => $lid, 'iacl_owner' => $oid));
		        $this->db->update('i_ac_ledgers', array('iacl_name' => $this->input->post('name'), 'iacl_group_id' => $gid, 'iacl_owner' => $oid, 'iacl_modified_by' => $uid, 'iacl_modified' => $dt, 'iacl_link' => $this->input->post('link_type'), 'iacl_link_id' => $cid ));
		    }
		    
		    $prp = $this->input->post('p_t');
		    $val = $this->input->post('p_v');
		    $this->db->where(array('inald_l_id' => $lid, 'inald_owner' => $oid));
		    $this->db->delete('i_n_ac_l_details');
		    for ($i=0; $i < count($prp); $i++) { 
		    	$this->db->insert('i_n_ac_l_details', array('inald_l_id' => $lid, 'inald_property' => $prp[$i], 'inald_value' => $val[$i], 'inald_owner' => $oid));
		    }

		} else {
		    redirect(base_url().'distributors/Account/login');
		}
	}
	
	public function delete_ledger($lid) {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			
		    $this->db->where(array('iacl_id' => $lid, 'iacl_owner' => $oid));
		    $this->db->delete('i_ac_ledgers');
		    
		    $this->db->where(array('iacje_from' => $lid, 'iacje_owner' => $oid));
		    $this->db->delete('i_ac_journal_entries');

		    $this->db->where(array('iacje_to' => $lid, 'iacje_owner' => $oid));
		    $this->db->delete('i_ac_journal_entries');
		    
		    redirect(base_url().'distributors/Accounting/ledgers/'.$lid);
		} else {
		    redirect(base_url().'distributors/Account/login');
		}
	}
	
	public function ledger_details($lid) {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			$fy = $sess_data['fy'][0]->ify_start_date;
			$ty = $sess_data['fy'][0]->ify_end_date;
			
		    $dt = date('Y-m-d H:i:s');
		    
		    $query = $this->db->query("SELECT a.iacje_id AS id, a.iacje_date AS date, b.iacl_name AS account_from, a.iacje_from AS from_id, c.iacl_name AS account_to, a.iacje_to AS to_id, a.iacje_description AS account_description, a.iacje_amount AS amount FROM `i_ac_journal_entries` AS a LEFT JOIN i_ac_ledgers AS b ON a.iacje_from=b.iacl_id LEFT JOIN i_ac_ledgers AS c ON a.iacje_to=c.iacl_id  WHERE a.iacje_from='$lid' OR a.iacje_to='$lid' AND a.iacje_owner='$oid' AND a.iacje_date BETWEEN '$fy' AND '$ty' ORDER BY a.iacje_date DESC, a.iacje_id");
    	    $data['txn'] = $query->result();
    	    
    	    $query = $this->db->query("SELECT * FROM i_ac_ledgers WHERE iacl_owner='$oid' AND iacl_id='$lid'");
    	    $data['details'] = $query->result();
    	    
// 		    $data['txn']= $this->acc_model->load_ledger_details($oid, $lid, $fy, $ty);
// 		    $data['details'] = $this->acc_model->load_ledgers($oid, null, $lid);
// 			$data['ledgers'] = $this->acc_model->load_ledgers($oid);
// 			$data['groups'] = $this->acc_model->load_groups($oid);
			
			$query = $this->db->query("SELECT * FROM i_n_ac_group_ledgers AS a LEFT JOIN i_n_ac_groups AS b ON a.inagl_g_id=b.inag_id WHERE a.inagl_l_id='$lid' AND a.inagl_owner='$oid'");
			$data['g_details'] = $query->result();

			$data['lid']=$lid;
		    $data['oid'] = $oid;
			
			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Ledgers Details: ".$data['details'][0]->iacl_name;
			$ert['search'] = "no_text";
			$ert['search_placeholder'] = "Search Vendors";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/accounting/ledger_details', $data);
		} else {
		    redirect(base_url().'distributors/Account/login');
		}
	}
	
	public function update_ledger_star() {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			
			$this->db->where(array('iacl_id' => $this->input->post('l'), 'iacl_owner' => $oid));
			$this->db->update('i_ac_ledgers', array('iacl_starred' => $this->input->post('s')));
		} else {
		    redirect(base_url().'distributors/Account/login');
		}
	}

################# GROUPS ########################################################
	
	public function groups() {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		   	$uid = $sess_data['user_details'][0]->iu_id;
		   	
		   	$query = $this->db->query("SELECT * FROM i_ac_groups WHERE iacg_owner='$oid'");
			$data['groups'] = $query->result();
			$data['oid'] = $oid;
			
			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Groups";
			$ert['search'] = "true";
			$ert['search_placeholder'] = "Search Groups";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/accounting/groups', $data);
		} else {
		    redirect(base_url().'distributors/Account/login');
		}
	}
	
	public function search_group() {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			
			$g=$this->input->post('keywords');
			$query=$this->db->query("SELECT * FROM i_ac_groups WHERE iacg_name LIKE '%$g%' AND iacg_owner='$oid'");
			print_r(json_encode($query->result()));
		} else {
		    redirect(base_url().'distributors/Account/login');
		}
	}
	
	public function add_group() {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		   	$uid = $sess_data['user_details'][0]->iu_id;
		   	
		   	$query = $this->db->query("SELECT * FROM i_ac_groups WHERE iacg_owner='$oid'");
			$data['groups'] = $query->result();
			
			$query = $this->db->query("SELECT * FROM i_ac_classes WHERE iacc_owner='$oid'");
			$data['classes'] = $query->result();
			
			$data['oid'] = $oid;
			
			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Add Group";
			$ert['search'] = "false";
			$ert['search_placeholder'] = "Search Vendors";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/accounting/group_add', $data);
		
		} else {
		    redirect(base_url().'distributors/Account/login');
		}
	}
	
	public function edit_group($gid) {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		
		   	$uid = $sess_data['user_details'][0]->iu_id;
			$query=$this->db->query("SELECT a.iacg_id AS iacg_id, a.iacg_name AS iacg_name, b.iacg_name AS parent_name, c.iacc_name AS class_name FROM i_ac_groups AS a LEFT JOIN i_ac_groups AS b ON a.iacg_parent_id=b.iacg_id LEFT JOIN i_ac_classes AS c ON a.iacg_class_id=c.iacc_id  WHERE a.iacg_id='$gid' AND a.iacg_owner='$oid'");
			$data['detail'] = $query->result();
			
			$query = $this->db->query("SELECT * FROM i_ac_groups WHERE iacg_owner='$oid'");
			$data['groups'] = $query->result();
			
			$query = $this->db->query("SELECT * FROM i_ac_classes WHERE iacc_owner='$oid'");
			$data['classes'] = $query->result();
			
			
			$data['oid'] = $oid;
			$data['gid'] = $gid;
			
			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Edit Group";
			$ert['search'] = "false";
			$ert['search_placeholder'] = "Search Vendors";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/accounting/group_add', $data);
		
		} else {
		    redirect(base_url().'distributors/Account/login');
		}
	}
	
	public function fetch_group_classes() {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
		    $g=$this->input->post('g');
		    
		    $query = $this->db->query("SELECT b.iacc_name AS parent FROM i_ac_groups AS a LEFT JOIN i_ac_classes AS b ON a.iacg_class_id=iacc_id WHERE iacg_name='$g'");
		    $result = $query->result();
		    if(count($result) > 0) {
		        echo $result[0]->parent;
		    } else {
		        echo "";
		    }
		}
	}
	
	public function save_group($gid=null) {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			
		    $dt = date('Y-m-d H:i:s');
		    
		    $groups = $this->input->post('groups');
		    $classes = $this->input->post('classes');
		    
		    $sgid=0; $scid=0;
	        $query = $this->db->query("SELECT * FROM i_ac_groups WHERE iacg_name='$groups' AND iacg_owner='$oid'");
	        $result = $query->result();
	        if(count($result) > 0) {
	            $sgid = $result[0]->iacg_id;
	        }
		    
	        $query = $this->db->query("SELECT * FROM i_ac_classes WHERE iacc_name='$classes' AND iacc_owner='$oid'");
	        $result = $query->result();
	        if(count($result) > 0) {
	            $scid = $result[0]->iacc_id;
	        }
		    
		    if($gid==null) {
		        $this->db->insert('i_ac_groups', array('iacg_name' => $this->input->post('name'), 'iacg_parent_id' => $sgid, 'iacg_class_id' => $scid, 'iacg_owner' => $oid, 'iacg_created_by' => $uid, 'iacg_created' => $dt));
		    } else {
		        $this->db->where(array('iacg_id' => $gid, 'iacg_owner' => $oid));
		        $this->db->update('i_ac_groups', array('iacg_name' => $this->input->post('name'), 'iacg_parent_id' => $sgid, 'iacg_class_id' => $scid, 'iacg_owner' => $oid, 'iacg_modified_by' => $uid, 'iacg_modified' => $dt));
		    }
		    echo "true";
		} else {
		    redirect(base_url().'distributors/Account/login');
		}
	}

	public function delete_group($gid) {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			
		    $this->db->where(array('iacg_id' => $gid, 'iacg_owner' => $oid));
		    $this->db->delete('i_ac_groups');
		    
		    redirect(base_url().'distributors/Accounting/groups');
		} else {
		    redirect(base_url().'distributors/Account/login');
		}
	}
	
	
	public function group_details($gid) {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			
		    $dt = date('Y-m-d H:i:s');
		    $que = $this->db->query("SELECT * FROM i_ac_groups WHERE iacg_owner='$oid' AND iacg_id='$gid'");
		    $res = $que->result();
		    
		    $query = $this->db->query("SELECT z.iacl_name AS name, z.iacl_id AS main_id, z.iacl_group_id AS groupid, x.iacg_name AS groupname, ((SELECT IFNULL(SUM(a.iacje_amount),0) FROM i_ac_journal_entries AS a LEFT JOIN i_ac_ledgers AS b ON a.iacje_to=b.iacl_id WHERE b.iacl_id=main_id) - (SELECT IFNULL(SUM(a.iacje_amount),0) FROM i_ac_journal_entries AS a LEFT JOIN i_ac_ledgers AS b ON a.iacje_from=b.iacl_id WHERE b.iacl_id=main_id)) AS balance FROM i_ac_ledgers AS z LEFT JOIN i_ac_groups AS x ON z.iacl_group_id=x.iacg_id WHERE z.iacl_group_id='$gid' AND z.iacl_owner='$oid'");
		    $data['detail'] = $query->result();
		    
            $data['gid']=$gid;
		    $data['oid'] = $oid;
			
			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Group Details: ".$res[0]->iacg_name;
			$ert['search'] = "no_text";
			$ert['search_placeholder'] = "Search Vendors";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/accounting/group_details', $data);
		} else {
		    redirect(base_url().'distributors/Account/login');
		}
	}
	
################# CLASSES ########################################################
	
	public function classes() {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		   	$uid = $sess_data['user_details'][0]->iu_id;
		   	
		   	$query = $this->db->query("SELECT * FROM i_ac_classes WHERE iacc_owner='$oid'");
		   	$data['classes'] = $query->result();
			$data['oid'] = $oid;
			
			$ert['name'] = $sess_data['user_details'][0]->ic_name;	
			$ert['title'] = "Classes";
			$ert['search'] = "true";
			$ert['search_placeholder'] = "Search Classes";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/accounting/classes', $data);
		
		} else {
		    redirect(base_url().'distributors/Account/login');
		}
	}
	

	public function search_classes() {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		   	$uid = $sess_data['user_details'][0]->iu_id;

		   	$l=$this->input->post('keywords');
		   	$query = $this->db->query("SELECT * FROM i_ac_classes WHERE iacc_name LIKE '%$l%' AND iacc_owner='$oid'");
		   	print_r(json_encode($query->result()));
		} else {
		    redirect(base_url().'distributors/Account/login');
		}
	}
	
	public function add_classes() {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		
			$data['oid'] = $oid;
			
			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Add Classes";
			$ert['search'] = "false";
			$ert['search_placeholder'] = "Search Vendors";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/accounting/classes_add', $data);
		
		} else {
		    redirect(base_url().'distributors/Account/login');
		}
	}
	
	public function edit_classes($cid) {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
		
		    $query=$this->db->query("SELECT * FROM i_ac_classes WHERE iacc_id='$cid' AND iacc_owner='$oid'");
			$data['detail'] = $query->result();

			$uid = $sess_data['user_details'][0]->iu_id;
			$data['oid'] = $oid;
			$data['cid'] = $cid;
			
			$ert['name'] = $sess_data['user_details'][0]->ic_name;
			$ert['title'] = "Edit Classes";
			$ert['search'] = "false";
			$ert['search_placeholder'] = "Search Classes";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/accounting/classes_add', $data);
		
		} else {
		    redirect(base_url().'distributors/Account/login');
		}
	}
	
	public function save_classes($cid=null) {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			
		    $dt = date('Y-m-d H:i:s');
		    
		    if($cid==null) {
		        $this->db->insert('i_ac_classes', array('iacc_name' => $this->input->post('name'), 'iacc_owner' => $oid, 'iacc_type' => $this->input->post('type'), 'iacc_created' => $dt, 'iacc_created_by' => $uid));
		    } else {
		        $this->db->where(array('iacc_owner' => $oid, 'iacc_id' => $cid));
		        $this->db->update('i_ac_classes', array('iacc_name' => $this->input->post('name'), 'iacc_type' => $this->input->post('type'), 'iacc_modified' => $dt, 'iacc_modified_by' => $uid));
		    }
		    echo "true";
		} else {
		    redirect(base_url().'distributors/Account/login');
		}
	}
	
	public function delete_classes($cid) {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			
		    $this->db->where(array('iacc_id' => $cid, 'iacc_owner' => $oid));
		    $this->db->delete('i_ac_classes');
		    redirect(base_url().'distributors/Accounting/classes/');
		} else {
		    redirect(base_url().'distributors/Account/login');
		}
	}
	
################# TRIAL BALANCE ########################################################
	
	public function trial_balance() {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			
		    $dt = date('Y-m-d H:m:s');
		    
		    $query = $this->db->query("SELECT * FROM i_n_ac_classes WHERE inacc_owner='$oid'");
		    $result = $query->result();
		    $data['classes'] = $result;
		    
		    $query = $this->db->query("SELECT a.inal_id AS lid, a.inal_ledger AS ledger, (SELECT IFNULL(SUM(inaje_amt), 0) AS credit  FROM i_n_ac_journal_entries WHERE inaje_from=a.inal_id) AS debit, (SELECT IFNULL(SUM(inaje_amt),0) FROM i_n_ac_journal_entries WHERE inaje_to=a.inal_id) AS credit, (SELECT IFNULL(SUM(inaje_amt),0) AS credit  FROM i_n_ac_journal_entries WHERE inaje_from=a.inal_id) - (SELECT IFNULL(SUM(inaje_amt),0) FROM i_n_ac_journal_entries WHERE inaje_to=a.inal_id) AS balance, (SELECT c.inacc_id AS cid FROM i_n_ac_classes AS c LEFT JOIN i_n_ac_classes_ledgers AS lc ON c.inacc_id=lc.inacl_c_id WHERE lc.inacl_l_id=a.inal_id ) AS cid FROM i_n_ac_ledgers AS a WHERE a.inal_owner='$oid'");
		    $data['detail'] = $query->result();
            
            $data['groups'] = $this->acc_model->load_groups($oid);
			$data['ledgers'] = $this->acc_model->load_ledgers($oid);

// 			$query = $this->db->query("SELECT a.inaje_id AS id, a.inaje_date AS date, b.inal_ledger AS account_from, a.inaje_from AS from_id, c.inal_ledger AS account_to, a.inaje_to AS to_id, a.inaje_description AS account_description, a.inaje_amt AS amount FROM i_n_ac_journal_entries AS a LEFT JOIN i_n_ac_ledgers AS b ON a.inaje_from=b.inal_id LEFT JOIN i_n_ac_ledgers AS c ON a.inaje_to=c.inal_id WHERE a.inaje_from IN (SELECT inagl_l_id FROM i_n_ac_group_ledgers WHERE inagl_g_id='$gid' AND inagl_owner='$oid' GROUP BY inagl_l_id) OR a.inaje_to IN (SELECT inagl_l_id FROM i_n_ac_group_ledgers WHERE inagl_g_id='$gid' AND inagl_owner='$oid' GROUP BY inagl_l_id) AND a.inaje_owner='$oid'");
			
// 		  	$sup['txn'] = $query->result();
// 		   // $data['txn']= $this->acc_model->load_group_details($oid, $gid);  
		   
		  //  $data['txn']=$sup;
		  //  $data['gid']=$gid;
		    $data['oid'] = $oid;
			
			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Trial Balance";
			$ert['search'] = "no_text";
			$ert['search_placeholder'] = "Search Vendors";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/accounting/trial_balance', $data);
		
		} else {
		    redirect(base_url().'distributors/Account/login');
		}
	}
	
################# PAYMENT/RECEIPT ########################################################
	
    public function vouchers($type) {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			
		    $dt = date('Y-m-d H:m:s');
		    
		    $data['v_type'] = $type;
		    $data['oid'] = $oid;
			
			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Voucher: ".$type;
			$ert['search'] = "no_text";
			$ert['search_placeholder'] = "Search Vendors";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/accounting/voucher', $data);
		} else {
		    redirect(base_url().'distributors/Account/login');
		}
    }	
	
	
####################### DUMMY ########################################
	
	public function load_journal_database_with_dummy() {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			#SALES ACCOUNT
			$query = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id WHERE a.it_owner='$oid' AND a.it_type='Sale'");
			$result = $query->result();
			
			for($i=0;$i<count($result);$i++) {
			    $from = $this->acc_model->search_ledger_by_name($oid, $result[$i]->ic_name, $uid);
			    $to = $this->acc_model->search_ledger_by_name($oid, 'Sales Account', $uid);
			    $description = $result[$i]->it_type." ".$result[$i]->ic_name." Txn No: ".$result[$i]->it_txn_no;
			    $dt = date('Y-m-d H:m:s');
			    echo $this->acc_model->save_journal_entry($oid, $result[$i]->it_date, $from, $to, $description, $result[$i]->it_amount, 'Sale', $result[$i]->it_id,  $dt, $uid, null);
			}
		} else {
		    redirect(base_url().'distributors/Account/login');
		}
	}
	
	public function load_journal_database_with_dummy_payment() {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			#SALES ACCOUNT
			$query = $this->db->query("SELECT * FROM i_txn_payments AS a LEFT JOIN i_contacts AS b ON a.itp_c_id=b.ic_id WHERE a.itp_owner='$oid' GROUP BY itp_amt, itp_created");
			$result = $query->result();
			
			for($i=0;$i<count($result);$i++) {
			    $from = $this->acc_model->search_ledger_by_name($oid, 'Bank', $uid);
			    $to = $this->acc_model->search_ledger_by_name($oid, $result[$i]->ic_name, $uid);
			    $description = "Payment ".$result[$i]->itp_mode." Details".$result[$i]->itp_details;
			    $dt = date('Y-m-d H:m:s');
			    echo $this->acc_model->save_journal_entry($oid, $result[$i]->itp_date, $from, $to, $description, $result[$i]->itp_amt, 'Payment', $result[$i]->itp_id,  $dt, $uid, null);
			}
		} else {
		    redirect(base_url().'distributors/Account/login');
		}
	}
	
	
	
	
	
}
?>
