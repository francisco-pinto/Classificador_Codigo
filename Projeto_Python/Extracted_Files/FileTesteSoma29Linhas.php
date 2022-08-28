<?php

function addFunction($num1, $num2) {
    $num1 = $num1 + $num2;
}

function addFunction2($num1, $num2) {
    $num1 = $num1 + $num2;
}

function subFunction($num1, $num2) {
    $num1 = $num1 - $num2;
}


function sum($i)
{
    $j = 1;
    $k = 2;
    $z = 2;
    $l = 0;

    addFunction($l, $k);
    addFunction2($l, $z);
    subFunction($l, $z);

    echo $i+$l;
}
?>