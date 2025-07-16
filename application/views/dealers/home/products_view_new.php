<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

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
    </div>
    <div class="mdl-grid" id="product_grid">
        <?php 
            $vb="";$bn="";
            for($i=0;$i<count($products);$i++) {
                $vb='<div class="mdl-cell mdl-cell--12-col"><h3>'.$products[$i]['name'].'</h3></div>';
                $vb.='<div class="mdl-cell mdl-cell--12-col">';
                $vb.='<div id="myCarousel" class="carousel slide" data-ride="carousel">';
                $vb.='<ol class="carousel-indicators">';
                for($j=0;$j<count($products[$i]['images']);$j++) {
                    if($j==0) {
                        $vb.='<li data-target="#myCarousel" data-slide-to="'.$j.'" class="active"></li>';
                        $bn.='<div class="item active"><img src="'.$products[$i]['images'][$j].'" alt="OMPV"></div>';    
                    } else {
                        $vb.='<li data-target="#myCarousel" data-slide-to="'.$j.'"></li>';
                        $bn.='<div class="item"><img src="'.$products[$i]['images'][$j].'" alt="OMPV"></div>'; 
                    }
                    
                }
                $vb.='</ol>';
                $vb.='<div class="carousel-inner">';
                $vb.=$bn;
                $vb.='</div>';
                $vb.='<a class="left carousel-control" href="#myCarousel" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span><span class="sr-only">Previous</span></a><a class="right carousel-control" href="#myCarousel" data-slide="next"><span class="glyphicon glyphicon-chevron-right"></span><span class="sr-only">Next</span></a>';
                $vb.='</div>';
                $vb.='</div>';
                $vb.='<div class="mdl-cell mdl-cell--12-col"><b>'.$products[$i]['price'].'</b>';
                $vb.='</div>';
                $vb.='<div class="mdl-cell mdl-cell--12-col">';
                $vb.='<div style="display:flex;">';
                $vb.='<button class="mdl-button mdl-button--colored mdl-button--icon sub_cart" id="'.$products[$i]['id'].'"><i class="material-icons">remove</i></button>';
                $vb.='<div class="material-icons mdl-badge mdl-badge--overlap count_cart" data-badge="'.$products[$i]['cart'].'" id="'.$products[$i]['id'].'">shopping_cart</div>';
                $vb.='<button class="mdl-button mdl-button--colored mdl-button--icon add_cart" id="'.$products[$i]['id'].'"><i class="material-icons">add</i></button>';
                $vb.='</div>';
                $vb.='</div>';
            }
            echo $vb;
        ?>
    </div>
    <div class="mdl-grid">
        <div class="mdl-cell mdl-cell--12-col">
            <button class="mdl-button" id="cart_proceed_bottom">Proceed to Cart <i class="material-icons">keyboard_arrow_right</i></button>
        </div>
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
        
        $('#cart_proceed_bottom').click(function(e) {
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
                b+= '<div class="mdl-cell mdl-cell--12-col"><div class="mdl-card mdl-shadow--2dp"><div class="mdl-card__supporting-text" style="text-align:left;"><img src="' + a.products[i].url + '" style="width:100%;"><h5>' + a.products[i].name + '</h5><p>' + a.products[i].price + '</p><div style="display:flex;"><button class="mdl-button mdl-button--colored mdl-button--icon sub_cart" id="'+ a.products[i].id + '"><i class="material-icons">remove</i></button><div class="material-icons mdl-badge mdl-badge--overlap count_cart" data-badge="' + a.products[i].cart + '" id="' + a.products[i].id + '">shopping_cart</div><button class="mdl-button mdl-button--colored mdl-button--icon add_cart" id="' + a.products[i].id + '"><i class="material-icons">add</i></button></div></div></div></div>';
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
        
            