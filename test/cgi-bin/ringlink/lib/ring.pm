############################> Ringlink <############################
#                                                                  #
#  $Id: ring.pm,v 1.169 2005/02/21 17:12:08 gunnarh Exp $          #
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

package ring;
$ring::VERSION = '3.2';

use strict;
use Fcntl qw(:DEFAULT :flock);
use SDBM_File;

use Locale::PGetText;

# These variables are used in more than one subroutine
my $windowFeatures = 'location,toolbar,status,menubar,directories,scrollbars,resizable';
my ($langselect, %execfiles, @links, @error, %linktexts, $response, $html);

sub form	{
  foreach (@rlmain::ringnames)	{
    if ($rlmain::data{$_})	{
      rlmain::entify($rlmain::data{$_});
    } else	{
      $rlmain::data{$_} = '';
    }
  }
  my ($ringid, $created);
  my $createdtext = gettext("Created");
  if ($rlmain::data{'routine'} eq 'Edit ring')	{
    $ringid = "<span style=\"font-family: 'courier new', monospace\">$rlmain::data{'ringid'}</span>\n"
    . "<input type=\"hidden\" name=\"ringid\" value=\"$rlmain::data{'ringid'}\" />";
    $created = ($rlmain::created ? "<span>$createdtext</span><br />"
    . "<span class=\"small\">$rlmain::created</span>\n" : '')
    . "<input type=\"hidden\" name=\"created\" value=\"$rlmain::data{'created'}\" />";
  } else	{
    $ringid = "<input class=\"text\" type=\"text\" size=\"15\" maxlength=\"15\" "
    . "name=\"ringid\" value=\"$rlmain::data{'ringid'}\" />";
    $created = '';
  }
  my $error = join ("\n", @rlmain::error);
  my $titletext = gettext("Ring title");
  my $desctext = gettext("Description");
  my $descmax = sprintf (gettext("max %d characters"), $rlmain::numcharringdesc);
  my $URLtext = gettext("Ring homepage URL");
  my $idtext = gettext("Ring ID");
  my $pwtext = gettext("Password");
  my $caution = gettext("Do not use<br />a valuable<br />password.");
  my $rmnametext = gettext("Ringmaster name");
  my $rmemailtext = gettext("Ringmaster email");
  my $checked = '';
  $checked = 'checked="checked"' if $rlmain::data{'allowsiteadd'} eq 'on';
  my $allowaddtext = gettext("Allow site additions");
  my $langtext = gettext("Language");
  &langselect;
  my $checked2nd = '';
  $checked2nd = 'checked="checked"' if $rlmain::data{'hide2ndURL'} eq 'on';
  my $hidetext = gettext("Hide HTML code URL");
  my $submittext = gettext("Submit");
  my $resettext = gettext("Reset");

  return qq~$error
<form method="post" action="$rlmain::cgiURL/$rlmain::action">
<table>
<tr>
<td colspan="3"><span>$titletext</span><br />
<input class="text" type="text" size="45" maxlength="45" name="ringtitle" value="$rlmain::data{'ringtitle'}" /></td>
</tr>
<tr>
<td colspan="3"><span>$desctext </span><span class="small">($descmax)</span><br />
<div class="textarea"><textarea name="ringdesc" rows="4" cols="45" $rlmain::wrapsoft>$rlmain::data{'ringdesc'}</textarea></div></td>
</tr>
<tr>
<td colspan="3"><span>$URLtext</span><br />
<input class="text" type="text" size="45" name="ringURL" value="$rlmain::data{'ringURL'}" /></td>
</tr>
<tr>
<td><span>$idtext</span><br />
$ringid</td>
<td><span>$pwtext</span><br />
<input class="text" type="text" size="15" maxlength="15" name="ringpw" value="$rlmain::data{'ringpw'}" /></td>
<td><span class="small" style="color: $rlmain::colerr">$caution</span></td>
</tr>
<tr>
<td colspan="3"><span>$rmnametext</span><br />
<input class="text" type="text" size="45" maxlength="45" name="rmname" value="$rlmain::data{'rmname'}" /></td>
</tr>
<tr>
<td colspan="3"><span>$rmemailtext</span><br />
<input class="text" type="text" size="45" name="rmemail" value="$rlmain::data{'rmemail'}" /></td>
</tr>
<tr>
<td><input class="text" type="checkbox" name="allowsiteadd" $checked />
<span>$allowaddtext</span></td>
<td rowspan="2" class="top"><span>$langtext</span><br />
$langselect</td>
<td rowspan="2" class="top">$created</td>
</tr>
<tr>
<td><input class="text" type="checkbox" name="hide2ndURL" $checked2nd />
<span>$hidetext</span></td>
</tr>
</table>
<input type="hidden" name="logoURL" value="$rlmain::data{'logoURL'}" />
<input type="hidden" name="logowidth" value="$rlmain::data{'logowidth'}" />
<input type="hidden" name="logoheight" value="$rlmain::data{'logoheight'}" />
<input type="hidden" name="colbg" value="$rlmain::data{'colbg'}" />
<input type="hidden" name="coltablebg" value="$rlmain::data{'coltablebg'}" />
<input type="hidden" name="coltxt" value="$rlmain::data{'coltxt'}" />
<input type="hidden" name="colemph" value="$rlmain::data{'colemph'}" />
<input type="hidden" name="colerr" value="$rlmain::data{'colerr'}" />
<input type="hidden" name="collink" value="$rlmain::data{'collink'}" />
<input type="hidden" name="colvlink" value="$rlmain::data{'colvlink'}" />
<input type="hidden" name="sitesperlistpage" value="$rlmain::data{'sitesperlistpage'}" />
<input type="hidden" name="dirmail" value="$rlmain::data{'dirmail'}" />
<input type="hidden" name="routine" value="$rlmain::data{'routine'}" />
<div class="center"><p>
<input class="button" type="submit" name="submit" value="$submittext" />&nbsp;&nbsp;&nbsp;<input class="button" type="reset" value="$resettext" />
</p></div>
</form>~;

}


sub appearanceform	{
  foreach (@rlmain::ringnames)	{
    if ($rlmain::data{$_})	{
      rlmain::entify($rlmain::data{$_});
    } else	{
      $rlmain::data{$_} = '';
    }
  }
  my $header = gettext("Customize appearance");
  my $error = join ("\n", @rlmain::error);
  my $logotext = gettext("Ring logo");
  my $imgURLtext = gettext("Image URL");
  my $widthtext = gettext("Image width");
  my $pixtext = gettext("pixels");
  my $heighttext = gettext("Image height");
  my $coltext = gettext("Ring colors");
  my $bodytext = gettext("Body background");
  my $tabletext = gettext("Table background");
  my $normal = gettext("Normal text");
  my $emph = gettext("Emphasized text");
  my $errtext = gettext("Error text");
  my $linktext = gettext("Links");
  my $vlinktext = gettext("Visited links");
  my $defaultcol = gettext("Get default colors");
  my $bgimage = gettext("You can use a background image instead of background color:");
  my $sizetext = gettext("Listpage size");
  my $maxnumtext = gettext("Max number of sites per listpage");
  my $select5 = '';
  my $select25 = '';
  my $select50 = '';
  $select5 = 'selected="selected"' if $rlmain::data{'sitesperlistpage'} == 5;
  $select25 = 'selected="selected"' if $rlmain::data{'sitesperlistpage'} == 25;
  $select50 = 'selected="selected"' if $rlmain::data{'sitesperlistpage'} == 50;
  my $submittext = gettext("Submit");

  return qq~<h4>$header</h4>
$error
<form method="post" action="$rlmain::cgiURL/$rlmain::action">
<table>
<tr>
<td colspan="2"><span style="font-weight: bold">$logotext</span></td>
</tr>
<tr>
<td colspan="2"><span>$imgURLtext</span><br />
<input class="text" type="text" size="45" name="logoURL" value="$rlmain::data{'logoURL'}" /></td>
</tr>
<tr>
<td class="top"><span>$widthtext </span><span class="small">($pixtext)</span><br />
<input class="text" type="text" size="3" maxlength="3" name="logowidth" value="$rlmain::data{'logowidth'}" /></td>
<td><span>$heighttext </span><span class="small">($pixtext)</span><br />
<input class="text" type="text" size="3" maxlength="3" name="logoheight" value="$rlmain::data{'logoheight'}" />
<br /><br /></td>
</tr>
<tr>
<td colspan="2"><span style="font-weight: bold">$coltext</span></td>
</tr>
<tr>
<td><span>$bodytext <sup>*)</sup></span><br />
<input class="text" type="text" size="20" name="colbg" value="$rlmain::data{'colbg'}" /></td>
<td><span>$tabletext <sup>*)</sup></span><br />
<input class="text" type="text" size="20" name="coltablebg" value="$rlmain::data{'coltablebg'}" /></td>
</tr>
<tr>
<td><span>$normal</span><br />
<input class="text" type="text" size="20" name="coltxt" value="$rlmain::data{'coltxt'}" /></td>
<td><span>$emph</span><br />
<input class="text" type="text" size="20" name="colemph" value="$rlmain::data{'colemph'}" /></td>
</tr>
<tr>
<td><span>$errtext</span><br />
<input class="text" type="text" size="20" name="colerr" value="$rlmain::data{'colerr'}" /></td>
<td><span>$linktext</span><br />
<input class="text" type="text" size="20" name="collink" value="$rlmain::data{'collink'}" /></td>
</tr>
<tr>
<td><span>$vlinktext</span><br />
<input class="text" type="text" size="20" name="colvlink" value="$rlmain::data{'colvlink'}" />
<br /><br /></td>
<td style="vertical-align: middle">
<span><input class="button" type="submit" name="submit" value="$defaultcol" /></span></td>
</tr>
<tr>
<td colspan="2"><span class="list"><sup>*)</sup>
$bgimage</span>
<pre style="color: $rlmain::colerr; font-size: 12px; margin-top: 0">  url(http://www.domain.com/path/background.jpg)
</pre></td>
</tr>
<tr>
<td colspan="2"><span style="font-weight: bold">$sizetext</span></td>
</tr>
<tr>
<td colspan="2"><p>$maxnumtext&nbsp;&nbsp;
<select name="sitesperlistpage" size="1"><option $select5 value="5">5</option>
<option $select25 value="25">25</option><option $select50 value="50">50</option>
</select></p></td>
</tr>
</table>
<input type="hidden" name="ringtitle" value="$rlmain::data{'ringtitle'}" />
<input type="hidden" name="ringdesc" value="$rlmain::data{'ringdesc'}" />
<input type="hidden" name="ringURL" value="$rlmain::data{'ringURL'}" />
<input type="hidden" name="ringid" value="$rlmain::data{'ringid'}" />
<input type="hidden" name="ringpw" value="$rlmain::data{'ringpw'}" />
<input type="hidden" name="rmname" value="$rlmain::data{'rmname'}" />
<input type="hidden" name="rmemail" value="$rlmain::data{'rmemail'}" />
<input type="hidden" name="allowsiteadd" value="$rlmain::data{'allowsiteadd'}" />
<input type="hidden" name="hide2ndURL" value="$rlmain::data{'hide2ndURL'}" />
<input type="hidden" name="ringlang" value="$rlmain::data{'ringlang'}" />
<input type="hidden" name="created" value="$rlmain::data{'created'}" />
<input type="hidden" name="dirmail" value="$rlmain::data{'dirmail'}" />
<input type="hidden" name="routine" value="$rlmain::data{'routine'}" />
<p><div class="center"><input class="button" type="submit" name="submit" value="$submittext" /></div></p>
</form>~;

}


sub validation	{
  rlmain::trim(@rlmain::data{ @rlmain::ringnames });
  if (!$rlmain::data{'ringtitle'})	{
    push (@rlmain::error, '<p class="error">' . gettext("You must enter Ring title.") . '</p>');
  }
  if (!$rlmain::data{'ringdesc'})	{
    push (@rlmain::error, '<p class="error">' . gettext("You must enter Description.") . '</p>');
  } elsif (length $rlmain::data{'ringdesc'} > $rlmain::numcharringdesc)	{
    push (@rlmain::error, '<p class="error">' . gettext("Too many characters in Description.") . '</p>');
  }
  $rlmain::data{'ringURL'} =~ s/ /%20/g;
  if (!$rlmain::data{'ringURL'} || 'http://' =~ /$rlmain::data{'ringURL'}/)	{
    push (@rlmain::error, '<p class="error">' . gettext("You must enter Ring homepage URL.") . '</p>');
  } else	{
    if ($rlmain::data{'ringURL'} !~ /^http\S*:\/\/\S+\.\S+/ || $rlmain::data{'ringURL'} =~ /["<>{}]/)	{
      push (@rlmain::error, '<p class="error">' . gettext("Ring homepage URL is not correctly filled.") . '</p>');
    }
  }
  unless ($rlmain::data{'routine'} eq 'Edit ring')	{
    if (!$rlmain::data{'ringid'})	{
      push (@rlmain::error, '<p class="error">' . gettext("You must enter Ring ID.") . '</p>');
    } elsif ($rlmain::data{'ringid'} =~ /\W|^_vti/)	{
      push (@rlmain::error, '<p class="error">' . gettext("Ring ID shall consist of letters and digits only.")
      . '</p>');
    } else	{ 
      rlmain::ringlist();
      foreach (@rlmain::rings, keys %rlmain::redir)	{
        if ("\L$_" eq "\L$rlmain::data{'ringid'}")	{
          $rlmain::data{'ringid'} = $_;
          push (@rlmain::error, '<p class="error">' . sprintf (
            gettext("Ring ID %s exists already. Choose another ring ID."),
            "&quot;$rlmain::data{'ringid'}&quot;") . '</p>');
          last;
        }
      }
    }
  }
  if (!$rlmain::data{'ringpw'})	{
    push (@rlmain::error, '<p class="error">' . gettext("You must enter Password.") . '</p>');
  } elsif ($rlmain::data{'ringpw'} =~ / /)	{
    push (@rlmain::error, '<p class="error">' . gettext("Password must not contain spaces.") . '</p>');
  } elsif ($rlmain::data{'ringpw'} =~ /["<>{}]/)	{
    push (@rlmain::error, '<p class="error">' . gettext("Password contains forbidden characters.") . '</p>');
  }
  if (!$rlmain::data{'rmname'})	{
    push (@rlmain::error, '<p class="error">' . gettext("You must enter Ringmaster name.") . '</p>');
  }
  if (!$rlmain::data{'rmemail'})	{
    push (@rlmain::error, '<p class="error">' . gettext("You must enter Ringmaster email.") . '</p>');
  } else	{
    my @addresses = split /(?: ?,)+ ?/, $rlmain::data{'rmemail'};
    $rlmain::data{'rmemail'} = join ', ', @addresses;
    for (@addresses)	{
      if (rlmain::emailsyntax($_))	{
        push (@rlmain::error, '<p class="error">' . gettext("Ringmaster email is not correctly filled.") . '</p>');
        last;
      }
    }
  }
}


sub appearancevalidation	{
  rlmain::trim(@rlmain::data{ @rlmain::ringnames });
  $rlmain::data{'logoURL'} =~ s/\s+//g;
  unless (!$rlmain::data{'logoURL'} || 'http://' =~ /$rlmain::data{'logoURL'}/)	{
    if ($rlmain::data{'logoURL'} !~ /^http\S*:\/\/\S+\.\S+/ || $rlmain::data{'logoURL'} =~ /["<>{}]/)	{
      push (@rlmain::error, '<p class="error">' . gettext("Ring logo URL is not correctly filled.") . '</p>');
    }
    if ($rlmain::data{'logowidth'} =~ /\D/)	{
      push (@rlmain::error, '<p class="error">' . gettext("Logo width shall consist of digits only.") . '</p>');
    } elsif ($rlmain::data{'logowidth'} =~ /\d/ && $rlmain::data{'logowidth'} > 250)	{
      push (@rlmain::error, '<p class="error">' . gettext("Logo width must not exceed 250 pixels.") . '</p>');
    }
    if ($rlmain::data{'logoheight'} =~ /\D/)	{
      push (@rlmain::error, '<p class="error">' . gettext("Logo height shall consist of digits only.") . '</p>');
    } elsif ($rlmain::data{'logoheight'} =~ /\d/ && $rlmain::data{'logoheight'} > 150)	{
      push (@rlmain::error, '<p class="error">' . gettext("Logo height must not exceed 150 pixels.") . '</p>');
    }
  }
  if (!$rlmain::data{'colbg'})	{
    push (@rlmain::error, '<p class="error">' . gettext("The body background field is empty.") . '</p>');
  } elsif ($rlmain::data{'colbg'} =~ /["<>{}]/)	{
    push (@rlmain::error, '<p class="error">' . gettext("Body background is not correctly filled.") . '</p>');
  }
  if (!$rlmain::data{'coltablebg'})	{
    push (@rlmain::error, '<p class="error">' . gettext("The table background field is empty.") . '</p>');
  } elsif ($rlmain::data{'coltablebg'} =~ /["<>{}]/)	{
    push (@rlmain::error, '<p class="error">' . gettext("Table background is not correctly filled.") . '</p>');
  }
  if (!$rlmain::data{'coltxt'})	{
    push (@rlmain::error, '<p class="error">' . gettext("The normal text color field is empty.") . '</p>');
  } elsif ($rlmain::data{'coltxt'} =~ /["<>{}]/)	{
    push (@rlmain::error, '<p class="error">' . gettext("Normal text color is not correctly filled.") . '</p>');
  }
  if (!$rlmain::data{'colemph'})	{
    push (@rlmain::error, '<p class="error">' . gettext("The emphasized text color field is empty.") . '</p>');
  } elsif ($rlmain::data{'colemph'} =~ /["<>{}]/)	{
    push (@rlmain::error, '<p class="error">' . gettext("Emphasized text color is not correctly filled.") . '</p>');
  }
  if (!$rlmain::data{'colerr'})	{
    push (@rlmain::error, '<p class="error">' . gettext("The error text color field is empty.") . '</p>');
  } elsif ($rlmain::data{'colerr'} =~ /["<>{}]/)	{
    push (@rlmain::error, '<p class="error">' . gettext("Error text color is not correctly filled.") . '</p>');
  }
  if (!$rlmain::data{'collink'})	{
    push (@rlmain::error, '<p class="error">' . gettext("The links color field is empty.") . '</p>');
  } elsif ($rlmain::data{'collink'} =~ /["<>{}]/)	{
    push (@rlmain::error, '<p class="error">' . gettext("Links color is not correctly filled.") . '</p>');
  }
  if (!$rlmain::data{'colvlink'})	{
    push (@rlmain::error, '<p class="error">' . gettext("The visited links color field is empty.") . '</p>');
  } elsif ($rlmain::data{'colvlink'} =~ /["<>{}]/)	{
    push (@rlmain::error, '<p class="error">' . gettext("Visited links color is not correctly filled.") . '</p>');
  }
}


sub langselect	{
  rlmain::langlist();
  $rlmain::data{'ringlang'} = $rlmain::lang unless $rlmain::lang{$rlmain::data{'ringlang'}};
  $langselect = "<select name=\"ringlang\" size=\"1\">\n";
  for (sort keys %rlmain::lang)	{
    $langselect .= '<option ' . ($_ eq $rlmain::data{'ringlang'} ? 'selected="selected" ' : '')
    . "value=\"$_\">$_ - $rlmain::lang{$_}</option>\n";
  }
  $langselect .= '</select>';
}


sub create	{
  my $ring;
  $rlmain::data{'ringid'} = rlmain::secureword ($rlmain::data{'ringid'});
  mkdir "$rlmain::datapath/$rlmain::data{'ringid'}", $rlmain::dirmode
   || die "Can't create '$rlmain::data{'ringid'}'\n$!";
  $rlmain::data{'created'} = rlmain::timestamp('date');
  foreach (@rlmain::ringnames)	{
    if ($rlmain::data{$_})	{
      $ring .= "$rlmain::data{$_}\n";
    } else	{
      $ring .= "\n";
    }
  }
  sysopen RING, "$rlmain::datapath/$rlmain::data{ringid}/ring.db", O_WRONLY|O_CREAT,
   $rlmain::filemode or die "Can't create '$rlmain::data{ringid}/ring.db'\n$!";
  flock RING, LOCK_EX or die $!;
  print RING $ring;
  close RING or die $!;
  rlmain::setlang ($rlmain::data{'ringlang'});
  sysopen CODE, "$rlmain::datapath/$rlmain::data{ringid}/htmlcode.txt", O_WRONLY|O_CREAT,
   $rlmain::filemode or die "Can't create '$rlmain::data{ringid}/htmlcode.txt'\n$!";
  flock CODE, LOCK_EX or die $!;
  print CODE defaultcode();
  close CODE or die $!;
  sysopen MSG, "$rlmain::datapath/$rlmain::data{ringid}/addpage.txt", O_WRONLY|O_CREAT,
   $rlmain::filemode or die "Can't create '$rlmain::data{ringid}/addpage.txt'\n$!";
  flock MSG, LOCK_EX or die $!;
  print MSG defaultaddpage();
  close MSG or die $!;
  sysopen MSG, "$rlmain::datapath/$rlmain::data{ringid}/addmail.txt", O_WRONLY|O_CREAT,
   $rlmain::filemode or die "Can't create '$rlmain::data{ringid}/addmail.txt'\n$!";
  flock MSG, LOCK_EX or die $!;
  print MSG defaultaddmail();
  close MSG or die $!;
  sysopen MSG, "$rlmain::datapath/$rlmain::data{ringid}/codepage.txt", O_WRONLY|O_CREAT,
   $rlmain::filemode or die "Can't create '$rlmain::data{ringid}/codepage.txt'\n$!";
  flock MSG, LOCK_EX or die $!;
  print MSG defaultcodepage();
  close MSG or die $!;
  tie my %ringstats, 'SDBM_File', "$rlmain::datapath/$rlmain::data{'ringid'}/stats",
   O_CREAT, $rlmain::filemode or die "Can't bind $rlmain::data{'ringid'}/stats\n$!";
  untie %ringstats;
  rlmain::sitecountupdate ($rlmain::data{'ringid'});
  my $intro = gettext("The following info was registered:");
  my $ringtitle = gettext("Ring title:");
  $ringtitle = $ringtitle . ' ' . ' ' x (25 - length($ringtitle)) . $rlmain::data{'ringtitle'};
  my $ringURL = gettext("Ring homepage URL:");
  $ringURL = $ringURL . ' ' . ' ' x (25 - length($ringURL)) . $rlmain::data{'ringURL'};
  my $ringid = gettext("Ring ID:");
  $ringid = $ringid . ' ' . ' ' x (25 - length($ringid)) . $rlmain::data{'ringid'};
  my $ringpw = gettext("Password:");
  $ringpw = $ringpw . ' ' . ' ' x (25 - length($ringpw)) . $rlmain::data{'ringpw'};
  my $rmname = gettext("Ringmaster name:");
  $rmname = $rmname . ' ' . ' ' x (25 - length($rmname)) . $rlmain::data{'rmname'};
  my $rmemail = gettext("Ringmaster email:");
  $rmemail = $rmemail . ' ' . ' ' x (25 - length($rmemail)) . $rlmain::data{'rmemail'};
  my $desc = gettext("Description:");
  my $enjoy = gettext("Enjoy your new ring!");
  my $mailcopy = $rlmain::adminemail if $rlmain::action =~ /newring/i;
  my $title = rlmain::nameclean($rlmain::title);

  my $body = qq~$intro

    $ringtitle
    $ringURL

    $ringid
    $ringpw

    $rmname
    $rmemail

    $desc
    $rlmain::data{'ringdesc'}


$enjoy

$rlmain::title
$rlmain::adminname
$rlmain::ringlinkURL
~;

  rlmain::email (
    $rlmain::data{'rmemail'},
    $mailcopy,
    "$title <$rlmain::adminemail>",
    gettext("New ring registered") . " [$rlmain::data{'ringid'}]",
    $body
  );
  rlmain::setlang ($rlmain::lang);
}


sub update	{
  my $ring;
  for (@rlmain::ringnames)	{
    if ($rlmain::data{$_})	{
      $ring .= "$rlmain::data{$_}\n";
    } else	{
      $ring .= "\n";
    }
  }
  $rlmain::data{'ringid'} = rlmain::secureword ($rlmain::data{'ringid'});
  open RING, "> $rlmain::datapath/$rlmain::data{'ringid'}/ring.db"
   or die "Can't open '$rlmain::data{'ringid'}/ring.db'\n$!";
  flock RING, LOCK_EX or die $!;
  print RING $ring;
  close RING or die $!;
}


sub menu	{
  my $masteradmin = '';
  my $ring = gettext("Ring:");
  my $master = gettext("Master admin");
  my $active = gettext("Active sites");
  my $inactive = gettext("Inactive sites");
  my $search = gettext("Search sites");
  my $check = gettext("Check sites");
  my $reorder = gettext("Reorder sites");
  my $email = gettext("Send email");
  my $edit = gettext("Edit ring");
  my $customize = gettext("Customize");
  opendir (DIR, "$rlmain::lib/RLDir") or die "Can't open /RLDir\n$!";
  $rlmain::numringdir = grep { /\.pm$/ } readdir (DIR);
  closedir DIR;
  my $directory = $rlmain::numringdir > 1 ? gettext("Directories") : gettext("Directory");
  my $backup = gettext("Backup ring");
  my $remove = gettext("Remove ring");
  my $new = gettext("New site");
  my $siteadm = gettext("Site admin");
  $rlmain::pagetitle = gettext("Ring admin");
  unless ($rlmain::data{'routine'} eq 'New site' && $rlmain::data{'submit'} && !@rlmain::error)	{
    rlmain::entify($rlmain::ringtitle);
  }

  $rlmain::ring_site = qq~<table cellspacing="8"><tr><td><span>$ring</span></td>
<td><span><a href="$rlmain::ringURL" target="Ringlink">
$rlmain::ringtitle</a></span></td></tr></table>~;

  if ($rlmain::action =~ /^admin/i)	{
    $masteradmin = "<div class=\"button\"><input class=\"button\" $rlmain::ns4width type=\"submit\" value=\"$master\" /></div>";
  }

  return qq~$masteradmin
<hr /><br /><input type="hidden" name="ringid" value="$rlmain::data{'ringid'}" />
<div class="button"><input class="button" $rlmain::ns4width type="submit" name="routine" value="$active" /></div>
<div class="button"><input class="button" $rlmain::ns4width type="submit" name="routine" value="$inactive" /></div>
<div class="button"><input class="button" $rlmain::ns4width type="submit" name="routine" value="$search" /></div>
<div class="button"><input class="button" $rlmain::ns4width type="submit" name="routine" value="$check" /></div>
<div class="button"><input class="button" $rlmain::ns4width type="submit" name="routine" value="$reorder" /></div>
<div class="button"><input class="button" $rlmain::ns4width type="submit" name="routine" value="$email" /></div>
<div class="button"><input class="button" $rlmain::ns4width type="submit" name="routine" value="$edit" /></div>
<div class="button"><input class="button" $rlmain::ns4width type="submit" name="routine" value="$customize" /></div>
<div class="button"><input class="button" $rlmain::ns4width type="submit" name="routine" value="$directory" /></div>
<div class="button"><input class="button" $rlmain::ns4width type="submit" name="routine" value="$backup" /></div>
<div class="button"><input class="button" $rlmain::ns4width type="submit" name="routine" value="$remove" /></div>
<div class="button"><input class="button" $rlmain::ns4width type="submit" name="routine" value="$new" /></div>
<div class="button"><input class="button" $rlmain::ns4width type="submit" name="routine" value="$siteadm" /></div>~;

}


sub customizemenu	{
  my $masteradmin = '';
  my $ringadmin;
  my $ring = gettext("Ring:");
  my $master = gettext("Master admin");
  my $ringadm = gettext("Ring admin");
  my $appear = gettext("Appearance");
  my $code = gettext("HTML code");
  my $addpage = gettext("Add page");
  my $addmail = gettext("Add mail");
  my $codepage = gettext("Code page");
  $rlmain::pagetitle = gettext("Ring customization");
  rlmain::entify($rlmain::ringtitle);

  $rlmain::ring_site = qq~<table cellspacing="8"><tr><td><span>$ring</span></td>
<td><span><a href="$rlmain::ringURL" target="Ringlink">
$rlmain::ringtitle</a></span></td></tr></table>~;

  if ($rlmain::action =~ /^admin/i)	{
    $masteradmin = "<div class=\"button\"><input class=\"button\" $rlmain::ns4width type=\"submit\" value=\"$master\" /></div>";
    $ringadmin = "<div class=\"button\"><input class=\"button\" $rlmain::ns4width type=\"submit\" name=\"routine\" value=\"$ringadm\" /></div>";
  } else	{
    $ringadmin = "<div class=\"button\"><input class=\"button\" $rlmain::ns4width type=\"submit\" value=\"$ringadm\" /></div>";
  }

  return qq~$masteradmin
$ringadmin
<hr /><br /><input type="hidden" name="ringid" value="$rlmain::data{'ringid'}" />
<div class="button"><input class="button" $rlmain::ns4width type="submit" name="routine" value="$appear" /></div>
<div class="button"><input class="button" $rlmain::ns4width type="submit" name="routine" value="$code" /></div>
<div class="button"><input class="button" $rlmain::ns4width type="submit" name="routine" value="$addpage" /></div>
<div class="button"><input class="button" $rlmain::ns4width type="submit" name="routine" value="$addmail" /></div>
<div class="button"><input class="button" $rlmain::ns4width type="submit" name="routine" value="$codepage" /></div>~;

}


sub siteadminform	{
  my $switchtext = gettext("Switch to the site admin menu");
  my $id = gettext("Site ID");
  my $switchbutton = gettext("Switch");
  $rlmain::pagemenu = &menu;
  $rlmain::data{'siteid'} = '' if !$rlmain::data{'siteid'};

  $rlmain::result = qq~<h4>$switchtext</h4>
@rlmain::error
<form method="post" action="$rlmain::cgiURL/$rlmain::action">
<span>$id</span><br />
<input class="text" type="text" size="15" maxlength="15" name="siteid" value="$rlmain::data{'siteid'}" />
<br /><br />
<input type="hidden" name="ringid" value="$rlmain::data{'ringid'}" />
<input type="hidden" name="routine" value="Site admin" />
<p><input class="button" type="submit" name="submit" value="$switchbutton" /></p>
</form>~;

}


sub logodisplay	{
  if ($rlmain::logoURL && 'http://' !~ /$rlmain::logoURL/)	{
    my $intro = gettext("This is what your logo looks like:");
    rlmain::entify($rlmain::ringtitle);

    return qq~
<p>$intro</p>
<img src="$rlmain::logoURL" width="$rlmain::logowidth" height="$rlmain::logoheight" alt="$rlmain::ringtitle" />~;

  } else	{
    return '';
  }
}


sub execfilenames	{
  opendir(DIR, $rlmain::cgipath) || die "Can't open $rlmain::cgipath\n$!";
  my @execfiles = grep { !/^\./ && -f "$rlmain::cgipath/$_" } readdir(DIR);
  closedir DIR;
  for (@execfiles)	{
    $execfiles{'next'}   = $_ if /^next(?:\.\w+)?$/i;
    $execfiles{'rand'}   = $_ if /^rand(?:\.\w+)?$/i;
    $execfiles{'list'}   = $_ if /^list(?:\.\w+)?$/i;
    $execfiles{'home'}   = $_ if /^home(?:\.\w+)?$/i;
    $execfiles{'prev'}   = $_ if /^prev(?:\.\w+)?$/i;
    $execfiles{'next5'}  = $_ if /^next5(?:\.\w+)?$/i;
    $execfiles{'prev5'}  = $_ if /^prev5(?:\.\w+)?$/i;
    $execfiles{'search'} = $_ if /^search(?:\.\w+)?$/i;
    $execfiles{'stats'}  = $_ if /^stats(?:\.\w+)?$/i and $rlmain::stats;
  }
}


sub defaultcode	{
  my $next = gettext("Next");
  my $rand = gettext("Random");
  my $list = gettext("List");
  execfilenames();

  return qq~<!-- BEGIN [ringtitle] code -->
<table><tr><td style="background: silver"><table cellpadding="5" border="2">
<tr>
<td colspan="3" style="background: #d0d0d0"><p style="text-align: center; margin: 0">
<a href="[cgiURL]/$execfiles{'home'}?ringid=$rlmain::data{'ringid'};siteid=[siteid]"
target="_top" style="font: bold 14px arial, helvetica, sans-serif">[ringtitle]</a></p></td>
</tr><tr>
<td style="background: #d0d0d0"><p style="text-align: center; margin: 0">
<a href="[cgiURL]/$execfiles{'next'}?ringid=$rlmain::data{'ringid'};siteid=[siteid]"
target="_top" style="font: bold 12px arial, helvetica, sans-serif">$next</a></p></td>
<td style="background: #d0d0d0"><p style="text-align: center; margin: 0">
<a href="[cgiURL]/$execfiles{'rand'}?ringid=$rlmain::data{'ringid'};siteid=[siteid]"
target="_top" style="font: bold 12px arial, helvetica, sans-serif">$rand</a></p></td>
<td style="background: #d0d0d0"><p style="text-align: center; margin: 0">
<a href="[cgiURL]/$execfiles{'list'}?ringid=$rlmain::data{'ringid'};siteid=[siteid]"
target="_top" style="font: bold 12px arial, helvetica, sans-serif">$list</a></p></td>
</tr>
</table></td></tr></table>
<!-- END [ringtitle] code -->~;

}


sub htmlcode	{
  $rlmain::htmlcode = $rlmain::data{'code'};
  $rlmain::htmlcode =~ s/\[ringtitle\]/$rlmain::ringtitle/g;
  $rlmain::htmlcode =~ s/\[cgiURL\]/$rlmain::cgiURL/g;
  rlmain::entify($rlmain::data{'code'});
  &execfilenames if !%execfiles;
  my $header = gettext("Customize HTML code");
  my $intro = gettext("These are substitutes that can be used\nin the customized code:");
  my $ringtitle = gettext("Ring title");
  my $cgiURL = sprintf (gettext("URL to folder with CGI files (%s etc.)"), $execfiles{'next'});
  my $id = gettext("Site ID");
  my $title = gettext("Site title");
  my $wmname = gettext("The site's webmaster name");
  my $wmemail = gettext("Note: Do not expose the webmaster's email address to\n&quot;spam&quot; by including it in the ring code.");
  my $navopt = gettext("Available navigation options");
  my $include = gettext("Included in the default code");
  my $stats = $rlmain::stats ? "<br />\n$execfiles{'stats'}" : '';
  my $extra = gettext("Extra options included in the Ringlink distribution");
  my $default = gettext("Get default code");
  my $preview = gettext("Preview");
  my $save = gettext("Save current code");
  my $reset = gettext("Reset");

  return qq~<h4>$header</h4>
<span>$intro</span>
<table>
<tr>
<td><span style="font-family: 'courier new', monospace">[ringtitle]&nbsp;&nbsp;&nbsp;</span></td>
<td><span>$ringtitle</span></td>
</tr>
<tr>
<td><span style="font-family: 'courier new', monospace">[cgiURL]</span></td>
<td><span>$cgiURL</span></td>
</tr>
<tr>
<td><span style="font-family: 'courier new', monospace">[siteid]</span></td>
<td><span>$id</span></td>
</tr>
<tr>
<td><span style="font-family: 'courier new', monospace">[sitetitle]</span></td>
<td><span>$title</span></td>
</tr>
<tr>
<td><span style="font-family: 'courier new', monospace">[wmname]</span></td>
<td><span>$wmname</span></td>
</tr>
<tr>
<td colspan="2"><span class="small" style="color: $rlmain::colerr; background: none">
$wmemail</span></td>
</tr>
</table>
<br />
<span style="font-weight: bold">$navopt</span>
<table border="1" cellpadding="5">
<tr>
<td><p>$execfiles{'home'}&nbsp;&nbsp;&nbsp;<br />
$execfiles{'next'}<br />
$execfiles{'rand'}<br />
$execfiles{'list'}</p></td>
<td class="top"><p>$include</p></td>
</tr>
<tr>
<td><p>$execfiles{'prev'}<br />
$execfiles{'next5'}<br />
$execfiles{'prev5'}<br />
$execfiles{'search'}$stats</p></td>
<td class="top"><p>$extra</p></td>
</tr>
</table>
<br />
<form method="post" action="$rlmain::cgiURL/$rlmain::action">
<div class="textarea"><textarea name="code" rows="10" cols="45" $rlmain::wrapoff>
$rlmain::data{'code'}</textarea></div>
<input type="hidden" name="ringid" value="$rlmain::data{'ringid'}" />
<input type="hidden" name="routine" value="$rlmain::data{'routine'}" />
<br /><br />

$rlmain::htmlcode
<br />
<table cellpadding="5">
<tr>
<td><p style="margin-top: 0; margin-bottom: 0.1em">
<input class="button" type="submit" name="submit" value="$default" /></p></td>
<td class="right"><p class="right" style="margin-top: 0; margin-bottom: 0.1em">
<input class="button" type="submit" name="submit" value="$preview" /></p></td>
</tr>
<tr>
<td><p style="margin-top: 0; margin-bottom: 0.1em">
<input class="button" type="submit" name="submit" value="$save" /></p></td>
<td class="right"><p class="right" style="margin-top: 0; margin-bottom: 0.1em">
<input class="button" type="submit" value="$reset" /></p></td>
</tr>
</table>
</form>~;

}


sub codeupdate	{
  $rlmain::data{'ringid'} = rlmain::secureword ($rlmain::data{'ringid'});
  open CODE, "> $rlmain::datapath/$rlmain::data{'ringid'}/htmlcode.txt"
   or die "Can't open '$rlmain::data{'ringid'}/htmlcode.txt'\n$!";
  flock CODE, LOCK_EX or die $!;
  print CODE $rlmain::data{'code'};
  close CODE or die $!;
}


sub defaultaddpage	{
  my $part1 = sprintf (gettext("The site %s was successfully submitted."),
    "\n<span style=\"font-weight: bold\">[sitetitle]\n</span>");
  my $part2 = sprintf (gettext("The information below will be\nemailed to %s as well."), '[wmemail]');
  my $part3 = sprintf (gettext("If you want to edit your site info,\nclick the %s button."), '&quot;' . gettext("Edit site") . '&quot;');
  my $part4 = sprintf (
    gettext("Note that your site is not active yet.\nThe next step is to insert the prescribed\npiece of HTML code. Get it from the email\nmessage or by clicking the %s button. You are\nexpected to copy the code and paste it on %s."),
    '&quot;' . gettext("Get code") . '&quot;', "\n<a href=\"[codeURL]\" target=\"Ringlink\">\n[codeURL]</a>");
  my $ringhomepage = gettext("ring homepage");
  my $part5 = sprintf (
    gettext("Please notify me when you have ensured\nthat your site is in compliance with the\nrules stated at the %s.\nIf everything is ok, your site will be\nactivated within a few days."),
    "<a href=\"[ringURL]\"\ntarget=\"Ringlink\">$ringhomepage</a>");

  return qq~<p class="success">$part1<br />
<span style="font-size: 11px; font-weight:
normal">$part2</span></p>

<p>$part3</p>

<p>$part4</p>

<p>$part5</p>

<p>[ringtitle]<br />
[rmname]<br />
<a href="mailto:[rmemail]">[rmemail]</a></p>
~;

}


sub addpage	{
  my $msg = '';
  my $header = gettext("Customize add page");
  my $intro = gettext("You can customize the resulting page that appears\nafter a new site has been added.");
  my $default = gettext("Get default code");
  my $preview = gettext("Preview");
  my $save = gettext("Save current code");
  my $reset = gettext("Reset");
  if ($rlmain::data{'submit'} eq gettext("Preview"))	{
    $msg = $rlmain::data{'addpage'};
    for (@rlmain::ringsubstitutes)	{
      $msg =~ s/\[$_\]/${$rlmain::refs{$_}}/g unless $_ eq 'rmemail';
    }
    (my $rmemail = $rlmain::rmemail) =~ s/,.+//;
    $msg =~ s/\[rmemail\]/$rmemail/g; 
  }

  return qq~<h4>$header</h4>
<p>$intro</p>
<form method="post" action="$rlmain::cgiURL/$rlmain::action">
<div class="textarea"><textarea name="addpage" rows="10" cols="45" $rlmain::wrapoff>
$rlmain::data{'addpage'}</textarea></div>
<input type="hidden" name="ringid" value="$rlmain::data{'ringid'}" />
<input type="hidden" name="routine" value="$rlmain::data{'routine'}" />
<br /><br />

$msg
<table cellpadding="5">
<tr>
<td><p style="margin-top: 0; margin-bottom: 0.1em">
<input class="button" type="submit" name="submit" value="$default" /></p></td>
<td class="right"><p class="right" style="margin-top: 0; margin-bottom: 0.1em">
<input class="button" type="submit" name="submit" value="$preview" /></p></td>
</tr>
<tr>
<td><p style="margin-top: 0; margin-bottom: 0.1em">
<input class="button" type="submit" name="submit" value="$save" /></p></td>
<td class="right"><p class="right" style="margin-top: 0; margin-bottom: 0.1em">
<input class="button" type="submit" value="$reset" /></p></td>
</tr>
</table>
</form>~;

}


sub addpageupdate	{
  $rlmain::data{'ringid'} = rlmain::secureword ($rlmain::data{'ringid'});
  open MSG, "> $rlmain::datapath/$rlmain::data{'ringid'}/addpage.txt"
   or die "Can't open '$rlmain::data{'ringid'}/addpage.txt'\n$!";
  flock MSG, LOCK_EX or die $!;
  print MSG $rlmain::data{'addpage'};
  close MSG or die $!;
}


sub defaultaddmail	{
  my $siteadmin = rlmain::filenamefix('siteadmin.pl');
  my $ringidtext = gettext("ring ID:");
  my $intro = sprintf (
    gettext("Thank you for your interest in the webring %s!\nYour site was successfully submitted, and the following\ninformation was registered:"),
    "\n[ringtitle] ($ringidtext $rlmain::data{'ringid'})");
  my $title = gettext("Site title:");
  $title = $title . ' ' . ' ' x (25 - length($title)) . '[sitetitle]';
  my $id = gettext("Site ID:");
  $id = $id . ' ' . ' ' x (25 - length($id)) . '[siteid]';
  my $pw = gettext("Password:");
  $pw = $pw . ' ' . ' ' x (25 - length($pw)) . '[sitepw]';
  my $entryURL = gettext("Site entry URL:");
  $entryURL = $entryURL . ' ' . ' ' x (25 - length($entryURL)) . '[entryURL]';
  my $codeURL = gettext("HTML code URL:");
  $codeURL = $codeURL . ' ' . ' ' x (25 - length($codeURL)) . '[codeURL]';
  my $wmname = gettext("Webmaster name:");
  $wmname = $wmname . ' ' . ' ' x (25 - length($wmname)) . '[wmname]';
  my $wmemail = gettext("Webmaster email:");
  $wmemail = $wmemail . ' ' . ' ' x (25 - length($wmemail)) . '[wmemail]';
  my $desc = gettext("Description:");
  my $keyw = gettext("Keywords:");
  my $para1 = sprintf (gettext("If you want to edit your site info, go to %s"), "\n[cgiURL]/$siteadmin?ringid=$rlmain::data{'ringid'}");
  my $para2 = gettext("Note that your site is not active yet. The next step is to\ninsert the following piece of HTML code:");
  my $para3 = sprintf (
    gettext("You are expected to copy the code and paste it on %s\n(You may be required to do some editing; check it out at\nthe ring homepage %s.)"),
    "\n[codeURL]", '[ringURL]');
  my $para4 = sprintf (
    gettext("Please notify me when you have ensured that your site is in\ncompliance with the rules stated at %s.\nIf everything is ok, your site will be activated within a\nfew days."),
    "\n[ringURL]");


  return qq~$intro

    $title

    $id
    $pw

    $entryURL
    $codeURL

    $wmname
    $wmemail

    $desc
    [sitedesc]

    $keyw
    [keywords]

$para1

$para2

[htmlcode]

$para3

$para4

[ringtitle]
[rmname]
[rmemail]
~;

}


sub addmail	{
  my $header = gettext("Customize add mail");
  my $intro = gettext("You can customize the email message that is sent to the webmaster\nafter a new site has been added.");
  my $default = gettext("Get default text");
  my $save = gettext("Save current text");
  my $reset = gettext("Reset");

  return qq~<h4>$header</h4>
<p>$intro</p>
<form method="post" action="$rlmain::cgiURL/$rlmain::action">
<div class="textarea"><textarea name="addmail" rows="14" cols="45" $rlmain::wrapoff>
$rlmain::data{'addmail'}</textarea></div>
<input type="hidden" name="ringid" value="$rlmain::data{'ringid'}" />
<input type="hidden" name="routine" value="$rlmain::data{'routine'}" />
<br /><br />
<p><input class="button" type="submit" name="submit" value="$default" />&nbsp;&nbsp;&nbsp;
<input class="button" type="submit" name="submit" value="$save" />&nbsp;&nbsp;&nbsp;
<input class="button" type="submit" value="$reset" /></p>
</form>~;

}


sub addmailupdate	{
  $rlmain::data{'ringid'} = rlmain::secureword ($rlmain::data{'ringid'});
  open MSG, "> $rlmain::datapath/$rlmain::data{'ringid'}/addmail.txt"
   or die "Can't open '$rlmain::data{'ringid'}/addmail.txt'\n$!";
  flock MSG, LOCK_EX or die $!;
  print MSG $rlmain::data{'addmail'};
  close MSG or die $!;
}


sub defaultcodepage	{
  my $ringhomepage = gettext("ring homepage");
  my $intro = sprintf (
    gettext("This is the piece of HTML code that you\nare expected to copy and paste on %s. The code is customized to\nfit your site, but there may still be\nrequirements at the %s to edit\nthe code."),
    "\n<a href=\"[codeURL]\" target=\"Ringlink\">\n[codeURL]</a>",
    "<a href=\"[ringURL]\"\ntarget=\"Ringlink\">$ringhomepage</a>");

  return qq~<p>$intro</p>
~;

}


sub codepage	{
  my $msg = '';
  my $header = gettext("Customize &quot;Get code&quot; page");
  my $intro = gettext("You can customize the introduction text on the page where\nwebmasters can get the HTML code.");
  my $default = gettext("Get default code");
  my $preview = gettext("Preview");
  my $save = gettext("Save current code");
  my $reset = gettext("Reset");
  if ($rlmain::data{'submit'} eq gettext("Preview"))	{
    $msg = $rlmain::data{'codepage'};
    for (@rlmain::ringsubstitutes)	{
      $msg =~ s/\[$_\]/${$rlmain::refs{$_}}/g unless $_ eq 'rmemail';
    }
    (my $rmemail = $rlmain::rmemail) =~ s/,.+//;
    $msg =~ s/\[rmemail\]/$rmemail/g;
  }

  return qq~<h4>$header</h4>
<p>$intro</p>
<form method="post" action="$rlmain::cgiURL/$rlmain::action">
<div class="textarea"><textarea name="codepage" rows="10" cols="45" $rlmain::wrapoff>
$rlmain::data{'codepage'}</textarea></div>
<input type="hidden" name="ringid" value="$rlmain::data{'ringid'}" />
<input type="hidden" name="routine" value="$rlmain::data{'routine'}" />
<br /><br />

$msg
<table cellpadding="5">
<tr>
<td><p style="margin-top: 0; margin-bottom: 0.1em">
<input class="button" type="submit" name="submit" value="$default" /></p></td>
<td class="right"><p class="right" style="margin-top: 0; margin-bottom: 0.1em">
<input class="button" type="submit" name="submit" value="$preview" /></p></td>
</tr>
<tr>
<td><p style="margin-top: 0; margin-bottom: 0.1em">
<input class="button" type="submit" name="submit" value="$save" /></p></td>
<td class="right"><p class="right" style="margin-top: 0; margin-bottom: 0.1em">
<input class="button" type="submit" value="$reset" /></p></td>
</tr>
</table>
</form>~;

}


sub codepageupdate	{
  $rlmain::data{'ringid'} = rlmain::secureword ($rlmain::data{'ringid'});
  open MSG, "> $rlmain::datapath/$rlmain::data{'ringid'}/codepage.txt"
   or die "Can't open '$rlmain::data{'ringid'}/codepage.txt'\n$!";
  flock MSG, LOCK_EX or die $!;
  print MSG $rlmain::data{'codepage'};
  close MSG or die $!;
}


sub adminlist	{
  my (@list, $listheader, $statuschange, $statuschangetext, $pass);
  my $completeinfo_checked = $rlmain::data{'completeinfo'} eq 'on' ? 'checked="checked"' : '';
  my $select5 = '';
  my $select25 = '';
  my $select50 = '';
  if ($rlmain::data{'routine'} =~ /Active[ _]sites/)	{
    @list = splice @rlmain::activesites;
    $listheader = gettext("Active sites");
    $statuschange = 'Deactivate';
    $statuschangetext = gettext("Deactivate");
    $pass = 'active';
  } elsif ($rlmain::data{'routine'} =~ /Inactive[ _]sites/)	{
    @list = splice @rlmain::inactivesites;
    $listheader = gettext("Inactive sites");
    $statuschange = 'Activate';
    $statuschangetext = gettext("Activate");
    $pass = 'inactive';
    unless ($rlmain::data{'submit'})	{
      $completeinfo_checked = 'checked="checked"';
      $rlmain::data{'completeinfo'} = 'on';
    }
  }
  my $completetext = gettext("Complete site info");
  my $sitestext = gettext("Sites per page");
  $rlmain::data{'sitesperpage'} = 25 if !$rlmain::data{'sitesperpage'};
  $select5 = 'selected="selected"' if $rlmain::data{'sitesperpage'} == 5;
  $select25 = 'selected="selected"' if $rlmain::data{'sitesperpage'} == 25;
  $select50 = 'selected="selected"' if $rlmain::data{'sitesperpage'} == 50;
  my $begin = gettext("Begin list with");
  my $order = gettext("Site order #");
  my $or = gettext("or");
  my $id = gettext("Site ID");
  my $show = gettext("Show list");

  my $headerform = qq~<form method="post" action="$rlmain::cgiURL/$rlmain::action">
<table width="100%">
<tr>
<td><p><input class="text" type="checkbox" name="completeinfo" $completeinfo_checked />
&nbsp;<span>$completetext</span></p><p>$sitestext&nbsp;&nbsp;
<select name="sitesperpage" size="1"><option $select5 value="5">5</option>
<option $select25 value="25">25</option><option $select50 value="50">50</option>
</select></p></td>
<td><span>$begin<br />
=> $order</span>&nbsp;&nbsp;
<input class="text" type="text" size="4" name="ordnumb" />&nbsp;&nbsp;<span>$or<br />
=> $id</span>&nbsp;&nbsp;
<input class="text" type="text" size="15" maxlength="15" name="siteid" /></td>
</tr>
</table>
<input type="hidden" name="ringid" value="$rlmain::data{'ringid'}" />
<input type="hidden" name="routine" value="$rlmain::data{'routine'}" />
<p><input class="button" type="submit" name="submit" value="$show" /></p>
</form>~;

  $rlmain::result = qq~<h4>$listheader</h4>
$headerform
<br />
@rlmain::error
~;

  unless ($rlmain::nolist)	{
    $rlmain::data{'completeinfo'} = '' unless $rlmain::data{'completeinfo'};
    my $numb = ($rlmain::data{'numb'} ? $rlmain::data{'numb'}
             : ($rlmain::data{'offset'} + $rlmain::data{'sitesperpage'} > scalar @list - 1
                ? scalar @list - $rlmain::data{'offset'} : $rlmain::data{'sitesperpage'})
             );
    $rlmain::result .= '<h5 style="margin-top: 0">' . sprintf (gettext("Site %d - %d of %d"), $rlmain::data{'offset'} + 1,
      $rlmain::data{'offset'} + $numb, scalar @list) . "</h5>\n";
    my ($prevoffset, $prevnumb);
    if ($rlmain::data{'offset'} - $rlmain::data{'sitesperpage'} < 0)	{
      $prevoffset = 0;
      $prevnumb = $rlmain::data{'offset'};
    } else	{
      $prevoffset = $rlmain::data{'offset'} - $rlmain::data{'sitesperpage'};
      $prevnumb = $rlmain::data{'sitesperpage'};
    }
    my $nextoffset = $rlmain::data{'offset'} + $numb;
    my $nextnumb = '';
    if ($rlmain::data{'offset'} + $numb + $rlmain::data{'sitesperpage'} > scalar @list)	{
      $nextnumb = scalar @list - $rlmain::data{'offset'} - $numb;
    } else	{
      $nextnumb = $rlmain::data{'sitesperpage'};
    }
    (my $routine = $rlmain::data{'routine'}) =~ s/ /_/g;

    my $buttons_pre = qq~<form method="post" action="$rlmain::cgiURL/$rlmain::action">
<input type="hidden" name="ringid" value="$rlmain::ringid" />
<input type="hidden" name="routine" value="$routine" />
<input type="hidden" name="completeinfo" value="$rlmain::data{'completeinfo'}" />
<input type="hidden" name="sitesperpage" value="$rlmain::data{'sitesperpage'}" />~;

    my $prevtext = sprintf (gettext("Previous %d sites"), $prevnumb);
    my $nexttext = sprintf (gettext("Next %d sites"), $nextnumb);

    my $prevbutton = qq~$buttons_pre
<input type="hidden" name="offset" value="$prevoffset" />
<input type="hidden" name="numb" value="$prevnumb" />
<input class="button" type="submit" value="&lt;&lt;&nbsp;&nbsp;$prevtext" />
</form>~;

    my $nextbutton = qq~$buttons_pre
<input type="hidden" name="offset" value="$nextoffset" />
<input type="hidden" name="numb" value="$nextnumb" />
<input class="button" type="submit" value="$nexttext&nbsp;&nbsp;&gt;&gt;" />
</form>~;

    my $buttons;
    if ($rlmain::data{'offset'} > 0 && $nextnumb > 0)	{
      $buttons = "\n<br />\n<table width=\"100%\"><tr>\n<td class=\"top\">$prevbutton</td>\n"
      . "<td><div class=\"right\">\n$nextbutton\n</div></td>\n</tr></table>";
    } elsif ($rlmain::data{'offset'} > 0 && $nextnumb <= 0)	{
      $buttons = "\n<br />\n<table><tr>\n<td>$prevbutton</td>\n</tr></table>";
    } elsif (!$rlmain::data{'offset'} && $numb < scalar @list)	{
      $buttons = "\n<br />\n<table align=\"right\"><tr>\n<td>$nextbutton</td>\n</tr></table>";
    }
    @list = splice (
     @list,
     $rlmain::data{'offset'},
     ($rlmain::data{'numb'} ? $rlmain::data{'numb'} : $rlmain::data{'sitesperpage'})
    );
    my $pw = gettext("Password:");
    my $desc = gettext("Description:");
    my $keyw = gettext("Keywords:");
    my $codeURL = gettext("HTML code URL:");
    my $updated = gettext("Last updated:");
    my $edit = gettext("Edit");
    my $remove = gettext("Remove");
    my $i = $rlmain::data{'offset'};
    for (@list)	{
      my @sitevalues = split (/\t/, $_);
      for (@rlmain::sitenames)	{
        ${$rlmain::refs{$_}} = shift (@sitevalues);
        rlmain::entify(${ $rlmain::refs{$_} });
      }
      $i++;
      my $extinfo = '';
      if ($rlmain::data{'completeinfo'} eq 'on')	{

        $extinfo = qq~<br />
$pw <span class="list">$rlmain::sitepw</span><br />
$desc <span class="list">$rlmain::sitedesc</span><br />
$keyw <span class="list">$rlmain::keywords</span><br />
$codeURL <span class="list"><a href="$rlmain::codeURL" target="Ringlink">
$rlmain::codeURL</a></span><br />
$updated <span class="list">$rlmain::updated</span>~;

      }

      $rlmain::result .= qq~
<p>$i. $rlmain::siteid - <a href="$rlmain::entryURL" target="Ringlink">
$rlmain::sitetitle</a><br />
<a href="$rlmain::cgiURL/$rlmain::action?ringid=$rlmain::ringid;siteid=$rlmain::siteid;routine=$statuschange;pass=$pass;completeinfo=$rlmain::data{'completeinfo'};sitesperpage=$rlmain::data{'sitesperpage'}">
$statuschangetext</a> |
<a href="$rlmain::cgiURL/$rlmain::action?ringid=$rlmain::ringid;siteid=$rlmain::siteid;routine=Edit_site;pass=$pass;completeinfo=$rlmain::data{'completeinfo'};sitesperpage=$rlmain::data{'sitesperpage'}">
$edit</a> |
<a href="$rlmain::cgiURL/$rlmain::action?ringid=$rlmain::ringid;siteid=$rlmain::siteid;routine=Remove_site;pass=$pass;completeinfo=$rlmain::data{'completeinfo'};sitesperpage=$rlmain::data{'sitesperpage'}">
$remove</a> - <a href="mailto:$rlmain::wmname &lt;$rlmain::wmemail&gt;">$rlmain::wmname</a>$extinfo</p>
~;

    }
    $rlmain::result .= $buttons if $buttons;
    $rlmain::result .= "\n<br />";
  }
  $rlmain::pagemenu = &menu;
  rlmain::adminhtml();
  rlmain::exit();
}


sub Search_sites	{
  my @list = ();
  rlmain::sitelist();
  if (!@rlmain::sites)	{
    $rlmain::result = $rlmain::data{'pass'} ? $rlmain::error[0]
     : '<p class="error">' . gettext("There are no sites in this ring.") . '</p>';
    $rlmain::pagemenu = &menu;
    rlmain::adminhtml();
    rlmain::exit();
  }
  if ($rlmain::data{'submit'} && !$rlmain::data{'search'} && !$rlmain::data{'pass'})	{
    push (@rlmain::error, '<p class="error">' . gettext("Enter a string to search for!") . '</p>');
    $rlmain::nolist = 1;
  } elsif (!$rlmain::data{'search'})	{
    $rlmain::nolist = 1;
  } else	{
    open (SITES, "< $rlmain::datapath/$rlmain::data{'ringid'}/sites.db")
     || die "Can't open '$rlmain::data{'ringid'}/sites.db'\n$!";
    while (<SITES>)	{
      use locale;
      push (@list, $_) if $_ =~ /\Q$rlmain::data{'search'}/i;
    }
    close (SITES);
    rlmain::entify($rlmain::data{'search'});
    unless (@list)	{
      push (@rlmain::error, '<p class="success">' . sprintf (gettext("No sites were found containing: %s"),
       "<br />\n<span style=\"color: $rlmain::coltxt\">&quot;<tt>$rlmain::data{'search'}</tt>&quot;</span>") . '</p>');
      $rlmain::nolist = 1;
    } 
  }

  my $listheader = gettext("Search sites");
  my $completeinfo_checked = $rlmain::data{'completeinfo'} eq 'on' ? 'checked="checked"' : '';
  my $completetext = gettext("Complete site info");
  my $sitestext = gettext("Sites per page");
  $rlmain::data{'sitesperpage'} = 25 if !$rlmain::data{'sitesperpage'};
  my $select5 = $rlmain::data{'sitesperpage'} == 5 ? 'selected="selected"' : '';
  my $select25 = $rlmain::data{'sitesperpage'} == 25 ? 'selected="selected"' : '';
  my $select50 = $rlmain::data{'sitesperpage'} == 50 ? 'selected="selected"' : '';
  my $searchtext = gettext("Search for:");
  $rlmain::data{'search'} = '' if !$rlmain::data{'search'};
  my $searchbutton = gettext("Search");

  my $headerform = qq~<form method="post" action="$rlmain::cgiURL/$rlmain::action">
<table width="100%" cellpadding="3">
<tr>
<td style="vertical-align: bottom"><input class="text" type="checkbox" name="completeinfo" $completeinfo_checked />
&nbsp;<span>$completetext</span></td>
<td><span>$sitestext&nbsp;&nbsp;
<select name="sitesperpage" size="1"><option $select5 value="5">5</option>
<option $select25 value="25">25</option><option $select50 value="50">50</option>
</select></span></td>
</tr>
<tr>
<td colspan="2"><span>$searchtext</span><br />
<input class="text" type="text" size="25" name="search" value="$rlmain::data{'search'}" />
<input type="hidden" name="ringid" value="$rlmain::data{'ringid'}" />
<input type="hidden" name="routine" value="$rlmain::data{'routine'}" />
<input class="button" type="submit" name="submit" value="$searchbutton" /></td>
</tr>
</table>
</form>~;

  $rlmain::result = qq~<h4>$listheader</h4>
$headerform
<br />
@rlmain::error
~;

  unless ($rlmain::nolist)	{
    if ($rlmain::data{'submit'} || (!$rlmain::data{'submit'} && !$rlmain::data{'offset'}))	{
      $rlmain::data{'offset'} = 0;
    }
    $rlmain::data{'completeinfo'} = '' unless $rlmain::data{'completeinfo'};
    my $numb = ($rlmain::data{'offset'} + $rlmain::data{'sitesperpage'} > scalar @list - 1
                ? scalar @list - $rlmain::data{'offset'} : $rlmain::data{'sitesperpage'});
    $rlmain::result .= '<h5 style="margin-top: 0">' . sprintf (gettext("Site %d - %d of %d"), $rlmain::data{'offset'} + 1,
      $rlmain::data{'offset'} + $numb, scalar @list) . "</h5>\n";
    my ($prevoffset, $prevnumb);
    if ($rlmain::data{'offset'} - $rlmain::data{'sitesperpage'} < 0)	{
      $prevoffset = 0;
      $prevnumb = $rlmain::data{'offset'};
    } else	{
      $prevoffset = $rlmain::data{'offset'} - $rlmain::data{'sitesperpage'};
      $prevnumb = $rlmain::data{'sitesperpage'};
    }
    my $nextoffset = $rlmain::data{'offset'} + $numb;
    my $nextnumb = '';
    if ($rlmain::data{'offset'} + $numb + $rlmain::data{'sitesperpage'} > scalar @list)	{
      $nextnumb = scalar @list - $rlmain::data{'offset'} - $numb;
    } else	{
      $nextnumb = $rlmain::data{'sitesperpage'};
    }
    (my $routine = $rlmain::data{'routine'}) =~ s/ /_/g;

    my $buttons_pre = qq~<form method="post" action="$rlmain::cgiURL/$rlmain::action">
<input type="hidden" name="ringid" value="$rlmain::ringid" />
<input type="hidden" name="routine" value="$routine" />
<input type="hidden" name="search" value="$rlmain::data{'search'}" />
<input type="hidden" name="completeinfo" value="$rlmain::data{'completeinfo'}" />
<input type="hidden" name="sitesperpage" value="$rlmain::data{'sitesperpage'}" />~;

    my $prevtext = sprintf (gettext("Previous %d sites"), $prevnumb);
    my $nexttext = sprintf (gettext("Next %d sites"), $nextnumb);

    my $prevbutton = qq~$buttons_pre
<input type="hidden" name="offset" value="$prevoffset" />
<input type="hidden" name="numb" value="$prevnumb" />
<input class="button" type="submit" value="&lt;&lt;&nbsp;&nbsp;$prevtext" />
</form>~;

    my $nextbutton = qq~$buttons_pre
<input type="hidden" name="offset" value="$nextoffset" />
<input type="hidden" name="numb" value="$nextnumb" />
<input class="button" type="submit" value="$nexttext&nbsp;&nbsp;&gt;&gt;" />
</form>~;

    my $buttons;
    if ($rlmain::data{'offset'} > 0 && $nextnumb > 0)	{
      $buttons = "\n<br />\n<table width=\"100%\"><tr>\n<td class=\"top\">$prevbutton</td>\n"
      . "<td><div class=\"right\">\n$nextbutton\n</div></td>\n</tr></table>";
    } elsif ($rlmain::data{'offset'} > 0 && $nextnumb <= 0)	{
      $buttons = "\n<br />\n<table><tr>\n<td>$prevbutton</td>\n</tr></table>";
    } elsif (!$rlmain::data{'offset'} && $numb < scalar @list)	{
      $buttons = "\n<br />\n<table align=\"right\"><tr>\n<td>$nextbutton</td>\n</tr></table>";
    }
    @list = splice (
     @list,
     $rlmain::data{'offset'},
     ($rlmain::data{'numb'} ? $rlmain::data{'numb'} : $rlmain::data{'sitesperpage'})
    );
    my $pw = gettext("Password:");
    my $desc = gettext("Description:");
    my $keyw = gettext("Keywords:");
    my $codeURL = gettext("HTML code URL:");
    my $updated = gettext("Last updated:");
    my $edit = gettext("Edit");
    my $remove = gettext("Remove");
    my $i = $rlmain::data{'offset'};
    for (@list)	{
      my @sitevalues = split (/\t/, $_);
      for (@rlmain::sitenames)	{
        ${$rlmain::refs{$_}} = shift (@sitevalues);
        rlmain::entify(${ $rlmain::refs{$_} });
      }
      my $statuschange = $rlmain::status eq 'active' ? 'Deactivate' : 'Activate';
      my $statuschangetext = $rlmain::status eq 'active' ? gettext("Deactivate") : gettext("Activate");
      $i++;
      my $extinfo = '';
      if ($rlmain::data{'completeinfo'} eq 'on')	{

        $extinfo = qq~<br />
$pw <span class="list">$rlmain::sitepw</span><br />
$desc <span class="list">$rlmain::sitedesc</span><br />
$keyw <span class="list">$rlmain::keywords</span><br />
$codeURL <span class="list"><a href="$rlmain::codeURL" target="Ringlink">
$rlmain::codeURL</a></span><br />
$updated <span class="list">$rlmain::updated</span>~;

      }

      $rlmain::result .= qq~
<p>$i. $rlmain::siteid - <a href="$rlmain::entryURL" target="Ringlink">
$rlmain::sitetitle</a><br />
<a href="$rlmain::cgiURL/$rlmain::action?ringid=$rlmain::ringid;siteid=$rlmain::siteid;routine=$statuschange;pass=search;completeinfo=$rlmain::data{'completeinfo'};sitesperpage=$rlmain::data{'sitesperpage'}">
$statuschangetext</a> |
<a href="$rlmain::cgiURL/$rlmain::action?ringid=$rlmain::ringid;siteid=$rlmain::siteid;routine=Edit_site;pass=search;completeinfo=$rlmain::data{'completeinfo'};sitesperpage=$rlmain::data{'sitesperpage'}">
$edit</a> |
<a href="$rlmain::cgiURL/$rlmain::action?ringid=$rlmain::ringid;siteid=$rlmain::siteid;routine=Remove_site;pass=search;completeinfo=$rlmain::data{'completeinfo'};sitesperpage=$rlmain::data{'sitesperpage'}">
$remove</a> - <a href="mailto:$rlmain::wmname &lt;$rlmain::wmemail&gt;">$rlmain::wmname</a>$extinfo</p>
~;

    }
    $rlmain::result .= $buttons if $buttons;
    $rlmain::result .= "\n<br />";
  }
  $rlmain::pagemenu = &menu;
  rlmain::adminhtml();
  rlmain::exit();
}


sub inactivesort	{
  # sort inactive sites by last updated info
  my %dates = ();
  for my $site (@rlmain::inactivesites)	{
    my @sitevalues = split /\t/, $site;
    ${$rlmain::refs{$_}} = shift @sitevalues for @rlmain::sitenames;
    if ($rlmain::updated =~ /^\d+ \w{3}/)	{  # convert the old date format
      my @updated = split / /, $rlmain::updated;
      my $i = 1;
      for (qw/Jan Feb Mar Apr May Jun Jul Aug Sep Oct Nov Dec/)	{
        if ($updated[1] eq $_)	{
          $updated[1] = $i;
          last;
        }
        $i++;
      }
      $updated[$_] = sprintf "%02d", $updated[$_] for (0,1);
      $dates{$site} = "$updated[2]-$updated[1]-$updated[0]T$updated[3]Z";
    } else	{
      $dates{$site} = $rlmain::updated;
    }
  }
  @rlmain::inactivesites = sort { $dates{$b} cmp $dates{$a} } keys %dates;
}


sub removeform	{
  my $emailnote_yes = '';
  my $emailnote_no = '';
  my $disabled = '';
  my $emailnote = '';
  my $notify = gettext("Notify the ringmaster?");
  my $yes = gettext("Yes");
  my $no = gettext("No");
  my $header = gettext("Remove ring");
  my $surequery = gettext("Are you sure you want to remove this ring,\nincluding all the sites in it?");
  my $sure = gettext("I am sure.");
  my $remove = gettext("Remove");
  my $cancel = gettext("Cancel");
  $rlmain::pagemenu = &menu;
  if ($rlmain::action =~ /^admin/i)	{
    if (!$rlmain::data{'submit'})	{
      if ($rlmain::nomail)	{
        $disabled = 'disabled="disabled"';
        $emailnote_no = 'checked="checked"';
      } else	{
        my $msg = sprintf (gettext("Your ring %s has been removed from %s."),
          "&quot;$rlmain::ringtitle&quot;", "&quot;$rlmain::title&quot;");
        $emailnote_yes = 'checked="checked"';

        $rlmain::data{'body'} = qq~$msg

$rlmain::title
$rlmain::adminname
$rlmain::ringlinkURL
~;

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

  }

  $rlmain::result = qq~<h4 style="margin-top: 0">$header</h4>
@rlmain::error
<form method="post" action="$rlmain::cgiURL/$rlmain::action">
<p>$surequery<br />
<input class="text" type="checkbox" name="removesure" />&nbsp;<span>$sure</span></p>
$emailnote
<input type="hidden" name="ringid" value="$rlmain::data{'ringid'}" />
<input type="hidden" name="routine" value="$rlmain::data{'routine'}" />
<p><input class="button" type="submit" name="submit" value="$remove" />
&nbsp;&nbsp;&nbsp;<input class="button" type="submit" name="submit" value="$cancel" /></p>
</form>~;

}


sub remove	{
  $rlmain::data{'ringid'} = rlmain::secureword ($rlmain::data{'ringid'});
  rlmain::sitelist();
  for (@rlmain::sites)	{
    $_ = rlmain::secureword ($_);
    rlmain::removedirectory ("$rlmain::datapath/$rlmain::data{'ringid'}/$_");
  }
  rlmain::removedirectory ("$rlmain::datapath/$rlmain::data{'ringid'}");
  if ($rlmain::stats)	{
    unlink "$rlmain::sitecountpath/$rlmain::data{'ringid'}.js"
     or die "Can't remove $rlmain::data{'ringid'}.js\n$!";
  }
}


sub removemail	{
  if ($rlmain::data{'emailnote'} eq 'yes')	{
    my $title=rlmain::nameclean($ring::title);

    rlmain::email (
      $rlmain::rmemail,
      $rlmain::adminemail,
      "$title <$rlmain::adminemail>",
      gettext("Ring removed"),
      $rlmain::data{'body'}
    );
  }
}


sub removemail2	{
  my $body = sprintf (gettext("Your ring %s has been removed from %s."),
    "\"$rlmain::ringtitle\"", "\"$rlmain::title\"");
  my $title=rlmain::nameclean($rlmain::title);
  rlmain::email (
    $rlmain::rmemail,
    $rlmain::adminemail,
    "$title <$rlmain::adminemail>",
    gettext("Ring removed"),
    $body
  );
}


sub checksites	{
  my $error;
  if ($rlmain::data{'details'})	{
    open (CHECKINFO, "< $rlmain::datapath/checkresults_$rlmain::ringid.txt")
     || die "Can't open 'checkresults_$rlmain::ringid.txt'\n$!";
    my @code = <CHECKINFO>;
    close (CHECKINFO);
    print "Content-type: text/html\n\n";
    print "<pre>\n", rlmain::entify(join '', @code), "\n</pre>";
    rlmain::exit();
  } elsif ($rlmain::data{'submit'})	{
    @links = ();
    my @options = qw/next rand list home prev next5 prev5 search/;
    push @options, 'stats' if $rlmain::stats;
    for (@options)	{
      if ($rlmain::data{'method'} eq 'all')	{
        my $htmlcode = rlmain::htmlcode();
        push (@links, $_) if $htmlcode =~ /\/$_(\.\w{2,3})?\?/i;
      } else	{
        push (@links, $_) if $rlmain::data{$_} eq 'on';
      }
    }
    if ($rlmain::data{'method'} eq 'all' && !@links)	{
      push (@rlmain::error, '<p class="error">'
      . gettext("There are no Ringlink links in the customized HTML code.") . '</p>');
    }
    if ($rlmain::data{'limit'} eq 'yes')	{
      if (!$rlmain::data{'startsite'})	{
        push (@rlmain::error, '<p class="error">'
        . gettext("You must enter the order number of the first site to be checked.") . '</p>');
      } elsif ($rlmain::data{'startsite'} =~ /[^\d]/ || $rlmain::data{'startsite'} < 1
         || $rlmain::data{'startsite'} > scalar @rlmain::activesites)	{
        push (@rlmain::error, '<p class="error">' . sprintf (
          gettext("The site order number must be a number between 1 and %d."), scalar @rlmain::activesites) . '</p>');
      }
    }
  }
  if (!eval "require LWP::UserAgent")	{
    (my $error = $@) =~ s/\n/<br \/>\n/g;
    $rlmain::result .= rlmain::missingsoftware() . "<p><tt>$error</tt></p>";
    rlmain::adminhtml();
    rlmain::exit();
  } elsif (!$rlmain::data{'submit'} || @rlmain::error)	{
    my $intro = gettext("By clicking the button below, you make Ringlink check the\nvalidity of sites in the ring. When checking active sites, those\nsites which fail the check are listed, while those sites which\npass are listed as well when checking inactive sites.");
    my $fails = gettext("A site can fail the check because 1) the server is down or\n2) the URL is not valid or 3) the HTML code is not correct.");
    my $checked_all    = '';
    my $codelinks = gettext("Check those Ringlink links that<br />are included in\nthe customized<br />HTML code");
    my $checked_sample = '';
    my $selectedlinks  = gettext("Check selected links");
    my $checked_next   = '';
    my $next           = gettext("Next");
    my $checked_prev   = '';
    my $prev           = gettext("Previous");
    my $checked_list   = '';
    my $list           = gettext("List");
    my $checked_rand   = '';
    my $rand           = gettext("Random");
    my $checked_next5  = '';
    my $next5          = gettext("Next 5");
    my $checked_prev5  = '';
    my $prev5          = gettext("Previous 5");
    my $checked_search = '';
    my $search         = gettext("Search");
    my $checked_stats  = '';
    my $stats          = gettext("Stats");
    my $checked_home   = '';
    my $home           = gettext("Home");
    my $timeout_5 = '';
    my $timeout_15 = '';
    my $timeout_30 = '';
    my $checked_no     = '';
    my $checked_yes    = '';
    my $checked_unreg  = '';
    my $select_5  = '';
    my $select_25 = '';
    my $select_50 = '';

    $checked_all    = 'checked="checked"' if !$rlmain::data{'submit'} || $rlmain::data{'method'} eq 'all';
    $checked_sample = 'checked="checked"' if $rlmain::data{'method'} eq 'sample';
    $checked_next   = 'checked="checked"' if !$rlmain::data{'submit'} || $rlmain::data{'next'} eq 'on';
    $checked_prev   = 'checked="checked"' if $rlmain::data{'prev'} eq 'on';
    $checked_list   = 'checked="checked"' if !$rlmain::data{'submit'} || $rlmain::data{'list'} eq 'on';
    $checked_rand   = 'checked="checked"' if $rlmain::data{'rand'} eq 'on';
    $checked_next5  = 'checked="checked"' if $rlmain::data{'next5'} eq 'on';
    $checked_prev5  = 'checked="checked"' if $rlmain::data{'prev5'} eq 'on';
    $checked_search = 'checked="checked"' if $rlmain::data{'search'} eq 'on';
    $checked_stats  = 'checked="checked"' if $rlmain::data{'stats'} eq 'on';
    $checked_home   = 'checked="checked"' if !$rlmain::data{'submit'} || $rlmain::data{'home'} eq 'on';
    $timeout_5 = 'selected="selected"' if $rlmain::data{'timeout'} == 5;
    $timeout_15 = 'selected="selected"' if !$rlmain::data{'submit'} || $rlmain::data{'timeout'} == 15;
    $timeout_30 = 'selected="selected"' if $rlmain::data{'timeout'} == 30;
    $checked_no     = 'checked="checked"' if !$rlmain::data{'submit'} || $rlmain::data{'limit'} eq 'no';
    $checked_yes    = 'checked="checked"' if $rlmain::data{'limit'} eq 'yes';
    $checked_unreg  = 'checked="checked"' if $rlmain::data{'limit'} eq 'unreg';
    $select_5  = 'selected="selected"' if $rlmain::data{'maxsites'} == 5;
    $select_25 = 'selected="selected"' if !$rlmain::data{'submit'} || $rlmain::data{'maxsites'} == 25;
    $select_50 = 'selected="selected"' if $rlmain::data{'maxsites'} == 50;
    my $statsoption = $rlmain::stats ? "<tr>\n" . '  <td><input class="text" type="checkbox" '
    . 'name="stats" ' . "$checked_stats /><span>$stats</span></td>\n  <td></td>\n  </tr>" : '';
    my $timeout = sprintf (gettext("Try to connect to a server<br />for max %s seconds"),
      "<select name=\"timeout\" size=\"1\">\n<option $timeout_5 value=\"5\">5</option>" .
      "<option $timeout_15 value=\"15\">15</option>\n<option $timeout_30 value=\"30\">30</option></select>");
    my $alltext = gettext("Check all active sites");
    my $maxtext = sprintf (gettext("Check max %s sites"), "<select name=\"maxsites\" size=\"1\">"
    . "<option $select_5 value=\"5\">5</option>\n<option $select_25 value=\"25\">25</option>"
    . "<option $select_50 value=\"50\">50</option>\n</select>");
    my $large = gettext("For a large ring it takes several<br />\nminutes to accomplish this procedure.");
    my $startnum = gettext("starting by site order #");
    my $inactivetext = gettext("Check inactive sites");
    my $checkbutton = gettext("Check now");
    $error = join ("\n", @rlmain::error);

    $rlmain::result .= qq~$error
<form method="post" action="$rlmain::cgiURL/$rlmain::action"
onsubmit="window.open('','Checkresult','$windowFeatures')" target="Checkresult">
<p>$intro<br />
$fails</p>
<table width="100%">
<tr>
<td class="top"><input class="text" type="radio" $checked_all name="method" value="all" /></td>
<td class="top"><span>$codelinks</span></td>
<td rowspan="2"><input class="text" type="radio" $checked_sample name="method" value="sample" />
<span>$selectedlinks</span>
  <table width="100%">
  <tr>
  <td><input class="text" type="checkbox" name="next" $checked_next /><span>$next</span></td>
  <td><input class="text" type="checkbox" name="prev" $checked_prev /><span>$prev</span></td>
  </tr>
  <tr>
  <td><input class="text" type="checkbox" name="list" $checked_list /><span>$list</span></td>
  <td><input class="text" type="checkbox" name="rand" $checked_rand /><span>$rand</span></td>
  </tr>
  <tr>
  <td><input class="text" type="checkbox" name="next5" $checked_next5 /><span>$next5</span></td>
  <td><input class="text" type="checkbox" name="prev5" $checked_prev5 /><span>$prev5</span></td>
  </tr>
  <tr>
  <td><input class="text" type="checkbox" name="home" $checked_home /><span>$home</span></td>
  <td><input class="text" type="checkbox" name="search" $checked_search /><span>$search</span></td>
  </tr>
  $statsoption
  </table>
</td>
</tr>
<tr>
<td colspan="2"><span>$timeout</span></td>
</tr>
</table>
<table width="100%">
<tr>
<td><input class="text" type="radio" $checked_no name="limit" value="no" />
<span>$alltext</span></td>
<td><input class="text" type="radio" $checked_yes name="limit" value="yes" />
<span>$maxtext</span></td>
</tr>
<tr>
<td><span class="small">$large</span></td>
<td><span>&nbsp;$startnum</span>&nbsp;
<input class="text" type="text" size="4" name="startsite" value="$rlmain::data{'startsite'}" /></td>
</tr>
<tr>
<td><br />
<input class="text" type="radio" $checked_unreg name="limit" value="unreg" />
<span>$inactivetext</span></td>
<td style="text-align: center; vertical-align: bottom"><span class="center">
<input class="button" type="submit" name="submit" value="$checkbutton" /></span></td>
</tr>
</table>
<input type="hidden" name="ringid" value="$rlmain::data{'ringid'}" />
<input type="hidden" name="routine" value="$rlmain::data{'routine'}" />
</form>~;

  } else	{

    %linktexts = (
      next   => '- ' . gettext("Next-link missing or incorrect") . '<br />',
      rand   => '- ' . gettext("Random-link missing or incorrect") . '<br />',
      list   => '- ' . gettext("List-link missing or incorrect") . '<br />',
      home   => '- ' . gettext("Home-link missing or incorrect") . '<br />',
      prev   => '- ' . gettext("Previous-link missing or incorrect") . '<br />',
      next5  => '- ' . gettext("Next 5-link missing or incorrect") . '<br />',
      prev5  => '- ' . gettext("Previous 5-link missing or incorrect") . '<br />',
      search => '- ' . gettext("Search-link missing or incorrect") . '<br />',
      stats  => '- ' . gettext("Stats-link missing or incorrect") . '<br />',
    );

    &execfilenames;

    my @checksites=
      $rlmain::data{'limit'} eq 'yes'
        ? splice (@rlmain::activesites, $rlmain::data{'startsite'} - 1, $rlmain::data{'maxsites'}) :
      $rlmain::data{'limit'} eq 'no'
        ? @rlmain::activesites : @rlmain::inactivesites;

    # Remove previous file with saved checkresults
    opendir(DIR, $rlmain::datapath) || die "Can't open data directory\n$!";
    my @files = grep { !/^\./ && -f "$rlmain::datapath/$_" } readdir(DIR);
    closedir DIR;
    for (@files)	{
      if (/^(checkresults_[\w.]+)$/)	{
        $_ = $1;
        unlink ("$rlmain::datapath/$_") || die "Can't remove '$_'\n$!";
      }
    }

    # Create new file for saving checkresults
    $rlmain::ringid = rlmain::secureword ($rlmain::ringid);
    sysopen CHECKINFO, "$rlmain::datapath/checkresults_$rlmain::ringid.txt", O_WRONLY|O_CREAT,
     $rlmain::filemode or die "Can't create 'checkresults_$rlmain::ringid.txt'\n$!";
    flock CHECKINFO, LOCK_EX or die $!;
    print CHECKINFO "\nServer responses to Ringlink checker requests\n" . '=' x 45
    . "\n\nRing title:    $rlmain::ringtitle\nRing homepage: $rlmain::ringURL\n\n\n";

    my $url;
    my $siteid = gettext("Site ID:");
    my $sitetitle = gettext("Site title:");
    my $activate = $rlmain::data{'limit'} eq 'unreg' ? 'Activate' : 'Deactivate';
    my $activatetext = $rlmain::data{'limit'} eq 'unreg' ? gettext("Activate") : gettext("Deactivate");
    my $edit = gettext("Edit");
    my $remove = gettext("Remove");

    # Create counter files
    for ('failcount', 'successcount')	{
      open (FH, "> $rlmain::datapath/$rlmain::ringid/$_.tmp")
       or die "Couldn't open '$rlmain::ringid/$_.tmp'\n$!";
      close (FH);
    }

    my $useragent = new LWP::UserAgent;
    $useragent -> timeout ($rlmain::data{'timeout'});
    require Parallel::ForkManager;
    my $pm = new Parallel::ForkManager ($rlmain::max_processes);
  
    for (@checksites)	{
      my $pid = $pm -> start and next;
      my @sitevalues = split (/\t/, $_);
      for (@rlmain::sitenames)	{
        ${$rlmain::refs{$_}} = shift (@sitevalues);
        rlmain::entify(${ $rlmain::refs{$_} });
      }
      (my $entryURL = $rlmain::entryURL) =~ s/&amp;/&/g;
      (my $codeURL = $rlmain::codeURL) =~ s/&amp;/&/g;

      @error = ();

      $response = $useragent -> request (new HTTP::Request GET => $entryURL);
      $html = $response -> content;
      if ($response -> code !~ /200/)	{
        push (@error, '- ' . sprintf (gettext("Couldn't read %s"),
          $rlmain::entryURL eq $rlmain::codeURL ? gettext("URL") : gettext("Site entry URL")) .
          &status ($response -> status_line) . '<br />');
        &checkprint($rlmain::entryURL);
      } elsif ($rlmain::entryURL eq $rlmain::codeURL)	{
        &htmlcheck;
      }
      unless ($rlmain::entryURL eq $rlmain::codeURL)	{
        $response = $useragent -> request (new HTTP::Request GET => $codeURL);
        $html = $response -> content;
        if ($response -> code !~ /200/)	{
          push (@error, '- ' . gettext("Couldn't read HTML code URL") . ' '
          . &status ($response -> status_line) . '<br />');
          &checkprint($rlmain::codeURL);
        } else	{
          &htmlcheck;
        }
      }

      if (@error)	{
        if ($rlmain::entryURL eq $rlmain::codeURL)	{
          $url = "<a href=\"$rlmain::entryURL\" target=\"Ringlink\">$rlmain::entryURL</a><br />";
        } else	{
          $url = "<a href=\"$rlmain::entryURL\" target=\"Ringlink\">" . gettext("Site entry URL") . "</a> |\n"
          . "<a href=\"$rlmain::codeURL\" target=\"Ringlink\">" . gettext("HTML code URL") . '</a><br />';
        }
        failcount(1);
        $error = join ("\n", @error);

        adderror (qq~
<hr />
<span>$siteid $rlmain::siteid | $sitetitle $rlmain::sitetitle<br />
$url</span>
<table><tr><td><p>
$error
</p></td></tr></table>
<span><a href="$rlmain::cgiURL/$rlmain::action?ringid=$rlmain::ringid;siteid=$rlmain::siteid;routine=$activate;pass=check"
onclick="window.open('','Ringlink','$windowFeatures')" target="Ringlink">$activatetext</a> |
<a href="$rlmain::cgiURL/$rlmain::action?ringid=$rlmain::ringid;siteid=$rlmain::siteid;routine=Edit_site;pass=check"
onclick="window.open('','Ringlink','$windowFeatures')" target="Ringlink">$edit</a> |
<a href="$rlmain::cgiURL/$rlmain::action?ringid=$rlmain::ringid;siteid=$rlmain::siteid;routine=Remove_site;pass=check"
onclick="window.open('','Ringlink','$windowFeatures')" target="Ringlink">$remove</a> -
<a href="mailto:$rlmain::wmname &lt;$rlmain::wmemail&gt;">$rlmain::wmname</a></span><br /><br />~);

      } elsif ($rlmain::data{limit} eq 'unreg') {
        if ($rlmain::entryURL eq $rlmain::codeURL)	{
          $url = qq{<a href="$rlmain::entryURL" target="Ringlink">$rlmain::entryURL</a><br />};
        } else	{
          $url = "<a href=\"$rlmain::entryURL\" target=\"Ringlink\">" . gettext("Site entry URL") . "</a> |\n"
          . "<a href=\"$rlmain::codeURL\" target=\"Ringlink\">" . gettext("HTML code URL") . '</a><br />';
        }
        successcount(1);

        addsuccess (<<EOC);
<hr />
<span>$siteid $rlmain::siteid | $sitetitle $rlmain::sitetitle<br />
$url</span>
<p><a href="$rlmain::cgiURL/$rlmain::action?ringid=$rlmain::ringid;siteid=$rlmain::siteid;routine=Activate;pass=check"
onclick="window.open('','Ringlink','$windowFeatures')" target="Ringlink">$activatetext</a> |
<a href="$rlmain::cgiURL/$rlmain::action?ringid=$rlmain::ringid;siteid=$rlmain::siteid;routine=Edit_site;pass=check"
onclick="window.open('','Ringlink','$windowFeatures')" target="Ringlink">$edit</a> |
<a href="$rlmain::cgiURL/$rlmain::action?ringid=$rlmain::ringid;siteid=$rlmain::siteid;routine=Remove_site;pass=check"
onclick="window.open('','Ringlink','$windowFeatures')" target="Ringlink">$remove</a> -
<a href="mailto:$rlmain::wmname &lt;$rlmain::wmemail&gt;">$rlmain::wmname</a></p>
EOC

      }
      $pm -> finish;
    }
    $pm -> wait_all_children;
    close CHECKINFO or die $!;
    unless (-s "$rlmain::datapath/$rlmain::ringid/error.tmp")	{
      if ($rlmain::data{'limit'} eq 'no')	{
        $rlmain::result .= '<p class="success">'
        . sprintf (gettext("All %d active sites passed the check."), scalar @checksites) . "</p>\n";
      } elsif ($rlmain::data{'limit'} eq 'unreg')	{
        $rlmain::result .= '<p class="success">'
        . sprintf (gettext("All %d inactive sites were checked."), scalar @checksites) . "</p>\n";
      } else	{
        $rlmain::result .= '<p class="success">' . sprintf (
          gettext("The active sites with order number %d - %d\npassed the check."), $rlmain::data{'startsite'},
          ($rlmain::data{'maxsites'} > scalar @checksites ? scalar @checksites : $rlmain::data{'maxsites'})
          + $rlmain::data{'startsite'} - 1) . "</p>\n";
      }
      unless ($rlmain::data{'limit'} eq 'unreg')	{
        $rlmain::result .= '<form><p><input type="button" class="button" value="' . gettext("Close this window")
        . "\" onclick=\"window.close()\" /></p></form>\n";
      }
    } else	{
      $rlmain::result .= '<p style="font-size: 12px">'
      . gettext("It might be a good idea to save this page\ntemporarily on the hard disk.") . "</p>\n";
      if ($rlmain::data{'limit'} eq 'no')	{
        $rlmain::result .= '<p class="success">'
        . sprintf (gettext("All %d active sites were checked."), scalar @checksites) . "</p>\n";
      } elsif ($rlmain::data{'limit'} eq 'unreg')	{
        $rlmain::result .= '<p class="success">'
        . sprintf (gettext("All %d inactive sites were checked."), scalar @checksites) . "</p>\n";
      } else	{
        $rlmain::result .= '<p class="success">' . sprintf (
          gettext("The active sites with order number %d - %d\nwere checked."), $rlmain::data{'startsite'},
          ($rlmain::data{'maxsites'} > scalar @checksites ? scalar @checksites : $rlmain::data{'maxsites'})
          + $rlmain::data{'startsite'} - 1) . "</p>\n";
      }
    }

    if (-s "$rlmain::datapath/$rlmain::ringid/success.tmp") {
      my $numsites;
      if (&successcount == 1)	{
        $numsites = gettext("site");
      } elsif (&successcount == 2)	{
        $numsites = gettext("2 sites");
      } else	{
        $numsites = sprintf (gettext("%d sites"), &successcount);
      }
      $rlmain::result .= '<p class="success">'
      . sprintf (gettext("The following %s passed the check:"), $numsites) . "</p>\n";
      open (FH, "< $rlmain::datapath/$rlmain::ringid/success.tmp")
       or die "Couldn't open '$rlmain::ringid/success.tmp'\n$!";
      $rlmain::result .= join ('', <FH>) . "\n";
      close (FH);
    }

    if (-s "$rlmain::datapath/$rlmain::ringid/error.tmp")	{
      my $details = gettext("Details");
      my $detailsintro = gettext("View the actual server responses for those sites\nbelow where the checker either couldn't read the\nURL or one or more link failed.");
      my $numsites;
      if (&failcount == 1)	{
        $numsites = gettext("site");
      } elsif (&failcount == 2)	{
        $numsites = gettext("2 sites");
      } else	{
        $numsites = sprintf (gettext("%d sites"), &failcount);
      }

      $rlmain::result .= qq~<form method="post" action="$rlmain::cgiURL/$rlmain::action"
onsubmit="window.open('','Ringlink','$windowFeatures')" target="Ringlink">
<hr />
<table>
<tr>
<td><input type="hidden" name="ringid" value="$rlmain::data{'ringid'}" />
<input type="hidden" name="routine" value="$rlmain::data{'routine'}" />
<input class="button" type="submit" name="details" value="$details" />&nbsp;</td>
<td><span class="small">$detailsintro</span></td>
</tr>
</table>
</form>
~;

      $rlmain::result .= '<p class="error">'
      . sprintf (gettext("The following %s failed the check:"), $numsites) . "</p>\n";
      open (FH, "< $rlmain::datapath/$rlmain::ringid/error.tmp")
       or die "Couldn't open '$rlmain::ringid/error.tmp'\n$!";
      $rlmain::result .= join ('', <FH>) . "\n";
      close (FH);
    }
    for (qw/error success failcount successcount/)	{
      my $tmpfile = "$rlmain::datapath/$rlmain::ringid/$_.tmp";
      unlink $tmpfile if -e $tmpfile;
    }
  }
}


sub status	{
  if ($_[0] =~ /timeout/i)	{
    return ' ( ' . gettext("timeout") . ' )';
  } elsif ($_[0] =~ /Bad host/i)	{
    return ' ( ' . gettext("bad hostname") . ' )';
  } elsif ($_[0] =~ /Can't connect/i)	{
    return ' ( ' . gettext("couldn't connect to server") . ' )';
  } else	{
    return " ( $_[0] )";
  }
}


sub htmlcheck	{
  (my $page = $html) =~ s/href\s*=\s*"\s*http:([^ "]+)\s*"/'href="http:' . &remlinebreaks($1) . '"'/egi;
  $page =~ s/&(#38|amp);/&/g;
  $page =~ s/\?(siteid=\w+)([&;])(ringid=\w+)"/\?$3$2$1"/gi;
  (my $domain = $rlmain::cgiURL) =~ s/^(\w+:\/\/[^\s\/]+)(\S*)/$1/;
  my $directories = $2;
  my $checkprint = 0;
  for (@links)	{
    unless ($page =~ /((?i)href="$domain)$directories\/$execfiles{$_}\?((?i)ringid=$rlmain::ringid[&;]siteid=$rlmain::siteid)"/)	{
      push (@error, $linktexts{$_});
      $checkprint = 1;
    }
  }
  &checkprint($rlmain::codeURL) if $checkprint;
}


sub remlinebreaks	{
  (my $adjlink = $_[0]) =~ s/[\r\n]+//g;
  return $adjlink;
}


sub adderror	{
  open ERROR, ">> $rlmain::datapath/$rlmain::ringid/error.tmp"
   or die "Couldn't open '$rlmain::ringid/error.tmp'\n$!";
  flock ERROR, LOCK_EX or die $!;
  print ERROR shift;
  close ERROR or die $!;
}


sub addsuccess	{
  open SUCCESS, ">> $rlmain::datapath/$rlmain::ringid/success.tmp"
   or die "Couldn't open '$rlmain::ringid/success.tmp'\n$!";
  flock SUCCESS, LOCK_EX or die $!;
  print SUCCESS shift;
  close SUCCESS or die $!;
}


sub failcount	{
  my $incr = shift;
  open FAILCNT, "+< $rlmain::datapath/$rlmain::ringid/failcount.tmp"
   or die "Couldn't open '$rlmain::ringid/failcount.tmp'\n$!";
  flock FAILCNT, LOCK_EX or die $!;
  my $count = <FAILCNT>;
  if ($incr) {
    seek FAILCNT, 0, 0;
    $count += $incr;
    print FAILCNT $count;
  }
  close FAILCNT or die $!;
  $count
}


sub successcount	{
  my $incr = shift;
  open S_CNT, "+< $rlmain::datapath/$rlmain::ringid/successcount.tmp"
   or die "Couldn't open '$rlmain::ringid/successcount.tmp'\n$!";
  flock S_CNT, LOCK_EX or die $!;
  my $count = <S_CNT>;
  if ($incr) {
    seek S_CNT, 0, 0;
    $count += $incr;
    print S_CNT $count;
  }
  close S_CNT or die $!;
  $count
}


sub checkprint	{
  my $checkresults = '*' x 60 . "\n\nSite ID:    $rlmain::siteid\nSite title: $rlmain::sitetitle\n"
  . "URL:        $_[0]\n\n" . '*' x 60 . "\n\n";
  $checkresults .= $response -> protocol . ' ' . $response -> status_line . "\n"
  . $response -> headers_as_string . "\n" . $html . "\n\n";
  print CHECKINFO $checkresults;
}


sub reorder	{
  if ($rlmain::data{'submit'})	{
    $rlmain::ringid = rlmain::secureword($rlmain::ringid);
    open SITES, "+< $rlmain::datapath/$rlmain::ringid/sites.db"
     or die "Can't open '$rlmain::ringid/sites.db'\n$!";
    flock SITES, LOCK_EX or die $!;
    my @sites = <SITES>;
    my $length = length(join '', @sites);
    if ($rlmain::data{'method'} eq 'random')	{
      @sites = map splice(@sites, rand $_, 1), reverse 1 .. @sites;
    } elsif ($rlmain::data{'method'} eq 'alpha')	{
      use locale;
      @sites = map $_->[0], sort {
        uc $a->[1] cmp uc $b->[1]
      } map [$_, (split /\t/)[2] =~ /^\W*(\w.+)/], @sites;
    }
    if (length(join '', @sites) == $length)	{
      seek SITES, 0, 0;
      print SITES @sites;
      $rlmain::result = '<p class="success">' . gettext("Sites reordered") . '</p>';
    } else	{
      push @rlmain::error, '<p class="error">' . gettext("Execution error! Please try again.") . '</p>';
      reorderform();
    }
    close SITES or die $!;
  } else	{
    reorderform();
  }
}


sub reorderform	{
  my $header = gettext("Reorder sites");
  my $error = join ("\n", @rlmain::error);
  my $random = gettext("Random order");
  my $alpha = gettext("Sort by site title");
  my $reorder = gettext("Reorder");

  $rlmain::result = qq~<h4>$header</h4>
$error
<form method="post" action="$rlmain::cgiURL/$rlmain::action">
<table>
<tr><td><input class="text" type="radio" checked="checked" name="method" value="random" /></td>
<td style="vertical-align: middle"><span>$random</span></td></tr>
<tr><td><input class="text" type="radio" name="method" value="alpha" /></td>
<td style="vertical-align: middle"><span>$alpha</span></td></tr>
</table>
<input type="hidden" name="ringid" value="$rlmain::data{'ringid'}" />
<input type="hidden" name="routine" value="Reorder sites" />
<p><input class="button" type="submit" name="submit" value="$reorder" /></p>
</form>~;

}


sub sendemail	{
  my $select_active = '';
  my $select_inactive = '';
  my $select_all = '';
  my $select_comma = '';
  my $select_whitespace = '';
  my $select_newline = '';
  my $addresses = '';
  my $header = gettext("Send email");
  my $intro = gettext("Display a list with the webmasters' email addresses.\nThe list can be copied and pasted into your email client.");
  my $include = gettext("Include");
  my $active = gettext("Active sites");
  my $inactive = gettext("Inactive sites");
  my $all = gettext("All sites");
  my $sep = gettext("Separator");
  my $comma = gettext("comma");
  my $white = gettext("whitespace");
  my $newline = gettext("newline");
  my $show = gettext("Show addresses");
  if ($rlmain::data{'submit'})	{
    my $separator;
    my %addresses = ();
    if ($rlmain::data{'separator'} eq 'comma')	{
      $separator = ', ';
    } elsif ($rlmain::data{'separator'} eq 'whitespace')	{
      $separator = ' ';
    } else	{
      $separator = "\n";
    }
    open (SITES, "< $rlmain::datapath/$rlmain::ringid/sites.db")
     || die "Can't open '$rlmain::ringid/sites.db'\n$!";
    while (<SITES>)    {
      my @sitevalues = split /\t/;
      if ($rlmain::data{'status'} eq $sitevalues[1] || $rlmain::data{'status'} eq 'all')	{
        $addresses{$sitevalues[9]}++;
      }
    }
    close (SITES);
    $addresses = join $separator, sort {uc($a) cmp uc($b)} keys %addresses;
  }
  $select_active = 'selected="selected"' if !$rlmain::data{'submit'} || $rlmain::data{'status'} eq 'active';
  $select_inactive = 'selected="selected"' if $rlmain::data{'status'} eq 'inactive';
  $select_all = 'selected="selected"' if $rlmain::data{'status'} eq 'all';
  $select_comma = 'selected="selected"' if !$rlmain::data{'submit'} || $rlmain::data{'separator'} eq 'comma';
  $select_whitespace = 'selected="selected"' if $rlmain::data{'separator'} eq 'whitespace';
  $select_newline = 'selected="selected"' if $rlmain::data{'separator'} eq 'newline';

  $rlmain::result = qq~<h4>$header</h4>
<p>$intro</p>
<form method="post" action="$rlmain::cgiURL/$rlmain::action">
<table width="100%"><tr><td>
<p>$include<br /><select name="status" size="1">
<option $select_active value="active">$active</option>
<option $select_inactive value="inactive">$inactive</option>
<option $select_all value="all">$all</option></select></p>
</td><td>
<p>$sep<br /><select name="separator" size="1">
<option $select_comma value="comma">$comma</option>
<option $select_whitespace value="whitespace">$white</option>
<option $select_newline value="newline">$newline</option></select></p>
</td><td style="vertical-align: middle">
<input type="hidden" name="ringid" value="$rlmain::data{'ringid'}" />
<input type="hidden" name="routine" value="Send email" />
<p><input class="button" type="submit" name="submit" value="$show" /></p>
</td></tr></table>
<br />
<div class="textarea"><textarea name="addresses" rows="10" cols="45" $rlmain::wrapoff>
$addresses
</textarea></div>
</form>~;

}


sub backup	{
  my $disabled = my $nixcheck = my $wincheck = '';
  $rlmain::ringid = rlmain::secureword ($rlmain::ringid);
  my $file = "rl_backup_$rlmain::ringid";
  my $path = "$rlmain::datapath/$rlmain::ringid";
  my $header = gettext("Backup ring");
  my $intro = gettext("This routine lets you download a compressed file with\nthe current ring data. By means of the file, the ring\ncan be easily restored on some other Ringlink system.");
  my $system = gettext("File system");
  my $backup = gettext("Get file");
  my $or = gettext("or");
  $rlmain::pagemenu = &menu;
  $rlmain::result = "<h4>$header</h4>\n";
  if ($rlmain::cgipath =~ /^\w:/)	{
    $disabled = 'disabled="disabled"';
    $wincheck = 'checked="checked"';
  } else	{
    $nixcheck = 'checked="checked"';
  }
  if ($rlmain::data{'submit'})	{
    for ('.tar', '.tar.gz', '.tar.Z', '.zip')	{
      unlink "$path/$file$_" if -f "$path/$file$_";
    }
    $ENV{PATH} = rlmain::securepath();
    if ($rlmain::data{'system'} eq 'unix')	{
      UNIX:	{
        $file .= '.tar';
        qx(cd $rlmain::datapath; tar cvf $path/$file $rlmain::ringid);
        qx(cd $path; gzip $file);
        if (-f "$path/$file.gz")	{
          $file .= '.gz';
          last UNIX;
        }
        qx(cd $path; compress $file);
        if (-f "$path/$file.Z")	{
          $file .= '.Z';
          last UNIX;
        }
        $file = '' unless -f "$path/$file";
      }
    } elsif ($rlmain::data{'system'} eq 'windows')	{
      if (eval "require Compress::Zlib")	{
        $file .= '.zip';
        require Archive::Zip::Tree;
        my $zip = Archive::Zip->new();
        $zip->addTree( $path, $rlmain::ringid, sub { !/^\.|^_vti|$path$/ } );
        $file = '' unless $zip->writeToFileNamed("$path/$file") == 0;
      } else	{
        (my $error = $@) =~ s/\n/<br \/>\n/g;
        $rlmain::result = "<h4>$header ( <tt>.zip</tt> )</h4>\n"
         . rlmain::missingsoftware() . "<p><tt>$error</tt></p>";
        rlmain::adminhtml();
        rlmain::exit();
      }
    }
    if ($file)	{
      (my $ext) = $file =~ /\.(\w+)$/;
      my %formats = (zip => 'zip', gz => 'x-gzip', tar => 'x-tar', Z => 'x-compress');
      open(FILE, "< $path/$file") or die "Can't open temporary file.\n$!";
      binmode FILE;
      binmode STDOUT;
      print "Content-Type: application/$formats{$ext}\n",
            "Content-Disposition: attachment; filename=$file\n",
            'Content-Length: ' . (stat "$path/$file")[7] . "\n\n";
      while (<FILE>) { print }
      close(FILE);
      unlink "$path/$file";
      rlmain::exit();
    } else	{
      $rlmain::result .= '<p class="error">' . gettext("Couldn't create backup file.") . '</p>';
    }
  } else	{

    $rlmain::result .= qq~<p>$intro</p>
<form method="post" action="$rlmain::cgiURL/$rlmain::action">
<table>
<tr><td colspan="2"><p>$system:</p></td></tr>
<tr><td><input class="text" type="radio" name="system" $nixcheck $disabled value="unix" /></td>
<td style="vertical-align: middle"><span>Unix/Linux ( <tt>.tar.gz</tt> $or <tt>.tar.Z )</tt></span></td></tr>
<tr><td><input class="text" type="radio" name="system" $wincheck value="windows" /></td>
<td style="vertical-align: middle"><span>Windows/Unix/Linux ( <tt>.zip</tt> )</span></td></tr>
</table>
<input type="hidden" name="ringid" value="$rlmain::data{'ringid'}" />
<input type="hidden" name="routine" value="Backup ring" />
<p><input class="button" type="submit" name="submit" value="$backup" /></p>
</form>~;

  }
}


sub directory	{
  my $forms;
  $rlmain::pagemenu = &menu;  # The value of $rlmain::numringdir is set in 'sub menu'
  my $header = $rlmain::numringdir > 1 ? gettext("Directories") : gettext("Directory");
  my $intro = sprintf (
    gettext("%s below facilitates %s over Ringlink webrings,\nby taking you to a partly pre-filled add form."),
    $rlmain::numringdir > 1 ? gettext("Each submit button") : gettext("The submit button"),
    $rlmain::numringdir > 1 ? gettext("submissions to web directories")
    : gettext("a submission to our web directory")
  );
  opendir(DIR, "$rlmain::lib/RLDir") || die "Can't open '/RLDir'\n$!";
  my @ringdirs = grep { /\.pm$/ and !/^Global\.pm$/ } readdir(DIR);
  closedir DIR;
  for ( 'Global.pm', map { /^([-\w.]+)$/ } @ringdirs )	{
    $forms .= getdirinfo ($_);
  }
  $rlmain::result = "<h4>$header</h4>\n<p>$intro</p>\n$forms";
}


sub getdirinfo	{
  my $mod = shift;
  $rlmain::lib = "$rlmain::cgipath/$rlmain::lib" unless $rlmain::lib =~ /^(?:\w:|\\|\/)/;
  require "$rlmain::lib/RLDir/$mod";
  my ($dirname, $mainURL, $submitURL, $intro, %forminput) = RLDir();
  my $forminput;
  for (keys %forminput)	{
    $forminput .= "<input type=\"hidden\" name=\"$_\" value=\"$forminput{$_}\" />\n";
  }
  my $submit = gettext("Submit");

  return <<EOF;
<hr>
<p><a href="$mainURL" target="_blank">$dirname</a></p>
<p>$intro</p>
<form action="$submitURL" method="post" target="_blank">
$forminput<input class="button" type="submit" value="$submit" />
</form>
EOF

}


####################################################################

# Below are subroutines that have been moved from admin.pl and
# ringadmin.pl


sub Edit_ring	{
  if ($rlmain::data{'submit'})	{
    &validation;
    if (!@rlmain::error)	{
      &update;
      for (@rlmain::ringnames)	{
        ${$rlmain::refs{$_}} = $rlmain::data{$_};
      }
      if ($rlmain::action =~ /^ringadmin/i)	{
        (my $name = $rlmain::title) =~ s/\W/_/g;
        $name .= "/$rlmain::ringid";
        print "Set-cookie: $name=", crypt ($rlmain::ringpw, 'pw'), "\n";
      }
      rlmain::setlang ($rlmain::ringlang);
      $rlmain::pagemenu = &menu;
      $rlmain::result = '<p class="success">' . gettext("Ring updated") . '</p>';
    } else	{
      $rlmain::pagemenu = &menu;
      $rlmain::result = '<h4>' . gettext("Edit ring") . "</h4>\n";
      $rlmain::result .= &form;
    }
  } else	{
    for (@rlmain::ringnames)	{
      $rlmain::data{$_} = ${$rlmain::refs{$_}};
    }
    $rlmain::pagemenu = &menu;
    $rlmain::result = '<h4>' . gettext("Edit ring") . "</h4>\n";
    $rlmain::result .= &form;
  }
}


sub Appearance	{
  if ($rlmain::data{'submit'} eq gettext("Get default colors"))	{
    for (keys %rlmain::colors)	{
      $rlmain::data{$_} = $rlmain::colors{$_};
    }
    $rlmain::pagemenu = &customizemenu;
    $rlmain::result = &appearanceform;
  } elsif ($rlmain::data{'submit'})	{
    &appearancevalidation;
    if (!@rlmain::error)	{
      &update;
      for (@rlmain::ringnames)	{
        ${$rlmain::refs{$_}} = $rlmain::data{$_};
      }
      $rlmain::pagemenu = &customizemenu;
      $rlmain::result = '<p class="success">' . gettext("Customization values updated") . '</p>';
      $rlmain::result .= &logodisplay;
    } else	{
      $rlmain::pagemenu = &customizemenu;
      $rlmain::result = &appearanceform;
    }
  } else	{
    for (@rlmain::ringnames)	{
      $rlmain::data{$_} = ${$rlmain::refs{$_}};
    }
    $rlmain::pagemenu = &customizemenu;
    $rlmain::result = &appearanceform;
  }
}


sub HTML_code	{
  if ($rlmain::data{'submit'} eq gettext("Get default code"))	{
    $rlmain::data{'code'} = &defaultcode;
    $rlmain::pagemenu = &customizemenu;
    $rlmain::result = &htmlcode;
  } elsif ($rlmain::data{'submit'} eq gettext("Preview"))	{
    $rlmain::pagemenu = &customizemenu;
    $rlmain::result = &htmlcode;
  } elsif ($rlmain::data{'submit'})	{
    &codeupdate;
    $rlmain::pagemenu = &customizemenu;
    $rlmain::result = '<p class="success">' . gettext("HTML code updated") . '</p>';
  } else	{
    $rlmain::data{'code'} = rlmain::htmlcode();
    $rlmain::pagemenu = &customizemenu;
    $rlmain::result = &htmlcode;
  }
}


sub Add_page	{
  if ($rlmain::data{'submit'} eq gettext("Get default code"))	{
    $rlmain::data{'addpage'} = &defaultaddpage;
    $rlmain::pagemenu = &customizemenu;
    $rlmain::result = &addpage;
  } elsif ($rlmain::data{'submit'} eq gettext("Preview"))	{
    $rlmain::pagemenu = &customizemenu;
    $rlmain::result = &addpage;
  } elsif ($rlmain::data{'submit'})	{
    &addpageupdate;
    $rlmain::pagemenu = &customizemenu;
    $rlmain::result = '<p class="success">'
    . gettext("The page, that appears after a new site\nhas been added, was updated.") . '</p>';
  } else	{
    $rlmain::data{'addpage'} = rlmain::addpage();
    $rlmain::pagemenu = &customizemenu;
    $rlmain::result = &addpage;
  }
}


sub Add_mail	{
  if ($rlmain::data{'submit'} eq gettext("Get default text"))	{
    $rlmain::data{'addmail'} = &defaultaddmail;
    $rlmain::pagemenu = &customizemenu;
    $rlmain::result = &addmail;
  } elsif ($rlmain::data{'submit'})	{
    &addmailupdate;
    $rlmain::pagemenu = &customizemenu;
    $rlmain::result = '<p class="success">'
    . gettext("The email message, that is sent to the webmaster\nafter a new site has been added, was updated.")
    . '</p>';
  } else	{
    $rlmain::data{'addmail'} = rlmain::addmail();
    $rlmain::pagemenu = &customizemenu;
    $rlmain::result = &addmail;
  }
}


sub Code_page	{
  if ($rlmain::data{'submit'} eq gettext("Get default code"))	{
    $rlmain::data{'codepage'} = &defaultcodepage;
    $rlmain::pagemenu = &customizemenu;
    $rlmain::result = &codepage;
  } elsif ($rlmain::data{'submit'} eq gettext("Preview"))	{
    $rlmain::pagemenu = &customizemenu;
    $rlmain::result = &codepage;
  } elsif ($rlmain::data{'submit'})	{
    &codepageupdate;
    $rlmain::pagemenu = &customizemenu;
    $rlmain::result = '<p class="success">'
    . gettext("The introduction text on the &quot;Get code&quot; page was updated.") . '</p>';
  } else	{
    $rlmain::data{'codepage'} = rlmain::codepage();
    $rlmain::pagemenu = &customizemenu;
    $rlmain::result = &codepage;
  }
}


sub New_site	{
  if ($rlmain::data{'submit'})	{
    site::validation();
    if (!@rlmain::error)	{
      site::create();
      rlmain::entify($rlmain::data{'sitetitle'});
      $rlmain::pagemenu = &menu;
      my $sitetitle = '</p><p style="font-weight: bold">' . $rlmain::data{'sitetitle'}
      . '</p><p class="success">';
      $rlmain::result = '<p class="success">'
      . sprintf (gettext("The site %s was successfully created."), $sitetitle) . '</p>';
    } else	{
      $rlmain::pagemenu = &menu;
      $rlmain::result = '<h4>' . gettext("Add new site") . "</h4>\n";
      $rlmain::result .= site::form();
    }
  } else	{
    $rlmain::data{'entryURL'} = 'http://';
    $rlmain::data{'codeURL'} = 'http://';
    $rlmain::pagemenu = &menu;
    $rlmain::result = '<h4>' . gettext("Add new site") . "</h4>\n";
    $rlmain::result .= site::form();
  }
}


sub Site_admin	{
  my $siteexists;
  rlmain::sitelist();
  if (!@rlmain::sites)	{
    $rlmain::result = '<p class="error">' . gettext("No site has been added to this ring yet.") . '</p>';
    $rlmain::pagemenu = &menu;
  } elsif ($rlmain::data{'submit'})	{
    for (@rlmain::sites)	{
      if ($rlmain::data{'siteid'} eq $_)	{
        $siteexists = 1;
        last;
      }
    }
    if (!$rlmain::data{'siteid'})	{
      push (@rlmain::error, '<p class="error">' . gettext("Enter site ID!") . '</p>');
      &siteadminform;
    } elsif (!$siteexists)	{
      push (@rlmain::error, '<p class="error">'
      . gettext("Can't find entered site ID in ring, please try again.") . '</p>');
      &siteadminform;
    } else	{
      rlmain::getsitevalues();
      $rlmain::pagemenu = site::menu();
    }
  } else	{
    &siteadminform;
  }
}


sub Edit_site	{
  my $statuschange;
  rlmain::getsitevalues();
  die "There is no site ID '$rlmain::data{'siteid'}' in \"$rlmain::ringtitle\".\n\n"
   unless $rlmain::siteid;
  if ($rlmain::data{'submit'})	{
    site::validation();
    if (!@rlmain::error)	{
      site::update();
      $statuschange = 1 if $rlmain::data{'status'} ne $rlmain::status;
      for (@rlmain::sitenames)	{
        ${$rlmain::refs{$_}} = $rlmain::data{$_};
      }
      if ($rlmain::data{'pass'})	{
        rlmain::entify($rlmain::sitetitle);
        $rlmain::result = '<p class="success">' . sprintf (
          gettext("The site %s was updated."), "&quot;$rlmain::sitetitle&quot;") . '</p>';
      } else	{
        $rlmain::pagemenu = site::menu();
        $rlmain::result = '<p class="success">' . gettext("Site updated") . '</p>';
      }
      if ($statuschange && !$rlmain::nomail)	{
        $rlmain::result .= "\n" . site::statuschangemail();
        rlmain::emailhtml();
        rlmain::exit();
      } elsif ($rlmain::data{'pass'} eq 'active')	{
        push (@rlmain::error, '<p class="success">' . sprintf (
          gettext("The site %s was updated."), "&quot;$rlmain::sitetitle&quot;") . '</p>');
        $rlmain::data{'routine'} = 'Active sites';
        $rlmain::data{'siteid'} = '';
        $rlmain::nolist = 1;
        &Active_sites;
      } elsif ($rlmain::data{'pass'} eq 'inactive')	{
        push (@rlmain::error, '<p class="success">' . sprintf (
          gettext("The site %s was updated."), "&quot;$rlmain::sitetitle&quot;") . '</p>');
        $rlmain::data{'routine'} = 'Inactive sites';
        $rlmain::data{'siteid'} = '';
        &Inactive_sites;
      } elsif ($rlmain::data{'pass'} eq 'check')	{
        push (@rlmain::error, '<p class="success">' . sprintf (
          gettext("The site %s was updated."), "&quot;$rlmain::sitetitle&quot;") . '</p>');
        $rlmain::data{'routine'} = 'Check sites';
        &Check_sites;
      } elsif ($rlmain::data{'pass'} eq 'search')	{
        push (@rlmain::error, '<p class="success">' . sprintf (
          gettext("The site %s was updated."), "&quot;$rlmain::sitetitle&quot;") . '</p>');
        $rlmain::data{'routine'} = 'Search sites';
        &Search_sites;
      }
    } else	{
      if ($rlmain::data{'pass'} eq 'check')	{
        &menu;
      } elsif ($rlmain::data{'pass'})	{
        $rlmain::pagemenu = &menu;
      } else	{
        $rlmain::pagemenu = site::menu();
      }
      $rlmain::result = '<h4>' . gettext("Edit site") . "</h4>\n";
      $rlmain::result .= site::form();
    }
  } else	{
    for (@rlmain::sitenames)	{
      $rlmain::data{$_} = ${$rlmain::refs{$_}};
    }
    if ($rlmain::data{'pass'} eq 'check')	{
      &menu;
    } elsif ($rlmain::data{'pass'})	{
      $rlmain::pagemenu = &menu;
    } else	{
      $rlmain::pagemenu = site::menu();
    }
    $rlmain::result = '<h4>' . gettext("Edit site") . "</h4>\n";
    $rlmain::result .= site::form();
  }
}


sub Remove_site	{
  my $ring = gettext("Ring:");
  my $site = gettext("Site:");
  rlmain::getsitevalues();
  die "There is no site ID '$rlmain::data{'siteid'}' in \"$rlmain::ringtitle\".\n\n"
   unless $rlmain::siteid;
  if ($rlmain::data{'submit'} eq gettext("Remove"))	{
    if ($rlmain::data{'removesure'} eq 'on')	{
      site::remove();
      $site::ringtitle = $rlmain::ringtitle;
      site::removemail();
      rlmain::entify($rlmain::sitetitle);
      if (!$rlmain::data{'pass'} || $rlmain::data{'pass'} eq 'check')	{
        if ($rlmain::data{'pass'})	{
          &menu;
        } else	{
          $rlmain::pagemenu = &menu;
        }

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
        if ($rlmain::data{'pass'})	{
          my $close = gettext("Close this window");
          $rlmain::result .= "<form><p><input type=\"button\" class=\"button\" value=\"$close\" onclick=\"window.close()\"></p></form>";
          rlmain::adminhtml();
          rlmain::exit();
        }
      } else	{
        push (@rlmain::error, '<p class="success">' . sprintf (
          gettext("The site %s was removed."), "&quot;$rlmain::sitetitle&quot;") . '</p>');
        if ($rlmain::data{'pass'} eq 'active')	{
          $rlmain::data{'routine'} = 'Active sites';
          $rlmain::data{'siteid'} = '';
          $rlmain::nolist = 1;
          &Active_sites;
        } elsif ($rlmain::data{'pass'} eq 'inactive')	{
          $rlmain::data{'routine'} = 'Inactive sites';
          $rlmain::data{'siteid'} = '';
          &Inactive_sites;
        } elsif ($rlmain::data{'pass'} eq 'search')	{
          $rlmain::data{'routine'} = 'Search sites';
          &Search_sites;
        }
      }
    } else	{
      push (@rlmain::error, '<p class="error">'
      . gettext("The site was not removed,\nsince the checkbox below wasn't checked.") . '</p>');
      $rlmain::result = site::removeform();
      rlmain::emailhtml();
      rlmain::exit();
    }
  } elsif ($rlmain::data{'submit'} eq gettext("Cancel"))	{
    if (!$rlmain::data{'pass'})	{
      $rlmain::pagemenu = site::menu();
    } elsif ($rlmain::data{'pass'} eq 'active')	{
      $rlmain::data{'routine'} = 'Active sites';
      $rlmain::nolist = 1;
      &Active_sites;
    } elsif ($rlmain::data{'pass'} eq 'inactive')	{
      $rlmain::data{'routine'} = 'Inactive sites';
      $rlmain::data{'siteid'} = '';
      &Inactive_sites;
    } elsif ($rlmain::data{'pass'} eq 'search')	{
      $rlmain::data{'routine'} = 'Search sites';
      &Search_sites;
    }
  } else	{
      $rlmain::result = site::removeform();
      rlmain::emailhtml();
      rlmain::exit();
  }

}


sub Active_sites	{
  rlmain::sitelist();
  if (!@rlmain::sites)	{
    $rlmain::result = $rlmain::data{'pass'} ? $rlmain::error[0]
     : '<p class="error">' . gettext("There are no sites in this ring.") . '</p>';
    $rlmain::pagemenu = &menu;
    rlmain::adminhtml();
    rlmain::exit();
  }
  rlmain::statussplit();
  if (!@rlmain::activesites)	{
    $rlmain::result = $rlmain::data{'pass'} ? $rlmain::error[0]
     : '<p class="error">' . gettext("There are no active sites in this ring.") . '</p>';
    $rlmain::pagemenu = &menu;
    rlmain::adminhtml();
    rlmain::exit();
  }
  if (!$rlmain::data{'submit'})	{
    $rlmain::data{'offset'} = 0 if !$rlmain::data{'offset'};
    &adminlist;
  }
  if ($rlmain::data{'ordnumb'} || $rlmain::data{'ordnumb'} eq '0')	{
    if ($rlmain::data{'ordnumb'} =~ /\D/ || $rlmain::data{'ordnumb'} > scalar @rlmain::activesites
     || $rlmain::data{'ordnumb'} < 1)	{
      push (@rlmain::error, '<p class="error">' . sprintf (
        gettext("Incorrect order number (shall be\na number between 1 and %d)."), scalar @rlmain::activesites) .
        '</p>');
      $rlmain::nolist = 1;
      &adminlist;
    } else	{
      $rlmain::data{'offset'} = $rlmain::data{'ordnumb'} - 1;
      &adminlist;
    }
  }
  if ($rlmain::data{'siteid'})	{
    my $activesite;
    $rlmain::data{'offset'} = 0;
    for (@rlmain::activesites)	{
      if ($_ =~ /^$rlmain::data{'siteid'}\t/)	{
        $activesite = 1;
        last;
      } else	{
        $rlmain::data{'offset'} ++;
      }
    }
    if (!$activesite)	{
      my $inactivesite;
      $rlmain::data{'offset'} = '';
      for (@rlmain::inactivesites)	{
        if ($_ =~ /^$rlmain::data{'siteid'}\t/)	{
          $inactivesite = 1;
          push (@rlmain::error, '<p class="error">' . sprintf (
            gettext("Site ID %s is not active."), "&quot;$rlmain::data{'siteid'}&quot;") . '</p>');
          $rlmain::nolist = 1;
          &adminlist;
        }
      }
      if (!$inactivesite)	{
        push (@rlmain::error, '<p class="error">' . sprintf (
          gettext("Site ID %s does not exist in this ring."), "&quot;$rlmain::data{'siteid'}&quot;") . '</p>');
        $rlmain::nolist = 1;
        &adminlist;
      }
    } else	{
      &adminlist;
    }
  } else	{
    $rlmain::data{'offset'} = 0;
    &adminlist;
  }
}


sub Inactive_sites	{
  rlmain::sitelist();
  if (!@rlmain::sites)	{
    $rlmain::result = $rlmain::data{'pass'} ? $rlmain::error[0]
     : '<p class="error">' . gettext("There are no sites in this ring.") . '</p>';
    $rlmain::pagemenu = &menu;
    rlmain::adminhtml();
    rlmain::exit();
  }
  rlmain::statussplit();
  if (!@rlmain::inactivesites)	{
    $rlmain::result = $rlmain::data{'pass'} ? $rlmain::error[0]
     : '<p class="error">' . gettext("There are no inactive sites in this ring.") . '</p>';
    $rlmain::pagemenu = &menu;
    rlmain::adminhtml();
    rlmain::exit();
  }
  &inactivesort;
  if (!$rlmain::data{'submit'})	{
    $rlmain::data{'offset'} = 0 if !$rlmain::data{'offset'};
    &adminlist;
  }
  if ($rlmain::data{'ordnumb'} || $rlmain::data{'ordnumb'} eq '0')	{
    if ($rlmain::data{'ordnumb'} =~ /\D/ || $rlmain::data{'ordnumb'} > scalar @rlmain::inactivesites
     || $rlmain::data{'ordnumb'} < 1)	{
      push (@rlmain::error, '<p class="error">' . sprintf (
        gettext("Incorrect order number (shall be\na number between 1 and %d)."), scalar @rlmain::inactivesites) .
        '</p>');
      $rlmain::nolist = 1;
      &adminlist;
    } else	{
      $rlmain::data{'offset'} = $rlmain::data{'ordnumb'} - 1;
      &adminlist;
    }
  }
  if ($rlmain::data{'siteid'})	{
    my $inactivesite;
    $rlmain::data{'offset'} = 0;
    for (@rlmain::inactivesites)	{
      if ($_ =~ /^$rlmain::data{'siteid'}\t/)	{
        $inactivesite = 1;
        last;
      } else	{
        $rlmain::data{'offset'} ++;
      }
    }
    if (!$inactivesite)	{
      my $activesite;
      $rlmain::data{'offset'} = '';
      for (@rlmain::activesites)	{
        if ($_ =~ /^$rlmain::data{'siteid'}\t/)	{
          $activesite = 1;
          push (@rlmain::error, '<p class="error">' . sprintf (
            gettext("Site ID %s is active."), "&quot;$rlmain::data{'siteid'}&quot;") . '</p>');
          $rlmain::nolist = 1;
          &adminlist;
        }
      }
      if (!$activesite)	{
        push (@rlmain::error, '<p class="error">' . sprintf (
          gettext("Site ID %s does not exist in this ring."), "&quot;$rlmain::data{'siteid'}&quot;") . '</p>');
        $rlmain::nolist = 1;
        &adminlist;
      }
    } else	{
      &adminlist;
    }
  } else	{
    $rlmain::data{'offset'} = 0;
    &adminlist;
  }
}


sub Activate	{
  rlmain::getsitevalues();
  if (!$rlmain::siteid)	{
    die "There is no site ID '$rlmain::data{'siteid'}' in \"$rlmain::ringtitle\".\n\n";
  } elsif ($rlmain::status eq 'active')	{
    die "Site ID $rlmain::siteid is active.\n\n";
  }
  $rlmain::status = 'active';
  for (@rlmain::sitenames)	{
    $rlmain::data{$_} = ${$rlmain::refs{$_}};
  }
  site::update();
  rlmain::entify($rlmain::sitetitle);
  $rlmain::result = '<p class="success" style="margin-top: 0">' . sprintf (
    gettext("The site %s was activated."), "&quot;$rlmain::sitetitle&quot;") . '</p>';
  if ($rlmain::nomail)	{
    site::statuschangemail();
  } else	{
    $rlmain::result .= "\n" . site::statuschangemail();
    rlmain::emailhtml();
  }
  rlmain::exit();
}


sub Deactivate	{
  rlmain::getsitevalues();
  if (!$rlmain::siteid)	{
    die "There is no site ID '$rlmain::data{'siteid'}' in \"$rlmain::ringtitle\".\n\n";
  } elsif ($rlmain::status eq 'inactive')	{
    die "Site ID $rlmain::siteid is inactive.\n\n";
  }
  $rlmain::status = 'inactive';
  for (@rlmain::sitenames)	{
    $rlmain::data{$_} = ${$rlmain::refs{$_}};
  }
  site::update();
  rlmain::entify($rlmain::sitetitle);
  $rlmain::result = '<p class="success" style="margin-top: 0">' . sprintf (
    gettext("The site %s was deactivated."), "&quot;$rlmain::sitetitle&quot;") . '</p>';
  if ($rlmain::nomail)	{
    site::statuschangemail();
  } else	{
    $rlmain::result .= "\n" . site::statuschangemail();
    rlmain::emailhtml();
  }
  rlmain::exit();
}


sub Check_sites	{
  if ($rlmain::data{'pass'})	{
    &menu;
    $rlmain::result = "@rlmain::error\n";
    $rlmain::result .= '<form><p><input type="button" class="button" value="'
    . gettext("Close this window") . '" onclick="window.close()" /></p></form>';
    rlmain::adminhtml();
    rlmain::exit();
  } else	{
    $rlmain::pagemenu = &menu;
    rlmain::sitelist();
    if (!@rlmain::sites)	{
      $rlmain::result = '<p class="error">' . gettext("There are no sites in this ring.") . '</p>';
      rlmain::adminhtml();
      rlmain::exit();
    }
    rlmain::statussplit();
    $rlmain::result = '<h4>' . gettext("Check sites") . "</h4>\n";
    &checksites;
  }
}


sub Reorder_sites	{
  $rlmain::pagemenu = &menu;
  rlmain::sitelist();
  if (!@rlmain::sites)	{
    $rlmain::result = '<p class="error">' . gettext("There are no sites in this ring.") . '</p>';
  } else	{
    &reorder;
  }
}


sub Send_email	{
  $rlmain::pagemenu = &menu;
  rlmain::sitelist();
  if (!@rlmain::sites)	{
    $rlmain::result = '<p class="error">' . gettext("There are no sites in this ring.") . '</p>';
  } else	{
    &sendemail;
  }
}


1;


