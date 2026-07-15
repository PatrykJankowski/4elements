// Packages
const gulp = require('gulp'),
      uglify = require('gulp-uglify-es').default,
      //image = require('gulp-image'),
      clean = require('gulp-clean'),
      childProcess = require('child_process'),
      path = require('path');


gulp.task('clean', function() {
    return gulp.src('dist', { read: false }).pipe(clean());
});

gulp.task('html', () => {
    return gulp.src('./src/**/*.php')
        .pipe(gulp.dest('./dist'))
});

gulp.task('styles', function() {
    const sass = require("gulp-sass")(require("sass"));
    return gulp.src([
        'src/sass/style.scss',
        'src/sass/home.scss',
        'src/sass/content.scss',
        'src/sass/blog-page.scss',
        'src/sass/reviews-page.scss'
    ])
        .pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError))
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

gulp.task('text', function () {
    return gulp.src(['src/*.txt', 'src/*.xml'])
        .pipe(gulp.dest('dist'));
});

gulp.task('images', function() {
    return gulp.src('src/img/*')
        //.pipe(image())
        .pipe(gulp.dest('dist/img'))
});

gulp.task('webp', async function() {
    const cwebp = await import('cwebp-bin');

    const imageJobs = [
        ['nauka_plywania_dla_dzieci@1920.jpg', 'nauka_plywania_dla_dzieci@1920.webp', null, '76'],
        ['obozy_zimowe@1920.jpg', 'obozy_zimowe@1920.webp', null, '76'],
        ['nauka_plywania_dla_dzieci@1920.jpg', 'nauka_plywania_dla_dzieci@1080.webp', '1080', '74'],
        ['obozy_zimowe@1920.jpg', 'obozy_zimowe@1080.webp', '1080', '74']
    ];

    await Promise.all(imageJobs.map(function(files) {
        return new Promise(function(resolve, reject) {
            const args = ['-quiet', '-q', files[3], '-m', '6'];
            if (files[2]) {
                args.push('-resize', files[2], '0');
            }
            args.push(path.resolve('src/img', files[0]), '-o', path.resolve('dist/img', files[1]));

            childProcess.execFile(cwebp.default, args, function(error) {
                if (error) {
                    reject(error);
                    return;
                }
                resolve();
            });
        });
    }));
});

// Watch tasks
gulp.task('watch', function() {
    gulp.watch('src/**/*.php',gulp.series('html'));
    gulp.watch('src/**/*.scss',gulp.series('styles'));
    gulp.watch('src/**/*.js',gulp.series('js'));
    gulp.watch('src/**/*.json',gulp.series('json'));
    gulp.watch('src/*.txt',gulp.series('text'));
    gulp.watch('src/img/*',gulp.series('images', 'webp'));
});


// Run tasks
gulp.task('default', gulp.series(gulp.parallel(['watch', 'html', 'styles', 'js', 'json', 'text', 'images']), function a () {}));
