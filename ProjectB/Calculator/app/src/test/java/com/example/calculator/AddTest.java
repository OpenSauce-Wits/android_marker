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
public class AddTest{

    private int a;
    private int b;
    private int expAnswer;

    public AddTest(int a,int b,int expAnswer){
        this.a = a;
        this.b = b;
        this.expAnswer = expAnswer;
    }


    @Parameterized.Parameters
    public static Collection add(){
        return Arrays.asList(new Object[][]{
                {1,1,2},
                {1,2,3},
                {3,5,8},
                {10,12,22},
                {10,-10,0}
        });
    }


    @Test
    public void testAdd(){
        assertTrue("Answer was " + expAnswer,expAnswer == Calculator.add(a,b));
    }

}