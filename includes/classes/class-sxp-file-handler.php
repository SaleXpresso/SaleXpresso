<?php
/**
 * File Handler
 *
 * @package SaleXpresso/Core
 * @version 1.0.0
 */

namespace SaleXpresso;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * Class SXP_File_Handler
 *
 * @package SaleXpresso
 */
class SXP_File_Handler {
	
	/**
	 * Readonly mode with pointer at beginning
	 *
	 * @var string
	 */
	const MODE_READONLY_POINTER_BEGINNING = 'r';
	/**
	 * Binary safe readonly mode with pointer at beginning.
	 *
	 * @var string
	 */
	const MODE_READONLY_POINTER_BEGINNING_BINARY_SAFE = 'rb';
	/**
	 * Read & write mode with pointer at beginning.
	 *
	 * @var string
	 */
	const MODE_READ_WRITE_POINTER_BEGINNING = 'r+';
	/**
	 * Binary safe read & write mode with pointer at beginning.
	 *
	 * @var string
	 */
	const MODE_READ_WRITE_POINTER_BEGINNING_BINARY_SAFE = 'rb+';
	/**
	 * Write only mode with truncating the whole file (pointer at beginning).
	 *
	 * @var string
	 */
	const MODE_WRITE_ONLY_POINTER_BEGINNING_TRUNCATE = 'w';
	/**
	 * Binary safe write only mode with truncating the whole file (pointer at beginning).
	 *
	 * @var string
	 */
	const MODE_WRITE_ONLY_POINTER_BEGINNING_TRUNCATE_BINARY_SAFE = 'wb';
	/**
	 * Read & write mode with truncating the whole file (pointer at beginning).
	 *
	 * @var string
	 */
	const MODE_READ_WRITE_POINTER_BEGINNING_TRUNCATE = 'w+';
	/**
	 * Binary safe read & write mode with truncating the whole file (pointer at beginning).
	 *
	 * @var string
	 */
	const MODE_READ_WRITE_POINTER_BEGINNING_TRUNCATE_BINARY_SAFE = 'wb+';
	/**
	 * Write only mode append content to the file.
	 *
	 * @var string
	 */
	const MODE_WRITE_ONLY_APPEND = 'a';
	/**
	 * Binary safe write only mode append content to the file.
	 *
	 * @var string
	 */
	const MODE_WRITE_ONLY_APPEND_BINARY_SAFE = 'ab';
	/**
	 * Read & write mode append content to the file.
	 *
	 * @var string
	 */
	const MODE_READ_WRITE_APPEND = 'a+';
	/**
	 * Binary safe read & write mode append content to the file.
	 *
	 * @var string
	 */
	const MODE_READ_WRITE_APPEND_BINARY_SAFE = 'ab+';
	/**
	 * Create file with write only mode.
	 *
	 * @var string
	 */
	const MODE_CREATE_WRITE_ONLY = 'x';
	/**
	 * Binary safe create file with write only mode.
	 *
	 * @var string
	 */
	const MODE_CREATE_WRITE_ONLY_BINARY_SAFE = 'xb';
	/**
	 * Create file with read & write mode.
	 *
	 * @var string
	 */
	const MODE_CREATE_READ_WRITE = 'x+';
	/**
	 * Binary safe create file with read & write mode.
	 *
	 * @var string
	 */
	const MODE_CREATE_READ_WRITE_BINARY_SAFE = 'xb+';
	/**
	 * Write only mode with pointer at beginning locking the file.
	 * Opposite of w
	 *
	 * @var string
	 */
	const MODE_WRITE_ONLY_POINTER_BEGINNING_LOCK_ONLY = 'c';
	/**
	 * Binary safe write only mode with pointer at beginning locking the file.
	 * Opposite of wb
	 *
	 * @var string
	 */
	const MODE_WRITE_ONLY_POINTER_BEGINNING_LOCK_ONLY_BINARY_SAFE = 'cb';
	/**
	 * Read & write mode with pointer at beginning locking the file.
	 * Opposite of w+
	 *
	 * @var string
	 */
	const MODE_READ_WRITE_POINTER_BEGINNING_LOCK_ONLY = 'c+';
	/**
	 * Binary safe read & write mode with pointer at beginning locking the file.
	 * Opposite of wb+
	 *
	 * @var string
	 */
	const MODE_READ_WRITE_POINTER_BEGINNING_LOCK_ONLY_BINARY_SAFE = 'cb+';
	/**
	 * Binary safe translation flag
	 *
	 * @var string
	 */
	const MODE_NON_TRANSLATED = 'b';
	
	/**
	 * File resource returned by the fopen
	 *
	 * @var resource|false a file pointer resource or false.
	 */
	private $handler;
	
	/**
	 * File Name.
	 *
	 * @var string
	 */
	protected $file;
	
	/**
	 * File Path.
	 *
	 * @var string
	 */
	protected $path;
	
	/**
	 * Full Path for the file.
	 *
	 * @var string
	 */
	protected $full_path;
	
	/**
	 * File open mode flag.
	 *
	 * @var string
	 */
	protected $mode;
	
	/**
	 * File open context.
	 *
	 * @var resource|null
	 */
	protected $context;
	
	/**
	 * RWSync_File_Handler constructor.
	 *
	 * @TODO Use WP-Filesystem
	 * @see WP_Filesystem
	 * @see request_filesystem_credentials
	 *
	 * @param string   $filename file base name.
	 * @param string   $path     file path.
	 * @param string   $mode     fopen mode.
	 * @param resource $context  [optional] Fopen Context.
	 */
	public function __construct( $filename, $path, $mode, $context = null ) {
		$this->file      = $filename;
		$this->path      = $path;
		$this->mode      = $mode;
		$this->context   = $context;
		$this->full_path = sxp_get_file_upload_path( $this->file, $this->path, false );
	}
	
	/**
	 * Opens file for processing.
	 *
	 * @see fopen
	 *
	 * @return bool true on success, or false on error
	 */
	public function open() {
		if ( $this->is_opened() ) {
			return false; // file is already opened or failed to open.
		}
		
		$args = [
			'filename'         => $this->full_path,
			'mode'             => $this->mode,
			'use_include_path' => false,
		];
		
		if ( ! is_null( $this->context ) ) {
			$args['context'] = $this->context;
		}
		
		$this->handler = @call_user_func_array( 'fopen', $args ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
		
		return ! ! $this->handler;
	}
	
	/**
	 * Check if file is already opened.
	 *
	 * @return bool
	 */
	public function is_opened() {
		return ( ! is_null( $this->handler ) || false === $this->full_path );
	}
	
	/**
	 * Reopen file with new mode and/or context.
	 *
	 * @param string   $mode    fopen mode.
	 * @param resource $context [optional] Fopen Context.
	 *
	 * @return bool
	 */
	public function reopen( $mode = null, $context = null ) {
		if ( ! $this->is_opened() ) {
			return false;
		}
		$this->close();
		if ( ! is_null( $mode ) ) {
			$this->mode = $mode;
		}
		if ( ! is_null( $context ) ) {
			$this->context = $context;
		}
		
		return $this->open();
	}
	
	/**
	 * Look File.
	 *
	 * @see flock
	 *
	 * @param bool $exclusive [optional] shared or exclusive (writer) lock.
	 *                        Default is false (shared lock) good for reading.
	 *
	 * @return bool true on success or false on failure.
	 */
	public function lock( $exclusive = false ) {
		return @flock( $this->handler, ( $exclusive ? LOCK_EX : LOCK_SH ) | LOCK_NB ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged, WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_flock
	}
	
	/**
	 * Unlock File.
	 *
	 * @see flock
	 *
	 * @return bool true on success or false on failure.
	 */
	public function unlock() {
		return @flock( $this->handler, LOCK_UN | LOCK_NB ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged, WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_flock
	}
	
	/**
	 * Read file.
	 *
	 * @see fread
	 *
	 * @param int $length Up to length number of bytes read.
	 *
	 * @return string|false the read string or false on failure.
	 */
	public function read( $length = null ) {
		$args = [ $this->handler ];
		if ( is_numeric( $length ) ) {
			$args[] = $length;
		} else {
			$args[] = filesize( $this->full_path );
		}
		
		return @call_user_func_array( 'fread', $args ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
	}
	
	/**
	 * Get file content.
	 *
	 * @see fgets
	 *
	 * @param null $length [optional] Reading ends when length - 1 bytes have been read, on a
	 *                     newline (which is included in the return value), or on EOF
	 *                     (whichever comes first). If no length is specified, it will keep reading
	 *                     from the stream until it reaches the end of the line.
	 *
	 * @return string|false a string of up to length - 1 bytes read from the file pointed to by handle.
	 */
	public function get( $length = null ) {
		$args = [ $this->handler ];
		if ( is_numeric( $length ) ) {
			$args[] = $length;
		} else {
			$args[] = filesize( $this->full_path );
		}
		
		return @call_user_func_array( 'fgets', $args ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
	}
	
	/**
	 * Write content to file.
	 *
	 * @see fwrite
	 *
	 * @param string $string The string that is to be written.
	 * @param int    $length [optional] If the length argument is given, writing will
	 *                       stop after length bytes have been written or the end of
	 *                       string is reached, whichever comes first.
	 *
	 * @return int|false the number of bytes written, or FALSE on error.
	 */
	public function write( $string, $length = null ) {
		$args = [ $this->handler, $string ];
		if ( is_numeric( $length ) ) {
			$args[] = $length;
		}
		
		return @call_user_func_array( 'fwrite', $args ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
	}
	
	/**
	 * Alias of write.
	 * Write & close the file.
	 *
	 * @param string $string string to put.
	 *
	 * @return false|int
	 */
	public function put( $string ) {
		return $this->write( $string );
	}
	
	/**
	 * Close file
	 *
	 * @return bool
	 */
	public function close() {
		
		$close = @fclose( $this->handler ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged, WordPress.WP.AlternativeFunctions.file_system_read_fclose
		// set handler to null so the file can be reopen later.
		$this->handler = null;
		
		return $close;
	}
	
	/**
	 * Loads the entire file into memory.
	 * Not recommended for large file.
	 *
	 * @see file_get_contents
	 *
	 * @param string   $file    Filename with ext.
	 * @param string   $path    Directory name to look for.
	 * @param resource $context [optional] A valid context resource created with stream_context_create.
	 * @param int      $offset  [optional] The offset where the reading starts.
	 * @param null     $maxlen  [optional] Maximum length of data read. The default is to read until
	 *                          end of file is reached.
	 *
	 * @return bool|false|string
	 */
	public static function read_file( $file, $path, $context = null, $offset = null, $maxlen = null ) {
		$path = sxp_get_file_upload_path( $file, $path, 2 );
		if ( $path ) {
			$args = [
				'filename'         => $path,
				'use_include_path' => false,
			];
			
			if ( ! is_null( $context ) ) {
				$args['context'] = $context;
			}
			
			if ( ! is_null( $offset ) ) {
				$args['offset'] = $offset;
			}
			
			if ( ! is_null( $maxlen ) ) {
				$args['maxlen'] = $maxlen;
			}
			
			return @call_user_func_array( 'file_get_contents', $args ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
		}
		
		return false;
	}
	
	/**
	 * Save content to path/file.
	 * Will create file if it does not exists
	 *
	 * @see file_put_contents
	 *
	 * @param string   $content Content to put.
	 * @param string   $file    Filename with ext.
	 * @param string   $path    Directory name to look for.
	 * @param int      $flags   [optional] Lock flag. Default to exclusive lock.
	 * @param resource $context [optional] A valid context resource created with stream_context_create.
	 *
	 * @return false|int
	 */
	public static function save_file( $content, $file, $path, $flags = LOCK_EX, $context = null ) {
		$path = sxp_get_file_upload_path( $file, $path, false );
		if ( $path ) {
			$args = [
				'path'    => $path,
				'content' => $content,
				'flags'   => $flags,
			];
			if ( ! is_null( $context ) ) {
				$args['context'] = $context;
			}
			
			return @call_user_func_array( 'file_put_contents', $args ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
		}
		
		return false;
	}
	
	/**
	 * Magic Destruct method.
	 * Closes the open file.
	 * 
	 * @return void
	 */
	public function __destruct() {
		$this->close();
	}
}

// End of file class-sxp-file-handler.php.
