<?php

/**
 * Class GT_DAO is base class for data access objects.
 */
abstract class GT_DAO {
	/**
	 * @var GT_Database Database context.
	 */
	public GT_Database $db;

	/**
	 * GT_DAO constructor.
	 *
	 * @param GT_Database $db Database context
	 */
	public function __construct( GT_Database $db ) {
		$this->db = $db;
	}
}