import pandas as pd
import nltk
from nltk.tokenize import word_tokenize
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.ensemble import RandomForestClassifier, GradientBoostingClassifier
from sklearn.svm import SVC
from sklearn.metrics import classification_report
from sklearn.model_selection import train_test_split
from sklearn.metrics import accuracy_score

# Download NLTK resources 
nltk.download('punkt')

# Load the CSV file into a DataFrame
df = pd.read_csv('D:/Projects/Online paper marking system/New folder (3)/ML model/output.csv')

# Convert the columns to strings 
df['responses__'] = df['responses__'].astype(str)
df['patterns__001'] = df['patterns__001'].astype(str)
df['patterns__002'] = df['patterns__002'].astype(str)
df['patterns__003'] = df['patterns__003'].astype(str)

# Tokenize text for each column
df['tokens_responses'] = df['responses__'].apply(word_tokenize)
df['tokens_patterns_001'] = df['patterns__001'].apply(word_tokenize)
df['tokens_patterns_002'] = df['patterns__002'].apply(word_tokenize)
df['tokens_patterns_003'] = df['patterns__003'].apply(word_tokenize)

# Define the target variables (responses to each question)
y_responses = df['responses__']
y_001 = df['patterns__001']
y_002 = df['patterns__002']
y_003 = df['patterns__003']

# Define the input features (TF-IDF vectors) for each question
tfidf_vectorizer = TfidfVectorizer()

# Fit and transform text data for each pattern
X_responses = tfidf_vectorizer.fit_transform(df['responses__'])
X_patterns_001 = tfidf_vectorizer.transform(df['patterns__001'])
X_patterns_002 = tfidf_vectorizer.transform(df['patterns__002'])
X_patterns_003 = tfidf_vectorizer.transform(df['patterns__003'])

# Split the data into training and testing sets for each question
X_train_responses, X_test_responses, y_train_responses, y_test_responses = train_test_split(X_responses, y_responses, test_size=0.2, random_state=42)
X_train_001, X_test_001, y_train_001, y_test_001 = train_test_split(X_patterns_001, y_001, test_size=0.2, random_state=42)
X_train_002, X_test_002, y_train_002, y_test_002 = train_test_split(X_patterns_002, y_002, test_size=0.2, random_state=42)
X_train_003, X_test_003, y_train_003, y_test_003 = train_test_split(X_patterns_003, y_003, test_size=0.2, random_state=42)

# Initialize classifiers for each question
rf_model_001 = RandomForestClassifier(n_estimators=100, random_state=42)
svm_model_002 = SVC(kernel='linear', C=1)
gb_model_003 = GradientBoostingClassifier(n_estimators=100, learning_rate=1.0, max_depth=1, random_state=42)

# Train the models for each question
rf_model_001.fit(X_train_responses, y_train_001)
svm_model_002.fit(X_train_001, y_train_002)
gb_model_003.fit(X_train_002, y_train_003)

# Evaluate the models on the test set for each question
y_pred_001 = rf_model_001.predict(X_test_responses)
y_pred_002 = svm_model_002.predict(X_test_001)
y_pred_003 = gb_model_003.predict(X_test_002)

# Print classification reports for each question
print("Classification Report for patterns__001:")
print(classification_report(y_test_001, y_pred_001))

print("Classification Report for patterns__002:")
print(classification_report(y_test_002, y_pred_002))

print("Classification Report for patterns__003:")
print(classification_report(y_test_003, y_pred_003))

