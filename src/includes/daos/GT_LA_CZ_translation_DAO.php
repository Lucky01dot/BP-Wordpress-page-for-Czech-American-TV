<?php


class  GT_LA_CZ_translation_DAO extends GT_DAO {
    public function get_translations_la_to_cz(string $latin_word): ?array {
        $table = $this->db->la_cz_translation_table;

        $query = $this->db->wpdb->prepare("
        SELECT DISTINCT `{$table->czech_translation}` AS `word_cz`
        FROM `{$table->_tablename_}`
        WHERE `{$table->latin_word}` = %s;
    ", $latin_word);

        return $this->db->wpdb->get_results($query, ARRAY_A);
    }

    public function insert(array $columns): bool {
        $table = $this->db->la_cz_translation_table->_tablename_;

        if (count($columns) !== 2) {
            return false;
        }

        $result = $this->db->wpdb->insert(
            $table,
            array(
                'latin_word' => $columns[0],
                'czech_translation' => $columns[1]
            )
        );

        return $result !== false;
    }
    public function get_all_records(): ?array {
        $result = array();
        $table = $this->db->la_cz_translation_table;

        $query = "SELECT * FROM `{$table->_tablename_}`;";
        $all_records = $this->db->wpdb->get_results($query);

        foreach ($all_records as $record) {
            array_push($result, array(
                'latin_word' => $record->latin_word,
                'czech_translation' => $record->czech_translation
            ));
        }

        return $result;
    }
    public function get_records_count(): ?array {
        $table = $this->db->la_cz_translation_table;

        $query = "SELECT COUNT(*) FROM `{$table->_tablename_}`;";

        return array( $table->_tablename_,$this->db->wpdb->get_row($query));
    }
    public function get_word_la_by_prefix(string $prefix): ?array {
        $table = $this->db->la_cz_translation_table;


        $query = $this->db->wpdb->prepare("
        SELECT DISTINCT `{$table->latin_word}` AS `word_la`
        FROM `{$table->_tablename_}`
        WHERE `{$table->latin_word}` LIKE %s
        ORDER BY CHAR_LENGTH(`{$table->latin_word}`) ASC
        LIMIT 10;
    ", $prefix . "%");

        return $this->db->wpdb->get_results($query);
    }








}
