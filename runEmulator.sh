#!bin/bash
`cd ~`
if [ -d "/opt" ]
then
	if [ -d "/opt/mc/sdk/tools" ]
	then
		cd /opt/mc/sdk/tools
		echo $PWD
		./emulator -avd Nexus_5X_API_26
	else
		echo "You dont have the /opt/mc/sdk/tools directory!"
	fi

else
	echo "You don't have the /opt directory!"
fi
