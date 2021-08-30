<?php
require_once('appvars.php')
?>
<footer class='panel-footer footer'>
    <div class='container'>
        <p>&copy; 2016 - Bob Schmitz</p>
    </div>
</footer>
<script>
$(function(){
    $('[data-toggle="tooltip"]').tooltip()
});
$(document).ready(function(){
    // Hide product filter on non-equipment pages
    var currentLocation = window.location.href;
    currentLocation = currentLocation.split("?")[0].split("#")[0];
    if (currentLocation != '<?= SITE_ROOT ?>/equipment/index.php' &&
        currentLocation != '<?= SITE_ROOT ?>/equipment/myindex.php' &&
        currentLocation != '<?= SITE_ROOT ?>/equipment/claimedindex.php') {
        $('#filterbox-container').hide();
    } else {
        $('#filterbox').focus();
    };
});
</script>
</body>
</html>
