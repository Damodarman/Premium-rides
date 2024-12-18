<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title><?php echo $page;?></title>
  <meta content="" name="Taxi and passenger transfers. Order taxi. Order transfer to airport">
  <meta content="" name="keywords">

  <!-- Favicons -->
	
  <link href="<?php echo base_url('/assets/img/favicon.png')?>" rel="icon">
  <link href="<?php echo base_url('/assets/img/apple-touch-icon.png')?>" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Jost:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Courier+Prime:ital,wght@0,400;0,700;1,400;1,700&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
<!-- Bootstrap 5 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-daterangepicker@3.1.0/daterangepicker.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<link href="<?= base_url('/assets/css/style.css');?>" rel="stylesheet">
<link type="text/css" href="<?php echo base_url('/assets/vendor/aos/aos.css')?>" rel="stylesheet">
<link type="text/css" href="<?php echo base_url('/assets/vendor/boxicons/css/boxicons.min.css')?>" rel="stylesheet">
<link type="text/css" href="<?php echo base_url('/assets/vendor/glightbox/css/glightbox.min.css')?>" rel="stylesheet">
<link type="text/css" href="<?php echo base_url('/assets/vendor/remixicon/remixicon.css')?>" rel="stylesheet">
<link type="text/css" href="<?php echo base_url('/assets/vendor/swiper/swiper-bundle.min.css')?>" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.11.5/datatables.min.css"/>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css"/>
<link rel="stylesheet" href="https://unpkg.com/tableexport@5.3.0/dist/css/tableexport.min.css">
	<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">


<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
	<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-daterangepicker@3.1.0/daterangepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/glightbox/3.2.0/js/glightbox.min.js" integrity="sha512-S/H9RQ6govCzeA7F9D0m8NGfsGf0/HjJEiLEfWGaMCjFzavo+DkRbYtZLSO+X6cZsIKQ6JvV/7Y9YMaYnSGnAA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<!--<script src="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.1.4/dist/js/datepicker.min.js"></script>-->
<script src='https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.1.4/dist/js/datepicker-full.min.js'></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.1.2/js/buttons.print.min.js"></script>
	<script src="https://cdn.jsdelivr.net/gh/rainabba/jquery-table2excel@latest/dist/jquery.table2excel.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
<script src="https://unpkg.com/xlsx@0.17.4/dist/xlsx.full.min.js"></script>
<script src="https://unpkg.com/tableexport@5.2.0/dist/js/tableexport.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>	
<script src="
https://cdn.jsdelivr.net/npm/chart.js/dist/chart.umd.min.js
">
	</script>
<script src="https://cdn.jsdelivr.net/npm/underscore@1.13.6/underscore-umd-min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.11/index.global.min.js" integrity="sha512-WPqMaM2rVif8hal2KZZSvINefPKQa8et3Q9GOK02jzNL51nt48n+d3RYeBCfU/pfYpb62BeeDf/kybRY4SJyyw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.8/locales/hr.global.min.js'></script>


	
<style>
        /* Style for placeholder animation */
        .skeleton-row {
            background: linear-gradient(90deg, #f4f4f4, #e2e2e2, #f4f4f4);
            background-size: 200% 100%;
            animation: shimmer 1.5s linear infinite;
        }

        @keyframes shimmer {
            0% {
                background-position: -200% 0;
            }
            100% {
                background-position: 200% 0;
            }
        }
   #calendar {
            max-width: 900px;
            margin: 40px auto;
        }

        .fc-day-grid-event { /* This will style your date cells */
            border: none !important;
            padding: 2px !important;
        }

        .both { background-color: green !important; }
        .bolt-only { background-color: orange !important; }
        .uber-only { background-color: blue !important; }
        .no-data { /* Optional if you need to style days with no data */
            background-color: lightgray !important;
        }	
	.fc-day-grid-event .fc-content .both { background-color: green !important; }
	.fc-day-grid-event .fc-content .bolt-only { background-color: orange !important; }
	.fc-day-grid-event .fc-content .uber-only { background-color: blue !important; }
	
/* Timeline Line */
.timeline-line {
    z-index: 1;
    background-color: #e9ecef;
}

/* Timeline Badge (Circle) */
.timeline-badge {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    position: relative;
    z-index: 2;
}

/* Text Box Styling */
.bg-light {
    background-color: #f8f9fa !important;
}

.border {
    border: 1px solid #ddd;
}

.p-2 {
    padding: 10px;
    max-width: 200px;
    margin: 0 auto;
}
	
	
	
    </style>
	<!-- Template Main CSS File -->
  <link href="<?php echo base_url('/assets/css/style.css')?>" rel="stylesheet">
	<?php if(isset($svrha)){echo '<style>html {font-size: 62.5%; font-color: black;} .container{color: black; font-family: "Courier Prime";  }</style>' ;}   ?>

</head>

<body>