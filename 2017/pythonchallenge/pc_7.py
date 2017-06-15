# Python Challenge No.7

from PIL import Image

i = Image.open(
    '/Users/jaeseoklee/Documents/DSM/Club/Info/2017/pythonchallenge/pc_7/oxygen.png')
# ic = i.new()

pixelcolor = [i.getpixel((x, 45)) for x in range(i.size[0])]
result = [r for r, g, b, a in pixelcolor if r == g == b]

print(result)
