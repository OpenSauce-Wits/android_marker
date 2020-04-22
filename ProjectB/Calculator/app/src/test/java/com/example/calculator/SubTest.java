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
public class SubTest{

    private int a;
    private int b;
    private int expAnswer;

    public SubTest(int a,int b,int expAnswer){
        this.a = a;
        this.b = b;
        this.expAnswer = expAnswer;
    }


    @Parameterized.Parameters
    public static Collection add(){
        return Arrays.asList(new Object[][]{
                {1,1,0},
                {2,1,1},
                {4,2,2},
                {10,5,5}
        });
    }


    @Test
    public void testSub(){
        System.out.println("Parameterized: " + expAnswer);
        assertTrue(expAnswer == Calculator.subtract(a,b));
    }

}
