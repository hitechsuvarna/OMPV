<link href="<?php echo base_url().'assets/css/tableexport.css'; ?>" rel="stylesheet">
<script src="<?php echo base_url().'assets/js/FileSaver.js'; ?>"></script>
<script src="<?php echo base_url().'assets/js/tableexport.js'; ?>"></script>
<script src="<?php echo base_url().'assets/js/Blob.js'; ?>"></script>
<script src="<?php echo base_url().'assets/js/xlsx.core.min.js'; ?>"></script>

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
    
    #dealers {
        width: 100%;
        text-align: left;
        border: 0px solid #ccc;
        border-collapse: collapse;
    }
    
    @media only screen and (max-width: 760px) {
		#dealers {
			display: block;
        	overflow: auto;
		}
	}

    #dealers > thead > tr {
        box-shadow: 0px 5px 5px #ccc;
    }

    #dealers > thead > tr > th {
        padding: 10px;
    }

    #dealers > tbody > tr > td {
        padding: 15px;
    }

    #dealers > tbody > tr {
        border-bottom: 1px solid #ccc;
    }

    #dealers > tbody > tr > td > a {
        color: #ff0000;
        text-decoration: none;
    }
</style>
<main class="mdl-layout__content">
	<div class="mdl-grid">
	    <div class="mdl-cell mdl-cell--12-col">
	        <button class="mdl-button mdl-js--button mdl-button--raised mdl-button--colored" id="export">Export</button>    
	    </div>
        <table id="dealers">
            <thead>
                <tr>
                    <th>Dealer</th>
                    <th>Company</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>GST Number</th>
                </tr>
            </thead>
            <tbody>
                
                <?php for ($i=0; $i < count($dealers) ; $i++) { 
                    // 0-Activate, 1-New, 2-Block
                    if($dealers[$i]->ic_new_flag == "1") {
                        echo '<tr id="'.$dealers[$i]->ic_id.'"> <td><i class="material-icons">whatshot</i> '.$dealers[$i]->ic_name.'</td><td>'.$dealers[$i]->ic_company.'</td><td>'.$dealers[$i]->ic_phone.'</td><td>'.$dealers[$i]->ic_email.'</td><td>'.$dealers[$i]->ic_address.'</td><td>'.$dealers[$i]->ic_gst_number.'</td></tr>';
                    } elseif($dealers[$i]->ic_new_flag == "2") {
                        echo '<tr id="'.$dealers[$i]->ic_id.'"> <td><i class="material-icons">block</i> '.$dealers[$i]->ic_name.'</td><td>'.$dealers[$i]->ic_company.'</td><td>'.$dealers[$i]->ic_phone.'</td><td>'.$dealers[$i]->ic_email.'</td><td>'.$dealers[$i]->ic_address.'</td><td>'.$dealers[$i]->ic_gst_number.'</td></tr>';
                    } else {
                        echo '<tr id="'.$dealers[$i]->ic_id.'"> <td>'.$dealers[$i]->ic_name.'</td><td>'.$dealers[$i]->ic_company.'</td><td>'.$dealers[$i]->ic_phone.'</td><td>'.$dealers[$i]->ic_email.'</td><td>'.$dealers[$i]->ic_address.'</td><td>'.$dealers[$i]->ic_gst_number.'</td></tr>';
                    }
                } ?>
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
        $('#dealers').on('click','tr', function(e) {e.preventDefault(); window.location = "<?php echo base_url().$type.'/Dealers/edit_dealer/'; ?>" + $(this).prop('id'); });
        
        function update_flag(state) {
            $.post('<?php echo base_url().$type."/Dealers/update_flag"; ?>', {
                'd' : $(this).prop('id'), 'f' : state
            }, function(d,s,x) {
                window.location = "<?php echo base_url().$type.'/Dealers'; ?>";
            })
        }
        
        $('#fixed-header-drawer-exp').change(function(e) {
            $.post('<?php echo base_url().$type."/Dealers/search_dealers"; ?>', {
                'keywords' : $(this).val()
            }, function(data, status, xhr) {
                var a = JSON.parse(data), s = ""; $('#dealers > tbody').empty();
                for (var i = 0; i < a.length; i++) {
                    
                    if(a[i].ic_new_flag == "1") {
                        s+='<tr id="' + a[i].ic_id + '"> <td><i class="material-icons">whatshot</i> ' + a[i].ic_name + '</td></tr>';
                    } else if(a[i].ic_new_flag == "2") {
                        s+='<tr id="' + a[i].ic_id + '"> <td><i class="material-icons">block</i> ' + a[i].ic_name + '</td></tr>';
                    } else {
                        s+='<tr id="' + a[i].ic_id + '"> <td>' + a[i].ic_name + '</td></tr>';
                    }
                }
                $('#dealers').append(s);
            }, "text");
        });

        $('#submit').click(function(e) {e.preventDefault(); window.location = "<?php echo base_url().$type.'/Dealers/add_dealer'; ?>"; }); 
        
        $('#export').click(function(e) {
		    e.preventDefault();
		    var dt = new Date();
		    
		    $('#dealers').tableExport({
                // Displays table headings (th or td elements) in the <thead>
                headings: true,                    
                // Displays table footers (th or td elements) in the <tfoot>    
                footers: true, 
                // Filetype(s) for the export
                formats: ["xls"],           
                // Filename for the downloaded file
                filename: 'Dealer List as on ' + dt.getDate() + '-' + (dt.getMonth() + 1) + '-' + dt.getFullYear(), 
                // Style buttons using bootstrap framework  
                bootstrap: true,                     
                // Position of the caption element relative to table
                position: "top",                   
                // (Number, Number[]), Row indices to exclude from the exported file(s)
                ignoreRows: null,       
                // (Number, Number[]), column indices to exclude from the exported file(s)              
                ignoreCols: null,                
                // Selector(s) to exclude cells from the exported file(s)       
                ignoreCSS: ".tableexport-ignore",  
                // Selector(s) to replace cells with an empty string in the exported file(s)       
                emptyCSS: ".tableexport-empty",   
                // Removes all leading/trailing newlines, spaces, and tabs from cell text in the exported file(s)     
                trimWhitespace: false         

            });
		})
        
    });
</script>
</html>