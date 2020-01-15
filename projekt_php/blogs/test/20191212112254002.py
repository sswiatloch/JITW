import urllib.request
from bs4 import BeautifulSoup

for i in range(0,30):
    sock = urllib.request.urlopen('http://lipsum.zjekoza.pl/')
    htmlSource = sock.read()                             
    sock.close()                                         
    soup = BeautifulSoup(htmlSource, 'html.parser')

    #heading = soup.find('h1')
    paragraph = soup.find_all('p')[1]

    for e in paragraph.find_all('br'):
        e.extract()

    f=open("loremIpsumParagraphs.txt", "a+")
    f.write(str(paragraph) + "\n")


