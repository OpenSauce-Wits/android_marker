<?php
namespace App;
class calculator{
  

  //Defining functions so that they may be tested separately
  function add($one, $two){
    return $one + $two;
  }

  function subtract($one, $two){
    return $one - $two;
  }

  function multiply($one, $two){
    return $one * $two;
  }

  function divide($one, $two){
    return $one / $two;
  }
}


