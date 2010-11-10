#! /bin/bash

#
# scan the entire CCMS codebase and create a file listing all the language text items 
# (which expect translation, of course).
#
# next, compare this list with the existing translation files and add the missing entries
# to them, where applicable.
#
# See also:
#   http://community.compactcms.nl/forum/index.php/topic,170.0.html
#
# The 'collect_lang_items.sh' script is handy to test/update the language files in 
# ./lib/languages/ : it scans the entire CCMS source tree and detects which 
# $ccms['lang'][etc.etc.] multilingual item references exist in there, then checks 
# this set against each of the language files, e.g. lib/languages/de.inc.php and 
# writes a code block like shown below to that file, so the translator can see which 
# entries still must be done.
# It's also handy as a semi-auto way to update language files while the CCMS is further 
# developed (and possibly new $ccms['lang'][...] entries are introduced.
#

#
# first, collect all the source files:
#

# we assume we're starting from the _install directory.
# ... we can dump temp files in there as, after installation, the entire directory
#     is to be 'rm -rf'-ed anyhow, so we won't clutter the CCMS code tree, while
#     we run this or other UNIX shell scripts during development.
pushd ./

echo collecting data...

# the pipe by lines:
#   1: list all files, except the language files themselves of course.
#      also ignore any cache or media files: those are there for download/use
#      only and are NOT part of the CCMS code (if /media files are, we've a bother anyway)
#   2: grep all the code lines which have 1 OR MORE language text references in them
#   3: chop lines up before the $ and after several combos at the end of a ccms[lang][x][y]
#      statement as they exist out there. This is written so that we NOT require ccms[a][b][c]
#      triple index levels; it's rather more flexible.
#      UNFORTUNATELY, some $ccms['lang'] entries have PHP code in their index, so those get
#      corrupted. Currently this applies to ['lang']['menu'][$coded_index_something] only.
#      ALSO split at the ':' at the end of the filepath printed by grep.
#   4: take this newline-riddled feed and extract the only two things we want to hear about:
#      /where/, i.e. the filepaths which grep reported, and the $ccms[] entries themselves
#   5: gawk will 'fake' those nuked ['lang]['menu'][$coded!] entries, plus make sure
#      each entry gets prefixed by the filepath where it was dup up by grep.
#      Doing it in this multistage pipeline ensures this keeps working for code lines with
#      MULTIPLE $ccms['lang'] references!
#      BTW: The qt=39 + %c thingy is necessary because getting single quotes in a single-quote
#      delimited awk inline script turned ugly; this was the easier cop-out there.
#   6: standard phrase to uniquify the output and dump it to a log file, where we like to
#      see each line with both path and $ccms['lang'] entry.
#   7: next, we're stripping away those ../path/file: prefixes as we only want the $ccms['lang']
#      entries by now.
#   8: again uniquify them and dump to file. THIS list will be used to check the /lib/languages/*
#      files against!

find ../ -type f -a ! -path '*/lib/languages/*' \
		-a ! -path '*/media/*' -a ! -path '*/includes/cache/*' -a ! -path '*.sh' \
		-a ! -path '*.bak'  -a ! -path '*~' -print0 \
	| xargs -0 grep -e "\$ccms\['lang'\]" \
	| sed -e 's/\$/\n\$/g' -e 's/\:/:\n/g' -e 's/;/\n/g' -e 's/\]\./\]\n/g' -e 's/\]:/\]\n/g' -e 's/)/\n/g' -e 's/ /\n/g' \
	| grep -e "^\(\$ccms\['lang'\].*\]\)$\|^\(../[.a-zA-Z0-9_/-]\+\:\)$" \
	| gawk -- 'BEGIN { qt=39; for (i=1; i<=5; i++) { printf("../--manually-added--/:\t$ccms[%clang%c][%cmenu%c][%c%d%c]\n", qt, qt, qt, qt, qt, i, qt); } } /:/ { path=$0; next; } /./ { printf("%s\t%s\n", path, $0); }' \
	| sort | uniq | tee ccms_lang_entries_log.txt \
	> ccms_lang_entries.txt.tmp
	
# also find all template references to '{%lang:xyz:abc%}' template args as those are language items as well:
# these are the template-language equivalent of $ccms['lang']['xyz']['abc']
find ../lib/templates -type f -print0 \
	| xargs -0 grep -e "\{%lang\:" \
	| sed -e 's/{%/\n\$ccms\[/g' -e 's/\:/\]\[/g' -e 's/%}/\]\n/g' \
	| grep -e "^\(\$ccms\['lang'\].*\]\)$\|^\(../[.a-zA-Z0-9_/-]\+\:\)$" \
	| gawk -- ' /:/ { path=$0; next; } /./ { printf("%s\t%s\n", path, $0); }' \
	| sort | uniq | tee -a ccms_lang_entries_log.txt \
	>> ccms_lang_entries.txt.tmp

# marge both and turn them into one	
cat ccms_lang_entries.txt.tmp \
	| sed -e 's/^.*:\t//' \
	| sort | uniq \
	> ccms_lang_entries.txt
	


 
# now get the language files and check each to see whether we're having missing and/or superfluous items in there.

# (arg1: existing file, arg2: collected list)
function check_file
{
	echo checking $1

	# process:
	#   1: strip old check block at the end of the language file, WHEN such a block exists in there already.
	#      NOTE that such a block is a PHP comment and hence entirely harmless; you can run the script
	#           and decide to fix it later.
	#   2: pull a diff to see which $ccms['lang'] entries are in the language file and which aren't.
	#   3: extract the obsolete (superfluous) and subsequently all missing entries from said diff and
	#      dump those into a fresh PHP comment report block at the end of the language file (that is, the
	#      cleaned up local copy; when finished the local copy replaces the original language file and we're
	#      done.
	#
	# The 'sed' line following the awk one is from:
	#   http://sed.sourceforge.net/sed1line.txt
	# --> delete all trailing blank lines at end of file  # works on all seds
	#
	cat $1 \
	| gawk -- 'BEGIN { ok=1; } END { } /^\?>/ { ok=0; next; } /### OBSOLETED ENTRIES ###/ { ok=0; next; }   { if (ok) { printf("%s\n", $0); } }' \
	| sed -e :a -e '/^\n*$/{$d;N;ba' -e '}' \
	| tee lang.$$.php.tmp \
	| sed -e 's/=/\n/g' \
	| grep -e "\$ccms\['lang'\]" \
	| sort | uniq \
	> lang_list.$$.tmp

	diff -dwb -U 0 lang_list.$$.tmp $2 \
	> lang_diff.$$.tmp

	
	cat >> lang.$$.php.tmp <<EOF


      /* ### OBSOLETED ENTRIES ### */
      /*
         Please check the CompactCMS code to:

         a) make sure whether these entries are indeed obsoleted.
            When yes, then the corresponding entry above should be
            removed as well!

         b) When no, i.e. the entry exists in the code, this merits
            a bug report regarding the $0 script.
       
         ----------------------------------------------------------
	
EOF
	
	cat lang_diff.$$.tmp \
	| grep -e '^-\$' \
	| sed -e 's/^-/\t/' \
	>> lang.$$.php.tmp
	
	cat >> lang.$$.php.tmp <<EOF
       
         ----------------------------------------------------------
	
         ### MISSING ENTRIES ###

         The entries below have been found to be missing from this 
         translation file; move them from this comment section to the
         PHP code above and assign them a suitable text.

         When done so, you can of course remove them from the list 
         below.
       
         ----------------------------------------------------------
      */
	  
EOF

	# now pull the missing entries' text from the English language file, IFF they exist there.
	# When they don't, insert them anyhow, but put a dummy text assignment with them too.
	#
	# for some odd reason the piped command doesn't work backqquoted in the 'for in' statement 
	# itself, so we use a second temp file here. :-(
	
	cnt=0
	for t in $( cat lang_diff.$$.tmp | grep -e '^+\$' | sed -e 's/^+/\t/' ) ; do
		let "cnt = $cnt + 1"
		line="$(grep -F -- "$t" ../lib/languages/en.inc.php)"
		if test -z "$line" ; then
			var="$( echo $t | sed -e 's/\$//' )"
			echo "$t = \"Report this to the CompactCMS developers: unknown text for $var\";" >> lang.$$.php.tmp
		else
			echo "$line" >> lang.$$.php.tmp
		fi
	done
	
	if test $cnt = 0 ; then
		echo "     (ALL required entries are here. Congratulations!)"
	else
		echo "     ($cnt missing entries have been added; please have those translated)"
	fi
	
	cat >> lang.$$.php.tmp <<EOF
       
      /*
         ----------------------------------------------------------
      */
	  
?>
EOF

	# replace the original:
	cat lang.$$.php.tmp \
		| sed -e :a -e '/^\n*$/{$d;N;ba' -e '}' \
		| sed -e :a -e 's/\?>\n/?>/' \
		> $1
}
 

for f in $( find ../lib/languages/ -type f -name '*.inc.php' -print ) ; do
  check_file $f ccms_lang_entries.txt
done

# remove temp files:
rm lang_diff.$$.tmp
rm lang_list.$$.tmp
rm lang.$$.php.tmp
rm ccms_lang_entries.txt.tmp
rm ccms_lang_entries.txt
# keep the ccms_lang_entries_log.txt

popd

