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
          <div class="table_123" style="padding:0 20px;">
            <div class="table123_haeder">
                <h4 id="hh2">List Of Students</h4>
                <div>
                    <input id="tx" type="text" placeholder="Search ...">
                    <button class="add123_new" onclick="window.location.href='{{ route('students.create') }}'">+Add New</button>
                </div>
            </div>
            
            <div class="table123_section">
            </div>
           
        </div>

          <!-- ================================================================================================ -->
        
      </div> 
    </div>
    <!--==========================================================================================================================-->

    <!-- =========================================  ======== Admin Panel ==================================================== -->
     @include('Admin_panel')
    <!-- ================================================================================================================== -->
    @include('Reports')
    <!-- ===================================================== Footer =============================================================== -->
     @include('Footer')
    <!--========================================================================================================================== -->

    <div id="notification-container"></div>

    <script>
    function viewStudent(id) {
        window.location.href = `{{ url('students') }}/${id}`;
    }

    function loadStudents(url) {
        url = url || '{{ route("students.index") }}';
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.text())
        .then(html => {
            document.querySelector('.table123_section').innerHTML = html;
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error loading students', 'danger');
        });
    }

    function showNotification(message, type = 'success') {
        let container = document.getElementById('notification-container');
        
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        
        const messageText = document.createElement('span');
        messageText.textContent = message;
        
        const closeButton = document.createElement('button');
        closeButton.className = 'notification-close';
        closeButton.innerHTML = '&times;';
        closeButton.onclick = () => notification.remove();
        
        notification.appendChild(messageText);
        notification.appendChild(closeButton);
        container.appendChild(notification);
        
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 3000);
    }

    let searchTimeout;
    document.getElementById('tx').addEventListener('keyup', function() {
        clearTimeout(searchTimeout);
        const searchText = this.value.toLowerCase();
        
        if (searchText.length >= 3 || searchText.length === 0) {
            searchTimeout = setTimeout(() => {
                const url = new URL('{{ route("students.index") }}');
                url.searchParams.set('search', searchText);
                loadStudents(url);
            }, 500);
        }
    });

    document.addEventListener('click', function(e) {
        if (e.target.matches('.pagination a')) {
            e.preventDefault();
            loadStudents(e.target.href);
        }
    });

    function deleteStudent(id) {
        if (confirm('Are you sure you want to delete this student?')) {
            fetch(`{{ url('students') }}/${id}`, {
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
                    loadStudents();
                    showNotification('Student deleted successfully', 'success');
                } else {
                    throw new Error(data.message || 'Error deleting student');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification(error.message || 'An error occurred', 'danger');
            });
        }
    }

    function editStudent(id) {
        window.location.href = `{{ url('students') }}/${id}/edit`;
    }

    loadStudents();
    </script>

    <style>
    .alert {
        padding: 10px 15px;
        border-radius: 4px;
        margin-bottom: 15px;
    }
    .alert-success {
        background-color: #d4edda;
        border-color: #c3e6cb;
        color: #155724;
    }
    .alert-danger {
        background-color: #f8d7da;
        border-color: #f5c6cb;
        color: #721c24;
    }
    #notification-container {
        position: fixed;
        top: 85px;
        right: 20px;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .notification {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 20px;
        border-radius: 4px;
        min-width: 300px;
        background: white;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        animation: slideIn 0.3s ease-out;
    }

    .notification-success {
        background-color: #d4edda;
        border-left: 4px solid #28a745;
        color: #155724;
    }

    .notification-danger {
        background-color: #f8d7da;
        border-left: 4px solid #dc3545;
        color: #721c24;
    }

    .notification-close {
        background: none;
        border: none;
        color: inherit;
        font-size: 20px;
        cursor: pointer;
        padding: 0 0 0 10px;
        opacity: 0.5;
    }

    .notification-close:hover {
        opacity: 1;
    }

    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    </style>
  </body>
</html>

