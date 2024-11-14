
## Tabulky

Každý CSV soubor má v prvním řádku název sloupců.

----------------------------

### `region.csv`

Kraje.

Zdroj: MVČR.

Použité pro *Name Distribution*. Odkazováno z `district.csv` a `mep.csv`.

- `id` = id kraje
- `name_cz` = název kraje česky
- `name_en` = název kraje anglicky
- `map_code` = kód kraje pro Google maps

----------------------------

### `mep.csv`

Obce s rozšířenou působností (Municipality with Extended Powers).

Zdroj: MVČR.

Použité pro *Name Distribution*.

- `id` = id okresu
- `name_cz` = název obce česky
- `name_de` = název obce německy
- `region_id` = id kraje (FK do tabulky `region.csv`)
- `lat` = zeměpisná šířka
- `lng` = zeměpisná výška

----------------------------

### `first_name.csv`

Jména.

Zdroj: MVČR.

Použité pouze jako lookup při přepisování jmen v *Changing Names*.

- `id` = id jména
- `name` = jméno

----------------------------

### `last_name.csv`

Příjmení.

Zdroj: MVČR.

Použité pro *Name Distribution* a také jako lookup při přepisování jmen v *Changing Names*.

- `id` = id jména
- `name` = jméno
- `count` = celková četnost jména 

----------------------------

### `district.csv`

Okresy.

Zdroj: Wiki.

Použité pro *German Terminology*. Odkazováno ze `city.csv`.


- `id` = id okresu
- `name_cz` = název okresu česky
- `name_en` = název okresu anglicky
- `region_id` = id kraje (FK do tabulky `region.csv`)

----------------------------

### `city.csv`

Města, obce a osady a jejich německé názvy.

Data z Wiki (https://cs.wikipedia.org/wiki/Seznam_n%C4%9Bmeck%C3%BDch_n%C3%A1zv%C5%AF_obc%C3%AD_a_osad_v_%C4%8Cesku).

Použité pro *German Terminology*.

- `id` = id obce
- `name_cz` = název obce česky
- `name_de` = název obce německy
- `district_id` = id okresu (FK do tabulky `district.csv`)
- `note` = poznámka k obci, zatím v češtině, možno v budoucnu přeložit a použít

----------------------------

### `ln_count.csv`

Četnost příjmení podle obce s rozšířenou působností.

Zdroj: MVČR.

Použité pro *Name Distribution*. 

- `name_id` = id příjmení (FK do tabulky `last_name.csv`)
- `mep_id` = id obce s rozšířenou působností (FK do tabulky `mep.csv`)
- `count` = četnost

----------------------------

### `fn_translation.csv`

Anglická jména a jejich české překlady.

Použité pro *Changing Names*. 

- `name_en` = jméno anglicky
- `name_cz` = jméno česky
- `priority` = priorita překladu (menší číslo -> větší priorita) \<integer\>
- (`id`) = generováno automaticky v DB (autoincrement)

----------------------------

### `ln_explanation.csv`

Příjmení a jejich význam.

Použité pro *Behind the Name*. 

- `name` = jméno česky
- `explanation` = vysvětlení významu
- (`id`) = generováno automaticky v DB (autoincrement)

----------------------------

### `fn_diminutive.csv`

Jména -> jejich zdrobněliny.

Použité pro *Behind the Name*. 

- `name` = jméno
- `diminutive` = zdrobnělina
- (`id`) = generováno automaticky v DB (autoincrement)

----------------------------