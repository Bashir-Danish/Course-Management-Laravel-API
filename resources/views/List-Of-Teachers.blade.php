<!DOCTYPE html>
<html class="no-js" lang="en">
  <head>
     <!--================================================= Head Part ==================================================== -->
     @include('head')
     <!--================================================================================================================ -->
  </head>

  <body>
    <!-- ================================================ Left Sidebar ================================================================ -->
      @include('sidebar')
    <!-- ===============================================================================================================================-->

    <!-- ========================================== Navbar Part  And Main Section ================================================-->
    <div class="all-content-wrapper">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="logo-pro">
              <a href="{{ route('dashboard') }}"><h2 id="head"><span id="title-1">Nano</span><span id="title-2">Net</span></h2></a>
            </div>
          </div>
        </div>
      </div>
  
       <!-- header section -->
       @include('header')
        <!-- Mobile Menu start -->
        @include('Mobile_menu')
        
          <!-- =================================== The Main Part ============================================== -->
          <hr>
          <div class="table_123" >
            <div class="table123_haeder">
                <h4 id="hh2">List Of Teachers</h4>
                <div>
                    <input id="tx" type="text" placeholder="Search ....">
                    <button class="add123_new" onclick="window.location.href='{{ route('teachers.create') }}'">+Add New</button>
                </div>
            </div>
            
            <div class="table123_section">
                <table id="tbl" style="border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th style="padding: 8px;">ID</th>
                            <th style="padding: 8px;">First Name</th>
                            <th style="padding: 8px;">Last Name</th>
                            <th style="padding: 8px;">Email</th>
                            <th style="padding: 8px;">Phone</th>
                            <th style="padding: 8px;">Department</th>
                            <th style="padding: 8px;">Gender</th>
                            <th style="padding: 8px;">Salary</th>
                            <th style="padding: 8px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teachers as $teacher)
                        <tr id="tr">
                            <td style="padding: 8px;">{{ $teacher->id }}</td>
                            <td style="padding: 8px;">{{ $teacher->first_name }}</td>
                            <td style="padding: 8px;">{{ $teacher->last_name }}</td>
                            <td style="padding: 8px;">{{ $teacher->email }}</td>
                            <td style="padding: 8px;">{{ $teacher->phone }}</td>
                            <td style="padding: 8px;">
                                @foreach($teacher->departments as $dept)
                                    <span class="badge bg-primary">{{ $dept->name }}</span>
                                @endforeach
                            </td>
                            <td style="padding: 8px;">{{ ucfirst($teacher->gender) }}</td>
                            <td style="padding: 8px;">${{ number_format($teacher->salary, 2) }}</td>
                            <td style="padding: 8px;">
                                <i class="fa fa-pencil-square-o" aria-hidden="true" 
                                   style="color: #007bff; cursor: pointer; margin-right: 10px; font-size: 16px;"
                                   onclick="editTeacher({{ $teacher->id }})" 
                                   data-toggle="tooltip" title="Edit"></i>
                                <i class="fa fa-trash-o" aria-hidden="true" 
                                   style="color: #dc3545; cursor: pointer; font-size: 16px;"
                                   onclick="deleteTeacher({{ $teacher->id }})" 
                                   data-toggle="tooltip" title="Trash"></i>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center" style="padding: 8px;">No teachers found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                
                @if($isPaginated)
                <div class="custom-pagination">
                    <ul class="pagination justify-content-center">
                        @if ($teachers->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">Previous</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $teachers->previousPageUrl() }}">Previous</a>
                            </li>
                        @endif

                        @for ($i = 1; $i <= $teachers->lastPage(); $i++)
                            <li class="page-item {{ ($teachers->currentPage() == $i) ? 'active' : '' }}">
                                <a class="page-link" href="{{ $teachers->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor

                        @if ($teachers->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $teachers->nextPageUrl() }}">Next</a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link">Next</span>
                            </li>
                        @endif
                    </ul>
                </div>
                @endif
            </div>
           
        </div>

          <!-- ================================================================================================ -->
        
      </div> 
    </div>
    <!--==========================================================================================================================-->

    <!-- ================================================= Admin Panel ==================================================== -->
     @include('Admin_panel')
    <!-- ================================================================================================================== -->
    @include('Reports')
    <!-- ===================================================== Footer =============================================================== -->
     @include('Footer')
    <!--========================================================================================================================== -->

    <script>
    function loadTeachers(url) {
        url = url || '{{ route("teachers.index") }}';
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            document.querySelector('.table123_section').innerHTML = html;
        });
    }

    let searchTimeout;
    document.getElementById('tx').addEventListener('keyup', function() {
        clearTimeout(searchTimeout);
        const searchText = this.value.toLowerCase();
        
        if (searchText.length >= 3 || searchText.length === 0) {
            searchTimeout = setTimeout(() => {
                const url = new URL('{{ route("teachers.index") }}');
                url.searchParams.set('search', searchText);
                loadTeachers(url);
            }, 500);
        }
    });

    document.addEventListener('click', function(e) {
        if (e.target.matches('.pagination a')) {
            e.preventDefault();
            loadTeachers(e.target.href);
        }
    });

    function deleteTeacher(id) {
        if (confirm('Are you sure you want to delete this teacher?')) {
            fetch(`{{ url('teachers') }}/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadTeachers();
                } else {
                    throw new Error(data.message || 'Error deleting teacher');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    }

    function editTeacher(id) {
        window.location.href = `{{ url('teachers') }}/${id}/edit`;
    }

    loadTeachers();
    </script>

    <style>
        
    .badge {
        padding: 5px 10px;
        border-radius: 15px;
        margin: 2px;
        display: inline-block;
        font-size: 12px;
    }
    .bg-primary {
        background-color: #007bff;
        color: white;
    }
    </style>
  </body>
</html>
