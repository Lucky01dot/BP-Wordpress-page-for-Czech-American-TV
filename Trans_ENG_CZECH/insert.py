import sqlite3

# Connect to SQLite (or create the database if it doesn't exist)
conn = sqlite3.connect('translations.db')
cur = conn.cursor()

# Create table if it doesn't exist
cur.execute('''
CREATE TABLE IF NOT EXISTS translations (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    czech_word TEXT,
    english_translation TEXT
)
''')

# Read the file
with open('trans.txt', 'r', encoding='utf-8') as file:
    lines = file.readlines()

# Prepare data for insertion
data = []
for line in lines:
    if '\t' in line:  # The words are separated by a tab character
        czech_word, english_translation = line.strip().split('\t', 1)
        data.append((czech_word, english_translation))

# Insert the data into the table
cur.executemany('''
INSERT INTO translations (czech_word, english_translation)
VALUES (?, ?)
''', data)

# Commit the transaction and close the connection
conn.commit()
conn.close()

print(f"{len(data)} rows inserted successfully!")
