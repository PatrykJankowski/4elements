// Packages
const gulp = require('gulp'),
      sass = require("gulp-sass")(require("node-sass")),
      uglify = require('gulp-uglify-es').default,
      image = require('gulp-image'),
      clean = require('gulp-clean'),
      webp = require('gulp-webp');


gulp.task('clean', function() {
    return gulp.src('dist', { read: false }).pipe(clean());
});

gulp.task('html', () => {
    return gulp.src('./src/*.php')
        .pipe(gulp.dest('./dist'))
});

gulp.task('styles', function() {
    return gulp.src('src/sass/style.scss')
        .pipe(sass().on('error', sass.logError))
        .pipe(sass({outputStyle: 'compressed'}))
        .pipe(gulp.dest('dist'))});

gulp.task('js', function () {
    return gulp.src('src/**/*.js')
        .pipe(uglify({
            compress: {
                global_defs: {
                    DEBUG: process.env.DEBUG || false
                }
            }
        }))
        .pipe(gulp.dest('dist'))
});

gulp.task('json', function () {
    return gulp.src('src/**/*.json')
        .pipe(gulp.dest('dist'))
});

gulp.task('images', function() {
    return gulp.src('src/img/*')
        .pipe(image())
        //.pipe(webp())
        .pipe(gulp.dest('dist/img'))
});

gulp.task('fonts', function() {
    return gulp.src('src/fonts/*')
        .pipe(gulp.dest('dist/fonts'));
});

// Watch tasks
gulp.task('watch', function() {
    gulp.watch('src/**/*.php',gulp.series('html'));
    gulp.watch('src/**/*.scss',gulp.series('styles'));
    gulp.watch('src/**/*.js',gulp.series('js'));
    gulp.watch('src/**/*.json',gulp.series('json'));
    gulp.watch('src/img/*',gulp.series('images'));
});


// Run tasks
gulp.task('default', gulp.series(gulp.parallel(['watch', 'html', 'styles', 'js', 'json', 'images', 'fonts']), function a () {}));
