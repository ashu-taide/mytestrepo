#!/usr/bin/env bash
source /home/local/.bashrc_pipelines
file_input="container-versions.log"
while IFS='|' read -r pkg version cmd
do
  [[ $pkg =~ ^#.* ]] && continue
  # PHP extensions
  if ! [[ $pkg =~ (php([0-9].[0-9])? extension version (.*)) ]];
  then
    if [ -z "$version" ];
    then
      php_version=$(echo "$pkg" | perl -wlne 'print $2 if /((php(\d.\d)?) extension (.*))/' | head -n1)
      package=$(echo "$pkg" | perl -wlne 'print $4 if /((php(\d.\d)?) extension (.*))/' | head -n1)
      $SOURCE_DIR/check-versions.sh "$php_version $package extension" "$(php -m)" "$package"
    else
      if [ -z "$cmd" ]; then cmd="$pkg --version"; fi
      eval '$SOURCE_DIR/check-versions.sh "'$pkg'" "$('$cmd')" "'$version'"'
    fi
  fi
done <"$file_input"

