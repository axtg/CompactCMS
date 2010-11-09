#! /bin/bash
#    
# See also:
#   http://community.compactcms.nl/forum/index.php/topic,170.0.html
#
# Handy if you're editing CCMS like me and want to send .tar.bz2 files up 
# and down instead of sets of (edited) files: this script creates a 
# 'release equivalent' CCMS .tar.bz2 archive.
#

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

