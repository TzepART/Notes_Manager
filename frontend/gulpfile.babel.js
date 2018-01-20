'use strict';

// This gulpfile makes use of new JavaScript features.
// Babel handles this without us having to do anything. It just works.
// You can read more about the new JavaScript features here:
// https://babeljs.io/docs/learn-es2015/

import path from 'path';
import gulp from 'gulp';
import del from 'del';
import runSequence from 'run-sequence';
import browserSync from 'browser-sync';
import swPrecache from 'sw-precache';
import gulpLoadPlugins from 'gulp-load-plugins';
import pkg from './package.json';
import pug from 'gulp-pug';
import watch from 'gulp-watch';
import gulp_watch_pug from 'gulp-watch-pug';

const $ = gulpLoadPlugins();

// paths to assets
var paths = {
  styles: [
    'app/styles/**/*.scss',
    'app/styles/**/*.css'
  ],
  scripts: {
    js: [
      './app/scripts/main.js',
      './app/scripts/jcanvas.js',
      './app/scripts/script.js'
    ]
  },
  images: [
    'app/assets/images/**/*'
  ],
  fonts: [
    'app/assets/fonts/**/*'
  ]
};

gulp.task('serve:watch', ['serve:dist','pug-watcher']);

// Build and serve the output from the dist build
gulp.task('serve:dist', ['default'], () =>
    browserSync({
      notify: false,
      logPrefix: 'WSK',
      // Allow scroll syncing across breakpoints
      scrollElementMapping: ['main', '.mdl-layout'],
      // Run as an https by uncommenting 'https: true'
      // Note: this uses an unsigned certificate which on first access
      //       will present a certificate warning in the browser.
      // https: true,
      server: 'dist',
      port: 3001,
      reloadDelay: 2000
    })
);

// Build production files, the default task
gulp.task('default', ['clean'], cb =>
    runSequence(
        'styles',
        ['lint', 'html', 'scripts', 'images', 'copy'],
        'copy-bootstrap',
        'copy-fonts',
        'pug-dist',
        // 'generate-service-worker',
        'js-watcher',
        'css-watcher',
        'html-watcher',
        cb
    )
);

// Clean output directory
gulp.task('clean', () => del(['.tmp', 'dist/*', '!dist/.git'], {dot: true}));

// Compile and automatically prefix stylesheets
gulp.task('styles', () => {
  const AUTOPREFIXER_BROWSERS = [
    'ie >= 10',
    'ie_mob >= 10',
    'ff >= 30',
    'chrome >= 34',
    'safari >= 7',
    'opera >= 23',
    'ios >= 7',
    'android >= 4.4',
    'bb >= 10'
  ];

  // For best performance, don't add Sass partials to `gulp.src`
  return gulp.src(paths.styles)
      .pipe($.newer('.tmp/styles'))
      .pipe($.sourcemaps.init())
      .pipe($.sass({
        precision: 10
      }).on('error', $.sass.logError))
      .pipe($.autoprefixer(AUTOPREFIXER_BROWSERS))
      .pipe(gulp.dest('.tmp/styles'))
      // Concatenate and minify styles
      .pipe($.if('*.css', $.cssnano()))
      .pipe($.size({title: 'styles'}))
      .pipe($.sourcemaps.write('./'))
      .pipe(gulp.dest('dist/styles'))
      .pipe(gulp.dest('.tmp/styles'));
});

// Lint JavaScript
// TODO COMMEMT CHECKING JS!!! (It is very bad)
// )
gulp.task('lint', () =>
    gulp.src(['app/scripts/**/*.js','!node_modules/**'])
    // .pipe($.eslint())
    // .pipe($.eslint.format())
        .pipe($.if(!browserSync.active, $.eslint.failAfterError()))
);

// Scan your HTML for assets & optimize them
gulp.task('html', () => {
  return gulp.src('app/**/*.html')
      .pipe($.useref({
        searchPath: '{.tmp,app}',
        noAssets: true
      }))

      // Minify any HTML
      .pipe($.if('*.html', $.htmlmin({
        removeComments: true,
        collapseWhitespace: true,
        collapseBooleanAttributes: true,
        removeAttributeQuotes: true,
        removeRedundantAttributes: true,
        removeEmptyAttributes: true,
        removeScriptTypeAttributes: true,
        removeStyleLinkTypeAttributes: true,
        removeOptionalTags: true
      })))
      // Output files
      .pipe($.if('*.html', $.size({title: 'html', showFiles: true})))
      .pipe(gulp.dest('dist'));
});

// Concatenate and minify JavaScript. Optionally transpiles ES2015 code to ES5.
// to enable ES2015 support remove the line `"only": "gulpfile.babel.js",` in the
// `.babelrc` file.
gulp.task('scripts', () =>
    gulp.src(paths.scripts.js)
        .pipe($.newer('.tmp/scripts'))
        .pipe($.sourcemaps.init())
        .pipe($.babel())
        .pipe($.sourcemaps.write())
        .pipe(gulp.dest('.tmp/scripts'))
        .pipe($.concat('main.min.js'))
        .pipe($.uglify({preserveComments: 'some'}))
        // Output files
        .pipe($.size({title: 'scripts'}))
        .pipe($.sourcemaps.write('.'))
        .pipe(gulp.dest('dist/scripts'))
        .pipe(gulp.dest('.tmp/scripts'))
);


// Optimize images
gulp.task('images', () =>
    gulp.src('app/images/**/*')
        .pipe($.cache($.imagemin({
          progressive: true,
          interlaced: true
        })))
        .pipe(gulp.dest('dist/images'))
        .pipe($.size({title: 'images'}))
);

// Copy all files at the root and bootstrap level (app)
gulp.task('copy', () =>
    gulp.src([
      'app/*',
      '!app/*.html',
      '!app/*.pug',
      'node_modules/apache-server-configs/dist/.htaccess'
    ], {
      dot: true
    }).pipe(gulp.dest('dist'))
        .pipe($.size({title: 'copy'}))
);

gulp.task('copy-bootstrap', () =>
    gulp.src([
      'app/bootstrap/**/*'
    ], {
      dot: true
    }).pipe(gulp.dest('dist/bootstrap'))
        .pipe($.size({title: 'copy'}))
);

gulp.task('copy-fonts', () =>
    gulp.src([
      'app/fonts/*',
    ], {
      dot: true
    }).pipe(gulp.dest('dist/fonts'))
        .pipe($.size({title: 'copy'}))
);

// Load custom tasks from the `tasks` directory
// Run: `npm install --save-dev require-dir` from the command-line
// try { require('require-dir')('tasks'); } catch (err) { console.error(err); }

gulp.task('pug-dist', function buildHTML() {
  return gulp.src('app/*.pug')
      .pipe(pug({ yourTemplate: 'Locals' }))
      .pipe(gulp.dest('dist/'));
});


//pug-watcher
gulp.task('pug-watcher', function buildHTML() {
  return gulp.src('app/**/*.pug')
      .pipe(watch('app/**/*.pug'))
      .pipe(gulp_watch_pug('app/**/*.pug', { delay: 100 }))
      .pipe(pug())
      .pipe(gulp.dest('dist/'));
});

//pug-watcher
gulp.task('html-watcher', function () {
  gulp.watch("dist/**/*.html").on('change', browserSync.reload);
});


//js-watcher
gulp.task('js-watcher', function () {
  gulp.watch(paths.scripts.js, ['clean_js','scripts']);
  gulp.watch("dist/scripts/*").on('change', browserSync.reload);
});

gulp.task('clean_js', () => del(['.tmp', 'dist/scripts/*', '!dist/.git'], {dot: true}));
// Copy all files at the root and bootstrap level (app)

// gulp.task('copy_js', () =>
//     gulp.src([
//       'app/scripts/*',
//     ], {
//       dot: true
//     }).pipe(gulp.dest('dist'))
//         .pipe($.size({title: 'copy'}))
// );



//css-watcher
gulp.task('css-watcher', function () {
  gulp.watch(paths.styles, ['styles', 'clean_css', 'copy_css']);
  gulp.watch("dist/styles/*").on('change', browserSync.reload);
});

gulp.task('clean_css', () => del(['.tmp', 'dist/styles/*', '!dist/.git'], {dot: true}));
// Copy all files at the root and bootstrap level (app)

gulp.task('copy_css', () =>
    gulp.src([
      'app/styles/*',
    ], {
      dot: true
    }).pipe(gulp.dest('dist'))
        .pipe($.size({title: 'copy'}))
);







// ******************************************************************************************************************

/*
// Watch files for changes & reload
gulp.task('serve', ['scripts', 'styles'], () => {
  browserSync({
    notify: false,
    // Customize the Browsersync console logging prefix
    logPrefix: 'WSK',
    // Allow scroll syncing across breakpoints
    scrollElementMapping: ['main', '.mdl-layout'],
    // Run as an https by uncommenting 'https: true'
    // Note: this uses an unsigned certificate which on first access
    //       will present a certificate warning in the browser.
    // https: true,
    server: ['.tmp', 'app'],
    port: 3000
  });

  gulp.watch(['app/!**!/!*.html'], reload);
  gulp.watch(['app/styles/!**!/!*.{scss,css}'], ['styles', reload]);
  gulp.watch(['app/scripts/!**!/!*.js'], ['lint', 'scripts', reload]);
  gulp.watch(['app/images/!**!/!*'], reload);
});


// Run PageSpeed Insights
gulp.task('pagespeed', cb =>
    // Update the below URL to the public URL of your site
    pagespeed('example.com', {
      strategy: 'mobile'
      // By default we use the PageSpeed Insights free (no API key) tier.
      // Use a Google Developer API key if you have one: http://goo.gl/RkN0vE
      // key: 'YOUR_API_KEY'
    }, cb)
);

gulp.task('watch', function() {
  gulp.watch(['dist/!**!/!*.html'], reload);
});

*/

// See http://www.html5rocks.com/en/tutorials/service-worker/introduction/ for
// an in-depth explanation of what service workers are and why you should care.
// Generate a service worker file that will provide offline functionality for
// local resources. This should only be done for the 'dist' directory, to allow
// live reload to work as expected when serving from the 'app' directory.
gulp.task('generate-service-worker', ['copy-sw-scripts'], () => {
  const rootDir = 'dist';
  const filepath = path.join(rootDir, 'service-worker.js');

  return swPrecache.write(filepath, {
    // Used to avoid cache conflicts when serving on localhost.
    cacheId: pkg.name || 'web-starter-kit',
    // sw-toolbox.js needs to be listed first. It sets up methods used in runtime-caching.js.
    importScripts: [
      'scripts/sw/sw-toolbox.js',
      'scripts/sw/runtime-caching.js'
    ],
    staticFileGlobs: [
      // Add/remove glob patterns to match your directory setup.
      `${rootDir}/images/**/*`,
      `${rootDir}/scripts/**/*.js`,
      `${rootDir}/styles/**/*.css`,
      `${rootDir}/*.{html,json}`
    ],
    // Translates a static file path to the relative URL that it's served from.
    // This is '/' rather than path.sep because the paths returned from
    // glob always use '/'.
    stripPrefix: rootDir + '/'
  });
});

// Copy over the scripts that are used in importScripts as part of the generate-service-worker task.
gulp.task('copy-sw-scripts', () => {
  return gulp.src(['node_modules/sw-toolbox/sw-toolbox.js', 'app/scripts/sw/runtime-caching.js'])
      .pipe(gulp.dest('dist/scripts/sw'));
});
