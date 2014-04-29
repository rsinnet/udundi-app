#!/usr/bin/python

"""
.. module:: facebook_cache_interface
   :platform Unix
   :synopsis: Handles caching for user Facebook data.

.. moduleauthor:: R. W. Sinnet (ryan@udundi.com)

Whenever a user requests data, the user's access token is added to the database. Next time the supervisor runs, it will look for new tokens and use them to update user data.

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

    def cursor(self):
        return self.cur;


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

        self.update_access_token()

    def update_access_token(self):
        sql_statement = 'REPLACE INTO facebook_access_tokens ' + \
            '(userid, access_token) VALUES ({0}, "{1}")'.format(\
            self.userid, access_token)

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
        cur = fci.cursor()
