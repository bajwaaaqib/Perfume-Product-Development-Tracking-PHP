<?php
require 'includes/auth.php';
require 'includes/db.php';

// Fetch all report dates for calendar highlights
$datesStmt = $pdo->query("SELECT DISTINCT report_date FROM weekly_reports ORDER BY report_date ASC");
$reportDates = $datesStmt->fetchAll(PDO::FETCH_COLUMN);

// Selected date from GET or today
$selectedDate = $_GET['date'] ?? date('Y-m-d');

// Fetch report(s) for selected date
$reportStmt = $pdo->prepare("SELECT * FROM weekly_reports WHERE report_date = ? ORDER BY created_at DESC");
$reportStmt->execute([$selectedDate]);
$reports = $reportStmt->fetchAll();

// Find previous report date before $selectedDate
$prevStmt = $pdo->prepare("SELECT report_date FROM weekly_reports WHERE report_date < ? ORDER BY report_date DESC LIMIT 1");
$prevStmt->execute([$selectedDate]);
$prevDate = $prevStmt->fetchColumn();

// Find next report date after $selectedDate
$nextStmt = $pdo->prepare("SELECT report_date FROM weekly_reports WHERE report_date > ? ORDER BY report_date ASC LIMIT 1");
$nextStmt->execute([$selectedDate]);
$nextDate = $nextStmt->fetchColumn();

require 'includes/header.php';
?>


<div class="container my-5" style="max-width: 850px;">
    <!-- Page Header -->
    <div class="mb-4 text-center text-md-start">
        <h2 class="fw-bold text-primary">Weekly Reports</h2>
    </div>

    <!-- Add New & Navigation Buttons grouped -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3">
        <a href="add_edit_report.php" class="btn btn-secondary flex-grow-1 flex-md-grow-0">+ Add New Report</a>
        <div class="btn-group flex-grow-1 flex-md-grow-0" role="group" aria-label="Navigation Buttons">
            <?php if ($prevDate): ?>
                <a href="weekly_reports.php?date=<?= htmlspecialchars($prevDate) ?>" class="btn btn-outline-primary px-4">&laquo; Previous</a>
            <?php else: ?>
                <button class="btn btn-outline-secondary px-4" disabled>&laquo; Previous</button>
            <?php endif; ?>

            <?php if ($nextDate): ?>
                <a href="weekly_reports.php?date=<?= htmlspecialchars($nextDate) ?>" class="btn btn-outline-primary px-4">Next &raquo;</a>
            <?php else: ?>
                <button class="btn btn-outline-secondary px-4" disabled>Next &raquo;</button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Date Selection Card -->
    <div class="card shadow-sm mb-5">
        <div class="card-body">
            <form id="date-select-form" class="row g-3 align-items-center">
                <div class="col-12 col-md-6">
                    <label for="report-date-select" class="form-label fw-semibold">Jump to report date</label>
                    <select id="report-date-select" class="form-select" aria-label="Select report date">
                        <?php foreach ($reportDates as $d): ?>
                            <option value="weekly_reports.php?date=<?= htmlspecialchars($d) ?>" <?= $d === $selectedDate ? 'selected' : '' ?>>
                                <?= date('d M Y', strtotime($d)) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div  class="form-text">Short Cut</div>
                </div>

                <div class="col-12 col-md-6">
                    <label for="datepicker" class="form-label fw-semibold">Or select a date</label>
                    <input type="text" id="datepicker" class="form-control" style="cursor: pointer;" value="<?= htmlspecialchars($selectedDate) ?>" aria-describedby="datepickerHelp" autocomplete="off" />
                    <div id="datepickerHelp" class="form-text">Click to select date from calendar</div>
                </div>
            </form>
        </div>
    </div>

    <!-- Reports Display -->
    <div class="mb-5">
        <h4 class="mb-4 text-secondary fw-semibold border-bottom pb-2">Reports for <?= date('d M Y', strtotime($selectedDate)) ?></h4>

        <?php if (count($reports) === 0): ?>
            <p class="text-center text-muted fst-italic">No reports found for this date.</p>
        <?php else: ?>
            <div class="list-group">
                <?php foreach ($reports as $r): ?>
                    <div class="list-group-item list-group-item-action flex-column align-items-start shadow-sm mb-3 rounded">
                        <pre class="mb-3" style="white-space: pre-wrap; font-family: inherit; font-size: 1rem;"><?= htmlspecialchars($r['content']) ?></pre>
                        <div class="d-flex justify-content-end gap-2">
                            <a href="add_edit_report.php?id=<?= htmlspecialchars($r['id']) ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
                                <form method="POST" action="delete_report.php" >
                                      <input type="hidden" name="id" value="<?= htmlspecialchars($r['id']) ?>">
                                      <button type="submit" class="btn btn-sm btn-outline-danger">Del</button>
                                 </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<!-- Load jQuery (required for Bootstrap Datepicker) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap Datepicker CSS & JS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

<script>
const reportDates = <?= json_encode($reportDates) ?>;

$(document).ready(function() {
    // Jump to selected date from dropdown
    $('#report-date-select').on('change', function() {
        window.location.href = this.value;
    });

    // Initialize datepicker
    $('#datepicker').datepicker({
        format: 'yyyy-mm-dd',
        todayHighlight: true,
        autoclose: true,
        beforeShowDay: function(date) {
            const d = date.getFullYear() + '-' +
          String(date.getMonth() + 1).padStart(2, '0') + '-' +
          String(date.getDate()).padStart(2, '0');

            if (reportDates.includes(d)) {
                return {
                    classes: 'bg-info text-white rounded-circle',
                    tooltip: 'Report available'
                };
            }
            return;
        }
    });

    // Show datepicker on input click
    $('#datepicker').on('click', function() {
        $(this).datepicker('show');
    });

    // Prevent manual typing in date input
    $('#datepicker').on('keydown paste', function(e) {
        e.preventDefault();
    });

    // On date select, redirect to that date's report
    $('#datepicker').on('changeDate', function(e) {
        const date = e.format('yyyy-mm-dd');
        window.location.href = 'weekly_reports.php?date=' + date;
    });
});
</script>
