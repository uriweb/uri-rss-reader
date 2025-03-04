var gulp = require('gulp');
var pkg = require('./package.json');

// include plug-ins
var eslint = require('gulp-eslint');
var concat = require('gulp-concat');
//var stripDebug = require('gulp-strip-debug');
var terser = require('gulp-terser');
var replace = require('gulp-replace-task');
var sass = require('gulp-sass')(require('sass'));
var sourcemaps = require('gulp-sourcemaps');
var autoprefixer = require('autoprefixer');
var postcss = require('gulp-postcss');
var header = require('gulp-header');
var shell = require('gulp-shell');

// options
var sassOptions = {
  errLogToConsole: true,
  outputStyle: 'compressed' //expanded, nested, compact, compressed
};


// CSS concat, auto-prefix and minify
gulp.task('styles', styles);

function styles(done) {

	gulp.src('./src/sass/*.scss')
		.pipe(sourcemaps.init())
		.pipe(sass(sassOptions).on('error', sass.logError))
		.pipe(concat('style.built.css'))
        .pipe(postcss([ autoprefixer() ]))
		.pipe(sourcemaps.write('./map'))
		.pipe(gulp.dest('./css/'));

  done();
  //console.log('styles ran');
}


// JS concat, strip debugging and minify
gulp.task('scripts', scripts);

function scripts(done) {

	// Run ESLint for src js
    gulp.src('./src/js/*.js')
	.pipe(eslint({fix:true}))
        .pipe(eslint.format());

	// Compile src js
    gulp.src('./src/js/*.js')
        .pipe(concat('script.built.js'))
        //.pipe(stripDebug())
        .pipe(terser())
        .pipe(gulp.dest('./js/'));

	done();
 // console.log('scripts ran');
}


// run codesniffer
gulp.task('sniffs', sniffs);

function sniffs(done) {

    return gulp.src('.', {read:false})
        .pipe(shell(['./.sniff']));

}


// Update plugin version
gulp.task('version', version);

function version(done) {

	gulp.src('./uri-rss-reader.php')
		.pipe(replace({
			patterns: [{
				match: /Version:\s([^\n\r]*)/,
				replace: 'Version: ' + pkg.version
			}]
		}))
		.pipe(gulp.dest('./'));

}


// watch
gulp.task('watcher', watcher);

function watcher(done) {

	// watch for CSS changes
	gulp.watch('./src/sass/*.scss', styles);

    // watch for JS changes
	gulp.watch('./src/js/*.js', scripts);

	// watch for PHP change
    gulp.watch('./**/*.php', sniffs);

	done();
}

gulp.task( 'default',
	gulp.parallel('styles', 'scripts', 'sniffs', 'version', 'watcher', function(done){
		done();
	})
);


function done() {
	console.log('done');
}
