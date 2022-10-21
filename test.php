<?php
echo $a = '0002190003102547=000000000000000';
echo '<BR>';
$fcardno = preg_replace('/(;|\?)/s', '', $a);

echo $cardno = substr($fcardno, 3, 12);

?>