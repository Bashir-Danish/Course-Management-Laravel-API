<!-- <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script> -->
<table id="tbl" style="border-collapse: collapse;">
    <thead>
        <tr>
            <th style="padding: 8px;">ID</th>
            <th style="padding: 8px;">Name</th>
            <th style="padding: 8px;">Fees</th>
            <th style="padding: 8px;">Duration</th>
            <th style="padding: 8px;">Department</th>
            <th style="padding: 8px;">Time Slots</th>
            <th style="padding: 8px;">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($courses as $course)
        <tr>
            <td style="padding: 10px;">{{ $course->id }}</td>
            <td style="padding: 10px;">{{ $course->name }}</td>
            <td style="padding: 10px;">{{ number_format($course->fees, 2) }}</td> 
            <td style="padding: 10px;">{{ $course->duration }}</td>
            <td style="padding: 10px;">{{ $course->department->name }}</td>
            <td style="padding: 10px;" class="time-slots-cell">
                @php
                    $rawSlots = $course->available_time_slots;
                    $decodedSlots = is_string($rawSlots) ? json_decode($rawSlots, true) : $rawSlots;
                    $decodedSlots = is_array($decodedSlots) ? $decodedSlots : [];
                @endphp 
                
                <span class="time-slots-summary">{{ count($decodedSlots) }} slots</span>
                <div class="time-slots-tooltip">
                    @if(empty($decodedSlots))
                        <div class="time-slot-group">
                            <div class="time-slot-header">No time slots available</div>
                        </div>
                    @else
                        @php
                        $morningSlots = array_filter($decodedSlots, function($slot) {
                            $startTime = explode('-', $slot)[0];
                            return strtotime($startTime) < strtotime('12:00:00');
                        });
                        $afternoonSlots = array_filter($decodedSlots, function($slot) {
                            $startTime = explode('-', $slot)[0];
                            return strtotime($startTime) >= strtotime('12:00:00');
                        });
                        @endphp
                        
                        @if(count($morningSlots) > 0)
                            <div class="time-slot-group">
                                <div class="time-slot-header">Morning</div>
                                @foreach($morningSlots as $slot)
                                    <div class="time-slot-item">{{ $slot }}</div>
                                @endforeach
                            </div>
                        @endif
                        
                        @if(count($afternoonSlots) > 0)
                            <div class="time-slot-group">
                                <div class="time-slot-header">Afternoon</div>
                                @foreach($afternoonSlots as $slot)
                                    <div class="time-slot-item">{{ $slot }}</div>
                                @endforeach
                            </div>
                        @endif
                    @endif
                </div>
            </td>
            <td style="padding: 10px;">
                <i class="fa fa-pencil-square-o" aria-hidden="true" 
                   style="color: #007bff; cursor: pointer; margin-right: 10px; font-size: 16px;"
                   onclick="editCourse({{ $course->id }})" 
                   data-toggle="tooltip" title="Edit">
                </i>

                <i class="fa fa-trash" aria-hidden="true" 
                   style="color: #dc3545; cursor: pointer; font-size: 16px;"
                   onclick="deleteCourse({{ $course->id }})" 
                   data-toggle="tooltip" title="Trash"></i>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center" style="padding: 10px;">No courses found</td>
        </tr>
        @endforelse
    </tbody>
</table>

@if($isPaginated)
<div class="custom-pagination">
    <ul class="pagination justify-content-center">
        @if ($courses->onFirstPage())
            <li class="page-item disabled">
                <span class="page-link">Previous</span>
            </li>
        @else
            <li class="page-item">
                <a class="page-link" href="{{ $courses->previousPageUrl() }}">Previous</a>
            </li>
        @endif

        @for ($i = 1; $i <= $courses->lastPage(); $i++)
            <li class="page-item {{ ($courses->currentPage() == $i) ? 'active' : '' }}">
                <a class="page-link" href="{{ $courses->url($i) }}">{{ $i }}</a>
            </li>
        @endfor

        @if ($courses->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $courses->nextPageUrl() }}">Next</a>
            </li>
        @else
            <li class="page-item disabled">
                <span class="page-link">Next</span>
            </li>
        @endif
    </ul>
</div>
@endif 