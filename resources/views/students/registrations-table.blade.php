<table class="table">
    <thead>
        <tr>
            <th>Course</th>
            <th>Time Slot</th>
            <th>Total Fee</th>
            <th>Paid</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($registrations as $registration)
            <tr>
                <td>{{ $registration->course->name }}</td>
                <td>
                    @php
                        $timeSlot = is_array($registration->time_slot) 
                            ? $registration->time_slot[0] 
                            : json_decode($registration->time_slot)[0];
                        echo trim($timeSlot, '[]"');
                    @endphp
                </td>
                <td>${{ number_format($registration->fees_total, 2) }}</td>
                <td>${{ number_format($registration->fees_paid, 2) }}</td>
                <td>{{ $registration->status }}</td>
                <td>
                    <button class="btn btn-sm text-primary border-0" 
                        onclick='editRegistration(@json($registration))'>
                        Edit
                    </button>
                    <button class="btn btn-sm text-danger border-0"
                        onclick="deleteRegistration({{ $registration->id }})">
                        Delete
                    </button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<style>
.badge {
    padding: 5px 10px;
    border-radius: 4px;
    font-weight: normal;
}
.badge-success { background-color: #28a745; color: white; }
.badge-primary { background-color: #007bff; color: white; }
.badge-danger { background-color: #dc3545; color: white; }
</style>