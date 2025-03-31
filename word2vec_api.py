from fastapi import FastAPI
from gensim.models import KeyedVectors
import numpy as np
import uvicorn

app = FastAPI()

# Načtení modelu Google News Word2Vec
try:
    print("⏳ Načítání modelu...")
    model = KeyedVectors.load_word2vec_format("GoogleNews-vectors-negative300.bin.gz", binary=True)
    print("✅ Model načten!")
except Exception as e:
    print(f"❌ Chyba při načítání modelu: {e}")
    model = None


def get_sentence_vector(sentence: str):
    """ Vypočítá průměrný vektor věty. """
    words = sentence.split()
    word_vectors = [model[word] for word in words if word in model]
    if not word_vectors:
        return None  # Žádné použitelné vektory
    return np.mean(word_vectors, axis=0)  # Průměrný vektor


@app.get("/word2vec")
async def get_similar_words(word: str, topn: int = 3):
    try:
        if not model:
            print("❌ Word2Vec model nebyl úspěšně načten.")
            return {"error": "Word2Vec model nebyl úspěšně načten."}

        print(f"📥 Přijatý požadavek: word={word}")

        sentence_vector = get_sentence_vector(word)
        if sentence_vector is None:
            print("❌ Slovo není v modelu Word2Vec.")
            return {"error": "Slovo není v modelu Word2Vec."}

        similar_words = model.similar_by_vector(sentence_vector, topn=topn)
        suggestions = [w[0] for w in similar_words]
        probabilities = [f"{s * 100:.2f}%" for _, s in similar_words]

        print("🔍 Podobná slova:")
        for word, prob in zip(suggestions, probabilities):
            print(f"{word}: {prob}")

    except Exception as e:
        print(f"❌ Chyba: {e}")
        return {"error": str(e)}

    return {"word": word, "suggestions": suggestions}  # Pravděpodobnosti se nevrací, pouze vypisují


if __name__ == "__main__":
    uvicorn.run(app, host="127.0.0.1", port=5000)
