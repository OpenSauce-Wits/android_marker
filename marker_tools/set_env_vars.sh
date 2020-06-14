#marker things
export MARKER_TOOLS=/var/marker_tools
export PATH=$MARKER_TOOLS:$PATH
export MARKER_DATA=/var/marker_data
export PATH=$MARKER_DATA:$PATH
export MARKER_LOGS=$MARKER_DATA/logs
export PATH=$MARKER_LOGS:$PATH

#android home
export ANDROID_HOME=$MARKER_TOOLS/android-sdk-linux
export PATH=$ANDROID_HOME/tools:$PATH
export PATH=$ANDROID_HOME/tools/bin:$PATH
export PATH=$ANDROID_HOME/platform-tools:$PATH
export PATH=$ANDROID_HOME/emulator:$PATH

#android sdk root
export ANDROID_SDK_ROOT=$ANDROID_HOME
export PATH=$ANDROID_SDK_ROOT:$PATH

#gradle
export GRADLE_HOME=$MARKER_TOOLS/gradle/gradle-6.2.2
export PATH=$GRADDLE_HOME/bin:$PATH

#virtual device/emulator
export ANDROID_AVD_HOME=$MARKER_TOOLS/.android/avd
export PATH=$ANDROID_AV_HOME:$PATH
