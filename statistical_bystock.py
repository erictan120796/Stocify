from pymongo import MongoClient
from statistics import stdev, mean
import traceback

db = MongoClient('mongodb://localhost:27017/')['Stock_Quarter']
newdbcol = MongoClient('mongodb://localhost:27017/')['Statis_ByStock']['Stock_list']

for coll in db.list_collection_names():
    collection = db[coll]
    cursor = collection.find()
    qoq_list = []
    p_eps_list = []
    p_naps_list = []
    p_rps_list = []
    first = True
    try:
        for i in cursor:
            if first == True:
                eps = i['EPS']
                rps = i['RPS']
                naps = i['NAPS']
                qoq = i['QOQ']

            stockID = i['stockId']
            stockSector = i['stockSector']
            ave_price = 0
            ave_price = (float(i['EOQ_PRICE']) + float(i['ANN_PRICE'])) / 2
            p_eps_list.append(float(round(ave_price / float(i['EPS']) * 100 ,2)))
            p_rps_list.append(float(round(ave_price / float(i['RPS']) * 100 ,2)))
            if float(i['NAPS']) != 0:
                p_naps_list.append(float(round(ave_price / float(i['NAPS']) ,2)))
            first = False

    
        avg_p_eps =round(sum(p_eps_list) / len(p_eps_list),2) 
        stdev_p_eps = round(stdev(p_eps_list),2)

        avg_p_naps =round(sum(p_naps_list) / len(p_naps_list),2)
        stdev_p_naps = round(stdev(p_naps_list),2)

        avg_p_rps =round(sum(p_rps_list) / len(p_rps_list),2)
        stdev_p_rps = round(stdev(p_rps_list),2)

        mydict = {
                'stockId' : stockID,
                "stockName": coll,
                "stockSector": stockSector,
                "EPS": eps,
                "RPS": rps,
                "NAPS": naps,
                "avg_p_eps" : avg_p_eps,
                "stdev_p_eps":stdev_p_eps,
                "avg_p_naps" : avg_p_naps,
                "stdev_p_naps":stdev_p_naps,
                "avg_p_rps" : avg_p_rps,
                "stdev_p_rps":stdev_p_rps,
        }

        newdbcol.insert_one(mydict)

       
    except Exception as e:
        pass
        # print traceback.format_exc(e)
        # print coll + 'faill'
    # break
