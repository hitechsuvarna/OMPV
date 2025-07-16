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
    <div class="mdl-grid">
        <div class="mdl-cell mdl-cell--12-col" id="tot_disp">
            <span class="mdl-chip">
                <span class="mdl-chip__text" id="tot_products">Total number of Products: <b>19</b></span>
            </span>
            <span class="mdl-chip">
                <span class="mdl-chip__text" id="tot_qty">Total Qty in Cart: <b>34</b></span>
            </span>
            <span class="mdl-chip">
                <span class="mdl-chip__text" id="ord_type"></span>
            </span>
            <table class="purchase_table" id="account_table">
                <tbody></tbody>
            </table>
        </div>
    </div>
    <div class="mdl-grid" id="product_grid">
    </div>
    <div class="mdl-grid" id="empty_disp" style="display:none;">
        <div class="mdl-cell mdl-cell--12-col" style="text-align:center;">
            <h3 style="color:#ccc;">No items found</h3>
        </div>
    </div>
</main>
<div id="demo-snackbar-example" class="mdl-js-snackbar mdl-snackbar">
  <div class="mdl-snackbar__text"></div>
  <button class="mdl-snackbar__action" type="button"></button>
</div>

<script>
    var total=0, product_arr=[];
    $(document).ready(function() {
        var snackbarContainer = document.querySelector('#demo-snackbar-example');
        
        $('#product_grid').on('click','.add_cart', function(e) {
            e.preventDefault();
            var x1=$(this).prop('id');
            $.post('<?php echo base_url().$type."/Home/product_to_order/add/".$id; ?>', {
                'p' : x1,
            }, function(d,s,x) {
                update_badge_value(x1, d);
            })
        });
        
        $('#product_grid').on('click','.sub_cart', function(e) {
            e.preventDefault();
            var x1=$(this).prop('id');
            $.post('<?php echo base_url().$type."/Home/product_to_order/remove/".$id; ?>', {
                'p' : x1,
            }, function(d,s,x) {
                if(d == "-1") {
                    d="0";
                    var data = {
                        message: 'Qty cannot be less than 0',
                        timeout: 2000,
                    };
                    snackbarContainer.MaterialSnackbar.showSnackbar(data);
                    update_badge_value(x1, d);
                } else {
                    update_badge_value(x1, d);
                }
            })
        });
        
        load_order();
        
        $('#disp_cart').click(function(e) {
            e.preventDefault();
            window.location = '<?php echo base_url().$type."/Home/load_cart"; ?>';
        })
        
    })
    
    function load_order() {
        $.post('<?php echo base_url().$type."/Home/load_order_values/".$id; ?>', {}, function(d,s,x) {
            if(d!="") {
                var a=JSON.parse(d), b="", calc_total=0;
                $('#empty_disp').css('display','none');
                $('#disp_cart').attr('data-badge', a.item_count);
                $('#tot_products').empty().append('Total number of Products: <b>' + a.item_count + '</b>');
                $('#tot_qty').empty().append('Total Qty in Cart: <b>' + a.qty_count + '</b>');
                $('#ord_type').empty().append('Order Type: <b>' + a.order[0].it_type + '</b>');
                
                $('#product_grid').empty(); 
                for(var i=0;i<a.order_details.length;i++) {
                    if(a.order[0].it_type == 'Delivery') {
                        b+= '<div class="mdl-cell mdl-cell--12-col"><div class="mdl-card mdl-shadow--2dp"><div class="mdl-card__supporting-text" style="text-align:left;"><img src="' + a.order_details[i].url + '" style="width:100%;"><h5>' + a.order_details[i].name + '</h5><p>' + a.order_details[i].price + '</p><div style="display:flex;"><div class="material-icons mdl-badge mdl-badge--overlap count_cart" data-badge="' + a.order_details[i].qty + '" id="' + a.order_details[i].id + '">shopping_cart</div></div></div></div></div>';    
                    } else {
                        b+= '<div class="mdl-cell mdl-cell--12-col"><div class="mdl-card mdl-shadow--2dp"><div class="mdl-card__supporting-text" style="text-align:left;"><img src="' + a.order_details[i].url + '" style="width:100%;"><h5>' + a.order_details[i].name + '</h5><p>' + a.order_details[i].price + '</p><div style="display:flex;"><button class="mdl-button mdl-button--colored mdl-button--icon sub_cart" id="'+ a.order_details[i].id + '"><i class="material-icons">remove</i></button><div class="material-icons mdl-badge mdl-badge--overlap count_cart" data-badge="' + a.order_details[i].qty + '" id="' + a.order_details[i].id + '">shopping_cart</div><button class="mdl-button mdl-button--colored mdl-button--icon add_cart" id="' + a.order_details[i].id + '"><i class="material-icons">add</i></button></div></div></div></div>';
                    }
                    
                    var rt=0;
                    if(a.order_details[i].price!="") {
                        rt=a.order_details[i].price;
                        calc_total += (parseInt(a.order_details[i].price) * parseInt(a.order_details[i].qty));
                    }
                    product_arr.push({
                        "id" : parseInt(a.order_details[i].id),
                        "name" : a.order_details[i].name, 
                        "description" : a.order_details[i].description,
                        "rate" : rt,
                        "qty" : parseInt(a.order_details[i].qty),
                        "image" : a.order_details[i].url
                    });
                }
                $('#product_grid').append(b);
                var c="", g_tot=calc_total;
                c+='<tr><td><b>Sub Total</b></td><td style="text-align:right;"><b>Rs.' + calc_total + '/-</b></td></tr>';
                
                for(var i=0;i<a.payment.length;i++) {
                    g_tot+=parseInt(a.payment[i].amount);
                    c+='<tr><td>Payment: ' + a.payment[i].narration + ' ' + a.payment[i].date + '</td><td style="text-align:right;">Rs.' + a.payment[i].amount + '/-</td></tr>';
                }
                c+='<tr><td><b>Total</b></td><td style="text-align:right;"><b>Rs.' + g_tot + '/-</b></td></tr>'
                $('#account_table > tbody').empty().append(c);
            } else {
                $('#tot_disp').css('display','none');
                $('#proceed_disp').css('display','none');
                $('#product_grid').css('display','none');
                $('#empty_disp').css('display','block');
                
            }
                
        })
        
    }
    
    function update_badge_value(pid, d) {
        $('.count_cart').each(function(index, value) {
            if($(this).prop('id') == pid) {
                $(this).attr('data-badge', d);
            }
        })
        
        load_order();
        
    }
</script>
