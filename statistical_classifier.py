from pymongo import MongoClient
from statistics import stdev, mean
import traceback
from collections import Counter

collection = MongoClient('mongodb://localhost:27017/')['Statis_ByStock']['Stock_list']
cursor = collection.find()

for i in cursor:
    top_std_qoq = i['avg_qoq'] + i['stdev_qoq']
    btm_std_qoq = i['avg_qoq'] - i['stdev_qoq']
    if i['QOQ'] < top_std_qoq and i['QOQ'] > btm_std_qoq:
        qoq_class = 'Fair'
    elif i['QOQ'] > top_std_qoq:
        qoq_class = 'Over'
    elif i['QOQ'] < btm_std_qoq:
        qoq_class = 'Under'

    top_std_p_eps = i['avg_p_eps'] + i['stdev_p_eps']
    btm_std_p_eps = i['avg_p_eps'] - i['stdev_p_eps']
    if i['P_EPS'] < top_std_p_eps and i['P_EPS'] > btm_std_p_eps:
        p_eps_class = 'Fair'
    elif i['P_EPS'] > top_std_p_eps:
        p_eps_class = 'Over'
    elif i['P_EPS'] < btm_std_p_eps:
        p_eps_class = 'Under'    

    top_std_p_naps = i['avg_p_naps'] + i['stdev_p_naps']
    btm_std_p_naps = i['avg_p_naps'] - i['stdev_p_naps']
    if i['P_NAPS'] < top_std_p_naps and i['P_NAPS'] > btm_std_p_naps:
        p_naps_class = 'Fair'
    elif i['P_NAPS'] > top_std_p_naps:
        p_naps_class = 'Over'
    elif i['P_NAPS'] < btm_std_p_naps:
        p_naps_class = 'Under'  
    
    top_std_p_rps = i['avg_p_rps'] + i['stdev_p_rps']
    btm_std_p_rps = i['avg_p_rps'] - i['stdev_p_rps']
    if i['P_RPS'] < top_std_p_rps and i['P_RPS'] > btm_std_p_rps:
        p_rps_class = 'Fair'
    elif i['P_RPS'] > top_std_p_rps:
        p_rps_class = 'Over'
    elif i['P_RPS'] < btm_std_p_rps:
        p_rps_class = 'Under'

    class_list =[]
    class_list.append(p_eps_class)
    class_list.append(p_rps_class)
    class_list.append(p_naps_class)

    if class_list.count('Under') == 3:
        overall_class = 'Under / BUY'
        classs = 'Under'
    elif class_list.count('Over') >= 1:
        overall_class = 'Over / SELL'
        classs = 'Over'
    else:
        overall_class = 'Fair / HOLD'
        classs = 'Fair'

    myquery = { "stockId": i['stockId'] }
    newvalues = { "$set": { "qoq_class": qoq_class,'p_naps_class':p_naps_class,'p_rps_class':p_rps_class,'p_eps_class':p_eps_class,'overall_class':overall_class,'class':classs} }
    collection.update_one(myquery, newvalues)   

        
    