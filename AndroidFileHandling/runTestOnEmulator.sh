#!/bin/bash

rootDir=$(pwd)

# Makes the directory where the report will be stored
mkdir "$1"

# Changes to the directory with the gradlew file
cd "$(dirname "$(find -name *\\gradlew)")"

# Marks a shard on a device
ANDROID_SERIAL="$1" ./gradlew connectedDebugAndroidTest -Pandroid.testInstrumentationRunnerArguments.numShards="$2" -Pandroid.testInstrumentationRunnerArguments.shardIndex="$3"

#Copies Report to the root
cp -r app/build/reports "$rootDir/$1"
