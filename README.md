# android_marker
A moodle extension/plugin for marking student's android apps.

# Android Compiler Version 1.0

## In Conjunction with Android CLI

# Goal
> Compile and Install Android Code 


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



## Credits 
> NTU - https://www3.ntu.edu.sg/home/ehchua/programming/java/JavaUnitTesting.html

> TutorialsPoint - https://www.tutorialspoint.com/junit/


