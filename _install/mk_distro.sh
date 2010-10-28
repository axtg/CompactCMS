#! /bin/bash

pushd .
DSTDIR=`pwd`
echo DSTDIR = ${DSTDIR}

cd ../
tar czf /tmp/ccms.tar.gz ./

cd /tmp
pwd
rm -rf ccms-1
mkdir ccms-1
cd ccms-1
tar xzf ../ccms.tar.gz
rm ../ccms.tar.gz
pwd

rm -rf .git/

cd _install
chmod 0777 *.sh
./fix_chmod.sh

cd ..

tar cjf ${DSTDIR}/compactcms-svn.tar.bz2 ./

popd

