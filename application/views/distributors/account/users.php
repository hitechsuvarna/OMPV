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
    }

    @media only screen and (max-width: 768px) {

        #products {
            overflow: auto;
            display: block;
        }
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
</style>
<main class="mdl-layout__content">
	<div class="mdl-grid">
        <table id="products">
            <thead>
                <tr>
                    <th>Users</th>
                    <th>Products</th>
                    <th>Pricing</th>
                    <th>Dealers</th>
                    <th>Vendors</th>
                    <th>Orders</th>
                    <th>Delivery</th>
                    <th>Inventory</th>
                    <th>Purchase</th>
                    <th>Expenses</th>
                    <th>Invoice</th>
                    <th>Credit Notes</th>
                    <th>Payments</th>
                    <th>Users</th>
                    <th>Godown</th>
                    <th>Accounting</th>
                    <th>Settings</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $t = '<td><i class="material-icons">done</i></td>';
                    $f = '<td><i class="material-icons">clear</i></td>';
                    for ($i=0; $i < count($users); $i++) { 
                        echo '<tr id="'.$users[$i]->iu_id.'">';
                        echo '<td>'.$users[$i]->ic_name.'</td>';
                        if($users[$i]->iua_u_products == "true") { echo $t; } else { echo $f; }
                        if($users[$i]->iua_u_pricing == "true") { echo $t; } else { echo $f; }
                        if($users[$i]->iua_u_dealers == "true") { echo $t; } else { echo $f; }
                        if($users[$i]->iua_u_vendors == "true") { echo $t; } else { echo $f; }
                        if($users[$i]->iua_u_orders == "true") { echo $t; } else { echo $f; }
                        if($users[$i]->iua_u_delivery == "true") { echo $t; } else { echo $f; }
                        if($users[$i]->iua_u_inventory == "true") { echo $t; } else { echo $f; }
                        if($users[$i]->iua_u_purchase == "true") { echo $t; } else { echo $f; }
                        if($users[$i]->iua_u_expenses == "true") { echo $t; } else { echo $f; }
                        if($users[$i]->iua_u_invoice == "true") { echo $t; } else { echo $f; }
                        if($users[$i]->iua_u_credit_note == "true") { echo $t; } else { echo $f; }
                        if($users[$i]->iua_u_payments == "true") { echo $t; } else { echo $f; }
                        if($users[$i]->iua_u_users == "true") { echo $t; } else { echo $f; }
                        if($users[$i]->iua_u_godown == "true") { echo $t; } else { echo $f; }
                        if($users[$i]->iua_u_ledgers == "true") { echo $t; } else { echo $f; }
                        if($users[$i]->iua_u_settings == "true") { echo $t; } else { echo $f; }

                        echo '</tr>';
                    }
                ?>
            </tbody>
        </table>
	</div>
    <button class="lower-button mdl-button mdl-button-done mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--accent" id="submit">
        <i class="material-icons">add</i>
    </button>
</main>
</div>

</body>
<script type="text/javascript">
    $(document).ready(function() {

        $('#fixed-header-drawer-exp').change(function(e) {
            $.post('<?php echo base_url().$type."/Account/search_user"; ?>', {
                'keywords' : txn_tags
            }, function(data, status, xhr) {
                var abc = JSON.parse(data);

                $('#products > tbody').empty();

                var cust = abc.customer;
                var cust_out = "";

                $('#products > tbody').append(cust_out);

                console.log(data);
            }, "text");
        });

        $('#products').on('click','tr', function(e) {
            e.preventDefault();
            
            window.location = "<?php echo base_url().$type.'/Account/edit_user/' ?>" + $(this).prop('id');
            
        })

        $('#submit').click(function(e) {
            e.preventDefault();

            window.location = "<?php echo base_url().$type.'/Account/add_user'; ?>";
        });
    });
</script>
</html>