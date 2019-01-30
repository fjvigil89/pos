//GLOBAL JAVASCRIPT FILES
var core_plugins =[
    'assets/global/plugins/jquery.min.js','assets/global/plugins/jquery-migrate.min.js','assets/global/plugins/bootstrap-toastr/toastr.min.js',
    'js/jquery-ui.min.js','js/fileinput.min.js','js/moment-with-locales.js','js/bootstrap.min.js','js/bootstrap-datetimepicker.min.js',
    'js/jquery.validate_1.14.js','js/jquery.form.js','js/common.js','js/manage_tables.js','js/icheck.js','js/jquery.plainoverlay.js',
    'js/datatables/jquery.dataTables.js','js/datatables/dataTables.bootstrap.js','js/datatables/dataTables.responsive.js',
    'js/jquery.imagerollover.js','js/jquery.KeyTips.js','js/jquery.countTo.js','js/jquery.tokeninput.js','js/keyboard/jquery.keyboard.js',
    'js/keyboard/jquery.mousewheel.js','js/keyboard/jquery.keyboard.extension-all.js','js/iscroll.js','js/jquery.floatThead.js',
    'js/jquery.tablesorter.min.js','js/jquery.hideseek.js'
];

var charts =[
    'js/amcharts/amcharts.js','js/amcharts/serial.js','js/amcharts/pie.js','js/amcharts/light.js','js/jquery.flot.min.js','js/jquery.flot.pie.min.js','js/jquery.flot.time.min.js'
];

var page_level_plugins = [
    'assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js','assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js',
    'assets/global/plugins/jquery.cokie.min.js" type="text/javascript','assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js',
    'assets/global/plugins/backstretch/jquery.backstretch.min.js','assets/global/plugins/select2/select2.min.js','assets/global/plugins/bootstrap-select/bootstrap-select.min.js',
    'assets/global/plugins/jquery-multi-select/js/jquery.multi-select.js','assets/global/plugins/fuelux/js/spinner.js',
    'assets/global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js','assets/global/plugins/fancybox/source/jquery.fancybox.js'
];

var page_level_scripts = [
    'assets/global/scripts/metronic.js','assets/admin/layout4/scripts/layout.js','assets/admin/layout4/scripts/demo.js','assets/admin/pages/scripts/login-soft.js',
    'assets/admin/pages/scripts/components-dropdowns.js'
];

//GLOBAL CSS FILES

var global_mandatory_styles = [
    'assets/global/plugins/font-awesome/css/font-awesome.min.css','assets/global/plugins/simple-line-icons/simple-line-icons.min.css',
    'assets/global/plugins/bootstrap/css/bootstrap.css','css/jquery-ui.css','assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css'
];

var page_level_styles = [
    'assets/global/plugins/bootstrap-select/bootstrap-select.min.css','assets/global/plugins/select2/select2.css','assets/admin/pages/css/login-soft.css',
    'assets/global/plugins/jquery-multi-select/css/multi-select.css','assets/global/plugins/bootstrap-toastr/toastr.css'
];

var theme_styles = [
    'assets/global/css/components-md.css','assets/global/css/plugins-md.css','assets/admin/layout4/css/layout.css','assets/admin/layout4/css/themes/default.css',
    'assets/admin/layout4/css/custom.css'
];

var other_css = [
    'css/fileinput.min.css','css/custom_styles.css','css/subscription_cancelled.css','css/pricing.css','css/checkbox_style.css','css/square/green.css','assets/global/plugins/fancybox/source/jquery.fancybox.css',
    'css/KeyTips.css','css/token-input-facebook.css','css/keyboard/keyboard.css','css/flags/flags.css','css/flags/flag-icon.css','css/bootstrap-datetimepicker.min.css'
];

var datatables =[
    'css/datatables/dataTables.bootstrap.css','css/datatables/responsive.bootstrap.css'    
];

module.exports = function(grunt) {

    // Project configuration.
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        uglify:{
            my_target:{
                files:{
                    'bin/bin.min.js':[core_plugins,charts,page_level_plugins,page_level_scripts]
                }
            }
        },
        cssmin: {
            dist: {
                options : {
                    rebase: true,
                },
                files: {
                    'bin/bin.min.css':[global_mandatory_styles,page_level_styles,theme_styles,other_css,datatables]
                }
            }
        }
    });

    // Load the plugins that provides each task.
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-cssmin');

    // Default task(s).
    grunt.registerTask('default', ['cssmin','uglify']);

};