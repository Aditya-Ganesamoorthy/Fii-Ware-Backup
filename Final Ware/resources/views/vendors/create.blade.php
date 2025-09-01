@extends('layouts.dashboard')

@section('title', 'Add New Vendor')

@section('content')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Add New Vendor</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('vendors.index') }}">Vendors</a></li>
                <li class="breadcrumb-item active">Create</li>
            </ol>
        </div>
    </div>

    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Vendor Details</h3>
        </div>

        @if ($errors->any())
        <div class="alert alert-danger mx-3 mt-3">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if(session('success'))
<div class="alert alert-success alert-dismissible mx-3 mt-3">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <i class="icon fas fa-check"></i> {{ session('success') }}
</div>
@endif

        <form action="{{ route('vendors.store') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="company_name">Company Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('company_name') is-invalid @enderror" 
                                   id="company_name" name="company_name" placeholder="e.g. Acme Corporation Pvt. Ltd."
                                   value="{{ old('company_name') }}" required
                                   pattern="^[A-Za-z0-9\s\.\-&]+$">
                            <small class="form-text text-muted">Only letters, numbers, spaces, dots, hyphens and ampersands allowed</small>
                            <div id="company_error" class="invalid-feedback" style="display: none;">
                                This company name is already registered.
                            </div>
                            @error('company_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="authorized_person">Authorized Person <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('authorized_person') is-invalid @enderror"
                                   id="authorized_person" name="authorized_person" placeholder="e.g. John D. Smith"
                                   value="{{ old('authorized_person') }}" required
                                   pattern="^[A-Za-z\s\.]+$">
                            <small class="form-text text-muted">Only letters, spaces, and dots allowed</small>
                            @error('authorized_person')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="mobile_number">Mobile Number <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control @error('mobile_number') is-invalid @enderror"
                                   id="mobile_number" name="mobile_number" placeholder="e.g. 9876543210"
                                   value="{{ old('mobile_number') }}" required
                                   pattern="^[6-9]\d{9}$">
                            <small class="form-text text-muted">10-digit number starting with 6-9</small>
                            @error('mobile_number')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="gst_number">GST Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('gst_number') is-invalid @enderror"
                                   id="gst_number" name="gst_number" placeholder="e.g. 22AAAAA0000A1Z5"
                                   value="{{ old('gst_number') }}" required
                                   pattern="^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$">
                            <small class="form-text text-muted">Format: 22AAAAA0000A1Z5</small>
                            <div id="gst_error" class="invalid-feedback" style="display: none;">
                                This GST number is already registered.
                            </div>
                            @error('gst_number')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="address">Address <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('address') is-invalid @enderror"
                                      id="address" name="address" rows="4"
                                      placeholder="e.g. 123 Business Park, Sector 5, New Delhi - 110001"
                                      required>{{ old('address') }}</textarea>
                            @error('address')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Vendor
                </button>
                <a href="{{ route('vendors.index') }}" class="btn btn-default float-right">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
            
        </form>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Clear form if success message is shown
    @if(session('success'))
        $('#vendorForm')[0].reset();
    @endif

    // Company name duplicate check
    $('#company_name').on('blur', function() {
        var companyName = $(this).val();
        var isValid = $(this)[0].checkValidity();
        
        if (isValid && companyName.length > 0) {
            $.ajax({
                url: '{{ route("vendors.check-company") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    company_name: companyName
                },
                success: function(response) {
                    if (response.exists) {
                        $('#company_name').addClass('is-invalid');
                        $('#company_error').show();
                    } else {
                        $('#company_name').removeClass('is-invalid');
                        $('#company_error').hide();
                    }
                }
            });
        }
    });
    
    // GST number duplicate check
    $('#gst_number').on('blur', function() {
        var gstNumber = $(this).val();
        var isValid = $(this)[0].checkValidity();
        
        if (isValid) {
            $.ajax({
                url: '{{ route("vendors.check-gst") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    gst_number: gstNumber
                },
                success: function(response) {
                    if (response.exists) {
                        $('#gst_number').addClass('is-invalid');
                        $('#gst_error').show();
                    } else {
                        $('#gst_number').removeClass('is-invalid');
                        $('#gst_error').hide();
                    }
                }
            });
        }
    });

    // Form submission handler
    $('#vendorForm').on('submit', function(e) {
        var hasErrors = false;
        
        if ($('#company_name').hasClass('is-invalid')) {
            hasErrors = true;
        }
        
        if ($('#gst_number').hasClass('is-invalid')) {
            hasErrors = true;
        }
        
        if (hasErrors) {
            e.preventDefault();
            alert('Please fix the errors before submitting.');
        }
    });
});
</script>
@endpush
@endsection