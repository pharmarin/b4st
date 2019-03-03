import gulp from 'gulp';
import log from 'fancy-log';
import sass from 'gulp-sass';
import sourcemaps from 'gulp-sourcemaps';
import autoprefixer from 'gulp-autoprefixer';
import babel from 'gulp-babel';
import plumber from 'gulp-plumber';
import concat from 'gulp-concat';
import terser from 'gulp-terser';
import rename from 'gulp-rename';
import cleanCSS from 'gulp-clean-css';
import del from 'del';
import realFavicon from 'gulp-real-favicon';
import fs from 'fs';
import browserSync from 'browser-sync';
const server = browserSync.create();

const paths = {
  styles: {
    src: 'src/scss/**/*.scss',
    dest: 'theme/css/'
  },
  scripts: {
    src: 'node_modules/bootstrap/dist/js/*.js', //'node_modules/bootstrap/js/dist/**/*.js', //'node_modules/bootstrap/js/src/**/*.js',
    dest: 'theme/js/'
  },
  customScripts: {
    src: 'src/js/*.js',
    dest: 'theme/js/'
  },
  favicons: {
    src: 'src/images/icon.png',
    dest: 'favicons/'
  }
};

var FAVICON_DATA_FILE = 'faviconData.json';

/*
 * For small tasks you can export arrow functions
 */
export const clean = () => del([ 'theme' ]);

export function serverInit () {
  server.init({
    proxy: "https://pharmacie.local",
    ghostMode: false,
    open: false,
    notify: false
  })
}

/*
 * You can also declare named functions and export them as tasks
 */
export function styles() {
  return gulp.src('src/scss/styles.scss')
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

export function scripts() {
  return gulp.src(paths.scripts.src, { sourcemaps: true })
    .pipe(babel())
    .pipe(terser())
    .pipe(concat('bootstrap.min.js'))
    .pipe(gulp.dest(paths.scripts.dest))
    .pipe(server.reload({ stream: true }))
}

export function customScripts() {
  return gulp.src(paths.customScripts.src, { sourcemaps: true })
    .pipe(babel())
    .pipe(terser())
    .pipe(gulp.dest(paths.customScripts.dest))
    .pipe(server.reload({ stream: true }))
}

 /*
  * You could even use `export as` to rename exported tasks
  */
function watchFiles() {
  console.log("watchFiles started")
  gulp.watch(paths.scripts.src, function watchStyles () {
    console.log("Change in styles")
    styles
  });
  gulp.watch(paths.styles.src, styles);
  gulp.watch(paths.scripts.src, scripts);
  gulp.watch(paths.customScripts.src, customScripts);
  gulp.watch("./*.php").on("change", server.reload);
}

const build = gulp.series(clean, gulp.parallel(styles, scripts, customScripts));

export const watch = gulp.parallel(serverInit, gulp.series(build, watchFiles));

export default build;

export function favicon () {
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
