#! /bin/bash

# The arguments are tool name, version string, and expected version.
grep $3 <<< $2 >/dev/null 2>&1;
rc=$?; 

if [[ $rc != 0 ]]; 
  then echo "Version check failed for ${1}. Expected ${3}, got ${2}.";
  exit 1; 
else
  echo "Version check completed successfully for ${1}. Version: ${3}.";
  exit 0;
fi

