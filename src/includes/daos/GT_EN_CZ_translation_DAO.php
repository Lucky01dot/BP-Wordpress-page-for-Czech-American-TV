<?php

/**
 * Data access object for translations.
 */
class GT_EN_CZ_translation_DAO extends GT_DAO {

    /**
     * Get translations from Czech to English.
     *
     * @param string $czech_word
     * @return array|null
     */
    public function get_translations_cz_to_en(string $czech_word): ?array {
        $table = $this->db->en_cz_translation_table;


        $query = $this->db->wpdb->prepare("
            SELECT DISTINCT `{$table->english_translation}` AS `word_en`
            FROM `{$table->_tablename_}`
            WHERE `{$table->czech_word}` = %s;
        ", $czech_word);



        return $this->db->wpdb->get_results($query);
    }

    /**
     * Add a new translation entry.
     *
     * @param string $czech_word
     * @param string $english_translation
     * @return bool
     */
    public function add_translation(string $czech_word, string $english_translation): bool {
        $table = $this->db->en_cz_translation_table;

        return $this->db->wpdb->insert(
                $table->_tablename_,
                array(
                    'czech_word' => $czech_word,
                    'english_translation' => $english_translation
                ),
                array('%s', '%s')
            ) !== false;
    }

    /**
     * Insert a new translation using an array of columns.
     *
     * @param array $columns
     * @return bool
     */
    public function insert(array $columns): bool {
        $table = $this->db->en_cz_translation_table->_tablename_;

        if (count($columns) !== 2) {
            return false;
        }

        $result = $this->db->wpdb->insert(
            $table,
            array(
                'czech_word' => $columns[0],
                'english_translation' => $columns[1]
            )
        );

        return $result !== false;
    }

    /**
     * Retrieve all translation records.
     *
     * @return array|null
     */
    public function get_all_records(): ?array {
        $table = $this->db->en_cz_translation_table;

        $query = "SELECT `id`, `czech_word`, `english_translation` FROM `{$table->_tablename_}`;";
        $all_records = $this->db->wpdb->get_results($query);

        if (!$all_records) {
            return null;
        }

        $result = array();
        foreach ($all_records as $record) {
            $result[] = array(
                'id' => $record->id,
                'czech_word' => $record->czech_word,
                'english_translation' => $record->english_translation
            );
        }

        return $result;
    }

    /**
     * Get the total number of records in the table.
     *
     * @return array|null
     */
    public function get_records_count(): ?array {
        $table = $this->db->en_cz_translation_table;

        $query = "SELECT COUNT(*) AS `count` FROM `{$table->_tablename_}`;";
        $result = $this->db->wpdb->get_row($query);

        if (!$result) {
            return null;
        }

        return array(
            'table_name' => $table->_tablename_,
            'count' => $result->count
        );
    }

    /**
     * Get Czech words by prefix for translation.
     *
     * @param string $prefix Prefix for the Czech word.
     * @return array|null Array of matching Czech words or null if no match.
     */
    public function get_word_cz_by_prefix(string $prefix): ?array {
        $table = $this->db->en_cz_translation_table;


        $query = $this->db->wpdb->prepare("
        SELECT DISTINCT `{$table->czech_word}` AS `word_cz`
        FROM `{$table->_tablename_}`
        WHERE `{$table->czech_word}` LIKE %s
        ORDER BY CHAR_LENGTH(`{$table->czech_word}`) ASC
        LIMIT 10;
    ", $prefix . "%");

        return $this->db->wpdb->get_results($query);
    }

}
