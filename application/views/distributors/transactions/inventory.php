<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

<style type="text/css">
	a {
        color: #fff;
        text-decoration: none;
    }

    a:hover {
        color: #fff;
        text-decoration: none;
    }

    /*html, body {
        height: 100%;
        width: 100%;
        margin: 0;
        padding: 0;
        overflow: hidden;
    }*/
    
    #products {
        width: 100%;
        text-align: left;
        border: 0px solid #ccc;
        border-collapse: collapse;
    }

    #products > thead > tr {
        box-shadow: 0px 5px 5px #ccc;
    }

    #products > thead > tr > th {
        padding: 10px;
    }

    #products > tbody > tr > td {
        padding: 15px;
    }

    #products > tbody > tr {
        border-bottom: 1px solid #ccc;
    }

    #products > tbody > tr > td {
        color: #666;
        text-decoration: none;
    }
    
    .ui-front {
        z-index: 2000;
    }
    
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

	.purchase_table > tfoot > tr {
		border-bottom: 1px solid #ccc;
	}

	.purchase_table > tfoot > tr > td {
		padding: 15px;
		font-weight: bold;
	}

</style>

<main class="mdl-layout__content">
    <div class="mdl-grid">
        <div class="mdl-cell mdl-cell--12-col">
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
				<select id="p_category" class="mdl-textfield__input">
				    <option value="0">All</option>
				    <?php 
				        for($i=0;$i<count($category); $i++) {
				            echo '<option value="'.$category[$i]->ica_id.'">'.$category[$i]->ica_category_name.'</option>';
				        }
				    ?>
				</select>
				<label class="mdl-textfield__label" for="p_name">Select Category</label>
			</div>
        <!--</div>-->
        <!--<div class="mdl-cell mdl-cell--4-col">-->
            <button class="mdl-button mdl-js-button mdl-button--colored" id="search_return"> Search Return Items</button>
            <button class="mdl-button mdl-js-button mdl-button--colored" id="return"> Return Items</button>
            <button class="mdl-button mdl-js-button mdl-button--colored" id="reconcile"> Reconcile </button>
            <!--<button class="mdl-button mdl-js-button mdl-button--colored" id="defective"> Manage Defective </button>-->
        <!--</div>-->
        <!--<div class="mdl-cell mdl-cell--4-col" style="text-align:right;">-->
            <button class="mdl-button mdl-js-button mdl-button--colored" id="print_order_list"> Order List </button>
            <button class="mdl-button mdl-js-button mdl-button--colored" id="clear_order_list"> Clear Order List </button>
            
        </div>
    </div>
	<div class="mdl-grid">
	    <table id="products">
            <thead>
                <tr>
                    <th>Dealer</th>
                    <th>Balance</th>
                </tr>
            </thead>
            <tbody>
                <?php for ($i=0; $i < count($txn) ; $i++) { 
                    echo '<tr id="'.$txn[$i]['id'].'"> <td>'.$txn[$i]['product'].'</td><td>'.$txn[$i]['balance'].'</td></tr>';
                    }
                ?>
            </tbody>
        </table>
	</div>
	<button class="lower-button mdl-button mdl-button-done mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--accent" id="submit">
		<i class="material-icons">add</i>
	</button>
	<div id="demo-snackbar-example" class="mdl-js-snackbar mdl-snackbar">
        <div class="mdl-snackbar__text"></div>
        <button class="mdl-snackbar__action" type="button"></button>
    </div>
</div>
</div>
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Items to Return</h4>
            </div>
            <div class="modal-body">
                <div id="info_repea" class="mdl-grid">
					<div class="mdl-cel mdl-cell--6-col">
					    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width:90%;">
    						<input type="text" id="return_contact" class="mdl-textfield__input">
    						<label class="mdl-textfield__label" for="return_contact">Search Contact</label>
    					</div>
					</div>
					<div class="mdl-cel mdl-cell--6-col">
					    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width:90%;">
    						<input type="text" id="return_date" class="mdl-textfield__input" value="">
    						<label class="mdl-textfield__label" for="return_date">Date of Return</label>
    					</div>
					</div>
					<hr>
					<div class="mdl-cel mdl-cell--6-col">
					    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width:90%;">
    						<input type="text" id="prod" class="mdl-textfield__input">
    						<label class="mdl-textfield__label" for="prod">Search Products</label>
    					</div>
					</div>
					<div class="mdl-cel mdl-cell--2-col">
					    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width:90%;">
    						<input type="text" id="prod_qty" class="mdl-textfield__input" value="">
    						<label class="mdl-textfield__label" for="prod_qty">Qty</label>
    					</div>
					</div>
					<div class="mdl-cel mdl-cell--2-col" style="margin-top:12px;margin-left:0%;padding:0px;">
						<button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" id="add_item"><i class="material-icons">add</i></button>
					</div>
				</div>
				<div class="mdl-grid">
					<table class="purchase_table" id="return_table">
						<thead>
							<tr>
								<th>Action</th>
								<th>Product</th>
								<th>Qty</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								
							</tr>
						</tbody>
					</table>
				</div>
				
            </div>
            <div class="modal-footer">
                <button type="button" class="mdl-button mdl-js-button" data-dismiss="modal">Close</button>
                <button type="button" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" data-dismiss="modal" id="save_return">Save</button>
            </div>
        </div>
    </div>
</div>
<div id="myModal2" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Search Returns</h4>
            </div>
            <div class="modal-body">
                <div id="info_repea" class="mdl-grid">
					<div class="mdl-cel mdl-cell--4-col">
					    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width:90%;">
    						<input type="text" id="return_search_contact" class="mdl-textfield__input">
    						<label class="mdl-textfield__label" for="return_search_contact">Search Contact</label>
    					</div>
					</div>
					<div class="mdl-cel mdl-cell--4-col">
					    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width:90%;">
    						<input type="text" id="return_search_date" class="mdl-textfield__input" value="">
    						<label class="mdl-textfield__label" for="return_search_date">Date of Return</label>
    					</div>
					</div>
					<div class="mdl-cel mdl-cell--4-col">
					    <button class="mdl-button mdl-js--button mdl-button--colored" id="return_search"><i class="material-icons">search</i>Search</button>
					</div>
				</div>
				<div class="mdl-grid">
					<table class="purchase_table" id="return_search_table">
						<thead>
							<tr>
								<th>Action</th>
								<th>Particulars</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								
							</tr>
						</tbody>
					</table>
				</div>
				
            </div>
            <div class="modal-footer">
                <button type="button" class="mdl-button mdl-js-button" data-dismiss="modal">Close</button>
                <!--<button type="button" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" data-dismiss="modal" id="save_return">Save</button>-->
            </div>
        </div>
    </div>
</div>
</body>
<script>
    var date = new Date();
    var product_name = [];
	var product_qty = [];
	var edit_index = 0;
	var edit_flag = false;
	
	var edit_return_index=0;
	var edit_return_flag = false;

    var dt = new Date();
	var s_dt = dt.getFullYear() + '-' + (dt.getMonth() + 1) + '-' + dt.getDate();
	$('#return_date').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
	$('#return_search_date').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
	<?php 
		if(!isset($edit_txn)) {
		    echo "$('#return_date').val(s_dt);";
		    echo "$('#return_search_date').val(s_dt);";
		}
	?>
	
    var order_txt= date + '<br><style>table { width:100%; border: 1px solid #aaa; border-radius: 5px; } table > tbody > tr > td { border-top:1px solid #aaa; } </style><table><thead><tr><th>Product</th><th>Qty</th></tr></thead><tbody>';
	$(document).ready(function() {
	    var snackbarContainer = document.querySelector('#demo-snackbar-example');
	    
	    $('#return').click(function(e) {
	        e.preventDefault();
	        $('#myModal').modal('toggle');
	    })
	    $('#search_return').click(function(e) {
	        e.preventDefault();
	        $('#myModal2').modal('toggle');
	    })
	    
	    var product_data = [];
    	
    	<?php
    		for ($i=0; $i < count($products) ; $i++) { 
    			echo "product_data.push('".$products[$i]->ip_name."');";
    		}
    	?>
    	
    	$( "#prod" ).autocomplete({
            source: function(request, response) {
                var results = $.ui.autocomplete.filter(product_data, request.term);
                response(results.slice(0, 10));
            }
        });
        
        var contact_data=[];
        <?php
    		for ($i=0; $i < count($vendors) ; $i++) { 
    			echo "contact_data.push('".$vendors[$i]->ic_name."');";
    		}
    	?>
    	
    	$( "#return_contact" ).autocomplete({
            source: function(request, response) {
                var results = $.ui.autocomplete.filter(contact_data, request.term);
                response(results.slice(0, 10));
            }
        });
        
        $( "#return_search_contact" ).autocomplete({
            source: function(request, response) {
                var results = $.ui.autocomplete.filter(contact_data, request.term);
                response(results.slice(0, 10));
            }
        });
        
        function add_product() {
			product_name.push($('#prod').val());
    		product_qty.push($('#prod_qty').val());
		}

		function remove_product(id) {
			product_name.splice(id, 1);
			product_qty.splice(id, 1);
		}

		function edit_product(id) {

			$('#prod').val(product_name[id]);
    		$('#prod_qty').val(product_qty[id]);
     		edit_index = id;
    		edit_flag = true;
		}

		function update_product(id) {
			product_name[id] = $('#prod').val();
    		product_qty[id] = $('#prod_qty').val();
		}

		function update_list() {
			$('#return_table > tbody').empty();

			var out = "";
			for (var i = 0; i < product_name.length; i++) {
				out+='<tr><td><button class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored edit" id="' + i + '"><i class="material-icons">create</i></button><button class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored delete" id="' + i + '"><i class="material-icons">delete</i></button></td><td>' + product_name[i] + "</td><td>" + product_qty[i] + "</td>"; //<td>" + product_rate[i] + "</td><td>" + product_amount[i] + "</td>";
				out+= "</tr>";
			}

			$('#return_table > tbody').append(out);
		}

		function reset_fields() {
			$('#prod').val("");
    		$('#prod_qty').val("");
    		$('#products').focus();
		} 


		$('#prod_qty').keypress(function(e) {
			if (e.keyCode == 13) {
				if(edit_flag == true) {
					update_product(edit_index);
				} else {
					add_product();
				}
				
				update_list();
				reset_fields();
			}
		});

		$('#add_item').click(function(e) {
			e.preventDefault();

			if(edit_flag == true) {
				update_product(edit_index);
			} else {
				add_product();
			}
			update_list();
			reset_fields();
		});

		$('#return_table').on('click', '.delete', function(e) {
			e.preventDefault();

			remove_product($(this).prop('id'));
			update_list();
			reset_fields();
		});

		$('#return_table').on('click','.edit', function(e) {
			e.preventDefault();

			edit_product($(this).prop('id'));

		});

        $('#save_return').click(function(e) {
            e.preventDefault();
            if(edit_return_flag == true) {
                var url = '<?php echo base_url().$type."/Transactions/save_inventory_return/"; ?>' + edit_return_index;
            } else {
                var url = '<?php echo base_url().$type."/Transactions/save_inventory_return"; ?>';
            }
            
            $.post(url, {
                'p' : product_name,
                'q' : product_qty,
                'c' : $('#return_contact').val(),
                'd' : $('#return_date').val()
            }, function(d,s,x) {
                // var a=JSON.parse
                var ert = {message: 'Added to Return List.',timeout: 2000, }; 
                snackbarContainer.MaterialSnackbar.showSnackbar(ert);
                location.reload();
            })
        });

		$('#return_search').click(function(e) {
		    e.preventDefault();
		    $.post('<?php echo base_url().$type."/Transactions/search_inventory_return"; ?>', {
		        'c' : $('#return_search_contact').val(),
		        'd' : $('#return_search_date').val()
		    }, function(d,s,x) {
		        var a=JSON.parse(d), x="";
		        for(var i=0;i<a.length;i++) {
		            x+='<tr><td><button class="mdl-button mdl-js--button mdl-button--colored return_search_explore" id="' + a[i].iir_id + '"><i class="material-icons">exit_to_app</i></button></td><td>' + a[i].iir_id + ' - ' + a[i].ic_name + ' on ' + a[i].iir_date + '</td></tr>';
		        }
		        $('#return_search_table > tbody').empty();
		        $('#return_search_table > tbody').append(x);
		        
		    })
		})
	    
	    
	    $('#return_search_table').on('click','.return_search_explore', function(e) {
	        e.preventDefault();
	        var xc = $(this).prop('id');
	        $.post('<?php echo base_url().$type."/Transactions/get_inventory_return"; ?>', {
		        'i' : xc
		    }, function(d,s,x) {
		        var a=JSON.parse(d), x="";
		        edit_return_flag = true;
		        edit_return_index = xc;
		        product_name = []; product_qty=[];
		        for(var i=0;i<a.rec.length;i++) {
		            product_name.push(a.rec[i].ip_name);
		            product_qty.push(a.rec[i].ii_inward);
		        }
		        
		        $('#return_contact').val(a.main[0].ic_name);
		        $('#return_date').val(a.main[0].iir_date);
		        
		        update_list();
		        $('#myModal2').modal('hide');    
		        setTimeout(function(){
		            $('#myModal').modal('show');    
		        }, 1000);
		    })
	        
	    })
        $('#p_category').change(function(e) {
	        e.preventDefault();
	       
	        $.post('<?php echo base_url().$type."/Transactions/filter_inventory"; ?>', {
	            'search' : $(this).val()
	        }, function(d,s,x) {
	            console.log(d);
	            var a = JSON.parse(d), b="";
	            $('#products > tbody').empty();
	            for(var i=0;i<a.length;i++) {
	                b+='<tr id="' + a[i].id +'"><td>' + a[i].product + '</td><td>' + a[i].balance + '</td></tr>';
	            }
	            $('#products > tbody').append(b);
	        }, "text");
	    });
	    
	    $('#reconcile').click(function(e) {
		    e.preventDefault();
		   
		    $.post('<?php echo base_url().$type."/Transactions/reconcile_inventory/"; ?>', {}, function(d,s,x) {
		        var ert = {message: 'Stocks Reconciled.',timeout: 2000, }; 
                snackbarContainer.MaterialSnackbar.showSnackbar(ert);
                location.reload();
		    }) 
		});
		
	    $('#fixed-header-drawer-exp').keyup(function(e) {
            $.post('<?php echo base_url().$type."/Transactions/search_inventory"; ?>', {
                'keywords' : $(this).val()
            }, function(data, status, xhr) {
                var a = JSON.parse(data), s = ""; $('#products > tbody').empty();
                for (var i = 0; i < a.length; i++) {
                    s+="<tr id='" + a[i].id + "'><td>" + a[i].product + "</td><td>" + a[i].balance + "</td></tr>";
                }
                $('#products > tbody').append(s);
            }, "text");
        });

        $('#submit').click(function(e) {
            e.preventDefault();
            
            window.location = "<?php echo base_url().$type.'/Transactions/add_inventory'; ?>";
        });
        
		$('#products').on('click', 'tr', function(e) {
			e.preventDefault();
			window.location = "<?php echo base_url().$type.'/Transactions/inventory_details/'; ?>" + $(this).prop('id');
		})
		
		
		$('#print_order_list').click(function(e) {
		    e.preventDefault();
		    $.post("<?php echo base_url().$type.'/Transactions/get_order_list'; ?>", {}, function(d,s,x) {
		       var a = JSON.parse(d);
		        for(var i=0;i<a.length;i++) {
                    order_txt+='<tr><td>' + a[i].product + '</td><td>' + a[i].qty + '</td></tr>';
                }
                order_txt +='</tbody></table>';
                print_reciept();
            });
            
		});
		
		function print_reciept() {
    		var mywindow = window.open('', 'Order List', fullscreen=1);
    		mywindow.document.write(order_txt); mywindow.document.close(); mywindow.focus(); mywindow.print(); mywindow.close();
    	}
        
		
		$('#clear_order_list').click(function(e) {
		    e.preventDefault();
		    var order_txt = "";
		    $.post("<?php echo base_url().$type.'/Transactions/clear_order_list'; ?>", {}, function(d,s,x) {
		      var ert = {message: 'Order List Cleared.',timeout: 2000, }; 
                snackbarContainer.MaterialSnackbar.showSnackbar(ert);
                location.reload();
		    });
		});
	});
	
</script>
</html>