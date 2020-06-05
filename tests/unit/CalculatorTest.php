<?php

class CalculatorTest extends \PHPUnit\Framework\TestCase
{
  /** @test */
	public function testAddition(){
    $calculator = new \App\calculator;
    $this-> assertEquals(20,$calculator->add(5,15));
  }

 /** @test */
	public function testSubtraction(){
		$calculator = new \App\calculator;
		$this -> assertEquals(20, $calculator->subtract(25,5));
	}

  /** @test */
	public function testMultiply(){
		$calculator = new \App\calculator;
		$this -> assertEquals(20, $calculator->multiply(5,4));
	}

  /** @test */
	public function testDivide(){
		$calculator = new \App\calculator;
		$this -> assertEquals(20,$calculator->divide(40,2));
	}
}
