<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"></script>

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
        <div class="mdl-cell mdl-cell--12-col" id="category_photos">
            <div id="myCarousel" class="carousel slide" data-ride="carousel">
                <!-- Indicators -->
                <ol class="carousel-indicators">
                    <?php 
                        if(isset($current_category)) {
                            if($current_category[0]->ica_img != "" || $current_category[0]->ica_img != NULL) {
                                echo '<li data-target="#myCarousel" data-slide-to="0" class="active"></li>';
                            }
                            if($current_category[0]->ica_img2 != "" || $current_category[0]->ica_img2 != NULL) {
                                echo '<li data-target="#myCarousel" data-slide-to="1" class="active"></li>';
                            }
                            if($current_category[0]->ica_img3 != "" || $current_category[0]->ica_img3 != NULL) {
                                echo '<li data-target="#myCarousel" data-slide-to="2" class="active"></li>';
                            }
                            if($current_category[0]->ica_img4 != "" || $current_category[0]->ica_img4 != NULL) {
                                echo '<li data-target="#myCarousel" data-slide-to="3" class="active"></li>';
                            }
                            if($current_category[0]->ica_img5 != "" || $current_category[0]->ica_img5 != NULL) {
                                echo '<li data-target="#myCarousel" data-slide-to="4" class="active"></li>';
                            }
                            if($current_category[0]->ica_img6 != "" || $current_category[0]->ica_img6 != NULL) {
                                echo '<li data-target="#myCarousel" data-slide-to="5" class="active"></li>';
                            }
                        }
                    ?>
                </ol>
                <!-- Wrapper for slides -->
                <div class="carousel-inner">
                    <?php 
                        if(isset($current_category)) {
                            if($current_category[0]->ica_img != "" || $current_category[0]->ica_img != NULL) {
                                echo '<div class="carousel-item active" style="text-align:center;"><img src="'.base_url().'assets/uploads/'.$oid.'/c/'.$current_category[0]->ica_id.'/'.$current_category[0]->ica_img.'" style="height:300px; width:100%;"></div>';
                            }
                            if($current_category[0]->ica_img2 != "" || $current_category[0]->ica_img2 != NULL) {
                                echo '<div class="carousel-item" style="text-align:center;"><img src="'.base_url().'assets/uploads/'.$oid.'/c/'.$current_category[0]->ica_id.'/'.$current_category[0]->ica_img2.'" style="height:300px;width:100%;"></div>';
                            }
                            if($current_category[0]->ica_img3 != "" || $current_category[0]->ica_img3 != NULL) {
                                echo '<div class="carousel-item" style="text-align:center;"><img src="'.base_url().'assets/uploads/'.$oid.'/c/'.$current_category[0]->ica_id.'/'.$current_category[0]->ica_img3.'" style="height:300px;width:100%;"></div>';
                            }
                            if($current_category[0]->ica_img4 != "" || $current_category[0]->ica_img4 != NULL) {
                                echo '<div class="carousel-item" style="text-align:center;"><img src="'.base_url().'assets/uploads/'.$oid.'/c/'.$current_category[0]->ica_id.'/'.$current_category[0]->ica_img4.'" style="height:300px;width:100%;"></div>';
                            }
                            if($current_category[0]->ica_img5 != "" || $current_category[0]->ica_img5 != NULL) {
                                echo '<div class="carousel-item" style="text-align:center;"><img src="'.base_url().'assets/uploads/'.$oid.'/c/'.$current_category[0]->ica_id.'/'.$current_category[0]->ica_img5.'" style="height:300px;width:100%;"></div>';
                            }
                            if($current_category[0]->ica_img6 != "" || $current_category[0]->ica_img6 != NULL) {
                                echo '<div class="carousel-item" style="text-align:center;"><img src="'.base_url().'assets/uploads/'.$oid.'/c/'.$current_category[0]->ica_id.'/'.$current_category[0]->ica_img6.'" style="height:300px;width:100%;"></div>';
                            }
                            
                            
                        }
                    ?>
                    
                </div>
            </div>
        </div>
    </div>
    <div class="mdl-grid">
        <?php $b=""; for($i=0;$i<count($category); $i++) {
            $b.='<div class="mdl-cell mdl-cell--2-col open" id="'.$category[$i]->ica_id.'"><div class="mdl-card mdl-shadow--2dp"><div class="mdl-card__title mdl-card--expand" style="background:linear-gradient(rgba(20,20,20,.3), rgba(20,20,20, 1)), url(\''.base_url().'assets/uploads/'.$oid.'/c/'.$category[$i]->ica_id.'/'.$category[$i]->ica_img.'\'); background-size:contain; "><div class="mdl-card__title-text">'.$category[$i]->ica_category_name.'</div></div></div></div>';
            } echo $b;
        ?>
    </div>
    <div class="mdl-grid" style="<?php if(isset($products)) { if(count($products) > 0) { echo 'display: block;'; } else { echo 'display: none'; } } else { echo 'display: none'; } ?>">
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
        <button class="mdl-snackbar__action" type="button" id="cart_updated">View Cart</button>
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
        $('div').on('click','.open', function(e) {
            e.preventDefault();
            window.location = "<?php echo base_url().$type.'/Products/load_category/'; ?>" + $(this).prop('id');
        });

        $('#fixed-header-drawer-exp').keyup(function(e) {
            $.post('<?php echo base_url().$type."/Products/search_products/"; ?>', {
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
        
        // $('#p_category').change(function(e) {
        //   e.preventDefault();
        //   $.post('<?php #echo base_url().$type."/Products/search_products/".$ctid; ?>', {
        //       'category' : $(this).val()
        //   }, function(d,s,x) {
        //       var a = JSON.parse(d);
        //       load_list(a);
        //         <?php 
        //             $sess_data = $this->session->userdata();
        //             echo "$('.price').css('display','".$sess_data["price_display"]."');";
        //         ?>
        //   });
        // });
        

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

            $.post('<?php echo base_url().$type."/Products/update_cart"; ?>', { 'p' : products_arr, 'q' : qty_arr }, function(d,s,x) { var ert = {message: 'Cart Updated.',actionHandler: function(event) { window.location = "<?php echo base_url().$type.'/Transactions/cart'; ?>"}, actionText: 'View Cart',timeout: 2000, }; snackbarContainer.MaterialSnackbar.showSnackbar(ert); }, "text");

             
            
        });

        function load_list(a) {
            var cust_out="";
            $('#products').empty();
            for(var i =0; i<a.length; i++) {
                // cust_out+= '<div class="mdl-cell mdl-cell--4-col"> <div class="mdl-card mdl-shadow--4dp"> <div class="mdl-card__title" style="background:  linear-gradient(rgba(20,20,20,.5), rgba(20,20,20, .5)), url(\'' + a[i].url +'\');height: 300px; background-size:cover;"><h2 class="mdl-card__title-text">' + a[i].name + '</h2> </div> <div class="mdl-card__supporting-text" style="text-align: left;"> <i>' + a[i].description + '</i> <hr> <table> <tbody> <tr> <td style="width:100%;"> <h4 class="price">' + a[i].price + '</h4> </td> <td style="text-align:right;"> <div> <input type="text" class="product_qty" id="txt' + a[i].id + '"> </div> <div> <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent qty_add" id="' + a[i].id + '">Add</button> </div> </td> </tr> </tbody> </table> </div> </div> </div>'
                cust_out+='<tr id="' + a[i].id + '"><td>' + a[i].name + '</td><td class="price">' + a[i].price + '</td><td><input type="text" class="product_qty" id="txt' + a[i].id + '"></td><td><button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--primary qty_add" id="' + a[i].id + '">Add</button></td></tr>'
            }
            $('#products').append(cust_out);
            
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