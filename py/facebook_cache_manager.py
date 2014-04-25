#!/usr/bin/python

import facebook
from facebook_python_extension import GraphApiExtension as G

import datetime, iso8601, time
import json

class UdundiUser():
    def __init__(self, userid, access_token):
        self.userid = userid
        self.user_access_token = access_token
        self.page_access_token = ''

        # The current page id.
        self.pageid = ''

    def set_pageid(self, pageid):
        self.pageid = pageid

    def backfill_cache(self, insight, period='lifetime'):
        # 0. What are we getting
        edge = insight

        # 1. Get shit from FB
        graph = G(self.user_access_token)
        accounts = graph.get('me/accounts')
        for d in accounts:
            if d['id'] != self.pageid:
                continue
            graph.set_access_token(d['access_token'])

        data = graph.get(self.pageid + '/insights/' + edge, {'period': period})
        print data
        print
        new_data = True

        while graph.paginated() and new_data:
            new_data = False;
            print graph.previous()[0]['values']
            print

        # 2. Put it in the database

userid = 0;
access_token = 'CAADuUkhGbnIBALROZAlwxYZBVk0Eqc755tfF2BExlyrdISrNH4UfX0iKC7ivjm5mZCrQK8itcr8EKZCzstpcfymxGOu8N9ltTEBTmmfuhpcp86164ZBPzVFzHNwRmfBKWBBAEZBN89Jg3h8djqYnZCfJUNTfQVyIiM6iylFEs0zsHNj9KFr6IywWcSZAZBPsSRbsZD'
pageid = '116485406912';

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
