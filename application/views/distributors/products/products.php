<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
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
    
    #products {
        width: 100%;
        text-align: left;
        border: 0px solid #ccc;
        border-collapse: collapse;
        display: block;
        overflow: auto;
    }

    @media only screen and (max-width: 760px) {
		#products {
			display: block;
        	overflow: auto;
		}
	}

    #products > thead > tr {
        box-shadow: 0px 5px 5px #ccc;
    }

    #products > thead > tr > th {
        padding: 15px;
    }

    #products > tbody > tr > td {
        padding: 15px 5px 0px 5px;
    }

    #products > tbody > tr {
        border-bottom: 1px solid #ccc;
    }

    #products > tbody > tr > td {
        color: #666;
        text-decoration: none;
    }
    
    .product_input {
        border: 1px solid #ccc;
        border-radius: 3px;
        padding: 10px;
        /*text-align: center;*/
        margin-bottom: 10px;
        width: 70px;
    }
    
    .ui-widget {
        z-index:9999;
    }

</style>
<main class="mdl-layout__content">
    <div class="mdl-grid">
        <?php $b=""; for($i=0;$i<count($category); $i++) {
	        $b.='<div class="mdl-cell mdl-cell--12-col-phone mdl-cell--3-col mdl-cell--4-col-tablet open" id="'.$category[$i]->ica_id.'"><div class="mdl-card mdl-shadow--2dp"><div class="mdl-card__title mdl-card--expand" style="background:linear-gradient(rgba(20,20,20,.3), rgba(20,20,20, .3)), url(\''.base_url().'assets/uploads/'.$oid.'/c/'.$category[$i]->ica_id.'/'.$category[$i]->ica_img.'\'); background-size:cover; "><div class="mdl-card__title-text">'.$category[$i]->ica_category_name.'</div></div></div></div>';
	        } echo $b;
	    ?>
    </div>
	<div class="mdl-grid">
        <table id="products">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Product Code</th>
                    <th>Alias</th>
                    <th>HSN Code</th>
                    <th>Units</th>
                    <th>Tax</th>
                    <th>Category</th>
                    <th>Lower Limit</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
	</div>
    <div class="mdl-grid">
        <div class="mdl-cell mdl-cell--12-col">
            <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--colored" id="manage_category" style="width: 100%;">
                <i class="material-icons">group_work</i> Manage Categories
            </button>
                    
        </div>
    </div>
    <button class="lower-button mdl-button mdl-button-done mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--accent" id="submit" currcat="<?php echo $curr_cat; ?>">
        <i class="material-icons">add</i>
    </button>
    <button class="lower-button mdl-button mdl-button-done mdl-js-button mdl-js-ripple-effect mdl-button--accent" id="merge">
		<i class="material-icons">update</i> Update Selected
	</button>
    <button class="lower-button mdl-button mdl-button-done mdl-js-button mdl-js-ripple-effect mdl-button--accent" style="right:235px !important;" id="select_all" state="0">
		Select All
	</button>
    <div id="demo-snackbar-example" class="mdl-js-snackbar mdl-snackbar">
        <div class="mdl-snackbar__text"></div>
        <button class="mdl-snackbar__action" type="button"></button>
    </div>
    
</main>
</div>
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Update Selected Products</h4>
            </div>
            <div class="modal-body">
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
					<input type="text" id="p_alias" class="mdl-textfield__input">
					<label class="mdl-textfield__label" for="p_alias">Alias</label>
				</div>
				<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
					<input type="text" id="p_code" class="mdl-textfield__input">
					<label class="mdl-textfield__label" for="p_code">Code</label>
				</div>
				<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
					<input type="text" id="p_hsn" class="mdl-textfield__input">
					<label class="mdl-textfield__label" for="p_hsn">HSN Code</label>
				</div>
				<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
					<input type="text" id="p_units" class="mdl-textfield__input">
					<label class="mdl-textfield__label" for="p_units">Units</label>
				</div>
				<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
					<input type="text" id="p_tax" class="mdl-textfield__input">
					<input type="text" id="p_tax_sec" style="display:none;">
					<label class="mdl-textfield__label" for="p_tax">Taxes</label>
				</div>
				<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
					<input type="text" id="p_category" class="mdl-textfield__input">
					<label class="mdl-textfield__label" for="p_category">Category</label>
				</div>
				<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
					<input type="text" id="p_lower" class="mdl-textfield__input">
					<label class="mdl-textfield__label" for="p_lower">Lower Limit</label>
				</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="mdl-button mdl-js-button" data-dismiss="modal">Close</button>
                <button type="button" class="mdl-button mdl-js-button mdl-button--raised" id="save_group_products">Save</button>
            </div>
        </div>
    </div>
</div>
</body>
<script type="text/javascript">
    var cat_data=[], unit_data=[], tax_data=[];
    var search_flg=false;
    var sl=0, sel_pro=[];
    var snackbarContainer = document.querySelector('#demo-snackbar-example');
    
    $(document).ready(function() {
        $('#merge').css('display','none');
        $('#select_all').css('display','none');
        
        <?php
    		for ($i=0; $i < count($cat_list) ; $i++) { 
    			echo "cat_data.push('".$cat_list[$i]->ica_category_name."');";
    		}
    	?>
    	
    	$( "#product_cat" ).autocomplete({
            source: function(request, response) {
                var results = $.ui.autocomplete.filter(cat_data, request.term);
                response(results.slice(0, 10));
            }
        });
        
        $( "#p_category" ).autocomplete({
            source: function(request, response) {
                var results = $.ui.autocomplete.filter(cat_data, request.term);
                response(results.slice(0, 10));
            }
        });
        
        <?php
    		for ($i=0; $i < count($units) ; $i++) { 
    			echo "unit_data.push('".$units[$i]->ip_unit."');";
    		}
    	?>
    	$( ".product_unit" ).autocomplete({
            source: function(request, response) {
                var results = $.ui.autocomplete.filter(unit_data, request.term);
                response(results.slice(0, 10));
            }
        });
        $( "#p_units" ).autocomplete({
            source: function(request, response) {
                var results = $.ui.autocomplete.filter(unit_data, request.term);
                response(results.slice(0, 10));
            }
        });
        
        <?php
    		for ($i=0; $i < count($taxes) ; $i++) { 
    			echo "tax_data.push({'label':'".$taxes[$i]->ittxg_group_name."', 'value' : '".$taxes[$i]->ittxg_id."'});";
    		}
    	?>
    	$( ".product_tax" ).autocomplete({
            source: function(request, response) {
                var results = $.ui.autocomplete.filter(tax_data, request.term);
                response(results.slice(0, 10));
            }, focus: function(e,u) {
                $(this).val(u.item.label);
                var x = '#ptx' + $(this).attr('tmp');
                $(x).val(u.item.value);
                return false;
            },select: function(e,u) {
                $(this).val(u.item.label);
                var x = '#ptx' + $(this).attr('tmp');
                $(x).val(u.item.value);
                return false;
            }
        });
        
        $( "#p_tax" ).autocomplete({
            source: function(request, response) {
                var results = $.ui.autocomplete.filter(tax_data, request.term);
                response(results.slice(0, 10));
            }, focus: function(e,u) {
                $(this).val(u.item.label);
                $('#p_tax_sec').val(u.item.value);
                return false;
            },select: function(e,u) {
                $(this).val(u.item.label);
                $('#p_tax_sec').val(u.item.value);
                return false;
            }
        });
        
        fetch_products(null);
        
        $('#merge').click(function(e) {
            e.preventDefault();
            $('#myModal').modal('toggle');
        })
        
        $('#products').on('click','.delete_btn', function(e) {
            e.preventDefault();
            
            $.post('<?php echo base_url().$type."/Products/delete_product/"; ?>' + $(this).prop('id'), function(d,s,x) {
                var ert = {message: "Product Deleted.",timeout: 2000, }; 
                snackbarContainer.MaterialSnackbar.showSnackbar(ert);
                setTimeout(function() {
                    fetch_products($('#fixed-header-drawer-exp').val());
                }, 2000);
            })    
        }).on('click','.select_btn', function(e) {
            e.preventDefault();
            if($(this).attr('state') == "0") {
                sl++;
                $(this).attr('state','1');
                $(this).addClass('mdl-button--raised');
		        $(this).children().empty();
		        $(this).children().append('trending_flat');
		        sel_pro.push($(this).prop('id'));
            } else {
                sl--;
                $(this).attr('state','1');
                $(this).removeClass('mdl-button--raised');
		        $(this).children().empty();
		        $(this).children().append('done');
		        var index = sel_pro.indexOf($(this).prop('id'));
                if(index!=-1){
                    sel_pro.splice(index, 1);
                }
            }
            
            if(sl>0) {
		        $('#merge').css('display','block');
		        $('#select_all').css('display','block');
		        
		        $('#submit').css('display','none');
		    } else {
		        $('#merge').css('display','none');
		        $('#select_all').css('display','none');
		        
		        $('#submit').css('display','block');
		    }
                
        }).on('click','.update_btn', function(e) {
            e.preventDefault();
            
            var x = '[product='+$(this).prop('id')+']';
            var m=[];
            $(x).each(function() {
                m.push($(this).val());
            })
            console.log(m);
            $.post('<?php echo base_url().$type."/Products/update_product/"; ?>' + $(this).prop('id'),{
                'product' : m[0],
                'description' : m[1],
                'alias' : m[2],
                'hsn' : m[3],
                'unit' : m[4],
                'tax' : m[5],
                'category' : m[6],
                'limit' : m[7],
            }, function(d,s,x) {
                var ert = {message: "Product Updated.",timeout: 2000, }; 
                snackbarContainer.MaterialSnackbar.showSnackbar(ert);
                setTimeout(function() {
                    fetch_products($('#fixed-header-drawer-exp').val());
                }, 2000);
            })    
        });
        
        $('#select_all').click(function(e) {
            e.preventDefault();
            if($(this).attr('state') == 0) {
                sel_pro=[];
                $(this).attr('state','1');
                $(this).empty();
                $(this).append('Unselect All');
                $('.select_btn').each(function(e) {
                    sl++;
                    $(this).attr('state','1');
                    $(this).addClass('mdl-button--raised');
    		        $(this).children().empty();
    		        $(this).children().append('trending_flat');
    		        sel_pro.push($(this).prop('id'));
                })    
            } else if($(this).attr('state') == 1) {
                $(this).attr('state','0');
                $(this).empty();
                $(this).append('Select All');
                $('.select_btn').each(function(e) {
                    sl--;
                    $(this).attr('state','1');
                    $(this).removeClass('mdl-button--raised');
    		        $(this).children().empty();
    		        $(this).children().append('done');
    		        var index = sel_pro.indexOf($(this).prop('id'));
                    if(index!=-1){
                        sel_pro.splice(index, 1);
                    }
                })    
            }
            
            if(sl>0) {
		        $('#merge').css('display','block');
		        $('#select_all').css('display','block');
		        
		        $('#submit').css('display','none');
		    } else {
		        $('#merge').css('display','none');
		        $('#select_all').css('display','none');
		        
		        $('#submit').css('display','block');
		    }
        })
        
        $('#save_group_products').click(function(e) {
            e.preventDefault();
            
            $.post('<?php echo base_url().$type."/Products/update_products_common/"; ?>', {
                'p' : sel_pro,
                'category' : $('#p_category').val(),
                'tax' : $('#p_tax_sec').val(),
                'alias' : $('#p_alias').val(),
                'hsn' : $('#p_hsn').val(),
                'unit' : $('#p_units').val(),
                'limit' : $('#p_lower').val()
            }, function(d,s,x) {
                var ert = {message: "Product Updated.",timeout: 2000, }; 
                snackbarContainer.MaterialSnackbar.showSnackbar(ert);
                setTimeout(function() {
                    fetch_products(null);
                    $('#p_category').val('');
                    $('#p_tax_sec').val('');
                    $('#p_alias').val('');
                    $('#p_hsn').val('');
                    $('#p_units').val('');
                    $('#p_lower').val('');
                    $('#myModal').modal('toggle');
                }, 2000);
            })
        })
        
        $('div').on('click','.open', function(e) {
            e.preventDefault();
            window.location = "<?php echo base_url().$type.'/Products/load_category/'; ?>" + $(this).prop('id');
        });

        $('#manage_category').click(function(e) {
            e.preventDefault();
            window.location = "<?php echo base_url().$type.'/Products/manage_categories' ?>"
        })

        $('#fixed-header-drawer-exp').keydown(function(e) {
            if(e.keyCode==13) {
                search_flg=true;
                fetch_products($(this).val());
            }
        });

        
        $('#products').on('click','.view_btn', function(e) {e.preventDefault(); window.location = "<?php echo base_url().$type.'/Products/edit_product/'.$curr_cat.'/'; ?>" + $(this).prop('id'); });
        $('#products').on('click','.inventory_btn', function(e) {e.preventDefault(); window.location = "<?php echo base_url().$type.'/Transactions/inventory_details/'; ?>" + $(this).prop('id'); });
        
        
        $('#submit').click(function(e) {e.preventDefault(); window.location = "<?php echo base_url().$type.'/Products/add_product/'; ?>" + $(this).attr('currcat'); });
    });
    var item_list = [];
    
    function fetch_products(key) {
        if(search_flg==true) {
            item_list=[];
            $.post('<?php echo base_url().$type."/Products/search_products/".$curr_cat; ?>', {
                'keywords' : key
            }, function(data, status, xhr) {
                var a = JSON.parse(data);
                for (var i = 0; i < a.length; i++) {
                    item_list.push({
                        "id" : a[i].ip_id,
                        "code" : a[i].ip_description,
                        "name" : a[i].ip_name,
                        "alias" : a[i].ip_alias,
                        "hsn" : a[i].ip_hsn_code,
                        "unit" : a[i].ip_unit,
                        "tax" : a[i].ittxg_group_name,
                        "category" : a[i].ica_category_name,
                        "lower_limit" : a[i].ip_lower_limit
                    });
                }
                load_products();
            }, "text");
        } else {
            item_list=[];
            $.post('<?php echo base_url().$type."/Products/load_products/".$curr_cat; ?>', {
            }, function(data, status, xhr) {
                var a = JSON.parse(data);
                for (var i = 0; i < a.length; i++) {
                    item_list.push({
                        "id" : a[i].ip_id,
                        "code" : a[i].ip_description,
                        "name" : a[i].ip_name,
                        "alias" : a[i].ip_alias,
                        "hsn" : a[i].ip_hsn_code,
                        "unit" : a[i].ip_unit,
                        "tax" : a[i].ittxg_group_name,
                        "category" : a[i].ica_category_name,
                        "lower_limit" : a[i].ip_lower_limit
                    });
                }
                load_products();
            }, "text");
        }
    }
    
    function load_products() {
        $('#products > tbody').empty(); var m="";
        for(var i=0;i<item_list.length;i++) {
            m+='<tr><td><input type="text" id="' + item_list[i].id + '" class="product_name product_input" value="' + item_list[i].name + '" type="name" product="' + item_list[i].id + '" style="width:450px;"></td><td><input type="text" id="" class="product_name product_input" value="' + item_list[i].code + '" type="code" product="' + item_list[i].id + '" style="width:120px;"></td><td><input type="text" id="" class="product_input" value="' + item_list[i].alias + '" product="' + item_list[i].id + '" type="alias" style="width:120px;"></td><td><input type="text" id="" class="product_input" value="' + item_list[i].hsn + '" product="' + item_list[i].id + '" type="hsn"></td><td><input type="text" id="" class="product_input product_unit" value="' + item_list[i].unit + '" product="' + item_list[i].id + '" type="unit"></td><td><input type="text" id="" class="product_input product_tax" value="' + item_list[i].tax + '" tmp="' + item_list[i].id + '"><input type="text" id="ptx' + item_list[i].id + '" class="product_tax_sec" style="display:none;" product="' + item_list[i].id + '" type="tax" value=""></td><td><input type="text" id="" class="product_input product_cat" value="' + item_list[i].category + '" product="' + item_list[i].id + '" type="category" style="width:200px;"></td><td><input type="text" id="" class="product_input" value="' + item_list[i].lower_limit + '" product="' + item_list[i].id + '" type="limit"></td><td><button class="mdl-button mdl-js-button mdl-button--colored update_btn" title="Update Product" id="' + item_list[i].id + '"><i class="material-icons">update</i></button><button class="mdl-button mdl-js-button mdl-button--colored delete_btn" title="Delete Product" id="' + item_list[i].id + '"><i class="material-icons">delete</i></button><button class="mdl-button mdl-js--button mdl-button--colored   select_btn" title="Select Product" id="' + item_list[i].id + '" state="0"><i class="material-icons">done</i></button><button class="mdl-button mdl-js--button   mdl-button--colored view_btn" title="View Product" id="' + item_list[i].id + '"><i class="material-icons">exit_to_app</i></button></td></tr>';   
        }
        $('#products > tbody').append(m);
        $("table").find('input[id=product_cat]').autocomplete({
            source: function(request, response) {
                var results = $.ui.autocomplete.filter(cat_data, request.term);
                response(results.slice(0, 10));
            }
        })
        // $( ".product_cat" ).autocomplete({
    	   // source: function( request, response ) {
        //         $.ajax({
        //             url: "<?php echo base_url().$type.'/Products/search_categories/name'; ?>",
        //             data: {
        //                 'keyword': request.term
        //             },
        //             sucess: function(data) {
        //                 console.log('ok');
        //                 var results = $.ui.autocomplete.filter(cat_data, request.term);
        //                 response(results.slice(0, 10));
        //             }, error: function(data) {
        //                 console.log('Not found');
        //             } 
        //         });
        //         },
        //         minLength: 2,
        //     // source: function(request, response) {
        //     //     var results = $.ui.autocomplete.filter(cat_data, request.term);
        //     //     response(results.slice(0, 10));
        //     // }
        // });
    }
</script>
</html>