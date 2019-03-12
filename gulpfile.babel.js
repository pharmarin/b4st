import gulp from 'gulp';
import HubRegistry from 'gulp-hub';
import browserSync from 'browser-sync';
import log from 'fancy-log';

import { paths } from './gulp/paths';


var hub = new HubRegistry(['gulp/*.js']);
gulp.registry(hub);

const server = browserSync.create();

/**
 * Init BrowserSync server
 * @return {none}
 */

function initServer (done) {
  server.init({
    proxy: "https://pharmacie.local",
    //ghostMode: false,
    //notify: false
  })
  done()
}

gulp.task('init-server', initServer)

/**
 * Reload BrowserSync server after change in files
 * @return {none}
 */

function reloadServer (done) {
  server.reload({ stream: false })
  done()
}

gulp.task('reload-server', reloadServer)

/**
 * Watch file change
 * @return {none}
 */

function watchFiles() {
  gulp.watch(paths.styles.src, gulp.series('build-css', 'reload-server'))
  gulp.watch(paths.scripts.src, gulp.series('build-scripts', 'reload-server'))
  gulp.watch(paths.customScripts.src, gulp.series('build-custom-scripts', 'reload-server'))
  gulp.watch(paths.react.watch, gulp.series('build-react', 'reload-server'))
  gulp.watch("./*.php", gulp.series('reload-server'))
}

gulp.task('watch-files', watchFiles)

gulp.task('watch', gulp.series('build-all', gulp.parallel('watch-files', 'init-server')))

gulp.task('build-css', gulp.series('lint-styles', 'build-styles'))
gulp.task('build-js', gulp.series('lint-scripts', 'build-scripts'))
gulp.task('build-custom-js', gulp.series('lint-custom-scripts', 'build-custom-scripts'))
gulp.task('build-react', gulp.series('compile-react'))

gulp.task('build-all', gulp.parallel('build-css', 'build-js', 'build-custom-js', 'build-react'))

gulp.task('build', gulp.series('clean-folder', 'build-all'))
gulp.task('precommit', gulp.series('build'))

gulp.task('test', gulp.series('phpTest'))
