<title><?php echo $title; ?></title>
<style>
    .detail_center {
        text-align:center;
    }
    
    .detail_right {
        text-align:right;
    }
</style>

<style> 
    @media print { 
        .main_head { 
            display:table-header-group !important; 
            page-break-inside:avoid;
        }
        
        .main_foot { 
            page-break-inside:avoid;
            display:table-footer-group !important;
        }
        
        
    }
    
    .col_right {
        text-align: right;
        padding-right: 10px;
    }
    
    .col_left {
        text-align: left;
        width: 30%;
    }
    
    .item_header > th {
        font-weight: bold;
        border:1px solid;
    }
    
    .item_name > td {
        border: 0.5px solid;
        height: 20px;
    }
    
    @page {
        size: A4;
        size:landscape;
    }
    
    @media print {
        #duplicate {
            /*page-break-before: always;*/
        }
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
						<textarea id="s_note" class="mdl-textfield__input"><?php echo $txn[0]->it_note; ?></textarea>
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
					
					$content="";
					$content.='<table><thead class="main_head"><tr>';
                    for($j=0;$j<count($typearr);$j++) {
                        $content.='<td><table><tbody>';
                        $content.='<tr><td colspan="6"><img src="'.$logo.'" style="width: 100%; height: 100px;margin-bottom:5px;"></td></tr>';
                        $content.='<tr><td colspan="6">Delivery Challan - '.$typearr[$j].'</td></tr>';
                        $content.='<tr><td colspan="4">Client name: <b>'.$txn[0]->ic_name.'</b></td><td>Challan No</td><td>'.$txn[0]->it_txn_no.'</td></tr>';
                        $content.='<tr><td colspan="4">'.$txn[0]->ic_address.'</td><td>Date</td><td>'.$txn[0]->it_date.'</td></tr>';
                        $content.='<tr><td colspan="4">GST: <b>'.$txn[0]->ic_gst_number.'</b></td><td></td><td></td></tr>';
                        $content.='<tr><td colspan="6">Delivery Details</td></tr>';
                        $content.='</tbody></table></td>';
                    }
                    $content.='</tr></thead><tbody><tr>';
                    for($j=0;$j<count($typearr);$j++) {
                        $content.='<td><table><tbody>';
                        $content.='<tr><th>Sr No</th><th>Particulars</th><th>Qty</th><th>Rate</th><th>Amount</th></tr>';
                        $line_cnt = count($txn_details);
                        if(isset($txn_transport) > 0 ) { $line_cnt_total = 10 - $line_cnt; } else { $line_cnt_total = 11 - $line_cnt; }
                        $amt=0; 
                        for($i=0; $i < count($txn_details) ; $i++) { 
                            $amt+=$txn_details[$i]->itp_amount;
                            $content.='<tr><td style="text-align:center;">'.($i+1).'</td><td>'.$txn_details[$i]->ip_name.'</td><td>'.$txn_details[$i]->itp_qty.'</td><td>'.$txn_details[$i]->itp_rate.'</td><td>'.$txn_details[$i]->itp_amount.'</td></tr>';
                        }
                        for ($i=0; $i < $line_cnt_total; $i++) {
                            $content.='<tr><td>'.$i.'</td><td></td><td> </td><td></td><td></td></tr>';
                        }
                        $content.='</tbody>';
                        $content.='<tfoot><tr><td colspan="5">Recievers Signature</td></tr><tr><td colspan="5">Payment Within: '.$txn[0]->ic_credit.'</td></tr><tr><td colspan="3" rowspan="3">Note: '.$txn[0]->it_note.'</td></tr><tr><td>Subtotal</td><td>'.$amt.'</td></tr><tr><td>Transport</td><td>';
                            if($txn_transport) { $transport=$txn_transport[(count($txn_transport)-1)]->ie_amount; } else { $transport=0;} ;
                            $content.=$transport.'</td>';
                        $content.='</tr><tr><td colspan="4">Grand total</td><td>'.($amt + $transport).'</td></tr></tfoot>';
                        $content.='</table></td>';
                    }
                    $content.='</tr></tbody>';
                    $content.='<tfoot class="main_foot"><tr>';
                    for($j=0;$j<count($typearr);$j++) {
                        $content.='<td><table><tbody><tr><td><div style="font-size:0.7em;font-weight:bold;">TERMS & CONDITIONS</div><div style="font-size:0.5em; line-height:1em;">1. Subject to mumbai Jurisdiction. 2. Goods supplied on order will not be accepted back. 3. Payment terms - Immidiate. Intrest @ 18% per annum will be charged on delayed payments. 4. Warranty/Service of purchased goods is the manufacturers/ importers responsibility under the warranty period. 5. I the undersigned have accepted the terms and conditions of the invoice. 6. O M Prime Ventures shall not be responsible for any expenses involving legal costs in case of a dispute 7. Received goods in order and good condition.</div><div style="font-size:0.6em;">E.&.O.E.</div></td><td><div style="padding-top: 3em;width:90%;border-bottom: 1px solid #000;margin:0px;"></div><div style="text-align: right; margin:0px;"><b style="padding-top: 0px; font-size:0.9em;">Proprietor/ Authorized Signature</b></div></td></tr></tbody></table></td>';
                    }
                    $content.='</tr></tfoot></table>';
                    echo $content;
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
		<?php echo 'mywindow.document.write(\''.$content.'\'); mywindow.document.close(); mywindow.focus(); mywindow.print(); mywindow.close();'; ?>
	}
	
	function print_slip() {
		var mywindow = window.open('', '<?php echo $title_doc; ?>', fullscreen=1);
		<?php echo 'mywindow.document.write(\''.$content.'\'); mywindow.document.close(); mywindow.focus(); mywindow.print(); mywindow.close();'; ?>
	}
</script>