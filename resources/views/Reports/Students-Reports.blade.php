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
    <h2 class="navbar-brand" style="color:white; font-size:30px; font-weight:bold;">Students Report</h2>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mynavbar">
      <ul class="navbar-nav me-auto">
      </ul>
      <div class="d-flex align-items-center">
        <form class="d-flex me-2" action="{{ route('reports.students') }}" method="GET">
          <select class="form-select" name="report_type" onchange="this.form.submit()">
            <option value="weekly" {{ ($reportType ?? 'weekly') == 'weekly' ? 'selected' : '' }}>Weekly</option>
            <option value="monthly" {{ ($reportType ?? 'weekly') == 'monthly' ? 'selected' : '' }}>Monthly</option>
            <option value="yearly" {{ ($reportType ?? 'weekly') == 'yearly' ? 'selected' : '' }}>Yearly</option>
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
          <th>Firstname</th>
          <th>Lastname</th>
          <th>Address</th>
          <th>Phone</th>
          <th>Department</th>
          <th>Course</th>
          <th>Fees Total</th>
          <th>Fees Paid</th>
          <th>Time Slot</th>
          <th>Registration Date</th>
        </tr>
      </thead>
      <tbody>
        @forelse($students as $index => $student)
          <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $student->first_name }}</td>
            <td>{{ $student->last_name }}</td>
            <td>{{ $student->address ?? 'N/A' }}</td>
            <td>{{ $student->phone ?? 'N/A' }}</td>
            <td>{{ $student->department_name ?? 'N/A' }}</td>
            <td>{{ $student->course_name ?? 'N/A' }}</td>
            <td>${{ number_format($student->fees_total ?? 0, 2) }}</td>
            <td>${{ number_format($student->fees_paid ?? 0, 2) }}</td>
            <td>
              @php
                try {
                    $timeSlot = $student->time_slot;
                    if ($timeSlot) {
                        $cleaned = str_replace(['[', ']', '"', '\\'], '', $timeSlot);
                        echo $cleaned;
                    } else {
                        echo 'N/A';
                    }
                } catch (Exception $e) {
                    echo 'N/A';
                }
              @endphp
            </td>
            <td>{{ $student->registration_date ? date('Y-m-d', strtotime($student->registration_date)) : 'N/A' }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="11" class="text-center">No students found for this period</td>
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
            content: "Students Report";
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
