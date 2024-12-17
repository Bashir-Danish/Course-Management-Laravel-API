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

        <div class="container-fluid">
          <div class="row">
            <div class="wrapper">
              <div class="inner">
                <div class="image-holder">
                  <img src="{{ asset('img/profile/teache.jpg') }}" alt="image-place" id="img">
                </div>
              <form action="{{ route('teachers.store') }}" method="POST" id="form3">
                @csrf
                <h2>{{ isset($teacher) ? 'Edit' : 'Add New' }} Teacher</h2>

                <div id="notification-container"></div>

                <div class="form-group">
                  <input type="text" placeholder="First Name" class="form-control" id="T_Name" name="first_name" 
                         value="{{ $teacher->first_name ?? '' }}" required>
                  
                  <input type="text" placeholder="Last Name" class="form-control" id="L_Name" name="last_name" 
                         value="{{ $teacher->last_name ?? '' }}" required>
              
                </div>
                
                <div class="form-wrapper">
                  <input type="text" placeholder="Address" class="form-control" id="Address" name="address" 
                         value="{{ $teacher->address ?? '' }}" required>
                  <p id="ppp1" class="error-message">Invalid Input</p>
                </div>

                <div class="form-wrapper">
                  <input type="text" placeholder="Phone Number" class="form-control" id="Phone_N" name="phone" 
                         value="{{ $teacher->phone ?? '' }}" required>
                  <p id="ppp2" class="error-message">Invalid Input</p>
                </div>

                <div class="form-wrapper">
                  <input type="email" placeholder="Email Address" class="form-control" id="Email" name="email" 
                         value="{{ $teacher->email ?? '' }}" required>
                  <p id="ppp3" class="error-message">Invalid Input</p>
                </div>

                <div class="form-wrapper">
                  <div class="department-selector">
                    <select id="department" class="form-control">
                      <option value="" disabled selected>Select Departments</option>
                      @foreach($departments as $department)
                        <option value="{{ $department->id }}" 
                                data-name="{{ $department->name }}">
                          {{ $department->name }}
                        </option>
                      @endforeach
                    </select>
                    <div id="selected-departments" class="selected-tags">
                      @if(isset($teacher) && $teacher->departments)
                        @foreach($teacher->departments as $dept)
                          <span class="tag" data-id="{{ $dept->id }}">
                            {{ $dept->name }}
                            <i class="fa fa-times" onclick="removeTag(this)"></i>
                            <input type="hidden" name="department_ids[]" value="{{ $dept->id }}">
                          </span>
                        @endforeach
                      @endif
                    </div>
                  </div>
                </div>

                <div class="form-wrapper">
                  <select name="gender" id="gender" class="form-control" required>
                    <option value="" disabled selected>Gender</option>
                    <option value="male" {{ (isset($teacher) && $teacher->gender == 'male') ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ (isset($teacher) && $teacher->gender == 'female') ? 'selected' : '' }}>Female</option>
                  </select>
                </div>
                
                <div class="form-wrapper">
                  <input type="number" placeholder="Salary" class="form-control" id="Salary" name="salary" 
                         value="{{ $teacher->salary ?? '' }}" required>
                </div>
                
                <div style="text-align: center;">
                  <button type="button" id="btn2" onclick="window.history.back()" style="display: inline-block; margin: 0 10px;">Cancel</button>
                  <button type="submit" id="btn1" style="display: inline-block; margin: 0 10px;">{{ isset($teacher) ? 'Update' : 'Add' }}</button>
                </div>
            
              </form>
              </div>
            </div>
          </div>
        </div>
      </div> 
    </div>
    <!--==========================================================================================================================-->
    <script>
    const form = document.querySelector('#form3');
    const T_Name = document.querySelector('#T_Name');
    const L_Name = document.querySelector('#L_Name');
    const Address = document.querySelector('#Address');
    const Phone = document.querySelector('#Phone_N');
    const Email = document.querySelector('#Email');
  
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
  
    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        if (checkInputs()) {
            const departmentIds = Array.from(document.getElementsByName('department_ids[]')).map(input => input.value);
            
            if (departmentIds.length === 0) {
                showNotification('Please select at least one department', 'danger');
                return;
            }

            const formData = {
                first_name: T_Name.value,
                last_name: L_Name.value,
                address: Address.value,
                phone: Phone.value,
                email: Email.value,
                department_ids: departmentIds,
                gender: document.getElementById('gender').value,
                salary: document.getElementById('Salary').value,
                _token: '{{ csrf_token() }}'
            };

            try {
                const isEdit = {{ isset($teacher) ? 'true' : 'false' }};
                const url = isEdit 
                    ? `{{ url('teachers') }}/{{ $teacher->id ?? '' }}`
                    : '{{ route("teachers.store") }}';
                
                const response = await fetch(url, {
                    method: isEdit ? 'PUT' : 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();
                console.log('Response:', data);

                if (response.ok) {
                    showNotification(`Teacher ${isEdit ? 'updated' : 'added'} successfully`);
                    if (isEdit) {
                        window.location.href = '{{ route("teachers.index") }}';
                    } else {
                        form.reset();
                        document.getElementById('selected-departments').innerHTML = '';
                    }
                } else {
                    if (data.errors) {
                        Object.keys(data.errors).forEach(key => {
                            const input = document.getElementById(key === 'first_name' ? 'T_Name' : 
                                                               key === 'last_name' ? 'L_Name' : key);
                            if (input) {
                                input.style.border = '2px solid red';
                                const errorP = input.nextElementSibling;
                                if (errorP && errorP.tagName === 'P') {
                                    errorP.textContent = data.errors[key][0];
                                    errorP.style.display = 'block';
                                }
                            }
                        });
                        showNotification(Object.values(data.errors)[0][0], 'danger');
                    }
                    showNotification(data.message || `Error ${isEdit ? 'updating' : 'adding'} teacher`, 'danger');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification(error.message, 'danger');
            }
        }
    });
  
    const checkInputs = () => {
      let isValid = true;
      const validStr1 = /^[a-zA-Z._]{3,20}$/;
      const valid_address = /^[a-zA-Z0-9\s]{2,30}$/;
      const valid_phone = /^\+?[0-9+-00]{7,15}$/;
      const valid_email = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

      document.querySelectorAll('.error-message').forEach(msg => msg.style.display = 'none');
      document.querySelectorAll('.form-control').forEach(input => input.style.border = '1px solid #ced4da');

      if (!validStr1.test(T_Name.value)) {
        T_Name.style.border = "2px solid red";
        isValid = false;
      }
      if (!validStr1.test(L_Name.value)) {
        L_Name.style.border = "2px solid red";
        isValid = false;
      }
      if (!valid_address.test(Address.value)) {
        Address.style.border = "2px solid red";
        document.getElementById("ppp1").style.display = "inherit";
        isValid = false;
      }
      if (!valid_phone.test(Phone.value)) {
        Phone.style.border = "2px solid red";
        document.getElementById("ppp2").style.display = "inherit";
        isValid = false;
      }
      if (!valid_email.test(Email.value)) {
        Email.style.border = "2px solid red";
        document.getElementById("ppp3").style.display = "inherit";
        isValid = false;
      }

      return isValid;
    };
    document.getElementById('department').addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        if (option.value) {
            addDepartmentTag(option.value, option.dataset.name);
            this.selectedIndex = 0; 
        }
    });

    function addDepartmentTag(id, name) {
        if (document.querySelector(`.tag[data-id="${id}"]`)) {
            return;
        }

        const tagsContainer = document.getElementById('selected-departments');
        const tag = document.createElement('span');
        tag.className = 'tag';
        tag.dataset.id = id;
        tag.innerHTML = `
            ${name}
            <i class="fa fa-times" onclick="removeTag(this)"></i>
            <input type="hidden" name="department_ids[]" value="${id}">
        `;
        tagsContainer.appendChild(tag);
    }

    function removeTag(element) {
        element.closest('.tag').remove();
    }
    </script>
    <style>
    .department-selector {
        position: relative;
    }
    .selected-tags {
        margin-top: 10px;
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }
    .tag {
        background: #007bff;
        color: white;
        padding: 5px 10px;
        border-radius: 15px;
        display: inline-flex;
        align-items: center;
        font-size: 14px;
    }
    .tag i {
        margin-left: 5px;
        cursor: pointer;
        font-size: 12px;
    }
    .tag i:hover {
        color: #ff4444;
    }
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
    <!-- ================================================= Admin Panel ==================================================== -->
    @include('Admin_panel')
    <!-- ================================================================================================================== -->
    @include('Reports')
    <!-- ===================================================== Footer =============================================================== -->
     @include('Footer')
    <!--========================================================================================================================== -->
  </body>
</html>
