#!/usr/bin/python
from string import maketrans

text = "map"
result = ""

# text = text.replace(" ", "")

for i in range(0, len(text)):
    result += chr(ord(text[i]) + 2)

print(result)
print(text.translate(maketrans("abcdefghijklmnopqrstuvwxyz", "cdefghijklmnopqrstuvwxyzab")))

# i hope you didnt translate it by hand. thats what computers are for. doing it in by hand is inefficient and that's why this text is so long. using string.maketrans() is recommended. now apply on the url.
