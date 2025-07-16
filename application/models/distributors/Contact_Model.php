<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contact_Model extends CI_Model {

	public function load_contacts($oid, $type=null, $cid=null) {
		if($type != null) {
    		if($cid !== null){
    			$query = $this->db->query("SELECT * FROM i_contacts WHERE ic_owner='$oid'AND ic_id='$cid' AND ic_section='$type'");
    		} else {
    			$query = $this->db->query("SELECT * FROM i_contacts WHERE ic_owner='$oid' AND ic_section='$type'");
    		}    
		} else {
		 	$query = $this->db->query("SELECT * FROM i_contacts WHERE ic_owner='$oid'");
		}
		
		return $query->result();
	}

	public function search_contacts($oid, $type, $keywords) {
		$query = $this->db->query("SELECT * FROM i_contacts WHERE ic_owner='$oid' AND ic_name LIKE '%$keywords%' AND ic_section='$type'");
		return $query->result();
	}

	public function save_contact($oid, $uid, $dt, $name, $company, $email, $phone, $address, $gst, $type, $credit) {
		$data = array(
			'ic_name' => $name,
			'ic_company' => $company,
			'ic_email' => $email,
			'ic_phone' => $phone,
			'ic_address' => $address,
			'ic_gst_number' => $gst,
			'ic_section' => $type,
			'ic_created' => $dt,
			'ic_created_by' => $uid,
			'ic_owner' => $oid,
			'ic_credit' => $credit );
		$this->db->insert('i_contacts', $data);
		return $this->db->insert_id();	
	}

	public function update_contact($oid, $uid, $cid, $dt, $name, $company, $email, $phone, $address, $gst, $type, $credit) {
		$data = array(
			'ic_name' => $name,
			'ic_company' => $company,
			'ic_email' => $email,
			'ic_phone' => $phone,
			'ic_address' => $address,
			'ic_gst_number' => $gst,
			'ic_section' => $type,
			'ic_created' => $dt,
			'ic_created_by' => $uid,
			'ic_owner' => $oid,
			'ic_credit' => $credit );
		$upd_data = array('ic_id'=>$cid, 'ic_owner'=>$oid);
		$this->db->where($upd_data);
		$this->db->update('i_contacts', $data);
		return $cid;
	}

	public function save_contact_by_name($oid, $uid, $dt, $name, $type, $company, $email, $phone, $address, $gst) {
		if($type == "Allow") {
		    $query = $this->db->query("SELECT * FROM i_contacts WHERE ic_name = '$name' AND ic_owner='$oid'");
		    $result = $query->result();

    		$vid = 0;
    		if(count($result) > 0) {
    			$vid = $result[0]->ic_id;
    		} else {
    			$data = array(
    				'ic_name' => $name,
    				'ic_company' => $company,
    				'ic_email' => $email,
    				'ic_phone' => $phone,
    				'ic_address' => $address,
    				'ic_gst_number' => $gst,
    				'ic_section' => 'Vendor',
    				'ic_created' => $dt,
    				'ic_created_by' => $uid,
    				'ic_owner' => $oid);
    			$this->db->insert('i_contacts', $data);
    			$vid = $this->db->insert_id();
    		}
    		
		} else {
		    $query = $this->db->query("SELECT * FROM i_contacts WHERE ic_name = '$name' AND ic_owner='$oid' AND ic_section='$type'");
		    $result = $query->result();

    		$vid = 0;
    		if(count($result) > 0) {
    			$vid = $result[0]->ic_id;
    		} else {
    			$data = array(
    				'ic_name' => $name,
    				'ic_company' => $company,
    				'ic_email' => $email,
    				'ic_phone' => $phone,
    				'ic_address' => $address,
    				'ic_gst_number' => $gst,
    				'ic_section' => $type,
    				'ic_created' => $dt,
    				'ic_created_by' => $uid,
    				'ic_owner' => $oid);
    			$this->db->insert('i_contacts', $data);
    			$vid = $this->db->insert_id();
    		}
    		
		}
		
		return $vid;
	}

	public function delete_contact($oid, $cid, $type) {
		$data = array('ic_id' => $cid , 'ic_owner' => $oid, 'ic_section' => $type);
		$this->db->where($data);
		$this->db->delete('i_contacts');
		return $cid;
	}

	public function load_contact_pricing($oid, $pid, $type) {
		$query = $this->db->query("SELECT * FROM i_contacts AS a LEFT JOIN i_p_pricing AS b ON a.ic_id=b.ipp_c_id WHERE a.ic_owner='$oid' AND b.ipp_p_id='$pid'");
		return $query->result();
	}

	public function create_user($oid, $cid, $type, $uname, $start_subs, $end_subs, $dt) {
		$data = array(
			'iu_username' => $uname,
			'iu_type' => $type,
			'iu_ref' => $cid,
			'iu_owner' => $oid,
			'iu_status' => 'password_u',
			'iu_subscription_start' => $start_subs,
			'iu_subscription_end' => $end_subs,
			'iu_created' => $dt,
			'iu_created_by' => $oid
		);

		$this->db->insert('i_users', $data);

	}
}