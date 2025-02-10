<?php


/**
 * Class GT_Container server as a dependency injection container class
 * for managing service instances. It is implemented as a singleton.
 */
class GT_Container {


	//region ------- Services keys -------

	private const CONFIG = 'config';
	private const DATABASE = 'database';

	private const MEP_DAO = 'mep_dao';
	private const REGION_DAO = 'region_dao';
	private const LN_COUNT_DAO = 'ln_count_dao';
	private const LN_DAO = 'ln_dao';
	private const FN_DAO = 'fn_dao';
	private const CITY_DAO = 'city_dao';
	private const DISTRICT_DAO = 'district_dao';
	private const FN_TRANSLATION_DAO = 'fn_translation_dao';
    private const EN_CZ_TRANSLATION_DAO = 'en_cz_translation_dao';
	private const FN_DIMINUTIVE_DAO = 'fn_diminutive_dao';
	private const LN_EXPLANATION_DAO = 'ln_explanation_dao';

	private const TRANSCRIPTION_SERVICE = 'ln_transcription_service';
	private const FEMALE_VARIANT_SERVICE = 'female_variant_service';
	private const NAME_DISTRIBUTION_SERVICE = 'name_distribution_service';

	//endregion
    private const LA_CZ_TRANSLATION_DAO = 'la_cz_translation_dao';

    /**
	 * @var ?GT_Container Singleton instance of this class.
	 */
	protected static ?GT_Container $instance = null;

	/**
	 * @var array Tracked instances storage.
	 */
	protected array $objects;

	/**
	 * GT_Container constructor.
	 * Protected access to disallow instantiation.
	 */
	protected function __construct() {
		$this->objects = [];
	}

	/**
	 * Get the singleton instance of this class.
	 *
	 * @return GT_Container
	 */
	public static function instance(): GT_Container {
		if ( ! static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}


	//region ------- DAOs -------

	/**
	 * Get the config instance.
	 *
	 * @return GT_Config
	 */
	public function get_config(): GT_Config {
		if ( ! isset( $this->objects[ self::CONFIG ] ) ) {
			// create config instance
			$this->objects[ self::CONFIG ] = new GT_Config();
		}

		return $this->objects[ self::CONFIG ];
	}

	/**
	 * Get the database instance.
	 *
	 * @return GT_Database
	 */
	public function get_database(): GT_Database {
		if ( ! isset( $this->objects[ self::DATABASE ] ) ) {
			// get plugin config
			$config    = $this->get_config();
			$db_config = $config->get_db_config();

			// get db config
			$host         = $db_config[ GT_Config::KEY_DB_HOST ];
			$database     = $db_config[ GT_Config::KEY_DB_DATABASE ];
			$username     = $db_config[ GT_Config::KEY_DB_USERNAME ];
			$password     = $db_config[ GT_Config::KEY_DB_PASSWORD ];
			$table_prefix = $db_config[ GT_Config::KEY_DB_TABLE_PREFIX ];

			// create db instance
			$this->objects[ self::DATABASE ] = new GT_Database( $host, $database, $username, $password, $table_prefix );
		}

		return $this->objects[ self::DATABASE ];
	}

	/**
	 * Get the first name translations DAO.
	 *
	 * @return GT_City_DAO
	 */
	public function get_city_dao(): GT_City_DAO {
		if ( ! isset( $this->objects[ self::CITY_DAO ] ) ) {
			$database = $this->get_database();

			// create DAO instance
			$this->objects[ self::CITY_DAO ] = new GT_City_DAO( $database );
		}

		return $this->objects[ self::CITY_DAO ];
	}

	/**
	 * Get the first name translations DAO.
	 *
	 * @return GT_FN_Translation_DAO
	 */
	public function get_fn_translation_dao(): GT_FN_Translation_DAO {
		if ( ! isset( $this->objects[ self::FN_TRANSLATION_DAO ] ) ) {
			$database = $this->get_database();

			// create DAO instance
			$this->objects[ self::FN_TRANSLATION_DAO ] = new GT_FN_Translation_DAO( $database );
		}

		return $this->objects[ self::FN_TRANSLATION_DAO ];
	}

	/**
	 * Get the first name diminutives DAO.
	 *
	 * @return GT_FN_Diminutive_DAO
	 */
	public function get_fn_diminutive_dao(): GT_FN_Diminutive_DAO {
		if ( ! isset( $this->objects[ self::FN_DIMINUTIVE_DAO ] ) ) {
			$database = $this->get_database();

			// create DAO instance
			$this->objects[ self::FN_DIMINUTIVE_DAO ] = new GT_FN_Diminutive_DAO( $database );
		}

		return $this->objects[ self::FN_DIMINUTIVE_DAO ];
	}

	/**
	 * Get the first name diminutives DAO.
	 *
	 * @return GT_LN_Explanation_DAO
	 */
	public function get_ln_explanation_dao(): GT_LN_Explanation_DAO {
		if ( ! isset( $this->objects[ self::LN_EXPLANATION_DAO ] ) ) {
			$database = $this->get_database();

			// create DAO instance
			$this->objects[ self::LN_EXPLANATION_DAO ] = new GT_LN_Explanation_DAO( $database );
		}

		return $this->objects[ self::LN_EXPLANATION_DAO ];
	}

	/**
	 * Get the last names DAO.
	 *
	 * @return GT_LN_DAO
	 */
	public function get_ln_dao(): GT_LN_DAO {
		if ( ! isset( $this->objects[ self::LN_DAO ] ) ) {
			$database = $this->get_database();

			// create DAO instance
			$this->objects[ self::LN_DAO ] = new GT_LN_DAO( $database );
		}

		return $this->objects[ self::LN_DAO ];
	}

	/**
	 * Get the first names DAO.
	 *
	 * @return GT_FN_DAO
	 */
	public function get_fn_dao(): GT_FN_DAO {
		if ( ! isset( $this->objects[ self::FN_DAO ] ) ) {
			$database = $this->get_database();

			// create DAO instance
			$this->objects[ self::FN_DAO ] = new GT_FN_DAO( $database );
		}

		return $this->objects[ self::FN_DAO ];
	}

	/**
	 * Get the municipalities with extended powers (MEP) DAO.
	 *
	 * @return GT_MEP_DAO
	 */
	public function get_mep_dao(): GT_MEP_DAO {
		if ( ! isset( $this->objects[ self::MEP_DAO ] ) ) {
			$database = $this->get_database();

			// create DAO instance
			$this->objects[ self::MEP_DAO ] = new GT_MEP_DAO( $database );
		}

		return $this->objects[ self::MEP_DAO ];
	}

	/**
	 * Get the names distributions DAO.
	 *
	 * @return GT_LN_Count_DAO
	 */
	public function get_ln_count_dao(): GT_LN_Count_DAO {
		if ( ! isset( $this->objects[ self::LN_COUNT_DAO ] ) ) {
			$database = $this->get_database();

			// create DAO instance
			$this->objects[ self::LN_COUNT_DAO ] = new GT_LN_Count_DAO( $database );
		}

		return $this->objects[ self::LN_COUNT_DAO ];
	}

	/**
	 * Get the regions DAO.
	 *
	 * @return GT_Region_DAO
	 */
	public function get_region_dao(): GT_Region_DAO {
		if ( ! isset( $this->objects[ self::REGION_DAO ] ) ) {
			$database = $this->get_database();

			// create DAO instance
			$this->objects[ self::REGION_DAO ] = new GT_Region_DAO( $database );
		}

		return $this->objects[ self::REGION_DAO ];
	}

	/**
	 * Get the districts DAO.
	 *
	 * @return GT_District_DAO
	 */
	public function get_district_dao(): GT_District_DAO {
		if ( ! isset( $this->objects[ self::DISTRICT_DAO ] ) ) {
			$database = $this->get_database();

			// create DAO instance
			$this->objects[ self::DISTRICT_DAO ] = new GT_District_DAO( $database );
		}

		return $this->objects[ self::DISTRICT_DAO ];
	}
    public function get_cz_en_transtation_dao(): GT_EN_CZ_translation_DAO {
        if( ! isset( $this->objects[ self:: EN_CZ_TRANSLATION_DAO ] ) ) {

            $database = $this->get_database();
            $this->objects[self::EN_CZ_TRANSLATION_DAO] = new GT_EN_CZ_translation_DAO( $database );
        }



        return $this->objects[ self::EN_CZ_TRANSLATION_DAO ];
    }
    public function get_la_cz_translation_dao(): GT_LA_CZ_translation_DAO {
        if( ! isset( $this->objects[ self:: LA_CZ_TRANSLATION_DAO ] ) ) {
            $database = $this->get_database();
            $this->objects[self::LA_CZ_TRANSLATION_DAO] = new GT_LA_CZ_translation_DAO( $database );
        }

        return $this->objects[ self::LA_CZ_TRANSLATION_DAO ];
    }


	//endregion


	//region ------- Services -------

	/**
	 * Get the name transcriptions service.
	 *
	 * @return GT_Transcription_Service
	 */
	public function get_transcription_service(): GT_Transcription_Service {
		if ( ! isset( $this->objects[ self::TRANSCRIPTION_SERVICE ] ) ) {
			$first_name_dao = $this->get_fn_dao();
			$last_name_dao  = $this->get_ln_dao();

			// create service instance
			$this->objects[ self::TRANSCRIPTION_SERVICE ] = new GT_Transcription_Service( $first_name_dao, $last_name_dao );
		}

		return $this->objects[ self::TRANSCRIPTION_SERVICE ];
	}

	/**
	 * Get the female variant service.
	 *
	 * @return GT_Female_Variant_Service
	 */
	public function get_female_variant_service(): GT_Female_Variant_Service {
		if ( ! isset( $this->objects[ self::FEMALE_VARIANT_SERVICE ] ) ) {
			$last_name_dao = $this->get_ln_dao();

			// create service instance
			$this->objects[ self::FEMALE_VARIANT_SERVICE ] = new GT_Female_Variant_Service( $last_name_dao );
		}

		return $this->objects[ self::FEMALE_VARIANT_SERVICE ];
	}

	/**
	 * Get the name distribution service.
	 *
	 * @return GT_Name_Distribution_Service
	 */
	public function get_name_distribution_service(): GT_Name_Distribution_Service {
		if ( ! isset( $this->objects[ self::NAME_DISTRIBUTION_SERVICE ] ) ) {
			$ln_dao       = $this->get_ln_dao();
			$ln_count_dao = $this->get_ln_count_dao();
			$mep_dao      = $this->get_mep_dao();
			$region_dao   = $this->get_region_dao();

			// create service instance
			$this->objects[ self::NAME_DISTRIBUTION_SERVICE ] = new GT_Name_Distribution_Service( $ln_dao, $ln_count_dao, $mep_dao, $region_dao );
		}

		return $this->objects[ self::NAME_DISTRIBUTION_SERVICE ];
	}

	//endregion
}