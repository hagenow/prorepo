#! /bin/sh
#------------------------------------------------------------------------------------
# Copyright Lom M. Hillah - Date: April 25, 2010 - Version: 0.3 
# License: GNU GPL v3. See http://www.gnu.org/licenses/
#
# Invocation: ./validatePNMDocument.sh pnmlFile1 [pnmlFile2 pnmlFile3 ...]
# 
# This script uses xmllint to validate your PNML Documents. It also uses perl. 
# I thus assume you already have libxml2 (and perl) installed on your machine.
# I also assume you have Internet connectivity, because the PNML grammars are
# fetched over the Internet from http://www.pnml.org
# The validation results are output in $HOME/pnmlValidation/pnmlValidationReport.html
#------------------------------------------------------------------------------------

REPORTFILE=schemas/pnmlValidationReport.html
TITLE="PNML Document(s) validation report"

[ $# -eq 0 ] && echo "Usage: $0 pnmlFile1 [pnmlFile2 pnmlFile3 ...]" && exit 1

# Prepare report file
echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>" > $REPORTFILE
echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\" lang=\"en\">
<head>
<meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\" />
<title>$TITLE</title>
<style type=\"text/css\" media=\"screen\">
* {
margin: 15;
padding: 15;
}

body{
background: #efefef;
color: #303030;
font-family: Helvetica, sans-serif;
font-size: 15px;
line-height: 150%;
margin-left: 25px;
margin-top: 30px;
margin-right: 25px;
margin-bottom: 30px;
word-wrap: break-word;	/* IE */
}

.reportSection {
-webkit-border-bottom-left-radius: 6px;
-webkit-border-bottom-right-radius: 6px;  
-webkit-border-top-left-radius: 6px; 
-webkit-border-top-right-radius: 6px;
background-color: rgb(240, 243, 252);
border-bottom-color: rgb(198, 209, 237);
border-bottom-style: solid;
border-bottom-width: 1px;
border-left-color: rgb(198, 209, 237);
border-left-style: solid;
border-left-width: 1px;
border-right-color: rgb(198, 209, 237);
border-right-style: solid;
border-right-width: 1px;
border-top-color: rgb(198, 209, 237);
border-top-style: solid;
border-top-width: 1px;
display: block;
margin-bottom: 4px;
margin-left: 25px;
margin-right: 25px;
margin-top: 0px;
overflow-x: hidden;
overflow-y: hidden;
padding-bottom: 10px;
padding-left: 20px;
padding-right: 20px;
padding-top: 7px;
position: relative;
}

#reportTitle{
text-align: center;
background-color: #5279e8;
color: #ffffff;
display: block;
height: 40px;
margin-top: 0px;
margin-bottom: 0px;
padding-bottom: 0px;
padding-top: 18px;
width: 100%
position: absolute;
}
</style>

</head>
<body>
	
<h1 id=\"reportTitle\">$TITLE</h1>
<p><em>Session of  `date` </em></p>
<p>&nbsp;</p>" >> $REPORTFILE

for f in "$@"
do
	# Extracts the URL of the grammar
	GRUL=`grep "type=" $f | awk -F "=\"" '{print $3}' | sed -e "s/\">/.pntd/g"`
	BASENAME=`basename $f`
	echo "<div class=\"reportSection\">
	<h2>Validation result for <a href=\"$BASENAME\">$BASENAME</a></h2>
	<p>" >> $REPORTFILE
	xmllint $f --noout --relaxng $GRUL 2>&1 |\
	perl -pe 's|^((?:</?[^>]+>)*)(.*?):(\d+):(.*error.*)|$1 <strong>on line $3</strong> : $4<br/>|' |\
	xargs echo >> $REPORTFILE
	echo "</p>
	</div>" >> $REPORTFILE
done

echo "</body>
</html>" >> $REPORTFILE
