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
	
</style>
<main class="mdl-layout__content" style="display:none;" id="page_content">
    <div class="mdl-grid">
        <div class="mdl-cell mdl-cell--12-col" style="border-radius:5px; padding:15px; box-shadow:0px 5px 10px #ccc;" id="search_products">
            <input type="text" id="search_records" style="font-size:1.3em; font-weight:bold; width:80%; outline:none; border:0px; border-bottom:1px solid #eee;" placeholder="Search Products" >
            <button class="mdl-button mdl-button--raised mdl-button--colored mdl-button--icon" id="search_records_button"><i class="material-icons">search</i></button>
        </div>
        <?php if(count($orders) > 0) { 
            echo '<div class="mdl-cell mdl-cell--12-col">';
            echo '<h5><b>Your Current Orders</b></h5>';
            echo '</div>';
            for($i=0;$i<count($orders);$i++) {
                echo '<div class="mdl-cell mdl-cell--12-col">';
                echo '<div class="mdl-card mdl-shadow--2dp" style="text-align: left;padding: 1em;background: linear-gradient(90deg, #b2ff59, #ffffff14), url(http://dhristi.evomata.com/ompv/assets/images/order_processing.jpg);background-size: contain;background-repeat: no-repeat;background-position: right;">';
                echo '<p>Order No:</p>';
                echo '<p style="font-size:3em;font-weight:bold;">'.$orders[$i]['txn_no'].'</p>';
                echo '<button class="mdl-button" style="text-align:left;"><i class="material-icons">money</i> '.$orders[$i]['amount'].'</button>';
                echo '<button class="mdl-button" style="text-align:left;"><i class="material-icons">event</i> '.$orders[$i]['date'].'</button>';
                echo '<button class="mdl-button" style="text-align:left;"><i class="material-icons">label</i>'.$orders[$i]['status'].'</button>';
                echo '</div>';
                echo '</div>';
            }
        } ?>
        
        
        <?php if(count($delivery) > 0) { 
            echo '<div class="mdl-cell mdl-cell--12-col">';
            echo '<h5><b>Your Deliveries</b></h5>';
            echo '</div>';
            for($i=0;$i<count($delivery);$i++) {
                echo '<div class="mdl-cell mdl-cell--12-col">';
                echo '<div class="mdl-card mdl-shadow--2dp" style="text-align: left;padding: 1em;background: linear-gradient(90deg, #b2ff59, #ffffff14), url(http://dhristi.evomata.com/ompv/assets/images/order_delivery.jpg);background-size: contain;background-repeat: no-repeat;background-position: right;">';
                echo '<p>Challan No:</p>';
                echo '<p style="font-size:3em;font-weight:bold;">'.$delivery[$i]['txn_no'].'</p>';
                echo '<button class="mdl-button" style="text-align:left;"><i class="material-icons">money</i> '.$delivery[$i]['amount'].'</button>';
                echo '<button class="mdl-button" style="text-align:left;"><i class="material-icons">event</i> '.$delivery[$i]['date'].'</button>';
                echo '<button class="mdl-button" style="text-align:left;"><i class="material-icons">label</i>'.$delivery[$i]['status'].'</button>';
                echo '</div>';
                echo '</div>';
            }
        } ?>
        
        <div class="mdl-cell mdl-cell--12-col" style="display:none;">
            <table class="purchase_table" id="schemes">
                <tbody>
                    <tr>
                        <td>
                            <div class="mdl-card mdl-shadow--2dp">
                                <div class="mdl-card__title mdl-card--expand"  style="background:url('<?php echo base_url().'assets/images/scheme1.jpg'; ?>'); width:90vw !important;"></div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
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
        } ?>
    </div>
</main>
<script>
    $(document).ready(function() {
        $('.child_card').click(function(e) {
            e.preventDefault();
            window.location = '<?php echo base_url().$type."/Home/load_category/"; ?>' + $(this).prop('id');
        });
        
        $('#search_records').keydown(function(e) {
            if(e.keyCode == 13) {
                $.post('<?php echo base_url().$type."/Home/search_products/"; ?>', {
                    'k' : $(this).val()
                }, function(d,s,x) {
                    window.location = '<?php echo base_url().$type."/Home/load_category/"; ?>';
                })
            }
        });
        
        $('#search_records_button').click(function(e) {
            e.preventDefault();
            
            $.post('<?php echo base_url().$type."/Home/search_products/"; ?>', {
                'k' : $('#search_records').val()
            }, function(d,s,x) {
                window.location = '<?php echo base_url().$type."/Home/load_category/"; ?>';
            })
        });
        
        $.post('<?php echo base_url().$type."/Home/load_cart_values/count"; ?>', {}, function(d,s,x) {
            $('#disp_cart').attr('data-badge', d);
        })
        
        $('#disp_cart').click(function(e) {
            e.preventDefault();
            window.location = '<?php echo base_url().$type."/Home/load_cart"; ?>';
        })
        
    })
</script>