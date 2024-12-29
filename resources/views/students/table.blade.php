<table id="tbl">
    <thead>
        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Gender</th>
            <th>Address</th>
            <th>Phone</th>
            <th>Date of Birth</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($students as $student)
        <tr>
            <td>{{ $student->id }}</td>
            <td>{{ $student->first_name }}</td>
            <td>{{ $student->last_name }}</td>
            <td>{{ $student->gender }}</td>
            <td>{{ $student->address ?: '-' }}</td>
            <td>{{ $student->phone ?: '-' }}</td>
            <td>{{ $student->dob ? date('Y-m-d', strtotime($student->dob)) : '-' }}</td>
            <td>
                <i class="fa fa-eye" aria-hidden="true" 
                   style="color: #17a2b8; cursor: pointer; margin-right: 10px; font-size: 16px;"
                   onclick="viewStudent({{ $student->id }})" 
                   data-toggle="tooltip" title="View Details"></i>
                <i class="fa fa-pencil-square-o" aria-hidden="true" 
                   style="color: #007bff; cursor: pointer; margin-right: 10px; font-size: 16px;"
                   onclick="editStudent({{ $student->id }})" 
                   data-toggle="tooltip" title="Edit"></i>
                <i class="fa fa-trash-o" aria-hidden="true" 
                   style="color: #dc3545; cursor: pointer; font-size: 16px;"
                   onclick="deleteStudent({{ $student->id }})" 
                   data-toggle="tooltip" title="Trash"></i>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="8" style="text-align: center;">No students found</td>
        </tr>
        @endforelse
    </tbody>
</table>

@if($isPaginated)
<div class="custom-pagination">
    <ul class="pagination">
        @if ($students->onFirstPage())
            <li class="page-item disabled">
                <span class="page-link">Previous</span>
            </li>
        @else
            <li class="page-item">
                <a class="page-link" href="{{ $students->previousPageUrl() }}">Previous</a>
            </li>
        @endif

        @for ($i = 1; $i <= $students->lastPage(); $i++)
            <li class="page-item {{ ($students->currentPage() == $i) ? 'active' : '' }}">
                <a class="page-link" href="{{ $students->url($i) }}">{{ $i }}</a>
            </li>
        @endfor

        @if ($students->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $students->nextPageUrl() }}">Next</a>
            </li>
        @else
            <li class="page-item disabled">
                <span class="page-link">Next</span>
            </li>
        @endif
    </ul>
</div>
@endif

<style>
#tbl {
    width: 100%;
    border-collapse: collapse;
    /* margin-top: 20px; */
}

#tbl th, #tbl td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

#tbl th {
    /* background-color: #f8f9fa; */
    font-weight: bold;
}

#tbl tr:hover {
    background-color: #f5f5f5;
}

.custom-pagination {
    margin-top: 20px;
    margin-left: 0px;
    display: flex;
    justify-content: center;
}

.pagination {
    display: flex;
    list-style: none;
    padding: 0;
    margin: 0;
}

.page-item {
    margin: 0 2px;
}

.page-link {
    padding: 8px 12px;
    border: 1px solid #dee2e6;
    color: #007bff;
    text-decoration: none;
    background-color: #fff;
}

.page-item.active .page-link {
    background-color: #007bff;
    color: white;
    border-color: #007bff;
}

.page-item.disabled .page-link {
    color: #6c757d;
    pointer-events: none;
    background-color: #fff;
}
</style> 