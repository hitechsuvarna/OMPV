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
	
</style>
<main class="mdl-layout__content" style="display:none;" id="page_content">
    <div class="mdl-grid">
        <div class="mdl-cell mdl-cell--12-col" style="border-radius:5px; padding:15px; box-shadow:0px 5px 10px #ccc;" id="search_products">
            <input type="text" id="search_records" style="font-size:1.3em; font-weight:bold; width:80%; outline:none; border:0px; border-bottom:1px solid #eee;" placeholder="Search Products" >
            <button class="mdl-button mdl-button--raised mdl-button--colored mdl-button--icon" id="search_records_button"><i class="material-icons">search</i></button>
        </div>
        <div class="mdl-cell mdl-cell--12-col" id="tot_disp">
            <span class="mdl-chip">
                <span class="mdl-chip__text" id="tot_products">Total number of Products: <b>19</b></span>
            </span>
            <span class="mdl-chip">
                <span class="mdl-chip__text" id="tot_qty">Total Qty in Cart: <b>34</b></span>
            </span>
        </div>
    </div>
    <div class="mdl-grid" id="proceed_disp">
        <button class="mdl-button mdl-button--raised mdl-button--colored" style="margin:0px auto;" id="proceed_order"></button>
    </div>
    <div class="mdl-grid" id="product_grid">
    </div>
    <div class="mdl-grid" id="empty_disp" style="display:none;">
        <div class="mdl-cell mdl-cell--12-col" style="text-align:center;">
            <i class="material-icons" style="font-size:15em;color:#ccc;">add_shopping_cart</i>
            <h3 style="color:#ccc;">Empty Cart.<br>Search Products Now</h3>
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
        $('.child_card').click(function(e) {
            e.preventDefault();
            // window.location = '<?php echo base_url().$type."/Home/load_category/"; ?>' + $(this).prop('id');
        });
        
        $('#search_records').keydown(function(e) {
            if(e.keyCode == 13) {
                $.post('<?php echo base_url().$type."/Home/search_products/"; ?>', {
                    'k' : $(this).val()
                }, function(d,s,x) {
                    window.location = '<?php echo base_url().$type."/Home/load_category/"; ?>';
                })
            }
        })
        
        $('#search_records_button').click(function(e) {
            e.preventDefault();
            
            $.post('<?php echo base_url().$type."/Home/search_products/"; ?>', {
                'k' : $('#search_records').val()
            }, function(d,s,x) {
                window.location = '<?php echo base_url().$type."/Home/load_category/"; ?>';
            })
        });
        
        $('#product_grid').on('click','.add_cart', function(e) {
            e.preventDefault();
            var x1=$(this).prop('id');
            $.post('<?php echo base_url().$type."/Home/product_to_cart/add"; ?>', {
                'p' : x1,
            }, function(d,s,x) {
                update_badge_value(x1, d);
            })
        });
        
        $('#product_grid').on('click','.sub_cart', function(e) {
            e.preventDefault();
            var x1=$(this).prop('id');
            $.post('<?php echo base_url().$type."/Home/product_to_cart/remove"; ?>', {
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
        
        load_cart();
        
        $('#disp_cart').click(function(e) {
            e.preventDefault();
            window.location = '<?php echo base_url().$type."/Home/load_cart"; ?>';
        })
        
        $('#proceed_order').click(function(e) {
            e.preventDefault();
            $(this).attr('disable','disabled');
            $.post('<?php echo base_url().$type."/Transactions/confirm_cart"; ?>', {
                'total' : total,
                'products' : product_arr
            }, function(d,s,x) { 
                var data = {
                        message: 'Order Placed Successfully',
                        timeout: 2000,
                    };
                    snackbarContainer.MaterialSnackbar.showSnackbar(data);
                setTimeout(function() {
                    window.location = '<?php echo base_url().$type."/Home/index/".$oid; ?>';
                }, 2000);
            }, "text");
        })
        
    })
    
    function load_cart() {
        $.post('<?php echo base_url().$type."/Home/load_cart_values/all"; ?>', {}, function(d,s,x) {
            if(d!="") {
                var a=JSON.parse(d), b="", calc_total=0;
                $('#empty_disp').css('display','none');
                $('#disp_cart').attr('data-badge', a.item_count);
                $('#tot_products').empty().append('Total number of Products: <b>' + a.item_count + '</b>');
                $('#tot_qty').empty().append('Total Qty in Cart: <b>' + a.qty_count + '</b>');
                
                $('#product_grid').empty(); 
                for(var i=0;i<a.cart.length;i++) {
                    b+= '<div class="mdl-cell mdl-cell--12-col"><div class="mdl-card mdl-shadow--2dp"><div class="mdl-card__supporting-text" style="text-align:left;"><img src="' + a.cart[i].url + '" style="width:100%;"><h5>' + a.cart[i].name + '</h5><p>' + a.cart[i].price + '</p><div style="display:flex;"><button class="mdl-button mdl-button--colored mdl-button--icon sub_cart" id="'+ a.cart[i].id + '"><i class="material-icons">remove</i></button><div class="material-icons mdl-badge mdl-badge--overlap count_cart" data-badge="' + a.cart[i].qty + '" id="' + a.cart[i].id + '">shopping_cart</div><button class="mdl-button mdl-button--colored mdl-button--icon add_cart" id="' + a.cart[i].id + '"><i class="material-icons">add</i></button></div></div></div></div>';
                    var rt=0;
                    if(a.cart[i].price!="") {
                        rt=a.cart[i].price;
                        calc_total += parseInt(a.cart[i].price);
                    }
                    product_arr.push({
                        "id" : parseInt(a.cart[i].id),
                        "name" : a.cart[i].name, 
                        "description" : a.cart[i].description,
                        "rate" : rt,
                        "qty" : parseInt(a.cart[i].qty),
                        "image" : a.cart[i].url
                    });
                }
                $('#product_grid').append(b);
                
                if(calc_total > 0) {
                    total = calc_total;
                    $('#proceed_order').empty().append('<i class="material-icons">done</i> Confirm Order of Rs.' + calc_total + '/-');    
                } else {
                    $('#proceed_order').empty().append('<i class="material-icons">done</i> Confirm Order of Total - N/A');    
                }
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
        
        load_cart();
        
    }
</script>
