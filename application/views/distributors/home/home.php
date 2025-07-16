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
        color: #0033cc !important;
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

    .pro {
        text-align: left !important;
    }

    /*header {
        background-color: #fff !important;
        color: #000 !important;
        box-shadow: 0px 0px 0px #fff !important;
    }

    .mdl-layout-title, .mdl-layout__drawer-button {
        color: #000 !important;
    }*/
    
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
</style>
<main class="mdl-layout__content">
	<div class="mdl-grid" id="transactions">
	    <div class="mdl-cell mdl-cell--4-col mdl-cell--4-col-phone outstanding" id="out_sale">
	        <div class="dhr_card">
	            <div class="dhr_card_title">
	                Today's Sale
	            </div>
	            <div class="dhr_card_content">
	                Rs.<?php echo ($sale_amount='')? $sale_amount : "0"; ?>/-
	            </div>
	        </div>
	    </div>
	    <div class="mdl-cell mdl-cell--4-col mdl-cell--4-col-phone outstanding" id="out_delivery">
	        <div class="dhr_card">
	            <div class="dhr_card_title">
	                Today's Delivery
	            </div>
	            <div class="dhr_card_content">
	                Rs.<?php echo ($delivery_amount !='')? $delivery_amount : "0"; ?>/-
	            </div>
	        </div>
	    </div>
	    <div class="mdl-cell mdl-cell--4-col mdl-cell--4-col-phone outstanding" id="out_order">
	        <div class="dhr_card">
	            <div class="dhr_card_title">
	                Outstanding
	            </div>
	            <div class="dhr_card_content">
	                Rs.<?php echo ($order_pending_amount[0]->a !='')? $order_pending_amount[0]->a : "0"; ?>/-
	            </div>
	        </div>
	    </div>
	    <div class="mdl-cell mdl-cell--12-col outstanding">
            <div class="dhr_card_title" style="margin:50px 0px 10px 0px;">Products that you need to order</div>
            <button class="mdl-button mdl-js--button mdl-button--colored home" category="0"><i class="material-icons">home</i></button>
            <button class="mdl-button mdl-js--button mdl-button--colored back" category="0"><i class="material-icons">arrow_back_ios</i></button>
            
            <div class="dhr_card" style="height:500px; overflow:auto;">
                <table class="purchase_table">
                    <?php for($i=0;$i<count($categories);$i++) {
                        echo '<tr id="'.$categories[$i]->ica_id.'" type="category"><td><i class="material-icons">category</i> '.$categories[$i]->ica_category_name.'</td></tr>';
                    } ?>
                </table>    
            </div>
        </div>
        
        <!--<div class="mdl-cell mdl-cell--3-col  mdl-cell--4-col-phone" id="pending_orders">-->
        <!--    <div class="mdl-card mdl-shadow--4dp">-->
        <!--        <div class="mdl-card__title">-->
        <!--            <h2 class="mdl-card__title-text">-->
                        
        <!--            </h2>-->
        <!--        </div>-->
        <!--        <div class="mdl-card__supporting-text">-->
        <!--            <table style="width: 100%; display: block; overflow: auto;" class="mdl-data-table mdl-js-data-table">-->
                    <?php 
                            #for ($i=0; $i < count($pending) ; $i++) {
                             #   echo '<tr><td style="width:100%; text-align:left;"><a href="'.base_url().$type.'/Transactions/order_details/'.$pending[$i]->it_id.'" style="color: #0033cc;">#'.$pending[$i]->it_txn_no.' - '.$pending[$i]->ic_name.'</a></td><td>'.$pending[$i]->it_date.'</td><td>Rs.'.$pending[$i]->it_amount.'/-</td> </tr>';
                        #}
                        ?>
        <!--            </table>-->
        <!--        </div>-->
        <!--    </div>-->
        <!--</div>-->
        
        
        <div class="mdl-cell mdl-cell--12-col" id="order_products" style="display:none;">
            <div class="mdl-card mdl-shadow--4dp">
                <div class="mdl-card__title">
                    <h2 class="mdl-card__title-text">
                        Products that you need to order urgently.
                    </h2>
                </div>
                <div class="mdl-card__supporting-text">
                    <table style="width: 100%; display: block; overflow: auto;" class="mdl-data-table mdl-js-data-table order_table">
                        <thead>
                            <tr>
                                <th class="pro">Product</th><th>Available</th><th>Order Qty</th>
                            </tr>    
                        </thead>
                        <tbody>
                            <?php 
                                // print_r($order_products);
                                #for ($i=0; $i < count($order_products); $i++) { 
                                    #echo '<tr><td style="width:100%;" class="pro"><input class="ord_name" id="nm'.$order_products[$i]['id'].'" type="text" style="border:1px solid #aaa; border-radius:5px; padding:10px; width:100%;" readonly value="'.$order_products[$i]['product'].'"></td><td>'.$order_products[$i]['balance'].'</td><td><input class="ord_qty" type="text" id="'.$order_products[$i]['id'].'" style="border:1px solid #aaa; padding:10px; border-radius:5px; text-align:center;"></tr>';
                                #}
                            ?>    
                        </tbody>
                    </table>
                    <button class="mdl-button mdl-js--button mdl-button--colored mdl-button--raised" id="print_list">Print List</button>
                </div>
            </div>
        </div>
    </div>
</main>
</div>

</body>
<script type="text/javascript">
    var date = new Date();
    var sel_cat=0;
    var order_list = [], order_txt= date + '<br><style>table { width:100%; border: 1px solid #aaa; border-radius: 5px; } table > tbody > tr > td { border-top:1px solid #aaa; } </style><table><thead><tr><th>Product</th><th>Qty</th></tr></thead><tbody>';
    $(document).ready( function() {
        
        $('.purchase_table').on('click','tr', function(e) {
            e.preventDefault();
            if($(this).attr('type') == "category") {
                load_order_list($(this).prop('id'));
            }
            
        }).on('click','.submit_value', function(e) {
            e.preventDefault();
            var x = false;
            for(var i=0;i<order_list.length;i++) {
                if($(this).prop('id') == order_list[i].id) {
                    order_list[i].qty = $(this).val();
                    x= true;
                    break;
                }
            }
            
            if(x== false) {
                order_list.push({ 'id' : $(this).prop('id'), 'qty' : $(this).val() });
            }
            
        })
    
        $('.back').click(function(e) {
            e.preventDefault();
            load_order_list($(this).attr('category'));
        })
        
        $('.home').click(function(e) {
            e.preventDefault();
            load_order_list($(this).attr('category'));
        })
        
        
    
        // $('#print_list').click(function(e) {
        //     e.preventDefault();
        //     $('.ord_qty').each(function() {
        //         if($(this).val() != "") {
        //             var x = '#nm' + $(this).prop('id');
        //             order_list.push({ 'id' : $(this).prop('id'), 'name' : $(x).val(), 'qty' : $(this).val()});    
        //         }
        //     });
        //     fill_list();
        //     print_reciept();
        // });
        
        function fill_list() {
            for(var i=0;i<order_list.length;i++) {
                order_txt+='<tr><td>' + order_list[i].name + '</td><td>' + order_list[i].qty + '</td></tr>';
            }
            order_txt +='</tbody></table>';
        }
        
        function print_reciept() {
    		var mywindow = window.open('', 'Order List', fullscreen=1);
    		mywindow.document.write(order_txt); mywindow.document.close(); mywindow.focus(); mywindow.print(); mywindow.close();
    	}
        
        function load_order_list(catid) {
            $.post('<?php echo base_url().$type."/Home/get_product_category_child/"; ?>' + catid, {}, function(d,s,x) {
                var a=JSON.parse(d), b="";
                console.log(a.parent);
                $('.purchase_table > tbody').empty()
                for(var i=0;i<a.category.length;i++) {
                    b+='<tr id="' + a.category[i].ica_id + '" type="category"><td><i class="material-icons">category</i> ' + a.category[i].ica_category_name + '</td></tr>';
                }
                
                for(var i=0;i<a.product.length;i++) {
                    if(a.product[i].ip_lower_limit > a.product[i].bal) {
                        b+='<tr id="' + a.product[i].ip_id + '" type="product"><td>' + a.product[i].ip_name + '</td><td>Limit: ' + a.product[i].ip_lower_limit + '</td><td>Available: ' + a.product[i].bal + '</td><td><label>Enter Qty</label><input class="ord_qty" type="text" id="' + a.product[i].ip_id + '" style="border:1px solid #aaa; padding:10px; border-radius:5px; text-align:center;"></td><td><button class="mdl-button mdl-js--button mdl-button--raised mdl-button--colored submit_value" id="' + a.product[i].ip_id + '"></tr>';
                    }
                }
                
                $('.purchase_table > tbody').append(b);
                $('.back').attr("category", a.parent);
            });
        }
        
        $('.block').click(function(e) {
            console.log($(this).prop('id'));
        });

        <?php 
            if($oid != $rid ) { echo "$('.outstanding').css('display','none');$('#order_products').css('display','none');$('#pending_orders').css('display','none'); "; } else {
            if (count($sale_amount) > 0) { echo "$('.outstanding').css('display','block');"; } else {  echo "$('#outstanding').css('display','none');"; }

            if (count($order_pending_amount) > 0) { echo "$('#pending_orders').css('display','block');"; } else {  echo "$('#pending_orders').css('display','none');"; } }

        ?>
    });
</script>
</html>