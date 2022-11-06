import requests
from bs4 import BeautifulSoup
from pymongo import MongoClient
import os, hashlib, time
from os.path import dirname, abspath

path_name = os.path.dirname(os.path.realpath(__file__))

start_links = "https://klse.i3investor.com/financial/quarter/latest.jsp"
startlink = 'https://klse.i3investor.com/jsp/stocks.jsp'
start_links = 'https://klse.i3investor.com/jsp/stocks.jsp?g=S&m=int&s={}'
listt = [
    '0','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'
]

header = {
    'User-Agent' : 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36',
}

directory = path_name + '/Data/StockListPrice'
for element in listt:
    response = requests.get(start_links.format(element), headers=header)
    filename = directory + "/"  + element + ".html"
    with open(filename, 'wb') as f :
        f.write(response.text.encode('utf-8'))
        f.close()


