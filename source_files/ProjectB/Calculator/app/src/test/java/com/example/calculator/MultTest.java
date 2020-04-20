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
public class MultTest{

    private int a;
    private int b;
    private int expAnswer;

    public MultTest(int a,int b,int expAnswer){
        this.a = a;
        this.b = b;
        this.expAnswer = expAnswer;
    }


    @Parameterized.Parameters
    public static Collection add(){
        return Arrays.asList(new Object[][]{
                {1,1,1},
                {2,1,2},
                {4,2,8},
                {10,5,50}
        });
    }


    @Test
    public void testMult(){
        System.out.println("Parameterized: " + expAnswer);
        assertTrue(expAnswer == Calculator.multiply(a,b));
    }

}
