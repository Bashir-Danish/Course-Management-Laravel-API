<!DOCTYPE html>
<html class="no-js" lang="en">
  <head>
     @include('head')
     <style>
        .backup-container {
            min-height: 100vh;
            padding: 40px 0;
        }
        .backup-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
            max-width: 450px;
            margin: auto;
        }
        .backup-header {
            background: #ff6347;
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 20px;
            text-align: center;
        }
        .backup-body {
            padding: 30px;
        }
        .backup-input {
            border: 2px solid #e3e3e3;
            border-radius: 10px;
            padding: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        .backup-input:focus {
            border-color: #ff6347;
            box-shadow: 0 0 0 0.2rem rgba(255, 99, 71, 0.25);
        }
        .backup-btn {
            background: #ff6347;
            border: none;
            border-radius: 25px;
            padding: 12px 40px;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .backup-btn:hover {
            background: #ff6347;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .loading {
            display: none;
            position: relative;
        }
        
        .loading:after {
            content: '';
            width: 20px;
            height: 20px;
            border: 2px solid #ffffff;
            border-top: 2px solid transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
        }
        
        @keyframes spin {
            0% { transform: translateY(-50%) rotate(0deg); }
            100% { transform: translateY(-50%) rotate(360deg); }
        }
     </style>
  </head>

  <body>
    @include('sidebar')

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
  
      @include('header')
      @include('Mobile_menu')
          
      <div class="backup-container d-flex align-items-center">
          <div class="container">
              <div class="backup-card">
                  <div class="backup-header">
                      <h3 class="m-0">Database Backup</h3>
                  </div>

                  <div class="backup-body">
                      <div id="alert-container"></div>
                      
                      <form id="backupForm" action="{{ route('backup.create') }}" method="POST">
                          @csrf
                          <div class="form-group mb-4">
                              <label class="form-label fw-bold mb-2">Backup Name</label>
                              <input type="text" 
                                     class="form-control backup-input" 
                                     id="backup_name" 
                                     name="backup_name" 
                                     required 
                                     value="backup_{{ date('Y-m-d') }}"
                                     placeholder="Enter backup name">
                          </div>
                          
                          <div class="text-center mt-4">
                              <button type="submit" class="btn btn-primary backup-btn" id="backupBtn">
                                  <span class="btn-text"><i class="fas fa-download me-2"></i>Generate Backup</span>
                                  <span class="loading"></span>
                              </button>
                          </div>
                      </form>
                  </div>
              </div>
          </div>
      </div>
        
    </div>

    @include('Admin_panel')
    @include('Reports')
    @include('Footer')

    <script>
    $(document).ready(function() {
        $('#backupForm').on('submit', function(e) {
            e.preventDefault();
            
            const form = $(this);
            const button = $('#backupBtn');
            const alertContainer = $('#alert-container');
            
            alertContainer.empty();
            
            button.prop('disabled', true);
            button.addClass('loading');
            
            const formData = new FormData(this);
        
            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response, status, xhr) {
                    const blob = new Blob([response], { type: 'application/sql' });
                    const url = window.URL.createObjectURL(blob);
                    
                    const link = document.createElement('a');
                    const filename = xhr.getResponseHeader('Content-Disposition')?.split('filename=')[1]?.replace(/['"]/g, '') || 'backup.sql';
                    
                    link.href = url;
                    link.download = filename;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    
                    window.URL.revokeObjectURL(url);
                    
                    showAlert('success', 'Backup generated successfully!');
                },
                error: function(xhr) {
                    let errorMessage = 'An error occurred while generating the backup.';
                    
                    try {
                        const response = JSON.parse(xhr.responseText);
                        errorMessage = response.error || errorMessage;
                    } catch (e) {}
                    
                    showAlert('danger', errorMessage);
                },
                complete: function() {
                    button.prop('disabled', false);
                    button.removeClass('loading');
                }
            });
        });
        
        function showAlert(type, message) {
            const alertHtml = `
                <div class="alert alert-${type} alert-dismissible fade show text-center" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
            $('#alert-container').html(alertHtml);
        }
    });
    </script>
  </body>
</html>
