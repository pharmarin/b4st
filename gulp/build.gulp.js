import gulp from 'gulp';
import babel from 'gulp-babel';
import plumber from 'gulp-plumber';
import browserify from 'browserify';
import babelify from 'babelify';
import source from "vinyl-source-stream";
import buffer from 'vinyl-buffer';
import rename from 'gulp-rename';
import merge from 'merge-stream';
import concat from 'gulp-concat';
import fs from 'fs';
import del from 'del';
import log from 'fancy-log';
//CSS
import sass from 'gulp-sass';
import cleanCSS from 'gulp-clean-css';
import sourcemaps from 'gulp-sourcemaps';
import autoprefixer from 'gulp-autoprefixer';
//JS
import terser from 'gulp-terser';
//Favicons
import realFavicon from 'gulp-real-favicon';

import { server } from './server'
import { paths, FAVICON_DATA_FILE } from './paths';

/**
 * Delete theme folder before build phase
 * @return {none}
 */

const clean = () => del([ 'theme' ]);

gulp.task('clean-folder', clean)

/**
 * Build styles from SCSS using gulp-sass
 * @return {none}
 */

function buildStyles() {
  return gulp.src(paths.styles.src)
    .pipe(plumber())
    .pipe(sourcemaps.init())
    .pipe(sass())
    .pipe(autoprefixer({ browsers: ["last 2 versions"] })) // Auto préxifer pour la rétrocompatibilité
    .pipe(cleanCSS())
    .pipe(rename({
      basename: 'bootstrap',
      suffix: '.min'
    }))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest(paths.styles.dest))
    .pipe(server.reload({ stream: true }))
}

gulp.task('build-styles', buildStyles)

/**
 * Build scripts from Bootstrap source
 * TODO : à compléter
 * @return {none}
 */

function buildScripts() {
  return gulp.src(paths.scripts.src, { sourcemaps: true })
    .pipe(babel())
    .pipe(terser())
    .pipe(concat('bootstrap.min.js'))
    .pipe(gulp.dest(paths.scripts.dest))
}

gulp.task('build-scripts', buildScripts)

/**
 * Build custom scripts
 * @return {none}
 */

function buildCustomScripts() {
  return gulp.src(paths.customScripts.src, { sourcemaps: true })
    .pipe(babel())
    .pipe(terser())
    .pipe(gulp.dest(paths.customScripts.dest))
}

gulp.task('build-custom-scripts', buildCustomScripts)

/**
 * Build React components
 * @return {none}
 */

function buildReact () {
  return browserify({ debug: true })
    .transform(babelify.configure({ presets: ["@babel/preset-env", "@babel/react"] }))
    .require(paths.react.src, { entry: true })
    .bundle()
    .pipe(source('react-app.js'))
    .pipe(gulp.dest(paths.react.dest));
}

gulp.task('compile-react', buildReact)

/**
 * Parses the package.json file. We use this because its values
 * change during execution.
 * @return {json} Parsed package.json
 */
var getPackageJSON = function() {
  return JSON.parse(fs.readFileSync('./package.json', 'utf8'));
};

/**
 * Update version in style.css from package.json
 */
function updateVersion () {
  var pkg = getPackageJSON();
  var banner = ['/*',
  'Theme Name: ' + pkg.description,
  'Theme URI: '+ pkg.uri,
  'Author: '+ pkg.author,
  'Version: '+ pkg.version,
  'License: '+ pkg.license,
  'License URI: '+ pkg.licenseuri,
  '*/',
  ''].join('\n');
  return Promise.resolve (
    fs.writeFile('./style.css', banner, (err) => {
      if (err) throw err;
      console.log('The file has been saved!');
    })
  )
}

gulp.task('update-version', updateVersion)


/**
 * Function that create favicons from source
 * to different browsers and OSs.
 * @return {Promise} Promise for gulp, write "Favicon folder build" when OK.
 */

export function generateFavicons () {
  return Promise.resolve (
    realFavicon.generateFavicon({
  		masterPicture: paths.favicons.src,
  		dest: paths.favicons.dest,
  		iconsPath: '/favicons/',
  		design: {
  			ios: {
  				pictureAspect: 'backgroundAndMargin',
  				backgroundColor: '#ffffff',
  				margin: '14%',
  				assets: {
  					ios6AndPriorIcons: true,
  					ios7AndLaterIcons: true,
  					precomposedIcons: true,
  					declareOnlyDefaultIcon: true
  				},
  				appName: 'Pharmacie'
  			},
  			desktopBrowser: {},
  			windows: {
  				pictureAspect: 'whiteSilhouette',
  				backgroundColor: '#17cebd',
  				onConflict: 'override',
  				assets: {
  					windows80Ie10Tile: true,
  					windows10Ie11EdgeTiles: {
  						small: true,
  						medium: true,
  						big: true,
  						rectangle: true
  					}
  				},
  				appName: 'Pharmacie'
  			},
  			androidChrome: {
  				pictureAspect: 'shadow',
  				themeColor: '#17cebd',
  				manifest: {
  					name: 'Pharmacie',
  					display: 'standalone',
  					orientation: 'notSet',
  					onConflict: 'override',
  					declared: true
  				},
  				assets: {
  					legacyIcon: false,
  					lowResolutionIcons: false
  				}
  			},
  			safariPinnedTab: {
  				pictureAspect: 'silhouette',
  				themeColor: '#17cebd'
  			}
  		},
  		settings: {
  			scalingAlgorithm: 'Mitchell',
  			errorOnImageTooSmall: false,
  			readmeFile: false,
  			htmlCodeFile: true,
  			usePathAsIs: false
  		},
  		markupFile: FAVICON_DATA_FILE
  	}, function() {
  		log("Favicon folder build");
  	})
  )
}

gulp.task('generate-favicons', generateFavicons)
