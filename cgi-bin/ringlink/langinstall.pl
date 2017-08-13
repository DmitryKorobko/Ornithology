#!/usr/bin/perl -T
use lib 'lib';

############################> Ringlink <############################
#                                                                  #
#  $Id: langinstall.pl,v 1.39 2005/02/21 17:11:41 gunnarh Exp $    #
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

use rlconfig;
rlconfig::systemvar;

use Locale::PGetText 2.0;
Locale::PGetText::dbmselect;

# Unix file permissions controlled via the variables in rlconfig.pm
umask 0;

# Path to the language directory
my $langdir = ($0 =~ /[\\\/]/ ? $0 : $ENV{'SCRIPT_FILENAME'});
if ($langdir)	{
  $langdir =~ s/\\/\//g;
  $langdir = $1.'lang' if $langdir =~ m!^([\w\-./: ]+/)!;
} else	{
  $langdir = 'lang';
}

# Check that the 'mo' directory exists and is writable
MOTEST:	{
  last MOTEST if -d "$langdir/mo" && -r _ && -w _ && -x _;
  if (-d "$langdir/mo")	{
    die "You need to chmod the $langdir/mo directory 777\n\n";
  } elsif (-d $langdir)	{
    last MOTEST if eval mkdir "$langdir/mo", $rlmain::dirmode;
    die "You need to create the $langdir/mo directory\n\n";
  } else	{
    die "$langdir does not exist\n\n";
  }
}

# Create list of PO files
opendir (DIR, $langdir) || die "Can't open $langdir\n$!";
my @files = grep { /^[a-z]{2}\.po$/ } readdir(DIR);
closedir DIR;

# Remove old databases
opendir (DIR, "$langdir/mo") || die "Can't open $langdir/mo\n$!";
my @mofiles = grep { -f "$langdir/mo/$_" } readdir(DIR);
closedir DIR;
for (@mofiles)	{
  if (/^([\w.]+)$/)	{
    unlink "$langdir/mo/$1" || die "Can't remove $langdir/mo/$1\n$!";
  } else	{
    die 'Untainting failed at ' . __FILE__ . ' line ' . (__LINE__ -3) . ".\n";
  }
}

# Create new databases
my $path;
my @error = ();
for (@files)	{
  (my $lang = $_) =~ s/\.po$//;
  $path = "$langdir/mo/$lang";
  open (POFILE, "< $langdir/$_") || die "Can't open $langdir/$_\n$!";
  &MsgFormat;
  close POFILE;
}

# Print result
print "Content-type: text/html\n\n", "<pre>\n";
if (@error)	{
  print join ("\n", @error) . "\n</pre>";
} elsif (@files)	{
  print "Language databases were created from:\n" . join ("\n", @files) . "\n</pre>";
} else	{
  print "Can't find any ??.po files in $langdir\n</pre>";
}


sub MsgFormat	{
  # This subroutine is an extract from the file MsgFormat, included
  # in the CPAN module Locale::PGetText, (C) 1998 Mike Shoyher

  my $s = my $msgid = '';
  dbmopen(my %msg, $path, $rlmain::filemode) || push (@error, "Cannot create database $path");

  for (<POFILE>){
    if (/^msgid\s+\"(.*)\"\s*$/){
        if ($msgid) {
            $msgid=~s/\\n/\n/g;
            $s=~s/\\n/\n/g;
            $s=~s/\\"/"/g;
            $msg{$msgid}=$s;
        }
        $s=$1
    }
    if (/^\"(.+)\"\s*$/){
        $s.=$1
    }
    if (/^msgstr\s+\"(.*)\"\s*$/){
        $msgid=$s;
        $s=$1
    }
  }

  $msgid=~s/\\n/\n/g;
  $s=~s/\\n/\n/g;
  $s=~s/\\"/"/g;
  $msg{$msgid}=$s;

  dbmclose(%msg);
}

