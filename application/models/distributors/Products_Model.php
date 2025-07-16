<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products_Model extends CI_Model {

	public function load_products($oid, $pid=null, $limit = null, $category=null, $name=null) {
		if($limit !== null) {
			if($pid !== null){
				$query = $this->db->query("SELECT * FROM i_products WHERE ip_owner='$oid'AND ip_id='$pid' LIMIT $limit");
			} else if($category !== null) {
			    $query = $this->db->query("SELECT * FROM i_products WHERE ip_owner='$oid' AND ip_category='$category'");
			} else if($name !== null) {
			    $query = $this->db->query("SELECT * FROM i_products WHERE ip_owner='$oid' AND ip_name LIKE '%$name%'");
			} else {
				$query = $this->db->query("SELECT * FROM i_products WHERE ip_owner='$oid' LIMIT $limit");
			}
		} else {
			if($pid !== null){
				$query = $this->db->query("SELECT * FROM i_products WHERE ip_owner='$oid'AND ip_id='$pid'");
			} else if($category !== null) {
			    $query = $this->db->query("SELECT * FROM i_products WHERE ip_owner='$oid' AND ip_category='$category'");
			} else if($name !== null) {
			    $query = $this->db->query("SELECT * FROM i_products WHERE ip_owner='$oid' AND ip_name LIKE '%$name%'");
			} else {
				$query = $this->db->query("SELECT * FROM i_products WHERE ip_owner='$oid'");
			}
			
		}
		return $query->result();
	}

	public function load_products_dealers($oid, $uid, $pid=null, $limit=null) {
		if ($limit !== null) {
			if($pid !== null){
				$query = $this->db->query("SELECT * FROM i_products AS a LEFT JOIN i_p_pricing AS b ON a.ip_id=b.ipp_p_id WHERE a.ip_owner='$oid' AND b.ipp_c_id='$uid' AND a.ip_id='$pid' LIMIT $limit");
			} else {
				$query = $this->db->query("SELECT * FROM i_products AS a LEFT JOIN i_p_pricing AS b ON a.ip_id=b.ipp_p_id WHERE a.ip_owner='$oid' AND b.ipp_c_id='$uid' LIMIT $limit");
			}	
		} else {
			if($pid !== null){
				$query = $this->db->query("SELECT * FROM i_products AS a LEFT JOIN i_p_pricing AS b ON a.ip_id=b.ipp_p_id WHERE a.ip_owner='$oid' AND b.ipp_c_id='$uid' AND a.ip_id='$pid'");
			} else {
				$query = $this->db->query("SELECT * FROM i_products AS a LEFT JOIN i_p_pricing AS b ON a.ip_id=b.ipp_p_id WHERE a.ip_owner='$oid' AND b.ipp_c_id='$uid'");
			}
		}

		
		return $query->result();
	}
	
	public function search_products_dealers($oid, $rid, $keywords=null, $category=null) {
	    if($keywords != null) {
	        $query = $this->db->query("SELECT * FROM i_products AS a LEFT JOIN i_p_pricing AS b ON a.ip_id=b.ipp_p_id WHERE a.ip_owner='$oid' AND b.ipp_c_id='$rid' AND a.ip_name LIKE '%$keywords%'");   
	    } else if($category != null) {
	        $c = $this->load_categories($oid, null, $category);
	        $query = $this->db->query("SELECT * FROM i_products AS a LEFT JOIN i_p_pricing AS b ON a.ip_id=b.ipp_p_id WHERE a.ip_owner='$oid' AND b.ipp_c_id='$rid' AND a.ip_category = '$category'");
	    }
	    return $query->result();
	}
	
	
	
	public function load_product_images($oid, $pid) {
	    $query = $this->db->query("SELECT * FROM i_p_images WHERE ipi_p_id = '$pid' AND ipi_owner = '$oid'");
	    return $query->result();
	}
	
	public function load_categories($oid, $name=null, $id=null ) {
	    if($name!=null) {
	        $query = $this->db->query("SELECT * FROM i_category WHERE ica_owner = '$oid' AND ica_category_name = '$name'");
	    } else if($id!=null) {
	        $query = $this->db->query("SELECT * FROM i_category WHERE ica_owner = '$oid' AND ica_id = '$id'");
	    } else {
	        $query = $this->db->query("SELECT * FROM i_category WHERE ica_owner = '$oid'");
	    }
	    return $query->result();
	}
	
	public function search_products($oid, $keywords) {
		$query = $this->db->query("SELECT * FROM i_products WHERE ip_owner='$oid' AND ip_name LIKE '%$keywords%'");
		return $query->result();
	}
	
	public function filter_products($oid, $ctid) {
	    if($ctid != 0) {
	        $query = $this->db->query("SELECT * FROM i_products WHERE ip_owner='$oid' AND ip_category = '$ctid'");   
	    } else {
	        $query = $this->db->query("SELECT * FROM i_products WHERE ip_owner='$oid'");
	    }
		return $query->result();
	}
	
	public function load_product_units($oid) {
		$query = $this->db->query("SELECT ip_unit FROM i_products WHERE ip_owner = '$oid' GROUP BY ip_unit");
		return $query->result();
	}

	public function save_product($oid, $product, $description, $hsn, $unit, $tax, $limit, $category, $alias) {
	    if($category!="") {
	        $c = $this->load_categories($oid, $category);
    	    if(count($c) > 0) {
    	        $ctid = $c[0]->ica_id;
    	    } else {
    	        $cdata = array(
    	            'ica_category_name' => $category,
    	            'ica_parent_category' => 0,
    	            'ica_owner' => $oid
    	            );
    	       $this->db->insert('i_category', $cdata);
    	       $ctid = $this->db->insert_id();
    	    }
	    } else {
	        $ctid=0;
	    }
	    
	    $query = $this->db->query("SELECT * FROM i_products WHERE ip_owner='$oid' AND ip_name='$product'");
	    $result = $query->result();
	    
	    if(count($result) > 0) {
	        $pid = $result[0]->ip_id;
	    } else {
	        $data = array(
    		    'ip_category' => $ctid,
    			'ip_name' => $product,
    			'ip_alias' => $alias,
    			'ip_description' => $description,
    			'ip_owner' => $oid,
    			'ip_hsn_code' => $hsn,
    			'ip_unit' => $unit,
    			'ip_lower_limit' => $limit);
    		$this->db->insert('i_products', $data);
    		$pid = $this->db->insert_id();
    
    		$data = array(
    			'ipt_p_id' => $pid,
    			'ipt_t_id' => $tax,
    			'ipt_oid' => $oid );
    		$this->db->insert('i_p_taxes', $data);
	    }
		return $pid;
	}

	public function update_product($oid, $pid, $product, $description, $hsn, $unit, $tax, $limit, $category, $alias) {
		if($category!="" || $category!=null) {
		    $c = $this->load_categories($oid, $category);
    	    if(count($c) > 0) {
    	        $ctid = $c[0]->ica_id;
    	    } else {
    	        $cdata = array(
    	            'ica_category_name' => $category,
    	            'ica_parent_category' => 0,
    	            'ica_owner' => $oid
    	            );
    	       $this->db->insert('i_category', $cdata);
    	       $ctid = $this->db->insert_id();
    	    }
		} else {
		    $ctid=0;
		}
    		
	    
	    $data = array(
	        'ip_category' => $ctid,
			'ip_name' => $product,
			'ip_alias' => $alias,
			'ip_description' => $description,
			'ip_hsn_code' => $hsn,
			'ip_unit' => $unit,
			'ip_lower_limit' => $limit);
		$this->db->where('ip_id', $pid);
		$this->db->update('i_products', $data);

		$upd_data = array('ipt_oid' => $oid , 'ipt_p_id' => $pid);
		$this->db->where($upd_data);
		$this->db->delete('i_p_taxes');

		$data = array(
			'ipt_p_id' => $pid,
			'ipt_t_id' => $tax,
			'ipt_oid' => $oid );
		$this->db->insert('i_p_taxes', $data);
		return $pid;
	}

	public function delete_product($oid, $pid) {
		$data = array('ip_id' => $pid , 'ip_owner' => $oid);
		$this->db->where($data);
		$this->db->delete('i_products');
		return $pid;
	}

	public function update_image($oid, $pid, $image) {
		$data = array('ip_image' => $image);
		$upd_data = array('ip_owner'=>$oid,'ip_id'=>$pid);
		$this->db->where($upd_data);
		$this->db->update('i_products', $data);
	}
	
	public function update_image_list($oid, $pid, $image, $flg) {
	    
		if($flg == 1) {
		    $deldata = array('ipi_p_id' => $pid, 'ipi_owner' => $oid);
		    $this->db->where($deldata);
		    $this->db->delete('i_p_images');
		}
		
		$data = array('ipi_p_id' => $pid, 'ipi_img' => $image, 'ipi_owner' => $oid);
		$this->db->insert('i_p_images', $data);
		return $this->db->insert_id();
	}
	
	

	public function update_product_pricing($oid, $dt, $pid, $did, $price) {
		for ($i=0; $i < count($did) ; $i++) { 
			$query = $this->db->query("SELECT * FROM i_p_pricing WHERE ipp_owner='$oid' AND ipp_c_id='$did[$i]' AND ipp_p_id='$pid'");
			$result = $query->result();
			
			$data = array(
				'ipp_p_id' => $pid,
				'ipp_c_id' => $did[$i],
				'ipp_price' => $price,
				'ipp_owner' => $oid
			);

			if(count($result) > 0) {
				$ippid = $result[0]->ipp_id;
				$this->db->where('ipp_id', $ippid);
				$this->db->update('i_p_pricing', $data);
			}  else {
				$this->db->insert('i_p_pricing', $data);
				$ippid = $this->db->insert_id();
			}
		}
		
		return $pid;
	}

	public function save_product_by_name($oid, $uid, $dt, $name) {
		$query = $this->db->query("SELECT * FROM i_products WHERE ip_name = '$name' AND ip_owner='$oid'");
		
		$result = $query->result();

		$vid = 0;
		if(count($result) > 0) {
			$vid = $result[0]->ip_id;
		} else {
			$data = array(
				'ip_name' => $name,
				'ip_created' => $dt,
				'ip_created_by' => $uid,
				'ip_owner' => $oid);
			$this->db->insert('i_products', $data);
			$vid = $this->db->insert_id();
		}
		return $vid;
	}

	public function update_cart($oid, $uid, $pid, $qty, $note, $dt) {
		$query = $this->db->query("SELECT * FROM i_u_cart WHERE iuc_owner = '$oid' AND iuc_u_id = '$uid' AND iuc_u_p_id = '$pid'");
		$result = $query->result();

		$data = array(
			'iuc_u_id' => $uid,
			'iuc_u_owner' => $oid,
			'iuc_u_p_id' => $pid,
			'iuc_u_qty' => $qty,
			'iuc_u_note' => $note,
			'iuc_owner' => $oid,
			'iuc_created' => $dt,
			'iuc_created_by' => $uid );
		$a=0;
		if (count($result) > 0) {
			$a = $result[0]->iuc_id;
			$upd_data = array(
				'iuc_owner' => $oid,
				'iuc_id' => $a,
			);
			$this->db->where($upd_data);
			$this->db->update('i_u_cart', $data);

		} else {
			$this->db->insert('i_u_cart', $data);
			$a = $this->db->insert_id();
		}
		return $a;
	}

	public function load_cart_details($oid, $uid, $cid) {
// 		//$query = $this->db->query("SELECT * FROM i_u_cart AS a LEFT JOIN i_products AS b ON a.iuc_u_p_id=b.ip_id LEFT JOIN i_p_pricing AS c ON a.iuc_u_p_id=c.ipp_p_id and a.iuc_u_id=c.ipp_c_id LEFT JOIN i_contacts AS d ON a.iuc_u_id=d.ic_id WHERE a.iuc_owner='$oid' AND a.iuc_u_id='$uid'");
// 		$query = $this->db->query("SELECT * FROM i_u_cart AS a LEFT JOIN i_products AS b ON a.iuc_u_p_id=b.ip_id LEFT JOIN i_p_pricing AS c ON a.iuc_u_p_id=c.ipp_p_id WHERE a.iuc_owner='1' AND a.iuc_u_id='$uid' and c.ipp_c_id = '$rid'");
// 		return $query->result();
		
		$query = $this->db->query("SELECT * FROM i_u_cart AS a LEFT JOIN i_products AS b ON a.iuc_u_p_id=b.ip_id WHERE a.iuc_owner='1' AND a.iuc_u_id='$uid'");
		$result = $query->result();
		
		$data = [];
		for($i=0;$i<count($result);$i++) {
		    $p = $result[$i]->ip_id;
		    $qu = $this->db->query("SELECT * FROM i_p_pricing WHERE ipp_owner='$oid' AND ipp_p_id='$p' AND ipp_c_id='$cid'");
		    $rs = $qu->result();
		    
		    if(count($rs) > 0) {
		        $rt = $rs[0]->ipp_price;
		    } else {
		        $rt = 0;
		    }
		    array_push($data, array('ip_id' => $result[$i]->ip_id, 'ip_name' => $result[$i]->ip_name, 'ip_description' => $result[$i]->ip_description, 'ipp_price' => $rt, 'iuc_u_qty' => $result[$i]->iuc_u_qty, 'ip_image' => $result[$i]->ip_image ));
		}
		return $data;
	}

	public function load_order_number($oid, $type) {
		$query = $this->db->query("SELECT * FROM i_txns WHERE it_owner='$oid' AND it_type='$type'");
		return count($query->result()) + 1;
	}

	public function confirm_cart($oid, $uid, $cid, $tdate, $tnum, $tnote, $tamt, $tstatus, $prod, $qty, $rate, $amt, $dt, $mode=null, $sub=null) {
		$data = array(
			'it_type' => 'Orders',
			'it_c_id' => $cid,
			'it_date' => $tdate,
			'it_txn_no' => $tnum,
			'it_note' => $tnote,
			'it_amount' => $tamt,
			'it_status' => $tstatus,
			'it_mode' => $mode,
			'it_owner' => $oid,
			'it_created' => $dt,
			'it_created_by' => $uid,
			'it_order_txn' => $tnum,
			'it_notify' => 'pending',
			'it_sub_dealer' => $sub);
		$this->db->insert('i_txns', $data);
		$tid = $this->db->insert_id();

		$this->load->model('distributors/Products_Model','product_model');
		for ($i=0; $i < count($prod) ; $i++) { 
			// $pid = $this->product_model->save_product_by_name($oid, $uid, $dt, $prod[$i]);
			$data = array(
				'itd_t_id' => $tid,
				'itd_p_id' => $prod[$i],
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

			// $this->update_inventory($oid, $uid, $cid, $tid, $tmptid, 'credit', $prod[$i], $qty[$i], 0, $dt, null);
		}
		return $tid;
	}

	public function clear_cart($oid, $uid) {
		$data = array('iuc_u_owner' => $oid, 'iuc_u_id' => $uid);
		$this->db->where($data);
		$this->db->delete('i_u_cart');
	}

	public function load_orders($oid, $cid, $orderid=null, $type=null) {
		if($orderid == null) {
		    if($type != null) {
		        $query = $this->db->query("SELECT * FROM i_txns WHERE it_c_id = '$cid' AND it_owner = '$oid' AND it_type='$type'");    
		    } else {
		        $query = $this->db->query("SELECT * FROM i_txns WHERE it_c_id = '$cid' AND it_owner = '$oid'");
		    }
			
		} else {
			$query = $this->db->query("SELECT * FROM i_txns WHERE it_c_id = '$cid' AND it_owner = '$oid' AND it_id='$orderid'");
		}
		
		return $query->result();
	}

	public function load_order_details($oid, $uid, $orderid) {
		$query = $this->db->query("SELECT * FROM i_txns_details AS a LEFT JOIN i_products AS b ON a.itd_p_id=b.ip_id WHERE a.itd_t_id='$orderid' AND a.itp_owner='$oid'");
		return $query->result();
	}

	public function load_tax_groups($oid) {
		$query = $this->db->query("SELECT * FROM i_tax_group WHERE ittxg_owner = '$oid'");
		return $query->result();
	}

	public function load_product_taxes($oid, $pid) {
		$query = $this->db->query("SELECT * FROM i_p_taxes WHERE ipt_oid = '$oid' AND ipt_p_id = '$pid'");
		return $query->result();
	}

	public function get_product_tax_details($oid, $pid, $amt, $txgp=null, $qty=1) {
	    if($txgp== null) {
	        $txarr = $this->load_product_taxes($oid, $pid);
	        if(count($txarr) > 0) {
	            $tid = $txarr[0]->ipt_t_id;    
	        } else {
	            $tid=0;
	        }
	    } else {
	        $tid = $txgp;
	    }
		
		$query = $this->db->query("SELECT * FROM i_tax_group_collection AS a LEFT JOIN i_taxes AS b ON a.itxgc_tx_id=b.itx_id WHERE a.itxgc_tg_id = '$tid' AND b.itx_owner = '$oid'");
		$result = $query->result();
		$txtl = 0;

		$data_txamt = [];
		$data_txid = [];
		$data_txname = [];

		$data_tax_tot = [];
		$data_tax_id = [];
		for ($i=0; $i < count($result) ; $i++) { 
			$tpcr = $result[$i]->itx_percent;
			$taxt = ($amt * $qty * $tpcr) / 100;
			$txtl += $taxt;
			array_push($data_txamt, $taxt);
			array_push($data_txid , $result[$i]->itx_id);
			array_push($data_txname, $result[$i]->itx_name);
		}
		
		if (count($result) > 0) {
			array_push($data_tax_tot, $txtl);
			array_push($data_tax_id, $result[0]->itxgc_tg_id);
		}

		$data['taxid'] = $data_txid;
		$data['taxamt'] = $data_txamt;
		$data['taxname'] = $data_txname;
		
		$data['taxtotid'] = $data_tax_id;
		$data['taxtotamt'] = $data_tax_tot;

		return $data;
	}

}