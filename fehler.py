
#!/usr/bin/env python3

import httplib, urllib

conn = httplib.HTTPSConnection("api.pushover.net:443")
conn.request("POST", "/1/messages.json",
  urllib.urlencode({
    "token": "TOKEN",
    "user": "USER",
    "message": "Das Script hat sich neu gestartet!",
  }), { "Content-type": "application/x-www-form-urlencoded" })
conn.getresponse()
