#!/bin/bash

## We have to create SDK checks and warning mitigation


## Merge with installation files

echo "Extracting Folder Files"
unzip "src.zip"
root_dir=$(pwd)
echo "${root_dir}"
proj_root="${root_dir}/ProjectB"
to_dir="${proj_root}/${1}/app/src"
if [ -d "${to_dir}" ];then
	if [ -d "${root_dir}/src" ];then
		src_dir="${root_dir}/src"
		cp -r -f "${src_dir}/main" "${to_dir}"
		echo "Files have been successfully copied"
		cd "${proj_root}/${1}"
		

		# Run Gradle Tests
		# The Gradle Build will generated outputs that have "marked" the code
		./gradlew clean build
		echo "Successfully cleaned the build ...."
		./gradlew test






		# Run Instrumentation Tests
		# For UI
		# This is where the Headless Server Comes In

		# We will run this command on the remote server
		# $ adb shell am start -n “package/package.MainActivity” -a android.intent.action.MAIN -c android.intent.category.LAUNCHER
		# This will run an emulator - but we will run a headless one to save memory

		# Collect the tests from outputs
		# ./gradlew connectedTest
		# We are going to launch and view our UI results
		#testReleaseInstrumentationTests


		# We are going to launch and view our results
		report_dir="${proj_root}/${1}/app/build/reports/tests/testReleaseUnitTest"
		cd "${report_dir}"
		firefox "${report_dir}/index.html"




		# Send the test output back from the remote server to the moodle uploader
		# Do this using php

	else
		echo "The source folder does not exist...Please check your folder"
	fi
else
	echo "The current folder does not exist"
fi
