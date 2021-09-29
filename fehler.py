#!/usr/bin/env python3

import http.client, urllib
conn = http.client.HTTPSConnection("api.pushover.net:443")
conn.request("POST", "/1/messages.json",
  urllib.parse.urlencode({
    "token": "Token",
    "user": "usertoken",
    "message": "Das Script hat sich neu gestartet!",
  }), { "Content-type": "application/x-www-form-urlencoded" })
conn.getresponse()
