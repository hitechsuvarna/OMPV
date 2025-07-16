<style>
	.price {
        <?php $sess_data = $this->session->userdata(); echo 'display:'.$sess_data['price_display'].';'; ?>
    }
    
    ::-webkit-input-placeholder { /* Edge */
        color: #ddd;
    }
        
        :-ms-input-placeholder { /* Internet Explorer 10-11 */
        color: #ddd;
    }
        
        ::placeholder {
        color: #ddd;
    }
	
	.purchase_table {
		width: 100%;
        text-align: left;
        border: 0px solid #ccc;
        border-collapse: collapse;
        
	}

	.purchase_table > thead > tr {
		box-shadow: 0px 5px 5px #ccc;
	}

	.purchase_table > thead > tr > th {
		padding: 10px;
	}

	.purchase_table > tbody > tr {
		border-bottom: 1px solid #ccc;
	}

	.purchase_table > tbody > tr > td {
		padding: 15px;
	}
</style>
<main class="mdl-layout__content" style="display:none;" id="page_content">
</main>
<div id="print_data"></div>
<div id="demo-snackbar-example" class="mdl-js-snackbar mdl-snackbar">
  <div class="mdl-snackbar__text"></div>
  <button class="mdl-snackbar__action" type="button"></button>
</div>

<script>
    $(document).ready(function() {
          <?php
		    function getIndianCurrency($number)
            {
                $decimal = round($number - ($no = floor($number)), 2) * 100;
                $hundred = null;
                $digits_length = strlen($no);
                $i = 0;
                $str = array();
                $words = array(0 => '', 1 => 'one', 2 => 'two',
                    3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six',
                    7 => 'seven', 8 => 'eight', 9 => 'nine',
                    10 => 'ten', 11 => 'eleven', 12 => 'twelve',
                    13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen',
                    16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
                    19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
                    40 => 'forty', 50 => 'fifty', 60 => 'sixty',
                    70 => 'seventy', 80 => 'eighty', 90 => 'ninety');
                $digits = array('', 'hundred','thousand','lakh', 'crore');
                while( $i < $digits_length ) {
                    $divider = ($i == 2) ? 10 : 100;
                    $number = floor($no % $divider);
                    $no = floor($no / $divider);
                    $i += $divider == 10 ? 1 : 2;
                    if ($number) {
                        $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                        $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                        $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
                    } else $str[] = null;
                }
                $Rupees = implode('', array_reverse($str));
                $paise = ($decimal) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
                return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise.' only';
            }
            
            setlocale(LC_MONETARY, 'en_IN');
		
		  //  echo $logo;
			$typearr = ["Customer Copy"]; 
			$typeidarr = ["original"];
			$content="";
			if(isset($txn)) {
			    $content = "<style> .txt_center { text-align:center; } .txt_right { text-align:right; }  .col_right {text-align: right; padding-right: 10px; } .col_left {text-align: left; width: 30%; } .item_header > th {font-weight: bold; border:0.5px solid; } .item_name > td { border: 0.5px solid; height:20px;} @page {size: A4; size:potrait; } @media print { #original, #duplicate, #triplicate { page-break-before: always; } } </style> <!--<table style=\"width:100%;\"><tr> -->";
    			for ($j=0; $j < count($typearr); $j++) { 
    				// $content.= '<td>';
    				$content.= '<div style="border: 0px #999 solid;padding: 20px;" id="'.$typeidarr[$j].'">';
    				$content.= '<div style="border: 0px #000 solid;">';
    				$content.= '<img src="'.$logo.'" style="width: 85%; margin: 0px auto; height: auto;margin-bottom:2px;padding:2px" alt="Logo" />';
    				$content.= '<table border="0" style="width: 100%;">';
    				$content.= '<tr>';
    				$content.= '<td style="width:100%;">';
    				$content.= '<table style="width:100%;"><tr><td style="width:33%;">';
    				$content.= '<b>Invoice No: </b><u>'.$txn[0]->it_txn_no.'</u>';
    				$content.= '</td>';
    				$content.= '<td style="width:33%;font-size:1.3em;text-decoration:underline;">';
    				$content.= '<div style="text-align: center">TAX INVOICE - '.$typearr[$j].'</div>';
    				$content.= '</td>';
    				$content.= '<td style="width:33%;text-align:right;">';
    				$content.= '<b>Date:</b><u>'.date_format(date_create($txn[0]->it_date), 'd/m/Y').'</u>';
    				$content.= '</td></tr></table>';
    				$content.= '</td>';
    				$content.= '</tr>';
    				$content.= '<tr>';
    				$content.= '<td colspan="3">';
    				$content.= '<table style="width:100%;">';
    				$content.= '<tbody>';
    				$content.= '<tr>';
    				$content.= '<td style="width:80%;" rowspan="3">';
    				$content.= 'Client Name:';
    				$content.= '<b>'.$txn[0]->ic_name.'</b><br>';
    				$content.= $txn[0]->ic_address.'<br>';
    				if($txn[0]->ic_gst_number !== "") $content.= 'GST: '.$txn[0]->ic_gst_number;
    				$content.= '</td>';
    				// $content.= '<td>';
    				// if(isset($txn_order)) $content.= 'Order No: '.$txn_order;
    				// $content.= '</td>';
    				$content.= '</tr>';
    				$content.= '<tr>';
    				$content.= '<td>';
    				if(isset($txn_delivery)) $content.= 'Challan No: '.$txn_delivery;
    				$content.= '</td>';
    				$content.= '</tr>';
    				$content.= '<tr>';
    				$content.= '<td>';
    				$content.= 'Mode: '.$txn[0]->it_mode;
    				$content.= '</td>';
    				$content.= '</tr>';
    				$content.= '</tbody>';
    				$content.= '</table>';
    				$content.= '</td>';
    				$content.= '</tr>';
    				$content.= '<tr>';
    				$content.= '<td colspan="3">';
    				if(count($txn_transport) > 0 ) $content.= 'Transporter: <u>'.$txn_transport[0]->ittd_transporter.'</u> ';
    				if(count($txn_transport) > 0 ) $content.= 'L/R No.: <u>'.$txn_transport[0]->ittd_lrno.'</u> ';
    				if(count($txn_transport) > 0 ) $content.= 'Date: <u>'.$txn_transport[0]->ittd_date.'</u> ';
    				if(count($txn_transport) > 0 ) $content.= 'Transporter GSTIN: <u>'.$txn_transport[0]->ittd_transporter_gstno.'</u> ';
    				if(count($txn_transport) > 0 ) $content.= 'Date: <u>'.$txn_transport[0]->ittd_state.'</u>';
    				$content.= '</td>';
    				$content.= '</tr>';
    				$content.= '<tr>';
    				$content.= '<td colspan="3">';
    				if(count($txn_challans) > 0 ) {
    				    $content.= 'Challans: ';
    				    $content.=implode(", ", $txn_challans);
    				}
    				$content.= '</td>';
    				$content.= '</tr>';
    				
    				$content.= '</table>';
    
    				$content.= '</div>';
    				$content.= '<div style="text-align: left;">';
    				$content.= '<hr>';
    				// $content.= '<b style="font-size:1.1em;">Order Details</b>';
    				$content.= '</div>';
    				$content.= '<div style="border: 0px #000 solid;">';
    				$content.= '<table border="0" cellspacing=0 style="width: 100%;border:1px solid; border-radius:5px;display:block;overflow:auto;">';
    				$content.= '<thead>';
    				$content.= '<tr class="item_header">';
    				$content.= '<th>Sr. No.</th>';
    				$content.= '<th>Particulars</th>';
    				$content.= '<th>HSN Code</th>';
    				$content.= '<th>Unit</th>';
    				$content.= '<th>Tax</th>';
    				$content.= '<th>Qty</th>';
    				$content.= '<th>Rate</th>';
    				$content.= '<th>Amount</th>';
    				$content.= '</tr>';
    				$content.= '</thead>';
    				$content.= '<tbody>';
    
    				$taxes_arr = [];
    				$taxes_flg = false;
    				$taxes_amt_arr = [];
    				$subtot = 0;
    				$line_cnt = count($txn_details);
    				if(count($txn_transport) > 0 ) { $line_cnt_total = 14 - $line_cnt; } else { $line_cnt_total = 14 - $line_cnt; }
    				
    				for ($i=0; $i < count($txn_details) ; $i++) { 
    				    $subtot = $subtot + $txn_details[$i]->itp_value;
    					$content.= '<tr class="item_name"><td class="txt_center">'.($i+1).'</td><td>';
    					if($txn_details[$i]->itp_alias == 1) { $content.= $txn_details[$i]->ip_alias; } else { $content.= $txn_details[$i]->ip_name; }
    					$content.= '</td><td class="txt_center">'.$txn_details[$i]->ip_hsn_code.'</td><td class="txt_center">'.$txn_details[$i]->ip_unit.'</td><td class="txt_center">'.$txn_details[$i]->ittxg_group_name.'</td><td class="txt_center">'.$txn_details[$i]->itp_qty.'</td><td class="txt_right">'.money_format('%!i', $txn_details[$i]->itp_rate).'</td><td class="txt_right">'.money_format('%!i', $txn_details[$i]->itp_value).'</td></tr>';
    				}
                    for ($i=0; $i < $line_cnt_total; $i++) { 
    				    $content.= '<tr class="item_name"><td></td><td></td><td></td><td class="detail_center"></td><td class="detail_center"></td><td class="detail_right"></td><td class="detail_right"></td><td class="detail_right"></td></tr>';
    				}
                    for ($i=0; $i < count($taxes); $i++) { 
    					$amt = 0.0;
    					$tmp_flg = false;
    					for ($ik=0; $ik < count($txn_prod_tax); $ik++) { 
    						
    						if ($taxes[$i]->itx_id == $txn_prod_tax[$ik]->itpt_tx_id ) {
    						    if ($taxes_flg == false) {
    								array_push($taxes_arr, $txn_prod_tax[$ik]->itpt_t_name);
    								$taxes_flg = true;
    							}
    							$tmp_flg = true;
    							$amt+=$txn_prod_tax[$ik]->itpt_t_amount;
    						}
    					}
    					if($tmp_flg == true) { array_push($taxes_amt_arr, $amt); }
    					$taxes_flg = false;
    				}
    				// $subtot = $txn[0]->it_amount;
    				
    				$disamt = $txn[0]->it_discount;
    				$transamt = 0;
    				if(count($txn_transport) > 0) $transamt=$txn_transport[0]->ie_amount;
    				$totamt = $subtot - $disamt + $transamt;
    				$freight = $txn[0]->it_freight;
    				$creamt = $txn[0]->it_credit;
    				$grandtot = $totamt + $freight;
    
    				$content.= '</tbody>';
    				$content.= '<tfoot>';
    				$content.= '<tr class="item_name"><td colspan="8">Payment within '.$creamt.' days</td></tr>';
    				$content.= '<tr class="item_name"><td colspan="4" rowspan="';
    				if(count($txn_transport) > 0 ) { $content.= 5; } else { $content.= 4; }
    				$content.= '" style="vertical-align: text-top;">Receivers Signature</td><td colspan="3">Subtotal</td><td class="txt_right">'.money_format('%!i', $subtot).'</td></tr><tr class="item_name"><td colspan="3">Discount</td><td class="txt_right">'.money_format('%!i', $disamt).'</td></tr>';
    				if(count($txn_transport) > 0 ) { $content.= '<tr class="item_name"><td colspan="3">Transport</td><td class="txt_right">'.money_format('%!i', $txn_transport[0]->ie_amount).'</td></tr>'; }
    				$content.= '<tr class="item_name"><td colspan="3">Total</td><td class="txt_right">'.money_format('%!i', $totamt).'</td></tr><tr class="item_name"><td colspan="3">Freight/Insuarance/Packg</td><td class="txt_right">'.money_format('%!i', $freight).'</td></tr>';
    				$content.= '<tr class="item_name"><td colspan="4" rowspan="'.(count($taxes_arr) + 2).'" style="vertical-align: text-top;">Note: '.$txn[0]->it_note.'</td>';
    				$taxtot = 0.0;
    				if(count($taxes_arr) <= 0) {
    				    $content.= '</tr>';
    				}
    				for ($i=0; $i < count($taxes_arr) ; $i++) { 
    					$taxtot += $taxes_amt_arr[$i];
    					if($i==0) {
    					    $content.= '<td colspan="3">'.$taxes_arr[$i].'</td><td class="txt_right">'.money_format('%!i', $taxes_amt_arr[$i]).'</td>';
    					    
    					} else {
    					    $content.= '<tr class="item_name"><td colspan="3">'.$taxes_arr[$i].'</td><td class="txt_right">'.money_format('%!i', $taxes_amt_arr[$i]).'</td></tr>';   
    					}
    				}
    				$grandtot += $taxtot;
    				$t_flot=$grandtot - round($grandtot);
    				
    				
    				$content.= '<tr class="item_name"><td colspan="3">Round Off</td><td class="txt_right">'.money_format('%!i', abs(number_format((float)$t_flot, 2, ".",""))).'</td></tr>';
    				$content.= '<tr class="item_name"><td colspan="3">Grand Total</td><td class="txt_right">'.money_format('%!i', round($grandtot)).'</td></tr>';
    				
    				$content.= '<tr class="item_name"><td colspan="8"><b>In words:</b> '.getIndianCurrency(round($grandtot)).'</td></tr>';
    				$content.= '</tfoot>';
    				$content.= '</table>';
    				$content.= '</div>';
    				# $content.= '<div style="border: 0px #000 solid;">';
    				#$content.= '<table border="0" style="width: 100%;text-align: left;padding-top: 10px;">';
    				#$content.= '<tr>';
    				#$content.= '<th colspan="2">Terms:</th>';
    				#$content.= '</tr>';
    				#for ($i=0; $i < count($terms) ; $i++) {$content.= "<tr>"; $content.= "<td>".($i+1)."</td>"; $content.= "<td>".$terms[$i]->iextdt_term."</td>"; $content.= "</tr>"; }
    				#$content.= '</table>';
    				// $content.= '<b>GST No:'.$gst_num.'</b>';
    				// $content.= '</div>';
    				$content.= '<table>';
    				$content.= '<tr>';
    				$content.= '<td style="width:100%;">';
    				$content.= '<table style="width:100%;"><tr><td colspan="3" style="font-size:0.8em;"><b>CHEQUE BOUNCING CHARGES Rs. 500/-</b></td></tr><tr>';
    				$content.= '<td style="width:25%; font-size: 0.7em;">GSTIN: '.$gst_num.'<br>Bank Details:<br>Bank Name: '.$bank.'<br>Branch: '.$branch.'<br>Account No: '.$acc.'<br>IFSC Code: '.$ifsc.'</td>';
    				$content.= '<td style="width:41%;"><div style="font-size:0.5em;font-weight:bold;">TERMS & CONDITIONS</div><div style="font-size:0.6em; line-height:1em;">1. Subject to mumbai Jurisdiction. 2. Goods supplied on order will not be accepted back. 3. Payment terms - Immidiate. Intrest @ 18% per annum will be charged on delayed payments. 4. Warranty/Service of purchased goods is the manufacturers/ importers responsibility under the warranty period. 5. I the undersigned have accepted the terms and conditions of the invoice. 6. O M Prime Ventures shall not be responsible for any expenses involving legal costs in case of a dispute 7. Received goods in order and good condition. 8. We declare this invoice shows the actual goods described and that the all particulars and correct to the best of our knowledge and belief. 9. Our responsibility ceases absolutely as soon as goods are handed over to the carrier.</div><div style="font-size:0.6em;">E.&.O.E.</div></td>';
    				$content.= '<td style="width:33%;">';
    				$content.= '<div style="text-align: right;margin-top: 4em;white-space:nowrap;">';
    				$content.= '<b style="border-top: 1px #000 solid;padding-top: 10px; font-size:0.9em;">Proprietor/ Authorized Signature</b>';
    				$content.= '</div>';
    				$content.= '</td>';
    				$content.= '</tr></table>';
    				
    				$content.= '</td>';
    				$content.= '</tr>';
    				$content.= '</table>';
    				$content.= '<hr>';
    				$content.= '</div>';
    
    				// $content.= "</td>";
    			}
    			// $content.= "</tr></table>";
    			echo '$("#print_data").append(\''.$content.'\')';    
			}
		?>
        
        $.post('<?php echo base_url().$type."/Home/load_cart_values/count"; ?>', {}, function(d,s,x) {
            $('#disp_cart').attr('data-badge', d);
        })
        
        $('#disp_cart').click(function(e) {
            e.preventDefault();
            window.location = '<?php echo base_url().$type."/Home/load_cart"; ?>';
        })
        
    })
    
</script>
