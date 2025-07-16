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
        <table id="dealers">
            <thead>
                <tr>
                    <th>Vendor</th>
                </tr>
            </thead>
            <tbody>
                <?php for ($i=0; $i < count($vendors) ; $i++) { 
                    echo '<tr id="'.$vendors[$i]->ic_id.'"> <td>'.$vendors[$i]->ic_name.'</td> </tr>';
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

        $('#dealers').on('click','tr', function(e) {e.preventDefault(); window.location = "<?php echo base_url().$type.'/Vendors/edit_vendor/'; ?>" + $(this).prop('id'); });

        $('#fixed-header-drawer-exp').change(function(e) {
            $.post('<?php echo base_url().$type."/Vendors/search_vendors"; ?>', {
                'keywords' : $(this).val()
            }, function(data, status, xhr) {
                var a = JSON.parse(data), s = ""; $('#dealers > tbody').empty();
                for (var i = 0; i < a.length; i++) {
                    s+="<tr id='" + a[i].ic_id + "'><td>" + a[i].ic_name + "</td></tr>";
                }
                $('#dealers').append(s);
            }, "text");
        });

        $('#submit').click(function(e) {e.preventDefault(); window.location = "<?php echo base_url().$type.'/Vendors/add_vendor'; ?>"; }); 
    });
</script>
</html>