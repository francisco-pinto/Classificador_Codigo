<?php

function addFunction($num1, $num2) {
    $num1 = $num1 + $num2;
}

function sum($i)
{
    $j = 1;
    $k = 2;
    $z = 1;
    $l = 0;

    addFunction($l, $k);

    $l = $l + $z;

    echo $i+$l;
}
?>