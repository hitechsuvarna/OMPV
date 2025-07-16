<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
<link href="<?php echo base_url().'assets/css/tableexport.css'; ?>" rel="stylesheet">
<script src="<?php echo base_url().'assets/js/FileSaver.js'; ?>"></script>
<script src="<?php echo base_url().'assets/js/tableexport.js'; ?>"></script>
<script src="<?php echo base_url().'assets/js/Blob.js'; ?>"></script>
<script src="<?php echo base_url().'assets/js/xlsx.core.min.js'; ?>"></script>


<style type="text/css">
	.purchase_table {
		width: 100%;
        text-align: left;
        border: 0px solid #ccc;
        border-collapse: collapse;
        overflow:auto;
        height:450px;
        display:block;
        
	}

	@media only screen and (max-width: 760px) {
		.purchase_table {
			display: block;
        	overflow: auto;
		}
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
		color: #000;
	}

	.cart_table_details {
		width: 100%;
	}
	.amount {
		text-align: right;
	}

	#order_number {
		color: #ff0000;
	}

	#total_amount {
		color: #ff0000;
	}

	.product_qty {
        border: 1px solid #999;
        border-radius: 3px;
        padding: 10px;
        text-align: center;
        margin-bottom: 10px;
        width: 70px;
    }
    
    .detail_center {
        text-align:center;
    }
    
    .detail_right {
        text-align:right;
    }
    
    .dhr_card {
        box-shadow: 0px 2px 5px #aaa;
        border-radius: 5px;
        padding:20px;
    }
    
    .dhr_card_title {
        text-align: left;
        padding-left: 15px;
        color: #aaa;
        font-weight: bold;
        font-size: 20px;
    }
    
    .dhr_card_content {
        font-size: 3em;
        padding: 30px;
        text-align: center;
        color: #666;
    }
</style>


<main class="mdl-layout__content">
	<div class="mdl-grid">
		<div class="mdl-cell mdl-cell--2-col">
		    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
		        <select class="mdl-textfield__input" id="type">
		            <option value="0">Select</option>
		            <option value="total_orders">Total Orders</option>
		            <option value="total_sale">Total Sale</option>
		            <option value="product_trend">Product Trends</option>
		            <option value="txn_trend">Transaction Trends</option>
		            <option value="txn_today">Todays Sale & Purchase</option>
		            <option value="txn_tax">Sales Register</option>
		            <option value="txn_tax_purchase">Purchase Register</option>
		            <option value="txn_income_delivery">Income Delivery</option>
		            <option value="txn_income">Income Invoice</option>
		        </select>
				<label class="mdl-textfield__label" for="vendors">Operation</label>
			</div>
		</div>
		<div class="mdl-cell mdl-cell--2-col">
			<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
		        <input type="text" id="from" class="mdl-textfield__input">
				<label class="mdl-textfield__label" for="from">From Date</label>
			</div>
		</div>
		<div class="mdl-cell mdl-cell--2-col">
			<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
				<input type="text" id="to" class="mdl-textfield__input">
				<label class="mdl-textfield__label" for="to">To</label>
			</div>
	    </div>
	    <div class="mdl-cell mdl-cell--2-col">
			<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
				<select class="mdl-textfield__input" id="filter_type">
		            <option value="0">Select</option>
		            <option value="client">Client Name</option>
		            <option value="amt_greater">Amount Greater Than</option>
		            <option value="amt_less">Amount Less Than</option>
		            <option value="txnid">Txn ID</option>
		            <option value="txntype">Txn Type</option>
		            <option value="txnstatus">Txn Status</option>
		            <option value="txnmode">Mode</option>
		        </select>
				<label class="mdl-textfield__label" for="filter_type">Filter By</label>
			</div>
	    </div>
	    <div class="mdl-cell mdl-cell--2-col">
			<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
				<input type="text" id="filter_keyword" class="mdl-textfield__input">
				<label class="mdl-textfield__label" for="filter_keyword">Keywords</label>
			</div>
	    </div>
	    <div class="mdl-cell mdl-cell--1-col">
		    <button class="mdl-button mdl-js-button mdl-button--colored mdl-button--raised" id="search"><i class="material-icons">search</i></button>
		</div>
		<div class="mdl-cell mdl-cell--1-col">
		    <button class="mdl-button mdl-js-button mdl-button--colored mdl-button--raised" id="download"><i class="material-icons">file_download</i></button>
		</div>
	</div>
	<div class="mdl-grid">
	    <div id="print_data"></div><canvas id="myChart"></canvas>
	    
	</div>
	<button class="lower-button mdl-button mdl-button-done mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--primary" id="print">
		<i class="material-icons">print</i>
	</button>
</div>
</div>

</body>
<script type="text/javascript">
	$('#from').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
	$('#to').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
	
	var dt = new Date();
	var s_dt = dt.getFullYear() + '-' + (dt.getMonth() + 1) + '-' + dt.getDate();
	$('#from').val(s_dt);
	$('#to').val(s_dt);
	
</script>
<script>
	$(document).ready(function() {
	    $('#search').click(function(e) {
	        e.preventDefault();
	        
	        $.post('<?php echo base_url().$type."/Transactions/search_analyze"; ?>', {
	            'type' : $('#type').val(),
	            'from' : $('#from').val(),
	            'to' : $('#to').val(),
	            'filter' : $('#filter_type').val(),
	            'keyword' : $('#filter_keyword').val(),
	        }, function(d,s,x) {
	            var a=JSON.parse(d), t="", chrt=false;
	            var sale_total=0.0, transport_total=0.0, main_total=0.0;
	            if(a.sum) {
	               // t+="<h4>Total Amount: Rs." + a.sum[0].amt + "/-</h4>";
	            }
	            if(a.txntable) {
	                t+='<table class="purchase_table"><thead><tr><th>Type</th><th>Txn Id</th><th>Date</th><th>Client</th><th>Status</th><th>Amount</th><th>Mode</th><th>Note</th><th>Sub Dealer</th><th>Transport Details</th><th>Transport Expense</th></tr></thead><tbody>';
	                for(var i=0;i<a.txntable.length;i++) {
	                    sale_total+=parseInt(a.txntable[i].it_amount);
	                    t+='<tr><td>' + a.txntable[i].it_type + '</td><td>' + a.txntable[i].it_txn_no + '</td><td>' + a.txntable[i].it_date + '</td><td>' + a.txntable[i].ic_name + '</td><td>' + a.txntable[i].it_status + '</td><td>' + a.txntable[i].it_amount + '</td><td>' + a.txntable[i].it_mode + '</td><td>' + a.txntable[i].it_note + '</td><td>';
	                    
	                    if(a.txntable[i].idu_name !== null) {
	                    	t+=a.txntable[i].idu_name	
	                    }
	                    t+='</td>';
	                    if(a.txntable[i].ittd_expense == null) {
	                        t+='<td></td><td>0</td>'
	                    } else {
	                        transport_total+=parseInt(a.txntable[i].ie_amount);
	                        t+='<td>' + a.txntable[i].ittd_transporter + ' - ' + a.txntable[i].ittd_date + '</td><td>' + a.txntable[i].ie_amount + '</td>';
	                    }
	                    main_total=sale_total+transport_total;
	                    t+='</tr>';
	                }
	                t+='</tbody>';
	                t+='<tfoot style="border:1px solid #000;"><tr><td colspan="5">Total</td><td>' + sale_total + '<td colspan="4"></td><td>' + transport_total +  '</tr><tr><td colspan="10">Grand Total</td><td>' + main_total + '</td></tr></tfoot>';
	                
	                t+='</table>'
	            }
	            if(a.prodhigh) {
	                t+="<h4>Highest Selling Product: " + a.prodhigh[0].ip_name + " Qty: " + a.prodhigh[0].sum + "</h4>";
	            }
	            if(a.prodlow) {
	                t+="<h4>Lowest Selling Product: " + a.prodlow[0].ip_name + " Qty: " + a.prodlow[0].sum + "</h4>";
	            }
	            if(a.prod) {
	                t+='<table class="purchase_table"><thead><tr><th>Product</th><th>Sold</th></tr></thead><tbody>';
	                for(var i=0;i<a.prod.length;i++) {
	                    t+='<tr><td>' + a.prod[i].ip_name + '</td><td>' + a.prod[i].sum + '</td></tr>';
	                }
	                t+='</tbody></table>'
	            }
	            if(a.txntrend) {
	                var amt_data = [];
	                var date_data = [];
	                chrt=true;
	                for(var i=0;i<a.txntrend.length;i++) {
	                   // data.push({'x' : a.txntrend[i].sum, 'y' : a.txntrend[i].date });    
	                   amt_data.push(a.txntrend[i].sum);
	                   date_data.push(a.txntrend[i].date);
	                }
	                
	                var ctx = document.getElementById('myChart').getContext('2d');
	                var myLineChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: date_data,
                            datasets: [{
                                label: 'Amount ',
                                data: amt_data,
                                backgroundColor: '#0033ccaa',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                yAxes: [{
                                    ticks: {
                                        beginAtZero:true
                                    }
                                }]
                            }
                        }
                    });
	            }
	            if(a.txntax) {
	                t+='<table class="purchase_table"><thead><tr><th>Date</th><th>Txn Num</th><th>Client Name</th><th>GST No</th><th>Taxable Amount</th><th>Transport Amount</th>';
	                for(var i=0;i<a.taxes.length;i++) {
	                    t+='<th>' + a.taxes[i].itx_name + '</th>'
	                }
	                t+='<th>Total</th></tr></thead><tbody>';
	                for(var i=0;i<a.txntax.length;i++) {
	                    var totalAmount = parseFloat(a.txntax[i].total) + parseFloat(a.txntax[i].transport);
	                    t+='<tr><td>' + a.txntax[i].date + '</td><td>' + a.txntax[i].id + '</td><td>' + a.txntax[i].name + '</td><td>' + a.txntax[i].gstno + '</td><td>' + a.txntax[i].amt + '</td><td>' + a.txntax[i].transport + '</td>';
	                    for(var j=0;j<a.taxes.length;j++){
	                        var flg=false;
	                        for(var k=0;k<a.txntax[i].tax.length;k++){
	                            if(a.taxes[j].itx_id == a.txntax[i].tax[k].taxid) {
	                                t+='<td>' + a.txntax[i].tax[k].amt + '</td>';
	                                flg=true;
	                                break;
	                            }
	                        }
	                        if(flg==false) { t+='<td> </td>'; }
	                    }
	                    t+='<td>' + totalAmount + '</td></tr>';
	                }
	                t+='</tbody></table>'
	            }
	            
	            if(a.income) {
	               t+='<div class="mdl-grid">';m="",x="";
	               var purchase_total=0, sale_total=0, expense_total=0;
	               m+='<table class="purchase_table"><thead><tr><th>Type</th><th>Client Name</th><th>Date</th><th>Amount</th></tr></thead><tbody>';
	               for(var i=0;i<a.income.sale.length;i++) {
	                   sale_total+=parseInt(a.income.sale[i].it_amount)+parseInt(a.income.sale[i].ie_amount);
	                   m+='<tr><td>'+a.income.sale[i].it_type+'</td><td>'+a.income.sale[i].ic_name+'</td><td>'+a.income.sale[i].it_date+'</td><td>'+(a.income.sale[i].it_amount + a.income.sale[i].ie_amount)+'</td></tr>';
	               }
	               m+='</tbody></table>';
	               
	               if(a.income.sale) {
                        t+='<div class="mdl-cell mdl-cell--4-col"><div class="dhr_card"><div class="dhr_card_title">Total Sale</div><div class="dhr_card_content">' + sale_total + '</div></div>' + m;
	               }
	               t+='</div>';
	               
	               m='<table class="purchase_table"><thead><tr><th>Type</th><th>Vendor Name</th><th>Date</th><th>Amount</th></tr></thead><tbody>';
	               for(var i=0;i<a.income.purchase.length;i++) {
	                   purchase_total+=parseInt(a.income.purchase[i].it_amount)+parseInt(a.income.purchase[i].ie_amount);
	                   m+='<tr><td>'+a.income.purchase[i].it_type+'</td><td>'+a.income.purchase[i].ic_name+'</td><td>'+a.income.purchase[i].it_date+'</td><td>'+(a.income.purchase[i].it_amount + a.income.purchase[i].ie_amount)+'</td></tr>';
	               }
	               m+='</tbody></table>';
	               
	               if(a.income.purchase) {
                        t+='<div class="mdl-cell mdl-cell--4-col"><div class="dhr_card"><div class="dhr_card_title">Total Purchase</div><div class="dhr_card_content">' + purchase_total + '</div></div>' + m;
	               }
	               t+='</div>';
	               
	               m='<table class="purchase_table"><thead><tr><th>Description</th><th>Date</th><th>Amount</th></tr></thead><tbody>';
	               for(var i=0;i<a.income.expenses.length;i++) {
	                   expense_total+=parseInt(a.income.expenses[i].ie_amount);
	                   m+='<tr><td>'+a.income.expenses[i].ie_description+'</td><td>'+a.income.expenses[i].ie_date+'</td><td>'+a.income.expenses[i].ie_amount+'</td></tr>';
	               }
	               m+='</tbody></table>';
	               
	               if(a.income.expenses) {
                        t+='<div class="mdl-cell mdl-cell--4-col"><div class="dhr_card"><div class="dhr_card_title">Total Expenses</div><div class="dhr_card_content">' + expense_total + '</div></div>' + m;
	               }
	               t+='</div>';
	               
	               if(a.income) {
	                   x='<div class="mdl-cell mdl-cell--12-col"><div class=""><div class="dhr_card_title">Total Income</div><div class="dhr_card_content">' + (sale_total - purchase_total - expense_total) + '</div></div>';
	               }
	               t=x+t;
	               
	               
	            }
	            
	            if(chrt == false) {
	                $('#print_data').css('display','block');
	                $('#myChart').css('display','none');
	                $('#print_data').empty();
	                $('#print_data').append(t);    
	            } else {
	                $('#print_data').css('display','none');
	                $('#myChart').css('display','block');
	            }
	            
	            
	        })
	    });
	   
		$('#print').click(function(e) {
			e.preventDefault();
			print_reciept();
		});
		
		$('#download').click(function(e) {
		    e.preventDefault();
		    $('.purchase_table').tableExport({
                // Displays table headings (th or td elements) in the <thead>
                headings: true,                    
                // Displays table footers (th or td elements) in the <tfoot>    
                footers: true, 
                // Filetype(s) for the export
                formats: ["xls"],           
                // Filename for the downloaded file
                filename: $('#type').val() + '-' + $('#from').val() + '_' + $('#to').val(),                         
                // Style buttons using bootstrap framework  
                bootstrap: true,                     
                // Position of the caption element relative to table
                position: "top",                   
                // (Number, Number[]), Row indices to exclude from the exported file(s)
                ignoreRows: null,       
                // (Number, Number[]), column indices to exclude from the exported file(s)              
                ignoreCols: null,                
                // Selector(s) to exclude cells from the exported file(s)       
                ignoreCSS: ".tableexport-ignore",  
                // Selector(s) to replace cells with an empty string in the exported file(s)       
                emptyCSS: ".tableexport-empty",   
                // Removes all leading/trailing newlines, spaces, and tabs from cell text in the exported file(s)     
                trimWhitespace: false         

            });
		})
	});
	
	function print_reciept() {
		var mywindow = window.open('', 'Analyze', fullscreen=1);
		mywindow.document.write($('#print_data').html()); mywindow.document.close(); mywindow.focus(); mywindow.print(); mywindow.close();
	}
</script>
</html>