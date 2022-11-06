from pymongo import MongoClient
import pandas as pd
import traceback

newdb = MongoClient('mongodb://localhost:27017/')['Stock_Class_Model_Data']
stock_id_list = MongoClient('mongodb://localhost:27017/')['Stock_Details']['stock_id_list']
myquery = { "class_model": "Yes" }
stock_list = stock_id_list.find(myquery)

for i in stock_list:
    collection = MongoClient('mongodb://localhost:27017/')['Stock_Quarter'][i['stockName']]
    quarter_list = collection.find()
    length = collection.find().count()
    for j in range(length):
        try:
            diff_within_quart = (float(quarter_list[j]['ANN_PRICE']) - float(quarter_list[j]['EOQ_PRICE'])) / float(quarter_list[j]['EOQ_PRICE']) * 100
            diff_within_quart = float(round(diff_within_quart,1))

            if j == 0:
                diff_out_quart = 0
            else:
                diff_out_quart = (float(quarter_list[j-1]['EOQ_PRICE']) - float(quarter_list[j]['ANN_PRICE'])) / float(quarter_list[j]['ANN_PRICE']) * 100
                diff_out_quart = float(round(diff_out_quart,1))
            
            total_diff_quart = diff_within_quart + diff_out_quart
            if total_diff_quart < float(5) and total_diff_quart > float(-5):
                classs = 'Fair'
            elif total_diff_quart > float(5):
                classs = 'Under'
            else:
                classs = 'Over'

            mydict = {
                            "EOQ_P_EPS": quarter_list[j]['EOQ_P_EPS'],
                            "EOQ_P_RPS": quarter_list[j]['EOQ_P_RPS'],
                            "EOQ_P_NAPS": quarter_list[j]['EOQ_P_NAPS'],
                            'Class':classs
                    }
            newcollection = newdb[i['stockName']]
            newcollection.insert_one(mydict)

        except:
            pass

                
       
        # under_count = classs_list.count('Under')
        # fair_count = classs_list.count('Fair') 
        # over_count = classs_list.count('Over')
        # if under_count >= 8 and fair_count >= 8 and over_count >= 8:
        #     print coll 
        #     print str(under_count) + ' ' + str(fair_count) + ' ' + str(over_count)
        #     myquery = { "stockName": coll}
        #     newvalues = { "$set": {'class_model':'Yes'}}
        #     stock_id_list.update_one(myquery, newvalues)
        # else:
        #     myquery = { "stockName": coll }
        #     newvalues = { "$set": {'class_model':'No'}}
        #     stock_id_list.update_one(myquery, newvalues)

        # if i == 0:
        #     print quarter_list[i]['quarter']
        # else:
        #     print i
    # for i in quarter_list:
    #     print i['ANN_PRICE']
    # break


# if under_count >= 8 and fair_count >= 8 and over_count >= 8:
#             print coll 
#             print str(under_count) + ' ' + str(fair_count) + ' ' + str(over_count)
#             myquery = { "stockName": coll}
#             newvalues = { "$set": {'class_model':'Yes'}}
#             stock_id_list.update_one(myquery, newvalues)
#         else:
#             myquery = { "stockName": coll }
#             newvalues = { "$set": {'class_model':'No'}}
#             stock_id_list.update_one(myquery, newvalues)