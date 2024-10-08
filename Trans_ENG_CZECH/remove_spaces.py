import re

# Function to remove multiple spaces
def remove_extra_spaces(text):
    # Using regex to replace 2 or more spaces with a single space
    return re.sub(r'\s{2,}', '', text)

# Open the file and process each line
with open("processed_wordbook.txt", "r", encoding="utf-8") as file:
    content = file.read()

# Remove extra spaces
processed_content = remove_extra_spaces(content)

# Write the processed content back to a file
with open("processed_wordbook_processed.txt", "w", encoding="utf-8") as file:
    file.write(processed_content)

print("Processed content saved to processed_wordbook_processed.txt")
