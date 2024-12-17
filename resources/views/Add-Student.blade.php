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
                  <img src="{{ asset('img/student/student.jpg') }}" alt="image-place" id="img">
                </div>
              <form action="" id="form3">
                <h2>{{ isset($student) ? 'Edit Student' : 'Add New Student' }}</h2>
                <div id="notification" class="alert" style="display: none; margin-bottom: 15px;"></div>

                <div class="form-group">
                  <input type="text" placeholder="First Name" class="form-control" id="T_Name" required 
                         value="{{ $student->first_name ?? '' }}">
                  <input type="text" placeholder="Last Name" class="form-control" id="L_Name" required 
                         value="{{ $student->last_name ?? '' }}">
                </div>

                <div class="form-wrapper">
                  <select name="gender" id="gender" class="form-control" required >
                    <option value="" disabled selected>Gender</option>
                    <option value="Male" {{ isset($student) && $student->gender == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ isset($student) && $student->gender == 'Female' ? 'selected' : '' }}>Female</option>
                  </select>
                </div>
                
                <div class="form-wrapper">
                  <input type="text" placeholder="Address" class="form-control" id="Address" required 
                         value="{{ $student->address ?? '' }}">
                  <p id="address-error" class="error-message">Invalid Input</p>
                </div>

                <div class="form-wrapper">
                  <input type="text" placeholder="Phone Number" class="form-control" id="Phone_N" required 
                         value="{{ $student->phone ?? '' }}">
                  <p id="phone-error" class="error-message">Invalid Input</p>
                </div>

                <div class="form-wrapper">
                  <input type="date" placeholder="Date of Birth" class="form-control" id="dob" required 
                         value="{{ isset($student) ? date('Y-m-d', strtotime($student->dob)) : '' }}">
                  <p id="dob-error" class="error-message">Invalid Input</p>
                </div>

                <button id="btn2" type="button">Cancel</button>
                <button id="btn1" type="submit">{{ isset($student) ? 'Update' : 'Add' }}</button>
            
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
    const DOB = document.querySelector('#dob');
  
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
  
    form.addEventListener('submit', async function(event) {
        event.preventDefault();
        
        if (!checkInputs()) {
            showNotification('Please check the form for errors', 'danger');
            return;
        }

        const formData = {
            first_name: T_Name.value,
            last_name: L_Name.value,
            gender: document.getElementById('gender').value,
            address: Address.value,
            phone: Phone.value,
            dob: DOB.value,
            _token: '{{ csrf_token() }}'
        };

        const isEdit = {{ isset($student) ? 'true' : 'false' }};
        const url = isEdit 
            ? `{{ url('students') }}/{{ $student->id ?? '' }}`
            : '{{ route("students.store") }}';

        try {
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

            if (!response.ok) {
                if (response.status === 422) {
                    showNotification('Please check the form for errors', 'danger');
                } else {
                    throw new Error(data.message || 'Error processing student');
                }
            } else {
                showNotification(data.message || 'Student saved successfully', 'success');
                if (isEdit) {
                    window.location.href = '{{ route("students.index") }}';
                } else {
                    form.reset();
                }
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification(error.message || 'An error occurred', 'danger');
        }
    });
  
    const checkInputs = () => {
      const t_nameValue = T_Name.value;
      const l_nameValue = L_Name.value;
      const addressValue = Address.value;
      const phoneValue = Phone.value;
  
      const validName = /^[a-zA-Z\s]{2,50}$/;
      const valid_address = /^[a-zA-Z0-9\s,.-]{5,200}$/;
      const valid_phone = /^\+?[0-9+-00]{7,15}$/;
      let isValid = true;
  
      if(!validName.test(t_nameValue)){
        document.getElementById("T_Name").style.border = "2px solid red";
        isValid = false;
      }
      if(!validName.test(l_nameValue)){
        document.getElementById("L_Name").style.border = "2px solid red";
        isValid = false;
      }
      if(!valid_address.test(addressValue)){
        document.getElementById("Address").style.border = "2px solid red";
        document.getElementById("address-error").style.display = "inherit";
        isValid = false;
      }
      if(!valid_phone.test(phoneValue)){
        document.getElementById("Phone_N").style.border = "2px solid red";
        document.getElementById("phone-error").style.display = "inherit";
        isValid = false;
      }
      if(!DOB.value){
        document.getElementById("dob").style.border = "2px solid red";
        document.getElementById("dob-error").style.display = "inherit";
        isValid = false;
      }
  
      return isValid;
    }
  
    document.querySelectorAll('.form-control').forEach(input => {
      input.addEventListener('input', function() {
        this.style.border = '1px solid #ced4da';
        const errorMsg = this.nextElementSibling;
        if (errorMsg && errorMsg.classList.contains('error-message')) {
          errorMsg.style.display = 'none';
        }
      });
    });
  
    document.getElementById('btn2').addEventListener('click', function() {
        window.location.href = '{{ route("students.index") }}';
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
    .error-message {
        color: #dc3545;
        font-size: 12px;
        margin-top: 5px;
        display: none;
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
    <div id="notification-container"></div>
  </body>
</html>
