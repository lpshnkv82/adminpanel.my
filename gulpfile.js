var gulp = require('gulp'), // Подключаем Gulp
    sass = require('gulp-sass'), //Подключаем Sass пакет,
    browserSync = require('browser-sync'); // Подключаем Browser Sync

gulp.task('sass', function() { // Создаем таск Sass
    return gulp.src('public_html/app/sass/**/*.sass') // Берем источник
        .pipe(sass()) // Преобразуем Sass в CSS посредством gulp-sass
        .pipe(gulp.dest('public_html/template/admin/css')) // Выгружаем результата в папку app/css
        .pipe(browserSync.reload({ stream: true })) // Обновляем CSS на странице при изменении
});

gulp.task('browser-sync', function() { // Создаем таск browser-sync
    browserSync({ // Выполняем browserSync
        server: { // Определяем параметры сервера
            baseDir: 'public_html' // Директория для сервера - app
        },
        notify: false // Отключаем уведомления
    });
});

gulp.task('watch', ['browser-sync', 'sass'], function() {
    gulp.watch('public_html/app/sass/**/*.sass', ['sass']); // Наблюдение за sass файлами в папке sass
    gulp.watch('public_html/app/*.html', browserSync.reload); // Наблюдение за HTML файлами в корне проекта
    gulp.watch('public_html/app/js/**/*.js', browserSync.reload); // Наблюдение за JS файлами в папке js
});

gulp.task('default', ['watch']);