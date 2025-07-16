<title><?php echo $title; ?></title>
<style>
    .detail_center {
        text-align:center;
    }
    
    .detail_right {
        text-align:right;
    }
</style>
<main class="mdl-layout__content">
    <div class="mdl-grid">
        <div class="mdl-cell mdl-cell--6-col">
            <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--colored" id="print" style="margin: 20px; width:100%;">Print Challan</button>    
        </div>
        <div class="mdl-cell mdl-cell--6-col">
            <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--colored" id="print_slip" style="margin: 20px; width:100%;">Print Slip</button>    
        </div>
    </div>

	<div class="mdl-grid">
		<div class="mdl-cell mdl-cell--4-col">
			<div class="mdl-card mdl-shadow--4dp">
				<div style="padding:25px; text-align:left; color: #666;">
					<h2 class="mdl-card__title-text">Shipping Details</h2>
				</div>
				<div class="mdl-card__supporting-text">
				    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input id="s_txn_no" class="mdl-textfield__input" value= "<?php echo $txn[0]->it_txn_no; ?>" />
						<label class="mdl-textfield__label" for="s_txn_no">Challan Number</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input id="s_note" class="mdl-textfield__input" value="<?php echo $txn[0]->it_note; ?>" />
						<label class="mdl-textfield__label" for="s_note">Shipping Note</label>
					</div>
				</div>
				<hr>
				<div style="padding:25px; text-align:left; color: #666;">
					<h2 class="mdl-card__title-text">Transport Details & Shipping Note</h2>
				</div>
				<div class="mdl-card__supporting-text">
				    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
				        <select class="mdl-textfield__input" id="s_account">
				            <option value="None">None</option>
				            <?php for($i=0;$i<count($modes);$i++) {
				                echo '<option value="'.$modes[$i]->itp_mode.'"';
				                if(count($txn_transport) > 0) if($txn_transport[(count($txn_transport) -1)]->ittd_account == $modes[$i]->itp_mode) echo ' selected ';
				                echo '>'.$modes[$i]->itp_mode.'</option>';
				            } ?>
				        </select>
						<label class="mdl-textfield__label" for="s_account">Account for Transport Expense</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="s_transport" class="mdl-textfield__input" value="<?php if(count($txn_transport) > 0 ) echo $txn_transport[(count($txn_transport)-1)]->ittd_transporter; ?>">
						<label class="mdl-textfield__label" for="s_transport">Transport Through</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="s_lrno" class="mdl-textfield__input" value="<?php if(count($txn_transport) > 0 ) echo $txn_transport[(count($txn_transport)-1)]->ittd_lrno; ?>">
						<label class="mdl-textfield__label" for="s_lrno">L/R No</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" data-type="date" id="s_txn_date" class="mdl-textfield__input" value="<?php if(count($txn_transport) > 0 ) echo $txn_transport[(count($txn_transport)-1)]->ittd_date; ?>">
						<label class="mdl-textfield__label" for="s_txn_date">Date</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="s_gstno" class="mdl-textfield__input" value="<?php if(count($txn_transport) > 0 ) echo $txn_transport[(count($txn_transport)-1)]->ittd_transporter_gstno; ?>">
						<label class="mdl-textfield__label" for="s_gstno">Transporter GST No/</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="s_state" class="mdl-textfield__input" value="<?php if(count($txn_transport) > 0 ) echo $txn_transport[(count($txn_transport)-1)]->ittd_state; ?>">
						<label class="mdl-textfield__label" for="s_state">State</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="s_expense" class="mdl-textfield__input" value="<?php if(count($txn_transport) > 0 ) echo $txn_transport[(count($txn_transport)-1)]->ie_amount; ?>">
						<label class="mdl-textfield__label" for="s_expense">Transport Expense</label>
					</div>
    				<div>
    					<button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--colored" id="update" style="margin: 20px; width: 90%;">Update</button>
    				</div>
			    </div>
				<!--<a href="<?php echo base_url().$type.'/Transactions/get_pdf/'.$tid; ?>"><button>Click me</button></a>-->
				<!--<a href="<?php echo base_url().'assets/My-File-Name.pdf'; ?>"><button>Click me</button></a>-->
				<!--<a href="http://ascendeducators.com/test-pdf.pdf"><button>Try Me</button></a>-->
				
			</div>
		</div>
		<div class="mdl-cell mdl-cell--8-col">
			<div id="content" style="width: 100%;">
				
				<?php
					$typearr = ["Original","Duplicate"]; 
					$typeidarr = ["original", "duplicate"];
					$content = "<style> @media print { .main_head { display:table-header-group !important; } .main_foot { display:table-footer-group !important; }  } .col_right {text-align: right; padding-right: 10px; } .col_left {text-align: left; width: 30%; } .item_header > th {font-weight: bold; border:1px solid;   } .item_name > td { border: 0.5px solid; height: 20px;} @page {size: A4; size:landscape; }  @media print { #duplicate {/*page-break-before: always;*/ } }  </style> <table class=\"main_table\" style=\"width:100%;margin-bottom:5px;font-family: Calibri, sans-serif;width:100vw;\"><tr>";
					/*for ($j=0; $j < count($typearr); $j++) { 
						$content.= '<td>';
						$content.= '<div style="border: 0px #999 solid;padding: 10px;" id="'.$typeidarr[$j].'">';
						$content.= '<div style="border: 0px #000 solid;">';
						$content.= '<img src="'.$logo.'" style="width: 100%; height: 100px;margin-bottom:10px;">';
						$content.= '<div style="text-align: center">Delivery Challan - '.$typearr[$j].'</div>';
						$content.= '<table border="0" style="width: 100%;">';
						$content.= '<tr>';
						$content.= '<td style="width: 50%;font-size:1em;">';
						$content.= 'Client Name:';
						$content.= '<b>'.$txn[0]->ic_name.'</b><br>';
						$content.= $txn[0]->ic_address.'<br>';
						if($txn[0]->ic_gst_number !== "") $content.= 'GST: '.$txn[0]->ic_gst_number;
						$content.= '</td>';
						$content.= '<td>';
						$content.= '<div style="text-align: right;">';
						$content.= '</div>';
						$content.= '<div style="text-align: right;">';
						
						$content.= '<table style="width: 100%;padding: 10px;">';
						$content.= '<tr>';
						$content.= '<td class="col_right">';
						$content.= '<b>Challan No:</b>';
						$content.= '</td>';
						$content.= '<td class="col_left">';
						$content.= '<u>#'.$txn[0]->it_txn_no.'</u>';
						$content.= '</td>';
						$content.= '</tr>';
						$content.= '<tr>';
						$content.= '<td class="col_right">';
						$content.= '<b>Date:</b>';
						$content.= '</td>';
						$content.= '<td class="col_left">';
						$content.= '<u>'.date_format(date_create($txn[0]->it_date), 'd/m/Y').'</u>';
						$content.= '</td>';
						$content.= '</tr>';
						$content.= '</table>';
						
						$content.= '</div>';
						$content.= '</td>';
						$content.= '</tr>';
						$content.= '<tr>';
						$content.= '<td colspan="2">';
						if(count($txn_transport) > 0 ) $content.= 'Delivery through: <u>'.$txn_transport[0]->ittd_transporter.'</u> ';
						if(count($txn_transport) > 0 ) $content.= 'L/R No.: <u>'.$txn_transport[0]->ittd_lrno.'</u> ';
						if(count($txn_transport) > 0 ) $content.= 'Date: <u>'.$txn_transport[0]->ittd_date.'</u> ';
						$content.= '</td>';
						$content.= '</tr>';
						$content.= '</table>';
                        
						$content.= '</div>';
						$content.= '<div style="text-align: left;">';
						$content.= '<hr>';
						$content.= '<b style="font-size:1.1em;">Order Details</b>';
						$content.= '</div>';
						$content.= '<div style="border: 0px #000 solid;">';
						$content.= '<table border="0" cellspacing=0 style="width: 100%;border:1px solid; border-radius:5px;">';
						$content.= '<thead>';
						$content.= '<tr class="item_header">';
						$content.= '<th>Sr. No.</th>';
						$content.= '<th>Particulars</th>';
						$content.= '<th>Qty</th>';
						$content.= '<th>Rate</th>';
						$content.= '<th>Amount</th>';
						$content.= '</tr>';
						$content.= '</thead>';
						$content.= '<tbody>';
						
						$line_cnt = count($txn_details);
						
						if(count($txn_transport) > 0 ) { $line_cnt_total = 13 - $line_cnt; } else { $line_cnt_total = 14 - $line_cnt; }
				        
				        $amt=0;
				        for ($i=0; $i < count($txn_details) ; $i++) { 
				            $amt+=$txn_details[$i]->itp_amount;
							$content.= '<tr class="item_name"><td style="text-align:center;">'.($i+1).'</td>';
							if(strlen($txn_details[$i]->ip_name) > 32) {
							    $content.= '<td style="font-size:0.7em;font-weight:bold;">'.$txn_details[$i]->ip_name.'</td>';
							} else {
							    $content.= '<td>'.$txn_details[$i]->ip_name.'</td>';
							}
							$content.='<td class="detail_center" style="text-align:center;">'.$txn_details[$i]->itp_qty.'</td><td class="detail_right" style="text-align:right;">'.$txn_details[$i]->itp_rate.'</td><td class="detail_right" style="text-align:right;">'.$txn_details[$i]->itp_amount.'</td></tr>';
						}
						for ($i=0; $i < $line_cnt_total; $i++) {
							$content.= '<tr class="item_name"><td> </td><td></td><td class="detail_center" style="text-align:center;"> </td><td class="detail_right" style="text-align:right;"></td><td class="detail_right" style="text-align:right;"></td></tr>';
						}
						$totamt=0;$transamt=0;
						$content.= '</tbody>';
						$content.= '<tfoot>';
						$content.= '<tr class="item_name"><td colspan="5">Receivers Signature</td></tr>';
						$content.= '<tr class="item_name"><td colspan="5">Payment within: '.$txn[0]->ic_credit.' days</td></tr>';
						$content.= '<tr class="item_name"><td colspan="3" rowspan="';
						if(count($txn_transport) > 0 ) { $content.= '3'; } else { $content .= '2'; }
						$content.= '">Note: '.$txn[0]->it_note.'</td><td>Subtotal</td><td>'.$amt.'</td></tr>';
						if(count($txn_transport) > 0 ) { $content.= '<tr class="item_name"><td>Transport</td><td>'.$txn_transport[0]->ie_amount.'</td></tr>' ; $transamt = $txn_transport[0]->ie_amount; } 
						$totamt = $transamt + $amt;
						$content.= '<tr class="item_name"><td>Grand Total</td><td>'.$totamt.'</td></tr>';
						$content.= '<tr><td colspan="5">';
						$content.= '<table>';
						$content.= '<tr>';
						$content.= '<td style="width:100%;"><div style="font-size:0.7em;font-weight:bold;">TERMS & CONDITIONS</div><div style="font-size:0.5em; line-height:1em;">1. Subject to mumbai Jurisdiction. 2. Goods supplied on order will not be accepted back. 3. Payment terms - Immidiate. Intrest @ 18% per annum will be charged on delayed payments. 4. Warranty/Service of purchased goods is the manufacturers/ importers responsibility under the warranty period. 5. I the undersigned have accepted the terms and conditions of the invoice. 6. O M Prime Ventures shall not be responsible for any expenses involving legal costs in case of a dispute 7. Received goods in order and good condition.</div><div style="font-size:0.6em;">E.&.O.E.</div></td>';
						$content.= '<td>';
						$content.= '<div style="text-align: right;margin-top: 4em;white-space:nowrap;">';
						$content.= '<b style="border-top: 1px #000 solid;padding-top: 10px; font-size:0.9em;">Proprietor/ Authorized Signature</b>';
						$content.= '</div>';
						$content.= '</td>';
						$content.= '</tr>';
						$content.= '</table>';
						$content.= '</td></tr>';
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
						
				// 		$content.= '<hr>';
						$content.= '</div>';
						$content.= "</td>";
					}*/
					
					for($j=0;$j<count($typearr);$j++) {
					    $content.= '<td id="'.$typeidarr[$j].'" style="padding-right:25px;padding-left:25px;">';
            			$content.= '<table style="border-collapse:seperate; border-spacing:0px;">';
            			$content.= '<thead class="main_head">';
            			$content.= '<tr>';
            			$content.= '<td colspan="5"><img src="'.$logo.'" style="width: 100%; height: 100px;margin-bottom:5px;"></td>';
            			$content.= '</tr>';
            			$content.= '<tr><td colspan="5"><div style="text-align: center">Delivery Challan - '.$typearr[$j].'</div></td></tr>';
            			$content.= '<tr>';
            			// CLIENT
            			$content.= '<td colspan="3" style="font-size:0.9em;">Client Name:<b>'.$txn[0]->ic_name.'</b><br>'.$txn[0]->ic_address.'<br>';
						if($txn[0]->ic_gst_number !== "") $content.= 'GST: '.$txn[0]->ic_gst_number;
						$content.= '</td>';
            			$content.= '<td colspan="2">';
                        // DATE
            			$content.= '<table style="width: 100%;padding: 10px;">';
            			$content.= '<tr>';
            			$content.= '<td class="col_right"><b>Challan No:</b></td>';
						$content.= '<td class="col_left"><u>#'.$txn[0]->it_txn_no.'</u></td>';
						$content.= '</tr>';
						$content.= '<tr>';
						$content.= '<td class="col_right"><b>Date:</b></td>';
						$content.= '<td class="col_left"><u>'.date_format(date_create($txn[0]->it_date), 'd/m/Y').'</u></td>';
						$content.= '</tr>';
						$content.= '</table>';
					    $content.= '</td>';
					    
					    $content.= '</tr>';
                        if(count($txn_transport) > 0 ) $content.= '<tr><td colspan="5">Delivery through: <u>'.$txn_transport[0]->ittd_transporter.'</u> L/R No.: <u>'.$txn_transport[0]->ittd_lrno.'</u> Date: <u>'.$txn_transport[0]->ittd_date.'</u></td></tr>';
						$content.= '<tr><td colspan="5">Order Details</td></tr>';
						$content.= '<tr class="item_header"><th style="border-radius:5px 0px 0px 0px;">Sr. No.</th><th>Particulars</th><th>Qty</th><th>Rate</th><th style="border-radius:0px 5px 0px 0px;">Amount</th></tr>';
						$content.='</thead>';
						
				        // BODY BEGINS
						$content.='<tbody class="main_content_body">';
						$line_cnt = count($txn_details);
						if(count($txn_transport) > 0 ) { $line_cnt_total = 13 - $line_cnt; } else { $line_cnt_total = 14 - $line_cnt; }
				        $amt=0;
				        for ($i=0; $i < count($txn_details) ; $i++) { 
				            $amt+=$txn_details[$i]->itp_amount;
							$content.= '<tr class="item_name"><td style="text-align:center;">'.($i+1).'</td>';
							if(strlen($txn_details[$i]->ip_name.' <b>('.$txn_details[$i]->ip_description.')</b>') > 27) {
							    $content.= '<td style="font-size:0.7em;">'.$txn_details[$i]->ip_name.' <b>('.$txn_details[$i]->ip_description.')</b></td>';
							} else {
							    $content.= '<td>'.$txn_details[$i]->ip_name.' ('.$txn_details[$i]->ip_description.')</td>';
							}
							$content.='<td class="detail_center" style="text-align:center;">'.$txn_details[$i]->itp_qty.'</td><td class="detail_right" style="text-align:right;">'.$txn_details[$i]->itp_rate.'</td><td class="detail_right" style="text-align:right;">'.$txn_details[$i]->itp_amount.'</td></tr>';
						}
						for ($i=0; $i < $line_cnt_total; $i++) {
							$content.= '<tr class="item_name"><td> </td><td></td><td class="detail_center" style="text-align:center;"> </td><td class="detail_right" style="text-align:right;"></td><td class="detail_right" style="text-align:right;"></td></tr>';
						}
						$totamt=0;$transamt=0;
						$content.= '</tbody>';
						
						// FOOTER BEGINS
						$content.= '<tfoot class="main_foot">';
						$content.= '<tr class="item_name"><td colspan="5">Receivers Signature</td></tr>';
						$content.= '<tr class="item_name"><td colspan="5">Payment within: '.$txn[0]->ic_credit.' days</td></tr>';
						$content.= '<tr class="item_name"><td colspan="3" rowspan="';
						if(count($txn_transport) > 0 ) { $content.= '3'; } else { $content .= '2'; }
						$content.= '" style="border-radius:0px 0px 0px 5px;">Note: '.$txn[0]->it_note.'</td><td>Subtotal</td><td>'.$amt.'</td></tr>';
						if(count($txn_transport) > 0 ) { $content.= '<tr class="item_name"><td>Transport</td><td>'.$txn_transport[(count($txn_transport)-1)]->ie_amount.'</td></tr>' ; $transamt = $txn_transport[(count($txn_transport)-1)]->ie_amount; } 
						$totamt = $transamt + $amt;
						$content.= '<tr class="item_name"><td>Grand Total</td><td style="border-radius:0px 0px 5px 0px;">'.$totamt.'</td></tr>';
						$content.= '<tr>';
						$content.= '<td colspan="3"><div style="font-size:0.7em;font-weight:bold;">TERMS & CONDITIONS</div><div style="font-size:0.5em; line-height:1em;">1. Subject to mumbai Jurisdiction. 2. Goods supplied on order will not be accepted back. 3. Payment terms - Immidiate. Intrest @ 18% per annum will be charged on delayed payments. 4. Warranty/Service of purchased goods is the manufacturers/ importers responsibility under the warranty period. 5. I the undersigned have accepted the terms and conditions of the invoice. 6. O M Prime Ventures shall not be responsible for any expenses involving legal costs in case of a dispute 7. Received goods in order and good condition.</div><div style="font-size:0.6em;">E.&.O.E.</div></td>';
						$content.= '<td colspan="2"><div style="padding-top: 3em;width:90%;border-bottom: 1px solid #000;margin:0px;"></div><div style="text-align: right; margin:0px;"><b style="padding-top: 0px; font-size:0.9em;">Proprietor/ Authorized Signature</b></div></td>';
						$content.= '</tr>';
						$content.= '</tfoot>';
						$content.= '</table>';
            		    $content.= '</td>';
					}
            					
		
					$content.= "</tr></table>";
					echo $content;
					
					$typearr1 = ["Original"]; 
					$typeidarr1 = ["original"];
					$content1 = "<style> @media print { .main_foot_slip { display:table-footer-group; }  .main_head_slip { display:table-header-group; }  } .col_right_slip {text-align: right; padding-right: 10px; } .col_left_slip {text-align: left; width: 30%; } .item_header_slip > th {font-weight: bold; border:1px solid;   } .item_name_slip > td { border: 0.5px solid; height: 20px;} @page {size: A5; size:landscape; }  @media print { #duplicate_slip {/*page-break-before: always;*/ } }  </style> <table style=\"width:50%;margin-bottom:5px;font-family: Calibri, sans-serif;\"><tr>";
					
					for($j=0;$j<count($typearr1);$j++) {
					    $content1.= '<td id="'.$typeidarr[$j].'" style="padding-right:10px;padding-left:10px;">';
            			$content1.= '<table style="border-collapse:seperate; border-spacing:0px;">';
            			$content1.= '<thead class="main_head_slip">';
            // 			$content1.= '<tr>';
            			$content1.= '<td colspan="5"><img src="'.$logo.'" style="width: 100%; height: 100px;margin-bottom:5px;"></td>';
            // 			$content1.= '</tr>';
            			$content1.= '<tr><td><div style="text-align: center">Delivery Slip</div></td></tr>';
            			
            			$content1.= '<tr>';
            			// CLIENT
            			$content1.= '<td colspan="3" style="font-size:0.9em;">Client Name:<b>'.$txn[0]->ic_name.'</b></td>';
            			// DATE
            			$content1.= '<td colspan="2">';
                        $content1.= '<table style="width: 100%;padding: 10px;">';
            			$content1.= '<tr>';
            			$content1.= '<td class="col_right"><b>Challan No:</b></td>';
						$content1.= '<td class="col_left"><u>#'.$txn[0]->it_txn_no.'</u></td>';
						$content1.= '</tr>';
						$content1.= '<tr>';
						$content1.= '<td class="col_right"><b>Date:</b></td>';
						$content1.= '<td class="col_left"><u>'.date_format(date_create($txn[0]->it_date), 'd/m/Y').'</u></td>';
						$content1.= '</tr>';
						$content1.= '</table>';
					    $content1.= '</td>';
					    $content1.= '</tr>';
					    
					    // if(count($txn_transport) > 0 ) $content.= '<tr><td colspan="5">Delivery through: <u>'.$txn_transport[0]->ittd_transporter.'</u> L/R No.: <u>'.$txn_transport[0]->ittd_lrno.'</u> Date: <u>'.$txn_transport[0]->ittd_date.'</u></td></tr>';
						$content1.= '<tr><td colspan="5" style="text-align:center;">Order Details</td></tr>';
						$content1.= '<tr class="item_header_slip"><th style="border-radius:5px 0px 0px 0px;">Sr. No.</th><th colspan="3">Particulars</th><th>Qty</th></tr>';
						$content1.='</thead>';
						
				        // BODY BEGINS
						$content1.='<tbody>';
						$line_cnt = count($txn_details);
						if(count($txn_transport) > 0 ) { $line_cnt_total = 13 - $line_cnt; } else { $line_cnt_total = 14 - $line_cnt; }
				        $amt=0;
				        for ($i=0; $i < count($txn_details) ; $i++) { 
				            $amt+=$txn_details[$i]->itp_amount;
							$content1.= '<tr class="item_name_slip"><td style="text-align:center;">'.($i+1).'</td>';
							if(strlen($txn_details[$i]->ip_name) > 32) {
							    $content1.= '<td colspan="3" style="font-size:0.8em;">'.$txn_details[$i]->ip_name.' ('.$txn_details[$i]->ip_description.')</td>';
							} else {
							    $content1.= '<td colspan="3" style="">'.$txn_details[$i]->ip_name.' ('.$txn_details[$i]->ip_description.')</td>';
							}
							$content1.='<td class="detail_center" style="text-align:center;">'.$txn_details[$i]->itp_qty.'</td></tr>';
						}
						for ($i=0; $i < $line_cnt_total; $i++) {
							$content1.= '<tr class="item_name"><td> </td><td></td><td class="detail_center" style="text-align:center;"> </td><td class="detail_right" style="text-align:right;"></td><td class="detail_right" style="text-align:right;"></td></tr>';
						}
						$totamt=0;$transamt=0;
						$content1.= '</tbody>';
						
						// FOOTER BEGINS
						$content1.= '<tfoot class="main_foot">';
						$content1.= '<tr class="item_name"><td colspan="5">Receivers Signature</td></tr>';
				// 		$content1.= '<tr class="item_name"><td colspan="5">Payment within: '.$txn[0]->ic_credit.' days</td></tr>';
				// 		$content1.= '<tr class="item_name"><td colspan="3" rowspan="';
				// 		if(count($txn_transport) > 0 ) { $content1.= '3'; } else { $content1 .= '2'; }
				// 		$content1.= '" style="border-radius:0px 0px 0px 5px;">Note: '.$txn[0]->it_note.'</td><td>Subtotal</td><td>'.$amt.'</td></tr>';
				// 		if(count($txn_transport) > 0 ) { $content1.= '<tr class="item_name"><td>Transport</td><td>'.$txn_transport[(count($txn_transport)-1)]->ie_amount.'</td></tr>' ; $transamt = $txn_transport[(count($txn_transport)-1)]->ie_amount; } 
				// 		$totamt = $transamt + $amt;
				// 		$content1.= '<tr class="item_name"><td>Grand Total</td><td style="border-radius:0px 0px 5px 0px;">'.$totamt.'</td></tr>';
				// 		$content1.= '<tr>';
				// 		$content1.= '<td colspan="3"><div style="font-size:0.7em;font-weight:bold;">TERMS & CONDITIONS</div><div style="font-size:0.5em; line-height:1em;">1. Subject to mumbai Jurisdiction. 2. Goods supplied on order will not be accepted back. 3. Payment terms - Immidiate. Intrest @ 18% per annum will be charged on delayed payments. 4. Warranty/Service of purchased goods is the manufacturers/ importers responsibility under the warranty period. 5. I the undersigned have accepted the terms and conditions of the invoice. 6. O M Prime Ventures shall not be responsible for any expenses involving legal costs in case of a dispute 7. Received goods in order and good condition.</div><div style="font-size:0.6em;">E.&.O.E.</div></td>';
				// 		$content1.= '<td colspan="2"><div style="padding-top: 3em;width:90%;border-bottom: 1px solid #000;margin:0px;"></div><div style="text-align: right; margin:0px;"><b style="padding-top: 0px; font-size:0.9em;">Proprietor/ Authorized Signature</b></div></td>';
				// 		$content1.= '</tr>';
						$content1.= '</tfoot>';
						$content1.= '</table>';
            		    $content1.= '</td>';
					}
            					
		
					$content1.= "</tr></table>";
					
				?>
			</div>
		</div>
	</div>
</main>

<script type="text/javascript">
	$(document).ready(function() {
		$('#s_txn_date').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
		<?php 
			if(!isset($edit_txn)) {
				echo "var dt = new Date();";
				echo "var s_dt = dt.getFullYear() + '-' + (dt.getMonth() + 1) + '-' + dt.getDate();";
				
				echo "$('#s_txn_date').val(s_dt);";
			}
		?>

		$('#update').click(function(e) {
			e.preventDefault();

			$.post('<?php echo base_url().$type."/Transactions/update_transport_details/".$tid; ?>', { 'acc' : $('#s_account').val(), 'transporter' : $('#s_transport').val(), 'lrno' : $('#s_lrno').val(), 'date' : $('#s_txn_date').val(), 'gstno' : $('#s_gstno').val(), 'state' : $('#s_state').val(), 'expenses' : $('#s_expense').val(), 'note' : $('#s_note').val(), 'challan' : $('#s_txn_no').val()  }, function(d,s,x) { window.location = "<?php echo base_url().$type.'/Transactions/print_delivery/'.$tid; ?>" }, "text");
		});
		
		$('#print').click(function(e) {
			print_reciept();
		});
		
		$('#print_slip').click(function(e) {
		    print_slip();
		})
	});

	function print_reciept() {
		var mywindow = window.open('', '<?php echo $title_doc; ?>', fullscreen=1);
		<?php echo 'mywindow.document.write(\''.$content.'\'); mywindow.document.close(); mywindow.focus(); mywindow.document.onreadystatechange=function(){
     if(this.readyState==="complete"){
      this.onreadystatechange=function(){};
      mywindow.focus();
      mywindow.print();
      mywindow.close();
     }
    }'; ?>
	}
	
	function print_slip() {
		var mywindow = window.open('', '<?php echo $title_doc; ?>', fullscreen=1);
		<?php echo 'mywindow.document.write(\''.$content1.'\'); mywindow.document.close();mywindow.document.onreadystatechange=function(){
     if(this.readyState==="complete"){
      this.onreadystatechange=function(){};
      mywindow.focus();
      mywindow.print();
      mywindow.close();
     }
    }'; ?>
	}
</script>