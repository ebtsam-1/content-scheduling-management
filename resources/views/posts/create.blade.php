<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Post Editor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .editor-container {
            max-width: 900px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        .card {
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
        }
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px 12px 0 0 !important;
            padding: 1.5rem;
        }
        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
        }
        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            padding: 0.75rem;
            transition: all 0.3s ease;
        }
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        #content {
            min-height: 200px;
            resize: vertical;
        }
        .character-counter {
            font-size: 0.875rem;
            color: #6c757d;
            text-align: right;
            margin-top: 0.25rem;
        }
        .character-counter.warning {
            color: #ffc107;
        }
        .character-counter.danger {
            color: #dc3545;
        }
        .platform-selector {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 0.75rem;
            margin-top: 0.5rem;
        }
        .platform-checkbox {
            display: flex;
            align-items: center;
            padding: 0.75rem;
            background: #f8f9fa;
            border-radius: 8px;
            border: 2px solid transparent;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .platform-checkbox:hover {
            background: #e9ecef;
        }
        .platform-checkbox.selected {
            background: #e7f3ff;
            border-color: #667eea;
        }
        .platform-checkbox input[type="checkbox"] {
            margin-right: 0.5rem;
            transform: scale(1.2);
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 8px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(102, 126, 234, 0.3);
        }
        .btn-success {
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(40, 167, 69, 0.3);
        }
        .btn-warning {
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            color: #000;
        }
        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(255, 193, 7, 0.3);
            color: #000;
        }
        .btn-outline-primary {
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-outline-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(13, 110, 253, 0.3);
        }
        .btn-outline-secondary {
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .preview-image {
            max-width: 100%;
            max-height: 300px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .image-info {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            border-left: 4px solid #667eea;
            margin-top: 1rem;
        }
        .loading {
            display: none;
        }
        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
        }
        @media (max-width: 768px) {
            .editor-container {
                margin: 1rem auto;
                padding: 0 0.5rem;
            }
            .platform-selector {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="editor-container">
        <div class="card">
            <div class="card-header">
                <h2 class="mb-0">
                    <i class="fas fa-edit me-2"></i>
                    Post Editor
                </h2>
            </div>
            <div class="card-body p-4">
                <form id="postForm" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Title Field -->
                    <div class="mb-4">
                        <label for="title" class="form-label">
                            <i class="fas fa-heading me-1"></i>
                            Post Title
                        </label>
                        <input type="text" class="form-control" id="title" name="title" required>
                        <div class="character-counter" id="titleCounter">0 characters</div>
                    </div>

                    <!-- Content Field -->
                    <div class="mb-4">
                        <label for="content" class="form-label">
                            <i class="fas fa-align-left me-1"></i>
                            Content
                        </label>
                        <textarea class="form-control" id="content" name="content" rows="8" required></textarea>
                        <div class="character-counter" id="contentCounter">0 characters</div>
                    </div>

                    <!-- Image Upload -->
                    <div class="mb-4">
                        <label class="form-label">
                            <i class="fas fa-image me-1"></i>
                            Featured Image
                        </label>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="file" class="form-control" id="image" name="image" accept="image/png,image/jpg,image/jpeg,image/gif">
                                <small class="form-text text-muted">PNG, JPG, GIF up to 10MB</small>
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="btn btn-outline-primary w-100" id="browseBtn">
                                    <i class="fas fa-folder-open me-2"></i>
                                    Browse Files
                                </button>
                            </div>
                        </div>
                        <div id="imagePreview" class="mt-3"></div>
                    </div>

                    <!-- Platform Selector -->
                    <div class="mb-4">
                        <label class="form-label">
                            <i class="fas fa-share-alt me-1"></i>
                            Select Platforms
                        </label>
                        <div class="platform-selector" id="platformSelector">
                            @if(isset($platforms) && count($platforms) > 0)
                                @foreach($platforms as $platform)
                                    <div class="platform-checkbox" data-platform-id="{{ $platform['id'] }}">
                                        <input type="checkbox" name="platforms[]" value="{{ $platform['id'] }}" id="platform-{{ $platform['id'] }}">
                                        <label for="platform-{{ $platform['id'] }}" class="mb-0">
                                            @if(isset($platform['icon']))
                                                <i class="{{ $platform['icon'] }} me-2"></i>
                                            @endif
                                            {{ $platform['name'] }}
                                        </label>
                                    </div>
                                @endforeach
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    No platforms available. Please add platforms first.
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Date/Time Picker -->
                    <div class="mb-4">
                        <label for="scheduled_time" class="form-label">
                            <i class="fas fa-calendar-alt me-1"></i>
                            Schedule Date & Time
                        </label>
                        <input type="datetime-local" class="form-control" id="scheduled_time" name="scheduled_time">
                        <small class="form-text text-muted">Required when scheduling posts for later</small>
                    </div>

                    <!-- Hidden Status Field -->
                    <input type="hidden" id="status" name="status" value="">

                    <!-- Action Buttons -->
                    <div class="row g-3">
                        <div class="col-md-4">
                            <button type="button" class="btn btn-outline-secondary w-100" onclick="resetForm()">
                                <i class="fas fa-undo me-1"></i>
                                Reset Form
                            </button>
                        </div>
                        <div class="col-md-8">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-outline-primary flex-fill" data-status="draft">
                                    <span class="loading">
                                        <span class="spinner-border spinner-border-sm me-2"></span>
                                    </span>
                                    <i class="fas fa-file-alt me-1"></i>
                                    Save as Draft
                                </button>
                                <button type="submit" class="btn btn-success flex-fill" data-status="published">
                                    <span class="loading">
                                        <span class="spinner-border spinner-border-sm me-2"></span>
                                    </span>
                                    <i class="fas fa-rocket me-1"></i>
                                    Publish Now
                                </button>
                                <button type="submit" class="btn btn-warning flex-fill" data-status="scheduled">
                                    <span class="loading">
                                        <span class="spinner-border spinner-border-sm me-2"></span>
                                    </span>
                                    <i class="fas fa-clock me-1"></i>
                                    Schedule Later
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050">
        <div id="alertContainer"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Character counters
            function updateCharacterCounter(input, counter, maxLength = null) {
                const length = input.val().length;
                const counterElement = $(counter);
                counterElement.text(length + ' characters');
                
                if (maxLength) {
                    if (length > maxLength * 0.9) {
                        counterElement.addClass('warning').removeClass('danger');
                    }
                    if (length > maxLength) {
                        counterElement.addClass('danger').removeClass('warning');
                    }
                    if (length <= maxLength * 0.9) {
                        counterElement.removeClass('warning danger');
                    }
                }
            }

            $('#title').on('input', function() {
                updateCharacterCounter($(this), '#titleCounter', 100);
            });

            $('#content').on('input', function() {
                updateCharacterCounter($(this), '#contentCounter', 5000);
            });

            // Platform selector
            $('.platform-checkbox').on('click', function(e) {
                if (e.target.type !== 'checkbox') {
                    const checkbox = $(this).find('input[type="checkbox"]');
                    checkbox.prop('checked', !checkbox.prop('checked'));
                }
                
                if ($(this).find('input[type="checkbox"]').prop('checked')) {
                    $(this).addClass('selected');
                } else {
                    $(this).removeClass('selected');
                }
            });

            // Image upload - Simple file input approach
            $('#browseBtn').on('click', function() {
                $('#image').click();
            });

            $('#image').on('change', function() {
                handleImageUpload(this);
            });

            function handleImageUpload(input) {
                const file = input.files[0];
                
                if (!file) {
                    $('#imagePreview').empty();
                    return;
                }

                // Validate file type
                const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                if (!validTypes.includes(file.type)) {
                    showAlert('danger', 'Please select a valid image file (PNG, JPG, GIF)');
                    input.value = '';
                    return;
                }
                
                // Validate file size (10MB)
                if (file.size > 10 * 1024 * 1024) {
                    showAlert('danger', 'File size must be less than 10MB');
                    input.value = '';
                    return;
                }
                
                // Create preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = `
                        <div class="image-info">
                            <div class="row">
                                <div class="col-md-4">
                                    <img src="${e.target.result}" class="preview-image" alt="Preview">
                                </div>
                                <div class="col-md-8">
                                    <h6 class="mb-2">
                                        <i class="fas fa-file-image me-2"></i>
                                        Selected Image
                                    </h6>
                                    <p class="mb-1"><strong>Name:</strong> ${file.name}</p>
                                    <p class="mb-1"><strong>Size:</strong> ${(file.size / 1024 / 1024).toFixed(2)} MB</p>
                                    <p class="mb-3"><strong>Type:</strong> ${file.type}</p>
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeImage()">
                                        <i class="fas fa-trash me-1"></i> Remove Image
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                    $('#imagePreview').html(preview);
                    showAlert('success', 'Image selected successfully!');
                };
                
                reader.onerror = function() {
                    showAlert('danger', 'Error reading the selected file');
                    input.value = '';
                };
                
                reader.readAsDataURL(file);
            }

            // Status change handler (removed since status dropdown is removed)
            // Date validation for scheduled posts
            function validateScheduledDate() {
                const scheduledAt = $('#scheduled_time').val();
                if (!scheduledAt) {
                    return false;
                }
                
                const scheduledDate = new Date(scheduledAt);
                const now = new Date();
                
                if (scheduledDate <= now) {
                    showAlert('danger', 'Scheduled date must be in the future');
                    return false;
                }
                
                return true;
            }

            // Form submission
            $('#postForm').on('submit', function(e) {
                e.preventDefault();
                
                // Get the clicked button's status
                const clickedButton = $(document.activeElement);
                const status = clickedButton.data('status');
                
                // Set the status in hidden field
                $('#status').val(status);
                
                // Validate scheduled date if status is 'scheduled'
                if (status === 'scheduled' && !validateScheduledDate()) {
                    return;
                }
                
                // Clear scheduled_time if not scheduling
                if (status !== 'scheduled') {
                    $('#scheduled_time').val('');
                }
                
                const formData = new FormData(this);
                const submitButtons = $('button[type="submit"]');
                const loadingElements = submitButtons.find('.loading');
                const iconElements = submitButtons.find('.fas');
                
                // Show loading state on clicked button
                clickedButton.find('.loading').show();
                clickedButton.find('.fas').hide();
                submitButtons.prop('disabled', true);

                // Format datetime for Laravel (Y-m-d H:i:s)
                const scheduledAt = $('#scheduled_time').val();
                if (scheduledAt && status === 'scheduled') {
                    const formattedDate = new Date(scheduledAt).toISOString().slice(0, 19).replace('T', ' ');
                    formData.set('scheduled_time', formattedDate);
                }

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: '{{ route("posts.store") }}', // Adjust route as needed
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        let message = 'Post saved successfully!';
                        if (status === 'draft') {
                            message = 'Post saved as draft!';
                        } else if (status === 'published') {
                            message = 'Post published successfully!';
                        } else if (status === 'scheduled') {
                            message = 'Post scheduled successfully!';
                        }
                        
                        showAlert('success', message);
                        
                        if (response.redirect) {
                            setTimeout(() => {
                                window.location.href = response.redirect;
                            }, 1500);
                        }
                    },
                    error: function(xhr) {
                        let message = 'An error occurred while saving the post.';
                        
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            const errors = xhr.responseJSON.errors;
                            message = Object.values(errors).flat().join('<br>');
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        
                        showAlert('danger', message);
                    },
                    complete: function() {
                        // Hide loading state
                        loadingElements.hide();
                        iconElements.show();
                        submitButtons.prop('disabled', false);
                    }
                });
            });
        });

        function removeImage() {
            $('#image').val('');
            $('#imagePreview').empty();
            showAlert('info', 'Image removed successfully');
        }

        function resetForm() {
            $('#postForm')[0].reset();
            $('#imagePreview').empty();
            $('.platform-checkbox').removeClass('selected');
            $('.platform-checkbox input[type="checkbox"]').prop('checked', false);
            $('#titleCounter, #contentCounter').text('0 characters').removeClass('warning danger');
        }

        function showAlert(type, message) {
            const alertHtml = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            
            $('#alertContainer').append(alertHtml);
            
            // Auto-dismiss after 5 seconds
            setTimeout(() => {
                $('#alertContainer .alert').first().alert('close');
            }, 5000);
        }
    </script>
</body>
</html>