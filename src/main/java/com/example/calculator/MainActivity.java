
package com.example.calculator;

import androidx.appcompat.app.AppCompatActivity;

import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;

public class MainActivity extends AppCompatActivity implements View.OnClickListener{
    private static final String TAG = "MainActivity";
    @Override
    public void onClick(View v) {


        double num1 = Double.parseDouble(number1.getText().toString());
        double num2 = Double.parseDouble(number2.getText().toString());
        double value = 0;
        switch (v.getId()) {

            case R.id.add:
                Log.d(TAG, "onClick: Adding... ");
                value = Calculator.add(num1,num2);
                break;
            case R.id.subtract:
                Log.d(TAG, "onClick: Subtracting...");
                value = Calculator.subtract(num1,num2);
                break;
            case R.id.divide:
                Log.d(TAG, "onClick: Dividing...");
                value = Calculator.divide(num1,num2);
                break;
            case R.id.multiply:
                Log.d(TAG, "onClick: Multiplying ...");
                value = Calculator.multiply(num1,num2);
                break;

        }
        textView.setText(String.valueOf(value));
    }

    /**
     * Logic Code
     * @param savedInstanceState
     */


    private EditText number1;
    private EditText number2;
    private Button add;
    private Button subtract;
    private Button divide;
    private Button multiply;
    private TextView textView;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        number1 = findViewById(R.id.edtText1);
        number2 = findViewById(R.id.edtText2);
        textView = findViewById(R.id.text);
        add = findViewById(R.id.add);
        add.setOnClickListener(this);

        subtract = findViewById(R.id.subtract);
        subtract.setOnClickListener(this);

        divide = findViewById(R.id.divide);
        divide.setOnClickListener(this);


        multiply = findViewById(R.id.multiply);
        multiply.setOnClickListener(this);


    }
}
