#!/bin/sh

PROJECT="bootstrapi"

#if [ $# -eq 0 ]
if [ -z "$1" ]
then
    BUILD_NAME="local"
else
    BUILD_NAME="$1"
fi

if [ -z "$2" ]
then
    BUILD_NO="0"
else
    BUILD_NO="$2"
fi

if [ -z "$3" ]
then
    BRANCH=`git branch|grep "*"|awk '{ sub($1." ",""); print $0 }' 2>/dev/null`
else
    BRANCH="$3"
fi

BUILD_TIME=`date +%FT%T%z`
GIT_HASH=`git rev-parse HEAD`
GIT_TAG=`git symbolic-ref -q --short HEAD || git describe --tags --exact-match 2>/dev/null`

echo "{
    \"BuildTime\":\"${BUILD_TIME}\",
    \"BuildName\":\"${BUILD_NAME}\",
    \"BuildNo\":\"${BUILD_NO}\",
    \"Project\":\"${PROJECT}\",
    \"Branch\":\"${BRANCH}\",
    \"GitHash\":\"${GIT_HASH}\",
    \"GitTag\":\"${GIT_TAG}\"
}"
