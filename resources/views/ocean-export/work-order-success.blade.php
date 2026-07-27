<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Work Order Saved</title>
    <style>
        body {
            font-family: 'Inter', Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .success-container {
            text-align: center;
            padding: 40px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        .checkmark {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: block;
            stroke-width: 3;
            stroke: #fff;
            stroke-miterlimit: 10;
            margin: 0 auto 20px;
            box-shadow: inset 0px 0px 0px #7ac142;
            animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both;
        }
        .checkmark__circle {
            stroke-dasharray: 166;
            stroke-dashoffset: 166;
            stroke-width: 3;
            stroke-miterlimit: 10;
            stroke: #fff;
            fill: none;
            animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
        }
        .checkmark__check {
            transform-origin: 50% 50%;
            stroke-dasharray: 48;
            stroke-dashoffset: 48;
            animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
        }
        @keyframes stroke {
            100% {
                stroke-dashoffset: 0;
            }
        }
        @keyframes scale {
            0%, 100% {
                transform: none;
            }
            50% {
                transform: scale3d(1.1, 1.1, 1);
            }
        }
        @keyframes fill {
            100% {
                box-shadow: inset 0px 0px 0px 30px #7ac142;
            }
        }
        h1 {
            font-size: 28px;
            margin: 0 0 10px;
            font-weight: 700;
        }
        p {
            font-size: 16px;
            opacity: 0.9;
            margin: 0 0 20px;
        }
        .spinner {
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top: 3px solid white;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="success-container">
        <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
            <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/>
            <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
        </svg>
        
        <h1>Work Order Saved!</h1>
        <p>Returning to shipment page...</p>
        
        <div class="spinner"></div>
    </div>

    <script>
        // Get source information
        const source = '{{ $source }}';
        const sourceId = '{{ $sourceId }}';
        const workOrderId = '{{ $workOrder->id }}';
        
        console.log('Work Order Saved:', { source, sourceId, workOrderId });
        
        // Function to notify parent and close
        function notifyParentAndClose() {
            try {
                // Check if we have a parent window (opener)
                if (window.opener && !window.opener.closed) {
                    console.log('Parent window found, switching tab and refreshing...');
                    
                    // Try to communicate with parent window
                    if (window.opener.Alpine && window.opener.Alpine.raw) {
                        // Get Alpine data from parent
                        const parentData = window.opener.Alpine.raw(
                            window.opener.Alpine.$data(window.opener.document.querySelector('[x-data]'))
                        );
                        
                        if (parentData) {
                            console.log('Alpine found in parent, updating...');
                            
                            // Switch to work order tab
                            parentData.activeTab = 'workorder';
                            
                            // Refresh work orders list
                            if (typeof parentData.fetchWorkOrders === 'function') {
                                parentData.fetchWorkOrders();
                            }
                            
                            // Show success toast in parent window using global showToast function
                            // The global showToast signature is: showToast(type, message)
                            if (window.opener.showToast && typeof window.opener.showToast === 'function') {
                                window.opener.showToast('success', 'Work order saved successfully!');
                            }
                            
                            console.log('Parent updated successfully');
                        }
                    }
                    
                    // Close this window after a short delay
                    setTimeout(() => {
                        window.close();
                        
                        // If window.close() doesn't work (some browsers block it)
                        setTimeout(() => {
                            if (!window.closed) {
                                document.body.innerHTML = '<div style="text-align:center;padding:50px;font-family:Arial;"><h1>✓ Saved!</h1><p>You can close this window now.</p><button onclick="window.close()" style="padding:10px 20px;font-size:16px;cursor:pointer;">Close Window</button></div>';
                            }
                        }, 500);
                    }, 1500);
                    
                } else {
                    console.log('No parent window, redirecting...');
                    // No parent window, redirect to source page
                    let redirectUrl = '';
                    
                    if (source === 'air_export') {
                        redirectUrl = `/air-export/${sourceId}/edit`;
                    } else if (source === 'air_import') {
                        redirectUrl = `/air-import/${sourceId}/edit`;
                    } else if (source === 'ocean_export') {
                        redirectUrl = `/ocean-export/${sourceId}/edit`;
                    } else if (source === 'ocean_import') {
                        redirectUrl = `/ocean-import/${sourceId}/edit`;
                    } else {
                        redirectUrl = `/ocean-export/work-order/${workOrderId}/edit`;
                    }
                    
                    setTimeout(() => {
                        window.location.href = redirectUrl;
                    }, 1500);
                }
            } catch (error) {
                console.error('Error notifying parent:', error);
                // Fallback: try to close or redirect
                setTimeout(() => {
                    window.close();
                    setTimeout(() => {
                        if (!window.closed) {
                            document.body.innerHTML = '<div style="text-align:center;padding:50px;font-family:Arial;"><h1>✓ Saved!</h1><p>You can close this window now.</p></div>';
                        }
                    }, 500);
                }, 1500);
            }
        }
        
        // Execute after animation completes
        setTimeout(notifyParentAndClose, 1000);
    </script>
</body>
</html>
