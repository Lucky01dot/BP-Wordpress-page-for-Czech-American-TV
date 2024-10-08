def process_file(input_file, output_file):
    with open(input_file, 'r', encoding='utf-8') as infile, open(output_file, 'w', encoding='utf-8') as outfile:
        for line in infile:
            # Odstranit prázdné řádky a bílé znaky na začátku a na konci řádku
            line = line.strip()

            # Zkontrolovat, zda řádek obsahuje tabulátor nebo více mezer mezi českým výrazem a překladem
            if '\t' in line or '  ' in line:
                # Rozdělit řádek na český výraz a překlad
                parts = line.split('\t') if '\t' in line else line.split('  ', 1)

                # Pokud je rozděleno na dva díly, uložíme je
                if len(parts) == 2:
                    cesky_vyraz = parts[0].strip()
                    preklad = parts[1].strip()
                    # Zapsat český výraz a překlad do výstupního souboru
                    outfile.write(f"{cesky_vyraz}\t{preklad}\n")


if __name__ == "__main__":
    # Nastavte název vstupního souboru (váš textový soubor) a výstupního souboru
    input_file = 'wordbook-Genealogy.txt'
    output_file = 'processed_wordbook.txt'

    # Spustit funkci pro zpracování souboru
    process_file(input_file, output_file)
