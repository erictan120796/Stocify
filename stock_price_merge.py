from pymongo import MongoClient

db = MongoClient('mongodb://localhost:27017/')['Stock_Details']
stock_collection = MongoClient('mongodb://localhost:27017/')['Statis_ByStock']['Stock_list']
price_collection = MongoClient('mongodb://localhost:27017/')['Stock_Details']['stock_daily_price']

stock_collection_list = stock_collection.find()
price_collection_list = price_collection.find()
stockId_list = []
stockPrice_list = [] 
for x in price_collection_list:
    stockId_list.append(x['stockId'])
    stockPrice_list.append(x['stockPrice'])

for z in stock_collection_list:
    for c in stockId_list:
        if z['stockId'] == c:
            price = float(stockPrice_list[stockId_list.index(c)])
            p_eps =  float(round(price/ float(z['EPS'])* 100 ,2))
            p_naps =  float(round(price/ float(z['NAPS']),2))
            p_rps =  float(round(price/ float(z['RPS'])* 100,2))
            myquery = { "stockId": z['stockId'] }
            newvalues = { "$set": { "Price": price , 'P_EPS':p_eps,'P_NAPS':p_naps,'P_RPS':p_rps}}
            stock_collection.update_one(myquery, newvalues)
