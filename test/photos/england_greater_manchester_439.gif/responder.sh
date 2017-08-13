#!/bin/sh
###
#
# Copyright (c) Ensim Corporation 2000, 2001   All Rights Reserved.
#
# This software is furnished under a license and may be used and copied
# only  in  accordance  with  the  terms  of such  license and with the
# inclusion of the above copyright notice. This software or any other
# copies thereof may not be provided or otherwise made available to any
# other person. No title to and ownership of the software is hereby
# transferred.
#
# The information in this software is subject to change without notice
# and  should  not be  construed  as  a commitment by Ensim Corporation.
# Ensim assumes no responsibility for the use or  reliability  of its
# software on equipment which is not supplied by Ensim.
# --------------------------------------------------------------------------
# $Id: responder.sh,v 1.2 2003/02/11 04:10:26 naris Exp $
# $Name:  $
# --------------------------------------------------------------------------

PATH=/usr/bin:/usr/local/bin:$PATH
RUNAPP=/usr/lib/opcenter/sendmail/responder.pyc

# Test if the file exists
# before trying to load it
if [ -f /lib/virtualname.so ] ; then
   export LD_PRELOAD=/lib/virtualname.so
fi

if [ -f /usr/bin/ensim-python ]; then
    PYTHONBIN=/usr/bin/ensim-python
else
    PYTHONBIN=/usr/bin/python2
fi

${PYTHONBIN} ${RUNAPP} $@ > /dev/null 2>&1

exit $?
