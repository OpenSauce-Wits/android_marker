--TEST--
calc() function - A basic test to see if it works. :)
--FILE--
<?php
include 'calculator.php'; // might need to adjust path if not in the same dir
$f = 5;
$s = 5;
$o = '*';
var_dump(calc($f, $s, $o));
?>
--EXPECT--
string(11) "The answer is: 25"

//https://web.archive.org/web/20180627160032/http://www.simpletest.org/en/first_test_tutorial.html
