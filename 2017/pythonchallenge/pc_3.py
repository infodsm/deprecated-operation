# Python Challenge No.3

from bs4 import BeautifulSoup, Comment
from urllib import urlopen
import re

url = "http://www.pythonchallenge.com/pc/def/equality.html"

bs = BeautifulSoup(urlopen(url).read(), "html.parser")

comments = bs.findAll(text=lambda text: isinstance(text, Comment))

reg = re.compile("[^A-Z][A-Z]{3}([a-z])[A-Z]{3}[^A-Z]")
result = reg.findall(comments[0])

print(result)

# [u'l', u'i', u'n', u'k', u'e', u'd', u'l', u'i', u's', u't']

print(''.join(result))

# linkedlist