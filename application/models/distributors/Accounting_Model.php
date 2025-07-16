<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accounting_Model extends CI_Model {
    
    public function save_journal_entry($oid, $from, $to, $details, $date, $amount, $link_type, $link_id, $uid, $dt, $je_id=null) {
        if($je_id == null) {
            $this->db->insert('i_ac_journal_entries', array('iacje_from' => $from, 'iacje_to' => $to, 'iacje_description' => $details, 'iacje_amount' => $amount, 'iacje_date' => $date, 'iacje_link_type' => $link_type, 'iacje_link_id' => $link_id, 'iacje_owner' => $oid, 'iacje_created_by' => $uid, 'iacje_created' => $dt));
            $je_id=$this->db->insert_id();
        } else {
            $this->db->where(array('iacje_owner' => $oid, 'iacje_id' => $je_id));
            $this->db->update('i_ac_journal_entries', array('iacje_from' => $from, 'iacje_to' => $to, 'iacje_description' => $details, 'iacje_amount' => $amount, 'iacje_date' => $date, 'iacje_link_type' => $link_type, 'iacje_link_id' => $link_id, 'iacje_modified_by' => $uid, 'iacje_modified' => $dt));
        }
        return $je_id;
    }
    
    public function delete_journal_entries($oid, $jeid, $link_type, $link_id) {
        if($jeid == null) {
            $this->db->where(array('iacje_owner' => $oid, 'iacje_link_type' => $link_type, 'iacje_link_id' => $link_id));
            $this->db->delete('i_ac_journal_entries');
        } else if($link_type == null) {
            $this->db->where(array('iacje_owner' => $oid, 'iacje_id' => $jeid));
            $this->db->delete('i_ac_journal_entries');
        }
    }
    
    public function save_invoice($oid, $cid, $uid, $dt, $date, $txnno, $note, $total, $status, $product, $qty, $rate, $tax_group, $alias, $tid=null, $discount, $freight, $credit, $transport, $lrno, $transport_date, $transport_gst, $transport_state, $transport_expense) {
        
        // SAVE INVOICE TO MASTER TXN TABLE
        $total_rate=0;
		for ($i=0; $i < count($rate) ; $i++) { 
			$total_rate+=($rate[$i]*$qty[$i]);	
		}
		
		if($tid == null) {
		    $data = array(
    			'it_type' => 'Invoice',
    			'it_c_id' => $cid,
    			'it_date' => $date,
    			'it_txn_no' => $txnno,
    			'it_note' => $note,
    			'it_amount' => $total,
    			'it_status' => $status,
    			'it_discount' => $discount,
    			'it_freight' => $freight,
    			'it_credit' => $credit,
    			'it_mode' => '',
    			'it_owner' => $oid,
    			'it_created' => $dt,
    			'it_created_by' => $uid );
    		$this->db->insert('i_txns', $data);
    		$tid = $this->db->insert_id();
    		
    		$query = $this->db->query("SELECT * FROM i_txn_nums WHERE itn_type='Invoice' AND itn_owner='$oid'");
        	$result = $query->result();
        	
        	if(count($result) > 0) {
        	    $x=$result[0]->itn_num + 1;
        	    $this->db->where(array('itn_type' => 'Invoice', 'itn_owner' =>$oid));
        	    $this->db->update('i_txn_nums', array('itn_num' => $x, 'itn_date' => $date ));    
        	} else {
        	    $this->db->insert('i_txn_nums', array('itn_type' => 'Invoice', 'itn_num' => '1', 'itn_owner' => $oid, 'itn_date' => $date));
        	}
        	
    		$data1=array(
    		    'ie_description' => $transport.' via '.$lrno.' GST No:'.$transport_gst.' to '.$transport_state,
    		    'ie_amount' => $transport_expense,
    		    'ie_order_id' => $tid,
    		    'ie_status' => 'done',
    		    'ie_owner' => $oid,
    		    'ie_created_by' => $uid,
    		    'ie_created' => $dt,
    		    );
    		$this->db->insert('i_expenses', $data1);
    		$eid = $this->db->insert_id();
    		
    		$data1 = array(
    		    'ittd_txn_id' => $tid,
    		    'ittd_transporter' => $transport,
    		    'ittd_lrno' => $lrno,
    		    'ittd_date' => $transport_date,
    		    'ittd_transporter_gstno' => $transport_gst,
    		    'ittd_state' => $transport_state,
    		    'ittd_owner' => $oid,
    		    'ittd_expense' => $eid);
    	    $this->db->insert('i_txn_transport_details', $data1);	    
    	
		} else {
		    $que = $this->db->query("SELECT * FROM i_txns WHERE it_id='$tid' AND it_owner='$oid'");
		    $res = $que->result();
		    if(count($res) > 0) {
		        if($res[0]->it_type == "Delivery") {
		            $data = array(
            			'it_type' => 'Invoice',
            			'it_c_id' => $cid,
            			'it_date' => $date,
            			'it_txn_no' => $txnno,
            			'it_note' => $note,
            			'it_amount' => $total,
            			'it_status' => $status,
            			'it_owner' => $oid,
            			'it_discount' => $discount,
            			'it_freight' => $freight,
            			'it_credit' => $credit,
            			'it_mode' => $res[0]->it_mode,
            			'it_order_txn' => $res[0]->it_id,
            			'it_created' => $dt,
            			'it_created_by' => $uid );
            		$this->db->insert('i_txns', $data);
            		$tid = $this->db->insert_id();
            		
            		$this->db->where(array('it_id' => $res[0]->it_id, 'it_owner' => $oid));
            		$this->db->update('i_txns', array('it_status' => 'invoiced'));
            		
            		$query = $this->db->query("SELECT * FROM i_txn_nums WHERE itn_type='Invoice' AND itn_owner='$oid'");
                	$result = $query->result();
                	
                	if(count($result) > 0) {
                	    $x=$result[0]->itn_num + 1;
                	    $this->db->where(array('itn_type' => 'Invoice', 'itn_owner' =>$oid));
                	    $this->db->update('i_txn_nums', array('itn_num' => $x ));    
                	} else {
                	    $this->db->insert('i_txn_nums', array('itn_type' => 'Invoice', 'itn_num' => '1', 'itn_owner' => $oid, 'itn_date' => $date));
                	}
        	
            		$xc = $res[0]->it_id;
            		$que1 = $this->db->query("SELECT * FROM i_txn_invoice_merge WHERE itim_primary='$xc' AND itim_owner='$oid' AND itim_status='active'");
            		$res1 = $que1->result();
            		
            		for($m=0;$m<count($res1);$m++) {
            		    $this->db->where(array('it_id' => $res1[$m]->itim_secondary, 'it_owner' => $oid));
            		    $this->db->update('i_txns', array('it_status' => 'invoiced'));
            		}
		        } else {
		            $data = array(
            			'it_type' => 'Invoice',
            			'it_c_id' => $cid,
            			'it_date' => $date,
            			'it_txn_no' => $txnno,
            			'it_note' => $note,
            			'it_amount' => $total,
            			'it_status' => $status,
            			'it_owner' => $oid,
            			'it_discount' => $discount,
            			'it_freight' => $freight,
            			'it_credit' => $credit,
            			'it_modified' => $dt,
            			'it_modified_by' => $uid );
                    $this->db->where(array('it_owner' => $oid, 'it_type' => 'Invoice', 'it_id' => $tid));
    		        $this->db->update('i_txns', $data);		
		        }
		    }
		    
		    $que = $this->db->query("SELECT * FROM i_expenses WHERE ie_order_id='$tid' AND ie_owner='$oid'");
		    $res = $que->result();
		    
		    $data1=array(
    		    'ie_description' => $transport.' via '.$lrno.' GST No:'.$transport_gst.' to '.$transport_state,
    		    'ie_amount' => $transport_expense,
    		    'ie_order_id' => $tid,
    		    'ie_date' => $transport_date,
    		    'ie_status' => 'done',
    		    'ie_owner' => $oid,
    		    'ie_created_by' => $uid,
    		    'ie_created' => $dt,
    		    );
    		
		    if(count($res) > 0) {
		        $eid = $res[0]->ie_id;
		        $this->db->where(array('ie_id' => $eid, 'ie_owner' => $oid, 'ie_order_id' => $tid));
    		    $this->db->update('i_expenses', $data1);
		    } else {
		        $this->db->insert('i_expenses', $data1);
    		    $eid = $this->db->insert_id();
		    }
		    
		    $que = $this->db->query("SELECT * FROM i_txn_transport_details WHERE ittd_owner='$oid' AND ittd_txn_id='$tid'");
		    $res = $que->result();
		    
		    $data1 = array(
    		    'ittd_txn_id' => $tid,
    		    'ittd_transporter' => $transport,
    		    'ittd_lrno' => $lrno,
    		    'ittd_date' => $transport_date,
    		    'ittd_transporter_gstno' => $transport_gst,
    		    'ittd_state' => $transport_state,
    		    'ittd_owner' => $oid,
    		    'ittd_expense' => $eid);
    	    
		    if(count($res) > 0) {
		        $this->db->where(array('ittd_txn_id' => $tid, 'ittd_owner' => $oid));
		        $this->db->update('i_txn_transport_details', $data1);
		    } else {
		        $this->db->insert('i_txn_transport_details', $data1);
		    }
		}
		
		
		####################################
		// GET LEDGER DATA AND INSERT MAIN JOURNAL ENTRY
// 		$secondary_ledger_name="";$secondary_ledger_name_to="";
// 		$s_ledger_from=0;$_ledger_to=0;
// 		$query = $this->db->query("SELECT * FROM i_modules WHERE im_name='Invoice'");
//         $result = $query->result();
//         $ledger_from=0;$ledger_to=0;
//         if(count($result) > 0) {
//             $secondary_ledger_name=$result[0]->im_secondary_ledger;
//             $secondary_ledger_name_to=$result[0]->im_secondary_ledger_to;
            
//             $imid = $result[0]->im_id;
//             $que = $this->db->query("SELECT * FROM i_ac_ledgers WHERE iacl_link='module' AND iacl_link_id='$imid' AND iacl_owner='$oid'");
//             $res = $que->result();
//             if(count($res) > 0) {
//                 $ledger_to=$res[0]->iacl_id;
//             }
//         }
        
//         $que = $this->db->query("SELECT * FROM i_ac_ledgers WHERE iacl_link='contact' AND iacl_link_id='$cid' AND iacl_owner='$oid'");
//         $res = $que->result();
//         if(count($res) > 0) {
//             $ledger_from=$res[0]->iacl_id;
//         }
        
        
//         $que = $this->db->query("SELECT * FROM i_ac_ledgers WHERE iacl_name='$secondary_ledger_name' AND iacl_owner='$oid'");
//         $res = $que->result();
//         if(count($res) > 0) {
//             $s_ledger_from=$res[0]->iacl_id;
//         }
        
//         $que = $this->db->query("SELECT * FROM i_ac_ledgers WHERE iacl_name='$secondary_ledger_name_to' AND iacl_owner='$oid'");
//         $res = $que->result();
//         if(count($res) > 0) {
//             $s_ledger_to=$res[0]->iacl_id;
//         }
        
//         $query = $this->db->query("SELECT * FROM i_ac_journal_entries WHERE iacje_owner='$oid' AND iacje_link_type='Invoice' AND iacje_link_id='$tid'");
//         $result = $query->result();
//         if(count($result) >0) {
//             $this->db->where(array('iacje_link_type' => 'Invoice' , 'iacje_owner' => $oid, 'iacje_link_id' => $tid ));
//     		$this->db->delete('i_ac_journal_entries');
            
//             $this->save_journal_entry($oid, $ledger_from, $ledger_to, 'Invoice No: '.$txnno, $date, $total_rate, 'Invoice', $tid, $uid, $dt, null);
//         } else {
//             $this->save_journal_entry($oid, $ledger_from, $ledger_to, 'Invoice No: '.$txnno, $date, $total_rate, 'Invoice', $tid, $uid, $dt, null);
//         }
        
//         $query = $this->db->query("SELECT * FROM i_ac_journal_entries WHERE iacje_owner='$oid' AND iacje_link_type='Invoice Expense' AND iacje_link_id='$eid'");
//         $result = $query->result();
        
//         if(count($result) > 0) {
//             $this->db->where(array('iacje_link_type' => 'Invoice Expense' , 'iacje_owner' => $oid, 'iacje_link_id' => $eid ));
//     		$this->db->delete('i_ac_journal_entries');
            
//             $this->save_journal_entry($oid, $s_ledger_from, $s_ledger_to, $transport.' via '.$lrno.' GST No:'.$transport_gst.' to '.$transport_state, $transport_date, $transport_expense, 'Invoice Expense', $eid, $uid, $dt, null);    
//         } else {
//             $this->save_journal_entry($oid, $s_ledger_from, $s_ledger_to, $transport.' via '.$lrno.' GST No:'.$transport_gst.' to '.$transport_state, $transport_date, $transport_expense, 'Invoice Expense', $eid, $uid, $dt, null);
//         }
        
		###########################################
		
		
		$this->db->where(array('itd_t_id' => $tid , 'itp_owner' => $oid ));
		$this->db->delete('i_txns_details');

		$this->db->where(array('itpt_txn_id' => $tid , 'itpt_owner' => $oid ));
		$this->db->delete('i_txn_product_taxes');
		
		// STORE TXN DETAILS IN DATABASE
		$total_amount=0;
        $ledger_taxation = [];$ledger_flag=false;
		$this->load->model('distributors/Products_Model','product_model');
		for ($i=0;$i<count($product);$i++) { 
    		$pid = $this->product_model->save_product_by_name($oid, $uid, $dt, $product[$i]);
    		$tax = $this->product_model->get_product_tax_details($oid, $pid, $rate[$i], $tax_group[$i], $qty[$i]);
			if(count($tax['taxtotid']) > 0) {
                $total_amount+=(($rate[$i] * $qty[$i]) + $tax['taxtotamt'][0]);
                $data = array(
    				'itd_t_id' => $tid,
    				'itd_p_id' => $pid,
    				'itp_qty' => $qty[$i],
    				'itp_rate' => $rate[$i],
    				'itp_value' => ($qty[$i] * $rate[$i]),
    				'itp_tax_group_id' => $tax['taxtotid'][0],
    				'itp_tax' => $tax['taxtotamt'][0],
    				'itp_amount' => (($qty[$i]*$rate[$i]) + $tax['taxtotamt'][0]),
    				'itp_alias' => $alias[$i],
    				'itp_owner' => $oid,
    				'itp_created' => $dt,
    				'itp_created_by' => $uid );    
            } else {
                $total_amount+=($rate[$i] * $qty[$i]);
                $data = array(
    				'itd_t_id' => $tid,
    				'itd_p_id' => $pid,
    				'itp_qty' => $qty[$i],
    				'itp_rate' => $rate[$i],
    				'itp_value' => ($rate[$i] * $qty[$i]),
    				'itp_tax_group_id' => 0,
    				'itp_tax' => 0,
    				'itp_amount' => ($rate[$i] * $qty[$i]),
    				'itp_alias' => $alias[$i],
    				'itp_owner' => $oid,
    				'itp_created' => $dt,
    				'itp_created_by' => $uid );
            }
            $this->db->insert('i_txns_details', $data);
			$tmptid = $this->db->insert_id();
			
			for ($ij=0; $ij < count($tax['taxid']) ; $ij++) { 
			    array_push($ledger_taxation, array('id' => $tax['taxid'][$ij], 'name' => $tax['taxname'][$ij], 'amt' => $tax['taxamt'][$ij]));
			    $data = array(
					'itpt_txn_id' => $tid,
					'itpt_td_id' => $tmptid,
					'itpt_tx_id' => $tax['taxid'][$ij],
					'itpt_p_id' => $pid,
					'itpt_t_name' => $tax['taxname'][$ij],
					'itpt_t_amount' => $tax['taxamt'][$ij],
					'itpt_owner' => $oid );
				$this->db->insert('i_txn_product_taxes', $data);
			}
    	}
    		
		$this->db->where(array('it_id' => $tid, 'it_owner' => $oid, 'it_type' => 'Invoice'));
		$this->db->update('i_txns', array('it_amount' => $total_amount));
    	
    	
    	
		$que = $this->db->query("SELECT * FROM i_taxes WHERE itx_owner='$oid'");
		$res = $que->result();
		$fnl_taxation = [];
		for($i=0;$i<count($res);$i++) {
		    $amt=0;
		    for($j=0;$j<count($ledger_taxation);$j++) {
		        if($res[$i]->itx_id == $ledger_taxation[$j]['id']) {
		            $amt+=$ledger_taxation[$j]['amt'];
		        }
		    }
            if($amt > 0) {
                array_push($fnl_taxation, array('id' => $res[$i]->itx_id, 'name' => $res[$i]->itx_name, 'amt' => $amt));   
            }
		}
	
		for($i=0;$i<count($fnl_taxation);$i++) {
		    $ledger_id=0;
		    $taxid = $fnl_taxation[$i]['id'];
	        $que = $this->db->query("SELECT * FROM i_ac_ledgers WHERE iacl_link='tax' AND iacl_link_id='$taxid' AND iacl_owner='$oid'");
            $res = $que->result();
            if(count($res) > 0) {
                $ledger_to=$res[0]->iacl_id;
            }
		  //  $this->save_journal_entry($oid, $ledger_from, $ledger_to, $fnl_taxation[$i]['name'].' on Invoice No: '.$txnno, $date, $fnl_taxation[$i]['amt'], 'Invoice', $tid, $uid, $dt, null);
		}
            
		
		return $tid;
		
    }
    
    public function save_purchase($oid, $cid, $uid, $dt, $date, $txnno, $note, $total, $status, $product, $qty, $rate, $tax_group, $alias, $tid, $discount, $freight, $credit, $transport, $lrno, $transport_date, $transport_gst, $transport_state, $transport_expense, $inv_link, $sec_type) {
        
        // SAVE INVOICE TO MASTER TXN TABLE
        $total_rate=0;
		for ($i=0; $i < count($rate) ; $i++) { 
			$total_rate+=($rate[$i]*$qty[$i]);	
		}
		
		if($tid == null) {
		    $data = array(
    			'it_type' => 'Purchase',
    			'it_c_id' => $cid,
    			'it_date' => $date,
    			'it_txn_no' => $txnno,
    			'it_note' => $note,
    			'it_amount' => $total,
    			'it_status' => $status,
    			'it_discount' => $discount,
    			'it_freight' => $freight,
    			'it_credit' => $credit,
    			'it_mode' => '',
    			'it_txn_link' => $inv_link,
    			'it_owner' => $oid,
    			'it_created' => $dt,
    			'it_created_by' => $uid,
    			'it_type_sec' => $sec_type);
    		$this->db->insert('i_txns', $data);
    		$tid = $this->db->insert_id();
    		
    		$data1=array(
    		    'ie_description' => $transport.' via '.$lrno.' GST No:'.$transport_gst.' to '.$transport_state,
    		    'ie_amount' => $transport_expense,
    		    'ie_order_id' => $oid,
    		    'ie_status' => 'done',
    		    'ie_owner' => $oid,
    		    'ie_created_by' => $uid,
    		    'ie_created' => $dt,
    		    );
    		$this->db->insert('i_expenses', $data1);
    		$eid = $this->db->insert_id();
    		
    		$data1 = array(
    		    'ittd_txn_id' => $tid,
    		    'ittd_transporter' => $transport,
    		    'ittd_lrno' => $lrno,
    		    'ittd_date' => $transport_date,
    		    'ittd_transporter_gstno' => $transport_gst,
    		    'ittd_state' => $transport_state,
    		    'ittd_owner' => $oid,
    		    'ittd_expense' => $eid);
    	    $this->db->insert('i_txn_transport_details', $data1);	    
    	
		} else {
            $data = array(
    			'it_type' => 'Purchase',
    			'it_c_id' => $cid,
    			'it_date' => $date,
    			'it_txn_no' => $txnno,
    			'it_note' => $note,
    			'it_amount' => $total,
    			'it_status' => $status,
    			'it_owner' => $oid,
    			'it_discount' => $discount,
    			'it_freight' => $freight,
    			'it_credit' => $credit,
    			'it_txn_link' => $inv_link,
    			'it_modified' => $dt,
    			'it_modified_by' => $uid,
    			'it_type_sec' => $sec_type);
            $this->db->where(array('it_owner' => $oid, 'it_type' => 'Purchase', 'it_id' => $tid));
	        $this->db->update('i_txns', $data);
	        
	        $que = $this->db->query("SELECT * FROM i_expenses WHERE ie_order_id='$tid' AND ie_owner='$oid'");
		    $res = $que->result();
		    
		    $data1=array(
    		    'ie_description' => $transport.' via '.$lrno.' GST No:'.$transport_gst.' to '.$transport_state,
    		    'ie_amount' => $transport_expense,
    		    'ie_order_id' => $tid,
    		    'ie_date' => $transport_date,
    		    'ie_status' => 'done',
    		    'ie_owner' => $oid,
    		    'ie_created_by' => $uid,
    		    'ie_created' => $dt,
    		    );
    		
		    if(count($res) > 0) {
		        $eid = $res[0]->ie_id;
		        $this->db->where(array('ie_id' => $eid, 'ie_owner' => $oid, 'ie_order_id' => $tid));
    		    $this->db->update('i_expenses', $data1);
		    } else {
		        $this->db->insert('i_expenses', $data1);
    		    $eid = $this->db->insert_id();
		    }
		    
		    $que = $this->db->query("SELECT * FROM i_txn_transport_details WHERE ittd_owner='$oid' AND ittd_txn_id='$tid'");
		    $res = $que->result();
		    
		    $data1 = array(
    		    'ittd_txn_id' => $tid,
    		    'ittd_transporter' => $transport,
    		    'ittd_lrno' => $lrno,
    		    'ittd_date' => $transport_date,
    		    'ittd_transporter_gstno' => $transport_gst,
    		    'ittd_state' => $transport_state,
    		    'ittd_owner' => $oid,
    		    'ittd_expense' => $eid);
    	    
		    if(count($res) > 0) {
		        $this->db->where(array('ittd_txn_id' => $tid, 'ittd_owner' => $oid));
		        $this->db->update('i_txn_transport_details', $data1);
		    } else {
		        $this->db->insert('i_txn_transport_details', $data1);
		    }
		}
		
		
		#*****************************************************************
		// GET LEDGER DATA AND INSERT MAIN JOURNAL ENTRY
// 		$query = $this->db->query("SELECT * FROM i_modules WHERE im_name='Purchase'");
//         $result = $query->result();
//         $secondary_ledger_name="";$secondary_ledger_name_to="";
// 		$s_ledger_from=0;$_ledger_to=0;
//         $ledger_from=0;$ledger_to=0;
//         if(count($result) > 0) {
//             $secondary_ledger_name=$result[0]->im_secondary_ledger;
//             $secondary_ledger_name_to=$result[0]->im_secondary_ledger_to;
//             $imid = $result[0]->im_id;
//             $que = $this->db->query("SELECT * FROM i_ac_ledgers WHERE iacl_link='module' AND iacl_link_id='$imid' AND iacl_owner='$oid'");
//             $res = $que->result();
//             if(count($res) > 0) {
//                 $ledger_from=$res[0]->iacl_id;
//             }
//         }
        
//         $que = $this->db->query("SELECT * FROM i_ac_ledgers WHERE iacl_link='contact' AND iacl_link_id='$cid' AND iacl_owner='$oid'");
//         $res = $que->result();
//         if(count($res) > 0) {
//             $ledger_to=$res[0]->iacl_id;
//         }
        
//         $que = $this->db->query("SELECT * FROM i_ac_ledgers WHERE iacl_name='$secondary_ledger_name' AND iacl_owner='$oid'");
//         $res = $que->result();
//         if(count($res) > 0) {
//             $s_ledger_from=$res[0]->iacl_id;
//         }
        
//         $que = $this->db->query("SELECT * FROM i_ac_ledgers WHERE iacl_name='$secondary_ledger_name_to' AND iacl_owner='$oid'");
//         $res = $que->result();
//         if(count($res) > 0) {
//             $s_ledger_to=$res[0]->iacl_id;
//         }
        
//         $query = $this->db->query("SELECT * FROM i_ac_journal_entries WHERE iacje_owner='$oid' AND iacje_link_type='Purchase' AND iacje_link_id='$tid'");
//         $result = $query->result();
//         if(count($result) >0) {
//             $this->db->where(array('iacje_link_type' => 'Purchase' , 'iacje_owner' => $oid, 'iacje_link_id' => $tid ));
//     		$this->db->delete('i_ac_journal_entries');
            
//             $this->save_journal_entry($oid, $ledger_from, $ledger_to, 'Purchase Invoice No: '.$txnno, $date, $total_rate, 'Purchase', $tid, $uid, $dt, null);    
//         } else {
//             $this->save_journal_entry($oid, $ledger_from, $ledger_to, 'Purchase Invoice No: '.$txnno, $date, $total_rate, 'Purchase', $tid, $uid, $dt, null);
//         }
        
        
//         $query = $this->db->query("SELECT * FROM i_ac_journal_entries WHERE iacje_owner='$oid' AND iacje_link_type='Purchase Expense' AND iacje_link_id='$eid'");
//         $result = $query->result();
        
//         if(count($result) > 0) {
//             $this->db->where(array('iacje_link_type' => 'Purchase Expense' , 'iacje_owner' => $oid, 'iacje_link_id' => $eid ));
//     		$this->db->delete('i_ac_journal_entries');
            
//             $this->save_journal_entry($oid, $s_ledger_from, $s_ledger_to, $transport.' via '.$lrno.' GST No:'.$transport_gst.' to '.$transport_state, $transport_date, $transport_expense, 'Purchase Expense', $eid, $uid, $dt, null);    
//         } else {
//             $this->save_journal_entry($oid, $s_ledger_from, $s_ledger_to, $transport.' via '.$lrno.' GST No:'.$transport_gst.' to '.$transport_state, $transport_date, $transport_expense, 'Purchase Expense', $eid, $uid, $dt, null);
//         }
        
		// STORE TXN DETAILS IN DATABASE
		
		$this->db->where(array('itd_t_id' => $tid , 'itp_owner' => $oid ));
		$this->db->delete('i_txns_details');

		$this->db->where(array('itpt_txn_id' => $tid , 'itpt_owner' => $oid ));
		$this->db->delete('i_txn_product_taxes');
		
		$total_amount=0;
        $ledger_taxation = [];$ledger_flag=false;
		$this->load->model('distributors/Products_Model','product_model');
		for ($i=0;$i<count($product);$i++) { 
    		$pid = $this->product_model->save_product_by_name($oid, $uid, $dt, $product[$i]);
    		$tax = $this->product_model->get_product_tax_details($oid, $pid, $rate[$i], $tax_group[$i], $qty[$i]);
			if(count($tax['taxtotid']) > 0) {
                $total_amount+=(($rate[$i] * $qty[$i]) + $tax['taxtotamt'][0]);
                $data = array(
    				'itd_t_id' => $tid,
    				'itd_p_id' => $pid,
    				'itp_qty' => $qty[$i],
    				'itp_rate' => $rate[$i],
    				'itp_value' => ($qty[$i] * $rate[$i]),
    				'itp_tax_group_id' => $tax['taxtotid'][0],
    				'itp_tax' => $tax['taxtotamt'][0],
    				'itp_amount' => (($qty[$i]*$rate[$i]) + $tax['taxtotamt'][0]),
    				'itp_alias' => $alias[$i],
    				'itp_owner' => $oid,
    				'itp_created' => $dt,
    				'itp_created_by' => $uid );    
            } else {
                $total_amount+=($rate[$i] * $qty[$i]);
                $data = array(
    				'itd_t_id' => $tid,
    				'itd_p_id' => $pid,
    				'itp_qty' => $qty[$i],
    				'itp_rate' => $rate[$i],
    				'itp_value' => ($rate[$i] * $qty[$i]),
    				'itp_tax_group_id' => 0,
    				'itp_tax' => 0,
    				'itp_amount' => ($rate[$i] * $qty[$i]),
    				'itp_alias' => $alias[$i],
    				'itp_owner' => $oid,
    				'itp_created' => $dt,
    				'itp_created_by' => $uid );
            }
            $this->db->insert('i_txns_details', $data);
			$tmptid = $this->db->insert_id();
			
			for ($ij=0; $ij < count($tax['taxid']) ; $ij++) { 
			    array_push($ledger_taxation, array('id' => $tax['taxid'][$ij], 'name' => $tax['taxname'][$ij], 'amt' => $tax['taxamt'][$ij]));
			    $data = array(
					'itpt_txn_id' => $tid,
					'itpt_td_id' => $tmptid,
					'itpt_tx_id' => $tax['taxid'][$ij],
					'itpt_p_id' => $pid,
					'itpt_t_name' => $tax['taxname'][$ij],
					'itpt_t_amount' => $tax['taxamt'][$ij],
					'itpt_owner' => $oid );
				$this->db->insert('i_txn_product_taxes', $data);
			}
    	}
    	
    	$total_amount += $transport_expense;
    		
		$this->db->where(array('it_id' => $tid, 'it_owner' => $oid, 'it_type' => 'Purchase'));
		$this->db->update('i_txns', array('it_amount' => $total_amount));
    		
		$que = $this->db->query("SELECT * FROM i_taxes WHERE itx_owner='$oid'");
		$res = $que->result();
		$fnl_taxation = [];
		for($i=0;$i<count($res);$i++) {
		    $amt=0;
		    for($j=0;$j<count($ledger_taxation);$j++) {
		        if($res[$i]->itx_id == $ledger_taxation[$j]['id']) {
		            $amt+=$ledger_taxation[$j]['amt'];
		        }
		    }
            if($amt > 0) {
                array_push($fnl_taxation, array('id' => $res[$i]->itx_id, 'name' => $res[$i]->itx_name, 'amt' => $amt));   
            }
		}
	
		for($i=0;$i<count($fnl_taxation);$i++) {
		    $ledger_id=0;
		    $taxid = $fnl_taxation[$i]['id'];
	        $que = $this->db->query("SELECT * FROM i_ac_ledgers WHERE iacl_link='tax' AND iacl_link_id='$taxid' AND iacl_owner='$oid'");
            $res = $que->result();
            if(count($res) > 0) {
                $ledger_from=$res[0]->iacl_id;
            }
		  //  $this->save_journal_entry($oid, $ledger_from, $ledger_to, $fnl_taxation[$i]['name'].' on Purchase Invoice No: '.$txnno, $date, $fnl_taxation[$i]['amt'], 'Purchase', $tid, $uid, $dt, null);
		}
            
		
		return $tid;
		
    }
    
    public function save_expenses($oid, $uid, $dt, $description, $amount, $date, $order_id, $cid, $txnno, $status, $tickets, $type, $eid) {
        // SAVE EXPENSE TO MASTER TXN TABLE
        if($eid == null) {
		    $data = array(
		        'ie_description' => $description,
		        'ie_amount' => $amount,
		        'ie_order_id' => $order_id,
		        'ie_status' => $status,
		        'ie_date' => $date,
		        'ie_owner' => $oid,
		        'ie_created' => $dt,
		        'ie_created_by' => $uid,
		        'ie_tickets' => $tickets,
		        'ie_type' => $type
		        );
		    $this->db->insert('i_expenses', $data);
        } else {
            $data = array(
		        'ie_description' => $description,
		        'ie_amount' => $amount,
		        'ie_order_id' => $order_id,
		        'ie_status' => $status,
		        'ie_date' => $date,
		        'ie_owner' => $oid,
		        'ie_created' => $dt,
		        'ie_created_by' => $uid,
		        'ie_tickets' => $tickets,
		        'ie_type' => $type
		        );
		    $this->db->where(array('ie_id' => $eid, 'ie_owner' => $oid));
		    $this->db->update('i_expenses', $data);
        }
		
		// GET LEDGER DATA AND INSERT MAIN JOURNAL ENTRY
		$query = $this->db->query("SELECT * FROM i_modules WHERE im_name='Expenses'");
        $result = $query->result();
        $ledger_from=0;$ledger_to=0;
        if(count($result) > 0) {
            $imid = $result[0]->im_id;
            $que = $this->db->query("SELECT * FROM i_ac_ledgers WHERE iacl_link='module' AND iacl_link_id='$imid' AND iacl_owner='$oid'");
            $res = $que->result();
            if(count($res) > 0) {
                $ledger_from=$res[0]->iacl_id;
            }
        }
        
        $que = $this->db->query("SELECT * FROM i_ac_ledgers WHERE iacl_link='contact' AND iacl_link_id='$cid' AND iacl_owner='$oid'");
        $res = $que->result();
        if(count($res) > 0) {
            $ledger_to=$res[0]->iacl_id;
        }
        
        
        $query = $this->db->query("SELECT * FROM i_ac_journal_entries WHERE iacje_owner='$oid' AND iacje_link_type='Expenses' AND iacje_link_id='$eid'");
        $result = $query->result();
        if(count($result) >0) {
            $this->db->where(array('iacje_link_type' => 'Expenses' , 'iacje_owner' => $oid, 'iacje_link_id' => $eid ));
    		$this->db->delete('i_ac_journal_entries');
            
            $this->save_journal_entry($oid, $ledger_from, $ledger_to, 'Expense incurred for '.$txnno, $date, $amount, 'Expenses', $eid, $uid, $dt, null);    
        } else {
            $this->save_journal_entry($oid, $ledger_from, $ledger_to, 'Expense incurred for '.$txnno, $date, $amount, 'Expenses', $eid, $uid, $dt, null);
        }
		
        
    }
}
?>