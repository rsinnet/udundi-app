#!/usr/bin/python

from facebook import GraphAPI

import urlparse

class GraphApiExtension(GraphAPI):
    
    def get(self, endpoint, paging_args=dict()):

        if 'access_token' not in paging_args:
            paging_args.update({'access_token': self.current_access_token})

        data = super(GraphApiExtension, self).request(endpoint, paging_args)

        try:
            paging_data = data['paging']
	    url_parts = urlparse.urlsplit(paging_data['next'])
	    self.next_page_endpoint = url_parts[2][1:]
  	    self.next_page_args = dict(urlparse.parse_qsl(url_parts[3]))
        except KeyError:
            self.next_page_endpoint = None
            self.next_page_args = None

        try:
            paging_data = data['paging']
	    url_parts = urlparse.urlsplit(paging_data['previous'])
	    self.previous_page_endpoint = url_parts[2][1:]
  	    self.previous_page_args = dict(urlparse.parse_qsl(url_parts[3]))
        except KeyError:
            self.previous_page_endpoint = None
            self.previous_page_args = None

        return data['data']
    
    def next(self):
        if self.next_page_endpoint is None:
            return {}

        return self.get(self.next_page_endpoint, self.next_page_args)


    def previous(self):
        if self.previous_page_endpoint is None:
            return {}

        return self.get(self.previous_page_endpoint, self.previous_page_args)

    def set_access_token(self, access_token):
        self.current_access_token = access_token

    def access_token(self):
        return self.access_token

    def paginated(self):
        return not (self.next_page_endpoint is None)

    def __init__(self, access_token=None, timeout=None):
        super(GraphApiExtension, self).__init__()
        self.current_access_token = access_token

        self.next_page_endpoint = None
        self.previous_page_endpoint = None
