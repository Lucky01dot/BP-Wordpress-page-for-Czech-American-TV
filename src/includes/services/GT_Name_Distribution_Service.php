<?php


/**
 * Service for creating names distribution complex datasets.
 */
class GT_Name_Distribution_Service {
	/**
	 * @var GT_LN_DAO DAO for last names.
	 */
	private GT_LN_DAO $ln_dao;


	/**
	 * @var GT_LN_Count_DAO DAO for last names counts.
	 */
	private GT_LN_Count_DAO $ln_count_dao;

	/**
	 * @var GT_MEP_DAO DAO for MEPs.
	 */
	private GT_MEP_DAO $mep_dao;

	/**
	 * @var GT_Region_DAO DAO for regions.
	 */
	private GT_Region_DAO $region_dao;

	/**
	 * GT_Name_Distribution_Service constructor.
	 *
	 * @param GT_LN_Count_DAO $ln_count_dao DAO for last names counts.
	 * @param GT_MEP_DAO $mep_dao DAO for MEPs.
	 * @param GT_Region_DAO $region_dao DAO for regions.
	 */
	public function __construct( GT_LN_DAO $ln_dao, GT_LN_Count_DAO $ln_count_dao, GT_MEP_DAO $mep_dao, GT_Region_DAO $region_dao ) {
		$this->ln_dao       = $ln_dao;
		$this->ln_count_dao = $ln_count_dao;
		$this->mep_dao      = $mep_dao;
		$this->region_dao   = $region_dao;
	}

	/**
	 * Gets names distribution info for region.
	 *
	 * @param int $mep_id The id of region.
	 *
	 * @return array|null
	 */
	public function get_region_info( int $region_id ): ?array {
		$region = $this->region_dao->get_region_by_id( $region_id );

		if ( $region === null ) {
			return null;
		}

		$meps = $this->mep_dao->get_meps_by_region_order_by_name_frequency( $region_id );

		$result = array(
			"name_cz"  => $region->name_cz,
			"name_en"  => $region->name_en,
			"map_code" => $region->map_code,
			"meps"     => $meps
		);

		return $result;
	}

	/**
	 * Gets names distribution info for MEP.
	 *
	 * @param int $mep_id The id of MEP.
	 *
	 * @return array|null
	 */
	public function get_mep_info( int $mep_id ): ?array {
		$mep = $this->mep_dao->get_mep_by_id( $mep_id );

		if ( $mep === null ) {
			return null;
		}

		$region        = $this->region_dao->get_region_by_id( $mep->region_id );
		$popular_names = $this->ln_count_dao->get_popular_names_by_mep( $mep_id );

		$result = array(
			"name_cz"       => $mep->name_cz,
			"name_de"       => $mep->name_de,
			"region_name"   => $region->name_en,
			"region_id"     => $region->id,
			"lat"           => $mep->lat,
			"lng"           => $mep->lng,
			"popular_names" => $popular_names
		);

		return $result;
	}


	/**
	 * Gets name distribution info.
	 *
	 * @param int $name_id The id of name.
	 *
	 * @return array|null
	 */
	public function get_name_info( int $name_id ): ?array {
		$name = $this->ln_dao->get_name_by_id( $name_id );

		if ( $name === null ) {
			return null;
		}

		$regions       = $this->region_dao->get_all_regions();
		$meps          = $this->mep_dao->get_all_meps();
		$region_counts = $this->ln_count_dao->get_counts_in_regions_by_name( $name_id );
		$mep_counts    = $this->ln_count_dao->get_counts_in_meps_by_name( $name_id );

		$result = [
			"name"    => $name->name,
			"regions" => []
		];

		$total_count = 0;

		// add regions and their name counts into result
		foreach ( $region_counts as $region_count ) {
			$region        = $regions[ $region_count->region_id ];
			$region->count = $region_count->count;

			// init empty array for MEPs
			$region->meps = [];

			$total_count                      += $region_count->count;
			$result["regions"][ $region->id ] = $region;
		}

		// add MEPs and their name counts into result
		foreach ( $mep_counts as $mep_count ) {
			$mep        = $meps[ $mep_count->mep_id ];
			$mep->count = $mep_count->count;

			$result["regions"][ $mep->region_id ]->meps[ $mep->mep_id ] = $mep;
		}

		// add name total count
		$result["count"] = $total_count;

		return $result;
	}

	/**
	 * Gets name distribution info for region.
	 *
	 * @param int $name_id The id of name.
	 * @param int $region_id The id of region.
	 *
	 * @return array|null
	 */
	public function get_name_in_region_info( int $name_id, int $region_id ): ?array {
		$name   = $this->ln_dao->get_name_by_id( $name_id );
		$region = $this->region_dao->get_region_by_id( $region_id );


		if ( $name === null || $region === null ) {
			return null;
		}

		$region_count = $this->ln_count_dao->get_counts_in_region_by_name_and_region( $name_id, $region_id );
		$meps         = $this->mep_dao->get_meps_by_region( $region_id );
		$mep_counts   = $this->ln_count_dao->get_counts_in_meps_by_name_and_region( $name_id, $region_id );

		$result = [
			"name"    => $name->name,
			"regions" => [
				$region->id => $region
			],
			"count"   => $region_count->count,
		];

		// add total region count
		$result["regions"][ $region->id ]->count = $region_count->count;

		// add MEPs and their name counts into result
		$result["regions"][ $region_id ]->meps = [];
		foreach ( $mep_counts as $mep_count ) {
			$mep        = $meps[ $mep_count->mep_id ];
			$mep->count = $mep_count->count;

			$result["regions"][ $region->id ]->meps[ $mep->id ] = $mep;
		}

		return $result;
	}

	/**
	 * Gets name distribution info for MEP.
	 *
	 * @param int $name_id The id of name.
	 * @param int $mep_id The id of MEP.
	 *
	 * @return array|null
	 */
	public function get_name_in_mep_info( int $name_id, int $mep_id ): ?array {
		$name   = $this->ln_dao->get_name_by_id( $name_id );
		$mep = $this->mep_dao->get_mep_by_id( $mep_id );

		if ( $name === null || $mep === null ) {
			return null;
		}

		$region = $this->region_dao->get_region_by_id( $mep->region_id );
		$mep_count   = $this->ln_count_dao->get_counts_in_mep_by_name_and_mep( $name_id, $mep_id );

		$result = [
			"name"    => $name->name,
			"regions" => [
				$region->id => $region
			],
			"count"   => $mep_count->count,
		];

		// add total region count
		$result["regions"][ $region->id ]->count = $mep_count->count;

		// add MEP and its name count into result
		$result["regions"][ $region->id ]->meps = [
			$mep->id => $mep
		];
		$result["regions"][ $region->id ]->meps[ $mep->id ]->count = $mep_count->count;

		return $result;
	}
}
