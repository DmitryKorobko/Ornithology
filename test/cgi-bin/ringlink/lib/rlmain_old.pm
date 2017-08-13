############################> Ringlink <############################
#                                                                  #
#  $Id: rlmain.pm,v 1.204 2005/02/21 17:12:08 gunnarh Exp $        #
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

package rlmain;
$rlmain::VERSION = '3.2';

use 5.005;
use strict;
use Symbol 'qualify_to_ref';
use Fcntl qw(:DEFAULT :flock);
use SDBM_File;

use rlconfig;
use Locale::PGetText;

BEGIN	{
# To facilitate debugging, the preceding # characters at the five
# lines below can be removed:

 # set_message(\&handle_errors);
 # use CGI::Carp qw(carpout set_message);
 # open(LOG, '>>data/ERRORMSG.TXT') || die "Can't write to ERRORMSG.TXT; $!\n\n";
 # carpout(\*LOG);
 # $^W = 1;

# Ensure, though, that those lines are commented out in production
# installations. Note also that the path to the data directory may need
# to be edited.

  sub handle_errors	{
    my $msg = shift;
    print "<h1>Error!</h1>\n", "<pre>$msg</pre>\n",
          "<p>You might find more details in <tt>ERRORMSG.TXT</tt> in the data directory.";
  }

  sub exit {
    if ($ENV{MOD_PERL}) {
      if ($] < 5.006)	{
        require Apache;
        Apache::exit();
      }
    }
    exit;
  }

  # disregard "flock() unimplemented" errors on e.g. W95 and W98
  *CORE::GLOBAL::flock = sub(*$)	{
    my $fh = qualify_to_ref(shift, caller);
    unless (eval { CORE::flock $fh, shift })	{
      return 0 unless $@ =~ /unimplemented/;
    }
    return 1;
  };

  # make sprintf() able to reorder multiple arguments in perl < v5.8.0
  # A limitation with this fix is that the presence of e.g. a '%2$s'
  # conversion in the format string precludes the use of other additional
  # attributes in the same string, besides format parameter indexes.
  *CORE::GLOBAL::sprintf = sub($@)	{
    my $fmt = shift;
    ORDER:	{
      last ORDER if $] >= 5.008 or @_ < 2;
      last ORDER unless $fmt =~ /(?:^|[^%])%\d+\$[sd]/;
      my ($i, @p) = 0;
      $fmt =~ s{((?:^|[^%])%)(?:(\d+)\$)?([sd])}
               { push @p, $2 ? $2-1 : $i++; $1.$3 }ge;
      return CORE::sprintf $fmt, @_[@p];
    }
    CORE::sprintf $fmt, @_;
  };

  $rlmain::lib ||= $INC[0];  # conditional assignment because of mod_perl

  # make the use of startup.pl optional
  if ($ENV{MOD_PERL}) {
    require ring;
    require site;
    if (eval "require Compress::Zlib")	{
      require Archive::Zip::Tree;
    }
    require File::Find;
    require Mail::Sender;
    require MIME::QuotedPrint;
    require Parallel::ForkManager;
    require Tie::File;
  }

}  # BEGIN block ends here

sub execstart	{
  # initialize certain global variables to ensure that they are empty
  # and/or can be used without warnings
  $rlmain::data = '';
  $rlmain::pagetitle = '';
  $rlmain::ring_site = '';
  $rlmain::pagemenu = '';
  $rlmain::result = '';
  $rlmain::sendmail = '';
  %rlmain::smtp = ();
  $rlmain::allowringadd = 0;
  $rlmain::stats = 0;
  $rlmain::minsites = 0;
  $rlmain::ringspermainpage = 0;
  $rlmain::nolist = 0;
  $rlmain::ringURL = '';
  $rlmain::logoURL = '';
  $rlmain::allowsiteadd = '';
  $rlmain::hide2ndURL = '';
  $rlmain::created = '';
  $rlmain::sitetitle = '';
  $rlmain::siteid = '';
  $rlmain::sitedesc = '';
  $rlmain::entryURL = '';
  $rlmain::codeURL = '';
  $rlmain::ringlang = '';
  $rlmain::browserlang = 0;
  $rlmain::nomail = 0;
  @rlmain::activesites = ();
  %rlmain::activesites = ();
  @rlmain::inactivesites = ();
  @rlmain::error = ();
  %rlmain::charset = ();
  %rlmain::lang = ();
  %rlmain::routines = ();
  %rlmain::cgiURL = ();
  %rlmain::data = ();
  %rlmain::redir = ();
  my @datakeys = qw/pw submit pass removesure routine allowsiteadd hide2ndURL completeinfo sitesperpage
                    result ordnumb startsite method next prev list rand next5 prev5 search stats home
                    limit status separator ringid offset siteid entryURL codeURL/;
  $rlmain::data{$_} = '' for @datakeys;
  $rlmain::data{'timeout'} = 0;
  $rlmain::data{'maxsites'} = 0;


  # Unix file permissions controlled via the variables in rlconfig.pm
  umask 0;

  # Set name of the executable
  ($rlmain::action = ($0 =~ /[\\\/]/ ? $0 : $ENV{'SCRIPT_FILENAME'})) =~ tr!\\!/!;
  ($rlmain::cgipath, $rlmain::action) = $rlmain::action =~ m!([\w\-./: ]+)/([\w\-.]+)!;

  # Get system variables from rlconfig.pm
  rlconfig::systemvar();

  # multiple processes doesn't work with mod_perl
  $rlmain::max_processes = 0 if $ENV{MOD_PERL};

  # Check whether path to the executables is set
  die "The \$rlmain::cgipath variable is empty. Set it explicitly in the rlconfig.pm file.\n\n"
   unless $rlmain::cgipath;

  # Remove possible trailing slashes
  s!/$!! for ($rlmain::cgiURL, $rlmain::cgipath, $rlmain::datapath, $rlmain::sitecountpath);

  # Trim case
  $rlmain::action = filenamefix($rlmain::action);

  # Check that the data directory exists and is writable
  DATATEST:	{
    last DATATEST if -d $rlmain::datapath && -r _ && -w _ && -x _;
    if (-d $rlmain::datapath)	{
      die "You need to chmod the data directory 777\n\n";
    } else	{
      my $tip;
      if (-d "$rlmain::cgipath/data")	{
        $tip = "It looks as if the default setting\n\n"
         . "\$rlmain::datapath = \$rlmain::cgipath . '/data';\n\nwould work fine.\n";
      } else	{
        $tip = "A hint: The path to the CGI directory is:\n$rlmain::cgipath\n";
      }
      die "Problem with the \$rlmain::datapath variable in rlconfig.pm:\n"
          . "$rlmain::datapath does not exist.\n\n$tip\n";
    }
  }

  # Check that the sitecount directory exists and is writable
  SITECOUNTTEST:	{
    last SITECOUNTTEST unless $rlmain::stats;
    last SITECOUNTTEST if -d $rlmain::sitecountpath && -r _ && -w _ && -x _;
    if (-d $rlmain::sitecountpath)	{
      die "You need to chmod the sitecount directory 777\n\n";
    } else	{
      die "Problem with the \$rlmain::sitecountpath variable in rlconfig.pm:\n"
          . "$rlmain::sitecountpath does not exist.\n\n"
          . "A hint: The path to the document root is:\n$ENV{DOCUMENT_ROOT}\n\n";
    }
  }

  # Set if email is disabled
  $rlmain::nomail = 1 unless $rlmain::sendmail or $rlmain::smtp{smtp};

  # Set constants
  my @ringcolornames = qw/colbg coltablebg coltxt colemph colerr collink colvlink/;

  @rlmain::ringnames = ('ringid', 'ringtitle', 'ringdesc', 'ringURL', 'ringpw', 'rmname', 'rmemail',
                       'allowsiteadd', 'sitesperlistpage', 'logoURL', 'logowidth', 'logoheight',
                       @ringcolornames, 'hide2ndURL', 'ringlang', 'created', 'dirmail');

  @rlmain::sitenames = qw/siteid status sitetitle sitedesc keywords entryURL codeURL
                          sitepw wmname wmemail updated/;

  @rlmain::ringsubstitutes = qw/ringtitle ringURL rmname rmemail/;
  @rlmain::sitesubstitutes = qw/siteid sitetitle sitedesc keywords entryURL codeURL
                                sitepw wmname wmemail htmlcode/;

  my @refs = \($rlmain::ringid, $rlmain::ringtitle, $rlmain::ringdesc, $rlmain::ringURL,
               $rlmain::ringpw, $rlmain::rmname, $rlmain::rmemail, $rlmain::allowsiteadd,
               $rlmain::sitesperlistpage, $rlmain::logoURL, $rlmain::logowidth,
               $rlmain::logoheight, $rlmain::colbg, $rlmain::coltablebg, $rlmain::coltxt,
               $rlmain::colemph, $rlmain::colerr, $rlmain::collink, $rlmain::colvlink,
               $rlmain::hide2ndURL, $rlmain::ringlang, $rlmain::created, $rlmain::dirmail,
               $rlmain::siteid, $rlmain::status, $rlmain::sitetitle, $rlmain::sitedesc,
               $rlmain::keywords, $rlmain::entryURL, $rlmain::codeURL, $rlmain::sitepw,
               $rlmain::wmname, $rlmain::wmemail, $rlmain::updated, $rlmain::htmlcode,
               $rlmain::cgiURL);

  my $i = 0;
  for (@rlmain::ringnames, @rlmain::sitenames, 'htmlcode', 'cgiURL') {
    $rlmain::refs{$_} = $refs[$i++];
  }

  my @routinebuttonvalues = ('New ring', 'Ring admin', 'Email RMs', 'Backup data', 'Restore data',
                            'Reset stats', 'Active sites', 'Inactive sites', 'Search sites', 'Check sites',
                            'Reorder sites', 'Send email', 'Edit ring', 'Customize', 'Directory',
                            'Directories', 'Backup ring', 'Remove ring', 'New site', 'Site admin',
                            'Appearance', 'HTML code', 'Add page', 'Add mail', 'Code page', 'Get code',
                            'Edit site', 'View stats', 'Remove site');

  my @addroutines = qw/Activate Deactivate Active_sites Inactive_sites Search_sites
                       Edit_site Remove_site statuschangemail/;

  $rlmain::time = time;

  # Set colors to default
  for (keys %rlmain::colors)	{
    ${$rlmain::refs{$_}} = $rlmain::colors{$_};
  }

  # Check whether it's time for daily update of the ring stats
  if ($rlmain::stats and $rlmain::action !~ /^admin/i)	{
    open FH, "+< $rlmain::datapath/statsupdate.txt" or die "Missing file: 'statsupdate.txt'\n"
    . "Create it by running the \"Reset stats\" routine from admin.pl.\n\n";
    flock FH, LOCK_EX or die $!;
    my $update = 1 if $rlmain::time > <FH>;
    if ($update)	{
      seek FH, 0, 0;
      print FH statsupdatetime();
    }
    close FH or die $!;
    updateringstats() if $update;
  } elsif (!$rlmain::stats and -f "$rlmain::datapath/statsupdate.txt")	{
    unlink "$rlmain::datapath/statsupdate.txt" or die "Can't remove 'statsupdate.txt'\n$!";
    my $dir = $rlmain::sitecountpath;
    opendir DIR, $dir or die "Can't open $dir\n$!";
    my @files = grep { -f "$dir/$_" and /\.js$/ } readdir DIR;
    closedir DIR;
    for (@files)	{
      $_ = $1 if /^([\w.]+)$/;
      unlink "$dir/$_" or die "Can't remove $dir/$_\n$!";
    }
  }

  # Language fix
  Locale::PGetText::dbmselect;
  Locale::PGetText::setLocaleDir("$rlmain::cgipath/lang/mo");
  opendir DIR, "$rlmain::cgipath/lang/mo"
   or die "Can't open $rlmain::cgipath/lang/mo\n$!";
  my @pofiles = grep { /^[a-z]{2}(\.\w+)?$/ && -f "$rlmain::cgipath/lang/mo/$_" } readdir(DIR);
  closedir DIR;
  for my $po (@pofiles)	{
    $po =~ s/^([a-z]{2}).*/$1/;
    $rlmain::lang{$po} = $po;
  }
  charset();
  if ($rlmain::action =~ /admin/i)	{
    for my $lang (keys %rlmain::lang)	{
      setlang($lang);
      for my $button (@routinebuttonvalues)	{
        ( my $localstring = gettext($button) ) =~ s/&(?:quot|#34);/"/g;
        if ($rlmain::routines{$localstring} and $rlmain::routines{$localstring} ne $button
            and $rlmain::routines{$localstring} ne 'Directory')	{
          die "Problem in the $lang.po file: The string \"$localstring\", that is the "
            . "translation of\nthe routine name \"$button\", is already in use as "
            . "translation of \"$rlmain::routines{$localstring}\".\n";
        }
        $rlmain::routines{$localstring} = $button eq 'Directories' ? 'Directory' : $button;
      }
    }
    for (@routinebuttonvalues, @addroutines)	{
      $rlmain::routines{$_} = $_ eq 'Directories' ? 'Directory' : $_;
    }
  }
  if ($ENV{'HTTP_ACCEPT_LANGUAGE'})	{
    for (qw/next list main search stats goto home rand prev skip/)	{
      if ($rlmain::action =~ /^$_/i)	{
        my @browserlang = split (/,/, $ENV{'HTTP_ACCEPT_LANGUAGE'});
        for (@browserlang)	{
          my $browserlang = substr($_, 0, 2);
          if ($rlmain::lang{$browserlang})	{
            setlang($browserlang);
            $rlmain::browserlang = 1;
            last;
          }
        }
        last;
      }
    }
  }
  setlang($rlmain::lang) unless $rlmain::browserlang;

  # Browser specific HTML fix
  my $browser = $ENV{'HTTP_USER_AGENT'};
  if ($browser =~ /Mozilla\/[34]/ && $browser !~ /MSIE|Opera/) {  # NS 3/4
    $rlmain::wrapsoft = 'wrap="soft"';   # 'off' is default
    $rlmain::ns4width = 'width="130"';   # Unknown INPUT attribute in XHTML, but useful
                                         # in NS4 for the left pane buttons.
  } else	{
    $rlmain::wrapsoft = '';
    $rlmain::ns4width = '';
  }
  $rlmain::wrapoff = $browser =~ /MSIE|Gecko/                     # NS 6/7, Mozilla, MSIE
   && $browser !~ /Opera/ ? 'wrap="off"' : '';                    # ('soft' is default)
  $rlmain::wraphard = $browser =~ /Mozilla\/[34]|Gecko/           # NS 3/4/6/7, Mozilla
   && $browser !~ /Opera/ ? 'wrap="hard"' : '';                   # or MSIE
  $rlmain::leftalign = $browser =~ /Opera/ ? '; text-align: left' : '';

  # This line was used in a SSL experiment where the admin procedures were run
  # through a secure server. However, I haven't figured out yet how to make the
  # use of SSL to interact with images (ring logo and/or images in the HTML code).
  #$rlmain::cgiURL = 'https://rbro4.securesites.com/gunnar/cgi-bin/ringlink' if $rlmain::action =~ /admin/i;

  # Read indata
  if ($ENV{REQUEST_METHOD} eq 'POST')	{
    my $len = $ENV{CONTENT_LENGTH};
    $len <= 131072 or die "Too much data submitted.\n";
    binmode STDIN;
    read(STDIN, $rlmain::data, $len) == $len or die "Reading of posted data failed.\n";
  } else	{
    $rlmain::data = $ENV{QUERY_STRING};
  }
  $rlmain::data =~ tr/+/ /;
  my %names = map { $_, 1 } qw/ringid siteid offset/;
  for (split /[&;]/, $rlmain::data)	{
    my ($name, $value) = map { s/%(..)/chr(hex $1)/eg; $_ } split /=/, $_, 2;
    $name = lc $name if $names{ lc $name };
    $rlmain::data{$name} = $value;
  }

  # Check if ring shall be redirected
  REDIRECT:	{
    my $id = $rlmain::data{ringid} or last REDIRECT;
    open FILE, "< $rlmain::datapath/ringredirect.db" or last REDIRECT;
    my $skip = qr/^(?:#|\s*$)/;
    my $redir = qr/(\w+)\s{2,}(\S+(?: \S+)*)(?:\s{2,}(\S+))?/;
    while (<FILE>)	{
      next if /$skip/;
      if ( /$redir/ )	{
        ($rlmain::redir{$1}{url} = $2) =~ s/ /%20/g;
        $rlmain::redir{$1}{ext} = ($3 or '');
      }
    }
    close FILE or die $!;
    last REDIRECT unless $rlmain::redir{$id};
    last REDIRECT if $rlmain::action =~ /^newring/i;
    last REDIRECT if $rlmain::data{routine} and %rlmain::routines and
      $rlmain::routines{ $rlmain::data{routine} } eq 'New ring';
    my ($file) = $rlmain::action =~ /^(\w+)/;
    print 'Location: ', $rlmain::redir{$id}{url}, "/$file", $rlmain::redir{$id}{ext},
          ( $ENV{QUERY_STRING} ? "?$ENV{QUERY_STRING}" : '' ), "\n\n";
    rlmain::exit();
  }

  # Special for rings with different domains
  if (%rlmain::cgiURL)	{
    if ($rlmain::data{'ringid'})	{
      $rlmain::cgiURL = $rlmain::cgiURL{$rlmain::data{'ringid'}}
       || $rlmain::cgiURL{'system default'} || $rlmain::cgiURL;
    } elsif ($rlmain::action =~ /^admin/i)	{
      $rlmain::cgiURL = $rlmain::cgiURL{'system default'} || $rlmain::cgiURL;
    }
  }
}


sub menu	{
  my $siteadmin = gettext("Site admin");
  my $siteadminURL = "$rlmain::cgiURL/" . filenamefix ('siteadmin.pl');
  my $ringadmin = gettext("Ring admin");
  my $ringadminURL = "$rlmain::cgiURL/" . filenamefix ('ringadmin.pl');
  $rlmain::pagetitle = gettext("Ring list");
  if ($rlmain::stats and $rlmain::minsites)	{
    my $sites;
    if ($rlmain::minsites == 1)	{
      $sites = gettext("1 site");
    } elsif ($rlmain::minsites == 2)	{
      $sites = gettext("2 sites");
    } else	{
      $sites = sprintf(gettext("%d sites"), $rlmain::minsites);
    }
    $rlmain::ring_site = '<p style="margin-top: 0.6em; margin-bottom: 0.4em">'
    . sprintf(gettext("Rings with at least %s"), $sites) . '</p>';
  }

  return qq~<hr /><br />
<div class="button"><input class="button" $rlmain::ns4width type="button" value="$siteadmin"
 onclick="top.location='$siteadminURL'" /></div>
<div class="button"><input class="button" $rlmain::ns4width type="button" value="$ringadmin"
 onclick="top.location='$ringadminURL'" /></div>~;

}

sub pwcheck	{
  my ($pw, $ring, $site) = @_;
  my $result = 0;
  (my $name = $rlmain::title) =~ s/\W/_/g;
  $name .= "/$ring" . ($site ? "/$site" : '') if $ring;
  if ($ENV{'HTTP_COOKIE'}) {
    for (split /; /, $ENV{'HTTP_COOKIE'})	{
      my ($key, $val) = split /=/;
      if ($key eq $name)	{
        $result = 1 if $val eq crypt($pw, 'pw');
        last;
      }
    }
  }
  unless ($result)	{
    if ($rlmain::data{'pw'} and $rlmain::data{'pw'} eq $pw)	{
      print "Set-cookie: $name=", crypt ($pw, 'pw'), "\n";
      $result = 1;
    }
  }
  $result
}

sub filenamefix	{
  my $filename = shift;
  opendir DIR, $rlmain::cgipath or die "Can't open $rlmain::cgipath\n$!";
  my @execfiles = grep { !/^\./ && -f "$rlmain::cgipath/$_" } readdir DIR;
  closedir DIR;
  $filename =~ s/^(\w+)(?:\.\w+)?$/$1/;
  for (sort @execfiles)	{
    if (/^$filename/i)	{
      $filename = $_;
      last;
    }
  }
  $filename
}

sub trim	{
  for ( grep defined, @_ )	{
    s/^\s+//;
    s/\s+$//;
    s/\s+/ /g;
  }
  @_ > 1 ? @_ : $_[0]
}

sub entify {
  my $ref = defined wantarray ? [ @_ ] : \@_;
  for ( grep defined, @$ref )	{
    s/&/&amp;/g;
    s/"/&quot;/g;
    s/</&lt;/g;
    s/>/&gt;/g;
  }
  @$ref > 1 ? @$ref : $$ref[0]
}

sub nameclean {
  my $name=shift;
  if ($name =~ m/[^ \w]/) {
    $name=~tr/\"/\'/;
    $name=qq{"$name"};
#    encode_qp($name); # Doesn't always work well together with doublequotes
  }
  $name
}

sub encode_qp	{
  unless ($rlmain::charset eq 'ISO-8859-1')	{
    require MIME::QuotedPrint;
    $_[0] = "=?$rlmain::charset?Q?" . MIME::QuotedPrint::encode($_[0]) . '?=';
    $_[0] =~ s/=\s*=/=/g;
    $_[0] =~ s/\s+/=20/g;
  }
  return $_[0];
}

sub ringlist	{
  opendir(DIR, $rlmain::datapath) || die "Can't open data directory\n$!";
  @rlmain::rings = grep { !/^\.|^_vti/ && -d "$rlmain::datapath/$_" } readdir(DIR);
  closedir DIR;
  if ($rlmain::data{'ringid'})	{
    for (@rlmain::rings)	{
      if (uc $rlmain::data{'ringid'} eq uc)	{
        $rlmain::data{'ringid'} = $_;
        last;
      }
    }
  }
}

sub ringselect	{
  my $selectid = gettext("Select ring ID");
  my $startselect = '';
  $startselect = "\n<option selected=\"selected\" value=\"\">- $selectid -</option>" if !$rlmain::data{'ringid'};
  $rlmain::ringselect = "<select name=\"ringid\" size=\"1\">$startselect\n";
  foreach (sort {uc($a) cmp uc($b)} @rlmain::rings)	{
    if ($_ eq $rlmain::data{'ringid'})	{
      $rlmain::ringselect .= "<option selected=\"selected\" value=\"$_\">$_</option>\n";
    } else	{
      $rlmain::ringselect .= "<option value=\"$_\">$_</option>\n";
    }
  }
  $rlmain::ringselect .= '</select>';
}

sub noring	{
  my $noring = gettext("No ring has been registered yet.");
  return "<p class=\"error\">$noring</p>";
}

sub missingsoftware	{
  my $missing = gettext("Unfortunately this routine isn't available\nbecause of insufficient software.");
  return "<p class=\"error\">$missing</p>";
}

sub getringvalues	{
  open (RING, "< $rlmain::datapath/$rlmain::data{'ringid'}/ring.db")
   || die "Can't open '$rlmain::data{'ringid'}/ring.db'\n$!";
  chomp (my @ringvalues = <RING>);
  close (RING);
  for (@rlmain::ringnames)	{
    ${$rlmain::refs{$_}} = shift (@ringvalues);
  }
  if ($rlmain::ringlang && $rlmain::lang{$rlmain::ringlang} && !$rlmain::browserlang)	{
    setlang($rlmain::ringlang);
  }
}

sub sitelist	{
  opendir(DIR, "$rlmain::datapath/$rlmain::data{'ringid'}")
   || die "Can't open '$rlmain::data{'ringid'}'\n$!";
  @rlmain::sites = grep { !/^\.|^_vti/ && -d "$rlmain::datapath/$rlmain::data{'ringid'}/$_" } readdir(DIR);
  closedir DIR;
  if ($rlmain::data{'siteid'})	{
    for (@rlmain::sites)	{
      if (uc $rlmain::data{'siteid'} eq uc)	{
        $rlmain::data{'siteid'} = $_;
        last;
      }
    }
  }
}

sub getsitevalues	{
  open SITES, "< $rlmain::datapath/$rlmain::data{'ringid'}/sites.db"
   or die "Can't open '$rlmain::data{'ringid'}/sites.db'\n$!";
  while (<SITES>)	{
    if (/^$rlmain::data{'siteid'}\t/)	{
      chomp;
      my @sitevalues = split /\t/;
      for (@rlmain::sitenames)	{
        ${$rlmain::refs{$_}} = shift @sitevalues;
      }
      last;
    }
  }
  close (SITES);
}

sub timestamp	{
  my $fmt = (shift or '');
  my $days = (shift or 0);
  my @t = (gmtime($rlmain::time - $days * 86400))[0..5];
  return (sprintf '%d-%02d-%02d', $t[5]+1900, $t[4]+1, $t[3])
  . ($fmt eq 'date' ? '' : sprintf "T%02d:%02d:%02dZ", @t[2,1,0]);
}

sub templatevars	{
  my %tmpl_vars = ();
  $tmpl_vars{version} = \$rlmain::VERSION;
  $tmpl_vars{charset} = \$rlmain::charset;
  $tmpl_vars{colbg} = \$rlmain::colbg;
  $tmpl_vars{coltxt} = \$rlmain::coltxt;
  $tmpl_vars{colerr} = \$rlmain::colerr;
  $tmpl_vars{colemph} = \$rlmain::colemph;
  $tmpl_vars{coltablebg} = \$rlmain::coltablebg;
  $tmpl_vars{leftalign} = \$rlmain::leftalign;
  $tmpl_vars{collink} = \$rlmain::collink;
  $tmpl_vars{colvlink} = \$rlmain::colvlink;
  if ($rlmain::colbg eq $rlmain::colors{'colbg'} and $rlmain::coltablebg eq $rlmain::colors{'coltablebg'})	{
    $tmpl_vars{margcoltablebg} = \$rlmain::leftpanecolors{'tablebg'};
    $tmpl_vars{margcoltxt} = \$rlmain::leftpanecolors{'txt'};
    $tmpl_vars{margcollink} = \$rlmain::leftpanecolors{'link'};
    $tmpl_vars{margcolvlink} = \$rlmain::leftpanecolors{'vlink'};
  } else	{
    $tmpl_vars{margcoltablebg} = $tmpl_vars{coltablebg};
    $tmpl_vars{margcoltxt} = $tmpl_vars{coltxt};
    $tmpl_vars{margcollink} = $tmpl_vars{collink};
    $tmpl_vars{margcolvlink} = $tmpl_vars{colvlink};
  }
  $tmpl_vars{result} = \$rlmain::result;
  $tmpl_vars{homeurl} = \$rlmain::ringlinkURL;
  %tmpl_vars
}

sub doctype	{ # Set doctype showing that Ringlink is XHTML compliant
  return qq~<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
                      "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="$rlmain::currentlang" xmlns="http://www.w3.org/1999/xhtml">~;
}

sub ringlistbutton	{
  return '' if $rlmain::action =~ /^main/i;
  my $cgiURL = $rlmain::cgiURL{'system default'} || $rlmain::cgiURL;
  my $ringlist = gettext("Ring list");
  my $ringlistURL = "$cgiURL/" . filenamefix('main.pl');
  return '<div class="button"><input class="button" '
  . "$rlmain::ns4width type=\"button\" value=\"$ringlist\"\n"
  . " onclick=\"top.location='$ringlistURL'\" /></div>";
}

sub mainhtml	{
  my $noindex = (shift or '');
  my %tmpl_vars = templatevars();
  $tmpl_vars{doctype} = \doctype();
  $tmpl_vars{headtitle} = \$rlmain::ringtitle;
  $tmpl_vars{description} = \qq|<meta name="description" content="$rlmain::ringdesc" />|;
  $tmpl_vars{basehref} = \'';
  if ($noindex eq 'noindex')	{
    $tmpl_vars{noindex} = \'<meta name="robots" content="noindex,follow" />';
  } else	{
    $tmpl_vars{noindex} = \'';
  }
  if ('http://' !~ /$rlmain::logoURL/)	{
    $tmpl_vars{ringlogo} = \("<img src=\"$rlmain::logoURL\" style=\"float: right\" "
    . "width=\"$rlmain::logowidth\" height=\"$rlmain::logoheight\" alt=\"$rlmain::ringtitle\" />");
  } else	{
    $tmpl_vars{ringlogo} = \'';
  }
  $tmpl_vars{ringtitle} = \$rlmain::ringtitle;
  $tmpl_vars{ringurl} = \$rlmain::ringURL;
  $tmpl_vars{ringhome} = \gettext("Ring homepage");
  $tmpl_vars{ringhome2} = \gettext("homepage");
  $tmpl_vars{systemtitle} = \$rlmain::title;
  $tmpl_vars{poweredby} = \gettext("Powered by");

  my $top = gettemplate ('top.html', %tmpl_vars);
  my $main = gettemplate ('main.html', %tmpl_vars);
  my $bottom = gettemplate ('bottom.html', %tmpl_vars);

  printout ($top, $main, $bottom, 0);
}

sub adminhtml	{
  my $notfound = shift;
  my %tmpl_vars = templatevars();
  if ($ENV{'HTTP_USER_AGENT'} =~ /Netscape6\//) {                  # No DOCTYPE in Netscape 6
    $tmpl_vars{doctype} = \"<html lang=\"$rlmain::currentlang\">"; # (or else NS6 doesn't like
  } else {                                                         # the rowspan attribute...)
    $tmpl_vars{doctype} = \doctype();
  }
  $tmpl_vars{headtitle} = \"$rlmain::title - $rlmain::pagetitle";
  $tmpl_vars{description} = \'';
  $tmpl_vars{basehref} = \qq|<base href="$rlmain::ringURL" />|;
  $tmpl_vars{noindex} = \'';
  $tmpl_vars{scripturl} = \"$rlmain::cgiURL/$rlmain::action";
  $tmpl_vars{ns4width} = \$rlmain::ns4width;
  $tmpl_vars{home} = \gettext("Home");
  $tmpl_vars{ringlistbutton} = \ringlistbutton();
  $tmpl_vars{menu} = \$rlmain::pagemenu;
  $tmpl_vars{bodytitle} = \($rlmain::title .
    ($rlmain::pagetitle ? '&nbsp;&nbsp;-&nbsp;&nbsp;' : '') . $rlmain::pagetitle);
  $tmpl_vars{ring_site} = \$rlmain::ring_site;

  my $top = gettemplate ('top.html', %tmpl_vars);
  my $main = gettemplate ('admin.html', %tmpl_vars);
  my $bottom = gettemplate ('bottom.html', %tmpl_vars);

  printout ($top, $main, $bottom, 1, $notfound);
}

sub emailhtml	{
  my %tmpl_vars = templatevars();
  $tmpl_vars{doctype} = \doctype();
  $tmpl_vars{headtitle} = \"$rlmain::title - $rlmain::pagetitle";
  $tmpl_vars{description} = \'';
  $tmpl_vars{basehref} = \'';
  $tmpl_vars{noindex} = \'';
  $tmpl_vars{scripturl} = \"$rlmain::cgiURL/$rlmain::action";
  $tmpl_vars{ns4width} = \$rlmain::ns4width;
  $tmpl_vars{home} = \gettext("Home");
  $tmpl_vars{ringlistbutton} = \ringlistbutton();
  $tmpl_vars{bodytitle} = \($rlmain::title .
    ($rlmain::pagetitle ? '&nbsp;&nbsp;-&nbsp;&nbsp;' : '') . $rlmain::pagetitle);
  $tmpl_vars{ring_site} = \$rlmain::ring_site;

  my $top = gettemplate ('top.html', %tmpl_vars);
  my $main = gettemplate ('mail.html', %tmpl_vars);
  my $bottom = gettemplate ('bottom.html', %tmpl_vars);

  printout ($top, $main, $bottom, 1);
}

sub gettemplate	{
  my ($template, %tmpl_vars) = @_;
  my @error = ();
  open FH, "< $rlmain::cgipath/templates/$template" or die "Can't open 'templates/$template'\n$!";
  my $output = join '', <FH>;
  close FH;
  $output =~ s{%([^%\s]+)%}{
    if ($tmpl_vars{lc $1}) {
      ${$tmpl_vars{lc $1}};
    } else {
      push @error, "Unknown template variable in templates/$template: \%$1\%\n";
    }
  }egi;
  die join '', @error if @error;
  return \$output;
}

sub printout	{
  my ($top, $main, $bottom, $ext) = @_;
  my $notfound = ( $_[4] or '' );
  if ($rlmain::extappURL and $ext)	{
    open (TMP, "> $rlmain::datapath/top.txt") || die "Can't create 'top.txt'\n$!";
    binmode TMP if $^O eq 'MSWin32';
    print TMP $$top;
    close (TMP);
    open (TMP, "> $rlmain::datapath/main.txt") || die "Can't create 'main.txt'\n$!";
    binmode TMP if $^O eq 'MSWin32';
    print TMP $$main;
    close (TMP);
    open (TMP, "> $rlmain::datapath/bottom.txt") || die "Can't create 'bottom.txt'\n$!";
    binmode TMP if $^O eq 'MSWin32';
    print TMP $$bottom;
    close (TMP);
    print "Location: $rlmain::extappURL\n\n";
  } else	{
    binmode STDOUT if $^O eq 'MSWin32';
    print "Status: 404 Not Found\n" if $notfound eq 'Not Found';
    print "Content-type: text/html; charset=$rlmain::charset\n\n";
    print $$top, $$main, $$bottom;
  }
}

sub email	{
  my ($to, $bcc, $subject, $msg);
  my $extras = '';
  ($to, $bcc, $rlmain::from, $subject, $msg) = @_;
  encode_qp($subject);
  unless ($rlmain::from =~ /$rlmain::adminemail/)	{
    $rlmain::mailtitle = rlmain::nameclean($rlmain::title);
    $extras = "X-Sender: $rlmain::mailtitle <$rlmain::adminemail>";
  }
  my $CRLF = $rlmain::sendmail ? "\n" : "\r\n";
  $extras .= ($extras ? $CRLF : '') . "X-Originating-IP: [$ENV{'REMOTE_ADDR'}]" if $ENV{'REMOTE_ADDR'};
  $extras .= ($extras ? $CRLF : '') . 'MIME-Version: 1.0';
  $extras .= $CRLF . "Content-type: text/plain; charset=$rlmain::charset";
  if ($rlmain::smtp{smtp})	{
    require Mail::Sender;
    $Mail::Sender::SITE_HEADERS = $extras;
    $Mail::Sender::NO_X_MAILER = 1;
    ref (my $ms = new Mail::Sender ({%rlmain::smtp, from => $rlmain::from}))
     or die "new Error: $Mail::Sender::Error\n$!";
    ref ($ms -> MailMsg ({to => $to, ($bcc ? 'bcc' : '') => $bcc, subject => $subject,
     msg => $msg})) or die "send Error: $Mail::Sender::Error\n$!";
  } elsif ($rlmain::sendmail)	{
    (my $smpath = $rlmain::sendmail) =~ s/ +-\w+//g;
    $ENV{PATH} = securepath();
    if (-f $smpath && -x _)	{
      my $returnpath = $rlmain::from =~ /<(.+)>/ ? "-f$1" : '';
      open MAIL, "| $rlmain::sendmail -i -t $returnpath"
       or die "Can't open pipe to $smpath\n$!";
      print MAIL "To: $to\n", "From: $rlmain::from\n";
      print MAIL "bcc: $bcc\n" if $bcc;
      print MAIL "$extras\n", "Subject: $subject\n\n", "$msg\n";
      close MAIL or die "Can't close pipe to $smpath\n$!";
    } else	{
      my @MTAsuggest = ();
      my $suggestion;
      if (eval ("require File::Find"))	{
        # Look for path(s) to sendmail or a similar mail transfer agent
        my %path = ();
        my $sep = $rlmain::cgipath =~ /(^\w:)/ ? ';' : ':';
        my @path = ( split($sep, $ENV{'PATH'}), $sep eq ';' ? ("$1/Program Files",
         "$1/Program", "$1/Programs", "$1/scripts", "$1/sendmail") : qw(/bin /sbin /usr/bin
         /usr/sbin /usr/lib /usr/local/bin /usr/local/sbin /usr/local/lib /sendmail) );
        @path{@path} = ();
        File::Find::find (
          sub	{
            if ($File::Find::name =~ /(sendmail|exim)(\.exe)?$/)	{
              push @MTAsuggest, $File::Find::name if -f $File::Find::name && -x _;
            }
          }, keys %path
        );
      }
      if (!@MTAsuggest)	{
        $suggestion = "Check your web host's documentation for the correct path to\n"
         . "sendmail (or a similar mail transfer agent). Optionally you can\n"
         . "comment out \$rlmain::sendmail and set \$rlmain::smtp{smtp}.\n";
      } else	{
        $suggestion = 'Try ' . (@MTAsuggest == 1 ? 'this' : 'one of these')
         . " instead:\n\n";
        for (@MTAsuggest)	{
          $suggestion .= "\$rlmain::sendmail = '$_';\n";
        }
      }
      die "Incorrect path to sendmail in rlconfig.pm: $smpath\n" . (-e $smpath
       ? "It's " . (-l $smpath ? 'a symbolic link to a non-' : (-d $smpath
       ? 'a directory, not an ' : 'not an ') ) . 'executable file' : $!) . ".\n\n$suggestion\n";
    }
  }
}

sub nomailtext	{
  return '<p class="error">' . gettext("Email sending has been disabled.") . '<br />'
  . gettext("No message was sent.") . '</p>';
}

sub htmlcode	{
  open (CODE, "< $rlmain::datapath/$rlmain::data{'ringid'}/htmlcode.txt")
   || die "Can't open '$rlmain::data{'ringid'}/htmlcode.txt'\n$!";
  my @code = <CODE>;
  close (CODE);
  return varconvert(join ('', @code));
}

sub addpage	{
  open (MSG, "< $rlmain::datapath/$rlmain::data{'ringid'}/addpage.txt")
   || die "Can't open '$rlmain::data{'ringid'}/addpage.txt'\n$!";
  my @msg = <MSG>;
  close (MSG);
  return varconvert(join ('', @msg));
}

sub addmail	{
  open (MSG, "< $rlmain::datapath/$rlmain::data{'ringid'}/addmail.txt")
   || die "Can't open '$rlmain::data{'ringid'}/addmail.txt'\n$!";
  my @msg = <MSG>;
  close (MSG);
  return varconvert(join ('', @msg));
}

sub codepage	{
  open (MSG, "< $rlmain::datapath/$rlmain::data{'ringid'}/codepage.txt")
   || die "Can't open '$rlmain::data{'ringid'}/codepage.txt'\n$!";
  my @msg = <MSG>;
  close (MSG);
  return varconvert(join ('', @msg));
}

sub varconvert	{
  for my $subst (@rlmain::ringsubstitutes, @rlmain::sitesubstitutes)	{
    $_[0] =~ s/\$::$subst/\[$subst\]/g;
  }
  return $_[0];
}

sub ringlistpage	{
  my %rings = ();
  ringlist();
  for (@rlmain::rings)	{
    if ($rlmain::stats)	{
      open COUNT, "< $rlmain::sitecountpath/$_.js" or die "Can't open $_.js\n$!";
      my ($count) = <COUNT> =~ /(\d+)/;
      close COUNT;
      if ($count < $rlmain::minsites)	{
        next;
      } else	{
        $rings{$_}{count} = $count;
      }
    }
    open RING, "< $rlmain::datapath/$_/ring.db" or die "Can't open $_/ring.db\n$!";
    @{$rings{$_}}{qw/title desc URL created/} = (<RING>)[1,2,3,21];
    close RING;
    $rings{$_}{created} ||= '';
    chomp @{$rings{$_}}{qw/title desc URL created/};
  }
  use locale;
  my @rings = sort {
    (uc($rings{$a}{title}) =~ /^\W*(\w.+)/)[0]
                       cmp
    (uc($rings{$b}{title}) =~ /^\W*(\w.+)/)[0]
  } keys %rings;
  my $totnum = @rings;
  my $links = "\n<br />";
  if ($rlmain::ringspermainpage and $totnum > $rlmain::ringspermainpage)	{
    if ($rlmain::data{'offset'})	{
      if ($rlmain::data{'offset'} =~ /\D/)	{
        $rlmain::data{'offset'} = 0;
      } elsif ($rlmain::data{'offset'} > $totnum - 1 or $rlmain::data{'offset'} < 0)	{
        $rlmain::data{'offset'} = 0;
      }
    } else	{
      $rlmain::data{'offset'} = 0;
    }
    my $prevtxt = sprintf (gettext("Previous %d rings"), $rlmain::ringspermainpage);
    my $nexttxt = sprintf (gettext("Next %d rings"), $rlmain::ringspermainpage);
    $rlmain::result = '<h4>'
    . sprintf (gettext("%d rings out of %d"), $rlmain::ringspermainpage, $totnum)
    . "</h4>\n";
    my $prevoffset = $rlmain::data{'offset'} - $rlmain::ringspermainpage
    + ($rlmain::ringspermainpage > $rlmain::data{'offset'} ? $totnum : 0);
    my $nextoffset = $rlmain::data{'offset'} + $rlmain::ringspermainpage
    - ($rlmain::data{'offset'} + $rlmain::ringspermainpage > $totnum - 1 ? $totnum : 0);

    $links = qq~
<p class="center">
[ <a href="$rlmain::cgiURL/$rlmain::action?offset=$prevoffset">
$prevtxt</a> |
<a href="$rlmain::cgiURL/$rlmain::action?offset=$nextoffset">
$nexttxt</a> ]
</p>~;

    unshift @rings, splice(@rings, $rlmain::data{'offset'});
    splice @rings, $rlmain::ringspermainpage;
  } else	{
    $rlmain::result = '<h4>';
    if ($totnum == 1)	{
      $rlmain::result .= gettext("1 ring");
    } elsif ($totnum == 2)	{
      $rlmain::result .= gettext("2 rings");
    } else	{
      $rlmain::result .= sprintf (gettext("%d rings"), $totnum);
    }
    $rlmain::result .= "</h4>\n";
  }

  my $hometext = gettext("Ring homepage");
  my $list = rlmain::filenamefix('list.pl');
  my $listtext = gettext("List");
  my $stats = '';
  for (@rings)	{
    my $cgiURL = $rlmain::cgiURL{$_} || $rlmain::cgiURL;
    entify( @{$rings{$_}}{ qw/title URL desc/ } );
    if ($rlmain::stats)	{
      my $sites;
      if ($rings{$_}{count} == 1)	{
        $sites = gettext("1 site");
      } elsif ($rings{$_}{count} == 2)	{
        $sites = gettext("2 sites");
      } else	{
        $sites = sprintf(gettext("%d sites"), $rings{$_}{count});
      }
      $stats = "| <a href=\"$cgiURL/" . filenamefix('stats.pl') . "?ringid=$_\">"
      . gettext("Stats") . "</a>\n<span class=\"small\">&#8226; $sites</span>";
    }
    if ($rings{$_}{created})	{
      $rings{$_}{created} = '<span class="small">&#8226; ' . gettext("Created")
      . " $rings{$_}{created}</span>";
    }

    $rlmain::result .= <<RING;

<p class="success" style="margin-bottom: 0">$rings{$_}{title}</p>
<p style="margin-top: 0.2em; margin-bottom: 0.3em">
<a href="$rings{$_}{URL}">$hometext</a>
| <a href="$cgiURL/$list?ringid=$_">$listtext</a>
$stats
$rings{$_}{created}</p>
<span class="list">
$rings{$_}{desc}
</span><br />
RING

  }
  $rlmain::result .= $links;
  $rlmain::pagemenu = menu();
  adminhtml();
}

sub inittests	{
  ## Used in next, prev, rand, list, next5, prev5, search, stats, goto and home
  ## (and also in skipnext and skipprev)

  my $ringexists;
  ringlist();
  if (!$rlmain::data{'ringid'})	{
    $rlmain::result = '<p class="error">' . gettext("Error! You must provide a ring ID.") . '</p>';
    adminhtml();
    rlmain::exit();
  } else	{
    for (@rlmain::rings)	{
      if ($rlmain::data{'ringid'} eq $_)	{
        $ringexists = 1;
        last;
      }
    }
  }
  if (!$ringexists)	{
    $rlmain::result = '<p class="error">' . sprintf (gettext("Error! Ring ID %s does not exist in %s."),
      "&quot;$rlmain::data{'ringid'}&quot;", $rlmain::title) . '</p>';
    adminhtml('Not Found');
    rlmain::exit();
  } else	{
    getringvalues();
    for (@rlmain::ringnames)	{
      entify(${ $rlmain::refs{$_} });
    }
    sitelist();
    if (!@rlmain::sites)	{
      $rlmain::result = '<p class="error">' . gettext("There are no sites in this ring.") . '</p>';
      mainhtml();
      rlmain::exit();
    }
  }
  statussplit();
  if (!@rlmain::activesites and !%rlmain::activesites)	{
    $rlmain::result = '<p class="error">' . gettext("There are no active sites in this ring.") . '</p>';
    mainhtml();
    rlmain::exit();
  }
}

sub statussplit	{
  open (SITES, "< $rlmain::datapath/$rlmain::data{'ringid'}/sites.db")
   || die "Can't open '$rlmain::data{'ringid'}/sites.db'\n$!";
  while (<SITES>)	{
    if (m/^(\w+)\tactive\t/)	{
      my $id = $1;
      if ($rlmain::action =~ /^(?:search|stats)/i)	{
        $rlmain::activesites{$id} = $_;
      } else	{
        push @rlmain::activesites, $_;
      }
    } else	{
      push @rlmain::inactivesites, $_;
    }
  }
  close (SITES);
}

sub search	{
  my $searchtext = gettext("Search for:");
  my $string = entify($rlmain::data{search});
  my $searchbutton = gettext("Search");

  $rlmain::result = <<FORM;
<form method="post" action="$rlmain::cgiURL/$rlmain::action?ringid=$rlmain::data{ringid}">
<span>$searchtext</span><br />
<input class="text" type="text" size="25" name="search" value="$string" />
<input type="hidden" name="ringid" value="$rlmain::data{ringid}" />
<input class="button" type="submit" name="submit" value="$searchbutton" />
</form>
FORM

  if ($rlmain::data{submit} and !$string)	{
    $rlmain::result .= '<p class="error">' . gettext("Enter a string to search for!") . '</p>';
  } elsif ($string)	{
    use locale;
    for my $record (values %rlmain::activesites)	{
      for my $searchfield ((split /\t/, $record)[0,2..5])	{
        if ($searchfield =~ /\Q$rlmain::data{search}/i)	{
          push @rlmain::activesites, $record;
          last;
        }
      }
    }
  }
  if (@rlmain::activesites)	{
    use locale;
    @rlmain::activesites = map $_->[0], sort {
      uc $a->[1] cmp uc $b->[1]
    } map [$_, (split /\t/)[2] =~ /^\W*(\w.+)/], @rlmain::activesites;
  } else	{
    $rlmain::result .= '<p class="success">' . sprintf (
     gettext("No sites were found containing: %s"), "<br />\n<span style=\"color: "
     . "$rlmain::coltxt\">&quot;<tt>$string</tt>&quot;</span>") . '</p>' if $string;
    mainhtml();
    rlmain::exit();
  }
}

sub ringstats	{
  unless ($rlmain::stats)	{
    $rlmain::result = '<p class="error">'
    . gettext("The statistics function has not\nbeen activated on this system.") . '</p>';
    mainhtml();
    rlmain::exit();
  }
  tie my %stats, 'SDBM_File', "$rlmain::datapath/$rlmain::data{'ringid'}/stats",
   O_RDONLY, $rlmain::filemode or die "Can't bind $rlmain::data{'ringid'}/stats\n$!";
  %rlmain::ringstats = %stats;
  untie %stats;
  unless (%rlmain::ringstats)	{
    $rlmain::result = '<p class="error">'
    . gettext("No summary statistics is available\nfor this ring yet.") . '</p>';
    mainhtml();
    rlmain::exit();
  }
}

sub list	{
  my $activesite = 0;
  my $inactivesite = 0;
  entify($rlmain::data{'siteid'});
  my $total = $rlmain::action =~ /^stats/i ? keys %rlmain::activesites
   : @rlmain::activesites;
  if ($rlmain::data{'offset'} || $rlmain::data{'offset'} eq '0')	{
    if ($rlmain::data{'offset'} =~ /\D/)	{
      unless ($rlmain::action =~ /^(?:search|stats)/i)	{
        push (@rlmain::error, '<p class="error">'
        . gettext("The &quot;offset&quot; argument shall consist of digits only.\nThe list below starts with a randomly chosen site.")
        . '</p>');
        $rlmain::data{'offset'} = int (rand $total);
      } else	{
        $rlmain::data{'offset'} = 0;
      }
    } elsif ($rlmain::data{'offset'} > $total - 1 || $rlmain::data{'offset'} < 0)	{
      unless ($rlmain::action =~ /^(?:search|stats)/i)	{
        push (@rlmain::error, '<p class="error">' . sprintf (
          gettext("Incorrect &quot;offset&quot; value. (Should have been a number between\n0 and %d.) The list below starts with a randomly chosen site."),
          $total - 1) . '</p>');
        $rlmain::data{'offset'} = int (rand $total);
      } else	{
        $rlmain::data{'offset'} = 0;
      }
    }
    createlist('noindex');
  }
  $rlmain::data{'offset'} = 0;
  if ($rlmain::action =~ /^(?:search|stats)/i)	{
    rlmain::addgenhits ($rlmain::data{'siteid'}) if $rlmain::activesites{$rlmain::data{'siteid'}};
    createlist();
  }
  for (@rlmain::activesites)	{
    if ($_ =~ /^\Q$rlmain::data{'siteid'}\E\t/)	{
      $activesite = 1;
      last;
    } else	{
      $rlmain::data{'offset'} ++;
    }
  }
  if (!$rlmain::data{'siteid'})	{
    if ($rlmain::action =~ /^list/i)	{
      $rlmain::data{'offset'} = int(rand @rlmain::activesites);
      createlist();
    } else	{
      $rlmain::result = '<p class="error">' . gettext("Error! You must provide a site ID.") . '</p>';
      mainhtml();
      rlmain::exit();
    }
  } elsif (!$activesite && $rlmain::action =~ /^(?:next5|prev5)/i)	{
    naverror();
  } elsif (!$activesite)	{
    for (@rlmain::inactivesites)	{
      if ($_ =~ /^\Q$rlmain::data{'siteid'}\E\t/)	{
        $inactivesite = 1;
        push (@rlmain::error, '<p class="error">' . sprintf (
          gettext("Site ID %s is not active. The list below\nstarts with another, randomly chosen, site."),
          "&quot;$rlmain::data{'siteid'}&quot;") . '</p>');
        $rlmain::data{'offset'} = int(rand @rlmain::activesites);
        createlist();
        last;
      }
    }
    if (!$inactivesite)	{
      push (@rlmain::error, '<p class="error">' . sprintf (
        gettext("Site ID %s does not exist in this ring.\nThe list below starts with a randomly chosen site."),
        "&quot;$rlmain::data{'siteid'}&quot;") . '</p>');
      $rlmain::data{'offset'} = int(rand @rlmain::activesites);
      createlist();
    }
  } else	{
    rlmain::addgenhits ($rlmain::data{'siteid'});
    createlist();
  }
}

sub createlist	{
  my $noindex = shift;
  my $links = my $search = '';
  my ($prevoffset, $nextoffset);
  $rlmain::sitesperlistpage = 5 if $rlmain::action =~ /^(?:next5|prev5)/i;
  my $prevtxt = sprintf (gettext("Previous %d sites"), $rlmain::sitesperlistpage);
  my $nexttxt = sprintf (gettext("Next %d sites"), $rlmain::sitesperlistpage);
  my $totnum = (@rlmain::activesites or (keys %rlmain::ringstats) - 2);
  if ($totnum > $rlmain::sitesperlistpage)	{
    $rlmain::result .= '<h4 style="margin-top: 0">'
    . sprintf (gettext("%d sites out of %d"), $rlmain::sitesperlistpage, $totnum)
    . "</h4>\n";
    $prevoffset = $rlmain::data{'offset'} - $rlmain::sitesperlistpage
    + ($rlmain::sitesperlistpage > $rlmain::data{'offset'} ? $totnum : 0);
    $nextoffset = $rlmain::data{'offset'} + $rlmain::sitesperlistpage
    - ($rlmain::data{'offset'} + $rlmain::sitesperlistpage > $totnum - 1 ? $totnum : 0);
    if ($rlmain::action =~ /^next5/i)	{
      $rlmain::data{'offset'} = $rlmain::data{'offset'} + 1 - ($rlmain::data{'offset'}
      + 1 > $totnum - 1 ? $totnum : 0);
    } elsif ($rlmain::action =~ /^prev5/i)	{
      $rlmain::data{'offset'} = $prevoffset;
    }
    if ($rlmain::action =~ /^search/i)	{
      $search = $rlmain::data{search};
      # escape the string (code picked from URI::Escape)
      $search =~ s/([^A-Za-z0-9\-_.!~*'()])/sprintf "%%%02X", ord $1/eg;
      $search = ";search=$search";
    }

    $links = qq~
<p class="center">
[ <a href="$rlmain::cgiURL/$rlmain::action?ringid=$rlmain::ringid;offset=$prevoffset$search">
$prevtxt</a> |
<a href="$rlmain::cgiURL/$rlmain::action?ringid=$rlmain::ringid;offset=$nextoffset$search">
$nexttxt</a> ]
</p>~;

  } else	{
    $rlmain::result .= '<h4 style="margin-top: 0">';
    if ($totnum == 1)	{
      $rlmain::result .= gettext("1 site");
    } elsif ($totnum == 2)	{
      $rlmain::result .= gettext("2 sites");
    } else	{
      $rlmain::result .= sprintf (gettext("%d sites"), $totnum);
    }
    $rlmain::result .= "</h4>\n";
  }
  $rlmain::result .= "@rlmain::error\n";
  unless ($rlmain::action =~ /^stats/i)	{
    ordlist();
  } else	{
    statslist();
  }
  $rlmain::result .= $links;
  mainhtml($noindex);
  rlmain::exit();
}

sub ordlist	{
  my ($goto, @sitevalues);
  $rlmain::result .= "<dl>\n";
  unshift @rlmain::activesites, splice(@rlmain::activesites, $rlmain::data{'offset'});
  if (@rlmain::activesites > $rlmain::sitesperlistpage)	{
    splice @rlmain::activesites, $rlmain::sitesperlistpage;
  }
  $goto = rlmain::filenamefix('goto.pl');
  entify($rlmain::data{search});
  for (@rlmain::activesites)	{
    @sitevalues = split (/\t/, $_);
    for (@rlmain::sitenames)	{
      ${$rlmain::refs{$_}} = shift @sitevalues;
      entify(${ $rlmain::refs{$_} });
    }
    if ($rlmain::action =~ /^search/i)	{
      use locale;
      for (qw/sitetitle sitedesc/)	{
        ${$rlmain::refs{$_}} =~ s/(\Q$rlmain::data{search}\E)/<b>$1<\/b>/gi;
      }
    }

    $rlmain::result .= qq~
<dt><a href="$rlmain::cgiURL/$goto?ringid=$rlmain::ringid;siteid=$rlmain::siteid" target="_top">
$rlmain::sitetitle</a></dt>
<dd>$rlmain::sitedesc</dd>
~;

  }
  $rlmain::result .= "\n</dl>";
}

sub statslist	{
  my ($gen, $rec, @sites, $goto, @sitevalues, $sitelink);
  my $intro = gettext("Hits per site per day during\na period of up to 30 days.");
  my $updated = gettext("Last updated:") . " $rlmain::ringstats{'last updated'}";
  my $gen_text = gettext("Generated");
  my $rec_text = gettext("Received");
  my $average = gettext ("Average member site");

  ($gen, $rec) = split /\|/, $rlmain::ringstats{'ring average'};
  $gen = $gen >= 0 ? sprintf "%.1f", $gen : '-';
  $rec = $rec >= 0 ? sprintf "%.1f", $rec : '-';
  delete @rlmain::ringstats{'last updated', 'ring average'};

  $rlmain::result .= <<TOP;
<p>$intro<br />
<span class="small">$updated</span></p>
<table width="100%">
<tr>
<td></td>
<td class="right"><span><b>$gen_text</b></span></td>
<td class="right"><span><b>$rec_text</b></span></td>
</tr>
<tr><td colspan="3"><hr /></td></tr>
<tr>
<td><span>$average</span></td>
<td class="right"><span>$gen</span></td>
<td class="right"><span>$rec</span></td>
</tr>
<tr><td colspan="3"><hr /></td></tr>

TOP

  @sites = map $_->[0], sort {
    $b->[1] <=> $a->[1]  # primary sort key: generated hits
             or
    $a->[0] cmp $b->[0]  # secondary sort key: site IDs
  } map [$_, $rlmain::ringstats{$_} =~ /^([\d.-]+)/], keys %rlmain::ringstats;
  unshift @sites, splice(@sites, $rlmain::data{'offset'});
  splice @sites, $rlmain::sitesperlistpage if @sites > $rlmain::sitesperlistpage;
  $goto = filenamefix('goto.pl');
  for (@sites)	{
    ($gen, $rec) = split /\|/, $rlmain::ringstats{$_};
    $gen = $gen >= 0 ? sprintf "%.1f", $gen : '-';
    $rec = $rec >= 0 ? sprintf "%.1f", $rec : '-';
    if ($rlmain::activesites{$_})	{
      @sitevalues = split /\t/, $rlmain::activesites{$_};
      for (@rlmain::sitenames)	{
        ${$rlmain::refs{$_}} = shift @sitevalues;
        entify(${ $rlmain::refs{$_} });
      }
      $sitelink = "<a href=\"$rlmain::cgiURL/$goto?ringid=$rlmain::ringid;siteid=$rlmain::siteid\" target=\"_top\">"
      . "\n$rlmain::sitetitle</a>";
    } else	{
      $sitelink = gettext("Inactive or removed site");
    }

    $rlmain::result .= <<SITE;
<tr>
<td><span>$sitelink</span></td>
<td class="right"><span>$gen</span></td>
<td class="right"><span>$rec</span></td>
</tr>

SITE

  }
  $rlmain::result .= '<tr><td colspan="3"><hr /></td></tr>';
  $rlmain::result .= "\n</table>";
}

sub naverror	{
  my $list;
  entify($rlmain::data{'siteid'});
  my $inactivesite = 0;
  $list = rlmain::filenamefix('list.pl');
  for (@rlmain::inactivesites)	{
    if ($_ =~ /^\Q$rlmain::data{'siteid'}\E\t/)	{
      $inactivesite = 1;
      $rlmain::result = '<p class="error">'
      . sprintf (gettext("Site ID %s is not active."), "&quot;$rlmain::data{'siteid'}&quot;")
      . "</p>\n<p><a href=\"$rlmain::cgiURL/$list?ringid=$rlmain::ringid\">"
      . sprintf (gettext("List active sites in %s"), $rlmain::ringtitle) . '</a></p>';
      last;
    }
  }
  if (!$inactivesite)	{
    $rlmain::result = '<p class="error">'
    . sprintf (gettext("Site ID %s does not exist in this ring."), "&quot;$rlmain::data{'siteid'}&quot;")
    . "</p>\n<p><a href=\"$rlmain::cgiURL/$list?ringid=$rlmain::ringid\">"
    . sprintf (gettext("List sites in %s"), $rlmain::ringtitle) . '</a></p>';
  }
  mainhtml();
  rlmain::exit();
}

sub removedirectory	{
  my $rm = sub {
    my ($files, $dir) = @_;
    for ( @$files ) {
      $_ = $1 if /^([-\w.]+)$/;
      unlink "$dir/$_" or die "Can't remove $dir/$_\n$!";
    }
    rmdir $dir or die "Can't remove $dir\n$!";
  };
  my $dir = shift;
  opendir DIR, $dir or die "Can't open $dir\n$!";
  my $dirbegin = telldir DIR;
  if ( my ($fp) = grep { -d "$dir/$_" and /^_vti/ } readdir DIR ) {
    $fp = secureword($fp);
    opendir FPDIR, "$dir/$fp" or die "Can't open $dir/$fp\n$!";
    my @files = grep { -f "$dir/$fp/$_" } readdir FPDIR;
    closedir FPDIR;
    $rm->( \@files, "$dir/$fp" );
  }
  seekdir DIR, $dirbegin;
  my @files = grep { -f "$dir/$_" } readdir DIR;
  closedir DIR;
  $rm->( \@files, $dir );
}

sub sitecountupdate	{
  my $ringid = shift;
  my $count = 0;
  if (open SITES, "< $rlmain::datapath/$ringid/sites.db")	{
    while (<SITES>) { $count++ if /^\w+\tactive/ }
    close SITES;
  }
  open COUNT, "> $rlmain::sitecountpath/$ringid.js"
   or die "Can't open $rlmain::sitecountpath/$ringid.js\n$!";
  print COUNT "document.write ('$count')";
  close COUNT;
}

sub addgenhits	{
  my $site = secureword ($rlmain::ringid) . '/' . secureword (shift);
  addhits ("$rlmain::datapath/$site/genhits") if $rlmain::stats;
}

sub addrechits	{
  my $site = secureword ($rlmain::ringid) . '/' . secureword (shift);
  addhits ("$rlmain::datapath/$site/rechits") if $rlmain::stats;
}

sub addhits	{
  (my $dbname = $_[0]) =~ s!.*/!!;
  open DBLOCK, "> $_[0].lockfile" or die "Can't open $dbname.lockfile\n$!";
  flock DBLOCK, LOCK_EX or die $!;
  tie (my %hits, 'SDBM_File', $_[0], O_RDWR, $rlmain::filemode) or die "Can't bind $dbname\n$!";
  my $today = timestamp('date');
  dbupdate (\%hits) unless $hits{$today};
  $hits{$today}++;
  untie %hits or die $!;
  close DBLOCK or die $!;
}

sub dbupdate	{
  my $hitsref = shift;
  my $startdate = (sort keys %$hitsref)[0];
  if ($startdate and $startdate !~ /^20/ and $rlmain::action !~ /^admin/i)	{
    die "There is a need to update the statistics because of a new date format.\n"
    . "Run the \"Reset stats\" routine from admin.pl.\n\n" }
  my %dates = ();
  my $done;
  for (0..31) {
    my $date = timestamp('date', $_);
    $hitsref->{$date} ||= 0;
    $dates{$date} = 1;
    if (!$startdate || $date eq $startdate)	{
      $done = 1;
      last;
    }
  }
  unless ($done)	{
    for (keys %$hitsref)	{
      delete $hitsref->{$_} unless $dates{$_};
    }
  }
}

sub getstats	{
  my @sitestats = ();
  for ("$_[0]/genhits", "$_[0]/rechits")	{
    my (%hits, @keys, $numdays, $count);
    (my $dbname = $_) =~ s!.*/!!;
    open DBLOCK, "> $_.lockfile" or die "Can't open $dbname.lockfile\n$!";
    flock DBLOCK, LOCK_EX or die $!;
    tie (%hits, 'SDBM_File', $_, O_RDWR, $rlmain::filemode) or die "Can't bind $dbname\n$!";
    dbupdate (\%hits) unless $hits{timestamp('date')};
    @keys = sort keys %hits;
    $numdays = @keys;
    shift @keys if $numdays > 1;
    for (@keys) { $count += $hits{$_} }
    untie %hits or die $!;
    close DBLOCK or die $!;
    push (@sitestats, $numdays, $count);
  }
  return @sitestats;
}

sub updateringstats	{
  my $ringprint = shift;
  my (@rings, $gen, $rec);
  my ($sec, $min, $hour) = gmtime $rlmain::time;
  my $daysadjust = 2 - ($hour*60*60 + $min*60 + $sec) / (24*60*60);
  my $timestamp = timestamp();
  opendir DIR, $rlmain::datapath or die "Can't open data directory\n$!";
  @rings = grep { !/^\.|^_vti/ && -d "$rlmain::datapath/$_" } readdir DIR;
  closedir DIR;
  require Parallel::ForkManager;
  my $max = $rlmain::max_processes > 10 ? 10 : $rlmain::max_processes;
  my $pm = new Parallel::ForkManager($max);
  for my $ring (@rings)	{
    my $pid = $pm -> start and next;
    my $ringdir = "$rlmain::datapath/" . secureword($ring);
    my $gentotal = my $gencount = my $rectotal = my $reccount = 0;
    my @sites = ();
    if (open SITES, "< $ringdir/sites.db")	{
      while (<SITES>) { push @sites, $1 if /^(\w+)\tactive\t/ }
      close SITES;
    }
    tie my %stats, 'SDBM_File', "$ringdir/stats", O_RDWR, $rlmain::filemode
     or die "Can't bind $ring/stats\n$!";
    %stats = ();
    for (@sites)	{
      my ($gendays, $genhits, $recdays, $rechits) = getstats ("$ringdir/".secureword($_));
      if ($gendays >= 3)	{
        $gen = $genhits / ($gendays - $daysadjust);
        $gentotal += $gen;
        $gencount ++;
      } else	{
        $gen = -1;
      }
      if ($recdays >= 3)	{
        $rec = $rechits / ($recdays - $daysadjust);
        $rectotal += $rec;
        $reccount ++;
      } else	{
        $rec = -1;
      }
      $stats{$_} = "$gen|$rec";
    }
    if ($gencount or $reccount)	{
      $gen = $gencount ? $gentotal / $gencount : -1;
      $rec = $reccount ? $rectotal / $reccount : -1;
      $stats{'ring average'} = "$gen|$rec";
      $stats{'last updated'} = $timestamp;
    } else	{
      %stats = ();
    }
    untie %stats;
    print "Ring ID $ring done.\n" if $ringprint;
    $pm -> finish;
  }
  $pm -> wait_all_children;
}

sub statsupdatetime	{
  # Next day at 3:47 a.m. local time
  require Time::Local;
  return 100000 + Time::Local::timelocal(0,0,0,(localtime $rlmain::time)[3..5]);
}

sub setlang	{
  my $lang = shift;
  Locale::PGetText::setLanguage($lang);
  $rlmain::currentlang = $lang;
  $rlmain::charset = $rlmain::charset{$lang} || 'ISO-8859-1';
}

sub charset	{
  %rlmain::charset = (
    ja => 'Shift_JIS',
    ru => 'Windows-1251',
    tr => 'ISO-8859-9',
    zh => 'BIG5',
  );
}

sub langlist	{
  # ISO 639 language codes
  my %langlist = (
    da => gettext("Danish"),
    de => gettext("German"),
    en => gettext("English"),
    es => gettext("Spanish"),
    fr => gettext("French"),
    it => gettext("Italian"),
    ja => gettext("Japanese"),
    nl => gettext("Dutch"),
    ru => gettext("Russian"),
    sv => gettext("Swedish"),
    tr => gettext("Turkish"),
    zh => gettext("Chinese"),
  );

  for (keys %rlmain::lang)	{
    $rlmain::lang{$_} = $langlist{$_} if $langlist{$_};
  }
}

sub securepath	{
  my ($sep, $begin);
  my @paths = ();
  if ($^O eq 'MSWin32')	{
    $sep = ';';
    $begin = qr(\w:|\\|/);
  } else	{
    $sep = ':';
    $begin = '/';
  }
  for (split $sep, $ENV{PATH})	{
    push @paths, $1 if m!^($begin[\w\-. \\/~]*)$!;
  }
  delete @ENV{qw(IFS CDPATH ENV BASH_ENV)};
  return join $sep, @paths;
}

sub secureword	{
  my $string = shift;
  if ($string =~ /^(\w+)$/)	{
    $string = $1;
  } else	{
    my ($file, $line) = (caller)[1..2];
    die "Insecure variable at $file line $line.\n";
  }
  $string
}

sub emailsyntax	{
  return 1 unless my ($localpart, $domain) = shift =~ /^(.+)@(.+)/;
  my $atom = '[^[:cntrl:] "(),.:;<>@\[\\\\\]]+';
  my $qstring = '"(?:\\\\.|[^"\\\\\s]|[ \t])*"';
  my $word = qr($atom|$qstring);
  return 1 unless $localpart =~ /^$word(?:\.$word)*$/;
  $domain =~ /^$atom(?:\.$atom)+$/ ? 0 : 1;
}

1;

