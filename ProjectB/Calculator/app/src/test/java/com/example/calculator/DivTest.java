package com.example.calculator;

import org.junit.*;
import static org.junit.Assert.*;
import org.junit.runners.Suite;
import java.util.Arrays;
import java.util.Collection;
import org.junit.runners.Parameterized;
import org.junit.runners.Parameterized.Parameters;
import org.junit.runner.RunWith;

import java.util.Collections;

@RunWith(Parameterized.class)
public class DivTest{

    private int a;
    private int b;
    private int expAnswer;

    public DivTest(int a,int b,int expAnswer){
        this.a = a;
        this.b = b;
        this.expAnswer = expAnswer;
    }


    @Parameterized.Parameters
    public static Collection add(){
        return Arrays.asList(new Object[][]{
                {1,1,1},
                {2,1,2},
                {4,2,2},
                {10,5,2}
        });
    }


    @Test
    public void testDiv(){
        System.out.println("Parameterized: " + expAnswer);
        assertTrue(expAnswer == Calculator.divide(a,b));
    }

}