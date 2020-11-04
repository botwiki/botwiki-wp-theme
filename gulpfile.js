var gulp = require('gulp'),
    sass = require('gulp-sass'),
    less = require('gulp-less'),
    path = require('path'),
    streamify = require('gulp-streamify'),
    autoprefixer = require('gulp-autoprefixer'),
    minifycss = require('gulp-minify-css'),
    browserify = require('browserify'),
    babelify = require('babelify'),
    sourcemaps = require('gulp-sourcemaps'),
    babel = require('gulp-babel'),
    source = require('vinyl-source-stream'),
    gutil = require('gulp-util'),
    jshint = require('gulp-jshint'),
    stylish = require('jshint-stylish'),
    uglify = require('gulp-uglify'),
    rename = require('gulp-rename'),
    clean = require('gulp-clean'),
    concat = require('gulp-concat'),
    gutil = require('gulp-util'),
    notify = require('gulp-notify'),
    browserSync = require('browser-sync'),
    reload = browserSync.reload,
    criticalCss = require('gulp-penthouse'),
    penthouse = require('penthouse-pages');

function swallow_error (error) {
  console.log(error.toString());
  this.emit('end');
}


gulp.task('critical-css', function () {
    return gulp.src('./css/styles.min.css')
          .pipe(criticalCss({
              out: './styles.css',
              url: 'http://botwiki.local/bot/emoji__polls',
              width: 1300,
              height: 900,
              strict: true,
              userAgent: 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)'
          }))
          .pipe(minifycss())
          .on('error', swallow_error)        
          .pipe(gulp.dest('./css-critical/'));
});

// gulp.task('critical-css', function() {
//     return penthouse({
//         pages: [
//             {
//                 name: 'home',
//                 url: '',
//             },
//             {
//                 name: 'bot',
//                 url: '/bot/emoji__polls/',
//             },
//         ],
//         baseUrl: 'http://botwiki.local/',
//         dest: './css-critical/',
//         css: './css/styles.min.css',
//         width: 1300,
//         height: 900,
//         strict: true,
//         userAgent: 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)'
//     });
// });


// gulp.task('critical-css', function () {
//     return gulp.src('./css/styles.min.css')
//         .pipe(criticalCss({
//             pages: [
//                 {
//                     name: 'home',
//                     url: 'http://botwiki.local/',
//                 },
//                 {
//                     name: 'bot',
//                     url: 'http://botwiki.local/bot/emoji__polls/',
//                 },
//             ],
//             baseUrl: 'http://botwiki.local/',
//             dest: './css-critical/',
//             css: './css/styles.min.css',
//             width: 1300,
//             height: 900,
//             strict: true,
//             userAgent: 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)'
//         }))
//         // .pipe(cssNano({
//         //   safe:true // this isn't required, but I've included cssNano to minify the output file
//         // }))
//         .pipe(gulp.dest('./css-critical/')); // destination folder for the output file
// } );

gulp.task('browser-sync', function () {
   var files = [
      'css/*.css',
      'js/*.js'
   ];

   browserSync.init(files, {
      proxy: "http://localhost/botwiki-v2/",
      open: false
   });
});

gulp.task('styles', function() {
  return gulp.src('src/styles/styles.scss')
    .pipe(sass({
      paths: [ path.join(__dirname, 'scss', 'includes') ]
    }))
    .on('error', swallow_error)
    .pipe(autoprefixer('last 3 version', 'android >= 3', { cascade: true }))
    .on('error', swallow_error)
    .pipe(gulp.dest('css'))
    .pipe(rename({suffix: '.min'}))
    .pipe(minifycss())
    .on('error', swallow_error)
    .pipe(gulp.dest('css'))
    .pipe(reload({stream:true}));
});

gulp.task('admin-styles', function() {
  return gulp.src('src/admin-styles/*.scss')
    .pipe(sass({
      paths: [ path.join(__dirname, 'scss', 'includes') ]
    }))
    .on('error', swallow_error)
    .pipe(autoprefixer('last 3 version', 'android >= 3', { cascade: true }))
    .on('error', swallow_error)
    .pipe(gulp.dest('admin-css'))
    .pipe(rename({suffix: '.min'}))
    .pipe(minifycss())
    .on('error', swallow_error)
    .pipe(gulp.dest('admin-css'))
    .pipe(reload({stream:true}));
});

gulp.task('admin-scripts', function() {
  gulp.src('src/admin-scripts/*.js')
    .pipe(sourcemaps.init())
    .pipe(babel({
        presets: ['env']
    }))
    .on('error', swallow_error)
    .pipe(concat('scripts.js'))
    .pipe(uglify())
    .pipe(rename({suffix: '.min'}))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest('./admin-js'))
});

gulp.task('scripts', function() {
  gulp.src('src/scripts/*.js')
    .pipe(sourcemaps.init())
    .pipe(babel({
        presets: ['env']
    }))
    .on('error', swallow_error)
    .pipe(concat('scripts.js'))
    .pipe(uglify())
    .pipe(rename({suffix: '.min'}))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest('./js'))
});

gulp.task('jslint', function(){
  return gulp.src([
      './src/scripts/**/*.js'
    ]).pipe(jshint('tests/.jshintrc'))
    .on('error',gutil.noop)
    .pipe(jshint.reporter(stylish))
    // .pipe(jshint.reporter('default'))
    .on('error', swallow_error);
});

gulp.task('clean', function() {
  return gulp.src(['css', 'js', 'admin-css', 'admin-js'], {read: false})
    .pipe(clean());
});

gulp.task('watch', function() {
  gulp.watch('src/admin-styles/**/*.*', ['admin-styles','critical-css']);
  gulp.watch('src/styles/**/*.*', ['styles','critical-css']);
  gulp.watch('src/admin-scripts/**/*.*', ['jslint', 'admin-scripts']);
  gulp.watch('src/scripts/**/*.*', ['jslint', 'scripts']);
});

gulp.task('default', ['clean'], function() {
    gulp.start('styles', 'critical-css', 'admin-styles', 'jslint', 'admin-scripts', 'scripts', 'browser-sync', 'watch');
});