# main.py
from database import connect_to_db, load_questions_and_answers, update_score
#from similarity import calculate_similarity_sbert
from similarityv2 import calculate_similarity_hf
import time

def main():
    while True:
        print("\nListening to the database...")
        connection = connect_to_db()
        print("Connected to the database successfully!")
        question_answers = load_questions_and_answers(connection)
        print("Teachers questions and answers loded")
        cursor = connection.cursor()
        query = "SELECT id, question, answer FROM user_answers WHERE score IS NULL"
        cursor.execute(query)
        results = cursor.fetchall()
        print("Students answers loaded")
        for row in results:
            question_id, user_question, user_answer = row
            teacher_answer = question_answers.get(user_question)
            if teacher_answer:
                print("\n[Get] Question Id                :",question_id)
                similarity_score = calculate_similarity_hf(teacher_answer, user_answer)
                print("[Processing done] Question Id    :",question_id)
                update_score(connection, question_id, similarity_score)
                print("[Score updated] Question Id      :",question_id)
        
        cursor.close()
        connection.close()
        
        # Wait for 60 seconds before the next iteration
        time.sleep(60)

if __name__ == "__main__":
    main()
