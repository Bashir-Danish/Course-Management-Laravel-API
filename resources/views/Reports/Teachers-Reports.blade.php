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
    <h2 class="navbar-brand" style="color:white; font-size:30px; font-weight:bold;">Teachers Report</h2>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mynavbar">
      <ul class="navbar-nav me-auto">
      </ul>

      <button type="button" class="btn btn-outline-light" onclick="refreshPage()" style="margin-right: 15px;">
              <i></i>back
      </button>

      <button type="button" class="btn btn-outline-light" onclick="homePage()" style="margin-right: 15px;">
              <i></i>Home
      </button>
       
         <script>
            function refreshPage(){
            window.location.href = "{{ route('reports.teachers') }}";
            }
          </script>

          <script>
            function homePage(){
            window.location.href = "{{ route('dashboard') }}";
            }
          </script>

      <div class="d-flex align-items-center">

      <form class="d-flex me-2" action="{{ route('reports.teachers') }}" method="GET">
      @if(request('selected_gender'))
      <input type="hidden" name="selected_gender" value="{{ request('selected_gender')}}">
        @endif
        @if(request('report_type'))
        <input type="hidden" name="report_type" value="{{ request('report_type')}}">
        @endif
        @if(request('selected_salary'))
        <input type="hidden" name="selected_salary" value="{{ request('selected_salary')}}">
        @endif
              <select class="form-select" id="selected-department" name="selected_department" onchange="this.form.submit()">
                <option value="" disabled {{ !request('selected_department') ? 'selected' : '' }} >Select Department</option>
                @foreach($departments as $department)
                <option value="{{ $department['id'] }}" {{ request('selected_department') == $department->id ? 'selected' : '' }}>
                  {{ $department['name'] }}
                </option>
                @endforeach
              </select>
            </form>
        
        <form class="d-flex me-2" action="{{ route('reports.teachers') }}" method="GET">
        @if(request('selected_department'))
          <input type="hidden" name="selected_department" value="{{ request('selected_department')}}">
        @endif
        @if(request('report_type'))
        <input type="hidden" name="report_type" value="{{ request('report_type')}}">
        @endif
        @if(request('selected_gender'))
      <input type="hidden" name="selected_gender" value="{{ request('selected_gender')}}">
        @endif
          <select class="form-select" name="selected_salary" onchange="this.form.submit()">
            <option value="" disabled {{ !request('selected_salary') ? 'selected' : '' }}>Select Salary</option>
            <option value="ASC" {{ request('selected_salary') == 'ASC' ? 'selected' : '' }}>ASC</option>
            <option value="DESC" {{ request('selected_salary') == 'DESC' ? 'selected' : '' }}>DESC</option>
          </select>
        </form>

        <form class="d-flex me-2" action="{{ route('reports.teachers') }}" method="GET">
        @if(request('selected_department'))
          <input type="hidden" name="selected_department" value="{{ request('selected_department')}}">
        @endif
        @if(request('report_type'))
        <input type="hidden" name="report_type" value="{{ request('report_type')}}">
        @endif
        @if(request('selected_salary'))
        <input type="hidden" name="selected_salary" value="{{ request('selected_salary')}}">
        @endif
          <select class="form-select" name="selected_gender" onchange="this.form.submit()">
            <option value="" disabled {{ !request('selected_gender') ? 'selected' : '' }}>Select Gender</option>
            <option value="male" {{ request('selected_gender') == 'male' ? 'selected' : '' }}>Male</option>
            <option value="female" {{ request('selected_gender') == 'female' ? 'selected' : '' }}>Female</option>
          </select>
        </form>

        <form class="d-flex me-2" action="{{ route('reports.teachers') }}" method="GET">
        @if(request('selected_department'))
          <input type="hidden" name="selected_department" value="{{ request('selected_department')}}">
        @endif
        @if(request('selected_gender'))
          <input type="hidden" name="selected_gender" value="{{ request('selected_gender')}}">
        @endif
        @if(request('report_type'))
        <input type="hidden" name="selecte_salary" value="{{ request('selected_salary')}}">
        @endif
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
<div class="container-fluid mt-3 mb-4">
    <div class="row">
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title text-muted">Total Students</h5>
                    <h2 class="card-text">{{ \App\Models\Student::count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title text-muted">Total Teachers</h5>
                    <h2 class="card-text">{{ \App\Models\Teacher::count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title text-muted">Total Courses</h5>
                    <h2 class="card-text">{{ \App\Models\Course::count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title text-muted">Total Departments</h5>
                    <h2 class="card-text">{{ \App\Models\Department::count() }}</h2>
                </div>
            </div>
        </div>
    </div>
</div>
  <div class="table-responsive">
    <table class="table table-bordered table-hover table-striped" style="text-align:center;">
    <thead class="table-gray">
         <tr>
          <th colspan="2">Number Of Students</th>
          <th></th>
          <th colspan="2">Number Of Teachers</th>
          <th></th>
          <th colspan="2">Number Of Courses</th>
          <th></th>
          <th colspan="1">Number Of Departments</th>
          <th></th>

         </tr>
        </thead>
      <thead class="table-dark">
        <tr>
          <th>No</th>
          <th>Firstname</th>
          <th>Lastname</th>
          <th>Address</th>
          <th>Phone</th>
          <th>Email</th>
          <th>Department</th>
          <th>Gender</th>
          <th>Salary</th>
        </tr>
      </thead>
      <tbody>
        @forelse($teachers as $index => $teacher)
          <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $teacher->first_name }}</td>
            <td>{{ $teacher->last_name }}</td>
            <td>{{ $teacher->address }}</td>
            <td>{{ $teacher->phone }}</td>
            <td>{{ $teacher->email }}</td>
            <td>{{ $teacher->department_name ?? 'N/A' }}</td>
            <td>{{ $teacher->gender }}</td> 
            <td>{{ $teacher->salary }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="9" class="text-center">No teachers found for this period</td>
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
            content: "Teachers Report";
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
