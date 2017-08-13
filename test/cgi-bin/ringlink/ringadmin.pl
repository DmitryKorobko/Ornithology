#!/usr/bin/perl -T
use lib 'lib';

############################> Ringlink <############################
#                                                                  #
#  $Id: ringadmin.pl,v 1.66 2005/02/21 17:11:41 gunnarh Exp $      #
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
use site 3.2;
use Locale::PGetText 2.0;

rlmain::execstart;
rlmain::ringlist;
if (!@rlmain::rings)	{
  $rlmain::pagetitle = gettext("Ring admin");
  $rlmain::result = rlmain::noring();
} elsif (!$rlmain::data)	{
  &loginform;
} elsif (!$rlmain::data{'ringid'})	{
  my $selectid = gettext("Select ring ID!");
  push (@rlmain::error, "<p class=\"error\">$selectid</p>");
  &loginform;
} else	{
  rlmain::getringvalues();
  die "Error! Can't get registered password." if !$rlmain::ringpw;
  if ($rlmain::data{'emailpw'})	{
    my $pwintro = gettext("This is the registered password:");
    my $title = gettext("Ring title:");
    $title = $title . ' ' . ' ' x (15 - length($title)) . $rlmain::ringtitle;
    my $id = gettext("Ring ID:");
    $id = $id . ' ' . ' ' x (15 - length($id)) . $rlmain::ringid;
    my $pw = gettext("Password:");
    $pw = $pw . ' ' . ' ' x (15 - length($pw)) . $rlmain::ringpw;
    my $fromtitle = rlmain::nameclean($rlmain::title);
    my $pwsent = $rlmain::nomail ? rlmain::nomailtext()
     : '<p class="success">' . gettext("The password was sent, check your mail.") . '</p>';

    my $body = qq~$pwintro

    $title
    $id

    $pw

$rlmain::title
$rlmain::adminname
$rlmain::ringlinkURL
~;

    rlmain::email (
      $rlmain::rmemail,
      '',
      "$fromtitle <$rlmain::adminemail>",
      gettext("Ring password"),
      $body
    );
    push (@rlmain::error, $pwsent);
    for (keys %rlmain::colors)	{
      ${$rlmain::refs{$_}} = $rlmain::colors{$_};
    }
    &loginform;
  } elsif (!rlmain::pwcheck ($rlmain::ringpw, $rlmain::ringid))	{
    my $pwenter = gettext("Enter password!");
    my $wrongpw = gettext("Incorrect password, please try again.");
    if (!$rlmain::data{'pw'})	{
      unless ($ENV{'REQUEST_METHOD'} eq 'GET')	{
        push (@rlmain::error, "<p class=\"error\">$pwenter</p>");
      }
    } else	{
      push (@rlmain::error, "<p class=\"error\">$wrongpw</p>");
    }
    for (keys %rlmain::colors)	{
      ${$rlmain::refs{$_}} = $rlmain::colors{$_};
    }
    &loginform;
  } elsif (!$rlmain::data{'routine'})	{
    $rlmain::pagemenu = ring::menu();
    $rlmain::result = '<p class="success">' . gettext($rlmain::data{'result'}) . '</p>';
  } else	{
    $rlmain::data{'routine'} = $rlmain::routines{$rlmain::data{'routine'}};
    (my $routine = $rlmain::data{'routine'}) =~ tr/ /_/;
    &{\&$routine};
  }
}

rlmain::adminhtml;


sub loginform	{
  $rlmain::pagetitle = gettext("Ring admin");
  rlmain::ringselect();
  my $login = gettext("Login");
  my $id = gettext("Ring ID");
  my $pw = gettext("Ring password");
  my $cookies = gettext("&quot;cookies&quot; must be enabled");
  my $loginbutton = gettext("Log in");
  my $pwtext = gettext("Forgotten your password? Click below to have it sent to the\nringmaster email address for the selected ring.");
  my $pwbutton = gettext("Send password");

  $rlmain::result = qq~<h4>$login</h4>
@rlmain::error
<form method="post" action="$rlmain::cgiURL/$rlmain::action">
<p>$id<br />
$rlmain::ringselect</p>
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

sub Edit_ring	{
  ring::Edit_ring();
}

sub Customize	{
  $rlmain::pagemenu = ring::customizemenu();
  $rlmain::result = "<p class=\"success\">$rlmain::data{'result'}</p>";
}

sub Appearance	{
  ring::Appearance();
}

sub HTML_code	{
  ring::HTML_code();
}

sub Add_page	{
  ring::Add_page();
}

sub Add_mail	{
  ring::Add_mail();
}

sub Code_page	{
  ring::Code_page();
}

sub New_site	{
  ring::New_site();
}

sub Site_admin	{
  ring::Site_admin();
}

sub Edit_site	{
  ring::Edit_site();
}

sub statuschangemail	{
  rlmain::getsitevalues();
  site::statuschangemail();
  $rlmain::pagemenu = site::menu();
}

sub Remove_site	{
  ring::Remove_site();
}

sub Get_code	{
  rlmain::getsitevalues();
  $rlmain::htmlcode = rlmain::htmlcode();
  $rlmain::pagemenu = site::menu();
  $rlmain::result = site::htmlcode();
}

sub View_stats	{
  rlmain::getsitevalues();
  $rlmain::pagemenu = site::menu();
  $rlmain::result = site::getstats();
}

sub Active_sites	{
  ring::Active_sites();
}

sub Inactive_sites	{
  ring::Inactive_sites();
}

sub Activate	{
  ring::Activate();
}

sub Deactivate	{
  ring::Deactivate();
}

sub Remove_ring	{
  if ($rlmain::data{'submit'} eq gettext("Remove"))	{
    if ($rlmain::data{'removesure'} eq 'on')	{
      my $ring = gettext("Ring:");
      $rlmain::pagetitle = gettext("Ring admin");
      rlmain::entify($rlmain::ringtitle);

      $rlmain::ring_site = qq~<table cellspacing="8">
<tr>
<td><span>$ring</span></td>
<td><span><a href="$rlmain::ringURL" target="Ringlink">$rlmain::ringtitle</a></span></td>
</tr>
</table>~;

      $rlmain::result = '<p class="success">' . gettext("Ring removed") . '</p>';
      ring::remove();
      ring::removemail2();
    } else	{
      push (@rlmain::error, '<p class="error">'
      . gettext("The ring was not removed,\nsince the checkbox below wasn't checked.") . '</p>');
      $rlmain::result = ring::removeform();
    }
  } elsif ($rlmain::data{'submit'} eq gettext("Cancel"))	{
      $rlmain::pagemenu = ring::menu();
  } else	{
      $rlmain::result = ring::removeform();
  }
}

sub Check_sites	{
  ring::Check_sites();
}

sub Reorder_sites	{
  ring::Reorder_sites();
}

sub Send_email	{
  ring::Send_email();
}

sub Search_sites	{
  ring::Search_sites();
}

sub Backup_ring	{
  ring::backup();
}

sub Directory	{
  ring::directory();
}

