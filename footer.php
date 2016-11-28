<?php
require_once('appvars.php')
?>
<footer class='panel-footer footer'>
    <div class='container'>
        <p>&copy; 2016 - Extreme Engineering Solutions, Inc</p>
        <p><a href='https://redmine-hardware-engineering.xes-mad.com/projects/engapps/issues/new?issue[category_id]=9' target='_blank'>Feedback</a></p>
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
