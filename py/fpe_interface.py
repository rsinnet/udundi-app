#!/usr/bin/python

"""
.. module:: fpe_interface
   :platform Unix
   :synopsis: Provides a Python interface to the Facebook Graph.

.. moduleauthor:: R. W. Sinnet (ryan@udundi.com)
"""

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

since = datetime.datetime.strptime(data.getvalue('since'), '%m/%d/%Y').
until = datetime.datetime.strptime(data.getvalue('until'), '%m/%d/%Y')

graph = G(access_token)

since_iso = time.mktime(since.utctimetuple())
until_iso = time.mktime(until.utctimetuple())

#TODO CONVERT TIME ZONES

#Facebook will return up to 93 days of data at once. We will paginate using 90 days.
# 90 * 86400
if until_iso - since_iso > 7776000:
    since_iso = until_iso - 7776000


accounts = graph.get('me/accounts')
for d in accounts:
    if d['id'] != page_id:
        continue
    graph.set_access_token(d['access_token'])

data = graph.get('{0}/insights/{1}/{2}'.format(page_id, edge, period),
                 { 'since': since_iso, 'until': until_iso })[0]
new_data = True

def add_item(datum):
    item_date = iso8601.parse_date(datum['end_time']).replace(tzinfo=None);
    if item_date >= since and item_date <= until:
        data['values'] += [datum]
        new_data = True;
    elif item_date >= until:
        new_data = True
    else:
        new_data = False
        
    return new_data


dvals = data['values']
data['values'] = [];

# Add the data from the first result...
for i in dvals:
    new_data = add_item(i)

# ...and then page through the rest of the results.
while graph.paginated() and new_data:
    new_data = False;
    current_data = graph.previous()[0]['values']
    for i in range(len(current_data)):
        new_data = add_item(current_data[i])


# Print out the content of the message.
print 'Content-Type: text/json'
print

print json.dumps(data)


