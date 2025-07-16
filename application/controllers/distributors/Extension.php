<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header("Access-Control-Allow-Origin: *");

class Extension extends CI_Controller {

	public function __construct()	{
		parent:: __construct();
		$this->load->database();
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->library('email');
		$this->load->model('distributors/Contact_Model','contact_model');
		$this->load->dbforge();
	}
########## PRODUCTS ################
	public function getproductlist($oid, $cat_id) {
	    $x='http://dhristi.evomata.com/ompv/assets/uploads/'.$oid.'/c/';
		$query = $this->db->query("SELECT ica_category_name AS name, CONCAT('$x', ica_id, '/', ica_img) AS image, ica_id AS id FROM i_category WHERE ica_owner='$oid' AND ica_parent_category='$cat_id'");
		$data['category'] = $query->result();
		
		$query = $this->db->query("SELECT ip_name AS name, ip_id AS id, ip_image AS image FROM i_products WHERE ip_owner='$oid' AND ip_category='$cat_id'");
		$data['products'] = $query->result();
		
		print_r(json_encode($data));
	}
}