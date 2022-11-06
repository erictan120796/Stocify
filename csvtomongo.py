import csv,os
import json
import pandas as pd
import sys, getopt, pprint
from pymongo import MongoClient
from os.path import dirname, abspath

#CSV to JSON Conversion
mongo_client=MongoClient() 
db=mongo_client.Stock_Historical_Data

path_name = os.path.dirname(os.path.realpath(__file__))

directory = path_name + '/Data/StockHistoricalData'
for filename in os.listdir(directory):
    collection_name = filename.split('.')[0]
    filename = directory + '/' + filename
    csvfile = open(filename, 'r')
    reader = csv.DictReader(csvfile)

    header= ["Date","Open","High","Low","Close","Adj Close","Volume"]

    for each in reader:
        row={}
        for field in header:
            row[field]=each[field]
        db[collection_name].insert(row)


