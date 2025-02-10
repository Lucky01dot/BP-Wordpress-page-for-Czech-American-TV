# -*- coding: utf-8 -*-
import csv
import re
import chardet


def detect_encoding(file_path):
    """Detekuje kódování souboru"""
    with open(file_path, "rb") as f:
        raw_data = f.read()
        result = chardet.detect(raw_data)
        detected_encoding = result["encoding"]

        # Pokud chardet detekuje Windows-1254, změníme na Windows-1250
        if detected_encoding and detected_encoding.lower() == "windows-1254":
            detected_encoding = "windows-1250"

        print(f"Detekované kódování: {detected_encoding}")
        return detected_encoding


def clean_latin_word(word):
    """Odstraní latinské gramatické značky (např. , ari, atus sum)"""
    return re.sub(r',.*$', '', word).strip()  # Odstraní čárku a vše za ní


def parse_latin_data(input_file, output_file):
    """Načte latinská slova a české překlady ze souboru a uloží je do CSV s unikátním ID"""
    entries = []

    # Detekujeme kódování souboru
    encoding = detect_encoding(input_file)

    # Načítání souboru s upraveným kódováním
    with open(input_file, 'r', encoding=encoding, errors='ignore') as file:
        lines = file.readlines()

    for idx, line in enumerate(lines, start=1):  # Přidáme ID počínaje 1
        line = line.strip()
        if not line:
            continue

        match = re.match(r'([^	]+)\t(.+)', line)  # Hledání slov oddělených tabulátorem
        if match:
            latin_word = match.group(1).strip()
            czech_translation = match.group(2).strip()

            # Vyčištění latinského slova
            latin_word = clean_latin_word(latin_word)

            entries.append([idx, latin_word, czech_translation])

    # Uložení do CSV s UTF-8
    with open(output_file, 'w', newline='', encoding='utf-8') as csvfile:
        writer = csv.writer(csvfile, delimiter=',', quotechar='"', quoting=csv.QUOTE_MINIMAL)
        writer.writerow(["id", "latin_word", "czech_translation"])  # Hlavička s ID
        writer.writerows(entries)

    print(f"Data byla úspěšně uložena do {output_file}")


# Spuštění skriptu
parse_latin_data('lat_czech.txt', 'latin_czech.csv')
