# Python Challenge No.2

from bs4 import BeautifulSoup, Comment
from urllib import urlopen
import re

url = "http://www.pythonchallenge.com/pc/def/ocr.html"

bs = BeautifulSoup(urlopen(url).read(), "html.parser")

comments = bs.findAll(text=lambda text: isinstance(text, Comment))

reg = re.compile("[a-zA-Z0-9]")
result = reg.findall(comments[1])

print(result)

# [u'e', u'q', u'u', u'a', u'l', u'i', u't', u'y']

print(''.join(result))

# equality