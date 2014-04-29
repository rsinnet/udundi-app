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

class FacebookCacheInterface():
    """This class provides an interface for accessing the local Facebook cache.
    """

    def __init__(self):
        """Obtains a connection to the database and stores a reference to the
        cursor.
        """
        self.con = None
        try:
            self.con = MySQLdb.connect('localhost', 'rsinnet_dbuser',
                                       "D5J5{w6!{#%vpxw", 'rsinnet_udundi')

            self.cur = self.con.cursor()

        except MySQLdb.Error as e:
            print e
            sys.exit(1)

        self.query('SET time_zone="+00:00"')

    def __del__(self):
        """Closes the connection to the database.
        """
        if self.con:
            self.con.close()

    def query(self, sql_statement, args=None):
        """Performs the specified query on the database. This function
        implements protections against SQL injection attacks.
        """
        try:
            if args:
                self.cur.execute(sql_statement, args)
            else:
                self.cur.execute(sql_statement)
        except MySQLdb.Error as e:
            print e
            sys.exit(1)


class UdundiUser():
    """This class provides an interface between the Facebook Graph and the local
    cache manager for a single Udundi user.
    """

    def __init__(self, userid, access_token):
        """The default constructor.

        Args:
           userid (int): The Udundi User ID.
           access_token (str): A valid access token for the user.
        """

        self.userid = userid
        self.user_access_token = access_token
        self.page_access_token = ''

        # The current page id.
        self.pageid = ''

    def set_pageid(self, pageid):
        """Sets the ID of the current page being acted on.
        .. warning:: This method will be deprecated so batch calls can be used.
        """
        self.pageid = pageid

    def backfill_cache(self, insight, period='lifetime'):
        """Retrieves data from the Facebook graph to backfill the local cache.
        .. warning:: This will be generalized to make batch calls.
        """
        # 0. Which insight are we getting?
        edge = insight

        # 1. Get shit from FB.
        graph = G(self.user_access_token)

        try:
            accounts = graph.get('me/accounts')
        except GraphAPIError as e:
            if str(e).startswith('Error validating access token'):
                raise e
                #return False
            else:
                raise e

        for d in accounts:
            if d['id'] != self.pageid:
                continue
            graph.set_access_token(d['access_token'])

        try:
            data = graph.get(self.pageid + '/insights/' + edge, \
                                 {'period': period})
        except GraphAPIError as e:
            if str(e).startswith('Error validating access token'):
                raise e
            else:
                raise e

        new_data = True

        while graph.paginated() and new_data:
            new_data = False;
            #print graph.previous()
            #print graph.previous()[0]['values']
            #print

            # TODO end condition

        # TODO error handling.

        # 2. Put it in the database
        insight_subsql = '(SELECT id FROM facebook_insights_names ' + \
            'WHERE insight_name="%s")'
        #insight_subsql = '(SELECT id FROM facebook_insights_names ' + \
        #   'WHERE insight_name="{0}")'.format(insight)
        #sql_command = 'REPLACE INTO facebook_insights_basic ' + \
        #    '(userid, insightid, period, end_time, value) VALUES ' + \
        #    ', '.join(['({0}, {1}, "{2}", "{3}", {4})'.format(\
        #            self.userid, insight_subsql, data[0]['period'], d['end_time'], d['value']) \
        #                   for d in data[0]['values']])
        sql_statement = 'REPLACE INTO facebook_insights_basic ' + \
            '(userid, insightid, period, end_time, value) VALUES ' + \
            ', '.join(['({0}, {1}, "%s", "%s", %s)'. \
                           format(self.userid, insight_subsql) for d in data[0]['values']])
        sql_args = tuple(itertools.chain.from_iterable(\
                [[insight,
                  data[0]['period'],
                  d['end_time'][:-5],
                  d['value']] for d in data[0]['values']]))

        fci = FacebookCacheInterface()
        fci.query(sql_statement % sql_args)

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
