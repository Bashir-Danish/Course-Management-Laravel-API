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
              <a href="index.html"><h2 id="head"><span id="title-1">Nano</span><span id="title-2">Net</span></h2></a>
            </div>
          </div>
        </div>
      </div>
      
        <!-- header section -->
        @include('header')
        <!-- Mobile Menu start -->
        @include('Mobile_menu')
        
          <!-- =================================== The Main Part ============================================== -->
         <!-- <hr> -->
          <div class="table_123" style="padding:0 20px;">
            <div class="table123_haeder">
                <h4 id="hh2">List Of Departments</h4>
                <div>
                    <input id="tx" type="text" placeholder="Search ....">
                    <button class="add123_new" onclick="window.location.href='{{ route('departments.create') }}'">+Add New</button>
                </div>
            </div>
            
            <div class="table123_section" id="departments-table">
                <!-- Table content will be loaded here via AJAX -->
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
    function loadDepartments(url) {
        url = url || '{{ route("departments.index") }}';
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            document.getElementById('departments-table').innerHTML = html;
        });
    }

    loadDepartments();

    document.addEventListener('click', function(e) {
        if (e.target.matches('.pagination a')) {
            e.preventDefault();
            loadDepartments(e.target.href);
        }
    });

    let searchTimeout;
    document.getElementById('tx').addEventListener('keyup', function() {
        clearTimeout(searchTimeout);
        const searchText = this.value.toLowerCase();
        
        if (searchText.length >= 3 || searchText.length === 0) {
            searchTimeout = setTimeout(() => {
                const url = new URL('{{ route("departments.index") }}');
                url.searchParams.set('search', searchText);
                loadDepartments(url);
            }, 500); 
        }
    });

    function deleteDepartment(id) {
        if (confirm('Are you sure you want to delete this department?')) {
            fetch(`{{ url('departments') }}/${id}`, {
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
                    loadDepartments();
                } else {
                    throw new Error(data.message || 'Error deleting department');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    }

    function editDepartment(id) {
        window.location.href = `{{ url('departments') }}/${id}/edit`;
    }
    </script>
  </body>
</html>
