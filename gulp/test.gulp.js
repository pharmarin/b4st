import gulp from 'gulp';
import phplint from 'gulp-phplint';
import scssLint from 'gulp-scss-lint';
import eslint from 'gulp-eslint';
import log from 'fancy-log';

import { paths } from './paths.js';

/**
 * Lint php files of folder and subfolders
 * @return {none}
 */

function lintPHP () {
  return gulp.src(paths.php.src)
    .pipe(phplint());
}

gulp.task('lint-php', lintPHP)


function lintStyles () {
  return gulp.src(paths.styles.src)
    .pipe(scssLint())
}

gulp.task('lint-styles', lintStyles)


function lintScripts () {
  return Promise.resolve(
    log("Lint scripts")
  )
  /*.pipe(eslint())
  // eslint.format() outputs the lint results to the console.
  .pipe(eslint.format())
  // To have the process exit with an error code (1)
  .pipe(eslint.failAfterError());*/
}

gulp.task('lint-scripts', lintScripts)


function lintCustomScripts () {
  return Promise.resolve(
    log("Lint custom scripts")
  )
  /*.pipe(eslint())
  // eslint.format() outputs the lint results to the console.
  .pipe(eslint.format())
  // To have the process exit with an error code (1)
  .pipe(eslint.failAfterError());*/
}

gulp.task('lint-custom-scripts', lintCustomScripts)
