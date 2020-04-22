# android_marker 
<a href="https://travis-ci.com/github/OpenSauce-Wits/android_marker/builds/161445112"><img src="https://travis-ci.com/OpenSauce-Wits/android_marker.svg?branch=master"></a>
A moodle extension/plugin for marking student's android apps.

# Android Compiler Version 1.0

## In Conjunction with Android CLI

# Goal
> Compile and Install Android Code 

# Requirements for development
1. Android logic code that solves test cases
 * Copy src folder
2. Android Testing
 * ProjectB - Name the entire project folder "ProjectB"
 

# Assumptions
1. Moodle server that collects files
2. Android Headless Emulator

# The Rationale 
Let's assume student A writes some logic code
Lecturer B will create his own activity and create test cases
B will upload project files for A to develop, however will remove test cases.
***
The Program assumes that A has already submitted his logic

> The program will copy the contents of the logic code and save over B's file.


## Run the program
> The argument is the name of the project

`./compile.sh Calculator`

## Contents

* Android Calculator Code (src)
* Android Unit Test (Project B)
* Bash Script

## Future Requirements
* Version management
  1. Java version
  2. Android SDK
* Gradle Build UI/Instrumentation tests
* Collect outputs from the "outputs" directory
* Run php file to submit/retrieve zip files


## Notes
This is the first version. Future releases will emcompass more tests and regulations.
Criticism and critical analysis of shortfalls is critical to the development of the program.
It is also imperative to upload android code in the manner demonstrated in this example



## Credits 
> NTU - https://www3.ntu.edu.sg/home/ehchua/programming/java/JavaUnitTesting.html

> TutorialsPoint - https://www.tutorialspoint.com/junit/


