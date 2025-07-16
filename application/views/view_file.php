<?php
	$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->SetTitle('Delivery Challan');
    $pdf->SetHeaderMargin(10);
    $pdf->SetTopMargin(10);
    $pdf->setFooterMargin(10);
    $pdf->SetAutoPageBreak(true);
    $pdf->SetAuthor('Author');
    $pdf->SetDisplayMode('real', 'default');
    
    $pdf->AddPage();
    
    
	$typearr = ["Original"]; 
	$typeidarr = ["original"];
	$content = "<style> .col_right {text-align: right; padding-right: 10px; } .col_left {text-align: left; width: 30%; } .item_header > th {font-weight: bold; border:0.5px solid; } .item_name > td { border: 0.5px solid; height: 20px;} @page {size: A4; size:landscape; } @media print { #duplicate {/*page-break-before: always;*/ } } </style><table style=\"width:100%;border:2px solid;\"><tr>";
	for ($j=0; $j < count($typearr); $j++) { 
		$content.= '<td>';
		$content.= '<div style="border: 0px #999 solid;padding: 20px;" id="'.$typeidarr[$j].'">';
		$content.= '<div style="border: 0px #000 solid;">';
		$content.= '<img src="'.$logo.'" style="width: auto; height: 100px;margin-bottom:10px;">';
		$content.= '<div style="text-align: center">Delivery Challan - '.$typearr[$j].'</div>';
		$content.= '<table border="0" style="width: 100%;">';
		$content.= '<tr>';
		$content.= '<td style="width: 50%;">';
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
		
        $line_cnt_total = 14 - $line_cnt;
        $amt=0;
        for ($i=0; $i < count($txn_details) ; $i++) { 
            $amt+=$txn_details[$i]->itp_amount;
			$content.= '<tr class="item_name"><td>'.($i+1).'</td><td>'.$txn_details[$i]->ip_name.'</td><td class="detail_center" style="text-align:center;">'.$txn_details[$i]->itp_qty.'</td><td class="detail_right" style="text-align:right;">'.$txn_details[$i]->itp_rate.'</td><td class="detail_right" style="text-align:right;">'.$txn_details[$i]->itp_amount.'</td></tr>';
		}
		for ($i=0; $i < $line_cnt_total; $i++) {
			$content.= '<tr class="item_name"><td> </td><td></td><td class="detail_center" style="text-align:center;"> </td><td class="detail_right" style="text-align:right;"></td><td class="detail_right" style="text-align:right;"></td></tr>';
		}
		$content.= '</tbody>';
		$content.= '<tfoot>';
		$content.= '<tr class="item_name"><td colspan="5">Receivers Signature</td></tr>';
		$content.= '<tr class="item_name"><td colspan="5">Payment within: '.$txn[0]->ic_credit.' days</td></tr>';
		$content.= '<tr class="item_name"><td colspan="3" rowspan="2">Note: '.$txn[0]->it_note.'</td><td>Subtotal</td><td>'.$amt.'</td></tr><tr class="item_name"><td>Grand Total</td><td>'.$amt.'</td></tr>';
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
		$content.= '<td style="width:100%;"><div style="font-size:0.7em;font-weight:bold;">TERMS & CONDITIONS</div><div style="font-size:0.5em; line-height:1em;">1. Subject to mumbai Jurisdiction. 2. Goods supplied on order will not be accepted back. 3. Payment terms - Immidiate. Intrest @ 18% per annum will be charged on delayed payments. 4. Warranty/Service of purchased goods is the manufacturers/ importers responsibility under the warranty period. 5. I the undersigned have accepted the terms and conditions of the invoice. 6. O M Prime Ventures shall not be responsible for any expenses involving legal costs in case of a dispute 7. Received goods in order and good condition.</div><div style="font-size:0.6em;">E.&.O.E.</div></td>';
		$content.= '<td>';
		$content.= '<div style="text-align: right;margin-top: 4em;white-space:nowrap;">';
		$content.= '<b style="border-top: 1px #000 solid;padding-top: 10px; font-size:0.9em;">Proprietor/ Authorized Signature</b>';
		$content.= '</div>';
		$content.= '</td>';
		$content.= '</tr>';
		$content.= '</table>';
		$content.= '<hr>';
		$content.= '</div>';

		$content.= "</td>";
	}
	$content.= "</tr></table>";
// 			echo $content;
// 			$content = "<h1>Hey there!!</h1>";
    $upload_dir = '/home/hsuvar5/public_html/onedyamics.in/ompv/'.$oid.'/';
	if(!file_exists($upload_dir)) {
		mkdir($upload_dir, 0777, true);
		echo "Okay";
	}
	
	$t = '/home/hsuvar5/public_html/onedyamics.in/ompv/'.$oid.'/challan.pdf';
    $pdf->writeHTML($content);
    
    $pdf->Output($t, 'F');
    // redirect('http://onedynamics.in/ompv/'.$oid.'/challan.pdf');
    
?>