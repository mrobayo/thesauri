module.exports = function(grunt) {
  grunt.initConfig({
	clean: {
	  dist: {
		  src: ['public/vendor/*/*', '!public/vendor/*/dist']
	  }
	},
  });
  grunt.loadNpmTasks('grunt-contrib-clean');
  grunt.registerTask('delete', ['clean:dist'])
};

