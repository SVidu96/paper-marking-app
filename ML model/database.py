# database.py
import pymysql

def connect_to_db():
    db_name = 'mysql:host=localhost:3308;dbname=course_db'
    user_name = 'root'
    user_password = ''
    connection = pymysql.connect(host='localhost',
                                 port=3306,
                                 user=user_name,
                                 password=user_password,
                                 db='course_db')
    return connection

def load_questions_and_answers(connection):
    cursor = connection.cursor()
    query = "SELECT question, answer FROM question_papers"
    cursor.execute(query)
    results = cursor.fetchall()
    cursor.close()
    question_answers = {question: answer for question, answer in results}
    return question_answers

def update_score(connection, user_id, score):
    cursor = connection.cursor()
    update_query = "UPDATE user_answers SET score = %s WHERE id = %s"
    cursor.execute(update_query, (score, user_id))
    connection.commit()
    cursor.close()
