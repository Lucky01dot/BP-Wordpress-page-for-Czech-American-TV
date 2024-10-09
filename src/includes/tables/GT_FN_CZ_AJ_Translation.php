<?php


class GT_FN_CZ_AJ_Translation extends GT_Table {

    public string $id = "id";

    public string $name_en = "name_en";

    public string $name_cz = "name_cz";

    public function create(wpdb $wpdb): bool
    {
        $query = "
        CREATE TABLE IF NOT EXISTS `{$this->_tablename_}` (
			`{$this->id}` INT UNSIGNED AUTO_INCREMENT NOT NULL,
            `{$this->name_en}` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
			`{$this->name_cz}` varchar(100) NOT NULL,
			CONSTRAINT `PK_{$this->_tablename_}` PRIMARY KEY (`{$this->id}`),
			INDEX `IX_{$this->_tablename_}_{$this->name_en}` USING BTREE (`{$this->name_en}`),
			INDEX `IX_{$this->_tablename_}_{$this->name_cz}` USING BTREE (`{$this->name_cz}`)
		)
		ENGINE=InnoDB
		DEFAULT CHARSET=utf8mb4
		COLLATE=utf8mb4_czech_ci;
		";

        return $wpdb->query($query);
    }

    public function drop(wpdb $wpdb): bool
    {
        $query = " DROP TABLE IF EXISTS `{this->_tablename_}`";
        return $wpdb->query($query);
    }
}