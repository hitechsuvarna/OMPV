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

    #products > tbody > tr > td > a {
        color: #ff0000;
        text-decoration: none;
    }
    
    .price {
        <?php $sess_data = $this->session->userdata(); echo 'display:'.$sess_data['price_display'].';'; ?>
    }
</style>
<main class="mdl-layout__content">
    <div class="mdl-grid">
        <table id="products">
            <thead>
                <tr>
                    <th>Product name</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php for($i=0;$i<count($products); $i++) {
                    echo '<tr id="'.$products[$i]['id'].'"><td>'.$products[$i]['name'].'</td><td class="price">'.$products[$i]['price'].'</td><td><input type="text" class="product_qty" id="txt'.$products[$i]['id'].'"></td><td><button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--primary qty_add" id="'.$products[$i]['id'].'">Add</button></td></tr>';
                } ?>
            </tbody>
        </table>
                
    </div>
    <div id="demo-snackbar-example" class="mdl-js-snackbar mdl-snackbar">
        <div class="mdl-snackbar__text"></div>
        <button class="mdl-snackbar__action" type="button"></button>
    </div>
</main>
</div>

</body>
<script type="text/javascript">
    var products_arr = [], qty_arr = [];
    
    <?php 
        $sess_data = $this->session->userdata();
        echo "$('.price').css('display','".$sess_data["price_display"]."');";
    ?>
            

    $(document).ready(function(e) {
        $('#fixed-header-drawer-exp').keyup(function(e) {
            $.post('<?php echo base_url().$type."/Products/search_products/".$ctid; ?>', {
                'name' : $(this).val()
            }, function(d,s,x) {
                var a = JSON.parse(d);
                load_list(a);
                <?php 
                    $sess_data = $this->session->userdata();
                    echo "$('.price').css('display','".$sess_data["price_display"]."');";
                ?>
                console.log(d);
            }, "text");
        });
        

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

        function load_list(a) {
            var cust_out="";
            $('#products > tbody').empty();
            for(var i =0; i<a.length; i++) {
                cust_out+= '<tr id="' + a[i].id + '"><td>' + a[i].name + '</td><td class="price">' + a[i].price + '</td><td><input type="text" class="product_qty" id="txt' + a[i].id + '"></td><td><button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--primary qty_add" id="' + a[i].id + '">Add</button></td></tr>';
            }
            $('#products > tbody').append(cust_out);
            
        }
        
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