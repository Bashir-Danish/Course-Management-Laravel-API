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
   
    <div class="all-content-wrapper" style="background-color: gainsboro; height: 95vh; overflow: hidden;">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="logo-pro">
              <a href="index.html"><h2 id="head" class="h4"><span id="title-1">Nano</span><span id="title-2">Net</span></h2></a>
            </div>
          </div>
        </div>
      </div>
        <!-- header section -->
        @include('header')
        <!-- Mobile Menu start -->
        @include('Mobile_menu')
        <!-- =================================================== -->
        <div class="container-fluid">
          <div class="row">
            <div class="wrapper">
              <div class="inner d-flex flex-column flex-md-row">
                <div class="image-holder" style="flex: 1; max-height: 400px;">
                    <img src="{{ asset('img/department2.jpg') }}" alt="image-place" id="img" style="object-fit: cover; width: 100%; height: 100%; max-height: 400px;">
                </div>
              <form action="" id="form2" style="flex: 1; padding: 1rem;" class="mt-3 mt-md-0">
                @csrf
                @if(isset($department))
                    @method('PUT')
                @endif
                <h2 class="h4 mb-4">{{ isset($department) ? 'Edit' : 'Add New' }} Department</h2>

                <div class="form-wrapper mb-3">
                  <input type="text" placeholder="Department Name" class="form-control" id="d-p-Name" name="name" value="{{ $department->name ?? '' }}" required>
                  <p class="error-message text-danger small mt-1" id="name_error"></p>
                </div>

                <div class="form-wrapper mb-3">
                <textarea name="description" id="area" class="form-control" placeholder="Department Description" rows="6" required style="width: 100%; resize: vertical;">{{ $department->description ?? '' }}</textarea>
                <p class="error-message text-danger small mt-1" id="description_error"></p>
                </div>

                <div class="button-wrapper d-flex gap-2 justify-content-end">
                  <button type="button" id="btn2" class="btn btn-secondary" onclick="window.history.back()">Cancel</button>
                  <button type="submit" id="btn1" class="btn btn-primary">{{ isset($department) ? 'Update' : 'Add' }}</button>
                </div>
              </form>
              </div>
            </div>
          </div>
        </div>
      </div> 
    </div>
    <!--==========================================================================================================================-->
    <div id="notification-container"></div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('#form2');
        const submitBtn = document.querySelector('#btn1');
        
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

        document.querySelectorAll('.error-message').forEach(msg => msg.style.display = 'none');

        const isEdit = {{ isset($department) ? 'true' : 'false' }};
        const departmentId = {{ $department->id ?? 'null' }};
        
        form.addEventListener('submit', async function(event) {
            event.preventDefault();
            
            document.querySelectorAll('.error-message').forEach(msg => {
                msg.style.display = 'none';
                msg.textContent = '';
            });
            document.querySelectorAll('.form-control').forEach(input => {
                input.style.border = '1px solid #ced4da';
            });

            const formData = {
                name: document.getElementById('d-p-Name').value,
                description: document.getElementById('area').value,
                _token: document.querySelector('input[name="_token"]').value
            };

            submitBtn.disabled = true;
            submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ${isEdit ? 'Updating...' : 'Adding...'}`;

            try {
                const url = isEdit 
                    ? `{{ url('departments') }}/${departmentId}`
                    : '{{ route("departments.store") }}';
                    
                const response = await fetch(url, {
                    method: isEdit ? 'PUT' : 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();

                if (!response.ok) {
                    if (response.status === 422) {
                        Object.keys(data.errors).forEach(key => {
                            const errorMsg = document.getElementById(`${key}_error`);
                            const input = document.querySelector(`[name="${key}"]`);
                            if (errorMsg && input) {
                                errorMsg.textContent = data.errors[key][0];
                                errorMsg.style.display = 'block';
                                input.style.border = '2px solid red';
                            }
                        });
                        showNotification('Please check the form for errors', 'danger');
                    } else {
                        throw new Error(data.message || `An error occurred while ${isEdit ? 'updating' : 'creating'} the department`);
                    }
                } else {
                    showNotification(data.message, 'success');
                    if (isEdit) {
                        window.location.href = '{{ route("departments.index") }}';
                    } else {
                        form.reset();
                    }
                }
            } catch (error) {
                showNotification(error.message || 'An error occurred', 'danger');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = isEdit ? 'Update' : 'Add';
            }
        });

        const validateInput = (input, regex, errorElement) => {
            const value = input.value;
            const isValid = regex.test(value);
            
            if (!isValid) {
                input.style.border = '2px solid red';
                errorElement.style.display = 'block';
                return false;
            }
            
            input.style.border = '1px solid #ced4da';
            errorElement.style.display = 'none';
            return true;
        };

        document.getElementById('d-p-Name').addEventListener('input', function() {
            validateInput(
                this,
                /^[a-zA-Z\s]{2,50}$/,
                document.getElementById('name_error')
            );
        });

        document.getElementById('area').addEventListener('input', function() {
            validateInput(
                this,
                /^[\w\s.,!?-]{4,500}$/,
                document.getElementById('description_error')
            );
        });
    });
    </script>
    <!-- ================================================= Admin Panel ==================================================== -->
     @include('Admin_panel')
    <!-- ================================================================================================================== -->
    @include('Reports')
    <!-- ===================================================== Footer =============================================================== -->
     @include('Footer')
    <!--========================================================================================================================== -->
    <style>
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
