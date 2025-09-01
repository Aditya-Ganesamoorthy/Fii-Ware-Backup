@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col">
            <h1 class="h3">Edit Vendor</h1>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if ($errors->has('no_change'))
        <div class="alert alert-warning">
            {{ $errors->first('no_change') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('vendors.update', $vendor->id) }}" method="POST" novalidate id="vendorForm">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="company_name" class="form-label">Company Name <span class="text-danger">*</span></label>
                    <input type="text" id="company_name" name="company_name"
                        value="{{ old('company_name', $vendor->company_name) }}"
                        class="form-control @error('company_name') is-invalid @enderror"
                        required maxlength="100"
                        pattern="^[A-Za-z0-9\s\.\-&]+$"
                        title="Only letters, numbers, spaces, dots, hyphens and ampersands allowed">
                    @error('company_name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                    <div class="text-danger company-error" style="display: none;"></div>
                </div>

                <div class="mb-3">
                    <label for="authorized_person" class="form-label">Authorized Person <span class="text-danger">*</span></label>
                    <input type="text" id="authorized_person" name="authorized_person"
                        value="{{ old('authorized_person', $vendor->authorized_person) }}"
                        class="form-control @error('authorized_person') is-invalid @enderror"
                        required maxlength="60"
                        pattern="^[A-Za-z\s\.]+$"
                        title="Only letters, spaces, and dots allowed">
                    @error('authorized_person')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                    <textarea id="address" name="address" class="form-control @error('address') is-invalid @enderror" rows="4"
                        required maxlength="255">{{ old('address', $vendor->address) }}</textarea>
                    @error('address')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="gst_number" class="form-label">GST Number <span class="text-danger">*</span></label>
                    <input type="text" id="gst_number" name="gst_number"
                        value="{{ old('gst_number', $vendor->gst_number) }}"
                        class="form-control @error('gst_number') is-invalid @enderror"
                        required maxlength="15" minlength="15"
                        pattern="^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$"
                        title="Enter a valid 15-character GSTIN (e.g. 22AAAAA0000A1Z5)">
                    @error('gst_number')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                    <div class="text-danger gst-error" style="display: none;"></div>
                </div>

                <div class="mb-3">
                    <label for="mobile_number" class="form-label">Mobile Number <span class="text-danger">*</span></label>
                    <input type="tel" id="mobile_number" name="mobile_number"
                        value="{{ old('mobile_number', $vendor->mobile_number) }}"
                        class="form-control @error('mobile_number') is-invalid @enderror"
                        required maxlength="10" minlength="10"
                        pattern="^[6-9]\d{9}$"
                        title="Enter a valid 10-digit Indian mobile number">
                    @error('mobile_number')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('vendors.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Vendor
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .changed-field {
        background-color: #fffde7;
        border-left: 3px solid #ffd600;
    }
    .is-invalid {
        border-color: #dc3545;
    }
    .text-danger {
        color: #dc3545;
        font-size: 0.875em;
        margin-top: 0.25rem;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Store original values
    const originalValues = {
        company_name: "{{ $vendor->company_name }}",
        authorized_person: "{{ $vendor->authorized_person }}",
        address: "{{ $vendor->address }}",
        gst_number: "{{ $vendor->gst_number }}",
        mobile_number: "{{ $vendor->mobile_number }}"
    };

    // Highlight changed fields
    $('input, textarea').on('input change', function() {
        if ($(this).val() !== originalValues[this.name]) {
            $(this).addClass('changed-field');
        } else {
            $(this).removeClass('changed-field');
        }
    });

    // Company name duplicate check
    $('#company_name').on('blur', function() {
        const companyName = $(this).val();
        
        if (companyName !== originalValues.company_name && companyName.length > 0) {
            $.ajax({
                url: '{{ route("vendors.check-company") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    company_name: companyName,
                    exclude_id: {{ $vendor->id }}
                },
                success: function(response) {
                    const errorDiv = $('.company-error');
                    if (response.exists) {
                        $('#company_name').addClass('is-invalid');
                        errorDiv.text('This company name is already registered.').show();
                    } else {
                        $('#company_name').removeClass('is-invalid');
                        errorDiv.hide();
                    }
                },
                error: function() {
                    console.error('Error checking company name');
                }
            });
        }
    });

    // GST number duplicate check
    $('#gst_number').on('blur', function() {
        const gstNumber = $(this).val();
        
        if (gstNumber !== originalValues.gst_number && gstNumber.length === 15) {
            $.ajax({
                url: '{{ route("vendors.check-gst") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    gst_number: gstNumber,
                    exclude_id: {{ $vendor->id }}
                },
                success: function(response) {
                    const errorDiv = $('.gst-error');
                    if (response.exists) {
                        $('#gst_number').addClass('is-invalid');
                        errorDiv.text('This GST number is already registered.').show();
                    } else {
                        $('#gst_number').removeClass('is-invalid');
                        errorDiv.hide();
                    }
                },
                error: function() {
                    console.error('Error checking GST number');
                }
            });
        }
    });

    // Mobile number change confirmation
    $('#mobile_number').on('change', function() {
        if ($(this).val() !== originalValues.mobile_number) {
            if (!confirm('Are you sure you want to change the mobile number? This will update the primary contact information.')) {
                $(this).val(originalValues.mobile_number).trigger('change');
            }
        }
    });

    // Form submission handling
    $('#vendorForm').on('submit', function(e) {
        // Check for duplicate errors
        if ($('#company_name').hasClass('is-invalid') || $('#gst_number').hasClass('is-invalid')) {
            e.preventDefault();
            alert('Please fix the validation errors before submitting.');
            return false;
        }

        // Check if any field has actually changed
        let hasChanges = false;
        $('input, textarea').each(function() {
            if ($(this).val() !== originalValues[this.name]) {
                hasChanges = true;
                return false; // break loop
            }
        });

        if (!hasChanges) {
            e.preventDefault();
            alert('No changes detected. Please make changes before submitting.');
            return false;
        }

        return true;
    });
});
</script>
@endpush