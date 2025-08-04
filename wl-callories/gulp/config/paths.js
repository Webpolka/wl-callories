import path from 'path';

const buildFolder = './dist/wl-callories';
const srcFolder = './src';

const filePaths = {
  build: {
    js: `${buildFolder}/js/`,
    css: `${buildFolder}/css/`,
    postcss: `${buildFolder}/postcss/`,
    images: `${buildFolder}/images/`,
    svg: `${buildFolder}/icons/`,
    fonts: `${buildFolder}/fonts/`,
    static: `${buildFolder}/vendor/`,
  },
  src: {
    js: `${srcFolder}/js/*.js`,
    images: `${srcFolder}/images/**/*.{jpg,jpeg,png,gif,webp}`,
    svg: `${srcFolder}/icons/*.svg`,
    scss: [`${srcFolder}/scss/main.scss`, `!${srcFolder}/scss/**/_*.scss`, `${srcFolder}/scss/pages/*.scss`],
    postcss: [`${buildFolder}/css/*.css`,`${buildFolder}/css/**/*.css`],
    html: `${srcFolder}/*.html`,
    static: `${srcFolder}/vendor/**/*.*`,
    svgIcons: `${srcFolder}/icons/*.svg`,
    fontFacesFile: `${srcFolder}/scss/config/fonts.scss`,
    fonts: `${srcFolder}/fonts/`,
  },
  watch: {
    js: `${srcFolder}/js/**/*.js`,
    scss: `${srcFolder}/scss/**/*.scss`,
    html: `${srcFolder}/**/*.html`,
    images: `${srcFolder}/**/*.{jpg,jpeg,png,svg,gif,webp,ico}`,
    svg: `${srcFolder}/icons/*.svg`,
    static: `${srcFolder}/vendor/**/*.*`,
  },
  buildFolder,
  srcFolder,
  projectDirName: path.basename(path.resolve()),
  ftp: ``, // Путь к нужной папке на удаленном сервере. Gulp добавит имя папки проекта автоматически
};

export { filePaths };