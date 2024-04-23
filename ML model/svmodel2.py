from sentence_transformers import SentenceTransformer
from sklearn.metrics.pairwise import cosine_similarity

def calculate_similarity_sbert(teacher_answer, student_answer, model_name='paraphrase-distilroberta-base-v1'):
    """
    Calculate the similarity between the teacher's answer and the student's answer using SBERT.
    
    Parameters:
    teacher_answer (str): The correct answer provided by the teacher.
    student_answer (str): The answer provided by the student.
    model_name (str): The name of the pre-trained SBERT model to use. Default is 'paraphrase-distilroberta-base-v1'.
    
    Returns:
    float: The similarity score as a percentage.
    """
    # Load the SBERT model
    model = SentenceTransformer(model_name)
    
    # Generate embeddings for the teacher's answer and the student's answer
    embeddings = model.encode([teacher_answer, student_answer])
    
    # Calculate the similarity
    similarity = cosine_similarity(embeddings[0].reshape(1, -1), embeddings[1].reshape(1, -1))
    
    # Convert the similarity to a percentage
    similarity_percentage = similarity[0][0] * 100
    
    return similarity_percentage

# Input the question details
question_id = input("Enter the question ID: ")
question = input("Enter the question: ")
teacher_answer = input("Enter the teacher's answer: ")
student_answer = input("Enter the student's answer: ")

# Calculate and display the similarity score
similarity_score = calculate_similarity_sbert(teacher_answer, student_answer)
print(f"The similarity score between the teacher's answer and the student's answer is: {similarity_score:.2f}%")
