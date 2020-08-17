var gulp = require('gulp');
var wpPot = require('gulp-wp-pot');

gulp.task('default', function () {
    return gulp.src('./**/*.php')
        .pipe(wpPot( {
            domain: 'addonify-compare-products',
            package: 'addonify-compare-products'
        } ))
        .pipe(gulp.dest('languages/addonify-compare-products.pot'));
});
