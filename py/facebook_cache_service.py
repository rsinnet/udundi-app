#!/usr/bin/python

import facebook
from facebook_python_extension import GraphApiExtension as G
from facebook_cache_interface import FacebookCacheInterface as FCIface

import datetime, iso8601, time

import json
#import cgi, cgitb
#cgitb.enable()

#data = cgi.FieldStorage()

#page_id = data.getvalue('page_id');
page_id = ''

#edge = data.getvalue('edge');
#period = data.getvalue('period');

edge = 'page_fans'
period = 'lifetime'

#since = datetime.datetime.strptime(data.getvalue('since'), '%m/%d/%Y')
#until = datetime.datetime.strptime(data.getvalue('until'), '%m/%d/%Y')

since = '2014-04-21'
until = '2014-04-27'

if edge == 'page_fans':
    sql_statement = 'SELECT end_time, value FROM facebook_insights_basic AS fib ' + \
        'WHERE fib.period="' + period + '" ' + \
        'AND fib.end_time>="' + since + '" ' + \
        'AND fib.end_time<="' + until + '" ' + \
        'AND fib.insightid=' + \
        '(SELECT id FROM facebook_insights_names AS fin WHERE fin.insight_name="' + edge + '")'

data = {
    'values': []
}

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
