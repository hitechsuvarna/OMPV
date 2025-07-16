<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
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
	}
	
	.purchase_table > tfoot  {
		box-shadow: 0px 5px 5px #ccc;
	}

	.purchase_table > tfoot > tr > th {
		padding: 10px;
	}
	

	.cart_table_details {
		width: 100%;
	}
	.amount {
		text-align: right;
	}

	.order_number {
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

    .stock_display > h4	 {
    	text-align: center;
    }

    .stock_display > table {
    	width: 100%;
    }
    
    .ui-menu {
    	z-index: 2000;
    }

    .action-button {
        width:100%;
    }
    

    .modal-content {
    	border-radius: 0px;
    	box-shadow: 1px 5px 77px #000;
    }

    .modal-header {
    	padding: 30px;
    	border-bottom: 0px;
    }

    .modal {
    	padding-left: 0px;
    }
</style>

<main class="mdl-layout__content">
	<div class="mdl-grid">
        <div class="mdl-cell mdl-cell--12-col">
            <button class="mdl-button mdl-js-button mdl-button--colored" id="add_entry"><i class="material-icons">add</i></button>    
            <button class="mdl-button mdl-js-button mdl-button--colored" id="download"><i class="material-icons">file_download</i></button>
        </div>
    </div>
	<div class="mdl-grid stock_transaction">
	    <table class="purchase_table" id="details">
			<thead>
				<tr>
					<th colspan="2">Account Title</th>
					<th>Debit</th>
					<th>Credit</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
			    <?php $damt=0.0; $camt=0.0; for($i=0;$i<count($classes);$i++) {
			       echo '<tr><td colspan="5"><b>'.$classes[$i]->inacc_name.'</b></td></tr>'; 
			       for($j=0;$j<count($detail);$j++) {
			           if($detail[$j]->cid == $classes[$i]->inacc_id) {
			               echo '<tr><td></td><td>'.$detail[$j]->ledger.'</td>';
			               if($classes[$i]->inacc_type == "debit") {
			                    $damt+=$detail[$j]->balance;
			                   echo '<td>'.$detail[$j]->balance.'</td><td></td><td></td>';
			               } else {
			                   $camt+=$detail[$j]->balance;
			                   echo '<td></td><td>'.$detail[$j]->balance.'</td><td></td>';
			               }
			               echo '</tr>';
			           }
			       }
			    }?>
			    
				<?php $amt=0.0; for($j=0;$j<count($detail);$j++) {
				    // echo '<tr id="'.$detail[$j]->inal_id.'"><td>'.$detail[$j]->inal_ledger.'</td><td>'.$detail[$j]->debit.'</td><td>'.$detail[$j]->credit.'</td><td>'.$detail[$j]->balance.'</td><td><button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--primary edit" id="'.$detail[$j]->inal_id.'" data-toggle="modal" data-target="#edit_modal"><i class="material-icons">create</i></button><button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--primary delete" id="'.$detail[$j]->inal_id.'"><i class="material-icons">delete</i></button></td></tr>';
    				// $amt+=$detail[$j]->debit-$detail[$j]->credit;
				} ?>
			</tbody>
			<tfoot>
			    <tr>
			        <th colspan="2">Total</th>
			        <th id="tl_total"><?php echo $damt; ?></th>
			        <th id="tl_total"><?php echo $camt; ?></th>
			        <th></th>
			    </tr>
			    <tr>
			        <th colspan="3">Balance</th>
			        <th id="bl_total"><?php echo ($damt-$camt); ?></th>
			        <th></th>
			    </tr>
			    
			</tfoot>
		</table>
	</div>
</main>

</div>
</div>
</body>
<div class="modal fade"  id="filter_modal" role="dialog">
	<div class="modal-dialog">
	<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Filter & Search</h2>
			</div>
			<div class="modal-body">
	            <div class="mdl-grid">
			        <div class="mdl-cell mdl-cell--6-col">
			            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
    						<input type="text" id="t_f_date" class="mdl-textfield__input">
    						<label class="mdl-textfield__label" for="t_f_date">From Date</label>
    					</div>
			        </div>
			        <div class="mdl-cell mdl-cell--6-col">
			            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
    						<input type="text" id="t_t_date" class="mdl-textfield__input" value="">
    						<label class="mdl-textfield__label" for="t_t_date">To Date</label>
    					</div>
			        </div>
			        <div class="mdl-cell mdl-cell--4-col">
			            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
    						<input type="text" id="t_from" class="mdl-textfield__input" value="">
    						<label class="mdl-textfield__label" for="t_from">From</label>
    					</div>
			        </div>
			        <div class="mdl-cell mdl-cell--4-col">
			            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
    						<input type="text" id="t_to" class="mdl-textfield__input" value="">
    						<label class="mdl-textfield__label" for="t_to">To</label>
    					</div>
			        </div>
			        <div class="mdl-cell mdl-cell--4-col">
			            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
    						<input type="text" id="t_desc" class="mdl-textfield__input" value="">
    						<label class="mdl-textfield__label" for="t_desc">Description</label>
    					</div>
			        </div>
			    </div>
			</div>
			<div class="modal-footer">
				<button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" id="filter_records"><i class="material-icons">search</i>FIND RECORDS</button>
				<button type="button" class="mdl-button mdl-js-button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<script>
    var selected_je = 0, records = [];
	$(document).ready(function() {
	    // SEARCH & FILTER

		$('#t_f_date').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
	    $('#t_t_date').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
	    var dt = new Date();
	    var s_dt = dt.getFullYear() + '-' + (dt.getMonth() + 1) + '-' + dt.getDate();
		$('#t_f_date').val(s_dt);
		$('#t_t_date').val(s_dt);
		
		$('#fixed_header_search').click(function(e) {
			e.preventDefault();
			$('#filter_modal').modal('toggle');
		})
		$('#filter_records').click(function(e) {
		    e.preventDefault();
		    $.post('<?php echo base_url().$type."/Accounting/filter_journal_entries"; ?>', {
		        'f_dt' : $('#t_f_date').val(),
		        't_dt' : $('#t_t_date').val(),
		        'f' : $('#t_from').val(),
		        't' : $('#t_to').val(),
		        'd' : $('#t_desc').val(),
		    }, function(d,s,x) {
		    	var b = JSON.parse(d), a="";
    		    fill_main_table(b);
    		}, "text");
		});

		// REST
	    $('#add_entry').click(function(e) {
	        e.preventDefault();
	        window.location = "<?php echo base_url().$type.'/Accounting/journal_entry_details/tr/'; ?>";
	    });

	    
	    $('#details').on('click','.edit', function(e) {
            e.preventDefault();
            window.location = "<?php echo base_url().$type.'/Accounting/journal_entry_details/g/'; ?>" + $(this).prop('id') + "<?php echo '/'; ?>";
        });
        
        $('#details').on('click','.delete', function(e) {
            e.preventDefault();
            $(this).attr('disabled','disabled');
            $.post('<?php echo base_url().$type."/Accounting/delete_journal_entry/"; ?>' + $(this).prop('id') + '<?php echo "/g/"; ?>', {}, function(d,s,x) {
                var b = JSON.parse(d);
                fill_main_table(b);
            }, "text"); 
        });
        
		function reset_fields(from) {
		    if(from == true) {
		        $('#j_from').val('');
		        $('#add_records > tbody').empty();
		        records = [];
		        $('#j_from').focus();
		        selected_je = "";
		      //  display_entry();
		    } else {
		        $('#j_to').focus();
		    }
		    $('#j_to').val('');
		    $('#j_details').val('');
		    $('#j_amt').val('');
		    $('#edit_modal').modal('hide');
		    $('#add_modal').modal('hide');
		    $('#mytags > .tagit-choice').remove();
		    $('#myedittags > .tagit-choice').remove();
		}
		
		function fill_main_table(b) {
	        $('#details > tbody').empty(); var a="";var t=0.0;
	        for(var i=0;i<b.length;i++) {
	        	t+=parseFloat(b[i].amount);
	            a+='<tr id="' + b[i].id + '"><td>' + b[i].date + '</td><td>' + b[i].account_from + '</td><td>' + b[i].account_to + '</td><td>' + b[i].account_description + '</td><td>'+ b[i].amount + '</td><td><button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--primary edit" id="' + b[i].id + '"><i class="material-icons">create</i></button><button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--primary delete" id="' + b[i].id + '"><i class="material-icons">delete</i></button></td></tr>';
	        }
	        $('#details > tbody').append(a);
	        $('#tl_total').empty(); $('#bl_total').empty();
	        $('#tl_total').html(t); $('#bl_total').html(t);
	    }

	    $('#download').click(function(e) {
		    e.preventDefault();
		    var s_dt = dt.getDate() + '-' + (dt.getMonth() + 1) + '-' + dt.getFullYear();
			
		    $('.purchase_table').tableExport({
                // Displays table headings (th or td elements) in the <thead>
                headings: true,                    
                // Displays table footers (th or td elements) in the <tfoot>    
                footers: true, 
                // Filetype(s) for the export
                formats: ["xls","csv"],           
                // Filename for the downloaded file
                filename: 'Trial balance' + ' - ' +s_dt,
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
</script>
</html>