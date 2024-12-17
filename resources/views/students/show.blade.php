<!DOCTYPE html>
<html class="no-js" lang="en">
  <head>
     <!--================================================= Head Part ==================================================== -->
     @include('head')
     <!--================================================================================================================ -->
     <script>
     // Prevent WOW.js error
     window.WOW = function() {
         return {
             init: function() {}
         };
     };
     </script>
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
      <div class="container-fluid">
          <div class="row">
              <div class="col-lg-12">
                  <div class="card">
                      <div class="info-row">
                          <div class="row w-100">
                              <!-- Student Info Column -->
                              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                  <div class="student-info">
                                      <h3>{{ $student->first_name }} {{ $student->last_name }}</h3>
                                      <div class="info-grid">
                                          <div class="info-item">
                                              <span>Gender:</span>
                                              <span>{{ $student->gender }}</span>
                                          </div>
                                          <div class="info-item">
                                              <span>Phone:</span>
                                              <span>{{ $student->phone ?: '-' }}</span>
                                          </div>
                                          <div class="info-item">
                                              <span>DOB:</span>
                                              <span>{{ date('Y-m-d', strtotime($student->dob)) }}</span>
                                          </div>
                                          <div class="info-item">
                                              <span>Address:</span>
                                              <span>{{ $student->address ?: '-' }}</span>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                              
                              <!-- Payment Summary Column -->
                              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                  <div class="payment-summary">
                                      <div class="summary-boxes">
                                          <div class="summary-box">
                                              <h6>Total Fees</h6>
                                              <h4>${{ number_format($student->registrations->sum('fees_total'), 2) }}</h4>
                                          </div>
                                          <div class="summary-box">
                                              <h6>Total Paid</h6>
                                              <h4>${{ number_format($student->registrations->sum('fees_paid'), 2) }}</h4>
                                          </div>
                                          <div class="summary-box">
                                              <h6>Remaining</h6>
                                              <h4 class="text-danger">${{ number_format($student->registrations->sum('fees_total') - $student->registrations->sum('fees_paid'), 2) }}</h4>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>

                      <!-- Course Registration Form -->
                      <div class="card-body">
                          <h5 class="mb-3">Course Registration</h5>
                          <form id="registrationForm" class="registration-form">
                              <div class="row">
                                  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" style="width: 37%;">
                                      <div class="form-group">
                                          <label for="course_id">Select Course:</label>
                                          <select class="form-control form-control-sm" id="course_id" name="course_id" required>
                                              <option value="">Select Course</option>
                                              @foreach($courses as $course)
                                                  <option value="{{ $course['id'] }}" 
                                                      data-fee="{{ $course['fees'] }}"
                                                      data-time-slots="{{ $course['available_time_slots'] }}">
                                                      {{ $course['name'] }}
                                                  </option>
                                              @endforeach
                                          </select>
                                          <small class="text-muted">Course Fee: $<span id="course_fee">0.00</span></small>
                                      </div>
                                  </div>
                                  <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" style="width: 25%;">
                                      <div class="form-group">
                                          <label for="time_slot">Time Slot:</label>
                                          <select class="form-control form-control-sm" id="time_slot" name="time_slot" required disabled>
                                              <option value="">First select a course</option>
                                          </select>
                                      </div>
                                  </div>
                                  <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" style="width: 25%;">
                                      <div class="form-group">
                                          <label for="fees_paid">Payment Amount:</label>
                                          <input type="number" class="form-control form-control-sm" id="fees_paid" name="fees_paid" 
                                                 step="0.01" min="0" required>
                                      </div>
                                      <input type="hidden" name="student_id" value="{{ $student->id }}">
                                  </div>
                                  <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1" style="width: 10%;">
                                      <button type="submit" class="btn btn-primary btn-sm mt-2">Register Course</button>
                                  </div>
                              </div>
                          </form>
                      </div>

                      <!-- Registrations Table -->
                      <div class="card-body border-top">
                          <div class="table-responsive" id="registrationsTable">
                              <!-- Table content will be loaded via AJAX -->
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
      <!-- ================================================================================================ -->
        
    </div> 
    <!--==========================================================================================================================-->

    <!-- =========================================  ======== Admin Panel ==================================================== -->
     @include('Admin_panel')
    <!-- ================================================================================================================== -->
    @include('Reports')
    <!-- ===================================================== Footer =============================================================== -->
     @include('Footer')
    <!--========================================================================================================================== -->

    <script>
    // Load courses on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadRegistrations();
    });

    // Update course fee when course is selected
    document.getElementById('course_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        console.log('Selected course time slots:', {
            raw: selectedOption.dataset.timeSlots,
            parsed: JSON.parse(selectedOption.dataset.timeSlots)
        });
        const timeSlotSelect = document.getElementById('time_slot');
        const courseFee = parseFloat(selectedOption.dataset.fee || 0).toFixed(2);
        
        document.getElementById('course_fee').textContent = courseFee;
        document.getElementById('fees_paid').max = courseFee;

        // Update time slots
        timeSlotSelect.innerHTML = '<option value="">Select Time</option>';
        timeSlotSelect.disabled = !this.value;
        
        if (this.value) {
            try {
                // Get the time slots from the data attribute and parse it
                let timeSlots = selectedOption.dataset.timeSlots;
                
                // If the time slots are double-encoded JSON, parse it twice
                try {
                    timeSlots = JSON.parse(timeSlots);
                    // If it's still a string (due to double encoding), parse again
                    if (typeof timeSlots === 'string') {
                        timeSlots = JSON.parse(timeSlots);
                    }
                } catch (e) {
                    console.error('Parse error:', e);
                    timeSlots = [];
                }

                // Ensure we have an array
                if (Array.isArray(timeSlots)) {
                    timeSlots.forEach(slot => {
                        const formattedSlot = slot.charAt(0).toUpperCase() + slot.slice(1);
                        const option = new Option(formattedSlot, slot);
                        timeSlotSelect.add(option);
                    });
                } else {
                    console.error('Time slots is not an array:', timeSlots);
                }
            } catch (error) {
                console.error('Error loading time slots:', error);
                showNotification('Error loading time slots', 'danger');
            }
        }
    });

    // Add the checkInputs function
    function checkInputs() {
        let isValid = true;
        const form = document.getElementById('registrationForm');
        const isEdit = Boolean(form.dataset.editingId);
        
        const requiredFields = {
            'course_id': 'Please select a course',
            'time_slot': 'Please select a time slot'
        };
        
        // Only require fees_paid for new registrations
        if (!isEdit) {
            requiredFields['fees_paid'] = 'Please enter payment amount';
        }

        // Check each required field
        Object.keys(requiredFields).forEach(fieldId => {
            const field = document.getElementById(fieldId);
            const value = field.value.trim();
            
            if (!value) {
                field.style.border = '1px solid red';
                showNotification(requiredFields[fieldId], 'danger');
                isValid = false;
            } else {
                field.style.border = '1px solid #ced4da';
            }
        });

        // Validate fees if provided (even during update)
        const feesPaidInput = document.getElementById('fees_paid');
        const feesPaidValue = feesPaidInput.value.trim();
        if (feesPaidValue) {
            const courseFee = parseFloat(document.getElementById('course_fee').textContent);
            const feesPaid = parseFloat(feesPaidValue);
            
            if (!isEdit && feesPaid > courseFee) {
                feesPaidInput.style.border = '1px solid red';
                showNotification('Payment amount cannot exceed course fee', 'danger');
                isValid = false;
            }
        }

        return isValid;
    }

    // Add input event listeners to clear error styling
    document.querySelectorAll('#registrationForm input, #registrationForm select').forEach(element => {
        element.addEventListener('input', function() {
            this.style.border = '1px solid #ced4da';
        });
    });

    // Add this new function to refresh payment summary
    async function refreshPaymentSummary() {
        try {
            const response = await fetch(`{{ route('students.show', $student->id) }}`);
            const text = await response.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(text, 'text/html');
            
            // Get the new summary boxes
            const newSummary = doc.querySelector('.payment-summary');
            // Update the existing summary
            document.querySelector('.payment-summary').innerHTML = newSummary.innerHTML;
        } catch (error) {
            console.error('Error refreshing payment summary:', error);
        }
    }

    // Handle registration form submission
    document.getElementById('registrationForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        if (checkInputs()) {
            const timeSlot = document.getElementById('time_slot').value;
            
            if (!timeSlot) {
                showNotification('Please select a time slot', 'danger');
                return;
            }

            const formData = {
                student_id: {{ $student->id }},
                course_id: document.getElementById('course_id').value,
                time_slot: JSON.stringify([timeSlot]),
                fees_total: parseFloat(document.getElementById('course_fee').textContent),
                registration_date: new Date().toISOString().split('T')[0],
                status: 'Unpaid',
                _token: '{{ csrf_token() }}'
            };

            // Only include fees_paid if it has a value during update
            const feesPaidValue = document.getElementById('fees_paid').value;
            if (this.dataset.editingId) {
                // If updating and fees_paid has a value, include it
                if (feesPaidValue.trim()) {
                    formData.fees_paid = parseFloat(feesPaidValue);
                }
            } else {
                // For new registrations, fees_paid is required
                formData.fees_paid = parseFloat(feesPaidValue || 0);
            }

            try {
                const isEdit = Boolean(this.dataset.editingId);
                const url = isEdit 
                    ? `{{ url("registrations") }}/${this.dataset.editingId}`
                    : '{{ route("registrations.store") }}';
                
                console.log('Submitting registration with data:', formData);

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
                
                console.log('Registration response:', {
                    status: response.status,
                    data: data
                });

                if (response.ok) {
                    showNotification(`Registration ${isEdit ? 'updated' : 'added'} successfully`);
                    this.reset();
                    this.dataset.editingId = '';
                    await loadRegistrations();
                    await refreshPaymentSummary();
                    
                    // Reset form state
                    document.getElementById('time_slot').disabled = true;
                    document.getElementById('time_slot').innerHTML = '<option value="">First select a course</option>';
                    document.getElementById('course_fee').textContent = '0.00';
                    document.getElementById('fees_paid').placeholder = 'Enter payment amount';
                    document.querySelector('#registrationForm button[type="submit"]').textContent = 'Register Course';
                } else {
                    // Show validation errors if they exist
                    if (data.errors) {
                        const errorMessages = Object.values(data.errors).flat();
                        errorMessages.forEach(message => {
                            showNotification(message, 'danger');
                        });
                    } else {
                        throw new Error(data.message || `Error ${isEdit ? 'updating' : 'adding'} registration`);
                    }
                }
            } catch (error) {
                console.error('Registration error:', error);
                showNotification(error.message || 'Error processing registration', 'danger');
            }
        }
    });

    // Load registrations table
    async function loadRegistrations() {
        try {
            const response = await fetch(`{{ route("registrations.student", $student->id) }}`);
            const html = await response.text();
            document.getElementById('registrationsTable').innerHTML = html;
        } catch (error) {
            console.error('Error loading registrations:', error);
            showNotification('Error loading registrations', 'danger');
        }
    }

    // Delete registration
    async function deleteRegistration(id) {
        if (!confirm('Are you sure you want to delete this registration?')) return;

        try {
            const response = await fetch(`{{ url("registrations") }}/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (response.ok) {
                showNotification('Registration deleted successfully');
                await loadRegistrations();
                await refreshPaymentSummary();
            } else {
                throw new Error(data.message || 'Error deleting registration');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification(error.message, 'danger');
        }
    }

    // Edit registration (load data into form)
    function editRegistration(registration) {
        // Store registration ID for update
        document.getElementById('registrationForm').dataset.editingId = registration.id;
        
        // Set course and trigger change event to load time slots
        const courseSelect = document.getElementById('course_id');
        courseSelect.value = registration.course_id;
        
        // Trigger the change event manually to load time slots
        const changeEvent = new Event('change');
        courseSelect.dispatchEvent(changeEvent);
        
        // Wait for time slots to load then set the selected time slot
        setTimeout(() => {
            const timeSlotSelect = document.getElementById('time_slot');
            let timeSlot;
            try {
                // Handle different time_slot formats
                if (typeof registration.time_slot === 'string') {
                    // Clean up the time slot string
                    timeSlot = JSON.parse(registration.time_slot)[0];
                    timeSlot = timeSlot.replace(/[\[\]"]/g, '');
                } else if (Array.isArray(registration.time_slot)) {
                    timeSlot = registration.time_slot[0];
                    timeSlot = timeSlot.replace(/[\[\]"]/g, '');
                } else {
                    timeSlot = registration.time_slot;
                }
                
                // Find and select the matching option
                const options = Array.from(timeSlotSelect.options);
                const matchingOption = options.find(option => {
                    const cleanOptionValue = option.value.replace(/[\[\]"]/g, '');
                    return cleanOptionValue === timeSlot;
                });
                
                if (matchingOption) {
                    timeSlotSelect.value = matchingOption.value;
                }
            } catch (e) {
                console.error('Error parsing time slot:', e);
            }
        }, 300); // Increased timeout to ensure options are loaded

        // Clear and update fees_paid field
        const feesPaidInput = document.getElementById('fees_paid');
        feesPaidInput.value = '';
        feesPaidInput.required = false; // Remove required attribute for updates
        feesPaidInput.placeholder = `Current paid: $${registration.fees_paid} (optional)`;
        
        // Update the course fee display
        document.getElementById('course_fee').textContent = registration.fees_total.toFixed(2);
        
        // Change button text to indicate update mode
        const submitButton = document.querySelector('#registrationForm button[type="submit"]');
        submitButton.textContent = 'Update Registration';
        
        // Scroll to form
        document.getElementById('registrationForm').scrollIntoView({ behavior: 'smooth' });
    }

    function showNotification(message, type = 'success') {
        // Get or create the notification container
        let container = document.getElementById('notification-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'notification-container';
            document.body.appendChild(container);
        }

        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        
        // Create message text
        const messageText = document.createElement('span');
        messageText.textContent = message;
        
        // Create close button
        const closeButton = document.createElement('button');
        closeButton.className = 'notification-close';
        closeButton.innerHTML = '&times;';
        closeButton.onclick = () => notification.remove();
        
        // Assemble notification
        notification.appendChild(messageText);
        notification.appendChild(closeButton);
        container.appendChild(notification);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 3000);
    }

    // Add this debug code temporarily to check the data format
    document.addEventListener('DOMContentLoaded', function() {
        const courseSelect = document.getElementById('course_id');
        Array.from(courseSelect.options).forEach(option => {
            if (option.value) {
                console.group('Course Option Data');
                console.log('Course:', option.text);
                console.log('Raw time slots:', option.dataset.timeSlots);
                try {
                    const parsed = JSON.parse(option.dataset.timeSlots.replace(/^"(.*)"$/, '$1'));
                    console.log('Parsed time slots:', parsed);
                } catch (e) {
                    console.error('Parse error:', e);
                }
                console.groupEnd();
            }
        });
    });
    </script>

    <style>
    .card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }

    .card-header {
        padding: 20px;
        border-bottom: 1px solid #dee2e6;
    }

    .student-details {
        padding-right: 20px;
    }

    .info-table {
        margin-top: 15px;
    }

    .info-table .table {
        margin-bottom: 0;
    }

    .info-table th {
        font-weight: 600;
        color: #666;
    }

    .payment-summary {
        padding-left: 20px;
        border-left: 1px solid #dee2e6;
    }

    .summary-boxes {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
    }

    .summary-box {
        text-align: center;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 6px;
        flex: 1;
        margin: 0 10px;
    }

    .summary-box h6 {
        color: #666;
        margin-bottom: 10px;
    }

    .summary-box h4 {
        margin: 0;
        color: #333;
    }

    .form-control {
        border: 1px solid #ced4da;
        border-radius: 4px;
        padding: 4px 8px;
    }

    .btn-primary {
        background-color: #4ab2cc;
        border-color: #4ab2cc;
    }

    .btn-primary:hover {
        background-color: #3a8fa3;
        border-color: #3a8fa3;
    }

    .alert {
        padding: 10px 15px;
        border-radius: 4px;
        margin-bottom: 15px;
    }

    .card-body {
        padding: 20px;
    }

    .border-top {
        border-top: 1px solid #dee2e6;
    }

    /* New styles for the form layout */
    #registrationForm {
        gap: 10px;
    }

    #registrationForm .form-group {
        
    }

    .form-control-sm {
        height: 31px;
        width: 50%;
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }

    .me-2 {
        margin-right: 0.5rem;
    }

    /* Adjust label and small text size */
    #registrationForm label,
    #registrationForm small {
        font-size: 0.8rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Registration form styles */
    .registration-form {
        background-color: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
    }

    .info-row {
        height: 100px;
        padding: 10px 20px;
        background: white;
        border-bottom: 1px solid #dee2e6;
    }

    .student-info h3 {
        font-size: 1.2rem;
        margin-bottom: 5px;
        color: #333;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
    }

    .info-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.85rem;
    }

    .info-item span:first-child {
        color: #666;
        font-weight: 500;
        width: 50px;
    }

    .info-item span:last-child {
        color: #333;
    }

    /* Payment Summary Styles */
    .payment-summary {
        border-left: 1px solid #eee;
        padding-left: 15px;
    }

    .payment-summary h3 {
        font-size: 1.1rem;
        margin-bottom: 5px;
        color: #333;
    }

    .summary-boxes {
        display: flex;
        justify-content: space-between;
        gap: 10px;
    }

    .summary-box {
        text-align: center;
        padding: 0 10px;
        background: #f8f9fa;
        border-radius: 6px;
        flex: 1;
    }

    .summary-box h6 {
        color: #666;
        margin-bottom: 2px;
        font-size: 0.75rem;
    }

    .summary-box h4 {
        margin: 0;
        color: #333;
        font-size: 1rem;
    }

    /* Adjust button width */
    .btn-primary.btn-sm {
        width: 100%;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    #notification-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1050;
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
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
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
