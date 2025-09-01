@extends('layouts.dashboard')

@section('title', 'Role Page Access')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Add Role Page Access</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('role_access.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="role_id" class="form-label fw-semibold">Select Role</label>
                    <select name="role_id" id="role_id" class="form-select" required>
                        <option disabled selected>-- Choose Role --</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
    <label class="form-label fw-semibold">Page Name</label>

    <div id="pageSelectBox">
        <select name="page_name_select" id="page_name_select" class="form-select">
            <option disabled selected>-- Choose Page Name --</option>
            @foreach($pages as $page)
                <option value="{{ $page->page_name }}">{{ $page->page_name }}</option>
            @endforeach
        </select>
        <small class="form-text text-muted">
            <a href="#" onclick="toggleInput(true); return false;">Click here to enter new page name</a>
        </small>
    </div>

    <div id="pageInputBox" style="display: none;">
        <input type="text" name="page_name_input" id="page_name_input" class="form-control" placeholder="Enter new page name">
        <small class="form-text text-muted">
            <a href="#" onclick="toggleInput(false); return false;">Select from existing pages</a>
        </small>
    </div>
</div>


                <div class="d-flex justify-content-end">
                    <a href="{{ url()->previous() }}" class="btn btn-secondary me-2">Cancel</a>
                    <button type="submit" class="btn btn-success">Add Access</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleInput(showInput) {
        const selectBox = document.getElementById('pageSelectBox');
        const inputBox = document.getElementById('pageInputBox');
        const select = document.getElementById('page_name_select');
        const input = document.getElementById('page_name_input');

        if (showInput) {
            selectBox.style.display = 'none';
            inputBox.style.display = 'block';
            select.disabled = true;
            input.required = true;
        } else {
            selectBox.style.display = 'block';
            inputBox.style.display = 'none';
            select.disabled = false;
            input.required = false;
        }
    }
</script>
@endsection