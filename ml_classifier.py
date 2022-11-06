from pymongo import MongoClient
import pandas as pd
from scipy.stats import randint
from sklearn.model_selection import train_test_split
from sklearn.tree import DecisionTreeClassifier
from sklearn.model_selection import RandomizedSearchCV, GridSearchCV
from sklearn.metrics import classification_report, confusion_matrix
from sklearn.svm import SVC 
import sys


def getClassifier(ktype):
    if ktype == 0:
        # Radial Basis Function kernal
        return SVC(kernel='rbf', gamma="auto", random_state = 0)
    elif ktype == 1:
        # Sigmoid kernal
        return SVC(kernel='sigmoid', gamma="auto", random_state = 0)
    elif ktype == 2:
        # Linear kernal
        return SVC(kernel='linear', gamma="auto", random_state = 0)

stockName = sys.argv[1]
collection = MongoClient('mongodb://localhost:27017/')['Stock_Class_Model_Data'][stockName]
data = pd.DataFrame(list(collection.find()))
data.head() 
feature_name = ['EOQ_P_RPS','EOQ_P_NAPS','EOQ_P_EPS']
X = data[feature_name]
y = data['Class']

X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.25 ,random_state = 0)

param_dist = {
                "max_depth": [3,None],
                "min_samples_leaf" : randint(1,8),
                "criterion":['gini','entropy']}

optimal_classifier = DecisionTreeClassifier(random_state=0)
optimal_classifier = RandomizedSearchCV(optimal_classifier,param_dist,cv=5,random_state=0)
optimal_classifier.fit(X_train, y_train)
criterion = optimal_classifier.best_params_['criterion']
min_samples_leaf = optimal_classifier.best_params_['min_samples_leaf']
max_depth = optimal_classifier.best_params_['max_depth']

#Default Parameter
default_classifier = DecisionTreeClassifier(random_state= 0)
default_classifier.fit(X_train, y_train)
default_score = default_classifier.score(X_test, y_test)

#With Parameter
classifier = DecisionTreeClassifier(random_state= 0,criterion=criterion, max_depth=max_depth, min_samples_leaf=min_samples_leaf)
classifier.fit(X_train, y_train)
y_pred = classifier.predict(X_test)
score = classifier.score(X_test, y_test)

kernels = ['rbf', 'sigmoid','linear']
curr_score = 0

for i in range(3):
# Train a SVC model using different kernal
    svclassifier = getClassifier(i) 
    svclassifier.fit(X_train, y_train)
    score = svclassifier.score(X_test, y_test)
    if score > curr_score:
        curr_score = score
        kernel = kernels[i]

from sklearn import svm
clf_svm = svm.SVC(kernel=kernel,random_state=0)
clf_svm.fit(X_train, y_train)
svm_score = clf_svm.score(X_test, y_test)

models = ['DT','SVM','DTWP']
top_score = 0
score_list = [default_score, svm_score, score]
for i in range(3):
    if score_list[i] > top_score:
        top_score = score_list[i]
        chosen_model = models[i]

statis_collection = MongoClient('mongodb://localhost:27017/')['Statis_ByStock']['Stock_list']
cursor = statis_collection.find({ "stockName": stockName })
for i in cursor:
    stockNamee = stockName
    p_eps =  i['P_EPS']
    p_naps = i['P_NAPS']
    p_rps =  i['P_RPS']
    price = i['Price']
    sector = i['stockSector']

    if chosen_model == 'DT':
        classs = default_classifier.predict([[p_rps, p_naps, p_eps]])[0]
        parameter = 'Default'
        accuracy = default_score
        algo = 'Decision Tree'
        paremeter = 'Default'
    elif chosen_model == 'DTWP':
        classs = classifier.predict([[p_rps, p_naps, p_eps]])[0]
        accuracy = score
        algo = 'Decision Tree'
        parameter = 'Criterion : ' + str(classifier.criterion) + ' Max_depth : ' + str(classifier.max_depth) +' Min_Sample_Leaf : ' + str(classifier.min_samples_leaf)
    else:
        classs = clf_svm.predict([[p_rps, p_naps, p_eps]])[0]
        parameter = 'Kernel : '+ str(clf_svm.kernel)
        accuracy = score
        algo = 'SVM'
            
    mydict = {
                "stockName" : stockNamee,
                'Accuracy':accuracy,
                'Class':classs,
                'Algo':algo,
                'Parameter':parameter
        }
    
    collection = MongoClient('mongodb://localhost:27017/')['Ml_ByStock']['Stock_list']
    temp = collection.find({ "stockName": stockNamee })
    
    if temp.count() == 0:
        collection.insert_one(mydict)
    else:
        collection.find_one_and_update(
                                {"stockName" : stockNamee},
                                {"$set":
                                    {
                                        'Accuracy':accuracy,
                                        'Class':classs,
                                        'Algo':algo,
                                        'Parameter':parameter
                                    }
                                }
                            )





