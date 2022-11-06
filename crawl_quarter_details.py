import requests
from bs4 import BeautifulSoup
from pymongo import MongoClient
import os, hashlib, random, time, traceback, logging
from os.path import dirname, abspath

start_links = [
    "https://klse.i3investor.com/financial/quarter/latest.jsp",
    "https://klse.i3investor.com/financial/quarter/upcoming.jsp"
]

prefix_link = 'https://klse.i3investor.com'

header = {
    'User-Agent' : 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36',
}

a = []
b = []
counts = 1
path_name = os.path.dirname(os.path.realpath(__file__))
directory = path_name + '/Data/StockQuarterDetails'
for link in start_links:
    response = requests.get(link, headers=header)
    soup = BeautifulSoup(response.text,'lxml')
    tr = soup.find('tbody',{'id':'tablebody'})
    asp = tr.findAll('a')  
    for i in asp:
        if '/servlets/stk/fin/' in i['href']:
            b.append(i.text.strip())
            a.append(prefix_link + i['href'])

for job,stock_name in zip(a,b):
    try:
        rs = requests.get(job,headers = header)
        time.sleep(random.randint(3,5))
        filename = directory + "/"  + stock_name + ".html"
        with open(filename, 'wb') as f :
            f.write(rs.text.encode('utf-8'))
            f.close()
            counts +=1
    except:
        logging.error(traceback.format_exc())
        counts +=1
