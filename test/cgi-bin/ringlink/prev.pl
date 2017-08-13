#!/usr/bin/perl -T
use lib 'lib';

############################> Ringlink <############################
#                                                                  #
#  $Id: prev.pl,v 1.45 2005/02/21 17:11:41 gunnarh Exp $           #
#                                                                  #
#  Ringlink is a CGI Perl program that provides the tools          #
#  necessary to run and administer rings of websites.              #
#                                                                  #
#  Copyright © 2000-2005 Gunnar Hjalmarsson, gunnar@ringlink.org   #
#  Ringlink homepage: http://www.ringlink.org/                     #
#                                                                  #
#  Ringlink is free software; you can redistribute it and/or       #
#  modify it under the terms of the GNU General Public License as  #
#  published by the Free Software Foundation; either version 2 of  #
#  the License, or (at your option) any later version.             #
#                                                                  #
#  Ringlink is distributed in the hope that it will be useful,     #
#  but WITHOUT ANY WARRANTY; without even the implied warranty of  #
#  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the    #
#  GNU General Public License for more details.                    #
#                                                                  #
#  You should have received a copy of the GNU General Public       #
#  License along with this program; if not, write to the Free      #
#  Software Foundation, Inc., 59 Temple Place, Suite 330, Boston,  #
#  MA  02111-1307  USA                                             #
#                                                                  #
####################################################################

use CGI::Carp 'fatalsToBrowser';
BEGIN	{ CGI::Carp -> VERSION(1.20) }
use strict;

use rlmain 3.2;
use Locale::PGetText 2.0;

rlmain::execstart;
rlmain::inittests;
my $i = 0;
for (@rlmain::activesites)	{
  $i ++;
  if ($_ =~ /^\Q$rlmain::data{'siteid'}\E\t/)	{
    my @sitevalues = split (/\t/, splice (@rlmain::activesites, $i - 2, 1));
    for (@rlmain::sitenames)	{
      ${$rlmain::refs{$_}} = shift (@sitevalues);
    }
    rlmain::addrechits ($rlmain::siteid);
    rlmain::addgenhits ($rlmain::data{'siteid'});
    print "Location: $rlmain::entryURL\n\n";
    rlmain::exit();
  }
}
if (!$rlmain::data{'siteid'})	{
  $rlmain::result = '<p class="error">' . gettext("Error! You must provide a site ID.") . '</p>';
  rlmain::mainhtml();
} else	{
  rlmain::naverror();
}

