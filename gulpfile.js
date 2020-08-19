var gulp = require('gulp');
var wpPot = require('gulp-wp-pot');

gulp.task('default', function () {
    return gulp.src('./**/*.php')
        .pipe(wpPot( {
            domain: 'addonify-compare-products',
            package: 'Addonify_Compare_Products'
        } ))
        .pipe(gulp.dest('languages/addonify-compare-products.pot'));
});
