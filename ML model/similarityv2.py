from transformers import AutoTokenizer, AutoModel
import torch
from sklearn.metrics.pairwise import cosine_similarity

#def calculate_similarity_hf(teacher_answer, student_answer, model_name='BAAI/bge-small-en-v1.5'):
def calculate_similarity_hf(teacher_answer, student_answer, model_name='BAAI/bge-base-en-v1.5'):
    # Load the tokenizer and model
    tokenizer = AutoTokenizer.from_pretrained(model_name)
    model = AutoModel.from_pretrained(model_name)
    # Tokenize the inputs
    inputs_teacher = tokenizer(teacher_answer, return_tensors='pt', padding=True, truncation=True, max_length=512)
    inputs_student = tokenizer(student_answer, return_tensors='pt', padding=True, truncation=True, max_length=512)
    
    # Generate embeddings
    with torch.no_grad():
        embeddings_teacher = model(**inputs_teacher).last_hidden_state.mean(dim=1)
        embeddings_student = model(**inputs_student).last_hidden_state.mean(dim=1)
    
    # Calculate cosine similarity
    similarity = cosine_similarity(embeddings_teacher.numpy(), embeddings_student.numpy())
    similarity_percentage = similarity[0][0] * 100
    
    return similarity_percentage
