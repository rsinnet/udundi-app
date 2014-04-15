#!/usr/bin/python

import argparse
import facebook
from facebook_python_extension import GraphApiExtension as G

import json
import cgi

data = cgi.FieldStorage()

access_token = data.getvalue('user_access_token')
edge = data.getvalue('edge');

graph = G(access_token)

accounts = graph.get('me/accounts')
for d in accounts:
    if d['name'] == 'I Am Philosopher':
        continue
    graph.set_access_token(d['access_token'])
    page_id = d['id']

data = graph.get(page_id + '/insights/' + edge, {'period': 'week'})[0]

for i in range(5):
   if graph.paginated():
        data['values'] += graph.previous()[0]['values']

# Print out the content of the message.
print 'Content-Type: text/json'
print

print json.dumps(data)
