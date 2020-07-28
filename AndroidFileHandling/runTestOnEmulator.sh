#!/bin/bash

rootDir=$(pwd)
mkdir "$1"
cd "$(dirname "$(find -name *\\gradlew)")"
#ANDROID_SERIAL="$1" ./gradlew cAT --console=plain -Pandroid.testInstrumentationRunnerArguments.numShards="$2" -Pandroid.testInstrumentationRunnerArguments.shardIndex="$3"

#ANDROID_SERIAL="$1" ./gradlew installDebug
# installDebugAndroidTest
ANDROID_SERIAL="$1" ./gradlew connectedDebugAndroidTest

#Copies Report to the root
cp -r app/build/reports "$rootDir/$1"
