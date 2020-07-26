<?php /** @noinspection DuplicatedCode */
/** @noinspection PhpUnusedLocalVariableInspection */
/** @noinspection PhpUnused */
/** @noinspection RegExpRedundantEscape */
/**
 * Session Handler
 *
 * @package SaleXpresso
 * @version 1.0.0
 * @since   1.0.0
 */

namespace SaleXpresso;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * Class SXP_Session.
 *
 * @see \wfUtils
 * @see \wfWAFWordPressRequest
 *
 */
class SXP_IP {
	/**
	 * Cached IP Address for current Request.
	 * @var string
	 */
	protected static $the_IP;
	
	/**
	 * @return bool|string
	 */
	public static function get_ip() {
		// Check the cache first.
		if ( ! is_null( self::$the_IP ) ) {
			return self::$the_IP;
		}
		
		$ips = [];
		$ip_request_field = get_option( 'salexpresso_st_ip_request_field' );
		if ( $ip_request_field ) {
			if ( is_string( $ip_request_field ) && is_array( $_SERVER ) && array_key_exists( $ip_request_field, $_SERVER ) ) {
				$ips[] = [ $_SERVER[ $ip_request_field ], $ip_request_field ];
			}
			
			if ( $ip_request_field != 'REMOTE_ADDR' ) {
				$ips[] = [ ( is_array( $_SERVER ) && array_key_exists( 'REMOTE_ADDR', $_SERVER ) ) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1', 'REMOTE_ADDR', ];
			}
		} else {
			
			$ips[] = [ ( is_array($_SERVER) && array_key_exists('REMOTE_ADDR', $_SERVER)) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1', 'REMOTE_ADDR' ];
			if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
				$ips[] = [ $_SERVER['HTTP_X_FORWARDED_FOR'], 'HTTP_X_FORWARDED_FOR' ];
			}
			if (isset($_SERVER['HTTP_X_REAL_IP'])) {
				$ips[] = [ $_SERVER['HTTP_X_REAL_IP'], 'HTTP_X_REAL_IP' ];
			}
		}
		
		// Sanitize
		$cleanedIP = self::get_clean_ip_and_server_var( $ips );
		
		if ( is_array( $cleanedIP ) ) {
			list( $ip, $variable ) = $cleanedIP;
			self::$the_IP = $ip;
			
			return $ip;
		}
		
		// Cache the result.
		self::$the_IP = $cleanedIP;
		
		return $cleanedIP;
	}
	
	/**
	 * Get IP Addresses for previewing admin settings.
	 *
	 * @return bool|false|string
	 */
	public static function get_ip_preview() {
		$ip = self::get_ip_and_server_variable();
		if ( is_array( $ip ) ) {
			list( $IP, $variable ) = $ip;
			if ( isset( $_SERVER[ $variable ] ) && strpos( $_SERVER[ $variable ], ',' ) !== false ) {
				$items  = preg_replace( '/[\s,]/', '', explode( ',', $_SERVER[ $variable ] ) );
				$output = '';
				foreach ( $items as $i ) {
					if ( $IP == $i ) {
						$output .= ', <strong>' . esc_html( $i ) . '</strong>';
					} else {
						$output .= ', ' . esc_html( $i );
					}
				}
				
				return substr( $output, 2 );
			}
			
			return '<strong>' . esc_html( $IP ) . '</strong>';
		}
		
		return false;
	}
	
	/**
	 * Get IP & Server Variables.
	 *
	 * @param string $ip_request_field Array key to use with SERVER Super global to retrive the user's IP Address.
	 * @param string $trusted_proxies List of trusted proxies.
	 *
	 * @return array|bool
	 */
	public static function get_ip_and_server_variable( $ip_request_field = null, $trusted_proxies = null ) {
		// Real Remote Addr by detected by php
		$connection_ip = array_key_exists( 'REMOTE_ADDR', $_SERVER ) ? [ $_SERVER['REMOTE_ADDR'], 'REMOTE_ADDR' ] : [ '127.0.0.1', 'REMOTE_ADDR' ];
		$ips_to_check   = [];
		
		if ( $ip_request_field === null ) {
			$ip_request_field = get_option( 'salexpresso_st_ip_request_field' );
		}
		
		if ( $ip_request_field ) {
			if ( $ip_request_field == 'REMOTE_ADDR' ) {
				return self::get_clean_ip_and_server_var( [ $connection_ip ], $trusted_proxies );
			} else {
				$ips_to_check = [
					[
						( isset( $_SERVER[ $ip_request_field ] ) ? $_SERVER[ $ip_request_field ] : '' ),
						$ip_request_field
					],
					$connection_ip,
				];
				
				return self::get_clean_ip_and_server_var( $ips_to_check, $trusted_proxies );
			}
		} else {
			$ips_to_check[] = $connection_ip;
			if ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
				$ips_to_check[] = [ $_SERVER['HTTP_X_FORWARDED_FOR'], 'HTTP_X_FORWARDED_FOR' ];
			}
			if ( isset( $_SERVER['HTTP_X_REAL_IP'] ) ) {
				$ips_to_check[] = [ $_SERVER['HTTP_X_REAL_IP'], 'HTTP_X_REAL_IP' ];
			}
			
			return self::get_clean_ip_and_server_var( $ips_to_check, $trusted_proxies );
		}
	}
	
	/**
	 * Sanitize IP & Server Variable
	 * @param array $arr
	 * @param array $trusted_proxies
	 *
	 * @return array|bool
	 */
	private static function get_clean_ip_and_server_var( $arr, $trusted_proxies = null ) {
		// Store private addrs until end as last resort.
		$privates = [];
		
		foreach ( $arr as $entry ) {
			list( $item, $var ) = $entry;
			if ( is_array( $item ) ) {
				foreach ( $item as $j ) {
					// Try verifying the IP is valid before stripping the port off.
					if ( ! self::is_valid_ip( $j ) ) {
						$j = preg_replace( '/:\d+$/', '', $j ); // Strip off port.
					}
					if ( self::is_valid_ip( $j ) ) {
						if ( self::is_ipv6_mapped_ipv4( $j ) ) {
							$j = self::inet_ntop( self::inet_pton( $j ) );
						}
						
						if ( self::is_private_ip( $j ) ) {
							$privates[] = [ $j, $var ];
						} else {
							return [ $j, $var ];
						}
					}
				}
				continue; // This was an array so we can skip to the next item.
			}
			$skipToNext = false;
			if ( null === $trusted_proxies ) {
				$trusted_proxies = explode( "\n", 'salexpresso_st_trusted_proxies' );
			}
			foreach ( [ ',', ' ', "\t" ] as $char ) {
				if ( strpos( $item, $char ) !== false ) {
					$sp = explode( $char, $item );
					$sp = array_reverse( $sp );
					foreach ( $sp as $index => $j ) {
						$j = trim( $j );
						if ( ! self::is_valid_ip( $j ) ) {
							$j = preg_replace( '/:\d+$/', '', $j ); // Strip off port.
						}
						if ( self::is_valid_ip( $j ) ) {
							if ( self::is_ipv6_mapped_ipv4( $j ) ) {
								$j = self::inet_ntop( self::inet_pton( $j ) );
							}
							
							foreach ( $trusted_proxies as $proxy ) {
								if ( ! empty( $proxy ) ) {
									if ( self::subnetContainsIP( $proxy, $j )
									     && $index < count( $sp ) - 1 ) {
										continue 2;
									}
								}
							}
							
							if ( self::is_private_ip( $j ) ) {
								$privates[] = [ $j, $var ];
							} else {
								return [ $j, $var ];
							}
						}
					}
					$skipToNext = true;
					break;
				}
			}
			
			// Skip to next item because this one had a comma, space or tab so was delimited and we didn't find anything.
			if ( $skipToNext ) {
				continue;
			}
			
			if ( ! self::is_valid_ip( $item ) ) {
				$item = preg_replace( '/:\d+$/', '', $item ); // Strip off port.
			}
			if ( self::is_valid_ip( $item ) ) {
				if ( self::is_ipv6_mapped_ipv4( $item ) ) {
					$item = self::inet_ntop( self::inet_pton( $item ) );
				}
				
				if ( self::is_private_ip( $item ) ) {
					$privates[] = [ $item, $var ];
				} else {
					return [ $item, $var ];
				}
			}
		}
		
		if ( sizeof( $privates ) > 0 ) {
			// Return the first private we found so that we respect the order the IP's were passed to this function.
			return $privates[0];
		}
		
		return false;
	}
	
	/**
	 * Return dot or colon notation of IPv4 or IPv6 address.
	 *
	 * @param string $ip IP Address.
	 *
	 * @return string|bool
	 */
	public static function inet_ntop( $ip ) {
		// Trim this to the IPv4 equiv if it's in the mapped range.
		if ( self::strlen( $ip ) == 16
		     && self::substr( $ip, 0, 12 ) == "\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\xff\xff" ) {
			$ip = self::substr( $ip, 12, 4 );
		}
		
		return self::has_ipv6_support() ? @inet_ntop( $ip ) : self::_inet_ntop( $ip );
	}
	
	/**
	 * Return the packed binary string of an IPv4 or IPv6 address.
	 *
	 * @param string $ip
	 * @return string
	 */
	public static function inet_pton( $ip ) {
		// Convert the 4 char IPv4 to IPv6 mapped version.
		return str_pad(
			self::has_ipv6_support() ? @inet_pton( $ip ) : self::_inet_pton( $ip ),
			16,
			"\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\xff\xff\x00\x00\x00\x00",
			STR_PAD_LEFT
		);
	}
	
	/**
	 * Added compatibility for hosts that do not have inet_pton.
	 *
	 * @param $ip
	 * @return bool|string
	 */
	public static function _inet_pton( $ip ) {
		// IPv4.
		if ( preg_match( '/^(?:\d{1,3}(?:\.|$)){4}/', $ip ) ) {
			$octets = explode( '.', $ip );
			
			return chr( $octets[0] ) . chr( $octets[1] ) . chr( $octets[2] ) . chr( $octets[3] );
		}
		
		// IPv6
		if ( preg_match( '/^((?:[\da-f]{1,4}(?::|)){0,8})(::)?((?:[\da-f]{1,4}(?::|)){0,8})$/i', $ip ) ) {
			if ( $ip === '::' ) {
				return "\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0";
			}
			$colon_count = self::substr_count( $ip, ':' );
			$dbl_colon_pos = self::strpos( $ip, '::' );
			if ( $dbl_colon_pos !== false ) {
				$ip = str_replace(
					'::',
					str_repeat(
						':0000',
						(
							( $dbl_colon_pos === 0 || $dbl_colon_pos === self::strlen( $ip ) - 2 ) ? 9 : 8
						) - $colon_count
					) . ':',
					$ip
				);
				$ip = trim( $ip, ':' );
			}
			
			$ip_groups = explode( ':', $ip );
			$ipv6_bin  = '';
			foreach ( $ip_groups as $ip_group ) {
				$ipv6_bin .= pack( 'H*', str_pad( $ip_group, 4, '0', STR_PAD_LEFT ) );
			}
			
			return self::strlen( $ipv6_bin ) === 16 ? $ipv6_bin : false;
		}
		
		// IPv4 mapped IPv6
		if ( preg_match( '/^((?:0{1,4}(?::|)){0,5})(::)?ffff:((?:\d{1,3}(?:\.|$)){4})$/i', $ip, $matches ) ) {
			$octets = explode( '.', $matches[3] );
			
			return "\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\xff\xff" . chr( $octets[0] ) . chr( $octets[1] ) . chr( $octets[2] ) . chr( $octets[3] );
		}
		
		return false;
	}
	
	/**
	 * Added compatibility for hosts that do not have inet_ntop.
	 *
	 * @param $ip
	 * @return bool|string
	 */
	public static function _inet_ntop( $ip ) {
		// IPv4
		if ( self::strlen( $ip ) === 4 ) {
			return ord( $ip[0] ) . '.' . ord( $ip[1] ) . '.' . ord( $ip[2] ) . '.' . ord( $ip[3] );
		}
		
		// IPv6
		if ( self::strlen( $ip ) === 16 ) {
			
			// IPv4 mapped IPv6
			if ( self::substr( $ip, 0, 12 ) == "\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\xff\xff" ) {
				return "::ffff:" . ord( $ip[12] ) . '.' . ord( $ip[13] ) . '.' . ord( $ip[14] ) . '.' . ord( $ip[15] );
			}
			
			$hex           = bin2hex( $ip );
			$groups        = str_split( $hex, 4 );
			$collapse      = false;
			$done_collapse = false;
			foreach ( $groups as $index => $group ) {
				if ( $group == '0000' && ! $done_collapse ) {
					if ( ! $collapse ) {
						$groups[ $index ] = ':';
					} else {
						$groups[ $index ] = '';
					}
					$collapse = true;
				} elseif ( $collapse ) {
					$done_collapse = true;
					$collapse      = false;
				}
				$groups[ $index ] = ltrim( $groups[ $index ], '0' );
			}
			$ip = join( ':', array_filter( $groups ) );
			$ip = str_replace( ':::', '::', $ip );
			
			return $ip == ':' ? '::' : $ip;
		}
		
		return false;
	}
	
	/**
	 * Verify PHP was compiled with IPv6 support.
	 *
	 * Some hosts appear to not have inet_ntop, and others appear to have inet_ntop but are unable to process IPv6 addresses.
	 *
	 * @return bool
	 */
	public static function has_ipv6_support() {
		return defined( 'AF_INET6' );
	}
	
	/**
	 * Expand a compressed printable representation of an IPv6 address.
	 *
	 * @param string $ip
	 * @return string
	 */
	public static function expandIPv6Address( $ip ) {
		$hex = bin2hex( self::inet_pton( $ip ) );
		$ip  = self::substr( preg_replace( "/([a-f0-9]{4})/i", "$1:", $hex ), 0, - 1 );
		
		return $ip;
	}
	
	/**
	 * @param string $ip
	 * @return bool
	 */
	public static function is_valid_ip( $ip ) {
		return false !== filter_var( $ip, FILTER_VALIDATE_IP );
	}
	
	/**
	 * Check if input is valid cidr.
	 * 
	 * @param string $range Range to check.
	 *
	 * @return bool
	 */
	public static function is_valid_cidr_range( $range ) {
		$components = explode( '/', $range );
		if ( count( $components ) != 2 ) {
			return false;
		}
		
		list( $ip, $prefix ) = $components;
		if ( ! self::is_valid_ip( $ip ) ) {
			return false;
		}
		
		if ( ! preg_match( '/^\d+$/', $prefix ) ) {
			return false;
		}
		
		if ( filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) ) {
			if ( $prefix < 0 || $prefix > 32 ) {
				return false;
			}
		} else {
			if ( $prefix < 1 || $prefix > 128 ) {
				return false;
			}
		}
		
		return true;
	}
	
	/**
	 * @param string $ip
	 *
	 * @return bool
	 *
	 */
	private static function is_ipv6_mapped_ipv4( $ip ) {
		return preg_match( '/^(?:\:(?:\:0{1,4}){0,4}\:|(?:0{1,4}\:){5})ffff\:\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/i', $ip ) > 0;
	}
	
	/**
	 * Check if ip is in private ip range.
	 * 
	 * @param string $ip Should be in dot or colon notation (127.0.0.1 or ::1).
	 * @return bool
	 */
	private static function is_private_ip( $ip ) {
		// Run this through the preset list for IPv4 addresses.
		if ( filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) !== false ) {
			
			foreach ( self::private_ip_list() as $a ) {
				if ( self::subnetContainsIP( $a, $ip ) ) {
					return true;
				}
			}
		}
		
		return (
			filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6 ) !== false
			&&
			filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE ) === false
		);
	}
	
	/**
	 * Check if an IP address is in a network block
	 *
	 * @param string	$subnet	Single IP or subnet in CIDR notation (e.g. '192.168.100.0' or '192.168.100.0/22')
	 * @param string	$ip		IPv4 or IPv6 address in dot or colon notation
	 * @return boolean
	 */
	public static function subnetContainsIP( $subnet, $ip ) {
		static $_network_cache = [];
		static $_ip_cache = [];
		static $_masks = [
			0   => "\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00",
			1   => "\x80\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00",
			2   => "\xc0\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00",
			3   => "\xe0\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00",
			4   => "\xf0\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00",
			5   => "\xf8\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00",
			6   => "\xfc\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00",
			7   => "\xfe\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00",
			8   => "\xff\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00",
			9   => "\xff\x80\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00",
			10  => "\xff\xc0\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00",
			11  => "\xff\xe0\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00",
			12  => "\xff\xf0\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00",
			13  => "\xff\xf8\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00",
			14  => "\xff\xfc\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00",
			15  => "\xff\xfe\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00",
			16  => "\xff\xff\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00",
			17  => "\xff\xff\x80\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00",
			18  => "\xff\xff\xc0\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00",
			19  => "\xff\xff\xe0\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00",
			20  => "\xff\xff\xf0\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00",
			21  => "\xff\xff\xf8\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00",
			22  => "\xff\xff\xfc\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00",
			23  => "\xff\xff\xfe\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00",
			24  => "\xff\xff\xff\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00",
			25  => "\xff\xff\xff\x80\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00",
			26  => "\xff\xff\xff\xc0\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00",
			27  => "\xff\xff\xff\xe0\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00",
			28  => "\xff\xff\xff\xf0\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00",
			29  => "\xff\xff\xff\xf8\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00",
			30  => "\xff\xff\xff\xfc\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00",
			31  => "\xff\xff\xff\xfe\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00",
			32  => "\xff\xff\xff\xff\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00",
			33  => "\xff\xff\xff\xff\x80\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00",
			34  => "\xff\xff\xff\xff\xc0\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00",
			35  => "\xff\xff\xff\xff\xe0\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00",
			36  => "\xff\xff\xff\xff\xf0\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00",
			37  => "\xff\xff\xff\xff\xf8\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00",
			38  => "\xff\xff\xff\xff\xfc\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00",
			39  => "\xff\xff\xff\xff\xfe\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00",
			40  => "\xff\xff\xff\xff\xff\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00",
			41  => "\xff\xff\xff\xff\xff\x80\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00",
			42  => "\xff\xff\xff\xff\xff\xc0\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00",
			43  => "\xff\xff\xff\xff\xff\xe0\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00",
			44  => "\xff\xff\xff\xff\xff\xf0\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00",
			45  => "\xff\xff\xff\xff\xff\xf8\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00",
			46  => "\xff\xff\xff\xff\xff\xfc\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00",
			47  => "\xff\xff\xff\xff\xff\xfe\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00",
			48  => "\xff\xff\xff\xff\xff\xff\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00",
			49  => "\xff\xff\xff\xff\xff\xff\x80\x00\x00\x00\x00\x00\x00\x00\x00\x00",
			50  => "\xff\xff\xff\xff\xff\xff\xc0\x00\x00\x00\x00\x00\x00\x00\x00\x00",
			51  => "\xff\xff\xff\xff\xff\xff\xe0\x00\x00\x00\x00\x00\x00\x00\x00\x00",
			52  => "\xff\xff\xff\xff\xff\xff\xf0\x00\x00\x00\x00\x00\x00\x00\x00\x00",
			53  => "\xff\xff\xff\xff\xff\xff\xf8\x00\x00\x00\x00\x00\x00\x00\x00\x00",
			54  => "\xff\xff\xff\xff\xff\xff\xfc\x00\x00\x00\x00\x00\x00\x00\x00\x00",
			55  => "\xff\xff\xff\xff\xff\xff\xfe\x00\x00\x00\x00\x00\x00\x00\x00\x00",
			56  => "\xff\xff\xff\xff\xff\xff\xff\x00\x00\x00\x00\x00\x00\x00\x00\x00",
			57  => "\xff\xff\xff\xff\xff\xff\xff\x80\x00\x00\x00\x00\x00\x00\x00\x00",
			58  => "\xff\xff\xff\xff\xff\xff\xff\xc0\x00\x00\x00\x00\x00\x00\x00\x00",
			59  => "\xff\xff\xff\xff\xff\xff\xff\xe0\x00\x00\x00\x00\x00\x00\x00\x00",
			60  => "\xff\xff\xff\xff\xff\xff\xff\xf0\x00\x00\x00\x00\x00\x00\x00\x00",
			61  => "\xff\xff\xff\xff\xff\xff\xff\xf8\x00\x00\x00\x00\x00\x00\x00\x00",
			62  => "\xff\xff\xff\xff\xff\xff\xff\xfc\x00\x00\x00\x00\x00\x00\x00\x00",
			63  => "\xff\xff\xff\xff\xff\xff\xff\xfe\x00\x00\x00\x00\x00\x00\x00\x00",
			64  => "\xff\xff\xff\xff\xff\xff\xff\xff\x00\x00\x00\x00\x00\x00\x00\x00",
			65  => "\xff\xff\xff\xff\xff\xff\xff\xff\x80\x00\x00\x00\x00\x00\x00\x00",
			66  => "\xff\xff\xff\xff\xff\xff\xff\xff\xc0\x00\x00\x00\x00\x00\x00\x00",
			67  => "\xff\xff\xff\xff\xff\xff\xff\xff\xe0\x00\x00\x00\x00\x00\x00\x00",
			68  => "\xff\xff\xff\xff\xff\xff\xff\xff\xf0\x00\x00\x00\x00\x00\x00\x00",
			69  => "\xff\xff\xff\xff\xff\xff\xff\xff\xf8\x00\x00\x00\x00\x00\x00\x00",
			70  => "\xff\xff\xff\xff\xff\xff\xff\xff\xfc\x00\x00\x00\x00\x00\x00\x00",
			71  => "\xff\xff\xff\xff\xff\xff\xff\xff\xfe\x00\x00\x00\x00\x00\x00\x00",
			72  => "\xff\xff\xff\xff\xff\xff\xff\xff\xff\x00\x00\x00\x00\x00\x00\x00",
			73  => "\xff\xff\xff\xff\xff\xff\xff\xff\xff\x80\x00\x00\x00\x00\x00\x00",
			74  => "\xff\xff\xff\xff\xff\xff\xff\xff\xff\xc0\x00\x00\x00\x00\x00\x00",
			75  => "\xff\xff\xff\xff\xff\xff\xff\xff\xff\xe0\x00\x00\x00\x00\x00\x00",
			76  => "\xff\xff\xff\xff\xff\xff\xff\xff\xff\xf0\x00\x00\x00\x00\x00\x00",
			77  => "\xff\xff\xff\xff\xff\xff\xff\xff\xff\xf8\x00\x00\x00\x00\x00\x00",
			78  => "\xff\xff\xff\xff\xff\xff\xff\xff\xff\xfc\x00\x00\x00\x00\x00\x00",
			79  => "\xff\xff\xff\xff\xff\xff\xff\xff\xff\xfe\x00\x00\x00\x00\x00\x00",
			80  => "\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\x00\x00\x00\x00\x00\x00",
			81  => "\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\x80\x00\x00\x00\x00\x00",
			82  => "\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xc0\x00\x00\x00\x00\x00",
			83  => "\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xe0\x00\x00\x00\x00\x00",
			84  => "\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xf0\x00\x00\x00\x00\x00",
			85  => "\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xf8\x00\x00\x00\x00\x00",
			86  => "\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xfc\x00\x00\x00\x00\x00",
			87  => "\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xfe\x00\x00\x00\x00\x00",
			88  => "\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\x00\x00\x00\x00\x00",
			89  => "\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\x80\x00\x00\x00\x00",
			90  => "\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xc0\x00\x00\x00\x00",
			91  => "\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xe0\x00\x00\x00\x00",
			92  => "\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xf0\x00\x00\x00\x00",
			93  => "\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xf8\x00\x00\x00\x00",
			94  => "\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xfc\x00\x00\x00\x00",
			95  => "\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xfe\x00\x00\x00\x00",
			96  => "\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\x00\x00\x00\x00",
			97  => "\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\x80\x00\x00\x00",
			98  => "\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xc0\x00\x00\x00",
			99  => "\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xe0\x00\x00\x00",
			100 => "\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xf0\x00\x00\x00",
			101 => "\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xf8\x00\x00\x00",
			102 => "\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xfc\x00\x00\x00",
			103 => "\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xfe\x00\x00\x00",
			104 => "\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\x00\x00\x00",
			105 => "\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\x80\x00\x00",
			106 => "\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xc0\x00\x00",
			107 => "\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xe0\x00\x00",
			108 => "\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xf0\x00\x00",
			109 => "\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xf8\x00\x00",
			110 => "\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xfc\x00\x00",
			111 => "\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xfe\x00\x00",
			112 => "\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\x00\x00",
			113 => "\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\x80\x00",
			114 => "\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xc0\x00",
			115 => "\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xe0\x00",
			116 => "\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xf0\x00",
			117 => "\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xf8\x00",
			118 => "\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xfc\x00",
			119 => "\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xfe\x00",
			120 => "\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\x00",
			121 => "\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\x80",
			122 => "\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xc0",
			123 => "\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xe0",
			124 => "\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xf0",
			125 => "\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xf8",
			126 => "\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xfc",
			127 => "\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xfe",
			128 => "\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff",
		];
		
		/*
		 * The above is generated by:
		 * 
		   function gen_mask($prefix, $size = 128) {
				//Workaround to avoid overflow, split into four pieces			
				$mask_1 = (pow(2, $size / 4) - 1) ^ (pow(2, min($size / 4, max(0, 1 * $size / 4 - $prefix))) - 1);
				$mask_2 = (pow(2, $size / 4) - 1) ^ (pow(2, min($size / 4, max(0, 2 * $size / 4 - $prefix))) - 1);
				$mask_3 = (pow(2, $size / 4) - 1) ^ (pow(2, min($size / 4, max(0, 3 * $size / 4 - $prefix))) - 1);
				$mask_4 = (pow(2, $size / 4) - 1) ^ (pow(2, min($size / 4, max(0, 4 * $size / 4 - $prefix))) - 1);
				return ($mask_1 ? pack('N', $mask_1) : "\0\0\0\0") . ($mask_2 ? pack('N', $mask_2) : "\0\0\0\0") . ($mask_3 ? pack('N', $mask_3) : "\0\0\0\0") . ($mask_4 ? pack('N', $mask_4) : "\0\0\0\0");
			}
			
			$masks = [];
			for ($i = 0; $i <= 128; $i++) {
				$mask = gen_mask($i);
				$chars = str_split($mask);
				$masks[] = implode('', array_map(function($c) { return '\\x' . bin2hex($c); }, $chars));
			}
			
			echo '[' . "\n";
			foreach ($masks as $index => $m) {
				echo "\t{$index} => \"{$m}\",\n";
			}
			echo ']';
		 *
		 */
		
		if ( isset( $_network_cache[ $subnet ] ) ) {
			list( $bin_network, $prefix, $masked_network ) = $_network_cache[ $subnet ];
			$mask = $_masks[ $prefix ];
		} else {
			list( $network, $prefix ) = array_pad( explode( '/', $subnet, 2 ), 2, null );
			if ( filter_var( $network, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) ) {
				// If no prefix was supplied, 32 is implied for IPv4
				if ( $prefix === null ) {
					$prefix = 32;
				}
				
				// Validate the IPv4 network prefix
				if ( $prefix < 0 || $prefix > 32 ) {
					return false;
				}
				
				// Increase the IPv4 network prefix to work in the IPv6 address space
				$prefix += 96;
			} else {
				// If no prefix was supplied, 128 is implied for IPv6
				if ( $prefix === null ) {
					$prefix = 128;
				}
				
				// Validate the IPv6 network prefix
				if ( $prefix < 1 || $prefix > 128 ) {
					return false;
				}
			}
			$mask                      = $_masks[ $prefix ];
			$bin_network               = self::inet_pton( $network );
			$masked_network            = $bin_network & $mask;
			$_network_cache[ $subnet ] = [ $bin_network, $prefix, $masked_network ];
		}
		
		if ( isset( $_ip_cache[ $ip ] ) && isset( $_ip_cache[ $ip ][ $prefix ] ) ) {
			list( $bin_ip, $masked_ip ) = $_ip_cache[ $ip ][ $prefix ];
		} else {
			$bin_ip    = self::inet_pton( $ip );
			$masked_ip = $bin_ip & $mask;
			if ( ! isset( $_ip_cache[ $ip ] ) ) {
				$_ip_cache[ $ip ] = [];
			}
			$_ip_cache[ $ip ][ $prefix ] = [ $bin_ip, $masked_ip ];
		}
		
		return ( $masked_ip === $masked_network );
	}
	
	/**
	 * List of private IPs
	 * @return array
	 */
	private static function private_ip_list() {
		return [
			//We've modified this and removed some addresses which may be routable on the Net and cause auto-whitelisting.
			//'0.0.0.0/8',			# Broadcast addr.
			'10.0.0.0/8',			# Private addrs.
			//'100.64.0.0/10',		# Carrier-grade-nat for comms between ISP and subscribers.
			'127.0.0.0/8',			# Loopback.
			//'169.254.0.0/16',		# Link-local when DHCP fails e.g. os x.
			'172.16.0.0/12',		# Private addrs.
			'192.0.0.0/29',			# Used for NAT with IPv6, so basically a private addr.
			//'192.0.2.0/24',		# Only for use in docs and examples, not for public use.
			//'192.88.99.0/24',		# Used by 6to4 anycast relays.
			'192.168.0.0/16',		# Used for local communications within a private network.
			//'198.18.0.0/15',		# Used for testing of inter-network communications between two separate subnets.
			//'198.51.100.0/24',	# Assigned as "TEST-NET-2" in RFC 5737 for use solely in documentation and example source code and should not be used publicly.
			//'203.0.113.0/24',		# Assigned as "TEST-NET-3" in RFC 5737 for use solely in documentation and example source code and should not be used publicly.
			//'224.0.0.0/4',		# Reserved for multicast assignments as specified in RFC 5771.
			//'240.0.0.0/4',		# Reserved for future use, as specified by RFC 6890.
			//'255.255.255.255/32',	# Reserved for the "limited broadcast" destination address, as specified by RFC 6890.
		];
	}
	
	/**
	 * @param $string
	 * @param $start
	 * @param $length
	 * @return mixed
	 */
	public static function substr($string, $start, $length = null) {
		if ( $length === null ) {
			$length = self::strlen( $string );
		}
		
		return self::callMBSafeStrFunction( 'substr', [ $string, $start, $length ] );
	}
	
	/**
	 * @param $haystack
	 * @param $needle
	 * @param int $offset
	 * @return mixed
	 */
	public static function strpos($haystack, $needle, $offset = 0) {
		$args = func_get_args();
		return self::callMBSafeStrFunction('strpos', $args);
	}
	
	/**
	 * @param callable $function
	 * @param array $args
	 * @return mixed
	 */
	protected static function callMBSafeStrFunction( $function, $args ) {
		self::mbstring_binary_safe_encoding();
		$return = call_user_func_array( $function, $args );
		self::reset_mbstring_encoding();
		
		return $return;
	}
	
	/**
	 * Multibyte safe strlen.
	 *
	 * @param $binary
	 * @return int
	 */
	public static function strlen( $binary ) {
		$args = func_get_args();
		
		return self::callMBSafeStrFunction( 'strlen', $args );
	}
	
	/**
	 * @param $haystack
	 * @param $needle
	 * @param int $offset
	 * @return int
	 */
	public static function stripos( $haystack, $needle, $offset = 0 ) {
		$args = func_get_args();
		
		return self::callMBSafeStrFunction( 'stripos', $args );
	}
	
	/**
	 * @param $string
	 * @return mixed
	 */
	public static function strtolower( $string ) {
		$args = func_get_args();
		
		return self::callMBSafeStrFunction( 'strtolower', $args );
	}
	
	/**
	 * @param string $haystack
	 * @param string $needle
	 * @param int $offset
	 * @param int $length
	 * @return mixed
	 */
	public static function substr_count( $haystack, $needle, $offset = 0, $length = null ) {
		if ( $length === null ) {
			$length = self::strlen( $haystack );
		}
		
		return self::callMBSafeStrFunction(
			'substr_count',
			[
				$haystack,
				$needle,
				$offset,
				$length,
			]
		);
	}
	
	/**
	 * @param $string
	 * @return mixed
	 */
	public static function strtoupper( $string ) {
		$args = func_get_args();
		
		return self::callMBSafeStrFunction( 'strtoupper', $args );
	}
	
	/**
	 * @param string $haystack
	 * @param string $needle
	 * @param int $offset
	 * @return mixed
	 */
	public static function strrpos( $haystack, $needle, $offset = 0 ) {
		$args = func_get_args();
		
		return self::callMBSafeStrFunction( 'strrpos', $args );
	}
	
	/**
	 * Set the mbstring internal encoding to a binary safe encoding when func_overload
	 * is enabled.
	 *
	 * When mbstring.func_overload is in use for multi-byte encodings, the results from
	 * strlen() and similar functions respect the utf8 characters, causing binary data
	 * to return incorrect lengths.
	 *
	 * This function overrides the mbstring encoding to a binary-safe encoding, and
	 * resets it to the users expected encoding afterwards through the
	 * `reset_mbstring_encoding` function.
	 *
	 * It is safe to recursively call this function, however each
	 * `mbstring_binary_safe_encoding()` call must be followed up with an equal number
	 * of `reset_mbstring_encoding()` calls.
	 *
	 * @see wfWAFUtils::reset_mbstring_encoding
	 *
	 * @staticvar array $encodings
	 * @staticvar bool  $overloaded
	 *
	 * @param bool $reset Optional. Whether to reset the encoding back to a previously-set encoding.
	 *                    Default false.
	 */
	public static function mbstring_binary_safe_encoding( $reset = false ) {
		static $encodings = [];
		static $overloaded = null;
		
		if ( is_null( $overloaded ) ) {
			// phpcs:ignore PHPCompatibility.IniDirectives.RemovedIniDirectives.mbstring_func_overloadDeprecated
			$overloaded = function_exists( 'mb_internal_encoding' )
			              && ( ini_get( 'mbstring.func_overload' ) & 2 );
		}
		
		if ( false === $overloaded ) {
			return;
		}
		
		if ( ! $reset ) {
			$encoding = mb_internal_encoding();
			array_push( $encodings, $encoding );
			mb_internal_encoding( 'ISO-8859-1' );
		}
		
		if ( $reset && $encodings ) {
			$encoding = array_pop( $encodings );
			mb_internal_encoding( $encoding );
		}
	}
	
	/**
	 * Reset the mbstring internal encoding to a users previously set encoding.
	 *
	 * @see wfWAFUtils::mbstring_binary_safe_encoding
	 */
	public static function reset_mbstring_encoding() {
		self::mbstring_binary_safe_encoding( true );
	}
	
	/**
	 * Generate Random ID for testing.
	 * @return string
	 */
	public static function make_random_ip() {
		return rand( 11, 230 ) . '.' . rand( 0, 255 ) . '.' . rand( 0, 255 ) . '.' . rand( 0, 255 );
	}
}
// End of file class-sxp-ip.php.
