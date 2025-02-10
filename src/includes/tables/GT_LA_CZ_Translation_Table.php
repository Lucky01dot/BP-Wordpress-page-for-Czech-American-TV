<?php

class GT_LA_CZ_Translation_Table extends GT_Table {

    public string $id = "id";
    public string $latin_word = "latin_word";
    public string $czech_translation = "czech_translation";

    public function create(wpdb $wpdb): bool
    {
        $query = "
        CREATE TABLE IF NOT EXISTS `{$this->_tablename_}` (
            `{$this->id}` INT UNSIGNED AUTO_INCREMENT NOT NULL,
            `{$this->latin_word}` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
            `{$this->czech_translation}` varchar(100) NOT NULL,
            CONSTRAINT `PK_{$this->_tablename_}` PRIMARY KEY (`{$this->id}`),
            INDEX `IX_{$this->_tablename_}_{$this->latin_word}` USING BTREE (`{$this->latin_word}`),
            INDEX `IX_{$this->_tablename_}_{$this->czech_translation}` USING BTREE (`{$this->czech_translation}`)
        )
        ENGINE=InnoDB
        DEFAULT CHARSET=utf8mb4
        COLLATE=utf8mb4_czech_ci;
        ";

        return (bool) $wpdb->query($query);
    }

    public function drop(wpdb $wpdb): bool
    {
        $query = "DROP TABLE IF EXISTS `{$this->_tablename_}`";
        return (bool) $wpdb->query($query);
    }
}
