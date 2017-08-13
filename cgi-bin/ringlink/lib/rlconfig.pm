############################> Ringlink <############################
#                                                                  #
#  $Id: rlconfig.pm,v 1.38 2005/02/21 16:22:16 gunnarh Exp $       #
#                                                                  #
####################################################################

package rlconfig;
use strict;

sub systemvar	{

# Name of this Ringlink set-up
$rlmain::title = "Fatbirder's WebRing";

# Address (full URL) to the homepage of this Ringlink set-up
$rlmain::ringlinkURL = 'http://www.fatbirder.com/ringlink/index.html';
#$rlmain::ringlinkURL = 'http://fatbirdercom.site.securepod.com/ringlink/index.html';

# Address (full URL) to the CGI directory
$rlmain::cgiURL = 'http://www.fatbirder.com/cgi-bin/ringlink';
#$rlmain::cgiURL = 'http://fatbirdercom.site.securepod.com/cgi-bin/ringlink';

# Path to directory with files containing the number of member sites
# (should be a directory whose files can be accessed through URLs)
# Specify the full pathname, and do NOT use the $ENV{DOCUMENT_ROOT}
# variable.
#$rlmain::sitecountpath = '/home/virtual/site293/fst/var/www/html/ringlink/sitecount';
#$rlmain::sitecountpath = '/www/fatbirder/cgi-bin/ringlink';
$rlmain::sitecountpath = '/home/fbiwadm/public_html/cgi-bin/ringlink';

# Path to the directory where the ring data will be stored
# Don't change this setting if the data directory is a
# subdirectory to the CGI directory, and its name is 'data'.
# Otherwise you may need to specify the full path, e.g. like this:
#   $rlmain::datapath = '/www/htdocs/username/ringlink/data';
# Do NOT use the $ENV{DOCUMENT_ROOT} variable to set the path.
$rlmain::datapath = $rlmain::cgipath . '/data';

# Permissions of program generated files and directories
$rlmain::dirmode = 0755;
$rlmain::filemode = 0644;

# SMTP-server
# The default 'localhost' works on many servers. Otherwise you need to
# explicitly state the host name of your SMTP-server.
# The authentication values do often not need to be set. However, an
# error message like: "Local user "xxx@yyy.com" unknown on host "zzz""
# would indicate that you need to authenticate to the server. Common
# SMTP authentication protocols are 'LOGIN' and 'PLAIN'. You may need
# to consult your web hosting provider about what to enter here.
%rlmain::smtp = (
  auth    => '',		# authentication protocol
  authid  => '',		# username
  authpwd => '',		# password
);
#$rlmain::smtp{smtp} = 'localhost';

# Path to sendmail
# If $rlmain::smtp{smtp} above is disabled, e.g. through a # character
# before it, Ringlink will try to send messages via a command line
# mail program, such as sendmail, instead.
# (On Windows you need to include the file extension .exe,
# e.g. $rlmain::sendmail = 'd:/scripts/sendmail.exe';)
#$rlmain::sendmail = '/usr/lib/sendmail';
$rlmain::sendmail = '/usr/sbin/sendmail';

# Set to 0 (zero) to disallow adding of new rings via newring.pl
$rlmain::allowringadd = 1;

# Set to 0 (zero) to disable the statistics feature
$rlmain::stats = 1;

# Min number of active sites for a ring to be included in the ring list
# (this setting has an effect only if the statistics feature is enabled)
$rlmain::minsites = 3;

# Max number of rings per ring list page
# Set to 0 (zero) to display all rings on one page
$rlmain::ringspermainpage = 25;

# Default language code (ISO 639)
$rlmain::lang = 'en';

# Library for language databases. This variable should normally not
# need to be set. (For the case you encounter problems: A setting
# that typically works is  $rlmain::DBM_File = 'SDBM_File'; )
$rlmain::DBM_File = '';

# Default colors
%rlmain::colors = (
  colbg      => '#f3f3f3',
  coltablebg => '#ffffff',
  coltxt     => '#191919',
  colemph    => '#4c5ea8',
  colerr     => '#cc6600',
  collink    => '#4c5ea8',
  colvlink   => '#4c5ea8'
);

%rlmain::leftpanecolors = (
  tablebg    => '#d9eafb',
  txt        => '#000000',
  link       => '#4c5ea8',
  vlink      => '#4c5ea8'
);

# Max number of characters in ring description
$rlmain::numcharringdesc = 400;

# Max number of characters in site description
$rlmain::numcharsitedesc = 250;

# If you want that the output from the admin routines is printed via
# an external program, for instance a PHP script, you can set this
# variable to the URL of the external script.
# If not, the variable shall be empty.
$rlmain::extappURL = '';

# Max number of sites that can be processed simultaneously when
# running the checker. Making use of this feature should
# significantly reduce the time it takes to check all the sites in
# a larger ring. The value 0 (zero) disables the feature, and makes
# the program check one site at a time.
# This setting also affects the speed of Reset_stats() in admin.pl
# and updateringstats() in rlmain.pm.
$rlmain::max_processes = 10;

$rlmain::adminname = 'fatbirder';
$rlmain::adminemail = 'bo@fatbirder.net';
$rlmain::adminpw = 'H0rnb1ll';

}

1;

