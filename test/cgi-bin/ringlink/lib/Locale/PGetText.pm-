# (C) 1998 Mike Shoyher
#
# Modifications for Ringlink by Gunnar Hjalmarsson:
# Added option to set the database library manually in rlconfig.pm
#
# Ringlink CVS ID:
# $Id: PGetText.pm,v 1.14 2003/03/26 00:30:52 gunnarh Exp $
#

package Locale::PGetText;

$Locale::PGetText::VERSION = '2.0';     # Applicable Ringlink version
#$VERSION = "0.16" ;


=head1 NAME

PGetText - pure perl i18n routines

=head1 SYNOPSIS

 
  use Locale::PGetText;

  Locale::PGetText::setLocaleDir('/usr/local/perl/locale');
  Locale::PGetText::setLanguage('ru-koi8r');
  
  print gettext("Welcome!"), "\n";

=head1 DESCRIPTION

PGetText provides the same functionality as GNU gettext does, but it is written in pure perl and doesn't require any system locale stuff.

I<setLocaleDir()> sets directory where messages database is stored (there are no default and no domains).

I<setLanguage()> switches languages. 

I<gettext()> retrieves message in local language corresponding to given message. 

=head1 SEE ALSO

MsgFormat(1)

=head1 AUTHOR

Mike Shoyher <msh@corbina.net>, <msh@apache.lexa.ru>

=cut

# Code

use Exporter;
use Fcntl;
use strict;

use vars  qw(%messages $locale_dir $module $dbm_kosher);

use vars qw($VERSION @ISA @EXPORT @EXPORT_OK %EXPORT_TAGS);
@ISA = qw(Exporter);
@EXPORT = qw(gettext);


sub dbmselect	{
  $dbm_kosher=0;
  if ($rlmain::DBM_File)	{
      if (eval "require $rlmain::DBM_File")	{
          $module=$rlmain::DBM_File;
          $dbm_kosher=1;
      } else	{
          &AnyDBM;
      }
  } else	{
      &AnyDBM;
  }
  die "No suitable DBM library" unless ($dbm_kosher);
}

sub AnyDBM	{
  # Here goes some AnyDBM-like magic
  my @modules=qw(GDBM_File SDBM_File DB_File NDBM_File ODBM_File); 
  for my $mod (@modules){
      $module=$mod;
      if (eval "require $mod") {
          $dbm_kosher=1;
          last;
      }
  }
}


END {
untie(%messages);
}



return 1;

sub setLocaleDir($)
{
$locale_dir=shift;
}

sub setLanguage($)
{
my $lang=shift;
my $path="$locale_dir/$lang";
die "Call setLocaleDir() first" unless ($locale_dir);
tie(%messages, $module, $path, O_RDONLY,0644) || die ("Cannot open language file $path");
}

sub gettext($)
{
my $s=shift;
my $msg=$messages{$s};
return $msg if ($msg);
return $s;
}


