import gulp from 'gulp';
import HubRegistry from 'gulp-hub';
import browserSync from 'browser-sync';
import log from 'fancy-log';

import { paths } from './gulp/paths.js';

var hub = new HubRegistry(['gulp/*.js']);
gulp.registry(hub);

const server = browserSync.create();

/**
 * Init BrowserSync server
 * @return {none}
 */

function initServer () {
  server.init({
    proxy: "https://pharmacie.local",
    ghostMode: false,
    open: false,
    notify: false
  })
}

gulp.task('init-server', initServer)

/**
 * Reload BrowserSync server after change in files
 * @return {none}
 */

function reloadServer () {
  server.reload({ stream: true })
}

gulp.task('reload-server', reloadServer)

/**
 * Watch file change
 * @return {[type]} [description]
 */

function watchFiles() {
  gulp.series('build-all')
  log("Begin watching files")
  gulp.watch(paths.styles.src, () => {
    log("Change in styles")
    gulp.series('build-styles', 'reload-server')()
  });
  gulp.watch(paths.scripts.src, () => {
    log("Change in scripts")
    gulp.series('build-scripts', 'reload-server')()
  });
  gulp.watch(paths.customScripts.src, () => {
    log("Change in custom scripts")
    gulp.series('build-custom-scripts', 'reload-server')()
  });
  gulp.watch("./*.php").on("change", server.reload);
}

gulp.task('watch', watchFiles)

gulp.task('build-all', gulp.parallel('build-styles', 'build-scripts', 'build-custom-scripts'))

gulp.task('build', gulp.series('clean-folder', 'build-all'))

gulp.task('test', gulp.series('phpTest'))
