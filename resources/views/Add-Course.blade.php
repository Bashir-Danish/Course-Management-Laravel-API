
<!DOCTYPE html>
<html class="no-js" lang="en">
  <head>
     <!--================================================= Head Part ==================================================== -->
     @include('head')
     <!--================================================================================================================ -->
  </head>
  
  <body>
    <!-- <div id="notification-container"></div> -->
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

      <!-- <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="logo-pro">
              <a href="index.html"><h2 id="head"><span id="title-1">Nano</span><span id="title-2">Net</span></h2></a>
            </div>
          </div>
        </div>
      </div> -->
         <!-- header section -->
        @include('header')
        <!-- Mobile Menu start -->
        @include('Mobile_menu')

        <div class="container-fluid">
          <div class="row">
            <div class="wrapper">
              <div class="inner">
                <div class="image-holder">
                      <img src="{{ asset('img/language2.jpg') }}" alt="image-place" id="img">
                </div>
              <form id="form1" method="POST" action="{{isset($course) ? route('courses.update', $course->id) : route('courses.store') }}">
                @csrf
                @if(isset($course))
                    @method('PUT')
                @endif
                <br><br><br><br>
                <h2>{{ isset($course) ? 'Edit' : 'Add New' }} Course</h2>

                <div class="form-wrapper">
                  <input type="text" placeholder="Course Name" class="form-control" id="CourseName" name="name"
                         value="{{ $course->name ?? '' }}" required>
                  <p id="ppp1" class="error-message">Invalid Input</p>
                </div>

                <div class="form-wrapper">
                  <input type="number" placeholder="Fees Of Course" class="form-control" id="FeesOfCourse" name="fees"
                         value="{{ $course->fees ?? '' }}" required>
                </div>

                <div class="form-wrapper">
                  <select id="department" name="department_id" class="form-control" required>
                    <option value="" disabled selected>Department</option>
                    @foreach($departments as $department)
                      <option value="{{ $department->id }}"
                              {{ (isset($course) && $course->department_id == $department->id) ? 'selected' : '' }}>
                        {{ $department->name }}
                      </option>
                    @endforeach
                  </select>
                </div>

                <div class="form-wrapper">
                  <input type="text" placeholder="Duration Of Course" class="form-control" id="Duration" name="duration"
                         value="{{ $course->duration ?? '' }}" required>
                  <p id="ppp2" class="error-message">Invalid Input</p>
                </div>

                <div class="dropdown1">
                  <button type="button" class="form-control" id="bbb">Available Time Slots</button>
                  <div class="dropdown-content1">
                    @php
                    $timeSlots = [
                        'Morning' => [
                            '7:00-8:00' => '7:00 - 8:00',
                            '8:30-9:30' => '8:30 - 9:30',
                            '9:00-10:00' => '9:00 - 10:00',
                            '10:30-11:30' => '10:30 - 11:30',
                        ],
                        'Afternoon' => [
                            '12:00-13:00' => '12:00 - 1:00',
                            '13:00-14:00' => '1:00 - 2:00',
                            '14:30-15:30' => '2:30 - 3:30',
                            '16:30-17:30' => '4:30 - 5:30',
                        ]
                    ];
                    $selectedSlots = isset($course) ? (is_array($course->available_time_slots) ? $course->available_time_slots : json_decode($course->available_time_slots ?? '[]', true)) : [];
                    @endphp
                    
                    <div class="time-slots-container">
                      <div class="time-slot-column">
                        <div class="time-period">Morning</div>
                        @foreach($timeSlots['Morning'] as $value => $label)
                            <label>
                                <input type="checkbox" 
                                       value="{{ $value }}" 
                                       {{ in_array($value, $selectedSlots) ? 'checked' : '' }}>
                                {{ $label }}
                            </label>
                        @endforeach
                      </div>
                      <div class="time-slot-column">
                        <div class="time-period">Afternoon</div>
                        @foreach($timeSlots['Afternoon'] as $value => $label)
                            <label>
                                <input type="checkbox" 
                                       value="{{ $value }}" 
                                       {{ in_array($value, $selectedSlots) ? 'checked' : '' }}>
                                {{ $label }}
                            </label>
                        @endforeach
                      </div>
                    </div>
                  </div>
                  <div id="selected-slots" class="selected-slots"></div>
                </div>

                <div style="text-align: center;">
                  <button type="button" id="btn2" onclick="window.history.back()" style="display: inline-block; margin: 0 10px;">Cancel</button>
                  <button type="submit" id="btn1" style="display: inline-block; margin: 0 10px;">{{ isset($course) ? 'Update' : 'Add' }}</button>
                </div>
              </form>
              </div>
            </div>
          </div>
        </div>
    
        <div id="notification-container"></div>

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
    .dropdown1 {
        position: relative;
        display: inline-block;
        width: 100%;
    }
    .time-period {
        font-weight: bold;
        padding: 5px;
        background-color: #f8f9fa;
        margin-top: 5px;
        border-radius: 4px;
        text-align: center;
    }
    .dropdown-content1 {
        padding: 10px;
    }
    .dropdown-content1 label {
        display: block;
        padding: 5px 10px;
        margin: 2px 0;
        cursor: pointer;
        text-align: left;
    }
    .dropdown-content1 label:hover {
        background-color: #f8f9fa;
    }
    .dropdown-content1 input[type="checkbox"] {
        margin-right: 8px;
    }
    .selected-slots {
        margin-top: 5px;
        font-size: 12px;
        color: #666;
    }
    .time-slots-container {
        display: flex;
        gap: 20px;
    }
    .time-slot-column {
        flex: 1;
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
  
                
        <!--=================================================== Form Validation =======================================================================-->
        <script>
          const form = document.querySelector('#form1');
    const C_Name = document.querySelector('#CourseName');
    const F_Of_C = document.querySelector('#FeesOfCourse');
    const Duration = document.querySelector('#Duration');

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
        console.log(messageText);
        
        notification.appendChild(messageText);
        notification.appendChild(closeButton);
        container.appendChild(notification);
        
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 3000);
    }

    function updateSelectedSlots() {
        const selectedSlots = Array.from(document.querySelectorAll('.dropdown-content1 input[type="checkbox"]:checked'))
            .map(cb => cb.parentElement.textContent.trim());
        
        const display = document.getElementById('selected-slots');
        if (selectedSlots.length > 0) {
            display.textContent = 'Selected: ' + selectedSlots.join(', ');
        } else {
            display.textContent = '';
        }
    }

    document.querySelectorAll('.dropdown-content1 input[type="checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedSlots);
    });

    updateSelectedSlots();

    form.addEventListener('submit', async (event) => {
      event.preventDefault();
      if (checkInputs()) {
        const timeSlots = Array.from(document.querySelectorAll('.dropdown-content1 input[type="checkbox"]:checked'))
          .map(cb => cb.value);
        
        if (timeSlots.length === 0) {
          showNotification('Please select at least one time slot', 'danger');
          return;
        }

        const formData = {
          name: C_Name.value, 
          fees: F_Of_C.value,
          duration: Duration.value,
          department_id: document.getElementById('department').value,
          available_time_slots: timeSlots,
          _token: '{{ csrf_token() }}'
        };

        try {
          const isEdit = {{ isset($course) ? 'true' : 'false' }};
          const response = await fetch(form.action, {
            method: isEdit ? 'PUT' : 'POST',
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(formData)
          });

          const data = await response.json();

          if (response.ok) {
            showNotification(`Course ${isEdit ? 'updated' : 'added'} successfully`);
            if (isEdit) {
              setTimeout(() => {
                window.location.href = '{{ route("courses.index") }}';
              }, 1000);
            } else {
              form.reset();
              document.querySelectorAll('.dropdown-content1 input[type="checkbox"]').forEach(cb => cb.checked = false);
              updateSelectedSlots();
              document.getElementById('department').selectedIndex = 0;
            }
          } else {
            if (data.errors) {
              Object.keys(data.errors).forEach(key => {
                const input = document.getElementById(key === 'name' ? 'CourseName' : 
                                                   key === 'fees' ? 'FeesOfCourse' : 
                                                   key === 'duration' ? 'Duration' : key);
                if (input) {
                  input.style.border = '2px solid red';
                }
              });
              showNotification(Object.values(data.errors)[0][0], 'danger');
            } else {
              showNotification(data.message || `Error ${isEdit ? 'updating' : 'adding'} course`, 'danger');
            }
          }
        } catch (error) {
          console.error('Error:', error);
          showNotification(error.message, 'danger');
        }
      }
    });

    document.querySelectorAll('.form-control').forEach(input => {
      input.addEventListener('input', function() {
        this.style.border = '1px solid #ced4da';
        const errorMsg = this.nextElementSibling;
        if (errorMsg && errorMsg.classList.contains('error-message')) {
          errorMsg.style.display = 'none';
        }
      });
    });

    const checkInputs = () => {
      const courseValue = C_Name.value;
      const feescourselValue = F_Of_C.value;
      const durationValue = Duration.value;
  
      const validStr1 = /^[a-zA-Z0-9\s._-]{3,50}$/;
      const validFees = /^[0-9]{2,10}$/;
      const validDuration = /^[a-zA-Z0-9\s-]{2,20}$/;
     
      let isValid = true;

      if(!validStr1.test(courseValue)){
        document.getElementById("CourseName").style.border = "2px solid red";
        document.getElementById("ppp1").style.display = "inherit"; 
        isValid = false;
      }
      if(!validFees.test(feescourselValue)){
        document.getElementById("FeesOfCourse").style.border = "2px solid red";
        isValid = false;
      }
      if(!validDuration.test(durationValue)){
        document.getElementById("Duration").style.border = "2px solid red";
        document.getElementById("ppp2").style.display = "inherit";
        isValid = false;
      }

      return isValid;
    };
   </script>

    </body>
  </html>
