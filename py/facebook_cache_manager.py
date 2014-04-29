#!/usr/bin/python

"""
.. module:: facebook_cache_manager
   :platform Unix
   :synopsis: Handles caching for user Facebook data.

.. moduleauthor:: R. W. Sinnet (ryan@udundi.com)

"""

import facebook
from facebook_python_extension import GraphApiExtension as G, GraphAPIError

import sys
import datetime, iso8601, time, calendar
import MySQLdb
import itertools

from facebook_cache_interface import UdundiUser

userid = 1;
access_token = 'CAADuUkhGbnIBAEkdOCOsNJ5yFiRhDgbZCYCYAPa2uhTiujZAH0cgg23mZAZBmcB6WvibvuvN3CFQYesGhyaKqq5tgDwSsS6bNNgjBYvpwhR1QEnYiJJ3d31xVSGgAMXNbfSKpGBlvXMFCAOpMkgUARPVXuBe1sFqPWfezIinZCEPiITHdEXF5J0UWvg3WEnkZD'
access_token = 'CAADuUkhGbnIBAGCzq0LzVqC7UESZC9nYfSnZBg8ygm9Pg5E0vLDQCNJ0uO5mP7n5djpIuTPAZC3wyHg4zAucsVOChbI3Ma5ArfewMpbBbcyZAZAdo970ZC3Derm6yDZAywtCE3ZCMguPaGf2ol2NmDk0XGdQJe9FTVx4gLBqDSenQ5YEcwF0gUrHmtj4by96DtYZD'
pageid = '116485406912'

udundi_user = UdundiUser(userid, access_token)
udundi_user.set_pageid(pageid);
udundi_user.backfill_cache('page_fans')

'''
def process_item(datum):
    item_date = iso8601.parse_date(datum['end_time']).replace(tzinfo=None);
    if item_date >= since and item_date <= until:
        data['values'] += [datum]
        new_data = True;
    elif item_date >= until:
        new_data = True
    else:
        new_data = False

    return new_data
'''
