/**
 * Gulp Config
 *
 * @version 1.0.0
 * @author SaleXpresso <support@salexpresso.com>
 * @package SaleXpresso
 * @copyright 2020 SaleXpresso
 */

/* eslint-env node */
/* eslint no-console: "off" */
'use strict';

// const app = require( './package.json' );
// const path = require( 'path' );
const gulp = require( 'gulp' );
const wpPot = require( 'gulp-wp-pot' );
const zip = require( 'gulp-zip' );

/**
 * The Config.
 *
 * @todo update pot.options.bugReport
 */
const config = {
	pot: {
		src: '**/*.php',
		dist: './i18n/languages/salexpresso.pot',
		options: {
			domain: 'salexpresso',
			package: 'SaleXpresso',
			bugReport: 'https://wordpress.org/support/plugin/salexpresso/',
			team: 'support@salexpresso.com',
			headers: {
				'X-Domain': 'salexpresso',
			},
		},
	},
	zip: {
		src: [
			'**',
			'!.git/**',
			'!.idea/**',
			'!bin/**',
			'!dist/**',
			'!includes/dev.php',
			'!logs/**',
			'!node_modules/**',
			'!src/**',
			'!.babelrc',
			'!.browserslistrc',
			'!.eslintignore',
			'!.eslintrc',
			'!.gitignore',
			'!.huskyrc',
			'!.stylelintrc',
			'!.svnignore',
			'!CHANGELOG.md',
			'!DeveloperGuide.md',
			'!gulpfile.js',
			'!package.json',
			'!package-lock.json',
			'!README.MD',
		],
		file_name: 'sale-xpresso',
		dist: 'dist',
		options: {
			compress: true,
			modifiedTime: undefined,
		},
	},
};

// Tasks.
gulp.task( 'make-pot', () => {
	return gulp.src( config.pot.src )
		.pipe( wpPot( config.pot.options ) )
		.pipe( gulp.dest( config.pot.dist ) );
} );

gulp.task( 'make-zip', () => {
	// Replace with os level zip.
	// As this doesn't make archive required by WordPress installer.
	// WordPress requires the following format
	// archive.zip
	//   \-archive
	//     \- dir
	//       \- file
	//     \- file.
	//     \- file.
	return gulp.src( config.zip.src )
		.pipe( zip( config.zip.file_name.replace( '.zip', '' ) + '.zip' ), config.zip.options )
		.pipe( gulp.dest( config.zip.dist ) );
} );
