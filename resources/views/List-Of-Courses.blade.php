@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="logo-pro">
                <a href="{{ route('dashboard') }}"><h2 id="head"><span id="title-1">Nano</span><span id="title-2">Net</span></h2></a>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="table_123" style="padding:0 20px;">
    <div class="table123_haeder">
        <h4 id="hh2">List Of Courses</h4>
        <div>
            <input id="tx" type="text" placeholder="Search ....">
            <button class="add123_new" onclick="window.location.href='{{ route('courses.create') }}'">+Add New</button>
        </div>
    </div>
    
    <div class="table123_section">
        <!-- Table content will be loaded here via AJAX -->
    </div>
</div>

<script>
// Function to load courses table content
function loadCourses(url) {
    url = url || '{{ route("courses.index") }}';
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
        showNotification('Error loading courses', 'danger');
    });
}

function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type}`;
    notification.style.position = 'fixed';
    notification.style.top = '20px';
    notification.style.right = '20px';
    notification.style.zIndex = '1000';
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Handle search with debounce
let searchTimeout;
document.getElementById('tx').addEventListener('keyup', function() {
    clearTimeout(searchTimeout);
    const searchText = this.value.toLowerCase();
    
    if (searchText.length >= 3 || searchText.length === 0) {
        searchTimeout = setTimeout(() => {
            const url = new URL('{{ route("courses.index") }}');
            url.searchParams.set('search', searchText);
            loadCourses(url);
        }, 500);
    }
});

// Handle pagination clicks
document.addEventListener('click', function(e) {
    if (e.target.matches('.pagination a')) {
        e.preventDefault();
        loadCourses(e.target.href);
    }
});

function deleteCourse(id) {
    if (confirm('Are you sure you want to delete this course?')) {
        fetch(`{{ url('courses') }}/${id}`, {
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
                loadCourses();
                showNotification('Course deleted successfully');
            } else {
                throw new Error(data.message || 'Error deleting course');
            }
        })
        .catch(error => {
            showNotification(error.message, 'danger');
        });
    }
}

function editCourse(id) {
    window.location.href = `{{ url('courses') }}/${id}/edit`;
}

// Initial load
loadCourses();
</script>

<style>
.time-slots-cell {
    position: relative;
}

.time-slots-summary {
    cursor: pointer;
    color: #007bff;
}

.time-slots-tooltip {
    display: none;
    position: absolute;
    background: white;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 10px;
    z-index: 1000;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    min-width: 150px;
    left: 50%;
    transform: translateX(-50%);
    top: 100%;
    margin-top: 5px;
}

.time-slot-group {
    margin-bottom: 10px;
}

.time-slot-group:last-child {
    margin-bottom: 0;
}

.time-slot-header {
    font-weight: bold;
    color: #007bff;
    padding: 5px 0;
    margin-bottom: 5px;
    border-bottom: 1px solid #eee;
}

.time-slot-item {
    padding: 3px 0;
    padding-left: 10px;
    color: #666;
}

.time-slot-item:hover {
    background-color: #f8f9fa;
}

.time-slots-cell:hover .time-slots-tooltip {
    display: block;
}

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
@endsection
