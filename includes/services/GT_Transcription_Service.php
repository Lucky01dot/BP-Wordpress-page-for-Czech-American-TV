<?php


/**
 * Service for transcription between english name and czech variants.
 */
class GT_Transcription_Service {
	/**
	 * @var array Transcription rules `[[from, to], ...]`.
	 */
	protected array $rules;

	/**
	 * @var GT_FN_DAO DAO for first names.
	 */
	private GT_FN_DAO $first_name_DAO;

	/**
	 * @var GT_LN_DAO DAO for last names.
	 */
	private GT_LN_DAO $last_name_DAO;

	/**
	 * GT_Transcription_Service constructor.
	 *
	 * @param GT_FN_DAO $first_name_DAO DAO for first names.
	 * @param GT_LN_DAO $last_name_DAO DAO for last names.
	 */
	public function __construct( GT_FN_DAO $first_name_DAO, GT_LN_DAO $last_name_DAO ) {
		$this->rules          = $this->load_rules( plugin_dir_path( __FILE__ ) . "rules.txt" );
		$this->first_name_DAO = $first_name_DAO;
		$this->last_name_DAO  = $last_name_DAO;
	}

	/**
	 * Loads rules from the given file.
	 * All rules are converted to uppercase.
	 *
	 * @param string $rules_path Path to the rules file.
	 *
	 * @return array All loaded rules.
	 */
	private function load_rules( string $rules_path ): array {
		$rules_file = file( $rules_path );
		$rules      = [];

		foreach ( $rules_file as $rule ) {
			if ( mb_substr( $rule, 0, 1 ) == "#" || mb_strlen( $rule ) <= 1 ) {
				// comment line
				continue;
			}
			$part = explode( ">", $rule );
			array_push( $rules, [ mb_strtoupper( trim( $part[0] ) ), mb_strtoupper( trim( $part[1] ) ) ] );
		}

		return $rules;
	}

	/**
	 * Transcribe the first name from english into all possible czech variants.
	 *
	 * @param string $name English name.
	 *
	 * @return array Array of all transcriptions.
	 */
	public function get_fn_transcriptions( string $name ): array {
		return $this->transcribe( $name, $this->first_name_DAO );
	}

	/**
	 * Transcribe the last name from english into all possible czech variants.
	 *
	 * @param string $name English name.
	 *
	 * @return array All transcriptions - array of objects with fields `id` and `name`.
	 */
	public function get_ln_transcriptions( string $name ): array {
		return $this->transcribe( $name, $this->last_name_DAO );
	}

	/**
	 * Transcribe the name from english into all possible czech variants.
	 * During the process the string is converted to uppercase.
	 *
	 * @param string $string English string.
	 *
	 * @return array Array of all transcriptions.
	 */
	private function transcribe( string $string, GT_I_Name_DAO $dao ): array {
		$string = mb_strtoupper( $string );

		$stack = [ [ $string, 0 ] ];
		$names = [];

		// search through a tree of transcriptions
		while ( ! empty( $stack ) ) {
			$item   = array_pop( $stack );
			$string = $item[0];
			$ptr    = $item[1];

			// find a name matching the string
			if ( ! isset( $names[ $string ] ) ) {
				$name = $dao->get_name_by_name( $string );

				if ( $name ) {
					$names[ $string ] = $name;
				}
			}

			// try to apply all rules
			foreach ( $this->rules as $rule ) {
				$from = $rule[0];
				$to   = $rule[1];
				$pos  = mb_strpos( $string, $from, $ptr );

				if ( $pos !== false ) {
					// rule found

					$left  = mb_substr( $string, 0, $pos );
					$right = mb_substr( $string, $pos + mb_strlen( $from ) );

					$prefix        = $left . $to;
					$transcription = $prefix . $right;
					$prefix_len    = mb_strlen( $prefix );

					if ( $dao->get_number_of_names_starting_with( $prefix ) > 0 ) {
						// prefix exists in names
						array_push( $stack, [ $transcription, $prefix_len ] );
					}
				}
			}
		}

		$names = array_values($names);

		// sort names by count
		usort($names, function($a, $b) {return intval($b->count) - intval($a->count);});

		return $names;
	}
}
