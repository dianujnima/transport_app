let mix = require('laravel-mix');

//frontend
// mix.styles([
//    'resources/frontend/css/bootstrap.min.css',
//    'resources/frontend/css/jquery-ui.min.css',
//    'resources/frontend/css/font-awesome.min.css',
//    'resources/frontend/css/font-awesome-animation.min.css',
//    'resources/frontend/css/menu.css',
//    'resources/frontend/css/ace-responsive-menu.css',
//    'resources/frontend/css/megadropdown.css',
//    'resources/frontend/css/bootstrap-select.min.css',
//    'resources/frontend/css/simplebar.min.css',
//    'resources/frontend/css/progressbar.css',
//    'resources/frontend/css/flaticon.css',
//    'resources/frontend/css/animate.css',
//    'resources/frontend/css/slider.css',
//    'resources/frontend/css/magnific-popup.css',
//    'resources/frontend/css/timecounter.css',
//    'resources/frontend/css/style.css',
//    'resources/frontend/css/responsive.css',
//    'resources/frontend/css/sweetalert2.min.css',
// ], 'public/frontend_assets/css/bundled.min.css').options({
//    postCss: [
//       require('postcss-discard-comments')({
//          removeAll: true
//       })
//    ]
// });

mix.babel([
   'resources/frontend/js/jquery-3.3.1.js',
   'resources/frontend/js/jquery-migrate-3.0.0.min.js',
   'resources/frontend/js/popper.min.js',
   'resources/frontend/js/bootstrap.min.js',
   'resources/frontend/js/jquery.mmenu.all.js',
   'resources/frontend/js/ace-responsive-menu.js',
   'resources/frontend/js/bootstrap-select.min.js',
   'resources/frontend/js/snackbar.min.js',
   'resources/frontend/js/simplebar.js',
   'resources/frontend/js/parallax.js',
   'resources/frontend/js/scrollto.js',
   'resources/frontend/js/wow.min.js',
   'resources/frontend/js/jquery-scrolltofixed-min.js',
   'resources/frontend/js/jquery.counterup.js',
   'resources/frontend/js/progressbar.js',
   'resources/frontend/js/slider.js',
   'resources/frontend/js/timepicker.js',
   'resources/frontend/js/sweetalert2.min.js',
   'resources/frontend/js/parsley.min.js',
   'resources/frontend/js/jquery.form.js',
], 'public/frontend_assets/js/bundled.min.js');




// //admin setup
// mix.styles([
//    'resources/backend/css/flatpickr.min.css',
//    'resources/backend/css/sweetalert2.min.css',
//    'resources/backend/css/bootstrap.min.css',
//    'resources/backend/css/icons.min.css',
//    'resources/backend/css/app.min.css',
//    'resources/backend/css/dataTables.bootstrap4.css',
//    'resources/backend/css/buttons.bootstrap4.css'
// ], 'public/admin_assets/css/bundled.min.css').options({
//    postCss: [
//       require('postcss-discard-comments')({
//          removeAll: true
//       })
//    ]
// });

// mix.babel([
//    	"resources/backend/js/vendor.min.js",
// 	"resources/backend/js/parsley.min.js",
// 	"resources/backend/js/select2.min.js",
// 	"resources/backend/js/sweetalert2.min.js",
// 	"resources/backend/js/jquery.form.js",
// 	"resources/backend/js/jquery.mask.min.js",
// ], 'public/admin_assets/js/bundled.min.js');


// mix.babel([
// 	"resources/backend/datatable/jquery.dataTables.min.js",
// 	"resources/backend/datatable/dataTables.bootstrap4.js",
// 	"resources/backend/datatable/dataTables.buttons.min.js",
// 	"resources/backend/datatable/buttons.bootstrap4.min.js",
// 	"resources/backend/datatable/buttons.html5.min.js",
// 	"resources/backend/datatable/buttons.flash.min.js",
// 	"resources/backend/datatable/buttons.print.min.js",
// 	// "resources/backend/datatable/pdfmake.min.js",
// 	// "resources/backend/datatable/vfs_fonts.js"
// ], 'public/admin_assets/js/dataTable_bundled.min.js');

