#!/usr/bin/python


"""
.. module:: task_executor
   :platform Unix
   :synopsis: Provides a unified task executor for managing the cache servers.

.. moduleauthor:: R. W. Sinnet (ryan@udundi.com)

"""

"""
Questions:
   - Do we need atomic access?
"""

import argparse
from facebook_cache_interface import FacebookCacheInterface as FCIface


# Parse command line arguments
parser = argparse.ArgumentParser(description='Executes scheduled tasks or on-demand tasks.')
parser.add_argument('--userid')
parser.add_argument('--pageid')

userid = vars(parser.parse_args())['userid']
pageid = vars(parser.parse_args())['pageid']

if userid:
    pass
elif pageid:
    update_page_data(pageid)



def update_facebook_insight(pageid, insightid, period):
    """Updates the cached data for the specified page, insight, and period.
    """

def check_for_old
