<!-- app/Views/partials/header.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Premium Rides' ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">    
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css" integrity="sha512-IuO+tczf4J43RzbCMEFggCWW5JuX78IrCJRFFBoQEXNvGI6gkUw4OjuwMidiS4Lm9Q2lILzpJwZuMWuSEeT9UQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/alt/adminlte.components.min.css" integrity="sha512-C6GDY2X+A6W2CYRoEykmm+Ta04hV2TqOSer0LJ+TeZCY3+b9i9pDnbwNgvlrpZSZIgBonixchcyVe7Nu8ccauQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/alt/adminlte.core.min.css" integrity="sha512-xihZdz1B0BgSS+aKKZn3WCVokTH1I/KbsubJJ/jfk9ir22aAtbFHw+oGPvKJUX76Wtl3kKhO+Wkj6Z47Pa76VQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />	
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/alt/adminlte.extra-components.min.css" integrity="sha512-Rho/nal+5pKgEFMfnMeJ5iynqFe2y/ev+KwrKIzFALivzIkj+3ymOWzhY/T9m5v9pkDUxZevORjoavsYCVbU/w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/alt/adminlte.light.min.css" integrity="sha512-sH43x9hDH6VYZCimbGd58vYrO4uMdmPn3m8QUgxNYi4MNmj4sbt+fN1jG+TnVA2Q0SA6tvEo6W6P1Z0FA+6AXA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/alt/adminlte.pages.min.css" integrity="sha512-G5uca2T4CI7/9IHrOI1DKXQaqBN17tyNzgL4rMSEavhnKwN82WDWptayW8/VbzI21UCjpErfXv7jRve+iCbb9g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/alt/adminlte.plugins.min.css" integrity="sha512-ayIIFF0UuqVTtj88SVYRvEcSf+vs9aLDgte4Fd+jdsFFr3zJYo5wEjFFD0QXCM+3WrVUCyUAW8meKc2kzO5Tow==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">

	
	
	<!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
