. ./set_env_vars.sh
#read in the avd name
read AVD_NAME

#avd preferences
AVD_ID=0
AVD_DEVICE="pixel"
AVD_ANDROID_API="29"
AVD_TAG="google_apis"
AVD_ABI="x86"
AVD_SDCARD_SIZE="512M"
AVD_ADDITIONAL_CONFIGS=""		#< use this to append additional options for the creation of avd
AVD_ANDROID_PACKAGE="system-images;android-$AVD_ANDROID_API;$AVD_TAG;$AVD_ABI"

#create avd
CREATE_COMMAND="avdmanager create avd --force --name $AVD_NAME --device $AVD_DEVICE  --package ${AVD_ANDROID_PACKAGE} --tag $AVD_TAG --abi $AVD_ABI --sdcard $AVD_SDCARD_SIZE $AVD_ADDITIONAL_CONFIGS"
$CREATE_COMMAND > $MARKER_LOGS/AVD.log 2> $MARKER_LOGS/AVD.log
