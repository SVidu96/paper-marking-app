from transformers import AutoTokenizer, AutoModel
import torch

def calculate_similarity_bert(teacher_answer, student_answer, model_name='bert-base-uncased'):
    """
    Calculate the similarity between the teacher's answer and the student's answer using BERT.
    
    Parameters:
    teacher_answer (str): The correct answer provided by the teacher.
    student_answer (str): The answer provided by the student.
    model_name (str): The name of the pre-trained model to use. Default is 'bert-base-uncased'.
    
    Returns:
    float: The similarity score as a percentage.
    """
    # Load the tokenizer and model
    tokenizer = AutoTokenizer.from_pretrained(model_name)
    model = AutoModel.from_pretrained(model_name)
    
    # Tokenize the inputs
    inputs_teacher = tokenizer(teacher_answer, return_tensors='pt', padding=True, truncation=True, max_length=512)
    inputs_student = tokenizer(student_answer, return_tensors='pt', padding=True, truncation=True, max_length=512)
    
    # Encode the inputs
    with torch.no_grad():
        outputs_teacher = model(**inputs_teacher)
        outputs_student = model(**inputs_student)
    
    # Extract the pooler output
    pooler_output_teacher = outputs_teacher.pooler_output
    pooler_output_student = outputs_student.pooler_output
    
    # Calculate the similarity
    similarity = torch.nn.functional.cosine_similarity(pooler_output_teacher, pooler_output_student)
    
    # Convert the similarity to a percentage
    similarity_percentage = similarity.item() * 100
    
    return similarity_percentage

# Input the question details
question_id = input("Enter the question ID: ")
question = input("Enter the question: ")
teacher_answer = input("Enter the teacher's answer: ")
student_answer = input("Enter the student's answer: ")

# Calculate and display the similarity score
similarity_score = calculate_similarity_bert(teacher_answer, student_answer)
print(f"The similarity score between the teacher's answer and the student's answer is: {similarity_score:.2f}%")
