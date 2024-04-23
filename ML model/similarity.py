# similarity.py
from sentence_transformers import SentenceTransformer
from sklearn.metrics.pairwise import cosine_similarity

def calculate_similarity_sbert(teacher_answer, student_answer, model_name='paraphrase-distilroberta-base-v1'):
    model = SentenceTransformer(model_name)
    embeddings = model.encode([teacher_answer, student_answer])
    similarity = cosine_similarity(embeddings[0].reshape(1, -1), embeddings[1].reshape(1, -1))
    similarity_percentage = similarity[0][0] * 100
    return similarity_percentage
