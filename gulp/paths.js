export const paths = {
  php: {
    src: './**/*.php'
  },
  styles: {
    src: './src/scss/**/*.scss',
    dest: './theme/css/'
  },
  scripts: {
    src: './node_modules/bootstrap/dist/js/*.js', //'node_modules/bootstrap/js/dist/**/*.js', //'node_modules/bootstrap/js/src/**/*.js',
    dest: './theme/js/'
  },
  customScripts: {
    src: './src/js/*.js',
    dest: './theme/js/'
  },
  react: {
    src: [
      './src/js/react-app.js',
      //'!./node_modules/**'
    ],
    dest: './theme/js/'
  },
  components: {
    src: [
      './src/js/react/*.js',
      '!./node_modules/**'
    ],
    dest: './theme/js/react/'
  },
  favicons: {
    src: './src/images/icon.png',
    dest: './favicons/'
  }
};

export const FAVICON_DATA_FILE = '../faviconData.json';
