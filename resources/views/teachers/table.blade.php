<table id="tbl" style="border-collapse: collapse;">
    <thead>
        <tr>
            <th style="padding: 8px;">ID</th>
            <th style="padding: 8px;">First Name</th>
            <th style="padding: 8px;">Last Name</th>
            <th style="padding: 8px;">Email</th>
            <th style="padding: 8px;">Phone</th>
            <th style="padding: 8px;">Department</th>
            <th style="padding: 8px;">Gender</th>
            <th style="padding: 8px;">Salary</th>
            <th style="padding: 8px;">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($teachers as $teacher)
        <tr>
            <td style="padding: 8px;">{{ $teacher->id }}</td>
            <td style="padding: 8px;">{{ $teacher->first_name }}</td>
            <td style="padding: 8px;">{{ $teacher->last_name }}</td>
            <td style="padding: 8px;">{{ $teacher->email }}</td>
            <td style="padding: 8px;">{{ $teacher->phone }}</td>
            <td style="padding: 8px;">
                @foreach($teacher->departments as $dept)
                    <span class="badge bg-primary">{{ $dept->name }}</span>
                @endforeach
            </td>
            <td style="padding: 8px;">{{ ucfirst($teacher->gender) }}</td>
            <td style="padding: 8px;">${{ number_format($teacher->salary, 2) }}</td>
            <td style="padding: 8px;">
                <i class="fa fa-pencil-square-o" aria-hidden="true" 
                   style="color: #007bff; cursor: pointer; margin-right: 10px; font-size: 16px;"
                   onclick="editTeacher({{ $teacher->id }})" 
                   data-toggle="tooltip" title="Edit"></i>
                <i class="fa fa-trash-o" aria-hidden="true" 
                   style="color: #dc3545; cursor: pointer; font-size: 16px;"
                   onclick="deleteTeacher({{ $teacher->id }})" 
                   data-toggle="tooltip" title="Trash"></i>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="9" class="text-center" style="padding: 8px;">No teachers found</td>
        </tr>
        @endforelse
    </tbody>
</table>

@if($isPaginated)
<div class="custom-pagination">
    <ul class="pagination justify-content-center">
        @if ($teachers->onFirstPage())
            <li class="page-item disabled">
                <span class="page-link">Previous</span>
            </li>
        @else
            <li class="page-item">
                <a class="page-link" href="{{ $teachers->previousPageUrl() }}">Previous</a>
            </li>
        @endif

        @for ($i = 1; $i <= $teachers->lastPage(); $i++)
            <li class="page-item {{ ($teachers->currentPage() == $i) ? 'active' : '' }}">
                <a class="page-link" href="{{ $teachers->url($i) }}">{{ $i }}</a>
            </li>
        @endfor

        @if ($teachers->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $teachers->nextPageUrl() }}">Next</a>
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
    padding: 12px;
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

.bt {
    border: none;
    background: none;
    cursor: pointer;
    padding: 5px;
}

#btt1 {
    color: #007bff;
}

#btt2 {
    color: #dc3545;
}

.bt:hover {
    opacity: 0.8;
}


.custom-pagination {
    justify-content: center;
    margin-top: 20px;
    display: flex;
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