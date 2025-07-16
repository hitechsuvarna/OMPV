<div style="background-color:#fff; width:100vw;height:100vh; position:absolute;z-index:10;" id="loader">
    <div  style="width:50%; height:100vh; margin:50vh auto;">
        <div id="p2" class="mdl-progress mdl-js-progress mdl-progress__indeterminate" style="margin:0px auto;"></div>
    </div>
</div>

<script>
    $(document).ready(function() {
        setTimeout(function() {
            $('#page_content').fadeIn();
            $('#loader').fadeOut(1500);
        },1000);
    })
</script>