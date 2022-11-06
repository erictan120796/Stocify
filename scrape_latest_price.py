import requests
from bs4 import BeautifulSoup
from pymongo import MongoClient
import os, hashlib
from os.path import dirname, abspath

def generateHash(rawId):
    hashObject = rawId.encode('utf-8')
    hexDig = hashlib.sha256(hashObject.encode()).hexdigest()
    return hexDig

def setId(stock_name):
    hash = generateHash(stock_name)
    stock_id = str(hash)
    return stock_id


collection = MongoClient('mongodb://localhost:27017/')['Stock_Details']['stock_daily_price']

path_name = os.path.dirname(os.path.realpath(__file__))

directory = path_name + '/Data/StockListPrice'

collection_list = collection.find()
stock_id_mlist = []
for x in collection_list:
    stock_id_mlist.append(x['stockId'])
stock_id_list = set(stock_id_mlist)

i =1
for filename in os.listdir(directory):
    filename = directory + '/' + filename
    soup = BeautifulSoup(open(filename).read(),'lxml')
    t_header = soup.find('table',{'id':'stockListingTable'})
    column_name = []
    if t_header:
        header = t_header.find('thead')
        header_contents = header.findAll('th')
        for content in header_contents:
            column_name.append(content.text.strip())

 
    table = soup.find('tbody',{'id':'tablebody'})
    if table:
        rows = table.findAll('tr')
        if rows:
            for row in rows:
                columns = row.findAll('td')
                stock_name = str(columns[column_name.index('Stock')].text.strip()).strip()
                latest_stock_price = str(columns[column_name.index('Last')].text.strip()).strip()
                changes = str(columns[column_name.index('Change')].text.strip()).strip()
                i+=1

                stockID = setId(stock_name)
                
                if stockID in stock_id_list:
                    myquery = { "stockId": stockID }
                    newvalues = { "$set": { 
                        "stockPrice": latest_stock_price,
                        'changes' :changes
                    } }
                    collection.update_one(myquery, newvalues)d
                else:
                    mydict = { 
                            'stockId':stockID,
                            'stockName':stock_name,
                            'stockPrice':latest_stock_price,
                            'changes' :changes
                            }
                    collection.insert_one(mydict)
