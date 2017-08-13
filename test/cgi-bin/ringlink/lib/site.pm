############################> Ringlink <############################
#                                                                  #
#  $Id: site.pm,v 1.102 2005/02/21 17:12:09 gunnarh Exp $          #
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

package site;
$site::VERSION = '3.2';

use strict;
use Fcntl qw(:DEFAULT :flock);
use SDBM_File;

use Tie::File;
use Locale::PGetText;

sub form	{
  my ($siteid, $siteURLtext, $secondURL, $passvalues, $error);
  my $status = '';
  my $updated = '';
  my $siteidtext = '';
  my $blank = '';
  my $active_checked = '';
  my $inactive_checked = '';
  my $statustext = gettext("Status");
  my $activetext = gettext("Active");
  my $inactivetext = gettext("Inactive");
  my $updatedtext = gettext("Last updated");
  my $codeURL = gettext("HTML code URL");
  my $title = gettext("Site title");
  my $desc = gettext("Description");
  my $desc_maxchar = sprintf (gettext("max %d characters"), $rlmain::numcharsitedesc);
  my $keyw = gettext("Keywords");
  my $keyw_maxchar = gettext("optional; max 150 characters");
  my $idtext = gettext("Site ID");
  my $pw = gettext("Password");
  my $caution = gettext("Do not use<br />a valuable<br />password.");
  my $name = gettext("Name");
  my $name_expl = gettext("name of the site's webmaster");
  my $email = gettext("Email");
  my $email_expl = gettext("email address of the site's webmaster");
  my $submit = gettext("Submit");
  my $reset = gettext("Reset");
  my $validationcode="zebidie";
  foreach (@rlmain::sitenames)	{
    if ($rlmain::data{$_})	{
      rlmain::entify($rlmain::data{$_});
    } else	{
      $rlmain::data{$_} = '';
    }
  }
  if ($rlmain::data{'routine'} ne 'New site')	{
    $siteid = "<span style=\"font-family: 'courier new', monospace\">$rlmain::data{'siteid'}</span>\n"
    . "<input type=\"hidden\" name=\"siteid\" value=\"$rlmain::data{'siteid'}\" />";
    if ($rlmain::action =~ /siteadmin/i && $rlmain::status eq 'inactive')	{
      $status = "<tr><td colspan=\"3\"><table width=\"100%\"><tr>\n"
      . "<td><p style=\"margin: 0\">$statustext<br />"
      . "<span style=\"font-family: 'courier new', monospace\">$inactivetext</span></p>\n"
      . "<input type=\"hidden\" name=\"status\" value=\"$rlmain::data{'status'}\" /></td>";
    } else	{
      $active_checked = 'checked="checked"' if $rlmain::data{'status'} eq 'active';
      $inactive_checked = 'checked="checked"' if $rlmain::data{'status'} eq 'inactive';

      $status = qq~<tr><td colspan="3"><table width="100%"><tr>
<td><span>$statustext</span><br />
<input class="text" type="radio" $active_checked name="status" value="active">&nbsp;<span>$activetext</span>&nbsp;&nbsp;&nbsp;
<input class="text" type="radio" $inactive_checked name="status" value="inactive">&nbsp;<span>$inactivetext</span>&nbsp;&nbsp;&nbsp;</td>~;

    }
    $updated = "<td class=\"top\"><p style=\"margin: 0\">$updatedtext<br />"
    . "<span style=\"font-family: 'courier new', monospace\">$rlmain::data{'updated'}</span></p>\n"
    . "<input type=\"hidden\" name=\"updated\" value=\"$rlmain::data{'updated'}\" /></td>\n</tr></table></td></tr>";
  } else	{
    $siteidtext = '<span class="small">(' . gettext("letters and/or digits") . ')</span>';
    $siteid = "<input class=\"text\" type=\"text\" size=\"15\" maxlength=\"15\" "
    . "name=\"siteid\" value=\"$rlmain::data{'siteid'}\" />";
    $blank = '<span class="small">(' . gettext("leave blank if same as entry URL") . ')</span>';
  }
  if ($rlmain::hide2ndURL eq 'on' && ($rlmain::action =~ /siteadmin/i || $rlmain::action =~ /newsite/i))	{
    $siteURLtext = gettext("Site URL");
    $secondURL = "<input type=\"hidden\" name=\"codeURL\" value=\"$rlmain::data{'codeURL'}\" />";
  } else	{
    $siteURLtext = gettext("Site entry URL");

    $secondURL = qq~<tr>
<td colspan="3"><span>$codeURL </span>$blank<br />
<input class="text" type="text" size="45" name="codeURL" value="$rlmain::data{'codeURL'}" /></td>
</tr>~;

  }
  if ($rlmain::data{'pass'})	{

    $passvalues = qq~<input type="hidden" name="pass" value="$rlmain::data{'pass'}" />
<input type="hidden" name="completeinfo" value="$rlmain::data{'completeinfo'}" />
<input type="hidden" name="sitesperpage" value="$rlmain::data{'sitesperpage'}" />~;

  } else	{
    $passvalues = '';
  }
  $error = join ("\n", @rlmain::error);

  return qq~$error
<form method="post" action="$rlmain::cgiURL/$rlmain::action">
<table>
<tr>
<td colspan="3"><span>$title</span><br />
<input class="text" type="text" size="45" maxlength="45" name="sitetitle" value="$rlmain::data{'sitetitle'}" /></td>
</tr>
<tr>
<td colspan="3"><span>$desc </span><span class="small">($desc_maxchar)</span><br />
<div class="textarea"><textarea name="sitedesc" rows="4" cols="45" $rlmain::wrapsoft>$rlmain::data{'sitedesc'}</textarea></div></td>
</tr>
<tr>
<td colspan="3"><span>$keyw </span><span class="small">($keyw_maxchar)</span><br />
<input class="text" type="text" size="45" maxlength="150" name="keywords" value="$rlmain::data{'keywords'}" /></td>
</tr>
<tr>
<td colspan="3"><span>$siteURLtext</span><br />
<input class="text" type="text" size="45" name="entryURL" value="$rlmain::data{'entryURL'}" /></td>
</tr>
$secondURL
<tr>
<td><span>$idtext </span>$siteidtext<br />
$siteid</td>
<td><span>$pw</span><br />
<input class="text" type="text" size="15" maxlength="15" name="sitepw" value="$rlmain::data{'sitepw'}" /></td>
<td><span class="small" style="color: $rlmain::colerr; background: none">$caution</span></td>
</tr>
<tr>
<td colspan="3"><span>$name </span><span class="small">($name_expl)</span><br />
<input class="text" type="text" size="45" maxlength="45" name="wmname" value="$rlmain::data{'wmname'}" /></td>
</tr>
<tr>
<td colspan="3"><span>$email </span><span class="small">($email_expl)</span><br />
<input class="text" type="text" size="45" name="wmemail" value="$rlmain::data{'wmemail'}" /></td>
</tr>
$status
$updated
</table>
<input type="hidden" name="ringid" value="$rlmain::data{'ringid'}" />
<input type="hidden" name="routine" value="$rlmain::data{'routine'}" />
<input type="hidden" name="validationcode" value="zebidie" />
$passvalues
<div class="center"><p>
<input class="button" type="submit" name="submit" value="$submit" />&nbsp;&nbsp;&nbsp;<input class="button" type="reset" value="$reset" />
</p></div>
</form>~;

}


sub validation	{
  rlmain::trim(@rlmain::data{ @rlmain::sitenames });
  if ($rlmain::data{'validationcode'} ne 'zebidie')	{
    push (@rlmain::error, '<p class="error">System Error - Retry</p>');
  }
  if (!$rlmain::data{'sitetitle'})	{
    push (@rlmain::error, '<p class="error">' . gettext("You must enter Site title.") . '</p>');
  }
  if (!$rlmain::data{'sitedesc'})	{
    push (@rlmain::error, '<p class="error">' . gettext("You must enter Description.") . '</p>');
  } elsif (length $rlmain::data{'sitedesc'} > $rlmain::numcharsitedesc)	{
    push (@rlmain::error, '<p class="error">' . gettext("Too many characters in Description.") . '</p>');
  }
  $rlmain::data{'keywords'} =~ s/^[,; ]+//;
  $rlmain::data{'keywords'} =~ s/[,; ]+$//;
  $rlmain::data{'keywords'} =~ s/(?: ?[,;])+ ?/, /g;
  if (length $rlmain::data{'keywords'} > 150)	{
    push (@rlmain::error, '<p class="error">' . gettext("Too many characters in Keywords.") . '</p>');
  }
  $rlmain::data{'entryURL'} =~ s/ /%20/g;
  if (!$rlmain::data{'entryURL'} || 'http://' =~ /$rlmain::data{'entryURL'}/)	{
    push (@rlmain::error, '<p class="error">' . gettext("You must enter Site entry URL.") . '</p>');
  } elsif ($rlmain::data{'entryURL'} !~ /^http\S*:\/\/\S+\.\S+/ || $rlmain::data{'entryURL'} =~ /["<>{}]/)	{
    push (@rlmain::error, '<p class="error">' . gettext("Site entry URL is not correctly filled.") . '</p>');
  }
  $rlmain::data{'codeURL'} =~ s/ /%20/g;
  unless (!$rlmain::data{'codeURL'} || 'http://' =~ /$rlmain::data{'codeURL'}/)	{
    if ($rlmain::data{'codeURL'} !~ /^http\S*:\/\/\S+\.\S+/ || $rlmain::data{'codeURL'} =~ /["<>{}]/)	{
      push (@rlmain::error, '<p class="error">' . gettext("HTML code URL is not correctly filled.") . '</p>');
    }
  }
  unless ($rlmain::data{'routine'} ne 'New site')	{
    if (!$rlmain::data{'siteid'})	{
      push (@rlmain::error, '<p class="error">' . gettext("You must enter Site ID.") . '</p>');
    } elsif ($rlmain::data{'siteid'} =~ /\W|^_vti/)	{
      push (@rlmain::error, '<p class="error">'
      . gettext("Site ID shall consist of letters and digits only.") . '</p>');
    } else	{ 
      rlmain::sitelist();
      foreach (@rlmain::sites)	{
        if ("\L$_" eq "\L$rlmain::data{'siteid'}")	{
          push (@rlmain::error, '<p class="error">' . sprintf (
            gettext("Site ID %s exists already. Choose another site ID."),
            "&quot;$rlmain::data{'siteid'}&quot;") . '</p>');
          last;
        }
      }
    }
  }
  if (!$rlmain::data{'sitepw'})	{
    push (@rlmain::error, '<p class="error">' . gettext("You must enter Password.") . '</p>');
  } elsif ($rlmain::data{'sitepw'} =~ / /)	{
    push (@rlmain::error, '<p class="error">' . gettext("Password must not contain spaces.") . '</p>');
  } elsif ($rlmain::data{'sitepw'} =~ /["<>{}]/)	{
    push (@rlmain::error, '<p class="error">' . gettext("Password contains forbidden characters.") . '</p>');
  }
  if (!$rlmain::data{'wmname'})	{
    push (@rlmain::error, '<p class="error">' . gettext("You must enter webmaster name.") . '</p>');
  }
  if (!$rlmain::data{'wmemail'})	{
    push (@rlmain::error, '<p class="error">' . gettext("You must enter webmaster email.") . '</p>');
  } elsif (rlmain::emailsyntax($rlmain::data{'wmemail'}))	{
    push (@rlmain::error, '<p class="error">' . gettext("Webmaster email is not correctly filled.") . '</p>');
  }
}


sub create	{
  my ($site, $sitedir, %hits, $mailcopy, $ringtitle, $rmemail, $keyw, $body, @execfiles);
  $rlmain::data{'codeURL'} = $rlmain::data{'entryURL'} if 'http://' =~ /$rlmain::data{'codeURL'}/
   || !$rlmain::data{'codeURL'};
  $rlmain::data{'status'} = 'inactive';
  $rlmain::data{'updated'} = rlmain::timestamp();
  foreach (@rlmain::sitenames)	{
    $site .= "$rlmain::data{$_}\t";
    ${$rlmain::refs{$_}} = $rlmain::data{$_};
  }
  $site =~ s/\t$/\n/;
  $rlmain::ringid = rlmain::secureword ($rlmain::ringid);
  $rlmain::data{'siteid'} = rlmain::secureword ($rlmain::data{'siteid'});
  sysopen SITES, "$rlmain::datapath/$rlmain::ringid/sites.db", O_WRONLY|O_APPEND|O_CREAT,
   $rlmain::filemode or die "Can't create '$rlmain::ringid/sites.db'\n$!";
  flock SITES, LOCK_EX or die $!;
  print SITES $site;
  close SITES or die $!;
  $sitedir = "$rlmain::datapath/$rlmain::ringid/$rlmain::data{'siteid'}";
  mkdir $sitedir, $rlmain::dirmode
   || die "Can't create '$rlmain::ringid/$rlmain::data{'siteid'}'\n$!";
  for ('genhits', 'rechits')	{
    tie (%hits, 'SDBM_File', "$sitedir/$_", O_CREAT, $rlmain::filemode)
     or die "Can't bind '$rlmain::ringid/$rlmain::data{'siteid'}/$_'\n$!";
    untie %hits;
  }

  # Mail to webmaster
  $mailcopy = $rlmain::rmemail if $rlmain::action =~ /newsite/i;
  $ringtitle = rlmain::nameclean($rlmain::ringtitle);
  ($rmemail = $rlmain::rmemail) =~ s/,.+//;
  &substitute ($rlmain::htmlcode = rlmain::htmlcode());
  &substitute ($body = rlmain::addmail());
  $keyw = gettext("Keywords:");
  $body =~ s/[\t ]*\Q$keyw\E\s*\n// if !$rlmain::keywords;
  rlmain::email (
    $rlmain::wmemail,
    $mailcopy,
    "$ringtitle <$rmemail>",
    gettext("New site registered") . " [$rlmain::data{'siteid'}]",
    $body
  );

  # Prepare resulting page
  for (@rlmain::sitenames)	{
    rlmain::entify(${ $rlmain::refs{$_} });
  }
  for (@rlmain::ringnames)	{
    rlmain::entify(${ $rlmain::refs{$_} });
  }
  &substitute ($rlmain::addpage = rlmain::addpage());
}


sub update	{
  my ($newsite, $site, @sites);
  if ('http://' =~ /$rlmain::data{'codeURL'}/ || !$rlmain::data{'codeURL'} || ($rlmain::hide2ndURL eq 'on'
  && ($rlmain::action =~ /siteadmin/i || $rlmain::action =~ /newsite/i) && $rlmain::entryURL eq $rlmain::codeURL))	{
    $rlmain::data{'codeURL'} = $rlmain::data{'entryURL'};
  }
  $rlmain::data{'updated'} = rlmain::timestamp();
  for (@rlmain::sitenames)	{
    $newsite .= "$rlmain::data{$_}\t";
  }
  $newsite =~ s/\t$//;
  $rlmain::ringid = rlmain::secureword ($rlmain::ringid);
  (tie @sites, 'Tie::File', "$rlmain::datapath/$rlmain::ringid/sites.db")->flock
   or die "Can't bind '$rlmain::ringid/sites.db'\n$!";
  foreach $site (@sites)	{
    if ($site =~ /^$rlmain::siteid\t/)	{
      $site = $newsite;
      last;
    }
  }
  untie @sites or die $!;

  if ($rlmain::stats)	{

    # Reset stats if site was deactivated
    if (($rlmain::status eq 'active' and $rlmain::data{'status'} eq 'inactive')
     or $rlmain::data{'routine'} eq 'Deactivate')	{
      my $sitedir = rlmain::secureword($rlmain::ringid).'/'.rlmain::secureword($rlmain::siteid);
      for ('genhits', 'rechits')	{
        open DBLOCK, "> $rlmain::datapath/$sitedir/$_.lockfile"
         or die "Can't open '$sitedir/$_.lockfile'\n$!";
        flock DBLOCK, LOCK_EX or die $!;
        tie my %hits, 'SDBM_File', "$rlmain::datapath/$sitedir/$_", O_RDWR, $rlmain::filemode
         or die "Can't bind '$sitedir/$_'\n$!";
        %hits = ();
        untie %hits or die $!;
        close DBLOCK or die $!;
      }
    }

    # Update sitecount if site was activated or deactivated
    if (
     ($rlmain::status eq 'active' and $rlmain::data{'status'} eq 'inactive') or
     $rlmain::data{'routine'} eq 'Deactivate' or
     ($rlmain::status eq 'inactive' and $rlmain::data{'status'} eq 'active') or
     $rlmain::data{'routine'} eq 'Activate'
    )	{
      rlmain::sitecountupdate ($rlmain::ringid);
    }
  }

  # Change notification email to ringmaster
  if ($rlmain::action =~ /^siteadmin/i && $rlmain::status eq 'active')	{
    my @change = ();
    push (@change, gettext("Status") . ":\n" . gettext("Inactive") . "\n\n")
     if $rlmain::data{'status'} ne $rlmain::status;
    push (@change, gettext("Site title") . ":\n$rlmain::data{'sitetitle'}\n\n")
     if $rlmain::data{'sitetitle'} ne $rlmain::sitetitle;
    push (@change, gettext("Description") . ":\n$rlmain::data{'sitedesc'}\n\n")
     if $rlmain::data{'sitedesc'} ne $rlmain::sitedesc;
    push (@change, gettext("Site entry URL") . ":\n$rlmain::data{'entryURL'}\n\n")
     if $rlmain::data{'entryURL'} ne $rlmain::entryURL;
    push (@change, gettext("HTML code URL") . ":\n$rlmain::data{'codeURL'}\n\n")
     if $rlmain::data{'codeURL'} ne $rlmain::codeURL;
    if (@change)	{
      my $subject = sprintf (gettext("Site ID %s changed"), "\'$rlmain::siteid\'");
      my $title = gettext("Ring title:");
      $title = $title . ' ' . ' ' x (15 - length($title)) . $rlmain::ringtitle;
      my $id = gettext("Ring ID:");
      $id = $id . ' ' . ' ' x (15 - length($id)) . $rlmain::ringid;
      my $intro = sprintf (gettext("The webmaster of site ID %s has\nregistered the following new info:"),
       "\'$rlmain::siteid\'");
      my $change = join ('', @change);
      rlmain::email (
        $rlmain::rmemail,
        '',
        rlmain::nameclean($rlmain::title) . " <$rlmain::adminemail>",
        $subject,
        "$title\n$id\n\n$intro\n\n$change"
      );
    }
  }

  # Email to ringmaster about the Ringlink Webring Directory
  unless ($rlmain::dirmail)	{
    if ( (($rlmain::status eq 'inactive' and $rlmain::data{'status'} eq 'active')
     or $rlmain::data{'routine'} eq 'Activate') and !$rlmain::nomail )	{
      my $count = 0;
      open SITES, "< $rlmain::datapath/$rlmain::ringid/sites.db"
       or die "Can't open '$rlmain::ringid/sites.db'\n$!";
      while (<SITES>) { $count++ if /^\w+\tactive/ }
      close SITES;
      if ($count >= 3)	{

        # Compose and send mail
        opendir DIR, "$rlmain::lib/RLDir" or die "Can't open /RLDir\n$!";
        my $numringdir = grep { /\.pm$/ } readdir DIR;
        closedir DIR;
        my $greeting = gettext("Dear Ringmaster,");
        my $intro    = sprintf( gettext("We would like to call your attention to the %s,\na categorized web directory over Ringlink webrings."),
                       "\nRinglink Webring Directory (http://ringdir.ringlink.org/)" );
        my $submit   = sprintf( gettext("If you want to submit your ring %s,\nthe easiest way is to log in to %s\nand click the %s button."),
                       "\"$rlmain::ringtitle\"", '"' . gettext("Ring admin") . "\":\n\n$rlmain::cgiURL/"
                       . rlmain::filenamefix('ringadmin') . "?ringid=$rlmain::ringid\n",
                       '"'.( $numringdir > 1 ? gettext("Directories") : gettext("Directory") ).'"' );

        my $body = <<MSG;
$greeting

$intro

$submit

$rlmain::title
$rlmain::adminname
$rlmain::ringlinkURL
MSG

        rlmain::email (
          $rlmain::rmemail,
          '',
          rlmain::nameclean($rlmain::title) . " <$rlmain::adminemail>",
          'Ringlink Webring Directory',
          $body
        );

        # Prevent that this msg is sent in the future
        $rlmain::dirmail = 1;
        $rlmain::data{$_} = ${ $rlmain::refs{$_} } for @rlmain::ringnames;
        ring::update();
      }
    }
  }
}


sub statuschangemail	{
  my ($ringtitle, $rmemail, $subject, $text, $body);
  my $passvalues = '';
  my $notify = gettext("Notify the webmaster of the status change:");
  my $send = gettext("Send");
  my $cancel = gettext("Cancel");
  if ($rlmain::data{'submit'} eq gettext("Send"))	{
    $ringtitle = rlmain::nameclean($rlmain::ringtitle);
    ($rmemail = $rlmain::rmemail) =~ s/,.+//;
    rlmain::email (
      $rlmain::wmemail,
      $rlmain::rmemail,
      "$ringtitle <$rmemail>",
      $rlmain::data{'subject'},
      $rlmain::data{'body'}
    );
    if ($rlmain::data{'pass'})	{
      push (@rlmain::error, '<p class="success">' . gettext("Message sent") . '</p>');
      rlmain::getringvalues() if $rlmain::action =~ /^admin/i;
      if ($rlmain::data{'pass'} eq 'active')	{
        $rlmain::data{'routine'} = 'Active sites';
        $rlmain::data{'siteid'} = '';
        $rlmain::nolist = 1;
        ring::Active_sites();
      } elsif ($rlmain::data{'pass'} eq 'inactive')	{
        $rlmain::data{'routine'} = 'Inactive sites';
        $rlmain::data{'siteid'} = '';
        ring::Inactive_sites();
      } elsif ($rlmain::data{'pass'} eq 'check')	{
        $rlmain::data{'routine'} = 'Check sites';
        ring::Check_sites();
      } elsif ($rlmain::data{'pass'} eq 'search')	{
        $rlmain::data{'routine'} = 'Search sites';
        ring::Search_sites();
      }
    } else	{
      $rlmain::result = '<p class="success">' . gettext("Message sent") . '</p>';
    }
  } elsif ($rlmain::data{'submit'} eq gettext("Cancel") || $rlmain::nomail)	{
    if ($rlmain::data{'pass'})	{
      push (@rlmain::error, $rlmain::nomail ? $rlmain::result
       : '<p class="success">' . gettext("No message was sent.") . '</p>');
      rlmain::getringvalues() if $rlmain::action =~ /^admin/i;
      if ($rlmain::data{'pass'} eq 'active')	{
        $rlmain::data{'routine'} = 'Active sites';
        $rlmain::data{'siteid'} = '';
        $rlmain::nolist = 1;
        ring::Active_sites();
      } elsif ($rlmain::data{'pass'} eq 'inactive')	{
        $rlmain::data{'routine'} = 'Inactive sites';
        $rlmain::data{'siteid'} = '';
        ring::Inactive_sites();
      } elsif ($rlmain::data{'pass'} eq 'check')	{
        $rlmain::data{'routine'} = 'Check sites';
        ring::Check_sites();
      } elsif ($rlmain::data{'pass'} eq 'search')	{
        $rlmain::data{'routine'} = 'Search sites';
        ring::Search_sites();
      }
    } else	{
      $rlmain::result = '<p class="success">' . gettext("No message was sent.") . '</p>';
    }
  } else	{
    if ($rlmain::data{'pass'})	{
      $rlmain::pagemenu = ring::menu();

      $passvalues = qq~<input type="hidden" name="pass" value="$rlmain::data{'pass'}" />
<input type="hidden" name="completeinfo" value="$rlmain::data{'completeinfo'}" />
<input type="hidden" name="sitesperpage" value="$rlmain::data{'sitesperpage'}" />~;

    }
    if ($rlmain::data{'status'} eq 'active')	{
      $subject = sprintf (gettext("Site ID %s activated"), "\'$rlmain::siteid\'");
      $text = sprintf (gettext("Your site %s has been approved and is now\nan active site in the webring %s."),
        "&quot;$rlmain::sitetitle&quot;", "&quot;$rlmain::ringtitle&quot;");
    } else	{
      $subject = sprintf (gettext("Site ID %s deactivated"), "\'$rlmain::siteid\'");
      $text = sprintf (
        gettext("Your site %s at %s has been assigned the status\n&quot;Inactive&quot; in the webring %s. Please let\nme know if you don't understand the reason for it."),
        "&quot;$rlmain::sitetitle&quot;", $rlmain::entryURL, "&quot;$rlmain::ringtitle&quot;");
    }
    $body = rlmain::trim($text) . "\n\n$rlmain::ringtitle\n$rlmain::rmname\n$rlmain::ringURL\n";

    return qq~<p>$notify</p>
<form method="post" action="$rlmain::cgiURL/$rlmain::action">
<div class="textarea"><textarea name="body" rows="15" cols="65" $rlmain::wraphard>
$body</textarea></div>
<input type="hidden" name="siteid" value="$rlmain::data{'siteid'}" />
<input type="hidden" name="ringid" value="$rlmain::data{'ringid'}" />
<input type="hidden" name="routine" value="statuschangemail" />
<input type="hidden" name="subject" value="$subject" />
$passvalues
<p><input class="button" type="submit" name="submit" value="$send" />
&nbsp;&nbsp;&nbsp;<input class="button" type="submit" name="submit" value="$cancel" /></p>
</form>~;

  }
}


sub menu	{
  my $masteradmin = '';
  my $ringadmin = '';
  my $viewstats = '';
  my $ring = gettext("Ring:");
  my $site = gettext("Site:");
  my $master = gettext("Master admin");
  my $ringadm = gettext("Ring admin");
  my $getcode = gettext("Get code");
  my $edit = gettext("Edit site");
  my $view = gettext("View stats");
  my $remove = gettext("Remove site");
  $rlmain::pagetitle = gettext("Site admin");
  rlmain::entify($rlmain::ringtitle, $rlmain::sitetitle, $rlmain::wmname);

  $rlmain::ring_site = qq~<table cellspacing="8">
<tr>
<td><span>$ring</span></td>
<td><span><a href="$rlmain::ringURL" target="Ringlink">$rlmain::ringtitle</a></span></td>
</tr><tr>
<td><span>$site</span></td>
<td><span><a href="$rlmain::entryURL" target="Ringlink">$rlmain::sitetitle</a></span></td>
</tr>
</table>~;

  if ($rlmain::action =~ /^admin/i)	{
    $masteradmin = "<div class=\"button\"><input class=\"button\" $rlmain::ns4width type=\"submit\" value=\"$master\" /></div>";
    $ringadmin = "<div class=\"button\"><input class=\"button\" $rlmain::ns4width type=\"submit\" name=\"routine\" value=\"$ringadm\" /></div>";
  } elsif ($rlmain::action =~ /ringadmin/i)	{
    $ringadmin = "<div class=\"button\"><input class=\"button\" $rlmain::ns4width type=\"submit\" value=\"$ringadm\" /></div>";
  }
  if ($rlmain::stats)	{
    $viewstats = "<div class=\"button\"><input class=\"button\" $rlmain::ns4width type=\"submit\" name=\"routine\" value=\"$view\" /></div>";
  }

  return qq~$masteradmin
$ringadmin
<hr /><br /><input type="hidden" name="siteid" value="$rlmain::data{'siteid'}" />
<input type="hidden" name="ringid" value="$rlmain::data{'ringid'}" />
<div class="button"><input class="button" $rlmain::ns4width type="submit" name="routine" value="$getcode" /></div>
<div class="button"><input class="button" $rlmain::ns4width type="submit" name="routine" value="$edit" /></div>
$viewstats
<div class="button"><input class="button" $rlmain::ns4width type="submit" name="routine" value="$remove" /></div>~;

}


sub substitute	{
  for my $subst (@rlmain::ringsubstitutes, @rlmain::sitesubstitutes, 'cgiURL')	{
    $_[0] =~ s/\[$subst\]/${$rlmain::refs{$subst}}/g unless $subst eq 'rmemail';
  }
  (my $rmemail = $rlmain::rmemail) =~ s/,.+//;
  $_[0] =~ s/\[rmemail\]/$rmemail/g;
}


sub htmlcode	{
  my ($introduction, $htmlcode, $codeformat);
  my $normalintro = gettext("To have the normal format back, click here:");
  my $normal = gettext("Normal");
  my $onelineintro = gettext("If you prefer to get the code as one single line, click here:");
  my $oneline = gettext("One line");
  my $header = gettext("Get code");
  &substitute ($introduction = rlmain::codepage());
  &substitute ($rlmain::htmlcode);
  my $appear = gettext("The code appears like this:");
  $htmlcode = $rlmain::htmlcode;
  if ($rlmain::data{'submit'} eq gettext("One line"))	{
    for ($rlmain::htmlcode)	{
      s/>\s*\n\s*</></g;
      s/\s*\n\s*/ /g;
      s/^\s+//;
      s/\s+$//;
    }

    $codeformat = qq~<p>$normalintro<br />
<input class="button" type="submit" value="$normal" /></p>~;

  } else	{

    $codeformat = qq~<p>$onelineintro<br />
<input class="button" type="submit" name="submit" value="$oneline" /></p>~;

  }
  rlmain::entify($rlmain::htmlcode);

  return qq~<h4>$header</h4>
$introduction
<form method="post" action="$rlmain::cgiURL/$rlmain::action">
$codeformat
<div class="textarea"><textarea name="code" rows="10" cols="45" $rlmain::wrapoff>
$rlmain::htmlcode</textarea></div>
<input type="hidden" name="siteid" value="$rlmain::data{'siteid'}" />
<input type="hidden" name="ringid" value="$rlmain::data{'ringid'}" />
<input type="hidden" name="routine" value="$rlmain::data{'routine'}" />
<br /><br />
<span>$appear<br />
</span>

$htmlcode
</form>~;

}


sub getstats	{
  my $site = rlmain::secureword($rlmain::ringid) . '/' . rlmain::secureword($rlmain::siteid);
  my @sitestats = rlmain::getstats ("$rlmain::datapath/$site");
  my $gen = calcstats ($sitestats[0], $sitestats[1]);
  my $rec = calcstats ($sitestats[2], $sitestats[3]);
  my $header = gettext("View stats");
  my $contribute = gettext("contributed");
  my $receive = gettext("received");
  return '<h4>' . gettext("View stats") . "</h4>\n<p>" . sprintf (
    gettext("This site has %s to the ring traffic with %s when visitors\nhave clicked the links on the navigation panel for %s, and\nit has %s as a result of its membership in the ring."),
    "</p>\n<p><span style=\"font-weight: bold\">$contribute</span>", "<br />\n$gen</p>\n<p>",
    "<br />\n<span style=\"font-weight: bold\">$rlmain::ringtitle</span>",
    "</p>\n<p><span style=\"font-weight: bold\">$receive</span><br />\n$rec</p>\n<p>") . '</p>';
}


sub calcstats	{
  my ($numdays, $count) = @_;
  my $average;
  if ($numdays >= 3)	{
    my ($sec, $min, $hour) = gmtime $rlmain::time;
    my $adjnumdays = $numdays - 2 + ($hour*60*60 + $min*60 + $sec) / (24*60*60);
    $average = sprintf ("%.1f", $count / $adjnumdays);
  }
  if ($numdays < 3)	{
    return '<span style="font-weight: bold">' .
      sprintf (gettext("%s hits today"), "$count</span>");
  } elsif ($numdays < 32)	{
    return '<span style="font-weight: bold">' .
      sprintf (gettext("%s hits, or %s hits per day,\nsince %s"), "$count</span>",
      "<span style=\"font-weight: bold\">$average</span>",
      rlmain::timestamp('date', $numdays - 2));
  } else	{
    return '<span style="font-weight: bold">' .
      sprintf (gettext("%s hits, or %s hits per day,\nduring the last 30 days"),
      "$count</span>", "<span style=\"font-weight: bold\">$average</span>");
  }
}


sub removeform	{
  my $cancelbutton;
  my $emailnote = '';
  my $disabled = '';
  my $emailnote_yes = '';
  my $emailnote_no = '';
  my $passvalues = '';
  my $notify = gettext("Notify the webmaster?");
  my $yes = gettext("Yes");
  my $no = gettext("No");
  my $remove = gettext("Remove");
  my $cancel = gettext("Cancel");
  my $header = gettext("Remove site");
  my $surequery = gettext("Are you sure you want to remove this site?");
  my $sure = gettext("I am sure.");
  $rlmain::pagemenu = &menu;
  if ($rlmain::action =~ /^admin/i || $rlmain::action =~ /ringadmin/i)	{
    if (!$rlmain::data{'submit'})	{
      if ($rlmain::nomail)	{
        $disabled = 'disabled="disabled"';
        $emailnote_no = 'checked="checked"';
      } else	{
        $emailnote_yes = 'checked="checked"';
        my $text = sprintf (gettext("Your site %s has been removed from the webring %s."),
          "&quot;$rlmain::sitetitle&quot;", "&quot;$rlmain::ringtitle&quot;");
        $rlmain::data{'body'} = rlmain::trim($text) . "\n\n$rlmain::ringtitle\n$rlmain::rmname\n$rlmain::ringURL\n";
      }
    } else	{
      $emailnote_yes = 'checked="checked"' if $rlmain::data{'emailnote'} eq 'yes';
      $emailnote_no = 'checked="checked"' if $rlmain::data{'emailnote'} eq 'no';
      $disabled = 'disabled="disabled"' if $rlmain::nomail;
      rlmain::entify($rlmain::data{'body'});
    }

    $emailnote = qq~<span>$notify</span>&nbsp;&nbsp;&nbsp;
<input class="text" type="radio" $emailnote_yes $disabled name="emailnote" value="yes" />
<span>$yes</span>&nbsp;&nbsp;&nbsp;
<input class="text" type="radio" $emailnote_no name="emailnote" value="no" />
<span>$no</span>~;

    $emailnote .= qq~<br />
<div class="textarea"><textarea name="body" rows="10" cols="65" $rlmain::wraphard>
$rlmain::data{'body'}</textarea></div>~ unless $rlmain::nomail;

    if ($rlmain::data{'pass'})	{

      $passvalues = qq~<input type="hidden" name="pass" value="$rlmain::data{'pass'}" />
<input type="hidden" name="completeinfo" value="$rlmain::data{'completeinfo'}" />
<input type="hidden" name="sitesperpage" value="$rlmain::data{'sitesperpage'}" />~;

    }
  }
  $cancelbutton = ($rlmain::data{'pass'} eq 'check' ?
   "<input type=\"button\" class=\"button\" value=\"$cancel\" onclick=\"window.close()\" />" :
   "<input type=\"submit\" class=\"button\" name=\"submit\" value=\"$cancel\" />");

  $rlmain::result = qq~<h4 style="margin-top: 0">$header</h4>
@rlmain::error
<form method="post" action="$rlmain::cgiURL/$rlmain::action">
<p>$surequery<br />
<input class="text" type="checkbox" name="removesure" />&nbsp;<span>$sure</span></p>
$emailnote
<input type="hidden" name="siteid" value="$rlmain::data{'siteid'}" />
<input type="hidden" name="ringid" value="$rlmain::data{'ringid'}" />
<input type="hidden" name="routine" value="Remove site" />
$passvalues
<p><input class="button" type="submit" name="submit" value="$remove" />
&nbsp;&nbsp;&nbsp;$cancelbutton</p>
</form>~;

}


sub remove	{
  $rlmain::ringid = rlmain::secureword ($rlmain::ringid);
  $rlmain::siteid = rlmain::secureword ($rlmain::siteid);
  (tie my @sites, 'Tie::File', "$rlmain::datapath/$rlmain::ringid/sites.db")->flock
   or die "Can't bind '$rlmain::ringid/sites.db'\n$!";
  my $status;
  my $i = 0;
  for (@sites)	{
    if (/^$rlmain::siteid\t(\w+)/)	{
      $status = $1;
      splice (@sites, $i, 1);
      last;
    }
    $i++;
  }
  untie @sites or die $!;
  my $dir = "$rlmain::datapath/$rlmain::ringid/$rlmain::siteid";
  die "'$rlmain::ringid/$rlmain::siteid' was not removed\n"
   . "because of an ambiguity whether it really is a site directory.\n"
   unless -f "$dir/genhits.pag" or -f "$dir/genhits.db";
  rlmain::removedirectory ($dir);
  rlmain::sitecountupdate ($rlmain::ringid) if $status eq 'active';
}


sub removemail	{ # to the webmaster
  if ($rlmain::data{'emailnote'} eq 'yes')	{
    my $title=rlmain::nameclean($site::ringtitle);
    (my $rmemail = $rlmain::rmemail) =~ s/,.+//;
    rlmain::email (
      $rlmain::wmemail,
      $rlmain::rmemail,
      "$title <$rmemail>",
      sprintf (gettext("Site ID %s removed"), "\'$rlmain::siteid\'"),
      $rlmain::data{'body'}
    );
  }
}


sub removemail2	{ # to the ringmaster
  my $body = sprintf (gettext("The site %s, site ID %s, has been\nremoved from the webring %s."),
    "\"$rlmain::sitetitle\" ($rlmain::entryURL)", $rlmain::siteid, "\"$rlmain::ringtitle\"");
  rlmain::email (
    $rlmain::rmemail,
    '',
    rlmain::nameclean($rlmain::title) . " <$rlmain::adminemail>",
    gettext("Site removed"),
    $body
  );
}


1;

