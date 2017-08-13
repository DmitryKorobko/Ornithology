#!/usr/bin/perl -T
use lib 'lib';

############################> Ringlink <############################
#                                                                  #
#  $Id: admin.pl,v 1.112 2005/02/21 17:11:41 gunnarh Exp $         #
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
use Fcntl qw(:DEFAULT :flock);
use SDBM_File;

use rlmain 3.2;
use ring 3.2;
use site 3.2;
use Locale::PGetText 2.0;

rlmain::execstart;
unless (rlmain::pwcheck ($rlmain::adminpw))	{
  my $enterpw = gettext("Enter password!");
  my $wrongpw = gettext("Incorrect password, please try again.");
  my $login = gettext("Login");
  my $admpw = gettext("Admin password");
  my $cookies = gettext("&quot;cookies&quot; must be enabled");
  my $loginsuccess = gettext("Login succeeded");
  my $loginbutton = gettext("Log in");
  if ($rlmain::data && !$rlmain::data{'pw'})	{
    push (@rlmain::error, "<p class=\"error\">$enterpw</p>");
  } elsif ($rlmain::data{'pw'})	{
    push (@rlmain::error, "<p class=\"error\">$wrongpw</p>");
  }
  $rlmain::pagetitle = gettext("Master admin");

  $rlmain::result = qq~<h4>$login</h4>
@rlmain::error
<form method="post" action="$rlmain::cgiURL/$rlmain::action">
<span>$admpw</span><br />
<input class="text" type="password" size="15" name="pw" /><br />
<span class="small">($cookies)</span>
<input type="hidden" name="result" value="$loginsuccess" />
<p><input class="button" type="submit" value="$loginbutton" /></p>
</form>~;

} elsif (!$rlmain::data{'routine'})	{
  $rlmain::pagemenu = &menu;
  $rlmain::result = "<p class=\"success\">$rlmain::data{'result'}</p>";
} else	{
  $rlmain::data{'routine'} = $rlmain::routines{$rlmain::data{'routine'}};
  (my $routine = $rlmain::data{'routine'}) =~ tr/ /_/;
  &{\&$routine};
}

rlmain::adminhtml;


sub menu	{
  my $resetstats;
  my $reset = gettext("Reset stats");
  my $new = gettext("New ring");
  my $ringadm = gettext("Ring admin");
  my $emailrms = gettext("Email RMs");
  my $backup = gettext("Backup data");
  my $restore = gettext("Restore data");
  $rlmain::pagetitle = gettext("Master admin");
  if ($rlmain::stats)	{
    $resetstats = "<div class=\"button\"><input class=\"button\" $rlmain::ns4width "
                . "type=\"submit\" name=\"routine\" value=\"$reset\" /></div>";
  } else	{
    $resetstats = '';
  }

  return qq~<hr /><br />
<div class="button"><input class="button" $rlmain::ns4width type="submit" name="routine" value="$new" /></div>
<div class="button"><input class="button" $rlmain::ns4width type="submit" name="routine" value="$ringadm" /></div>
<div class="button"><input class="button" $rlmain::ns4width type="submit" name="routine" value="$emailrms" /></div>
<div class="button"><input class="button" $rlmain::ns4width type="submit" name="routine" value="$backup" /></div>
<div class="button"><input class="button" $rlmain::ns4width type="submit" name="routine" value="$restore" /></div>
$resetstats
~;

}

sub Backup_data	{
  my ($disabled, $nixcheck, $wincheck, $file);
  my $header = gettext("Backup data");
  my $intro = gettext("This routine creates a backup file in the data\ndirectory with all the ring and site data.");
  my $system = gettext("File system");
  my $backup = gettext("Create backup file");
  my $or = gettext("or");
  $rlmain::pagemenu = &menu;
  $rlmain::result = "<h4>$header</h4>\n";
  if ($rlmain::cgipath =~ /^\w:/)	{
    $disabled = 'disabled="disabled"';
    $nixcheck = '';
    $wincheck = 'checked="checked"';
  } else	{
    $disabled = '';
    $nixcheck = 'checked="checked"';
    $wincheck = '';
  }
  if ($rlmain::data{'submit'})	{
    for ('.tar', '.tar.gz', '.tar.Z', '.zip')	{
      if (-f "$rlmain::datapath/ringlink_backup$_")	{
        unlink ("$rlmain::datapath/ringlink_backup$_")
         || die "Can't remove \"ringlink_backup$_\"\n$!";
      }
    }
    $ENV{PATH} = rlmain::securepath();
    umask 0022;
    if ($rlmain::data{'system'} eq 'unix')	{
      UNIX:	{
        $file = 'ringlink_backup.tar';
        qx(cd $rlmain::datapath; tar cvf $file *);
        qx(cd $rlmain::datapath; gzip $file);
        if (-f "$rlmain::datapath/$file.gz")	{
          $file .= '.gz';
          last UNIX;
        }
        qx(cd $rlmain::datapath; compress $file);
        if (-f "$rlmain::datapath/$file.Z")	{
          $file .= '.Z';
          last UNIX;
        }
        $file = '' unless -f "$rlmain::datapath/$file";
      }
    } elsif ($rlmain::data{'system'} eq 'windows')	{
      if (eval "require Compress::Zlib")	{
        $file = 'ringlink_backup.zip';
        require Archive::Zip::Tree;
        my $zip = Archive::Zip->new();
        $zip->addTree( $rlmain::datapath, '', sub { !/^\.|^_vti|$rlmain::datapath$/ } );
        $file = '' unless $zip->writeToFileNamed("$rlmain::datapath/$file") == 0;
      } else	{
        (my $error = $@) =~ s/\n/<br \/>\n/g;
        $rlmain::result = "<h4>$header ( <tt>.zip</tt> )</h4>\n"
         . rlmain::missingsoftware() . "<p><tt>$error</tt></p>";
        rlmain::adminhtml();
        rlmain::exit();
      }
    }
    if ($file)	{
      $rlmain::result = '<p class="success">' . sprintf (
        gettext("The backup file %s was created and saved\nin the data directory. Store it safely!"),
        "<span style=\"font-family: 'courier new', monospace; color: $rlmain::coltxt\">$file</span>") . "</p>\n"
        . '<p class="error">' . gettext("Important!") . "</p>\n<p>" .
        gettext("If downloading the file to the hard disk,\nyou must transfer it in <b>binary mode</b>,\nor else you may not be able to use it.")
        . '</p>';
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
<input type="hidden" name="routine" value="Backup data" />
<p><input class="button" type="submit" name="submit" value="$backup" /></p>
</form>~;

  }
}

sub Restore_data	{
  my $tmp = '';
  my (@files, $tar);
  my $header = gettext("Restore data");
  my $intro = gettext("Upload your backup file to the data\ndirectory, and click the button.");
  my $restore = gettext("Restore now");
  $rlmain::pagemenu = &menu;
  if ($rlmain::data{'submit'})	{
    opendir(DIR, $rlmain::datapath) || die "Can't open $rlmain::datapath\n$!";
    @files = grep { /^(ringlink_backup|rl_backup_\w+)\.(tar\.gz|tar\.Z|tar|zip)$/ } readdir(DIR);
    closedir DIR;
    $ENV{PATH} = rlmain::securepath();
    if (scalar @files == 1)	{
      $files[0] = $1 if $files[0] =~ /^([\w.]+)$/;
    }
    if (scalar @files > 1)	{
      $tmp = 'too many';
    } elsif (!@files)	{
      $tmp = 'no file';
    } elsif	($files[0] =~ /\.gz$/)	{
      ($tar = $files[0]) =~ s/(.+)\.gz/$1/;
      qx(cd $rlmain::datapath; gunzip $files[0]; tar xvf $tar);
      unless (-f "$rlmain::datapath/$tar")	{
        qx(cd $rlmain::datapath; gzip -d $files[0]; tar xvf $tar);
      }
      $tmp = 'done' if -f "$rlmain::datapath/$tar";
    } elsif	($files[0] =~ /\.Z$/)	{
      ($tar = $files[0]) =~ s/(.+)\.Z/$1/;
      qx(cd $rlmain::datapath; uncompress $files[0]; tar xvf $tar);
      $tmp = 'done' if -f "$rlmain::datapath/$tar";
    } elsif	($files[0] =~ /\.zip$/)	{
      if (eval "require Compress::Zlib")	{
        require Archive::Zip::Tree;
        my $zip = Archive::Zip->new();
        chdir $rlmain::datapath;
        $zip->read($files[0]);
        &fileFormatFix( $zip->membersMatching('\.(txt|db)$') );
        $tmp = 'done' if $zip->extractTree() == 0;
        chdir $rlmain::cgipath;
      } else	{
        (my $error = $@) =~ s/\n/<br \/>\n/g;
        $rlmain::result = "<h4>$header ( <tt>.zip</tt> )</h4>\n"
         . rlmain::missingsoftware() . "<p><tt>$error</tt></p>";
        rlmain::adminhtml();
        rlmain::exit();
      }
    } else	{
      $tmp = 'done' if eval "qx(cd $rlmain::datapath; tar xvf $files[0])";
    }
    if ($tmp eq 'too many')	{
      $rlmain::result = '<p class="error">' . gettext("There are more than one backup file.") . '</p><p>'
      . gettext("Remove or rename the file(s) that\nshall not be restored, and try again."). '</p>';
    } elsif ($tmp eq 'no file')	{
      $rlmain::result = '<p class="error">' . gettext("Couldn't find any backup file.") . '</p>';
    } elsif ($tmp eq 'done')	{
      $rlmain::result = '<p class="success">' . gettext("Done.") . '</p><p>'
      . gettext("Please check that the data was\nsuccessfully restored.") . '</p>';
    } else	{
      $rlmain::result = '<p class="error">' . gettext("Something went wrong.") . '</p>';
    }
  } else	{

    $rlmain::result = qq~<h4>$header</h4>
<p>$intro</p>
<form method="post" action="$rlmain::cgiURL/$rlmain::action">
<input type="hidden" name="routine" value="Restore data" />
<p><input class="button" type="submit" name="submit" value="$restore" /></p>
</form>~;

  }
}

sub fileFormatFix	{
  for (@_)	{
    my ($string, $status) = $_->contents();
    die "error $status\n$!" unless $status == 0;
    if ($rlmain::cgipath =~ /^\w:/)	{
      $string =~ s/([^\r])\n/$1\r\n/g;
    } else	{
      $string =~ s/\r\n/\n/g;
    }
    $_->contents($string);
  }
}

sub Reset_stats	{
  my (@rings, $pm, $ringid, $ringdir, @files, @sites, $sitedir, $convert);
  open FH, "> $rlmain::datapath/statsupdate.txt" or die "Can't open 'statsupdate.txt'\n$!";
  flock FH, LOCK_EX or die $!;
  print FH rlmain::statsupdatetime();
  close FH or die $!;
  chmod $rlmain::filemode, "$rlmain::datapath/statsupdate.txt";
  opendir(DIR, $rlmain::datapath) || die "Can't open $rlmain::datapath\n$!";
  @rings = grep { !/^\.|^_vti/ && -d "$rlmain::datapath/$_" } readdir(DIR);
  closedir DIR;
  require Parallel::ForkManager;
  $pm = new Parallel::ForkManager($rlmain::max_processes > 10 ? 10 : $rlmain::max_processes);
  for (@rings)	{
    my $pid = $pm -> start and next;
    $ringid = rlmain::secureword($_);
    $ringdir = "$rlmain::datapath/$ringid";
    chmod $rlmain::dirmode, $ringdir;
    rlmain::sitecountupdate ($ringid);
    opendir(DIR, $ringdir) || die "Can't open $ringdir\n$!";
    @files = grep { !/^\./ && -f "$ringdir/$_" } readdir(DIR);
    closedir DIR;
    for (@files)	{
      $_ = $1 if /^([\w.]+)$/;
      chmod $rlmain::filemode, "$ringdir/$_";
    }
    opendir(DIR, $ringdir) || die "Can't open $ringdir\n$!";
    @sites = grep { !/^\.|^_vti/ && -d "$ringdir/$_" } readdir(DIR);
    closedir DIR;
    for (@sites)	{
      $sitedir = "$ringdir/" . rlmain::secureword($_);
      chmod $rlmain::dirmode, $sitedir;
      for ('genhits', 'rechits')	{
        my @hits = ();
        if (-f "$sitedir/$_.db")	{
          # converts text file to DBM files instead of resetting the stats
          open STATS, "< $sitedir/$_.db" or die "Can't open $sitedir/$_.db\n$!";
          chomp (@hits = <STATS>);
          s/^(\d{2}\-)/20$1/ for @hits;
          close STATS;
          unlink "$sitedir/$_.db" or die "Can't remove $sitedir/$_.db\n$!";
          $convert = 1;
        }
        open DBLOCK, "> $sitedir/$_.lockfile" or die "Can't open '$sitedir/$_.lockfile'\n$!";
        flock DBLOCK, LOCK_EX or die $!;
        tie my %hits, 'SDBM_File', "$sitedir/$_", O_RDWR|O_CREAT, $rlmain::filemode
         or die "Can't bind $sitedir/$_\n$!";
        if ((keys %hits)[0] and (keys %hits)[0] !~ /^20/)	{
          # converts to new date format instead of resetting the stats
          for (keys %hits) {
            $hits{"20$_"} = $hits{$_};
            delete $hits{$_};
          }
          $convert = 1;
        } else	{
          %hits = @hits;
        }
        untie %hits or die $!;
        close DBLOCK or die $!;
        chmod $rlmain::filemode, "$sitedir/$_.dir", "$sitedir/$_.pag", "$sitedir/$_.lockfile";
      }
    }
    tie my %ringstats, 'SDBM_File', "$ringdir/stats", O_RDWR|O_CREAT, $rlmain::filemode
     or die "Can't bind $ringdir/stats\n$!";
    %ringstats = () unless $convert;
    untie %ringstats;
    $pm -> finish;
  }
  $pm -> wait_all_children;
  $rlmain::pagemenu = menu();
  $rlmain::result = '<p class="success">' . ($convert ? 'Statistics files converted to '
  . 'new format.' : gettext("Stats reset.")) . "</p>\n<p>" . ($convert ? 'Run the &quot;'
  . 'Reset stats&quot; routine once more if you want to reset everything to zero.'
  : gettext("File permissions for the data directory was updated, as well.")) . '</p>';
}

sub New_ring	{
  $rlmain::pagemenu = &menu;
  if ($rlmain::data{'submit'})	{
    ring::validation();
    if (!@rlmain::error)	{
      ring::create();
      rlmain::entify($rlmain::data{'ringtitle'});
      my $ringtitle = '</p><p style="font-weight: bold">' . $rlmain::data{'ringtitle'}
      . '</p><p class="success">';
      $rlmain::result = '<p class="success">'
      . sprintf (gettext("The ring %s was successfully created."), $ringtitle) . '</p>';
    } else	{
      $rlmain::result = '<h4>' . gettext("Add new ring") . "</h4>\n";
      $rlmain::result .= ring::form();
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
    $rlmain::result = '<h4>' . gettext("Add new ring") . "</h4>\n";
    $rlmain::result .= ring::form();
  }
}

sub Ring_admin	{
  my $switchheader = gettext("Switch to the ring admin menu");
  my $ringid = gettext("Ring ID");
  my $switchbutton = gettext("Switch");
  rlmain::ringlist();
  if (!@rlmain::rings)	{
    $rlmain::pagemenu = &menu;
    $rlmain::result = rlmain::noring();
  } elsif ($rlmain::data{'ringid'})	{
    rlmain::getringvalues();
    $rlmain::pagemenu = ring::menu();
  } else	{
    if ($rlmain::data{'submit'})	{
      push (@rlmain::error, '<p class="error">' . gettext("Select ring ID!") . '</p>');
    }
    $rlmain::pagemenu = &menu;
    rlmain::ringselect();

    $rlmain::result = qq~<h4>$switchheader</h4>
@rlmain::error
<form method="post" action="$rlmain::cgiURL/$rlmain::action">
<p>$ringid<br />
$rlmain::ringselect</p>
<input type="hidden" name="routine" value="Ring admin" />
<p><input class="button" type="submit" name="submit" value="$switchbutton" /></p>
</form>~;

  }
}

sub Email_RMs	{
  $rlmain::pagemenu = &menu;
  opendir(DIR, $rlmain::datapath) || die "Can't open $rlmain::datapath\n$!";
  my @rings = grep { !/^\.|^_vti/ && -d "$rlmain::datapath/$_" } readdir(DIR);
  closedir DIR;
  if (!@rings)	{
    $rlmain::result = rlmain::noring();
    rlmain::adminhtml();
    rlmain::exit();
  }
  my $select_comma = '';
  my $select_whitespace = '';
  my $select_newline = '';
  my $addresses = '';
  my $header = gettext("Email RMs");
  my $intro = gettext("Display a list with the ringmasters' email addresses.\nThe list can be copied and pasted into your email client.");
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
    for (@rings)	{
      open (RING, "< $rlmain::datapath/$_/ring.db") || die "Can't open '$_/ring.db'\n$!";
      chomp (my @ringvalues = <RING>);
      close (RING);
      for (split /, /, $ringvalues[6]) { $addresses{$_}++ }
    }
    $addresses = join $separator, sort {uc($a) cmp uc($b)} keys %addresses;
  }
  $select_comma = 'selected="selected"' if !$rlmain::data{'submit'} || $rlmain::data{'separator'} eq 'comma';
  $select_whitespace = 'selected="selected"' if $rlmain::data{'separator'} eq 'whitespace';
  $select_newline = 'selected="selected"' if $rlmain::data{'separator'} eq 'newline';

  $rlmain::result = qq~<h4>$header</h4>
<p>$intro</p>
<form method="post" action="$rlmain::cgiURL/$rlmain::action">
<table width="100%"><tr><td>
<p>$sep<br /><select name="separator" size="1">
<option $select_comma value="comma">$comma</option>
<option $select_whitespace value="whitespace">$white</option>
<option $select_newline value="newline">$newline</option></select></p>
</td><td style="vertical-align: middle">
<input type="hidden" name="routine" value="Email RMs" />
<p><input class="button" type="submit" name="submit" value="$show" /></p>
</td></tr></table>
<br />
<div class="textarea"><textarea name="addresses" rows="10" cols="45" $rlmain::wrapoff>
$addresses
</textarea></div>
</form>~;

}

sub Edit_ring	{
  rlmain::getringvalues();
  ring::Edit_ring();
}

sub Customize	{
  rlmain::getringvalues();
  $rlmain::pagemenu = ring::customizemenu();
  $rlmain::result = "<p class=\"success\">$rlmain::data{'result'}</p>";
}

sub Appearance	{
  rlmain::getringvalues();
  ring::Appearance();
}

sub HTML_code	{
  rlmain::getringvalues();
  ring::HTML_code();
}

sub Add_page	{
  rlmain::getringvalues();
  ring::Add_page();
}

sub Add_mail	{
  rlmain::getringvalues();
  ring::Add_mail();
}

sub Code_page	{
  rlmain::getringvalues();
  ring::Code_page();
}

sub New_site	{
  rlmain::getringvalues();
  ring::New_site();
}

sub Site_admin	{
  rlmain::getringvalues();
  ring::Site_admin();
}

sub Edit_site	{
  rlmain::getringvalues();
  ring::Edit_site();
}

sub statuschangemail	{
  rlmain::getringvalues();
  rlmain::getsitevalues();
  site::statuschangemail();
  $rlmain::pagemenu = site::menu();
}

sub Remove_site	{
  rlmain::getringvalues();
  ring::Remove_site();
}

sub Get_code	{
  rlmain::getringvalues();
  rlmain::getsitevalues();
  $rlmain::htmlcode = rlmain::htmlcode();
  $rlmain::pagemenu = site::menu();
  $rlmain::result = site::htmlcode();
}

sub View_stats	{
  rlmain::getringvalues();
  rlmain::getsitevalues();
  $rlmain::pagemenu = site::menu();
  $rlmain::result = site::getstats();
}

sub Active_sites	{
  use ring;
  rlmain::getringvalues();
  ring::Active_sites();
}

sub Inactive_sites	{
  use ring;
  rlmain::getringvalues();
  ring::Inactive_sites();
}

sub Activate	{
  rlmain::getringvalues();
  ring::Activate();
}

sub Deactivate	{
  rlmain::getringvalues();
  ring::Deactivate();
}

sub Remove_ring	{
  rlmain::getringvalues();
  if ($rlmain::data{'submit'} eq gettext("Remove"))	{
    if ($rlmain::data{'removesure'} eq 'on')	{
      my $ring = gettext("Ring:");
      ring::remove();
      $ring::title = $rlmain::title;
      ring::removemail();
      $rlmain::pagetitle = gettext("Ring admin");
      rlmain::entify ($rlmain::ringtitle);
      $rlmain::pagemenu = &menu;

      $rlmain::ring_site = qq~<table cellspacing="8">
<tr>
<td><span>$ring</span></td>
<td><span><a href="$rlmain::ringURL" target="Ringlink">$rlmain::ringtitle</a></span></td>
</tr>
</table>~;

      $rlmain::result = '<p class="success">' . gettext("Ring removed") . '</p>';
    } else	{
      push (@rlmain::error, '<p class="error">'
      . gettext("The ring was not removed,\nsince the checkbox below wasn't checked.") . '</p>');
      $rlmain::result = ring::removeform();
      rlmain::emailhtml();
      rlmain::exit();
    }
  } elsif ($rlmain::data{'submit'} eq gettext("Cancel"))	{
      $rlmain::pagemenu = ring::menu();
  } else	{
      $rlmain::result = ring::removeform();
      rlmain::emailhtml();
      rlmain::exit();
  }
}

sub Check_sites	{
  rlmain::getringvalues();
  ring::Check_sites();
}

sub Reorder_sites	{
  rlmain::getringvalues();
  ring::Reorder_sites();
}

sub Send_email	{
  rlmain::getringvalues();
  ring::Send_email();
}

sub Search_sites	{
  rlmain::getringvalues();
  ring::Search_sites();
}

sub Backup_ring	{
  rlmain::getringvalues();
  ring::backup();
}

sub Directory	{
  rlmain::getringvalues();
  ring::directory();
}

