<?php


/**
 * Data access object for translations.
 */
class GT_FN_EN_CZ_translation_DAO extends GT_DAO {
    public function get_translations_cz_to_en( string $czech_word ): ?array {
        $table = $this->db->fn_translation_table;

        $query = $this->db->wpdb->prepare( "
            SELECT `{$table->english_translation}` AS `name`
            FROM `{$table->_tablename_}`
            WHERE `{$table->czech_word}`=%s COLLATE utf8mb4_bin;
        ", $czech_word );

        return $this->db->wpdb->get_results( $query );
    }
    public function add_translation( string $czech_word, string $english_translation ): bool {
        $table = $this->db->fn_translation_table;

        // Insert a new translation entry
        return $this->db->wpdb->insert(
                $table->_tablename_,
                array(
                    'czech_word'         => $czech_word,
                    'english_translation' => $english_translation
                ),
                array(
                    '%s',
                    '%s'
                )
            ) !== false;
    }




    /**
     * Get number of records from table
     *
     * @return array|null array with table name and records count
     */
    public function get_records_count(): ?array {
        $table = $this->db->fn_translation_table;

        $query = $this->db->wpdb->prepare( "
			SELECT COUNT(*)
			FROM `{$table->_tablename_}`;
		" );

        return array( $table->_tablename_, $this->db->wpdb->get_row( $query ) );
    }


}