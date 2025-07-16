<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Entity_Model extends CI_Model {

    public function load_contacts($oid, $type=null, $name=null, $cid=null) {
        if($type!=null) $this->db->where('ic_section', $type);
        if($name!=null) $this->db->like('ic_name', $name);
        if($cid!=null) $this->db->where('ic_id',$cid);
        $this->db->where('ic_owner',$oid);
        $query = $this->db->get('i_contacts');
        return $query->result();
    }
    
    public function save_contact($oid, $uid, $dt, $operation_type, $cid=null, $name, $section, $company=null, $phone=null, $phone2=null, $email=null, $email2=null, $website=null, $address=null, $gstnum=null, $credit=null, $type=null, $bank=null, $bank_branch=null, $bank_accno=null, $bank_ifsc=null) {
        $d = array(
            'ic_name' => $name,
            'ic_company' => $company,
            'ic_phone' => $phone,
            'ic_phone_2' => $phone2,
            'ic_email' => $email,
            'ic_email_2' => $email2,
            'ic_website' => $website,
            'ic_address' => $address,
            'ic_gst_number' => $gstnum,
            'ic_section' => $section,
            'ic_type' => $type,
            'ic_owner' => $oid,
            'ic_bank_name' => $bank,
            'ic_bank_branch' => $bank_branch,
            'ic_bank_accno' => $bank_accno,
            'ic_bank_ifsc' => $bank_ifsc,
            'ic_credit' => $credit
            );
        if($operation_type == "save") {
            $d=$d+array('ic_created' => $dt, 'ic_created_by' => $uid);
            $this->db->insert('i_contacts', $d);
            $cid = $this->db->insert_id();
        } else if ($operation_type == "update") {
            $d=$d+array('ic_modified' => $dt, 'ic_modified_by' => $uid);
            $this->db->where(array('ic_id' => $cid, 'ic_owner' => $oid));
            $this->db->update('i_contacts', $d);
        }
        echo $cid;
    }
    
    public function delete_contact($oid, $cid) {
        $this->db->where(array('ic_id' => $cid, 'ic_owner' => $oid));
        $this->db->delete('i_contacts');
    }
    
    public function load_products($oid, $type=null, $name=null, $cid=null) {
        if($type!=null) $this->db->where('ic_section', $type);
        if($name!=null) $this->db->like('ic_name', $name);
        if($cid!=null) $this->db->where('ic_id',$cid);
        $this->db->where('ic_owner',$oid);
        $query = $this->db->get('i_contacts');
        return $query->result();
    }
    
    public function save_products($oid, $uid, $dt, $operation_type, $cid=null, $name, $section, $company=null, $phone=null, $phone2=null, $email=null, $email2=null, $website=null, $address=null, $gstnum=null, $credit=null, $type=null, $bank=null, $bank_branch=null, $bank_accno=null, $bank_ifsc=null) {
        $d = array(
            'ic_name' => $name,
            'ic_company' => $company,
            'ic_phone' => $phone,
            'ic_phone_2' => $phone2,
            'ic_email' => $email,
            'ic_email_2' => $email2,
            'ic_website' => $website,
            'ic_address' => $address,
            'ic_gst_number' => $gstnum,
            'ic_section' => $section,
            'ic_type' => $type,
            'ic_owner' => $oid,
            'ic_bank_name' => $bank,
            'ic_bank_branch' => $bank_branch,
            'ic_bank_accno' => $bank_accno,
            'ic_bank_ifsc' => $bank_ifsc,
            'ic_credit' => $credit
            );
        if($operation_type == "save") {
            $d=$d+array('ic_created' => $dt, 'ic_created_by' => $uid);
            $this->db->insert('i_contacts', $d);
            $cid = $this->db->insert_id();
        } else if ($operation_type == "update") {
            $d=$d+array('ic_modified' => $dt, 'ic_modified_by' => $uid);
            $this->db->where(array('ic_id' => $cid, 'ic_owner' => $oid));
            $this->db->update('i_contacts', $d);
        }
        echo $cid;
    }
    
    public function delete_products($oid, $cid) {
        $this->db->where(array('ic_id' => $cid, 'ic_owner' => $oid));
        $this->db->delete('i_contacts');
    }
    
    
}
?>