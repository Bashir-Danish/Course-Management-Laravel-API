<table id="tbl" style="border-collapse: collapse;">
    <thead>
        <tr>
            <th style="padding: 8px;">ID</th>
            <th style="padding: 8px;">Department Name</th>
            <th style="padding: 8px;">Department Description</th>
            <th style="padding: 8px;">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($departments as $department)
        <tr>
            <td style="padding: 8px;">{{ $department->id }}</td>
            <td style="padding: 8px;">{{ $department->name }}</td>
                <td style="padding: 8px;">{{ $department->description }}</td>
            <td style="padding: 8px;">
                <i class="fa fa-pencil-square-o" aria-hidden="true" 
                   style="color: #007bff; cursor: pointer; margin-right: 10px; font-size: 16px;"
                   onclick="window.parent.editDepartment({{ $department->id }})" 
                   data-toggle="tooltip" title="Edit"></i>
                <i class="fa fa-trash" aria-hidden="true" 
                   style="color: #dc3545; cursor: pointer; font-size: 16px;"
                   onclick="window.parent.deleteDepartment({{ $department->id }})" 
                   data-toggle="tooltip" title="Trash"></i>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="4" class="text-center" style="padding: 5px;">No departments found</td>
        </tr>
        @endforelse
    </tbody>
</table>

@if($isPaginated)
<div class="custom-pagination">
    <ul class="pagination justify-content-center">
        @if ($departments->onFirstPage())
            <li class="page-item disabled">
                <span class="page-link">Previous</span>
            </li>
        @else
            <li class="page-item">
                <a class="page-link" href="{{ $departments->previousPageUrl() }}">Previous</a>
            </li>
        @endif

        @for ($i = 1; $i <= $departments->lastPage(); $i++)
            <li class="page-item {{ ($departments->currentPage() == $i) ? 'active' : '' }}">
                <a class="page-link" href="{{ $departments->url($i) }}">{{ $i }}</a>
            </li>
        @endfor

        @if ($departments->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $departments->nextPageUrl() }}">Next</a>
            </li>
        @else
            <li class="page-item disabled">
                <span class="page-link">Next</span>
            </li>
        @endif
    </ul>
</div>
@endif 