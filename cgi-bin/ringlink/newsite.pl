#!/usr/bin/perl -T
use lib 'lib';

############################> Ringlink <############################
#                                                                  #
#  $Id: newsite.pl,v 1.50 2005/02/21 17:11:41 gunnarh Exp $        #
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
use site 3.2;
use Locale::PGetText 2.0;

rlmain::execstart;
rlmain::ringlist;
if (!@rlmain::rings)	{
  $rlmain::result = rlmain::noring();
} elsif (!$rlmain::data)	{
  &selectform;
} elsif (!$rlmain::data{'ringid'})	{
  push (@rlmain::error, '<p class="error">' . gettext("Select ring ID!") . '</p>');
  &selectform;
} else	{
  rlmain::getringvalues();
  $rlmain::data{'ringtitle'} = rlmain::entify($rlmain::ringtitle);
  my $ring = gettext("Ring:");

  $rlmain::ring_site = qq~<table cellspacing="8"><tr><td><span>$ring</span></td>
<td><span><a href="$rlmain::ringURL" target="Ringlink">
$rlmain::data{'ringtitle'}</a></span></td></tr></table>~;

  if (!$rlmain::allowsiteadd)	{
    my $ringmaster = "<a href=\"$rlmain::ringURL\">" . gettext("the ringmaster") . '</a>';
    $rlmain::result = '<p class="error">' . sprintf (
      gettext("%s is configured so that the &quot;Add new site&quot; feature\nis not publicly available. You may want to contact\n%s to ask for a new site to be created."),
      $rlmain::data{'ringtitle'}, $ringmaster) . '</p>';
  } elsif ($rlmain::data{'submit'})	{
    site::validation();
    if (!@rlmain::error)	{
      site::create();
      for (@rlmain::sitenames)	{
        ${$rlmain::refs{$_}} = $rlmain::data{$_};
      }
      (my $name = $rlmain::title) =~ s/\W/_/g;
      $name .= "/$rlmain::ringid/$rlmain::siteid";
      print "Set-cookie: $name=", crypt ($rlmain::sitepw, 'pw'), "\n";
      $rlmain::pagemenu = site::menu();
      $rlmain::result = $rlmain::nomail ? rlmain::nomailtext() . "\n" : '';
      $rlmain::result .= $rlmain::addpage;
      $rlmain::action = rlmain::filenamefix('siteadmin.pl');
      $rlmain::pagetitle = gettext("Site admin");
      rlmain::adminhtml();
      rlmain::exit();
    } else	{
      $rlmain::result = site::form();
    }
  } else	{
    $rlmain::data{'entryURL'} = 'http://';
    $rlmain::data{'codeURL'} = 'http://';
    $rlmain::data{'routine'} = 'New site';
    $rlmain::result = site::form();
  }
}

$rlmain::pagetitle = gettext("Add new site");
rlmain::adminhtml;


sub selectform	{
  my $ringid = gettext("Ring ID");
  my $getform = gettext("Get form");
  rlmain::ringselect();

  $rlmain::result = qq~@rlmain::error
<form method="post" action="$rlmain::cgiURL/$rlmain::action">
<p>$ringid<br />
$rlmain::ringselect</p>
<p><input class="button" type="submit" value="$getform" /></p>
</form>~;

}

