const autoprefixer = require( 'autoprefixer' );
const pixrem = require( 'pixrem' );
const cssnano = require( 'cssnano' );

module.exports = {
	plugins: [ autoprefixer(), pixrem(), cssnano ],
};
