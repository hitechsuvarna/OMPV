<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction_Model extends CI_Model {

	public function load_transactions($oid, $type, $status, $tid=null) {
		if($tid !== null){
			if($status !== null) {
				$query = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id WHERE a.it_owner='$oid'AND a.it_id='$tid' AND a.it_status='$status' AND a.it_type='$type' ORDER BY a.it_date DESC");
			} else {
				$query = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id WHERE a.it_owner='$oid'AND a.it_id='$tid' AND a.it_type='$type' ORDER BY a.it_date DESC");
			}
		} else {
			if ($status!== null) {
				$query = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id WHERE a.it_owner='$oid' AND a.it_status='$status' AND a.it_type='$type' ORDER BY a.it_date DESC");
			} else {
				$query = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id WHERE a.it_owner='$oid' AND a.it_type='$type' ORDER BY a.it_date DESC");
			}
		}
		return $query->result();
	}
	

	public function load_transactions_dealer($oid, $status, $cid) {
		if($status !== null) {
			$query = $this->db->query("SELECT * FROM i_txns WHERE it_owner='$oid' AND it_c_id ='$cid' AND it_status='$status'");
		} else {
			$query = $this->db->query("SELECT * FROM i_txns WHERE it_owner='$oid'AND it_c_id ='$cid'");
		}
		return $query->result();
	}
	

	public function load_transactions_with_name($oid, $tid) {
		$query = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id WHERE a.it_owner='$oid'AND a.it_id='$tid'");
		return $query->result();
	}

	public function load_transaction_details($oid, $tid) {
		$query = $this->db->query("SELECT * FROM i_txns_details AS a LEFT JOIN i_products AS b ON a.itd_p_id=b.ip_id LEFT JOIN i_tax_group AS c ON a.itp_tax_group_id=c.ittxg_id WHERE a.itp_owner='$oid'AND a.itd_t_id='$tid'");
		return $query->result();
	}

	public function load_transaction_transport_details($oid, $tid) {
		$query = $this->db->query("SELECT * FROM i_txn_transport_details AS a LEFT JOIN i_expenses AS b ON a.ittd_expense=b.ie_id WHERE a.ittd_owner='$oid' AND a.ittd_txn_id = '$tid'");
		return $query->result();
	}

	public function load_transaction_prod_tax_details($oid, $tid) {
		$query = $this->db->query("SELECT * FROM i_txn_product_taxes WHERE itpt_owner='$oid' AND itpt_txn_id = '$tid'");
		return $query->result();
	}

	public function search_transactions($oid, $type, $keywords, $status) {
	    $s = implode("', '", $status);
		$query = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN i_contacts AS b ON a.it_c_id=b.ic_id WHERE a.it_owner='$oid' AND a.it_type='$type' AND a.it_status IN ('$s') AND b.ic_name LIKE '%$keywords%'");
		return $query->result();
	}

	public function save_purchase($oid, $uid, $cid, $tdate, $tnum, $tnote, $tamt, $tstatus, $prod, $qty, $rate, $amt, $dt, $tax_group, $alias, $upd="false", $type) {
		$jamt=0;
		for ($i=0; $i < count($amt) ; $i++) { 
			$jamt+=$amt[$i];	
		}
		$data = array(
			'it_type' => $type,
			'it_c_id' => $cid,
			'it_date' => $tdate,
			'it_txn_no' => $tnum,
			'it_note' => $tnote,
			'it_amount' => $tamt,
			'it_status' => $tstatus,
			'it_owner' => $oid,
			'it_created' => $dt,
			'it_created_by' => $uid );
		$this->db->insert('i_txns', $data);
		$tid = $this->db->insert_id();
		
		$que = $this->db->query("SELECT * FROM i_contacts WHERE ic_owner='$oid' AND ic_id='$cid'");
		$res = $que->result();
		$nme = $res[0]->ic_name;
		
		$this->load->model('distributors/Accounting_Model','acc_model');
		$g = [];
		$this->acc_model->save_journal_entry($oid, $tdate, 'Purchase Account', $nme, 'Purchase Txn No: '.$tnum, $jamt, 'Purchase', $tid,  $dt, $uid, $g, null);
		
		
        $tamt=0;
        $ledger_taxation = [];$ledger_flag=false;
		$this->load->model('distributors/Products_Model','product_model');
		
		for ($i=0; $i < count($prod) ; $i++) { 
			$pid = $this->product_model->save_product_by_name($oid, $uid, $dt, $prod[$i]);
			$tax = $this->product_model->get_product_tax_details($oid, $pid, $amt[$i], $tax_group[$i], $qty[$i]);
			
			
			if(count($tax['taxtotid']) > 0) {
                $tamt=$tamt+$amt[$i] + $tax['taxtotamt'][0];
                
                $data = array(
    				'itd_t_id' => $tid,
    				'itd_p_id' => $pid,
    				'itp_qty' => $qty[$i],
    				'itp_rate' => $rate[$i],
    				'itp_value' => $amt[$i],
    				'itp_tax_group_id' => $tax['taxtotid'][0],
    				'itp_tax' => $tax['taxtotamt'][0],
    				'itp_amount' => $amt[$i],
    				'itp_alias' => $alias[$i],
    				'itp_owner' => $oid,
    				'itp_created' => $dt,
    				'itp_created_by' => $uid );    
            } else {
                $tamt=$tamt+$amt[$i];
                $data = array(
    				'itd_t_id' => $tid,
    				'itd_p_id' => $pid,
    				'itp_qty' => $qty[$i],
    				'itp_rate' => $rate[$i],
    				'itp_value' => $amt[$i],
    				'itp_tax_group_id' => 0,
    				'itp_tax' => 0,
    				'itp_amount' => $amt[$i],
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
			if($upd == "true") {
			 //   $this->update_inventory($oid, $uid, $cid, $tid, $tmptid, 'credit',  array('0' => $pid),  array('0' => $qty[$i]), null, $dt, null);
			}
		}
		
		$this->db->where(array('it_id' => $tid, 'it_owner' => $oid, 'it_type' => $type));
		$this->db->update('i_txns', array('it_amount' => $tamt));
		
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
		
// 		for($i=0;$i<count($fnl_taxation);$i++) {
// 		    $this->acc_model->save_journal_entry($oid, $tdate, $fnl_taxation[$i]['name'], $nme, 'Taxation on Purchase Txn No: '.$tnum, $fnl_taxation[$i]['amt'], 'Purchase', $tid,  $dt, $uid, $g, null);
// 		}
	}

	public function update_purchase($oid, $uid, $cid, $tdate, $tnum, $tnote, $tamt, $tstatus, $prod, $qty, $rate, $amt, $dt, $tid, $tax_group, $alias, $upd="false") {
		$jamt=0;
		for ($i=0; $i < count($amt) ; $i++) { 
			$jamt+=$amt[$i];	
		}
		$data = array(
			'it_type' => 'Purchase',
			'it_c_id' => $cid,
			'it_date' => $tdate,
			'it_txn_no' => $tnum,
			'it_note' => $tnote,
			'it_amount' => $tamt,
			'it_status' => $tstatus,		
			'it_modified' => $dt,
			'it_modified_by' => $uid );
		$upd_data = array('it_id' => $tid , 'it_owner' => $oid );
		$this->db->where($upd_data);
		$this->db->update('i_txns', $data);
		$this->load->model('distributors/Accounting_Model','acc_model');
		
		$this->db->where(array('inaje_link_from' => 'Purchase', 'inaje_link_id' => $tid, 'inaje_owner' => $oid));
		$this->db->delete('i_n_ac_journal_entries');
		
		$que = $this->db->query("SELECT * FROM i_contacts WHERE ic_owner='$oid' AND ic_id='$cid'");
		$res = $que->result();
		$nme = $res[0]->ic_name;
		
		$g = [];
		$this->acc_model->save_journal_entry($oid, $tdate, 'Purchase Account', $nme, 'Purchase Txn No: '.$tnum, $jamt, 'Purchase', $tid,  $dt, $uid, $g, null);
		
		$ledger_taxation = [];$ledger_flag=false;
		$this->load->model('distributors/Products_Model','product_model');

		$del_data = array('itd_t_id' => $tid , 'itp_owner' => $oid );
		$this->db->where($del_data);
		$this->db->delete('i_txns_details');

        $tamt = 0;
		for ($i=0; $i < count($prod) ; $i++) { 
			$pid = $this->product_model->save_product_by_name($oid, $uid, $dt, $prod[$i]);
			$tax = $this->product_model->get_product_tax_details($oid, $pid, $amt[$i], $tax_group[$i], $qty[$i]);
			
			if(count($tax['taxtotid']) > 0) {
                $tamt=$tamt+$amt[$i] + $tax['taxtotamt'][0];
                $data = array(
    				'itd_t_id' => $tid,
    				'itd_p_id' => $pid,
    				'itp_qty' => $qty[$i],
    				'itp_rate' => $rate[$i],
    				'itp_value' => $amt[$i],
    				'itp_tax_group_id' => $tax['taxtotid'][0],
    				'itp_tax' => $tax['taxtotamt'][0],
    				'itp_amount' => $amt[$i],
    				'itp_alias' => $alias[$i],
    				'itp_owner' => $oid,
    				'itp_created' => $dt,
    				'itp_created_by' => $uid );    
            } else {
                $tamt=$tamt+$amt[$i];
                $data = array(
    				'itd_t_id' => $tid,
    				'itd_p_id' => $pid,
    				'itp_qty' => $qty[$i],
    				'itp_rate' => $rate[$i],
    				'itp_value' => $amt[$i],
    				'itp_tax_group_id' => 0,
    				'itp_tax' => 0,
    				'itp_amount' => $amt[$i],
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
			if($upd == "true") {
			    $this->update_inventory($oid, $uid, $cid, $tid, $tmptid, 'credit', array('0' => $pid),  array('0' => $qty[$i]), null, $dt, null);
			}
		}
		
		$this->db->where(array('it_id' => $tid, 'it_owner' => $oid, 'it_type' => 'Purchase'));
		$this->db->update('i_txns', array('it_amount' => $tamt));
		
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
		    $this->acc_model->save_journal_entry($oid, $tdate, $fnl_taxation[$i]['name'], $nme, 'Taxation on Purchase Txn No: '.$tnum, $fnl_taxation[$i]['amt'], 'Purchase', $tid,  $dt, $uid, $g, null);
		}
		
		
		return json_encode($fnl_taxation);
	}

	public function get_inventory_status($oid, $uid, $pid, $limit) {
		if($limit != null) {
			$query = $this->db->query("SELECT * FROM i_inventory WHERE ii_owner='$oid' AND ii_p_id = $pid ORDER BY ii_id DESC LIMIT $limit");
		} else {
			$query = $this->db->query("SELECT * FROM i_inventory WHERE ii_owner='$oid' AND ii_p_id = $pid ORDER BY ii_id DESC");
		}
		
		return $query->result();
	}

	public function get_inventory_all_details($oid, $uid, $pid) {
		$query = $this->db->query("SELECT * FROM i_inventory AS a LEFT JOIN i_contacts AS b ON a.ii_c_id=b.ic_id LEFT JOIN i_txns AS c ON a.ii_order_id=c.it_id LEFT JOIN i_txns_details AS d ON a.ii_order_txn=d.itd_id WHERE a.ii_owner='$oid' AND a.ii_p_id = '$pid' ORDER BY a.ii_id DESC");
		return $query->result();
	}

	public function load_inventory_summary($oid, $uid, $category=null, $name=null, $limit=null) {
		$this->load->model('distributors/Products_Model', 'product_model');
		$prod = $this->product_model->load_products($oid, null, null, $category, $name);
        $final = [];
        $loop = count($prod);
        if($limit != null) {
            if(count($prod) > $limit) {
                $loop = $limit;
            } else {
                $loop = count($prod);
            }
                
        }
        // return $prod;
		for ($i=0; $i < $loop; $i++) { 
			$txn = $this->get_inventory_status($oid, $uid, $prod[$i]->ip_id, 1);
			$bal = 0;
			if (count($txn) > 0) {
				$bal = $txn[0]->ii_balance;
			}
			if($prod[$i]->ip_image != ""  || $prod[$i]->ip_image != null) {
				array_push($final, array('id'=> $prod[$i]->ip_id, 'product' => $prod[$i]->ip_name , 'product_image' => base_url().'assets/uploads/'.$oid.'/'.$prod[$i]->ip_id.'/'.$prod[$i]->ip_image , 'balance' => $bal , 'threshold' => $prod[$i]->ip_lower_limit));
			} else {
				array_push($final, array('id'=> $prod[$i]->ip_id, 'product' => $prod[$i]->ip_name , 'product_image' => '' , 'balance' => $bal  , 'threshold' => $prod[$i]->ip_lower_limit));
			}
			
		}
		return $final;
	}

    public function update_inventory_new($oid, $uid, $dt, $type, $tid, $reset) {
        $query = $this->db->query("SELECT * FROM i_txns WHERE it_id='$tid' AND it_owner='$oid'");
		$result = $query->result();
		$cid = $result[0]->it_c_id;
		$orderid = $result[0]->it_txn_no;
		
		$query=$this->db->query("SELECT * FROM i_txns_details WHERE itd_t_id='$tid' AND itp_owner='$oid'");
		$result = $query->result();
		
		$products=[];
		$inward=[];
		$outward=[];
		
		for($i=0;$i<count($result); $i++) {
		    $product[$i] = $result[$i]->itd_p_id;
		    $inward[$i] = 0;
		    $outward[$i] = $result[$i]->itp_qty;
		}
		$this->update_inventory($oid, $uid, $cid, $orderid, $tid, $type, $product, $inward, $outward, $dt, 'true');
    }
    
    public function update_inventory_txnwise($oid, $tid, $del=null) {
        if($del == null) {
            $query = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN `i_txns_details` AS b ON a.it_id=b.itd_t_id WHERE b.itd_t_id='$tid' AND b.itp_owner='$oid'");
            $result = $query->result();
            
            $this->db->where(array('iin_order_txn' => $tid, 'iin_owner' => $oid));
            $this->db->delete('i_inventory_new');
                
            for($i=0;$i<count($result);$i++) {
                $bal=0;
                $pid=$result[$i]->itd_p_id;
                $qty=$result[$i]->itp_qty;
                
                $que = $this->db->query("SELECT * FROM i_inventory_accounts WHERE iia_owner='$oid' AND iia_star='1'");
                $res = $que->result();
                
                $fr=0; $fr_ty="";
                if(count($res) > 0) {
                    $fr=$res[0]->iia_id;
                    $fr_ty="account";
                }
                
                $this->db->insert('i_inventory_new', array(
                    'iin_from' => $fr,
                    'iin_from_type' => $fr_ty,
                    'iin_to' => $result[$i]->it_c_id,
                    'iin_to_type' => 'contact',
                    'iin_p_id' => $pid,
                    'iin_inward' => $qty,
                    'iin_date' => $result[$i]->it_date,
                    'iin_order_id' => $result[$i]->it_txn_no,
                    'iin_order_txn' => $result[$i]->it_id,
                    'iin_owner' => $oid,
                    'iin_created' => $result[$i]->it_created,
                    'iin_created_by' => $result[$i]->it_created_by));
            }
        } else {
            $this->db->where(array('iin_order_txn' => $tid, 'iin_owner' => $oid));
            $this->db->delete('i_inventory_new');
        }
        // return $a;
    }

    public function update_inventory_txnwise_purchase($oid, $tid, $del=null) {
        if($del == null) {
            
            $query = $this->db->query("SELECT * FROM i_txns AS a LEFT JOIN `i_txns_details` AS b ON a.it_id=b.itd_t_id WHERE b.itd_t_id='$tid' AND b.itp_owner='$oid'");
            $result = $query->result();
            
            $this->db->where(array('iin_order_txn' => $tid, 'iin_owner' => $oid));
            $this->db->delete('i_inventory_new');
                
            for($i=0;$i<count($result);$i++) {
                $bal=0;
                $pid=$result[$i]->itd_p_id;
                $qty=$result[$i]->itp_qty;
                
                $que = $this->db->query("SELECT * FROM i_inventory_accounts WHERE iia_owner='$oid' AND iia_star='1'");
                $res = $que->result();
                
                $fr=0; $fr_ty="";
                if(count($res) > 0) {
                    $to=$res[0]->iia_id;
                    $to_ty="account";
                }
                
                $this->db->insert('i_inventory_new', array(
                    'iin_from' => $result[$i]->it_c_id,
                    'iin_from_type' => 'contact',
                    'iin_to' => $to,
                    'iin_to_type' => $to_ty,
                    'iin_p_id' => $pid,
                    'iin_inward' => $qty,
                    'iin_date' => $result[$i]->it_date,
                    'iin_order_id' => $result[$i]->it_txn_no,
                    'iin_order_txn' => $result[$i]->it_id,
                    'iin_owner' => $oid,
                    'iin_created' => $result[$i]->it_created,
                    'iin_created_by' => $result[$i]->it_created_by));
            }
        } else {
            $this->db->where(array('iin_order_txn' => $tid, 'iin_owner' => $oid));
            $this->db->delete('i_inventory_new');
        }
        // return $a;
    }

	public function update_inventory($oid, $uid, $cid, $orderid, $ordertxnid, $type, $product, $inward, $outward, $dt, $reset) {
		for ($i=0; $i < count($product) ; $i++) { 
			$txns = $this->get_inventory_status($oid, $uid, $product[$i], 1);
			$bal = 0;
			if (count($txns) > 0) {
				$bal = $txns[0]->ii_balance;
			}
			$in = 0; $out=0;
			if ($inward!=null) {$in = $inward[$i]; $bal=$bal+$in; }
			if ($outward!=null) {$out = $outward[$i]; $bal=$bal-$out; }

			if ($reset!==null) {
				$del_data = array( 'ii_order_id' => $orderid, 'ii_owner' => $oid );
				$this->db->where($del_data);
				$this->db->delete('i_inventory');
			}

			 $data = array(
				'ii_c_id' => $cid,
				'ii_order_id' => $orderid,
				'ii_order_txn' => $ordertxnid,
				'ii_type' => $type,
				'ii_p_id' => $product[$i],
				'ii_inward' => $in,
				'ii_outward' => $out,
				'ii_balance' => $bal,
				'ii_owner' => $oid,
				'ii_created' => $dt,
				'ii_created_by' => $uid );
			 $this->db->insert('i_inventory', $data);
		}

		return $orderid;
	}

	public function update_order_status($oid, $uid, $tid, $status, $dt) {
		$data = array(
			'it_status' => $status,		
			'it_modified' => $dt,
			'it_modified_by' => $uid );

		$upd_data = array('it_id' => $tid , 'it_owner' => $oid );
		$this->db->where($upd_data);
		$this->db->update('i_txns', $data);
		
		return $tid;
	}

	public function update_orders($oid, $uid, $tid, $tstatus, $trow, $trate, $trequest, $tapprove, $dt, $est_pending) {
		$tamt = 0;
		$tflag = false;
		$tbal = []; $tbaltotal = 0;
		for ($i=0; $i < count($trow) ; $i++) { 
			$tamt=$tamt+($trate[$i] * $tapprove[$i]);
			$tmptbal = $trequest[$i] - $tapprove[$i];
			if ($tmptbal > 0) $tflag = true; array_push($tbal, array('row' => $trow[$i] , 'bal' => $tmptbal)); $tbaltotal+=$tmptbal*$trate[$i];
		}

		$data = array(
			'it_amount' => $tamt,
			'it_status' => $tstatus,		
			'it_modified' => $dt,
			'it_modified_by' => $uid,
			'it_notify' => 'done');

		$upd_data = array('it_id' => $tid , 'it_owner' => $oid );
		$this->db->where($upd_data);
		$this->db->update('i_txns', $data);
		
		for ($i=0; $i < count($trow) ; $i++) { 
			$tmpamt = ($trate[$i] * $tapprove[$i]);
			$data = array(
				'itp_qty' => $tapprove[$i],
				'itp_rate' => $trate[$i],
				'itp_value' => $tmpamt,
				'itp_amount' => $tmpamt,
				'itp_modified' => $dt,
				'itp_modified_by' => $uid );

			$upd_data = array(
				'itd_id' => $trow[$i],
				'itp_owner' => $oid
			);

			$this->db->where($upd_data);
			$this->db->update('i_txns_details', $data);

			$data = array(
				'ith_td_id' => $trow[$i],
				'ith_req_qty' => $trequest[$i],
				'ith_app_qty' => $tapprove[$i],
				'ith_rate' => $trate[$i],
				'ith_owner' => $oid,
				'ith_approved' => $dt,
				'ith_approved_by' => $uid
			);
			$this->db->insert('i_txns_history', $data);
		}

		if ($tflag == true) {
			$this->load->model('distributors/Products_Model','product_model');
			$nexttid = $this->product_model->load_order_number($oid, 'Orders');

			$query = $this->db->query("SELECT * FROM i_txns WHERE it_owner = '$oid' AND it_id = '$tid'");
			$result = $query->result();

			$data = array(
				'it_type' => 'Orders',
				'it_c_id' => $result[0]->it_c_id,
				'it_date' => $est_pending,
				'it_txn_no' => $nexttid,
				'it_note' => '',
				'it_amount' => $tbaltotal,
				'it_status' => 'pending',
				'it_owner' => $oid,
				'it_created' => $dt,
				'it_created_by' => $uid,
				'it_order_txn' => $nexttid);
			$this->db->insert('i_txns', $data);
			$tmptxnid = $this->db->insert_id();

			for ($j=0; $j < count($tbal); $j++) { 
				$m = $tbal[$j]['row'];
				$b = $tbal[$j]['bal'];

				$query = $this->db->query("SELECT * FROM i_txns_details WHERE itd_id = '$m' AND itp_owner = '$oid'");
				$result = $query->result();

				$p=0; $r=0; $v=0;
				if (count($result) > 0) {
					$p = $result[0]->itd_p_id;
					$r = $result[0]->itp_rate;
					$v=$b*$r;
				}

				$data = array(
					'itd_t_id' => $tmptxnid,
					'itd_p_id' => $p,
					'itp_qty' => $b,
					'itp_rate' => $r,
					'itp_value' => $v,
					'itp_amount' => $v,
					'itp_owner' => $oid,
					'itp_created' => $dt,
					'itp_created_by' => $uid,
					 );
				$this->db->insert('i_txns_details', $data);
			}
		}
		return $tid;
	}

	public function total_outstanding($oid, $cid=null) {
		if($cid == null) {
			$query = $this->db->query("SELECT SUM(it_amount) AS amount FROM i_txns WHERE it_status = 'payment_pending' AND it_owner='$oid'");
		} else {
			$query = $this->db->query("SELECT SUM(it_amount) AS amount FROM i_txns WHERE it_status = 'payment_pending' AND it_owner='$oid' AND it_c_id='$cid'");
		}
		return $query->result();

	}

	public function load_expenses($oid, $uid=null, $status, $tid= null) {
		if ($tid == null) {
			if ($uid==null) {
				$query = $this->db->query("SELECT * FROM i_expenses WHERE ie_owner = '$oid' AND ie_status = '$status'");
			} else {
				$query = $this->db->query("SELECT * FROM i_expenses WHERE ie_owner = '$oid' AND ie_status = '$status' AND ie_created_by = '$uid'");
			}
		} else {
			if ($uid==null) {
				$query = $this->db->query("SELECT * FROM i_expenses WHERE ie_owner = '$oid' AND ie_status = '$status' AND ie_id='$tid'");
			} else {
				$query = $this->db->query("SELECT * FROM i_expenses WHERE ie_owner = '$oid' AND ie_status = '$status' AND ie_created_by = '$uid' AND ie_id='$tid'");
			}
		}
		
		return $query->result();
	}

	public function save_expense($oid, $uid, $desc, $amt, $edt, $status, $order, $dt) {
		$data = array(
			'ie_description' => $desc,
			'ie_amount' => $amt,
			'ie_order_id' => $order,
			'ie_status' => $status,
			'ie_date' => $edt,
			'ie_owner' => $oid,
			'ie_created_by' => $uid,
			'ie_created' => $dt );
		$this->db->insert('i_expenses', $data);
		return $this->db->insert_id();
	}

	public function update_expense($oid, $uid, $desc, $amt, $edt, $status, $order, $dt, $eid) {
		$data = array(
			'ie_description' => $desc,
			'ie_amount' => $amt,
			'ie_order_id' => $order,
			'ie_status' => $status,
			'ie_date' => $edt);
		$upddata = array('ie_id' => $eid, 'ie_owner' => $oid);
		$this->db->where($upddata);
		$this->db->update('i_expenses', $data);
		return $eid;
	}

	public function update_invoice($oid, $uid, $cid, $tdate, $tnum, $tnote, $tamt, $tstatus, $prod, $qty, $rate, $amt, $tax_group, $dt, $tid, $discount, $freight, $credit,$alias ) {
		$tamt = 0;
		$jamt=0;
		for ($i=0; $i < count($amt) ; $i++) { 
			$jamt+=$amt[$i];	
		}
		
		$this->load->model('distributors/Products_Model','product_model');

        $query = $this->db->query("SELECT * FROM i_txns WHERE it_id = '$tid' AND it_owner='$oid'");
        $result = $query->result();
        
        if($result[0]->it_type == "Delivery") {
            
            $data = array(
    			'it_type' => 'Sale',
    			'it_c_id' => $cid,
    			'it_date' => $tdate,
    			'it_txn_no' => $tnum,
    			'it_inv_note' => $tnote,
    			'it_amount' => $tamt,
    			'it_status' => $tstatus,
    			'it_owner' => $oid,
    			'it_created' => $dt,
    			'it_created_by' => $uid,
    			'it_discount' => $discount,
    			'it_freight' => $freight,
    			'it_credit' => $credit,
    			'it_mode' => $result[0]->it_mode,
    			'it_order_txn' => $tid
    			);
    		$this->db->insert('i_txns', $data);
    		$newtid = $this->db->insert_id();
    		
    		$this->load->model('distributors/Accounting_Model','acc_model');
		
    		$this->db->where(array('inaje_link_from' => 'Invoice', 'inaje_link_id' => $newtid, 'inaje_owner' => $oid));
    		$this->db->delete('i_n_ac_journal_entries');
    		
    		$que = $this->db->query("SELECT * FROM i_contacts WHERE ic_owner='$oid' AND ic_id='$cid'");
    		$res = $que->result();
    		$nme = $res[0]->ic_name;
    		
    		$g = [];
    		
    		
    		$ledger_taxation = [];$ledger_flag=false;
    		
    		for ($i=0; $i < count($prod) ; $i++) { 
    			$pid = $this->product_model->save_product_by_name($oid, $uid, $dt, $prod[$i]); 
    			$tax = $this->product_model->get_product_tax_details($oid, $pid, $amt[$i], $tax_group[$i], $qty[$i]);
    
                if(count($tax['taxtotid']) > 0) {
                    $tamt=$tamt+$amt[$i] + $tax['taxtotamt'][0];
                    $data = array(
        				'itd_t_id' => $newtid,
        				'itd_p_id' => $pid,
        				'itp_qty' => $qty[$i],
        				'itp_rate' => $rate[$i],
        				'itp_value' => $amt[$i],
        				'itp_tax_group_id' => $tax['taxtotid'][0],
        				'itp_tax' => $tax['taxtotamt'][0],
        				'itp_amount' => $amt[$i],
        				'itp_alias' => $alias[$i],
        				'itp_owner' => $oid,
        				'itp_created' => $dt,
        				'itp_created_by' => $uid );    
                } else {
                    $tamt=$tamt+$amt[$i];
                    $data = array(
        				'itd_t_id' => $newtid,
        				'itd_p_id' => $pid,
        				'itp_qty' => $qty[$i],
        				'itp_rate' => $rate[$i],
        				'itp_value' => $amt[$i],
        				'itp_tax_group_id' => 0,
        				'itp_tax' => 0,
        				'itp_amount' => $amt[$i],
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
    					'itpt_txn_id' => $newtid,
    					'itpt_td_id' => $tmptid,
    					'itpt_tx_id' => $tax['taxid'][$ij],
    					'itpt_p_id' => $pid,
    					'itpt_t_name' => $tax['taxname'][$ij],
    					'itpt_t_amount' => $tax['taxamt'][$ij],
    					'itpt_owner' => $oid );
    				$this->db->insert('i_txn_product_taxes', $data);
    			}
    		}
    		
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
    		    $this->acc_model->save_journal_entry($oid, $tdate, $nme, $fnl_taxation[$i]['name'], 'Taxation on Invoice Txn No: '.$tnum, $fnl_taxation[$i]['amt'], 'Invoice', $newtid,  $dt, $uid, $g, null);
    		}
    		
    		
    		if ($discount !== "") {
    			$tamt = $tamt - $discount;
    		}
    		
    		$data = array(
    			'it_amount' => $tamt,
    			);
    			
    	    $upd_data = array('it_id' => $newtid , 'it_owner' => $oid );
    		$this->db->where($upd_data);
    		$this->db->update('i_txns', $data);
    		
    		$this->acc_model->save_journal_entry($oid, $tdate, $nme, 'Sales Account', 'Sale Txn No: '.$tnum, $jamt, 'Invoice', $newtid,  $dt, $uid, $g, null);
    		
    		$data = array(
    			'it_status' => 'invoiced',
    			);
    			
    	    $upd_data = array('it_id' => $tid , 'it_owner' => $oid );
    		$this->db->where($upd_data);
    		$this->db->update('i_txns', $data);
    		
    		$query = $this->db->query("SELECT * FROM i_txn_transport_details WHERE ittd_txn_id = '$tid' AND ittd_owner='$oid'");
    		$result = $query->result();
    		
    		if(count($result) > 0) {
    		    $ndat = array(
    		        'ittd_txn_id' => $newtid,
    		        'ittd_transporter' => $result[0]->ittd_transporter,
    		        'ittd_lrno' => $result[0]->ittd_lrno,
    		        'ittd_date' => $result[0]->ittd_date,
    		        'ittd_transporter_gstno' => $result[0]->ittd_transporter_gstno,
    		        'ittd_state' => $result[0]->ittd_state,
    		        'ittd_owner' => $oid,
    		        'ittd_expense' => $result[0]->ittd_expense
    		        );
    		        
    		    $this->db->insert('i_txn_transport_details', $ndat);
    		}
        } else {
            $newtid = $tid;
            $this->load->model('distributors/Accounting_Model','acc_model');
		
    		$this->db->where(array('inaje_link_from' => 'Invoice', 'inaje_link_id' => $newtid, 'inaje_owner' => $oid));
    		$this->db->delete('i_n_ac_journal_entries');
    		
    		$que = $this->db->query("SELECT * FROM i_contacts WHERE ic_owner='$oid' AND ic_id='$cid'");
    		$res = $que->result();
    		$nme = $res[0]->ic_name;
    		
    		$g = [];
    		
    		
    		$ledger_taxation = [];$ledger_flag=false;
    		
            $del_data = array('itd_t_id' => $tid , 'itp_owner' => $oid );
    		$this->db->where($del_data);
    		$this->db->delete('i_txns_details');
    
    		$del_data = array('itpt_txn_id' => $tid , 'itpt_owner' => $oid );
    		$this->db->where($del_data);
    		$this->db->delete('i_txn_product_taxes');
    
    		for ($i=0; $i < count($prod) ; $i++) { 
    			$pid = $this->product_model->save_product_by_name($oid, $uid, $dt, $prod[$i]); 
    			$tax = $this->product_model->get_product_tax_details($oid, $pid, $amt[$i], $tax_group[$i], $qty[$i]);
    
                if(count($tax['taxtotid']) > 0) {
                    $tamt=$tamt+$amt[$i] + $tax['taxtotamt'][0];
                    $data = array(
        				'itd_t_id' => $tid,
        				'itd_p_id' => $pid,
        				'itp_qty' => $qty[$i],
        				'itp_rate' => $rate[$i],
        				'itp_value' => $amt[$i],
        				'itp_tax_group_id' => $tax['taxtotid'][0],
        				'itp_tax' => $tax['taxtotamt'][0],
        				'itp_amount' => $amt[$i],
        				'itp_alias' => $alias[$i],
        				'itp_owner' => $oid,
        				'itp_created' => $dt,
        				'itp_created_by' => $uid );    
                } else {
                    $tamt=$tamt+$amt[$i];
                    $data = array(
        				'itd_t_id' => $tid,
        				'itd_p_id' => $pid,
        				'itp_qty' => $qty[$i],
        				'itp_rate' => $rate[$i],
        				'itp_value' => $amt[$i],
        				'itp_tax_group_id' => 0,
        				'itp_tax' => 0,
        				'itp_amount' => $amt[$i],
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
    		    $this->acc_model->save_journal_entry($oid, $tdate, $nme, $fnl_taxation[$i]['name'], 'Taxation on Invoice Txn No: '.$tnum, $fnl_taxation[$i]['amt'], 'Invoice', $newtid,  $dt, $uid, $g, null);
    		}
    		
    		if ($discount !== "") {
    			$tamt = $tamt - $discount;
    		}
    		
    		$data = array(
    			'it_type' => 'Sale',
    			'it_c_id' => $cid,
    			'it_date' => $tdate,
    			'it_txn_no' => $tnum,
    			'it_inv_note' => $tnote,
    			'it_amount' => $tamt,
    			'it_status' => $tstatus,
    			'it_discount' => $discount,
    			'it_freight' => $freight,
    			'it_credit' => $credit,		
    			'it_modified' => $dt,
    			'it_modified_by' => $uid );
    		########### CULPRIT ######################	
    	    $this->db->where(array('it_id' => $tid , 'it_owner' => $oid ));
    		$this->db->update('i_txns', $data);
    		########### CULPRIT ######################
    		$this->acc_model->save_journal_entry($oid, $tdate, $nme, 'Sales Account', 'Sale Txn No: '.$tnum, $jamt, 'Invoice', $newtid,  $dt, $uid, $g, null);
        }
    		
		return $newtid;
	}
	
	public function get_order_modes($oid) {
	    $query = $this->db->query("SELECT it_mode FROM i_txns GROUP BY it_mode");
	    return $query->result();
	}
	
	public function save_orders($oid, $uid, $cid, $tdate, $tnum, $tnote, $tamt, $tstatus, $prod, $qty, $rate, $amt, $dt, $mode, $type) {
		$tamt = 0;
		for($i=0;$i<count($amt); $i++) {
		    $tamt+=$amt[$i];
		}
		
		$data = array(
			'it_type' => $type,
			'it_c_id' => $cid,
			'it_date' => $tdate,
			'it_txn_no' => $tnum,
			'it_note' => $tnote,
			'it_amount' => $tamt,
			'it_status' => $tstatus,
			'it_owner' => $oid,
			'it_created' => $dt,
			'it_mode' => $mode,
			'it_created_by' => $uid,
			'it_order_txn' => $tnum,
			'it_notify' => 'done');
		$this->db->insert('i_txns', $data);
		$tid = $this->db->insert_id();

		$this->load->model('distributors/Products_Model','product_model');
		for ($i=0; $i < count($prod) ; $i++) { 
			$pid = $this->product_model->save_product_by_name($oid, $uid, $dt, $prod[$i]);
			$data = array(
				'itd_t_id' => $tid,
				'itd_p_id' => $pid,
				'itp_qty' => $qty[$i],
				'itp_rate' => $rate[$i],
				'itp_value' => $amt[$i],
				'itp_tax_group_id' => 0,
				'itp_tax' => 0,
				'itp_amount' => $amt[$i],
				'itp_owner' => $oid,
				'itp_created' => $dt,
				'itp_created_by' => $uid );
			$this->db->insert('i_txns_details', $data);
			$tmptid = $this->db->insert_id();
		}
		return $tid;
	}
	
	public function save_cd_notes($oid, $uid, $cid, $tdate, $tnum, $tnote, $tamt, $tstatus, $prod, $qty, $rate, $amt, $tax_group, $alias, $dt, $type, $link=null, $type_sec) {
		for ($i=0; $i < count($rate) ; $i++) { 
			$tamt=$tamt+($rate[$i]*$qty[$i]);	
		}
		$data = array(
			'it_type' => $type,
			'it_c_id' => $cid,
			'it_date' => $tdate,
			'it_txn_no' => $tnum,
			'it_note' => $tnote,
			'it_amount' => $tamt,
			'it_status' => $tstatus,
			'it_owner' => $oid,
// 			'it_txn_link' => $link,
			'it_type_sec' => $type_sec,
			'it_created' => $dt,
			'it_created_by' => $uid );
			
		$this->db->insert('i_txns', $data);
		$tid = $this->db->insert_id();
		
		$txncode = '';
		if($type=='Credit Note') {
		    if($type_sec=='Delivery') {
		        $txncode = 'Credit Delivery';
		        $query = $this->db->query("SELECT * FROM i_txn_nums WHERE itn_type='Credit Delivery'");
		    } else if ($type_sec=='Invoice') {
		        $txncode = 'Credit Invoice';
		        $query = $this->db->query("SELECT * FROM i_txn_nums WHERE itn_type='Credit Invoice'");
		    }
		} else if ($type=='Debit Note') {
		    if($type_sec=='Delivery') {
		        $txncode = 'Debit Delivery';
		        $query = $this->db->query("SELECT * FROM i_txn_nums WHERE itn_type='Debit Delivery'");
		    } else if ($type_sec=='Invoice') {
		        $txncode = 'Debit Invoice';
		        $query = $this->db->query("SELECT * FROM i_txn_nums WHERE itn_type='Debit Invoice'");
		    }
		}
		
		if(count($query->result()) > 0) {
		    $this->db->where(array(
		        'itn_type' => $query->result()[0]->itn_type,
		        'itn_owner' => $oid,
		        ));
		    $this->db->update('i_txn_nums', array(
		        'itn_num' => $query->result()[0]->itn_num + 1,
		        'itn_date' => $tdate
		        ));
		} else {
		    $this->db->insert('i_txn_nums', array(
		        'itn_type' => $txncode,
		        'itn_num' => 1,
		        'itn_date' => $tdate,
		        'itn_owner' => $oid
		        ));
		}
		
		$this->load->model('distributors/Products_Model','product_model');
		for ($i=0;$i<count($prod);$i++) { 
			$pid = $this->product_model->save_product_by_name($oid, $uid, $dt, $prod[$i]);
			$tax = $this->product_model->get_product_tax_details($oid, $pid, $rate[$i], $tax_group[$i], $qty[$i]);
			if(count($tax['taxtotid']) > 0) {
                $tamt=$tamt+($rate[$i]*$qty[$i]) + $tax['taxtotamt'][0];
                $data = array(
    				'itd_t_id' => $tid,
    				'itd_p_id' => $pid,
    				'itp_qty' => $qty[$i],
    				'itp_rate' => $rate[$i],
    				'itp_value' => ($rate[$i]*$qty[$i]),
    				'itp_tax_group_id' => $tax['taxtotid'][0],
    				'itp_tax' => $tax['taxtotamt'][0],
    				'itp_amount' => (($rate[$i]*$qty[$i]) + $tax['taxtotamt'][0]),
    				'itp_alias' => $alias[$i],
    				'itp_owner' => $oid,
    				'itp_created' => $dt,
    				'itp_created_by' => $uid );    
            } else {
                $tamt=$tamt+($rate[$i]*$qty[$i]);
                $data = array(
    				'itd_t_id' => $tid,
    				'itd_p_id' => $pid,
    				'itp_qty' => $qty[$i],
    				'itp_rate' => $rate[$i],
    				'itp_value' => $amt[$i],
    				'itp_tax_group_id' => 0,
    				'itp_tax' => 0,
    				'itp_amount' => $amt[$i],
    				'itp_alias' => $alias[$i],
    				'itp_owner' => $oid,
    				'itp_created' => $dt,
    				'itp_created_by' => $uid );
            }
            $this->db->insert('i_txns_details', $data);
			$tmptid = $this->db->insert_id();
			
			for ($ij=0; $ij < count($tax['taxid']) ; $ij++) { 
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

// 			$this->update_inventory($oid, $uid, $cid, $tid, $tmptid, 'credit',  array('0' => $pid),  array('0' => $qty[$i]), null, $dt, null);
		}
		
		for($i=0;$i<count($link);$i++) {
		    $t=$link[$i];
		    $que = $this->db->query("SELECT (IFNULL(a.it_amount,0) + IFNULL(e.ie_amount,0)) AS amt, a.it_id AS id FROM i_txns AS a LEFT JOIN i_txn_transport_details AS d ON a.it_id=d.ittd_txn_id LEFT JOIN i_expenses AS e ON d.ittd_expense=e.ie_id WHERE it_id='$t' AND it_owner='$oid'");
		    $res = $que->result();
		    if(count($res) > 0) {
		        $amt=$res[0]->amt;
		    } else {
		        $amt=0;
		    }
		    $this->db->insert('i_txn_payments_links', array('itpl_pay_id' => $tid, 'itpl_txn_id' => $link[$i], 'itpl_owner' => $oid, 'itpl_amt' => $amt, 'itpl_type' => 'credit_note'));
		}
		return $tid;
	}

    public function update_cd_notes($oid, $tid, $uid, $cid, $tdate, $tnum, $tnote, $tamt, $tstatus, $prod, $qty, $rate, $amt, $tax_group, $alias, $dt, $type, $link, $type_sec) {
		for ($i=0; $i < count($rate) ; $i++) { 
			$tamt=$tamt+($rate[$i]*$qty[$i]);	
		}
		$data = array(
			'it_type' => $type,
			'it_c_id' => $cid,
			'it_date' => $tdate,
			'it_txn_no' => $tnum,
			'it_note' => $tnote,
			'it_amount' => $tamt,
			'it_status' => $tstatus,
			'it_owner' => $oid,
// 			'it_txn_link' => $link,
			'it_modified' => $dt,
			'it_modified_by' => $uid,
			'it_type_sec' => $type_sec);
		$this->db->where(array('it_id' => $tid, 'it_owner' => $oid));
		$this->db->update('i_txns', $data);
		
		$txncode = '';
		if($type=='Credit Note') {
		    if($type_sec=='Delivery') {
		        $txncode = 'Credit Delivery';
		        $query = $this->db->query("SELECT * FROM i_txn_nums WHERE itn_type='Credit Delivery'");
		    } else if ($type_sec=='Invoice') {
		        $txncode = 'Credit Invoice';
		        $query = $this->db->query("SELECT * FROM i_txn_nums WHERE itn_type='Credit Invoice'");
		    }
		} else if ($type=='Debit Note') {
		    if($type_sec=='Delivery') {
		        $txncode = 'Debit Delivery';
		        $query = $this->db->query("SELECT * FROM i_txn_nums WHERE itn_type='Debit Delivery'");
		    } else if ($type_sec=='Invoice') {
		        $txncode = 'Debit Invoice';
		        $query = $this->db->query("SELECT * FROM i_txn_nums WHERE itn_type='Debit Invoice'");
		    }
		}
		
		if(count($query->result()) > 0) {
		    $this->db->where(array(
		        'itn_type' => $query->result()[0]->itn_type,
		        'itn_owner' => $oid,
		        ));
		    $this->db->update('i_txn_nums', array(
		        'itn_num' => $query->result()[0]->itn_num + 1,
		        'itn_date' => $tdate
		        ));
		} else {
		    $this->db->insert('i_txn_nums', array(
		        'itn_type' => $txncode,
		        'itn_num' => 1,
		        'itn_date' => $tdate,
		        'itn_owner' => $oid
		        ));
		}
		
		
		$this->load->model('distributors/Products_Model','product_model');
		
		$this->db->where(array('itd_t_id' => $tid, 'itp_owner' => $oid));
		$this->db->delete('i_txns_details');
		
		$this->db->where(array('itpt_txn_id' => $tid, 'itpt_owner' => $oid));
		$this->db->delete('i_txn_product_taxes');
		
		
		for ($i=0; $i < count($prod) ; $i++) { 
			$pid = $this->product_model->save_product_by_name($oid, $uid, $dt, $prod[$i]);
			$tax = $this->product_model->get_product_tax_details($oid, $pid, $rate[$i], $tax_group[$i], $qty[$i]);
			if(count($tax['taxtotid']) > 0) {
                $tamt=$tamt+($rate[$i]*$qty[$i]) + $tax['taxtotamt'][0];
                $data = array(
    				'itd_t_id' => $tid,
    				'itd_p_id' => $pid,
    				'itp_qty' => $qty[$i],
    				'itp_rate' => $rate[$i],
    				'itp_value' => ($rate[$i]*$qty[$i]),
    				'itp_tax_group_id' => $tax['taxtotid'][0],
    				'itp_tax' => $tax['taxtotamt'][0],
    				'itp_amount' => (($rate[$i]*$qty[$i]) + $tax['taxtotamt'][0]),
    				'itp_alias' => $alias[$i],
    				'itp_owner' => $oid,
    				'itp_created' => $dt,
    				'itp_created_by' => $uid );    
            } else {
                $tamt=$tamt+($rate[$i]*$qty[$i]);
                $data = array(
    				'itd_t_id' => $tid,
    				'itd_p_id' => $pid,
    				'itp_qty' => $qty[$i],
    				'itp_rate' => $rate[$i],
    				'itp_value' => ($rate[$i]*$qty[$i]),
    				'itp_tax_group_id' => 0,
    				'itp_tax' => 0,
    				'itp_amount' => ($rate[$i]*$qty[$i]),
    				'itp_alias' => $alias[$i],
    				'itp_owner' => $oid,
    				'itp_created' => $dt,
    				'itp_created_by' => $uid );
            }
            $this->db->insert('i_txns_details', $data);
			$tmptid = $this->db->insert_id();
			
			
			for ($ij=0; $ij < count($tax['taxid']) ; $ij++) { 
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

// 			$this->update_inventory($oid, $uid, $cid, $tid, $tmptid, 'credit',  array('0' => $pid),  array('0' => $qty[$i]), null, $dt, null);
		}
		
		$this->db->where(array('itpl_pay_id' => $tid, 'itpl_owner' =>$oid, 'itpl_type' => 'credit_note'));
		$this->db->delete('i_txn_payments_links');
		
		for($i=0;$i<count($link);$i++) {
		    $t=$link[$i];
		    $que = $this->db->query("SELECT (IFNULL(a.it_amount,0) + IFNULL(e.ie_amount,0)) AS amt, a.it_id AS id FROM i_txns AS a LEFT JOIN i_txn_transport_details AS d ON a.it_id=d.ittd_txn_id LEFT JOIN i_expenses AS e ON d.ittd_expense=e.ie_id WHERE it_id='$t' AND it_owner='$oid'");
		    $res = $que->result();
		    if(count($res) > 0) {
		        $amt=$res[0]->amt;
		    } else {
		        $amt=0;
		    }
		    $this->db->insert('i_txn_payments_links', array('itpl_pay_id' => $tid, 'itpl_txn_id' => $link[$i], 'itpl_owner' => $oid, 'itpl_amt' => $amt, 'itpl_type' => 'credit_note'));
		}
		
		return $tid;
	}
	
	public function copy_txn_data($oid, $tid, $type) {
	    
	    $this->load->model('distributors/Products_Model','product_model');
	    $query = $this->db->query("SELECT * FROM i_txn_nums WHERE itn_type='$type' AND itn_owner='$oid' ORDER BY itn_id DESC");
	    $new_txn = $query->result()[0]->itn_num + 1;
	    
	    $query = $this->db->query("SELECT * FROM i_txns WHERE it_owner = '$oid' AND it_id='$tid'");
		$result = $query->result();
		
		$new_tid=0;
		if(count($result) > 0) {
			$this->db->insert('i_txn_nums', array('itn_type' => $type, 'itn_num' => $new_txn, 'itn_owner' => $oid, 'itn_date' => $result[0]->it_date));
		    $data = array(
		        'it_type' => $type,
		        'it_c_id' => $result[0]->it_c_id,
		        'it_date' => $result[0]->it_date,
		        'it_txn_no' => $new_txn,
		        'it_note' => $result[0]->it_note,
		        'it_amount' => $result[0]->it_amount,
		        'it_status' => $result[0]->it_status,
		        'it_owner' => $result[0]->it_owner,
		        'it_created' => $result[0]->it_created,
		        'it_created_by' => $result[0]->it_created_by,
		        'it_modified' => $result[0]->it_modified,
		        'it_modified_by' => $result[0]->it_modified_by,
		        'it_discount' => $result[0]->it_discount,
		        'it_credit' => $result[0]->it_credit,
		        'it_freight' => $result[0]->it_freight,
		        'it_inv_note' => $result[0]->it_inv_note,
		        'it_mode' => $result[0]->it_mode,
		        'it_file' => $result[0]->it_file,
		        'it_order_txn' => $tid,
		        'it_sub_dealer' => $result[0]->it_sub_dealer
		        );
		        
		    $this->db->insert('i_txns', $data);
		    $new_tid = $this->db->insert_id();
		    
		    $que = $this->db->query("SELECT * FROM i_txns_details WHERE itd_t_id='$tid' AND itp_owner='$oid'");
		    $res = $que->result();
		    
		    for($i=0;$i<count($res);$i++) {
		        $dat = array(
		            'itd_t_id' => $new_tid,
		            'itd_p_id' => $res[$i]->itd_p_id,
		            'itp_qty' => $res[$i]->itp_qty,
		            'itp_rate' => $res[$i]->itp_rate,
		            'itp_value' => $res[$i]->itp_value,
		            'itp_tax_group_id' => $res[$i]->itp_tax_group_id,
		            'itp_tax' => $res[$i]->itp_tax,
		            'itp_amount' => $res[$i]->itp_amount,
		            'itp_owner' => $res[$i]->itp_owner,
		            'itp_created' => $res[$i]->itp_created,
		            'itp_created_by' => $res[$i]->itp_created_by,
		            'itp_modified' => $res[$i]->itp_modified,
		            'itp_modified_by' => $res[$i]->itp_modified_by,
		            'itp_alias' => $res[$i]->itp_alias,
		            'itp_ref' => $res[$i]->itd_id
		            );
                $this->db->insert('i_txns_details', $dat);
		    }
		}
        return $new_tid;
		
	}

}