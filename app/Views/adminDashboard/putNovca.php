<div class="container mt-5">

    <!-- Filtering Form -->
<!--
    <form id="filter-form" class="mb-3">
        <div class="form-row">
            <div class="form-group col-md-3">
                <label for="user">User</label>
                <input type="text" class="form-control" id="user" name="user" placeholder="Enter user">
            </div>
            <div class="form-group col-md-3">
                <label for="date">Date</label>
                <input type="date" class="form-control" id="date" name="date">
            </div>
            <div class="form-group col-md-3">
                <label for="start_date">Start Date</label>
                <input type="date" class="form-control" id="start_date" name="start_date">
            </div>
            <div class="form-group col-md-3">
                <label for="end_date">End Date</label>
                <input type="date" class="form-control" id="end_date" name="end_date">
            </div>
        </div>

        <button type="button" id="filter-button" class="btn btn-primary">Filter</button>
    </form>
-->

    <!-- Data Table -->
<div class="container">
    <h1>Pregled naplate</h1>

<form id="filter-form" class="row g-3">
    <!-- User input -->
	<?php if($role != 'admin'): ?>
	<div class="col-md-2">
		<label for="user" class="form-label">User</label>
        <input type="text" class="form-control" id="" name="" value ="<?=$name?>" disabled>
        <input type="text" class="form-control" id="user" name="user" value ="<?=$name?>" hidden>
	  </div>
	<?php else:?>
	<div class="col-md-2">
		<label for="user" class="form-label">User</label>
        <input type="text" class="form-control" id="user" name="user" placeholder="Enter user">
	  </div>
	<?php endif ?>
	<div class="col-md-2">
		<label for="user" class="form-label">Vozac</label>
        <input type="text" class="form-control" id="vozac" name="vozac" placeholder="Enter vozac">
    <input type="hidden" id="startDate" name="start_date">
    <input type="hidden" id="endDate" name="end_date">
	  </div>
	<div class="col-md-2">
		<label for="nacin_placanja" class="form-label">Način plačanja</label>
        <input type="text" class="form-control" id="nacin_placanja" name="nacin_placanja" placeholder="Enter nacin plačanja">
	  </div>
	<div class="col-md-1">
		<label for="iznos" class="form-label">Iznos</label>
        <input type="text" class="form-control" id="iznos" name="iznos" placeholder="Enter Iznos">
	  </div>
<!--
	<div class="col-md-2">
		<label for="user" class="form-label">User</label>
        <input type="text" class="form-control" id="user" name="user" placeholder="Enter user">
	  </div>
-->
	<div class="col-md-3">
		<label for="daterange" class="form-label">Raspon</label>
        <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc;">
            <i class="fa fa-calendar"></i>&nbsp;
            <span>Pick a date range</span> 
            <i class="fa fa-caret-down"></i>
        </div>
	  </div>
	<div class="col-md-1">
		<label for="predano" class="form-label">Predano</label>
        <input type="text" class="form-control" id="predano" name="predano" placeholder="DA / NE">
	  </div>
	<div class="col-md-1">
		<label for="primljeno" class="form-label">Primljeno</label>
        <input type="text" class="form-control" id="primljeno" name="primljeno" placeholder="DA / NE">
	  </div>

    <!-- Vozac input -->
<!--
    <div class="form-group mx-2">
        <input type="text" class="form-control" id="vozac" name="vozac" placeholder="Enter vozac">
    </div>


    <div class="form-group mx-2">
        <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc;">
            <i class="fa fa-calendar"></i>&nbsp;
            <span>Pick a date range</span> 
            <i class="fa fa-caret-down"></i>
        </div>
    </div>
	-->

</form>
    <table class="table table-striped" id="filtered-table">
		<thead>
            <tr>
                <th>User</th>
                <th>Vozac</th>
                <th>Nacin Placanja</th>
                <th>Iznos</th>
                <th>Vrijeme plaćanja</th>
                <th>Predano</th>
                <th>Primljeno</th>
            </tr>
        </thead>
        <tbody id="table-body">
            <!-- Start with placeholder rows -->
            <tr class="skeleton-row"><td colspan="6"></td></tr>
            <tr class="skeleton-row"><td colspan="6"></td></tr>
            <tr class="skeleton-row"><td colspan="6"></td></tr>
        </tbody>
    </table>
</div>

</div>

