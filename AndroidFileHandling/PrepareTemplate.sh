#!/bin/bash
# This script create a template, from the LecturerZip.zip and StudentZip,
# to be used for marking

################################################################################
################################################################################
# FUNCTIONS
################################################################################

# Deletes all files that aren't "RequiredDocuments", "PrepareTemplate.sh",
# "LecturerZip.zip" and "StudentZip.zip"
clear_files () {
  for file in $(ls)
  do
    if [ "$file" != "RequiredDocuments.txt" ] && [ "$file" != "PrepareTemplate.sh" ] && [ "$file" != "LecturerZip.zip" ] && [ "$file" != "StudentZip.zip" ];
    then
      rm -rf "$file"
    fi
  done
}

################################################################################
# Starts by clearing all files that are not Required
clear_files

# Declares Variables
textFile="RequiredDocuments.txt"
RequiredDocuments=()

# Reads in the RequiredDocuments lines from the RequiredDocuments text file and
# stores them in the RequiredDocuments array.
while IFS= read -r line
do
  RequiredDocuments[${#RequiredDocuments[@]}]="$line"
  # echo "${RequiredDocuments[NumReqDocs]}"
done < "$textFile"
echo "Required Documents Read"

# Unzip the LecturerZip.zip
unzip LecturerZip.zip >/dev/null
echo "Lecturer Project Extracted"

# Checks if all the RequiredDocuments exist and delete them
# Also stores the parent directories of the documnets
dir=""
ParentDirectories=()
for doc in ${RequiredDocuments[@]}
do
  dir=$(find -name *\\$doc)
  if [ "$dir" == "" ];
  then
    # Should terminate script
    echo "$doc doesn't exist. Please check RequiredDocuments.txt."
    clear_files
    exit 1
  else
    ParentDirectories[${#ParentDirectories[@]}]="$(dirname "$dir")"
    rm -f "$dir"
  fi
done
echo "Required Documents Deleted From Template"
echo "Required Documents Directories Extracted"

# By now the documnets in RequiredDocuments.txt have been deleted.
# Searching for them will only bring up the files in the StudentZip

# Make directory to store student Code
mkdir StudentCode

# Unzip the StudentZip.zip
unzip StudentZip.zip -d StudentCode >/dev/null
echo "Student Code Extracted"

# Checks if all the RequiredDocuments exist in the student's code and copies
# them into the template.
count=0
for doc in ${RequiredDocuments[@]}
do
  dir=$(find -name *\\$doc)
  if [ "$dir" == "" ];
  then
    # Should terminate script
    echo "$doc doesn't exist in the student's zip. Please check StudentZip.zip"
    clear_files
    exit 1
  else
    cp -R "$dir" ${ParentDirectories[$count]}
    count=$(($count + 1))
  fi
done
echo "Required Documents Copied From StudentZip"

# Removes the Student's code folder
rm -rf StudentCode

echo "Project Ready For Marking"


# basename "$filename" : strips away the previous directories
