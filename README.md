# 🌍 WordPress Plugin: Interactive Maps & Multilingual Translators with Word2Vec

This WordPress plugin combines **interactive maps built with Leaflet.js**, **multilingual translators**, and a **Python REST API** backend using Word2Vec to suggest semantically similar words.



## ✨ Plugin Features

### 🗺️ Interactive Maps
- **2 interactive maps** powered by **Leaflet.js**
- Display of historical or thematic city locations
- Custom markers, tooltips, and click actions supported

### 🈯 Translators
- **3 language translators**:
  - 🇨🇿 Czech → English
  - 🇩🇪 German → English
  - 🇱🇦 Latin → English
- Combines **local database lookup** with **MyMemory API** for broader results
- Displays direct translations and suggested alternatives

### 🧠 Word2Vec Recommendations
- Connected to a custom **Python REST API**
- Displays **semantically related words** using the Word2Vec model (Google News)
- Frontend queries the backend and displays results alongside translations

---

## 🧱 Technologies Used

- **Frontend:** JavaScript, Leaflet.js, AJAX, WordPress Shortcodes
- **Backend (WordPress Plugin):** PHP, MySQL
- **Translation logic:** SQL + MyMemory API
- **REST API Server:** Python (FastAPI or Flask), Google News Word2Vec model

