package com.example.calculator;

public class Calculator {
    public static double add(double num1,double num2){
        return num1 + num2;
    }

    public static double subtract(double num1,double num2){
        return num1 - num2;
    }

    public static double multiply(double num1,double num2){
        return num1 * num2;
    }

    public static double divide(double num1,double num2){
        if(num2 == 0){
            throw new IllegalArgumentException("Cannot divide by 0");
        }
        return num1/num2;
    }
}
