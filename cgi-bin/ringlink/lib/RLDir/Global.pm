# $Id: Global.pm,v 1.6 2003/05/29 12:32:52 gunnarh Exp $

package ring;

use strict;
use Locale::PGetText;

sub RLDir {
my ($dirname, $mainURL, $submitURL, $intro, %forminput);

=pod

Through this file, your Ringlink set-up offers a convenient way to
submit a webring to the global Ringlink Webring Directory. The file
can also be used as a template for similar files, making it possible
to submit to one or more further directories using the same method.

For instance, if you want to make it easier to submit a ring to
a local directory, you can
- make a copy of this file that you name [localname].pm
- change the variables below
- upload the new file to [Ringlink library]/RLDir/

=cut

$dirname     = 'Ringlink Webring Directory';
$mainURL     = 'http://ringdir.ringlink.org/';
$submitURL   = 'http://ringdir.ringlink.org/cgi-bin/add.cgi';

$intro = gettext("The Ringlink Webring Directory is a global directory,\nmaintained by the Ringlink developers. All submissions\nare validated before the rings appear in the actual\ndirectory. Certain kinds of rings, such as rings\npromoting adult or pornographic content or illegal\nactivities, won't be accepted for listing.");

%forminput = (

#  Information to be prefilled:
#  Name                Value
#  ====                =====
   Title           =>  $rlmain::ringtitle,
   URL             =>  $rlmain::ringURL,
   Description     =>  substr ($rlmain::ringdesc, 0, 300),
   'Ring Manager'  =>  $rlmain::rmname,
   'Ring Email'    =>  $rlmain::rmemail,
   'Host System'   =>  $rlmain::title,
   'Host Url'      =>  $rlmain::ringlinkURL,
   'Host Email'    =>  $rlmain::adminemail,
   'Ring ID'       =>  $rlmain::ringid,
);

# The names above are names of elements in the HTML form
# for submitting a webring to the directory.

return ($dirname, $mainURL, $submitURL, $intro, %forminput);
}

1;

