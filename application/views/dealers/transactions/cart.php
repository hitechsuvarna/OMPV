<style type="text/css">
	.cart_table {
		width: 100%;
		border: none;
		padding: 10px;
		border: 1px solid #999;
	}

	.cart_table > tr {
		width: 100%;
	}

	.cart_table > tr > td {
		border-bottom: 1px solid #999;
		border-top: 1px solid #999;
		display: flex;
		padding: 10px;
		width: 100%;
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

    #no_cart_view {
    	text-align: center;
    	display: none;
    }
</style>

<main class="mdl-layout__content">
	<div id="cart_view">
		<div class="mdl-grid">
			<table style="margin:10px;">
				<tr>
					<td style="width: 100%;">
						<h4>Total Amount: <span id="total_amount"></span></h4>
					</td>
					<td>
						<button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" id="recalculate"><span class="material-icons">refresh</span>REFRESH</button>
					</td>
				</tr>
			</table>
		</div>
		<div class="mdl-grid" >
			<h5 style="font-weight: bold; width: 100%; margin: 10px;">Order Summary</h5>
			<table class="cart_table">
			</table>
			
			<button class="lower-button mdl-button mdl-button-done mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--accent" id="submit">
				<i class="material-icons">done</i>
			</button>
		</div>
	</div>
	<div id="no_cart_view">
		<h3>No Items in the cart.</h3><h4>You can browse the products <br> OR <br> Check your orders.</h4>
	</div>
</div>
</div>
</body>
<script>
	var product_arr = [];
	var total = 0;
	
	
	$(document).ready(function() {
		<?php
			for ($i=0; $i < count($products) ; $i++) { 
				echo 'product_arr.push({"id" : '.$products[$i]['ip_id'].', "name" : "'.$products[$i]['ip_name'].'", "description" : "'.$products[$i]['ip_description'].'", "rate" : '.$products[$i]['ipp_price'].', "qty" : '.$products[$i]['iuc_u_qty'].', "image" : "'.base_url().'assets/uploads/'.$oid.'/'.$products[$i]['ip_id'].'/'.$products[$i]['ip_image'].'" });';
			}
		 ?>
		
		update_info();

		function update_info() {
			$('.cart_table').empty();
			$('#total_amount').empty();
			var a = "";
			total=0;
			if(product_arr.length > 0) {
				$('#cart_view').css('display','block');
				$('#no_cart_view').css('display','none');
				for (var i = 0; i < product_arr.length; i++) {
					total += product_arr[i].qty * product_arr[i].rate;
					a+='<tr> <td class="cart_table_details"> <h5>' + product_arr[i].name + '</h5> <i>' + product_arr[i].description + '</i><br><h4 class="price"> Rate: <b>Rs.' + product_arr[i].rate + '/-</b></h4><br> Qty: <input type="text" class="product_qty" value="' + product_arr[i].qty + '"><br> </td> <td class="cart_table_image"> <div style="background-image: url(\'' + product_arr[i].image + '\');width:100px;height: 100px; background-size:cover;"></div> </td><td><button class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored delete" id="' + i + '"> <i class="material-icons">delete</i> </button></td> </tr>';
					
				}
				<?php 
                    $sess_data = $this->session->userdata();
                    echo "$('.price').css('display','".$sess_data["price_display"]."');";
                ?>
        
				$('.cart_table').append(a);
				$('#total_amount').append(total);
			} else {
				$('#cart_view').css('display','none');
				$('#no_cart_view').css('display','block');
			}

			
		}

		function recalculate() {
			$('.product_qty').each(function(i) {
				product_arr[i].qty = $(this).val();
			});
		}

		function remove_item(id) {
			product_arr.splice(id, 1);
		}

		$('.cart_table').on('click','.delete', function(e) { 
			e.preventDefault();
			console.log($(this).prop('id'));
			remove_item($(this).prop('id'));
			// recalculate();
			update_info();
		});

		$('#recalculate').click(function(e) {
			e.preventDefault();
			recalculate();			
			update_info();
		});

		$('#submit').click(function(e) {
		    $(this).attr('disabled','disabled');
			recalculate();
			update_info();
			e.preventDefault();
			$.post('<?php echo base_url().$type."/Transactions/confirm_cart"; ?>', { 'total' : total, 'products' : product_arr }, function(d,s,x) { window.location = "<?php echo base_url().$type.'/Transactions/confirm/'; ?>" + d; }, "text");
		});
		
		<?php 
            $sess_data = $this->session->userdata();
            echo "$('.price').css('display','".$sess_data["price_display"]."');";
        ?>
        

	});
</script>
</html>