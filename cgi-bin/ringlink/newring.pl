#!/usr/bin/perl -T
use lib 'lib';

############################> Ringlink <############################
#                                                                  #
#  $Id: newring.pl,v 1.53 2005/02/21 17:11:41 gunnarh Exp $        #
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
use ring 3.2;
use Locale::PGetText 2.0;

rlmain::execstart;
if (!$rlmain::allowringadd)	{
  my $admin = "<a href=\"$rlmain::ringlinkURL\">" . gettext("the administrator") . '</a>';
  $rlmain::result = '<p class="error">' . sprintf (
    gettext("%s is configured so that the &quot;Add new ring&quot; feature\nis not publicly available. You may want to contact\n%s to ask for a new ring to be created."),
    $rlmain::title, $admin) . '</p>';
} elsif ($rlmain::data{'submit'})	{
  ring::validation();
  if (!@rlmain::error)	{
    ring::create();
    for (@rlmain::ringnames)	{
      ${$rlmain::refs{$_}} = $rlmain::data{$_};
    }
    (my $name = $rlmain::title) =~ s/\W/_/g;
    print "Set-cookie: $name/$rlmain::ringid=", crypt ($rlmain::ringpw, 'pw'), "\n";
    rlmain::setlang ($rlmain::ringlang);
    $rlmain::pagemenu = ring::menu();
    my $newtitle = "</p><p style=\"font-weight: bold\">$rlmain::ringtitle</p><p class=\"success\">";
    $rlmain::result = '<p class="success">' . sprintf (
      gettext("The ring %s was successfully created."), $newtitle) . '</p>';
    $rlmain::action = rlmain::filenamefix('ringadmin.pl');
    $rlmain::pagetitle = gettext("Ring admin");
    rlmain::adminhtml();
    rlmain::exit();
  } else	{
    $rlmain::result = ring::form();
  }
} else	{
  for (keys %rlmain::colors)	{
    $rlmain::data{$_} = ${$rlmain::refs{$_}};
  }
  $rlmain::data{'ringURL'} = 'http://';
  $rlmain::data{'logoURL'} = 'http://';
  $rlmain::data{'allowsiteadd'} = 'on';
  $rlmain::data{'ringlang'} = $rlmain::lang;
  $rlmain::data{'sitesperlistpage'} = 25;
  $rlmain::data{'routine'} = 'New ring';
  $rlmain::result = ring::form();
}

$rlmain::pagetitle = gettext("Add new ring");
rlmain::adminhtml;

