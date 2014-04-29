#!/usr/bin/python

"""
.. module:: facebook_cache_service
   :platform Unix
   :synopsis: Provides a service for accessing the facebook cache.

.. moduleauthor:: R. W. Sinnet (ryan@udundi.com)

"""

import facebook
from facebook_python_extension import GraphApiExtension as G
from facebook_cache_interface import FacebookCacheInterface as FCIface, UdundiUser as UU

import datetime, iso8601, time

import json
import cgi, cgitb
cgitb.enable()

data = cgi.FieldStorage()

access_token = data.getvalue('user_access_token')
user_id = data.getvalue('user_id');
page_id = data.getvalue('page_id');

edge = data.getvalue('edge');
period = data.getvalue('period');

[since until] = map(lambda x: datetime.datetime.strptime(data.getvalue(x), '%m/%d/%Y').strftime('%Y%m%d'),
                    ['since', 'until'])

# Update the access token.
u_user = UU(user_id, access_token)

# Expedite backfilling for the user.

since = '2014-04-21'
until = '2014-04-27'

sql_statement = 'SELECT end_time, value FROM facebook_insights_basic AS fib ' + \
    'WHERE fib.period="' + period + '" ' + \
    'AND fib.end_time>="' + since + '" ' + \
    'AND fib.end_time<="' + until + '" ' + \
    'AND fib.insightid=' + \
    '(SELECT id FROM facebook_insights_names AS fin WHERE fin.insight_name="' + edge + '")'

data = {'values': []}

fci = FCIface()
fci.query(sql_statement)
results = fci.cursor().fetchall()

for row in results:
    datum = {
        'end_time' : row[0].isoformat(),
        'value': row[1]}
    data['values'] += [datum]

# Print out the content of the message.
print 'Content-Type: text/json'
print

print json.dumps(data)
