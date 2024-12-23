<!DOCTYPE html>
<html lang="en">
<head>
  <title>Reports</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<div class="container-fluid" style="padding:0px 0px;">
<nav class="navbar navbar-expand-sm" style="background-color:#f80000; padding:10px; font-size:50px;">
  <div class="container-fluid">
    <h2 class="navbar-brand" style="color:white; font-size:30px; font-weight:bold;">Courses Report</h2>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mynavbar">
      <ul class="navbar-nav me-auto">
      </ul>
      <div class="d-flex align-items-center">
        <form class="d-flex me-2" action="{{ route('reports.courses') }}" method="GET">
          <select class="form-select" name="report_type" onchange="this.form.submit()">
            <option value="" disabled {{ !request('report_type') ? 'selected' : '' }}>Select Report Type</option>
            <option value="weekly" {{ request('report_type') == 'weekly' ? 'selected' : '' }}>Weekly</option>
            <option value="monthly" {{ request('report_type') == 'monthly' ? 'selected' : '' }}>Monthly</option>
            <option value="yearly" {{ request('report_type') == 'yearly' ? 'selected' : '' }}>Yearly</option>
          </select>
        </form>
        <button type="button" class="btn btn-outline-light" onclick="printReport()">
          <i class="fas fa-print me-2"></i>Print List
        </button>
      </div>
    </div>
  </div>
</nav>
  <div class="table-responsive">
    <table class="table table-bordered table-hover table-striped" style="text-align:center;">
      <thead class="table-dark">
        <tr>
          <th>No</th>
          <th>Course Name</th>
          <th>Department</th>
          <th>Fees</th>
          <th>Time Slots</th>
          <th>Duration</th>
        </tr>
      </thead>
      <tbody>
        @forelse($courses as $index => $course)
          <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $course->name }}</td>
            <td>{{ $course->department_name ?? 'N/A' }}</td>
            <td>${{ number_format($course->fees, 2) }}</td>
            <td>{{ json_decode($course->available_time_slots, true) ? implode(', ', json_decode($course->available_time_slots, true)) : 'N/A' }}</td>
            <td>{{ $course->duration }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="text-center">No courses found for this period</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<script>
function printReport() {
    window.print();
}
</script>

<style type="text/css" media="print">
    @media print {
        .navbar-toggler,
        .btn-outline-light,
        form.d-flex {
            display: none !important;
        }
        
        .table {
            width: 100%;
            margin-bottom: 1rem;
            border-collapse: collapse;
            page-break-inside: auto;
        }
        
        thead {
            display: table-header-group;
        }
        
        tr {
            page-break-inside: avoid;
        }

        body::before {
            content: "Courses Report";
            display: block;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }
    }
</style>

<style>
.btn-outline-light {
    padding: 8px 20px;
    font-weight: 500;
    transition: all 0.3s ease;
    border-radius: 5px;
}

.btn-outline-light:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    background-color: white;
    color: #f80000;
}

.form-select {
    min-width: 150px;
}
</style>

</body>
</html>
