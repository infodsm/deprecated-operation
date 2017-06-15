# Python Challenge No.1

from string import maketrans
from string import ascii_lowercase
import re

url = "http://www.pythonchallenge.com/pc/def/map.html"


hint = "g fmnc wms bgblr rpylqjyrc gr zw fylb. rfyrq ufyr amknsrcpq ypc dmp. bmgle gr gl zw fylb gq glcddgagclr ylb rfyr'q ufw rfgq rcvr gq qm jmle. sqgle qrpgle.kyicrpylq() gq pcamkkclbcb. lmu ynnjw ml rfc spj."

print(hint.translate(maketrans(ascii_lowercase,
                               ascii_lowercase[2:] + ascii_lowercase[:2])))
# i hope you didnt translate it by hand. thats what computers are for. doing it in by hand is inefficient and that's why this text is so long. using string.maketrans() is recommended. now apply on the url.

reg = r"/(\w+).html"
name = re.compile(reg).findall(url)
print(url.replace(name[0], name[0].translate(maketrans(ascii_lowercase,
                                                       ascii_lowercase[2:] + ascii_lowercase[:2]))))
# http://www.pythonchallenge.com/pc/def/ocr.html
