<style type="text/css">
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

    #products > tbody > tr > td {
        color: #666;
        text-decoration: none;
    }

</style>

<main class="mdl-layout__content">
    <div class="mdl-grid">
	    <div class="mdl-cell mdl-cell--4-col">
	        <div class="mdl-card mdl-shadow--4dp">
	            <div class="mdl-card__title">
	                <div class="mdl-card__title-text">
	                    <h4 class="mdl-card__title-text">Accounts Information</h4>
	                </div>
	            </div>
                <div class="mdl-card__supporting-text">
                    <p>Access your ledgers and group transactions from the options below</p>
                </div>
                <div class="mdl-card__actions mdl-card--border" style="text-align:left;">
                    <div><button class="mdl-button mdl-js-button" id="ledgers"><i class="material-icons">crop_square</i> Ledgers</button></div>
                    <div><button class="mdl-button mdl-js-button" id="groups"><i class="material-icons">filter_none</i> Groups</button></div>
                </div>
	        </div>
	    </div>
	    <div class="mdl-cell mdl-cell--4-col">
	        <div class="mdl-card mdl-shadow--4dp">
	            <div class="mdl-card__title">
	                <div class="mdl-card__title-text">
	                    <h4 class="mdl-card__title-text">Vouchers</h4>
	                </div>
	            </div>
                <div class="mdl-card__supporting-text">
                    <p>Create vouchers from the options below</p>
                </div>
                <div class="mdl-card__actions mdl-card--border" style="text-align:left;">
                    <div><button class="mdl-button mdl-js-button" id="journal_entry"><i class="material-icons">receipt</i> Journal Entries</button></div>
                    <!--<div><button class="mdl-button mdl-js-button" id="issue_payments"><i class="material-icons">payment</i> Issue Payments</button></div>-->
                    <!--<div><button class="mdl-button mdl-js-button" id="receive_payments"><i class="material-icons">payment</i> Receive Payments</button></div>-->
                </div>
	        </div>
	    </div>
	    <div class="mdl-cell mdl-cell--4-col">
	        <div class="mdl-card mdl-shadow--4dp">
	            <div class="mdl-card__title">
	                <div class="mdl-card__title-text">
	                    <h4 class="mdl-card__title-text">Masters</h4>
	                </div>
	            </div>
                <div class="mdl-card__supporting-text">
                    <p>Manage masters from the options below</p>
                </div>
                <div class="mdl-card__actions mdl-card--border" style="text-align:left;">
                    <div><button class="mdl-button mdl-js-button" id="classes"><i class="material-icons">category</i> Accounting Classes</button></div>
                </div>
	        </div>
	    </div>
	    <div class="mdl-cell mdl-cell--4-col">
	        <div class="mdl-card mdl-shadow--4dp">
	            <div class="mdl-card__title">
	                <div class="mdl-card__title-text">
	                    <h4 class="mdl-card__title-text">Reports</h4>
	                </div>
	            </div>
                <div class="mdl-card__supporting-text">
                    <p>View your reports from the options below</p>
                </div>
                <div class="mdl-card__actions mdl-card--border" style="text-align:left;">
                    <div><button class="mdl-button mdl-js-button" id="trial_balance"><i class="material-icons">account_balance_wallet</i> Trial Balance</button></div>
                </div>
	        </div>
	    </div>
	    <div class="mdl-cell mdl-cell--4-col">
	        <div class="mdl-card mdl-shadow--4dp">
	            <div class="mdl-card__title">
	                <div class="mdl-card__title-text">
	                    <h4 class="mdl-card__title-text"><i class="material-icons">star</i> Starred Ledgers</h4>
	                </div>
	            </div>
                <div class="mdl-card__supporting-text">
                    <p>The important ledgers that you need quick access to</p>
                </div>
                <div class="mdl-card__actions mdl-card--border" style="text-align:left;">
                    <?php for($i=0;$i<count($ledgers);$i++) {
            	        echo '<button style="width:100%; text-align:left;" class="mdl-button mdl-js-button ledger_specific" id="'.$ledgers[$i]->iacl_id.'"><i class="material-icons">star_border</i> '.$ledgers[$i]->iacl_name.'</button><hr>';
            	    } ?>
                </div>
	        </div>
	    </div>
    </div>
</div>
</div>
</body>
<script>
	$(document).ready(function() {
	    $('#journal_entry').click(function(e) {
	        e.preventDefault();
	        window.location = "<?php echo base_url().$type.'/Accounting/journal_entries'; ?>";
	    })
	    
	    $('#ledgers').click(function(e) {
	        e.preventDefault();
	        window.location = "<?php echo base_url().$type.'/Accounting/ledgers'; ?>";
	    });
	    
	    $('.ledger_specific').click(function(e) {
	        e.preventDefault();
	        window.location = "<?php echo base_url().$type.'/Accounting/ledger_details/'; ?>" + $(this).prop('id');
	    });
	    
	    $('#groups').click(function(e) {
	        e.preventDefault();
	        window.location = "<?php echo base_url().$type.'/Accounting/groups'; ?>";
	    });
	    
	    $('#classes').click(function(e) {
	        e.preventDefault();
	        window.location = "<?php echo base_url().$type.'/Accounting/classes'; ?>";
	    });
	    
	    $('#trial_balance').click(function(e) {
	        e.preventDefault();
	        window.location = "<?php echo base_url().$type.'/Accounting/trial_balance'; ?>";
	    });
	    
	    $('#receive_payments').click(function(e) {
	        e.preventDefault();
	        window.location = "<?php echo base_url().$type.'/Accounting/vouchers/receipt'; ?>";
	    });
	    
	    $('#issue_payments').click(function(e) {
	        e.preventDefault();
	        window.location = "<?php echo base_url().$type.'/Accounting/vouchers/payment'; ?>";
	    });
	    
	    
	});
</script>
</html>