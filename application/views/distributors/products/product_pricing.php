<style>
	
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
    
    #dealer, #products {
        width: 100%;
        text-align: left;
        border: 0px solid #ccc;
        border-collapse: collapse;
        color:#000;
    }

    #dealer > thead > tr , #products > thead > tr {
        box-shadow: 0px 5px 5px #ccc;
    }

    #dealer > thead > tr > th , #products > thead > tr > th {
        padding: 10px;
    }

    #dealer > tbody > tr > td , #products > tbody > tr > td {
        padding: 15px;
    }

    #dealer > tbody > tr , #products > tbody > tr {
        border-bottom: 1px solid #ccc;
        display: block;
    }

    #dealer > tbody {
        max-height: 300px;
        overflow: auto;
        display: block;
    }
    
    .purchase_table {
		width: 100%;
        text-align: left;
        border: 0px solid #ccc;
        border-collapse: collapse;
        height:300px;
        overflow: auto;
        display:block;
        color:#000;
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
	<div class="mdl-grid" id="dealer_selection">
        <div class="mdl-cell mdl-cell--12-col">
            <div class="mdl-card mdl-shadow--4dp">
                <div class="mdl-card__title">
                    <h2 class="mdl-card__title-text">1. Select Category</h2>
                </div>
                <div class="mdl-card__supporting-text">
                    <div class="mdl-grid">
                        <div class="mdl-cell mdl-cell--6-col">
                            <div style="display:flex; align-items:center; padding: 0em 3em 0em 0em;">
                                <button class="mdl-button mdl-button--colored" id="cat_home" style="margin: auto 5px;"><i class="material-icons">home</i></button>
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
            						<input type="text" id="search_products_for_pricing" class="mdl-textfield__input" value="">
            						<label class="mdl-textfield__label" for="search_products_for_pricing">Search Product Code</label>
            					</div> 
                            </div>
                            <table class="purchase_table" id="cat" style="height:300px;overflow-y:auto;">
                                <?php for ($i=0; $i < count($category) ; $i++) { echo "<tr><td id='".$category[$i]->ica_id."' class='cat_td' type='category'>".$category[$i]->ica_category_name."</td><td><button class='mdl-button mdl-button--colored select_prod' type='cat' title='".$category[$i]->ica_category_name."' sel='false' id='".$category[$i]->ica_id."'><i class='material-icons'>arrow_right_alt</i></button></td></tr>"; } ?>
                            </table>
                        </div>
                        <div class="mdl-cell mdl-cell--6-col">
                            <div id="sel_product_chip">
                            
                            </div>  
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mdl-cell mdl-cell--6-col">
            <div class="mdl-card mdl-shadow--4dp">
                <div class="mdl-card__title">
                    <h2 class="mdl-card__title-text">2. Select Dealers to assign price</h2>
                </div>
                <div class="mdl-card__supporting-text">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="vendors" class="mdl-textfield__input" value="">
						<label class="mdl-textfield__label" for="vendors">Vendor</label>
					</div>
					<div id="sel_dealer_chip">
                        
                    </div>
                    <table id="dealer">
                        <tbody>
                            <?php for ($i=0; $i < count($dealers) ; $i++) { echo "<tr><td id='".$dealers[$i]->ic_id."' val='".$dealers[$i]->ic_name."'>".$dealers[$i]->ic_name."</td></tr>"; } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="mdl-cell mdl-cell--6-col">
            <div class="mdl-card mdl-shadow--4dp">
                <div class="mdl-card__title">
                    <h2 class="mdl-card__title-text">3. Enter price for dealers</h2>
                </div>
                <div class="mdl-card__supporting-text">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input type="text" id="p_price" name="p_price" class="mdl-textfield__input" value="">
                        <label class="mdl-textfield__label" for="p_price">Enter Product Price</label>
                    </div>
                </div>
            </div>
        </div>
        
        <button class="lower-button mdl-button mdl-button-done mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--accent" id="submit">
            <i class="material-icons">done</i>
        </button>
    </div>
    <div id="demo-snackbar-example" class="mdl-js-snackbar mdl-snackbar">
        <div class="mdl-snackbar__text"></div>
        <button class="mdl-snackbar__action" type="button"></button>
    </div>
</main>

</div>

</body>
<script type="text/javascript">
    var product_id = [];
    var dealers_id = [];
    var price = 0;
    var product_arr = [];

    var snackbarContainer = document.querySelector('#demo-snackbar-example');
    
    var dealer_arr = [];
    <?php for ($i=0; $i < count($dealers) ; $i++) { echo "dealer_arr.push({ 'id' : '".$dealers[$i]->ic_id."', 'name' : '".$dealers[$i]->ic_name."' });"; } ?>
    
    $(document).ready(function() {
        <?php for ($i=0; $i < count($products) ; $i++) { echo "product_arr[".$products[$i]->ip_id."] = ({id:'".$products[$i]->ip_id."', name:'".$products[$i]->ip_name."', image: '".base_url()."assets/uploads/".$oid."/".$products[$i]->ip_id."/".$products[$i]->ip_image."'});"; } ?>

        $('#cat').on('click','.cat_td', function(e) {
            // product_id=[];
            
            e.preventDefault();
            if($(this).attr('type')=="category") {
                $.post('<?php echo base_url().$type.'/Products/load_prod_cat/'; ?>' + $(this).prop('id'), {}, function(d,s,x) {
                $('#cat').empty();
                var a = JSON.parse(d);
                for(var i=0;i<a.category.length;i++) {
                    $('#cat').append('<tr><td id="' + a.category[i].ica_id + '" class="cat_td" type="category">' + a.category[i].ica_category_name + '</td><td><button class="mdl-button mdl-button--colored select_prod" title="' + a.category[i].ica_category_name + '" id="' + a.category[i].ica_id + '" type="cat" sel="false"><i class="material-icons">arrow_right_alt</i></button></td></tr>')
                }
                for(var i=0;i<a.product.length;i++) {
                    $('#cat').append('<tr><td id="' + a.product[i].ip_id + '" class="cat_td" type="product">' + a.product[i].ip_name + '</td><td><button class="mdl-button mdl-button--colored select_prod" id="' + a.product[i].ip_id + '" title="' + a.product[i].ip_name + '" type="prod" sel="false"><i class="material-icons">arrow_right_alt</i></button></td></tr>')
                }
            })   
            }
        });
        
        $('#cat_home').click(function(e) {
            // product_id=[];
            $.post('<?php echo base_url().$type.'/Products/load_prod_cat/0'; ?>', {}, function(d,s,x) {
                $('#cat').empty();
                var a = JSON.parse(d);
                for(var i=0;i<a.category.length;i++) {
                    $('#cat').append('<tr><td id="' + a.category[i].ica_id + '" class="cat_td" type="category">' + a.category[i].ica_category_name + '</td><td><button class="mdl-button mdl-button--colored select_prod" id="' + a.category[i].ica_id + '" title="' + a.category[i].ica_category_name + '" type="cat" sel="false"><i class="material-icons">arrow_right_alt</i></button></td></tr>')
                }
            })
        });
        
        $('#cat').on('click','.select_prod', function(e) {
            e.preventDefault();
            // $('.select_prod').removeClass('mdl-button--raised');
            // if($(this).attr('sel') == "false") {
            //     $(this).attr('sel','true');    
            // } else {
            //     $(this).attr('sel','false');
            // }
            // product_id=[];
            
            // $('.select_prod').each(function() {
            //     if($(this).attr('sel') == 'true') {
                    product_id.push({'p' : $(this).prop('id'), 't' : $(this).attr('type'), 'title' : $(this).attr('title') });
                    // $(this).addClass('mdl-button--raised');
            //     }
            // });
            load_product_chips();
        });
        
        $('#search_products_for_pricing').change(function(e) {
            $.post('<?php echo base_url().$type."/Products/load_prod_cat/0"; ?>', {
                'code' : $(this).val(),
            }, function(d,s,x) {
                var a=JSON.parse(d);
                $('#cat').empty();
                for(var i=0;i<a.product.length;i++) {
                    $('#cat').append('<tr><td id="' + a.product[i].ip_id + '" class="cat_td" type="product">' + a.product[i].ip_name + '</td><td><button class="mdl-button mdl-button--colored select_prod" id="' + a.product[i].ip_id + '" type="prod" title="' + a.product[i].ip_name + '" sel="false"><i class="material-icons">arrow_right_alt</i></button></td></tr>')
                }
            })
        })
        
        $('#vendors').keyup(function(e) {
            e.preventDefault();
            $.post('<?php echo base_url().$type."/Products/get_dealer"; ?>', {
                'd':$(this).val()
            }, function(d,s,x) {
                var a=JSON.parse(d), b="";
                for(var i=0;i<a.length;i++) {
                    b+='<tr><td id="' + a[i].ic_id + '" val="' + a[i].ic_name + '">' + a[i].ic_name + '</td></tr>';
                }
                $('#dealer > tbody').empty();
                $('#dealer > tbody').append(b);
            })
            
            
        })
        
        $('#dealer').on('click','td', function(e) {
            e.preventDefault();
            dealers_id.push({ 'id' : $(this).prop('id'), 'val' : $(this).attr('val') });
            load_dealer_chips();
            
            $('#vendors').val('');
            $('#vendors').focus();
        });
        
        function load_dealer_chips() {
            var b="";
            $('#sel_dealer_chip').empty();
            for(var i=0;i<dealers_id.length;i++) {
                b+='<span class="mdl-chip mdl-chip--deletable" id="' + dealers_id[i].id + '"><span class="mdl-chip__text">' + dealers_id[i].val + '</span><button type="button" class="mdl-chip__action del_dealer" idx="' + i + '" id="' + dealers_id[i] + '"><i class="material-icons">cancel</i></button></span>'
            }
            $('#sel_dealer_chip').append(b);
        }
        
        function load_product_chips() {
            var b="";
            $('#sel_product_chip').empty();
            for(var i=0;i<product_id.length;i++) {
                b+='<span class="mdl-chip mdl-chip--deletable" id="' + product_id[i].p + '">';
                if(product_id[i].t == 'cat') {
                    b+='<span class="mdl-chip__contact mdl-color--indigo mdl-color-text--white">C</span>';
                }
                b+='<span class="mdl-chip__text">' + product_id[i].title + '</span><button type="button" class="mdl-chip__action del_product" idx="' + i + '" id="' + product_id[i].p + '"><i class="material-icons">cancel</i></button></span>';
            }
            $('#sel_product_chip').append(b);
        }
        
        $('#sel_dealer_chip').on('click','.del_dealer', function(e) {
            e.preventDefault();
            dealers_id.splice($(this).attr('idx'), 1);
            load_dealer_chips();
        })

        $('#sel_product_chip').on('click','.del_product', function(e) {
            e.preventDefault();
            product_id.splice($(this).attr('idx'), 1);
            load_product_chips();
        })

        $('#reset').click(function(e) {
            e.preventDefault();

            $('.dealer_list').css('background-color','#fff');
            $('.dealer_list').css('color','#999');

            $('#p_price').val('');

            $('#dealer_selection').css('display', 'none');
            $('#product_selection').css('display','flex');

            product_id = "";
            dealers_id = [];
            price = 0;
        });

        // $('#fixed-header-drawer-exp').change(function(e) {
        //     $.post('<?php echo base_url().$type."/Products/search_products"; ?>', {
        //         'keywords' : txn_tags
        //     }, function(data, status, xhr) {
        //         var abc = JSON.parse(data);

        //         $('#products').empty();

        //         var cust = abc.customer;
        //         var cust_out = "";

        //         $('#products').append(cust_out);

        //         console.log(data);

        //         componentHandler.upgradeDom();
        //     }, "text");
        // });

        $('#submit').click(function(e) {
            e.preventDefault();
            var ert = {message: 'Please wait.',timeout: 2000, }; 
            snackbarContainer.MaterialSnackbar.showSnackbar(ert);
                
            $.post('<?php echo base_url().$type."/Products/save_product_pricing"; ?>', {
                'ct_id' : product_id,
                'd_id' : dealers_id,
                'price' : $('#p_price').val(),
            }, function(data, status, xhr) {
                ert = {message: 'Pricing Updated.',timeout: 2000, }; 
                snackbarContainer.MaterialSnackbar.showSnackbar(ert);
                
                window.location = "<?php echo base_url().$type.'/Products/product_pricing'; ?>";
            }, "text");
            
        });


    });
</script>
</html>