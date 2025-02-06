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
        $result = array();
        $table = $this->db->en_cz_translation_table;

        $query = "SELECT * FROM `{$table->_tablename_}`;";
        $all_records = $this->db->wpdb->get_results($query);

        foreach ($all_records as $record) {
            array_push($result, array(
                'czech_word' => $record->czech_word,
                'english_translation' => $record->english_translation
            ));
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

        $query = "SELECT COUNT(*) FROM `{$table->_tablename_}`;";

        return array( $table->_tablename_,$this->db->wpdb->get_row($query));
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
