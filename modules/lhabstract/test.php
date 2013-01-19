<?php

echo ":asdasd";

$z = new erLhAbstractModelZipCode();

echo "<pre>";
$zips = $z->get_zips_in_range('97214', 5, _ZIPS_SORT_BY_DISTANCE_ASC, true); 
print_r($zips);


$zips = $z->setZipCodesInRange('97214', 5);
print_r($zips);
echo "</pre>";

echo "Pabaiga";
exit;