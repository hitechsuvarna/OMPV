<style>
	
    a {
        color: #fff;
        text-decoration: none;
    }

    a:hover {
        color: #fff;
        text-decoration: none;
    }

    table > tr > td > a {
        color: #ff0000 !important;
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
    
    .product_qty {
        border: 1px solid #999;
        border-radius: 3px;
        padding: 10px;
        text-align: center;
        margin-bottom: 10px;
        width: 70px;
    }

    .pending_orders_row {
        padding: 20px;
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
        <div class="mdl-cell mdl-cell--12-col" id="new_usr">
            <h4 style="border-bottom: 1px solid #aaa; width: 10em;">Welcome to OMPV Mobile Application.</h4>
        </div>
        <div class="mdl-cell mdl-cell--12-col" id="old_usr">
            <h4 style="border-bottom: 1px solid #aaa; width: 10em;">Your Updates</h4>
        </div>
    </div>
    <div class="mdl-grid" id="transactions">
        <div class="mdl-cell mdl-cell--4-col" id="outstanding">
            <div style="border-radius: 10px;box-shadow: 0px 1px 5px #666;padding: 20px;text-align: center;background-color: #ffc107;">
                <h4>
                    Total Amount Outstanding:<br>
                    <h2>Rs.<?php print_r($outstanding); ?>/-</h2><br>
                </h4>
            </div>
        </div>
        <div class="mdl-cell mdl-cell--8-col" id="pending_orders">
            <div style="    border-radius: 10px; box-shadow: 0px 1px 5px #666;padding: 20px;">
                <h4>Pending Orders</h4>
                <table style="width: 100%;" class="purchase_table">
                    <?php 
                        for ($i=0; $i < count($pending) ; $i++) { 
                            echo '<tr><td><a href="'.base_url().$type.'/Transactions/order_details/'.$pending[$i]->it_id.'" style="color: #ff0000;">#'.$pending[$i]->it_txn_no.'</a></td><td>'.$pending[$i]->it_date.'</td><td>Rs.'.$pending[$i]->it_amount.'/-</td> </tr>';
                        }
                    ?>
                </table>
            </div>
        </div>
    </div>
    <div class="mdl-grid">
        <h4 style="border-bottom: 1px solid #aaa; width: 10em;">New Arrivals</h4>
    </div>
    <div class="mdl-grid">
        <?php for($i=0;$i<count($new_arrival);$i++) {
            echo '<div class="mdl-cell mdl-cell--4-col category_click" id="'.$new_arrival[$i]->ica_id.'"><div class="mdl-card mdl-shadow--4dp"><div class="mdl-card__title mdl-card--expand" style="background:  linear-gradient(rgba(20,20,20,.5), rgba(20,20,20, .5))';
            if($new_arrival[$i]->ica_img != null || $new_arrival[$i]->ica_img != "") {
                echo ', url(\''.base_url().'assets/uploads/'.$oid.'/c/'.$new_arrival[$i]->ica_id.'/'.$new_arrival[$i]->ica_img.'\')';     
            }
            echo '; background-size:cover;">';
            echo '<h2 class="mdl-card__title-text">'.$new_arrival[$i]->ip_name.'</h2>';
            echo '</div></div></div>';
        } ?>
    </div>
    <div class="mdl-grid">
        <div class="mdl-cell mdl-cell--12-col">
            <button class="mdl-button mdl-js--button mdl-button--colored browse_more" style="width:100%;">Explore Products</button>
        </div>
    </div>
    
	
	<div class="mdl-grid" id="nothing">
	    <h1>Please contact Admin for pricing and products</h1>
	</div>
    <div id="demo-snackbar-example" class="mdl-js-snackbar mdl-snackbar">
        <div class="mdl-snackbar__text"></div>
        <button class="mdl-snackbar__action" type="button"></button>
    </div>
</main>
</div>

</body>
<script type="text/javascript">
    var products_arr = [], qty_arr = [], noth = false;
    $(document).ready( function() {
        <?php 
        
            if(count($sale_amount) > 0) {
                echo '$("#old_usr").css("display","block"); $("#new_usr").css("display","none");';
            } else {
                echo '$("#old_usr").css("display","none"); $("#new_usr").css("display","block");';
            }
        
            $sess_data = $this->session->userdata();
            echo "$('.price').css('display','".$sess_data["price_display"]."');";
            // if (count($sale_amount[0]->amt) > 0) { echo "$('#outstanding').css('display','block'); noth=false;"; } else {  echo "$('#outstanding').css('display','none'); noth=true;"; }
            // if (count($delivery_amount[0]->amt) > 0) { echo "$('#outstanding_del').css('display','block'); noth=false;"; } else {  echo "$('#outstanding_del').css('display','none'); noth=true;"; }
            if (count($pending) > 0) { echo "$('#pending_orders').css('display','block');  noth=false;"; } else {  echo "$('#pending_orders').css('display','none'); noth=true;"; } 
            // if (count($product_list) > 0) { echo "$('#new_arrival').css('display','block'); noth=false;"; } else {  echo "$('#new_arrival').css('display','none'); noth=true;"; } 
       
       ?>
       
       $('#outstanding').click(function(e) {
            e.preventDefault();
            
            window.location = "<?php echo base_url().$type.'/Transactions/orders'; ?>"
       });
       
    //   if(noth==true) {
    //       $('#nothing').css('display','block');
    //   } else {
          $('#nothing').css('display','none');
    //   }
       
        $('#products').on('click','.qty_add', function(e) {
            e.preventDefault();
            var a = "#txt" + $(this).prop('id');
            var f=false, d=0;
            for (var i = 0; i < products_arr.length; i++) {
                if ($(this).prop('id') == products_arr[i]) {
                    f=true;
                    d=i;
                    break;
                }
            }
            var snackbarContainer = document.querySelector('#demo-snackbar-example');
            if(f==false) { add_to_list($(this).prop('id') , $(a).val()); } else { edit_list(d, $(this).prop('id'), $(a).val()); }

            $.post('<?php echo base_url().$type."/Products/update_cart"; ?>', { 'p' : products_arr, 'q' : qty_arr }, function(d,s,x) { var ert = {message: 'Cart Updated.',timeout: 2000, }; snackbarContainer.MaterialSnackbar.showSnackbar(ert); }, "text");

             
            
        });
        
        $('div').on('click','.category_click', function(e) {
            window.location = "<?php echo base_url().$type.'/Products/load_category/'; ?>" + $(this).prop('id');
        })
        
        $('.browse_more').click(function(e) {
            e.preventDefault();
            
            window.location = "<?php echo base_url().$type.'/Products'; ?>";
        });

        function add_to_list(pid, qty) {
            products_arr.push(pid);
            qty_arr.push(qty);
        }

        function edit_list(id, pid, qty) {
            products_arr[id] = pid;
            qty_arr[id] = qty;
        }
    });
</script>
</html>