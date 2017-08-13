#!/usr/bin/perl -T
use lib 'lib';

############################> Ringlink <############################
#                                                                  #
#  $Id: main.pl,v 1.7 2005/02/21 17:11:41 gunnarh Exp $            #
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

rlmain::execstart;
rlmain::ringlistpage;

