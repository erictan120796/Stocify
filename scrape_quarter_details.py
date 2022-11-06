from bs4 import BeautifulSoup
import pandas as pd
import os, traceback, hashlib
from pymongo import MongoClient
from datetime import datetime


def generateHash(rawId):
    hashObject = rawId.encode('utf-8')
    hexDig = hashlib.sha256(hashObject.encode()).hexdigest()
    return hexDig

def setId(stock_name):
    hash = generateHash(stock_name)
    stock_id = str(hash)
    return stock_id
path_name = os.path.dirname(os.path.realpath(__file__))
directory = path_name + '/Data/StockQuarterDetails'

db = MongoClient('mongodb://localhost:27017/')['Stock_Quarter']

i = 1
try:
    for filename in os.listdir(directory):
        filename = directory + '/' + filename
        soup = BeautifulSoup(open(filename).read(),'lxml')

        stock_name = soup.find('span',{'class':'stname'})
        if stock_name and stock_name.text.strip():
            stock_name = stock_name.text.split(':')[-1].split('(')[0].strip()
        stock_sector = soup.find('span',{'class':'boarAndSector'})
        if stock_sector and stock_sector.text.strip():
            stock_sector = stock_sector.text.split(':')[-1].strip()
        collection = db[stock_name]

        t_header = soup.find('table',{'id':'financialResultTable'})
        column_name = []
        if t_header:
            header = t_header.find('thead')
            unwanted_tag = header.find('tr')
            unwanted_tag.extract()
            header_contents = header.findAll('th')
            for content in header_contents:
                column_name.append(content.text.strip()) 

        table = soup.find('tbody',{'id':'tablebody'})
        if table:
            rows = table.findAll('tr')
            if rows:
                for row in rows:
                    try:
                        columns = row.findAll('td') #38
                        eoq_date = str(columns[column_name.index('EOQ Date')].text.strip()).strip()
                        ann_date = str(columns[column_name.index('Ann. Date')].text.strip()).strip()
                        if eoq_date and ann_date:
                            objDate = str(columns[column_name.index('Quarter')].text.strip())
                            objDate = datetime.strptime(str(objDate).strip(),'%d-%b-%Y')
                            
                            quarter_date = objDate.strftime('%Y-%m-%d')
                            
                            eps = str(columns[column_name.index('EPS')].text.strip()).replace(',','')
                            rps = str(columns[column_name.index('RPS')].text.strip()).replace(',','')
                            naps = str(columns[column_name.index('NAPS')].text.strip()).replace(',','')
                            
                            qoq = str(columns[column_name.index('QoQ')].text.strip()).replace(',','').replace('%','')

                            eoq_price = str(columns[column_name.index('EOQ Price')].text.strip()).replace(',','')   
                            eoq_p_eps = str(columns[column_name.index('EOQ P/EPS')].text.strip()).replace(',','')
                            eoq_p_rps = str(columns[column_name.index('EOQ P/RPS')].text.strip()).replace(',','')
                            eoq_p_naps = str(columns[column_name.index('EOQ P/NAPS')].text.strip()).replace(',','')
                            

                            ann_price = str(columns[column_name.index('ANN Price')].text.strip()).replace(',','')
                            ann_p_eps = str(columns[column_name.index('ANN P/EPS')].text.strip()).replace(',','')
                            ann_p_rps = str(columns[column_name.index('ANN P/RPS')].text.strip()).replace(',','')
                            ann_p_naps = str(columns[column_name.index('ANN P/NAPS')].text.strip()).replace(',','')

                            stockID = setId(stock_name)
                            mydict = {
                                    'stockId' : stockID,
                                    "stockName": stock_name,
                                    "stockSector": stock_sector,
                                    "quarter": quarter_date,
                                    "EPS": eps,
                                    "RPS": rps,
                                    "NAPS": naps,
                                    "QOQ" : qoq,
                                    "EOQ_PRICE": eoq_price,
                                    "EOQ_P_EPS": eoq_p_eps,
                                    "EOQ_P_RPS": eoq_p_rps,
                                    "EOQ_P_NAPS": eoq_p_naps,
                                    "ANN_PRICE": ann_price,
                                    "ANN_P_EPS": ann_p_eps,
                                    "ANN_P_RPS": ann_p_rps,
                                    "ANN_P_NAPS": ann_p_naps
                            }
                            collection.insert_one(mydict)
                    except:
                        pass

        i+=1
except Exception as e:
    pass
    # print traceback.format_exc()
    # print str(stock_name) + 'FAIL'
