import gulp from 'gulp';
import phplint from 'gulp-phplint';

/**
 * Lint php files of folder and subfolders
 * @return {none}
 */

function phpTest () {
  return gulp.src('./**/*.php')
    .pipe(phplint());
}

gulp.task('phpTest', phpTest)
