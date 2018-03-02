var gulp = require('gulp'),
    sass = require('gulp-sass'),
    less = require('gulp-less'),
    path = require('path'),
    streamify = require('gulp-streamify'),
    autoprefixer = require('gulp-autoprefixer'),
    minifycss = require('gulp-minify-css'),
    browserify = require('browserify'),
    babelify = require('babelify'),
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
    sourcemaps = require('gulp-sourcemaps');

function swallow_error (error) {
  console.log(error.toString());
  this.emit('end');
}

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

gulp.task('scripts', function() {
  return browserify({ debug: true })
  .transform("babelify", {presets: ["es2015"]})
  .on('error', swallow_error)
  .require("./src/scripts/scripts.js", { entry: true })
  .on('error',swallow_error)
  .bundle()
  .on('error',swallow_error)
  .pipe(source('scripts.js'))
  .on('error', swallow_error)
  .pipe(gulp.dest('./js'))
  .on('error',swallow_error)
  .pipe(streamify(uglify()))
  .on('error',swallow_error)
  .pipe(rename({suffix: '.min'}))
  .on('error',swallow_error)
  .pipe(gulp.dest('./js'))
  .on('error',swallow_error)
  .pipe(reload({stream:true}))
  .on('error', swallow_error);
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
  return gulp.src(['css', 'js', 'admin-css'], {read: false})
    .pipe(clean());
});

gulp.task('watch', function() {
  gulp.watch('src/admin-styles/**/*.*', ['admin-styles']);
  gulp.watch('src/styles/**/*.*', ['styles']);
  gulp.watch('src/scripts/**/*.*', ['jslint', 'scripts']);
});

gulp.task('default', ['clean'], function() {
    gulp.start('styles', 'admin-styles', 'jslint', 'scripts', 'browser-sync', 'watch');
});