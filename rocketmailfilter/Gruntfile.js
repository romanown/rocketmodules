module.exports = function (grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        uglify: {
            rocketmailfilter: {
                files: {
                    'resources/js/humhub.rocketmailfilter.min.js': ['resources/js/humhub.rocketmailfilter.js'],
                }
            }
        },
        watch: {
            scripts: {
                files: ['resources/js/*.js', 'resources/css/*.css'],
                tasks: ['build'],
                options: {
                    spawn: false,
                },
            },
        },
    });

    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-watch');

    grunt.registerTask('build', ['uglify']);
};
