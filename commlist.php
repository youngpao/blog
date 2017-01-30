<?php
require('./lib/init.php');
$sql="select *from comment";
$comms=mGetALL($sql);

require(ROOT.'/view/admin/commlist.html');

?>