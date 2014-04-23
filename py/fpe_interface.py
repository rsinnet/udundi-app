#!/usr/bin/python

import facebook
from facebook_python_extension import GraphApiExtension as G

import datetime, iso8601, time

import json
import cgi, cgitb
cgitb.enable()

data = cgi.FieldStorage()

access_token = data.getvalue('user_access_token')
page_id = data.getvalue('page_id');

edge = data.getvalue('edge');
period = data.getvalue('period');
since = datetime.datetime.strptime(data.getvalue('since'), '%Y-%m-%d')
until = datetime.datetime.strptime(data.getvalue('until'), '%Y-%m-%d')

graph = G(access_token)

accounts = graph.get('me/accounts')
for d in accounts:
    if d['id'] != page_id:
        continue
    graph.set_access_token(d['access_token'])

data = graph.get(page_id + '/insights/' + edge, {'period': period})[0]
new_data = True

while graph.paginated() and new_data:
    new_data = False;
    current_data = graph.previous()[0]['values']
    for j in range(len(current_data)):
        item_date = iso8601.parse_date(current_data[j]['end_time']).replace(tzinfo=None)
        if item_date > since:
            data['values'] += [current_data[j]]
            new_data = True
        elif item_date > until:
            new_data = True

# Print out the content of the message.
print 'Content-Type: text/json'
print

print json.dumps(data)


