# PHP app

## DB connection
### projectUnder\components\connect.php
$db_name = 'mysql:host=localhost:3308;dbname=course_db';  
$user_name = 'root';  
$user_password = '';  
  
admin email - admin1@gmail.com  
pw - 123  

user emails - user1@gmail.com || user2@gmail.com || user3@gmail.com  
pw - 123  

exam pws 456/789


# ML Project
## Insatall dependencies
`pip install pymysql`   
`pip install sentence_transformers`   
`pip install scikit-learn`  OR `pip install sklearn`  

## DB connection  
### ML model\database.py  
connection = pymysql.connect(host='localhost',
                                 port=3308,
                                 user=user_name,
                                 password=user_password,
                                 db='course_db')
Run command `python main.py`   



