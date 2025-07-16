<style>
    .purchase_table {
		width: 100%;
        text-align: left;
        border: 0px solid #ccc;
        border-collapse: collapse;
		display: block;
    	overflow: auto;
	}

	.purchase_table > thead > tr {
		box-shadow: 0px 5px 5px #ccc;
	}

	.purchase_table > thead > tr > th {
		padding: 10px;
	}

	.purchase_table > tbody > tr {
		/*border-bottom: 1px solid #ccc;*/
	}

	.purchase_table > tbody > tr > td {
		padding: 15px;
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
	
	.price {
        <?php $sess_data = $this->session->userdata(); echo 'display:'.$sess_data['price_display'].';'; ?>
    }
</style>

<main class="mdl-layout__content" style="display:none;" id="page_content">
    <div class="mdl-grid">
        <div class="mdl-cell mdl-cell--12-col" style="border-radius:5px; padding:15px; box-shadow:0px 5px 10px #ccc;" id="search_products">
            <input type="text" id="search_records" style="font-size:1.3em; font-weight:bold; width:80%; outline:none; border:0px; border-bottom:1px solid #eee;" placeholder="Search Products" value="<?php if(isset($search_query)) { echo $search_query; } ?>">
            <button class="mdl-button mdl-button--raised mdl-button--colored mdl-button--icon" id="search_records_button"><i class="material-icons">search</i></button>
        </div>
        <?php for($i=0;$i<count($category);$i++) {
            $child = $category[$i]['child'];
            if(count($child) > 0 ) {
                echo '<div class="mdl-cell mdl-cell--12-col">';
                echo '<h5><b>'.$category[$i]['parent_name'].'</b></h5>';
                echo '</div>';
                echo '<table class="purchase_table"><tbody><tr>';
                for($j=0;$j<count($child);$j++) {
                    echo '<td>';
                    echo '<div class="mdl-card mdl-shadow--2dp child_card" id="'.$child[$j]['child_id'].'">';
                    echo '<div class="mdl-card__title mdl-card--expand" style="width:15em !important; text-align:left;background:  linear-gradient(#42424299, #424242), url(\''.base_url().'assets/uploads/'.$oid.'/c/'.$child[$j]['child_id'].'/'.$child[$j]['child_image'].'\'); background-size:cover;">';
                    echo '<h2 class="mdl-card__title-text">'.$child[$j]['child_name'].'</h2>';
                    echo '</div>';
                    echo '</div>';
                    echo '</td>';    
                }
                echo '</tr></tbody></table>';
            } else {
                echo '<div class="mdl-cell mdl-cell--12-col">';
                echo '<hr>';
                echo '<div class="mdl-card mdl-shadow--2dp child_card" id="'.$category[$i]['parent_id'].'">';
                echo '<div class="mdl-card__title mdl-card--expand" style="text-align:left;background:  linear-gradient(#42424299, #424242), url(\''.base_url().'assets/uploads/'.$oid.'/c/'.$category[$i]['parent_id'].'/'.$category[$i]['parent_image'].'\'); background-size:cover;">';
                echo '<h2 class="mdl-card__title-text">'.$category[$i]['parent_name'].'</h2>';
                echo '</div>';
                echo '</div>';
                echo '<hr>';
                echo '</div>';
            }
        }?>
    </div>
    <div class="mdl-grid" id="product_grid">
        <?php 
        for($i=0;$i<count($products);$i++) {
            echo '<div class="mdl-cell mdl-cell--12-col"><div class="mdl-card mdl-shadow--2dp"><div class="mdl-card__supporting-text" style="text-align:left;"><img src="'.$products[$i]['url'].'" class="product_view"  id="'.$products[$i]['id'].'" style="width:100%;"><h5>'.$products[$i]['name'].'</h5><p>'.$products[$i]['price'].'</p>';
            echo '<div style="display:flex;">';
            echo '<button class="mdl-button mdl-button--colored mdl-button--icon sub_cart" id="'.$products[$i]['id'].'"><i class="material-icons">remove</i></button>';
            echo '<div class="material-icons mdl-badge mdl-badge--overlap count_cart" data-badge="'.$products[$i]['cart'].'" id="'.$products[$i]['id'].'">shopping_cart</div>';
            echo '<button class="mdl-button mdl-button--colored mdl-button--icon add_cart" id="'.$products[$i]['id'].'"><i class="material-icons">add</i></button>';
            echo '</div>';
            echo '</div></div></div>';
        }
        ?>
        
    </div>
</main>
<div id="demo-snackbar-example" class="mdl-js-snackbar mdl-snackbar">
  <div class="mdl-snackbar__text"></div>
  <button class="mdl-snackbar__action" type="button"></button>
</div>
<script>
    var cart_products = [];
    var snackbarContainer = document.querySelector('#demo-snackbar-example');
    
    $(document).ready(function() {
        $('.child_card').click(function(e) {
            e.preventDefault();
            window.location = '<?php echo base_url().$type."/Home/load_category/"; ?>' + $(this).prop('id');
        });
        
        $('#product_grid').on('click','.add_cart', function(e) {
            e.preventDefault();
            var x1=$(this).prop('id');
            $.post('<?php echo base_url().$type."/Home/product_to_cart/add"; ?>', {
                'p' : x1,
            }, function(d,s,x) {
                update_badge_value(x1, d);
            })
        }).on('click','.sub_cart', function(e) {
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
        }).on('click','.product_view', function(e) {
            e.preventDefault();
            window.location = '<?php echo base_url().$type."/Home/load_product_details/"; ?>' + $(this).prop('id');
        });;
        
        
        
        $('#search_records').keydown(function(e) {
            if(e.keyCode == 13) {
                search_results($(this).val());
            }
        });
        
        $('#search_records_button').click(function(e) {
            e.preventDefault();
            search_results($('#search_records').val());
        });
        
        $('#disp_cart').click(function(e) {
            e.preventDefault();
            window.location = '<?php echo base_url().$type."/Home/load_cart"; ?>';
        })
        
        
    })
    
    function search_results(query) {
        $.post('<?php echo base_url().$type."/Home/search_products/page"; ?>', {
            'k' : query
        }, function(d,s,x) {
            var a=JSON.parse(d), b='';
            $('#product_grid').empty();
            for(var i=0;i<a.products.length;i++) {
                b+= '<div class="mdl-cell mdl-cell--12-col"><div class="mdl-card mdl-shadow--2dp"><div class="mdl-card__supporting-text" style="text-align:left;"><img src="' + a.products[i].url + '" class="product_view"  id="'+ a.products[i].id + '"  style="width:100%;"><h5>' + a.products[i].name + '</h5><p>' + a.products[i].price + '</p><div style="display:flex;"><button class="mdl-button mdl-button--colored mdl-button--icon sub_cart" id="'+ a.products[i].id + '"><i class="material-icons">remove</i></button><div class="material-icons mdl-badge mdl-badge--overlap count_cart" data-badge="' + a.products[i].cart + '" id="' + a.products[i].id + '">shopping_cart</div><button class="mdl-button mdl-button--colored mdl-button--icon add_cart" id="' + a.products[i].id + '"><i class="material-icons">add</i></button></div></div></div></div>';
            }
            $('#product_grid').append(b);
        })
    }
    
    function update_badge_value(pid, d) {
        $('.count_cart').each(function(index, value) {
            if($(this).prop('id') == pid) {
                $(this).attr('data-badge', d);
            }
        })
        
        $.post('<?php echo base_url().$type."/Home/load_cart_values/count"; ?>', {}, function(d,s,x) {
            $('#disp_cart').attr('data-badge', d);
        })
    }
</script>
        
            