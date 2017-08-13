#!/usr/bin/perl -T
use lib 'lib';

############################> Ringlink <############################
#                                                                  #
#  $Id: siteadmin.pl,v 1.58 2005/02/21 17:11:41 gunnarh Exp $      #
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
  $rlmain::pagetitle = gettext("Site admin");
  $rlmain::result = rlmain::noring();
} elsif (!$rlmain::data)	{
  &loginform;
} elsif (!$rlmain::data{'ringid'})	{
  push (@rlmain::error, '<p class="error">' . gettext("Select ring ID!") . '</p>');
  &loginform;
} else	{
  rlmain::getringvalues();
  rlmain::sitelist();
  my $siteexists = 0;
  for (@rlmain::sites)	{
    if ($rlmain::data{'siteid'} eq $_)	{
      $siteexists = 1;
      last;
    }
  }
  if (!@rlmain::sites)	{
    push (@rlmain::error, '<p class="error">'
    . gettext("No site has been added to this ring yet.") . '</p>');
    &loginform;
  } elsif (!$rlmain::data{'siteid'})	{
    unless ($ENV{'REQUEST_METHOD'} eq 'GET')	{
      push (@rlmain::error, '<p class="error">' . gettext("Enter site ID!") . '</p>');
    }
    &loginform;
  } elsif (!$siteexists)	{
    push (@rlmain::error, '<p class="error">'
    . gettext("Can't find entered site ID in ring, please try again.") . '</p>');
    &loginform;
  } else	{
    rlmain::getsitevalues();
    die "Error! Can't get registered password." if !$rlmain::sitepw;
    if ($rlmain::data{'emailpw'})	{
      my $pwintro = gettext("This is the registered password:");
      my $title = gettext("Site title:");
      $title = $title . ' ' . ' ' x (15 - length($title)) . $rlmain::sitetitle;
      my $id = gettext("Site ID:");
      $id = $id . ' ' . ' ' x (15 - length($id)) . $rlmain::siteid;
      my $pw = gettext("Password:");
      $pw = $pw . ' ' . ' ' x (15 - length($pw)) . $rlmain::sitepw;
      my $fromtitle = rlmain::nameclean($rlmain::ringtitle);
      (my $rmemail = $rlmain::rmemail) =~ s/,.+//;

      my $body = qq~$pwintro

	$title
	$id

	$pw

$rlmain::ringtitle
$rlmain::rmname
$rlmain::ringURL
~;

      rlmain::email (
        $rlmain::wmemail,
        '',
        "$fromtitle <$rmemail>",
        gettext("Site password"),
        $body
      );
      push (@rlmain::error, $rlmain::nomail ? rlmain::nomailtext() : '<p class="success">'
       . gettext("The password was sent, check your mail.") . '</p>');
      &loginform;
    } elsif (!rlmain::pwcheck ($rlmain::sitepw, $rlmain::ringid, $rlmain::siteid))	{
      if (!$rlmain::data{'pw'})	{
        push (@rlmain::error, '<p class="error">' . gettext("Enter password!") . '</p>');
      } else	{
        push (@rlmain::error, '<p class="error">' . gettext("Incorrect password, please try again.") . '</p>');
      }
      &loginform;
    } elsif (!$rlmain::data{'routine'})	{
      $rlmain::pagemenu = site::menu();
      $rlmain::result = '<p class="success">' . gettext($rlmain::data{'result'}) . '</p>';
    } else	{
      $rlmain::data{'routine'} = $rlmain::routines{$rlmain::data{'routine'}};
      (my $routine = $rlmain::data{'routine'}) =~ tr/ /_/;
      &{\&$routine};
    }
  }
}

rlmain::adminhtml;


sub loginform	{
  my $login = gettext("Login");
  my $ringid = gettext("Ring ID");
  my $siteid = gettext("Site ID");
  my $pw = gettext("Site password");
  my $cookies = gettext("&quot;cookies&quot; must be enabled");
  my $loginbutton = gettext("Log in");
  my $pwtext = gettext("Forgotten your password? Click below to have it sent to the\nwebmaster email address for the entered site.");
  my $pwbutton = gettext("Send password");
  $rlmain::pagetitle = gettext("Site admin");
  rlmain::ringselect();
  $rlmain::data{'siteid'} = '' if !$rlmain::data{'siteid'};

  $rlmain::result = qq~<h4>$login</h4>
@rlmain::error
<form method="post" action="$rlmain::cgiURL/$rlmain::action">
<p>$ringid<br />
$rlmain::ringselect</p>
<span>$siteid</span><br />
<input class="text" type="text" size="15" maxlength="15" name="siteid" value="$rlmain::data{'siteid'}" />
<br /><br />
<span>$pw</span><br />
<input class="text" type="password" size="15" maxlength="15" name="pw" /><br />
<span class="small">($cookies)</span>
<input type="hidden" name="result" value="Login succeeded" />
<p><input class="button" type="submit" value="$loginbutton" /></p>
<p>&nbsp;</p>
<p>$pwtext</p>
<p><input class="button" type="submit" name="emailpw" value="$pwbutton" /></p>
</form>~;

}

sub Edit_site	{
  if ($rlmain::data{'submit'})	{
    site::validation();
    if (!@rlmain::error)	{
      site::update();
      for (@rlmain::sitenames)	{
        ${$rlmain::refs{$_}} = $rlmain::data{$_};
      }
      (my $name = $rlmain::title) =~ s/\W/_/g;
      $name .= "/$rlmain::ringid/$rlmain::siteid";
      print "Set-cookie: $name=", crypt ($rlmain::sitepw, 'pw'), "\n";
      $rlmain::pagemenu = site::menu();
      $rlmain::result = '<p class="success">' . gettext("Site updated") . '</p>';
    } else	{
      $rlmain::pagemenu = site::menu();
      $rlmain::result = '<h4>' . gettext("Edit site") . "</h4>\n";
      $rlmain::result .= site::form();
    }
  } else	{
    for (@rlmain::sitenames)	{
      $rlmain::data{$_} = ${$rlmain::refs{$_}};
    }
    $rlmain::pagemenu = site::menu();
    $rlmain::result = '<h4>' . gettext("Edit site") . "</h4>\n";
    $rlmain::result .= site::form();
  }
}

sub Get_code	{
  $rlmain::htmlcode = rlmain::htmlcode();
  $rlmain::pagemenu = site::menu();
  $rlmain::result = site::htmlcode();
}

sub View_stats	{
  $rlmain::pagemenu = site::menu();
  $rlmain::result = site::getstats();
}

sub Remove_site	{
  if ($rlmain::data{'submit'} eq gettext("Remove"))	{
    if ($rlmain::data{'removesure'} eq 'on')	{
      my $ring = gettext("Ring:");
      my $site = gettext("Site:");
      $site::ringtitle = $rlmain::ringtitle;
      site::remove();
      site::removemail2();
      $rlmain::pagetitle = gettext("Site admin");
      rlmain::entify($rlmain::ringtitle, $rlmain::sitetitle);

      $rlmain::ring_site = qq~<table cellspacing="8">
<tr>
<td><span>$ring</span></td>
<td><span><a href="$rlmain::ringURL" target="Ringlink">$rlmain::ringtitle</a></span></td>
</tr><tr>
<td><span>$site</span></td>
<td><span><a href="$rlmain::entryURL" target="Ringlink">$rlmain::sitetitle</a></span></td>
</tr>
</table>~;

      $rlmain::result = '<p class="success">' . gettext("Site removed") . '</p>';
    } else	{
      push (@rlmain::error, '<p class="error">'
      . gettext("The site was not removed, since the checkbox below wasn't checked.") . '</p>');
      $rlmain::result = site::removeform();
    }
  } elsif ($rlmain::data{'submit'} eq gettext("Cancel"))	{
      $rlmain::pagemenu = site::menu();
  } else	{
      $rlmain::result = site::removeform();
  }
}

