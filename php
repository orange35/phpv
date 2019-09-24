#!/usr/bin/env bash
currentDir=$(pwd)

if [ -f ".phpv" ]; then
    php=$(cat .phpv)
else
    htaccessFile=""

    for dir in "" "public/" "www/" "public_html/" ; do
        for name in ".htaccess_dev" ".htaccess"; do
            file="$currentDir/$dir$name"
            if [ -f "$file" ] ; then
                htaccessFile="$file"
                break 2
            fi
        done
    done

    if [ -f "$htaccessFile" ] ; then
        php=$(cat "$htaccessFile" | grep 'SetHandler' | grep "proxy:fcgi:\/\/php*" | head -n 1 | grep -oP 'php[\d]+')
        if [ -z "$php" ] ; then
            php=$(cat "$htaccessFile" | grep 'x-httpd-php' | head -n 1 | grep -oP 'php[\d]+')
        fi
    fi
fi
if [ -z "$php" ]; then
    if [ ! -z "$PHPV_DEFAULT" ] ; then
        php="$PHPV_DEFAULT"
    else
        php=$(which -a php | grep -v "$HOME/" | head -n 1)
    fi
fi
if [ "$0" == "$php" ] ; then
    ## avoid recursion
    php=""
fi
if [ -z "$php" ]; then
    echo "PHPV: can't find PHP CLI"
    exit 1
fi
$php "$@"
