<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <!-- Operating Vehicles -->

        <!-- Active Drivers -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3><?= $activeDriversCount ?></h3>
                    <p>Active Drivers</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-check"></i>
                </div>
                <a href="<?= site_url('driver/active') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <!-- Inactive Drivers -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3><?= $inactiveDriversCount ?></h3>
                    <p>Inactive Drivers</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-times"></i>
                </div>
                <a href="<?= site_url('driver/inactive') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <!-- Add more boxes as needed -->
    </div>
<!-- Modal for Filtering (Quarter and Year Selection) -->
<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filterModalLabel">Choose Filtering Options</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="filterType">Select Filter:</label>
                    <select class="form-control" id="filterType">
                        <option value="all">All Time</option>
                        <option value="week">Single Week</option>
                        <option value="weekRange">Week Range</option>
                        <option value="quarter">Quarter</option>
                        <option value="year">Year</option>
                    </select>
                </div>

                <!-- Week Selection (Visible for Single Week or Week Range) -->
                <div id="weekSelection" class="d-none">
                    <label for="weekPicker">Select Week:</label>
                    <input type="text" class="form-control" id="weekPicker">
                </div>

                <!-- Date Range Picker (for Week Range) -->
                <div id="weekRangeSelection" class="d-none">
                    <label for="weekRangePicker">Select Date Range:</label>
                    <input type="text" class="form-control" id="weekRangePicker">
                </div>

                <!-- Quarter Selection (Dynamic Population from Backend) -->
                <div id="quarterSelection" class="d-none">
                    <label for="quarter">Select Quarter:</label>
                    <select class="form-control" id="quarter"></select>
                </div>

                <!-- Year Dropdown (Visible for Quarter and Year Filters) -->
                <div id="yearSelection" class="d-none">
                    <label for="year">Select Year:</label>
                    <select class="form-control" id="year"></select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="applyFilterModal">Apply Filter</button>
            </div>
        </div>
    </div>
</div>

	
	<div class="row">
<!-- Chart Section for Drivers per Week -->
        <div class="col-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Omjer vozača i zarade</h3>
 					<div class="card-tools">
						<!-- Filter Icon -->
						<i class="fa fa-filter  " id="" style="cursor: pointer;"></i>

					</div>
               </div>
                <div class="card-body">
                    <canvas id="driversChart"></canvas>
                </div>
            </div>
        </div>
<div class="col-4">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Aktivnost po platformama</h3>
				<div class="card-tools" style="display: flex; align-items: center;">
					<!-- Quarter Navigation (Initially Visible) -->
					<span id="quarterNavContainer" style="display: flex; align-items: center;">
						<i class="fa fa-angles-left" id="firstQuarterBtn" style="cursor: pointer;" title="Show first quarter"></i>
						<i class="fa fa-chevron-left mx-2" id="prevQuarterBtn" style="cursor: pointer;" title="Previous quarter"></i>
						<span id="currentQuarterDisplay" class="mx-3"></span>
						<i class="fa fa-chevron-right mx-2" id="nextQuarterBtn" style="cursor: pointer;" title="Next quarter"></i>
						<i class="fa fa-angles-right" id="lastQuarterBtn" style="cursor: pointer;" title="Show last quarter"></i>
					</span>
					<!-- Filter Icon -->
					<i class="fa fa-filter ms-5" id="openFilterModal" style="cursor: pointer;"></i>
				</div>
	        </div>
        <div class="card-body">


            <!-- The chart canvas -->
            <div class="mt-4">
                <canvas id="platformRatioChart"></canvas>
            </div>
        </div>
    </div>
</div>
	</div>
</div>

<?= $this->endSection() ?>
