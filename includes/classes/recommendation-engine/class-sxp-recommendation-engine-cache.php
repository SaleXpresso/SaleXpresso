<?php
/**
 * SaleXpresso
 *
 * @package SaleXpresso\Customer
 * @version 1.0.0
 * @since   1.0.0
 */

namespace SaleXpresso\RecommendationsEngine;

use Exception;
use Phpml\Estimator;
use Phpml\ModelManager;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * Class SXP_Recommendations
 *
 * @package SaleXpresso\RecommendationsEngine
 */
class SXP_Recommendation_Engine_Cache extends ModelManager {
	
	/**
	 * Cache data.
	 *
	 * @param Estimator $estimator Estimator.
	 * @param string    $cache_name Cache Name.
	 * @param int       $expiration Cache Expiration. Default 0 (no expiration)
	 *
	 * @throws Exception
	 */
	public function saveToOptions( Estimator $estimator, string $cache_name, int $expiration = 0 ): void {
		
		$res = set_transient( '__sxp_phpml_' . $cache_name, $estimator, $expiration );
		if ( false === $res ) {
			throw new Exception( 'Unable to write database.' );
		}
	}
	
	/**
	 * Get cached data.
	 *
	 * @param string $cache_name Cache Name.
	 *
	 * @return Estimator
	 * @throws Exception
	 */
	public function restoreFromOption( string $cache_name ): Estimator {
		$object = get_transient( '__sxp_phpml_' . $cache_name );
		if ( $object === false || ! $object instanceof Estimator ) {
			throw new Exception( 'Unable to read cached data from database.' );
		}
		
		return $object;
	}
	
	/**
	 * Remove Cached.
	 *
	 * @param string $cache_name Cache name.
	 */
	public function removeDBCache( string $cache_name ): void {
		delete_transient( '__sxp_phpml_' . $cache_name );
	}
}
// End of file class-sxp-recommendation-engine-cache.php.
