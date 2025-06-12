# 🌍 WordPress Plugin: Interaktivní mapy a vícejazyčné překladače s Word2Vec

Tento WordPress plugin kombinuje **interaktivní mapy vytvořené pomocí Leaflet.js**, **vícejazyčné překladače** a **serverové zpracování významově podobných slov pomocí Word2Vec**.


## ✨ Funkce pluginu

### 🗺️ Interaktivní mapy
- **2 mapy** vytvořené pomocí **Leaflet.js**
- Zobrazení historických nebo tematických lokalit
- Možnost přidávání vlastních vrstev, popisků nebo událostí po kliknutí

### 🈯 Překladače
- **3 překladače** (s databázovou a online podporou):
  - 🇨🇿 Čeština → Angličtina
  - 🇩🇪 Němčina → Angličtina
  - 🇱🇦 Latina → Angličtina
- Kombinace **lokální databáze** a **MyMemory API** (pro rozšíření výsledků)
- Automatické vyhledání překladu nebo návrat alternativních možností

### 🧠 Word2Vec doporučení
- Napojení na vlastní **REST API v Pythonu**
- Po překladu se zobrazí **významově podobná slova** díky Word2Vec (model: Google News)
- Backend server vystavuje JSON API pro komunikaci s WordPress frontendem

---

## 🧱 Technologie

- **Frontend:** JavaScript, Leaflet.js, AJAX, WordPress Shortcodes
- **Backend (WP plugin):** PHP, MySQL
- **Překladová logika:** kombinace SQL dotazů a MyMemory API
- **Word2Vec API Server:** Python (FastAPI/Flask), REST API, předtrénovaný model Google News

---

