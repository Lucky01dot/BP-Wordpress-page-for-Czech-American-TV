<?php


/**
 * Service for finding female variants of czech names.
 */
class GT_Female_Variant_Service {

	/**
	 * @var GT_LN_DAO DAO for last names.
	 */
	private GT_LN_DAO $last_name_DAO;

	/**
	 * GT_Female_Variant_Service constructor.
	 *
	 * @param GT_LN_DAO $last_name_DAO DAO for last names.
	 */
	public function __construct( GT_LN_DAO $last_name_DAO ) {
		$this->last_name_DAO = $last_name_DAO;
	}

	/**
	 * Finds the female variant of a given name.
	 * The rules used can be found here: {@link http://nase-rec.ujc.cas.cz/archiv.php?art=6153}.
	 *
	 * @param string $name The male name to find female variant to.
	 */
	public function get_last_name_female_variants( string $name ): array {
		$name     = mb_strtoupper( $name );
		$variants = array();

		// ---- Czech names ----

		//I
		//I.A
		//I.A.1
		array_push( $variants, $name . 'OVÁ' );

		//I.A.2
		//I.A.2.a - same as I.A.1
		//I.A.2.ba
		$variant = preg_replace( "/EC$/u", "COVÁ", $name );
		if ( $variant != $name ) {
			array_push( $variants, $variant );
		}

		//I.A.2.bb
		$variant = preg_replace( "/EK$/u", "KOVÁ", $name );
		if ( $variant != $name ) {
			array_push( $variants, $variant );
		}
		$variant = preg_replace( "/DĚK$/u", "ĎKOVÁ", $name );
		if ( $variant != $name ) {
			array_push( $variants, $variant );
		}
		$variant = preg_replace( "/TĚK$/u", "ŤKOVÁ", $name );
		if ( $variant != $name ) {
			array_push( $variants, $variant );
		}
		$variant = preg_replace( "/NĚK$/u", "ŇKOVÁ", $name );
		if ( $variant != $name ) {
			array_push( $variants, $variant );
		}

		//I.A.2.bc
		$variant = preg_replace( "/EŠ$/u", "ŠOVÁ", $name );
		if ( $variant != $name ) {
			array_push( $variants, $variant );
		}

		//I.A.2.bd
		$variant = preg_replace( "/EL$/u", "LOVÁ", $name );
		if ( $variant != $name ) {
			array_push( $variants, $variant );
		}
		$variant = preg_replace( "/EV$/u", "VOVÁ", $name );
		if ( $variant != $name ) {
			array_push( $variants, $variant );
		}

		//I.B
		$variant = preg_replace( "/([AEĚIYOUÁÉÍÝÓŮ]|OU|AU|EU)$/u", "OVÁ", $name );
		if ( $variant != $name ) {
			array_push( $variants, $variant );
		}
		$variant = preg_replace( "/Ě$/u", "ĚTOVÁ", $name );
		if ( $variant != $name ) {
			array_push( $variants, $variant );
		}

		//II
		//II.1
		$variant = preg_replace( "/Ý$/u", "Á", $name );
		if ( $variant != $name ) {
			array_push( $variants, $variant );
		}

		//II.2
		$variant = preg_replace( "/Í$/u", "OVÁ", $name );
		if ( $variant != $name ) {
			array_push( $variants, $name );
			array_push( $variants, $variant );
		}

		//II.3
		$variant = preg_replace( "/(Ů|ŮV|ŮJ|ÝCH)$/u", "OVÁ", $name );
		if ( $variant != $name ) {
			array_push( $variants, $name );
			array_push( $variants, $variant );
		}

		//II.4 - same as I.A.1
		//II.5
		$variant = preg_replace( "/(IJ|I|YJ|Y|OJ|EJ)$/u", "Á", $name );
		if ( $variant != $name ) {
			array_push( $variants, $variant );
		}

		// ---- Foreign names ----

		//A
		//A.1
		//A.1.a - same as czech I.A.1
		//A.1.b - (-el) same as czech I.A.2.B
		$variant = preg_replace( "/ER$/u", "ROVÁ", $name );
		if ( $variant != $name ) {
			array_push( $variants, $variant );
		}

		//A.1.c
		$variant = preg_replace( "/US$/u", "OVÁ", $name );
		if ( $variant != $name ) {
			array_push( $variants, $variant );
		}

		//A.2
		//A.2.a - same as czech I.B
		//A.2.b - same as czech I.A.1
		//B
		//B.1 - same as czech I.A.1
		//B.2
		//B.2.a - same as czech I.B
		//B.2.b - same as czech I.A.1
		//B.2.c - same as czech I.A.1 / I.B
		//B.2.d - same as czech I.B

		$variants = array_unique( $variants );
		$names    = [];

		foreach ( $variants as $variant ) {
			$name = $this->last_name_DAO->get_name_by_name( $variant );

			if ( $name ) {
				array_push( $names, $name );
			}
		}

		return $names;
	}
}