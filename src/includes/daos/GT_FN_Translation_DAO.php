<?php

/**
 * Data access object for last name translations.
 */
class GT_FN_Translation_DAO extends GT_DAO {

    /**
     * Gets all English translations by prefix for autocomplete.
     *
     * @param string $prefix Prefix of English word.
     *
     * @return array|null Array of objects {`name`}. Null on error.
     */
    public function get_names_en_by_prefix( string $prefix ): ?array {
        $table = $this->db->fn_translation_table;

        $query = $this->db->wpdb->prepare( "
            SELECT DISTINCT `{$table->english_translation}` as `name`
            FROM `{$table->_tablename_}`
            WHERE `{$table->english_translation}` LIKE %s
            ORDER BY CHAR_LENGTH(`{$table->english_translation}`)
            LIMIT 10;
        ", $prefix . "%" );

        return $this->db->wpdb->get_results( $query );
    }

    /**
     * Gets all Czech translations by prefix for autocomplete.
     *
     * @param string $prefix Prefix of Czech word.
     *
     * @return array|null Array of objects {`name`}. Null on error.
     */
    public function get_names_cz_by_prefix( string $prefix ): ?array {
        $table = $this->db->fn_translation_table;

        $query = $this->db->wpdb->prepare( "
            SELECT DISTINCT `{$table->czech_word}` as `name`
            FROM `{$table->_tablename_}`
            WHERE `{$table->czech_word}` LIKE %s COLLATE utf8mb4_general_ci
            ORDER BY CHAR_LENGTH(`{$table->czech_word}`)
            LIMIT 10;
        ", $prefix . "%" );

        return $this->db->wpdb->get_results( $query );
    }

    /**
     * Gets all Czech translations from a given English word.
     *
     * @param string $english_translation Word in English.
     *
     * @return array|null Array of objects {`name`}. Null on error.
     */
    public function get_translations_en_to_cz( string $english_translation ): ?array {
        $table = $this->db->fn_translation_table;

        $query = $this->db->wpdb->prepare( "
            SELECT `{$table->czech_word}` AS `name`
            FROM `{$table->_tablename_}`
            WHERE `{$table->english_translation}`=%s;
        ", $english_translation );

        return $this->db->wpdb->get_results( $query );
    }

    /**
     * Gets all English translations from a given Czech word.
     *
     * @param string $czech_word Word in Czech.
     *
     * @return array|null Array of objects {`name`}. Null on error.
     */
    public function get_translations_cz_to_en( string $czech_word ): ?array {
        $table = $this->db->fn_translation_table;

        $query = $this->db->wpdb->prepare( "
            SELECT `{$table->english_translation}` AS `name`
            FROM `{$table->_tablename_}`
            WHERE `{$table->czech_word}`=%s COLLATE utf8mb4_bin;
        ", $czech_word );

        return $this->db->wpdb->get_results( $query );
    }

    /**
     * Inserts record into the DB.
     *
     * @param array $columns Array with values (czech_word, english_translation).
     *
     * @return bool True on success, false otherwise.
     */
    public function insert( array $columns ): bool {
        $table = $this->db->fn_translation_table->_tablename_;

        if ( count( $columns ) != 2 ) {
            return false;
        }

        $result = $this->db->wpdb->insert( $table, array(
            'czech_word' => $columns[0],
            'english_translation' => $columns[1]
        ) );

        return $result !== false;
    }

    /**
     * Get number of records in the table.
     *
     * @return array|null Array with table name and records count.
     */
    public function get_records_count(): ?array {
        $table = $this->db->fn_translation_table;

        $query = "SELECT COUNT(*) FROM `{$table->_tablename_}`;";

        return array( $table->_tablename_, $this->db->wpdb->get_row( $query ) );
    }

    /**
     * Get all records from the table.
     * @return array|null
     */
    public function get_all_records(): ?array {
        $result = array();
        $table = $this->db->fn_translation_table;

        $query = "SELECT * FROM `{$table->_tablename_}`;";

        $all_records = $this->db->wpdb->get_results( $query );

        // Reformat records.
        foreach ( $all_records as $record ) {
            array_push( $result, array(
                'czech_word' => $record->czech_word,
                'english_translation' => $record->english_translation
            ) );
        }

        return $result;
    }
}
