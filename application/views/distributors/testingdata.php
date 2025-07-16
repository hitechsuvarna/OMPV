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
    
    .floater-text {
        position : relative;
    }

    .floater {
        box-shadow: 5px 5px 5px #ccc;
        position: absolute;
    }
    
    .floater-div {
        padding: 20px;
        width: 200px;
    }
    
</style>
<div class="floater-div">
    
    
</div>
<main class="mdl-layout__content">
	<div class="mdl-grid" id="dealer_selection">
        <div class="mdl-cell mdl-cell--4-col">
            <div class="mdl-card mdl-shadow--4dp">
                <div class="mdl-card__title">
                    <h2 class="mdl-card__title-text">2. Select Products to assign price</h2>
                </div>
                <div class="mdl-card__supporting-text">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label floater-text">
						<input type="text" id="vendors" class="mdl-textfield__input" value="">
						<label class="mdl-textfield__label" for="vendors">Vendor</label>
						<div class="floater">
                    		<table id="dealer">
                                <tbody>
                                    <?php for ($i=0; $i < count($dealers) ; $i++) { echo "<tr id='".$dealers[$i]->ic_id."'><td>".$dealers[$i]->ic_name."</td></tr>"; } ?>
                                </tbody>
                            </table>
                    	</div>
					</div>
					
                </div>
            </div>
        </div>
        
    </div>
</main>
</div>

</body>
<script type="text/javascript">
    var product_id = [];
    var product_arr = [];

    <?php for ($i=0; $i < count($products) ; $i++) { echo "product_arr.push({ 'id' : '".$products[$i]->ip_id."', 'name' : '".$products[$i]->ip_name."' });"; } ?>
    
    function test_elem(subject, input) {
        subject = subject.toLowerCase();
        input = input.toLowerCase()
        if(subject.indexOf(input) !== -1) {
            return true;
        } else {
            return false;
        }
    }
    $(document).ready(function() {
        
        $('#p_product').change(function(e) {
            e.preventDefault();

            product_id = $(this).val();
            

            // $('#selected_product').empty();
            // $('#selected_product').html('<img src="' + product_arr[product_id].image + '" style="width:100%;" />');

            // $.post('<?php #echo base_url().$type."/Products/get_dealer_pricing"; ?>', { 'ctid': $(this).val() }, function(d,s,x) {var a = JSON.parse(d);b="";
            //     $('#dealer > tbody').empty();
            //     for (var i = 0; i < a.dealers.length; i++) {
            //         b+="<tr id='" + a.dealers[i].ic_id + "'><td>" + a.dealers[i].ic_name + "<br><i>Price:";
            //         var p="N/A";
            //         for (var j = 0; j < a.dealer_pricing.length; j++) {
            //             if (a.dealers[i].ic_id == a.dealer_pricing[j].ipp_c_id) {
            //                 p=a.dealer_pricing[j].ipp_price;
            //                 break;
            //             }
            //         }
            //         b+= p + "</td></tr>";
            //     }
            //     $('#dealer > tbody').append(b); });
        });

        $('#vendors').keyup(function(e) {
            e.preventDefault();
            
            var t = $(this).val();
            if($(this).val() == "") {
                $('#dealer').css('display','none');
            } else {
                $('#dealer').css('display','block');
            }
            $('#dealer > tbody').empty();
            
            var a = product_arr.filter(function (product) { 
                // return product.name.includes(t)
                return test_elem(product.name, t);
            });
            console.log(a);
                    
            var b="";
            for (var i = 0; i < a.length; i++) {
                var flg = false;
                for(var j=0;j<product_id.length;j++) {
                    if(a[i].id == product_id[j]) {
                        flg = true;
                    }
                }
                var sty = "style='background-color:#0033cc; color:#fff;'";
                if(flg == false) {
                    sty = "style='background-color:#fff; color:#999;'";
                }
                
                b+="<tr " + sty + " id='" + a[i].id + "'><td>" + a[i].name;
                b+= "</td></tr>";
            }
            $('#dealer > tbody').append(b);
        })
        
        $('#dealer').on('click','tr', function(e) {
            e.preventDefault();

            var flg = false;
            var flg_index = 0
            for (var i = 0; i < product_id.length; i++) {
                if(product_id[i] == $(this).prop('id')) {
                    flg = true;
                    flg_index = i;
                    break;
                }
            }
            
            if(flg == false) {
                product_id.push($(this).prop('id'));
                $(this).css('background-color', '#0033cc');
                $(this).css('color','#fff');
            } else {
                product_id.splice(flg_index,1);
                $(this).css('background-color', '#fff');
                $(this).css('color','#999');
            }

            
        });

        $('#reset').click(function(e) {
            e.preventDefault();

            $('.dealer_list').css('background-color','#fff');
            $('.dealer_list').css('color','#999');

            $('#p_price').val('');

            $('#dealer_selection').css('display', 'none');
            $('#product_selection').css('display','flex');

            product_id = "";
            price = 0;
        });

        $('#fixed-header-drawer-exp').change(function(e) {
            $.post('<?php echo base_url().$type."/Products/search_products"; ?>', {
                'keywords' : txn_tags
            }, function(data, status, xhr) {
                var abc = JSON.parse(data);

                $('#products').empty();

                var cust = abc.customer;
                var cust_out = "";

                $('#products').append(cust_out);

                console.log(data);

                componentHandler.upgradeDom();
            }, "text");
        });

        $('#submit').click(function(e) {
            e.preventDefault();

            $.post('<?php echo base_url().$type."/Products/save_product_pricing"; ?>', {
                'ct_id' : product_id,
                'd_id' : dealers_id,
                'price' : $('#p_price').val(),
            }, function(data, status, xhr) {
                window.location = "<?php echo base_url().$type.'/Products/product_pricing'; ?>";
            }, "text");
            
        });


    });
</script>
</html>