<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accounting_Model extends CI_Model {

	public function load_ledgers($oid, $search=null, $lid=null) {
		if($search != null) {
    		$query = $this->db->query("SELECT * FROM i_n_ac_ledgers WHERE inal_owner='$oid' AND inal_ledger LIKE '%$search%'");
		} else if($lid!=null) {
		 	$query = $this->db->query("SELECT * FROM i_n_ac_ledgers WHERE inal_owner='$oid' AND inal_id = '$lid'");
		} else {
		    $query = $this->db->query("SELECT * FROM i_n_ac_ledgers WHERE inal_owner='$oid'");
		}
		return $query->result();
	}
	
	public function save_ledger($oid, $name, $dt, $uid, $lid=null) {
	    $data = array('inal_ledger' => $name, 'inal_owner' => $oid, 'inal_created' => $dt, 'inal_created_by' => $uid, 'inal_editable' => 'true');
	    if($lid==null) {
	        $this->db->insert('i_n_ac_ledgers', $data);
	        $lid = $this->db->insert_id();
	    } else {
	        $this->db->where(array('inal_id' => $lid, 'inal_owner' => $oid));
	        $this->db->update('i_n_ac_ledgers', $data);
	    }
	    return $lid;
	}
	
	public function search_ledger_by_name($oid, $name, $uid) {
	    $query = $this->db->query("SELECT * FROM i_n_ac_ledgers WHERE inal_owner='$oid' AND inal_ledger='$name'");
	    $result = $query->result();
	    if(count($result) > 0) {
	        $lid = $result[0]->inal_id;
	    } else {
	        $dt = date('Y-m-d H:m:s');
	        $lid = $this->save_ledger($oid, $name, $dt, $uid);
	    }
	    return $lid;
	}
	
	public function load_ledger_details($oid, $lid=null, $fy, $ty) {
	    if($lid==null) {
	        $query = $this->db->query("SELECT a.inaje_id AS id, a.inaje_date AS date, b.inal_ledger AS account_from, a.inaje_from AS from_id, c.inal_ledger AS account_to, a.inaje_to AS to_id, a.inaje_description AS account_description, a.inaje_amt AS amount FROM `i_n_ac_journal_entries` AS a LEFT JOIN i_n_ac_ledgers AS b ON a.inaje_from=b.inal_id LEFT JOIN i_n_ac_ledgers AS c ON a.inaje_to=c.inal_id  WHERE a.inaje_owner='$oid' ORDER BY a.inaje_date DESC, a.inaje_id");
	    } else {
	        $query = $this->db->query("SELECT a.inaje_id AS id, a.inaje_date AS date, b.inal_ledger AS account_from, a.inaje_from AS from_id, c.inal_ledger AS account_to, a.inaje_to AS to_id, a.inaje_description AS account_description, a.inaje_amt AS amount FROM `i_n_ac_journal_entries` AS a LEFT JOIN i_n_ac_ledgers AS b ON a.inaje_from=b.inal_id LEFT JOIN i_n_ac_ledgers AS c ON a.inaje_to=c.inal_id  WHERE a.inaje_from='$lid' OR a.inaje_to='$lid' AND a.inaje_owner='$oid' ORDER BY a.inaje_date DESC, a.inaje_id");
	    }
	    return $query->result();
	}
	
	public function save_journal_entry($oid, $date, $from, $to, $description, $amount, $link_from, $link_id,  $dt, $uid, $groups, $jid=null) {
	    if($from == null) {
		    $fid = 0;
		} else {
		    $query = $this->db->query("SELECT * FROM i_n_ac_ledgers WHERE inal_owner = '$oid' AND inal_ledger='$from'");
			$result = $query->result();
			
			if(count($result) > 0) {
			    $fid = $result[0]->inal_id;
			} else {
			    $data1 = array('inal_ledger' => $from, 'inal_owner' => $oid, 'inal_created' => $dt, 'inal_created_by' => $uid);
			    $this->db->insert('i_n_ac_ledgers', $data1);
			    $fid = $this->db->insert_id();
			}    
		}
		
		if($to==null) {
		    $tid=0;
		} else {
		    $query = $this->db->query("SELECT * FROM i_n_ac_ledgers WHERE inal_owner = '$oid' AND inal_ledger='$to'");
			$result = $query->result();
			
			if(count($result) > 0) {
			    $tid = $result[0]->inal_id;
			} else {
			    $data1 = array('inal_ledger' => $to, 'inal_owner' => $oid, 'inal_created' => $dt, 'inal_created_by' => $uid);
			    $this->db->insert('i_n_ac_ledgers', $data1);
			    $tid = $this->db->insert_id();
			}
		}
		
	    $data = array('inaje_date' => $date, 'inaje_from' => $fid, 'inaje_to' => $tid, 'inaje_description' => $description, 'inaje_amt' => $amount, 'inaje_link_from' => $link_from, 'inaje_link_id' => $link_id, 'inaje_owner' => $oid, 'inaje_created' => $dt, 'inaje_created_by' => $uid);
	    if($jid == null) {
	        $this->db->insert('i_n_ac_journal_entries', $data);    
	        $jid = $this->db->insert_id();
	    } else {
	        $this->db->where(array('inaje_owner' => $oid, 'inaje_id' => $jid));
	        $this->db->update('i_n_ac_journal_entries', $data);
	    }
	    
	    $this->db->where(array('inajg_owner' => $oid, 'inajg_je_id' => $jid));
	    $this->db->delete('i_n_ac_je_groups');
	    
	    for($i=0;$i<count($groups);$i++) {
	        $query = $this->db->query("SELECT * FROM i_n_ac_groups WHERE inag_owner='$oid' AND inag_group='$group[$i]'");
	        $result = $query->result();
	        
	        if(count($result) > 0) {
	            $gid = $result[0]->inag_id;
	            $this->db->insert('i_n_ac_je_groups', array('inajg_je_id' => $jid, 'inajg_g_id' => $gid, 'inajg_owner' =>$oid, 'inajg_created' => $dt, 'inajg_created_by' => $uid));
	        }
	    }
	    return $jid;
	}
	
	public function load_groups($oid, $search=null, $lid=null) {
		if($search != null) {
    		$query = $this->db->query("SELECT * FROM i_n_ac_groups WHERE inag_owner='$oid' AND inag_group LIKE '%$search%'");
		} else if($lid!=null) {
		 	$query = $this->db->query("SELECT * FROM i_n_ac_groups WHERE inag_owner='$oid' AND inag_id = '$lid'");
		} else {
		    $query = $this->db->query("SELECT * FROM i_n_ac_groups WHERE inag_owner='$oid'");
		}
		return $query->result();
	}
	
	public function save_group($oid, $name, $classes, $groups, $dt, $uid, $gid=null) {
	    $data = array('inag_group' => $name, 'inag_owner' => $oid, 'inag_created' => $dt, 'inag_created_by' => $uid);
	    if($gid==null) {
	        $this->db->insert('i_n_ac_groups', $data);
	        $gid = $this->db->insert_id();
	        for($i=0;$i<count($classes);$i++) {
	            $query=$this->db->query("SELECT * FROM i_n_ac_classes WHERE inacc_owner='$oid' AND inacc_name='$classes[$i]'");
	            $result = $query->result();
	            
	            if(count($result) > 0) {
	                $cid = $result[0]->inacc_id;
	            } else {
	                $this->db->insert('i_n_ac_classes', array('inacc_name' => $classes[$i], 'inacc_owner' => $oid, 'inacc_created_by' => $uid, 'inacc_created' => $dt));
	                $cid = $this->db->insert_id();
	            }
	            $this->db->insert('i_n_ac_group_classes', array('inagc_g_id' => $gid, 'inagc_c_id' => $cid, 'inagc_owner' => $oid, 'inagc_created' => $dt, 'inagc_created_by' => $uid));
	        }
	        
	        for($i=0;$i<count($groups);$i++) {
	            $query=$this->db->query("SELECT * FROM i_n_ac_groups WHERE inag_owner='$oid' AND inag_group='$groups[$i]'");
	            $result = $query->result();
	            
	            if(count($result) > 0) {
	                $cid = $result[0]->inag_id;
	            } else {
	                $this->db->insert('i_n_ac_groups', array('inag_group' => $groups[$i], 'inag_owner' => $oid, 'inag_created_by' => $uid, 'inag_created' => $dt));
	                $cid = $this->db->insert_id();
	            }
	            $this->db->insert('i_n_ac_parent_groups', array('inapg_g_id' => $gid, 'inapg_parent_g_id' => $cid, 'inapg_owner' => $oid, 'inapg_created' => $dt, 'inapg_created_by' => $uid));
	        }
	    } else {
	        $this->db->where(array('inag_id' => $gid, 'inag_owner' => $oid));
	        $this->db->update('i_n_ac_groups', $data);
	        
	        $this->db->where(array('inagc_g_id' => $gid, 'inagc_owner' => $oid));
	        $this->db->delete('i_n_ac_group_classes');
	        
	        $this->db->where(array('inapg_g_id' => $gid, 'inapg_owner' => $oid));
	        $this->db->delete('i_n_ac_parent_groups');
	        
	        for($i=0;$i<count($classes);$i++) {
	            $query=$this->db->query("SELECT * FROM i_n_ac_classes WHERE inacc_owner='$oid' AND inacc_name='$classes[$i]'");
	            $result = $query->result();
	            
	            if(count($result) > 0) {
	                $cid = $result[0]->inacc_id;
	            } else {
	                $this->db->insert('i_n_ac_classes', array('inacc_name' => $classes[$i], 'inacc_owner' => $oid, 'inacc_created_by' => $uid, 'inacc_created' => $dt));
	                $cid = $this->db->insert_id();
	            }
	            $this->db->insert('i_n_ac_group_classes', array('inagc_g_id' => $gid, 'inagc_c_id' => $cid, 'inagc_owner' => $oid, 'inagc_created' => $dt, 'inagc_created_by' => $uid));
	        }
	        
	        for($i=0;$i<count($groups);$i++) {
	            $query=$this->db->query("SELECT * FROM i_n_ac_groups WHERE inag_owner='$oid' AND inag_group='$groups[$i]'");
	            $result = $query->result();
	            
	            if(count($result) > 0) {
	                $cid = $result[0]->inag_id;
	            } else {
	                $this->db->insert('i_n_ac_groups', array('inag_group' => 'InSR'.$groups[$i], 'inag_owner' => $oid, 'inag_created_by' => $uid, 'inag_created' => $dt));
	                $cid = $this->db->insert_id();
	            }
	            $this->db->insert('i_n_ac_parent_groups', array('inapg_g_id' => $gid, 'inapg_parent_g_id' => $cid, 'inapg_owner' => $oid, 'inapg_created' => $dt, 'inapg_created_by' => $uid));
	        }
	    }
	    return $gid;
	}
	
	public function load_group_details($oid, $gid=null) {
	    if($gid==null) {
	        $query = $this->db->query("SELECT a.inaje_id AS id, a.inaje_date AS date, b.inal_ledger AS account_from, a.inaje_from AS from_id, c.inal_ledger AS account_to, a.inaje_to AS to_id, a.inaje_description AS account_description, a.inaje_amt AS amount FROM `i_n_ac_journal_entries` AS a LEFT JOIN i_n_ac_ledgers AS b ON a.inaje_from=b.inal_id LEFT JOIN i_n_ac_ledgers AS c ON a.inaje_to=c.inal_id  WHERE a.inaje_owner='$oid' ORDER BY a.inaje_date DESC");    
	        $data['txn'] = $query->result();
	    } else {
	        $que = $this->db->query("SELECT * FROM i_n_ac_group_ledgers WHERE inagl_g_id='$gid' AND inagl_owner='$oid'");
	        $res = $que->result();
	        $lid = [];
	        for($i=0;$i<count($res);$i++) {
	            array_push($lid, (int)$res[$i]->inagl_l_id);
	        }

	        $data['lids'] = $lid;
	        $jid_str = implode("','", $lid);
	        $query = $this->db->query("SELECT a.inaje_id AS id, a.inaje_date AS date, b.inal_ledger AS account_from, a.inaje_from AS from_id, c.inal_ledger AS account_to, a.inaje_to AS to_id, a.inaje_description AS account_description, a.inaje_amt AS amount FROM `i_n_ac_journal_entries` AS a LEFT JOIN i_n_ac_ledgers AS b ON a.inaje_from=b.inal_id LEFT JOIN i_n_ac_ledgers AS c ON a.inaje_to=c.inal_id  WHERE a.inaje_from IN ('$jid_str') AND a.inaje_to IN ('$jid_str') AND a.inaje_owner='$oid' ORDER BY a.inaje_date DESC");
	        $data['txn'] = $query->result();
	    }
	    return $data;
	}
	
}
?>