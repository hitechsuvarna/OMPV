<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account_Master extends CI_Controller {

	public function __construct()	{
		parent:: __construct();
		$this->load->database();
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->library('email');
		$this->load->library('excel_reader');
		$this->load->dbforge();
	}
    
    public function index() {
		$sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
            
            $q = $this->db->query("SELECT * FROM i_cash_master WHERE icm_owner='$oid'");
            $data['cash'] = $q->result();
            
			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Account Master";
			$ert['search'] = "false";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/cash/cash', $data);
			
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}
	
	public function load_cash_master($cid=null) {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
            
            if($cid==null) {
                $q = $this->db->query("SELECT * FROM i_cash_master WHERE icm_owner='$oid'");    
            } else {
                $q = $this->db->query("SELECT * FROM i_cash_master WHERE icm_owner='$oid' AND icm_id='$cid'");
            }
            print_r(json_encode($q->result()));
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}

    public function save_cash_master($cid=null) {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
            $uid = $sess_data['user_details'][0]->iu_id;
            $dt = date('Y-m-d H:i:s');
            
            if($cid == null) {
                $this->db->insert('i_cash_master', array(
                    'icm_name' => $this->input->post('n'),
                    'icm_owner' => $oid,
                    'icm_created' => $dt,
                    'icm_created_by' => $uid
                    ));
            } else {
                $this->db->where(array('icm_id' => $cid, 'icm_owner' => $oid));
                $this->db->update('i_cash_master', array(
                    'icm_name' => $this->input->post('n'),
                    'icm_created' => $dt,
                    'icm_created_by' => $uid
                    ));
            }
		} else {
			redirect(base_url().'distributors/Account/login');
		}
    }
    
    public function load_ledger_master($lid=null) {
	    $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
            
            if($lid==null) {
                $q = $this->db->query("SELECT * FROM i_ledger_master WHERE ilm_owner='$oid'");    
            } else {
                $q = $this->db->query("SELECT * FROM i_ledger_master WHERE ilm_owner='$oid' AND ilm_id='$lid'");
            }
            print_r(json_encode($q->result()));
		} else {
			redirect(base_url().'distributors/Account/login');
		}
	}

    public function save_ledger_master($lid=null) {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
            $uid = $sess_data['user_details'][0]->iu_id;
            $dt = date('Y-m-d H:i:s');
            
            if($lid == null) {
                $this->db->insert('i_ledger_master', array(
                    'ilm_name' => $this->input->post('l'),
                    'ilm_owner' => $oid,
                    'ilm_created' => $dt,
                    'ilm_created_by' => $uid
                    ));
            } else {
                $this->db->where(array('ilm_id' => $lid, 'ilm_owner' => $oid));
                $this->db->update('i_ledger_master', array(
                    'ilm_name' => $this->input->post('l'),
                    'ilm_created' => $dt,
                    'ilm_created_by' => $uid
                    ));
            }
		} else {
			redirect(base_url().'distributors/Account/login');
		}
    }
    
    # ONLY LEDGER VIEW
    
    public function view_account_ledgers() {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
            
            $q = $this->db->query("SELECT * FROM i_cash_master WHERE icm_owner='$oid'");
            $data['cash'] = $q->result();
            
			$ert['name'] = $sess_data['user_details'][0]->ic_name;			
			$ert['title'] = "Account Ledgers";
			$ert['search'] = "false";
			$ert['type'] = "distributors";
			
			$this->load->view('distributors/navbar', $ert);
			$this->load->view('distributors/cash/ledger_view', $data);
			
		} else {
			redirect(base_url().'distributors/Account/login');
		}
    }
    
    public function get_journal_entry($jid) {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
            
            $q = $this->db->query("SELECT * FROM i_cash_transactions WHERE ict_owner='$oid' AND ict_id='$jid'");
            print_r(json_encode($q->result()));
		} else {
			redirect(base_url().'distributors/Account/login');
		}
    }
    public function del_journal_entry($jid) {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
            
            $this->db->where(array('ict_id' => $jid, 'ict_owner' => $oid));
            $this->db->delete('i_cash_transactions');
            echo 'true';
		} else {
			redirect(base_url().'distributors/Account/login');
		}
    }
    
    public function fetch_account_ledger_details($type,$pid) {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$data=[];
			$filter = $this->input->post('f');
			if($type=='account') {
			    if($pid==0) {
			        if($filter=='') {
			            $q = $this->db->query("SELECT * FROM i_cash_master WHERE icm_owner='$oid'");    
			        } else {
			            $q = $this->db->query("SELECT * FROM i_cash_master WHERE icm_owner='$oid' AND icm_name LIKE '%$filter%'");
			        }
			        $data['account'] = $q->result();
			    } else {
			        $f=$this->input->post('f');$t=$this->input->post('t');$tx=$this->input->post('tx');
			        if($f!=null && $t!=null) {
			            $q = $this->db->query("SELECT * FROM i_cash_transactions AS a WHERE ((a.ict_from='$pid' AND a.ict_from_type='$type') OR (a.ict_to='$pid' AND a.ict_to_type='$type')) AND a.ict_flag_col='cash' AND a.ict_date BETWEEN '$f' AND '$t' AND a.ict_owner='$oid'");
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
			            
			            $q = $this->db->query("SELECT * FROM i_cash_transactions AS a WHERE ((a.ict_from='$pid' AND a.ict_from_type='$type') OR (a.ict_to='$pid' AND a.ict_to_type='$type')) AND a.ict_flag_col='cash' AND a.ict_date < '$f' AND a.ict_owner='$oid'");
			            $r = $q->result();
			            
			            $cr=0;$dr=0;
			            for($i=0;$i<count($r); $i++) {
			                if($r[$i]->ict_from == $pid && $r[$i]->ict_from_type==$type) {
			                    $cr+=$r[$i]->ict_amount;
			                } else {
			                    $dr+=$r[$i]->ict_amount;
			                }
			            }
			            
    			        $q = $this->db->query("SELECT * FROM i_opening_balance WHERE iob_e_id='$pid' AND iob_e_type='$type' AND iob_owner='$oid'");
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
			            $q = $this->db->query("SELECT * FROM i_cash_transactions AS a WHERE ((ict_from='$pid' AND ict_from_type='$type') OR (ict_to='$pid' AND ict_to_type='$type')) AND ict_flag_col='cash' AND ict_owner='$oid'");
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
			            
    			        $q = $this->db->query("SELECT * FROM i_opening_balance WHERE iob_e_id='$pid' AND iob_e_type='$type' AND iob_owner='$oid'");
    			        if(count($q->result()) > 0) {
    			            $data['opening'] = array('type' => $q->result()[0]->iob_amount_type, 'amt' => $q->result()[0]->iob_amount);    
    			        } else {
    			            $data['opening'] = array();
    			        }
			        }
			        
			    }
			} else if($type=='ledger') {
			    if($pid==0) {
			        if($filter==''){
			            $q = $this->db->query("SELECT * FROM i_ledger_master WHERE ilm_owner='$oid'");    
			        } else {
			            $q = $this->db->query("SELECT * FROM i_ledger_master WHERE ilm_owner='$oid' AND ilm_name LIKE '%$filter%'");
			        }
			        $data['ledger'] = $q->result();
			    } else {
			        $f=$this->input->post('f');$t=$this->input->post('t');$tx=$this->input->post('tx');
			        if($f!=null && $t!=null) {
			            $q = $this->db->query("SELECT * FROM i_cash_transactions AS a WHERE ((ict_from='$pid' AND ict_from_type='$type') OR (ict_to='$pid' AND ict_to_type='$type')) AND ict_flag_col='cash' AND ict_date BETWEEN '$f' AND '$t' AND  ict_owner='$oid' ORDER BY ict_date");
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
			            
			            $q = $this->db->query("SELECT * FROM i_cash_transactions AS a WHERE ((ict_from='$pid' AND ict_from_type='$type') OR (ict_to='$pid' AND ict_to_type='$type')) AND ict_flag_col='cash' AND ict_date < '$f' AND ict_owner='$oid' ORDER BY ict_date");
			            $r = $q->result();
			            
			            $cr=0;$dr=0;
			            for($i=0;$i<count($r); $i++) {
			                if($r[$i]->ict_from == $pid && $r[$i]->ict_from_type==$type) {
			                    $cr+=$r[$i]->ict_amount;
			                } else {
			                    $dr+=$r[$i]->ict_amount;
			                }
			            }
			            
    			        $q = $this->db->query("SELECT * FROM i_opening_balance WHERE iob_e_id='$pid' AND iob_e_type='$type' AND iob_owner='$oid'");
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
			            $q = $this->db->query("SELECT * FROM i_cash_transactions AS a WHERE ((ict_from='$pid' AND ict_from_type='$type') OR (ict_to='$pid' AND ict_to_type='$type')) AND ict_flag_col='cash' AND  ict_owner='$oid' ORDER BY ict_date");
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
			            
			            $q = $this->db->query("SELECT * FROM i_opening_balance WHERE iob_e_id='$pid' AND iob_e_type='$type' AND iob_owner='$oid'");
    			        if(count($q->result()) > 0) {
    			            $data['opening'] = array('type' => $q->result()[0]->iob_amount_type, 'amt' => $q->result()[0]->iob_amount);    
    			        } else {
    			            $data['opening'] = array();
    			        }
			        }
			    }
			} else if($type=='contact') {
			    if($pid==0) {
			        if($filter=='') {
			            $q = $this->db->query("SELECT * FROM i_contacts WHERE ic_owner='$oid'");    
			        } else {
			            $q = $this->db->query("SELECT * FROM i_contacts WHERE ic_owner='$oid' AND ic_name LIKE '%$filter%'");
			        }
			        $data['contact'] = $q->result();
			    } else {
			        $f=$this->input->post('f');$t=$this->input->post('t');$tx=$this->input->post('tx');
			        //   FETCH DELIVERY, INVOICE, PURCHASE, CREDIT NOTE, DEBIT NOTE
			        //   FROM & TO DATE != NULL
			        if($f!=null && $t!=null) {
			            if($tx=='delivery'){
			                $q = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id WHERE (it_type IN ('Delivery') OR it_type IN ('Credit Note') AND it_type_sec IN ('Delivery')) AND it_date BETWEEN '$f' AND '$t' AND it_c_id='$pid' AND it_owner='$oid' ORDER BY it_date, it_id");    
			            } else if($tx=='invoice') {
			                $q = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id WHERE (it_type IN ('Invoice','Purchase') OR it_type IN ('Credit Note', 'Debit Note') AND it_type_sec IN ('Invoice')) AND it_date BETWEEN '$f' AND '$t' AND it_c_id='$pid' AND it_owner='$oid' ORDER BY it_date, it_id");
			            } else {
			                $q = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id WHERE it_type IN ('Delivery','Invoice','Purchase','Credit Note','Debit Note') AND it_c_id='$pid' AND it_date BETWEEN '$f' AND '$t' AND it_owner='$oid' ORDER BY it_date, it_id");
			            }
			        } 
			        //   FROM & TO DATE == NULL
			        else {
			            if($tx=='delivery'){
			                $q = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id WHERE (it_type IN ('Delivery') OR it_type IN ('Credit Note') AND it_type_sec IN ('Delivery')) AND it_c_id='$pid' AND it_owner='$oid' ORDER BY it_date, it_id");    
			            } else if($tx=='invoice') {
			                $q = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id WHERE (it_type IN ('Invoice','Purchase') OR it_type IN ('Credit Note', 'Debit Note') AND it_type_sec IN ('Delivery','Invoice')) AND it_c_id='$pid' AND it_owner='$oid' ORDER BY it_date, it_id");
			            } else {
			                $q = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id WHERE it_type IN ('Delivery','Invoice','Purchase','Credit Note','Debit Note') AND it_c_id='$pid' AND it_owner='$oid' ORDER BY it_date, it_id");
			            }
			            
			        }
			        
			        $r=$q->result(); $x=[];
			        $payment_arr=[];
			        $transporter_arr=[];
			        //    FETCH PAYMENT DETAILS & TRANSPORTER PAYMENT (INVOICE ONLY) FOR ABOVE TRANSACTION LIST
			        for($i=0;$i<count($r);$i++) {
			            $tx=$r[$i]->it_id;
			            $tx_type=$r[$i]->it_type;
			            
			            $q1 = $this->db->query("SELECT * FROM i_txn_payments_new AS a LEFT JOIN i_cash_master AS b ON a.itpn_mode_id=b.icm_id WHERE a.itpn_txn_id='$tx' AND itpn_owner='$oid' ORDER BY a.itpn_date");
			            if(count($q1->result()) > 0) {
			                array_push($payment_arr, $q1->result());
			            }
			            
			            if($tx_type == "Invoice" || $tx_type == "Delivery") {
			                $q2 = $this->db->query("SELECT * FROM i_txn_transport_details AS a LEFT JOIN i_txns AS b ON a.ittd_txn_id=b.it_id LEFT JOIN i_expenses AS c ON a.ittd_expense=c.ie_id WHERE a.ittd_txn_id='$tx' AND a.ittd_owner='$oid'");
			                if(count($q2->result()) > 0) {
			                    array_push($transporter_arr, array(
			                        'transporter_name' => $q2->result()[0]->ittd_transporter,
			                        'transporter_gst' => $q2->result()[0]->ittd_transporter_gstno,
			                        'lrno' => $q2->result()[0]->ittd_lrno,
			                        'date' => $q2->result()[0]->ittd_date,
			                        'amount' => $q2->result()[0]->ie_amount,
			                        'description' => $q2->result()[0]->ie_description,
			                        'transaction_no' => $q2->result()[0]->it_txn_no
		                        ));
			                }
			            }
			        }
			        $data['txn'] = array('txn' => $r, 'pay' => $payment_arr, 'transporter' => $transporter_arr);       
			        
			        
			        //   FETCH MANUAL LEDGER ENTRIES IN CASH_TRANSACTIONS.
			        if($f!=null && $t!=null) {
			            if($tx=='delivery'){
			                $q = $this->db->query("SELECT * FROM i_txns WHERE (it_type IN ('Delivery') OR it_type IN ('Credit Note') AND it_type_sec IN ('Delivery')) AND it_date < '$f' AND it_c_id='$pid' AND it_owner='$oid' ORDER BY it_date");    
			            } else if($tx=='invoice') {
			                $q = $this->db->query("SELECT * FROM i_txns WHERE (it_type IN ('Invoice','Purchase') OR it_type IN ('Credit Note', 'Debit Note') AND it_type_sec IN ('Delivery','Invoice')) AND it_date < '$f' AND it_c_id='$pid' AND it_owner='$oid' ORDER BY it_date");
			            } else {
			                $q = $this->db->query("SELECT * FROM i_txns WHERE it_type IN ('Delivery','Invoice','Purchase','Credit Note','Debit Note') AND it_c_id='$pid' AND it_date < '$f' AND it_owner='$oid' ORDER BY it_date");
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
			            
			            $q = $this->db->query("SELECT * FROM i_cash_transactions AS a WHERE ((ict_from='$pid' AND ict_from_type='$type') OR (ict_to='$pid' AND ict_to_type='$type')) AND ict_flag_col='cash' AND ict_date BETWEEN '$f' AND '$t' AND  ict_owner='$oid' ORDER BY ict_date");
			            $r=$q->result();
			            $arr=[];
			            for($i=0;$i<count($r);$i++) {
			                $txp = $r[$i]->ict_txn_id;
			                $typx = 'false';
			                $qx = $this->db->query("SELECT * FROM i_txn_payments_new WHERE itpn_id='$txp' AND itpn_owner='$oid' ORDER BY itpn_date");
			                if(count($qx->result()) > 0) {
			                    $typx = 'true';
			                }
			                
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
			                    'typx' => $typx
			                    ));
			            }
			            $data['transaction'] = $arr;
			            
			            $q = $this->db->query("SELECT * FROM i_cash_transactions AS a WHERE ((ict_from='$pid' AND ict_from_type='$type') OR (ict_to='$pid' AND ict_to_type='$type')) AND ict_flag_col='cash' AND ict_date < '$f' AND ict_owner='$oid'");
			            $r = $q->result();
			            
			            for($i=0;$i<count($r); $i++) {
			                if($r[$i]->ict_from == $pid && $r[$i]->ict_from_type==$type) {
			                    $cr+=$r[$i]->ict_amount;
			                } else {
			                    $dr+=$r[$i]->ict_amount;
			                }
			            }
			            
    			        $q = $this->db->query("SELECT * FROM i_opening_balance WHERE iob_e_id='$pid' AND iob_e_type='$type' AND iob_owner='$oid'");
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
			            $q = $this->db->query("SELECT * FROM i_cash_transactions AS a WHERE ((ict_from='$pid' AND ict_from_type='$type') OR (ict_to='$pid' AND ict_to_type='$type')) AND ict_flag_col='cash' AND  ict_owner='$oid'");
			            $r=$q->result();
			            $arr=[];
			            for($i=0;$i<count($r);$i++) {
			                $txp = $r[$i]->ict_txn_id;
			                $typx = 'false';
			                $qx = $this->db->query("SELECT * FROM i_txn_payments_new WHERE itpn_id='$txp' AND itpn_owner='$oid'");
			                if(count($qx->result()) > 0) {
			                    $typx = 'true';
			                }
			                
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
			                    'typx' => $typx
			                    ));
			            }
			            $data['transaction'] = $arr;
			            
			            $q = $this->db->query("SELECT * FROM i_opening_balance WHERE iob_e_id='$pid' AND iob_e_type='$type' AND iob_owner='$oid'");
    			        if(count($q->result()) > 0) {
    			            $data['opening'] = array('type' => $q->result()[0]->iob_amount_type, 'amt' => $q->result()[0]->iob_amount);    
    			        } else {
    			            $data['opening'] = array();
    			        }
			        }
			    }
			}
            print_r(json_encode($data));
		} else {
			redirect(base_url().'distributors/Account/login');
		}
    }
    
    public function save_account_entry($jid=null) {
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			$dt = date('Y-m-d H:i:s');
			
			$type = $this->input->post('type');
			if($type=="issue") {
			    $from = $this->input->post('cash');
			    $from_type = 'account';
			    
			    $to = $this->input->post('sel_id');
			    $to_type = $this->input->post('sel_type');
			} else {
			    $from = $this->input->post('sel_id');
			    $from_type = $this->input->post('sel_type');
			    
			    $to = $this->input->post('cash');
			    $to_type = 'account';
			}
            
            if($jid == null) {
                $this->db->insert('i_cash_transactions', array(
                    'ict_from' => $from,
                    'ict_from_type' => $from_type,
                    'ict_to' => $to,
                    'ict_to_type' => $to_type,
                    'ict_amount' => $this->input->post('amount'),
                    'ict_date' => $this->input->post('date'),
                    'ict_narration' => $this->input->post('narration'),
                    'ict_owner' => $oid,
                    'ict_created' => $dt,
                    'ict_created_by' => $uid,
                    'ict_txn_id' => time(),
                    'ict_flag_col' => 'cash'
                ));
            } else {
                $this->db->where(array('ict_id' => $jid, 'ict_owner' => $oid));
                $this->db->update('i_cash_transactions', array(
                    'ict_from' => $from,
                    'ict_from_type' => $from_type,
                    'ict_to' => $to,
                    'ict_to_type' => $to_type,
                    'ict_amount' => $this->input->post('amount'),
                    'ict_date' => $this->input->post('date'),
                    'ict_narration' => $this->input->post('narration'),
                    'ict_modified' => $dt,
                    'ict_modified_by' => $uid
                ));
            }
                
            echo 'true';
		} else {
			redirect(base_url().'distributors/Account/login');
		}
    }
    
    public function update_account_balance(){
        $sess_data = $this->session->userdata();
		if(isset($sess_data['user_details'][0])) {
			$oid = $sess_data['user_details'][0]->iu_owner;
			$uid = $sess_data['user_details'][0]->iu_id;
			$dt = date('Y-m-d H:i:s');
			
			$s_type = $this->input->post('sel_type');
            $s_id = $this->input->post('sel_id');
            $type = $this->input->post('type');
            $date = $this->input->post('date');
            $amount = $this->input->post('amount');
            
             
			$q = $this->db->query("SELECT * FROM i_opening_balance WHERE iob_e_id='$s_id' AND iob_e_type='$s_type' AND iob_owner='$oid'");
			$r = $q->result();
			
			if(count($r) > 0) {
			    $this->db->where(array('iob_id' => $r[0]->iob_id, 'iob_owner' => $oid));
			    $this->db->update('i_opening_balance', array(
			        'iob_amount' => $amount,
			        'iob_amount_type' => $type,
			        'iob_date' => $date,
			        'iob_created' => $dt,
			        'iob_created_by' => $uid
			    ));
			} else {
			    $this->db->insert('i_opening_balance', array(
			        'iob_e_id' => $s_id,
			        'iob_e_type' => $s_type,
			        'iob_amount' => $amount,
			        'iob_amount_type' => $type,
			        'iob_date' => $date,
			        'iob_owner' => $oid,
			        'iob_created' => $dt,
			        'iob_created_by' => $uid
			    ));
			}
			echo 'true';
		} else {
		    redirect(base_url().'distributors/Account/login');
		}
    }
} ?>