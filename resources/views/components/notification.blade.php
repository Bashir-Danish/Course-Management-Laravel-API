<div id="notification-container"></div>

<style>
#notification-container { 
    position: fixed;
    top: 85px;
    right: 20px;
    z-index: 9999;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.notification { 
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 20px;
    border-radius: 4px;
    min-width: 300px;
    background: white;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    animation: slideIn 0.3s ease-out;
    margin-bottom: 10px;
}

.notification-success { 
    background-color: #d4edda;
    border-left: 4px solid #28a745;
    color: #155724;
}

.notification-danger { 
    background-color: #f8d7da;
    border-left: 4px solid #dc3545;
    color: #721c24;
}

.notification-close { 
    background: none;
    border: none;
    color: inherit;
    font-size: 20px;
    cursor: pointer;
    padding: 0 0 0 10px;
    opacity: 0.5;
}

.notification-close:hover { 
    opacity: 1;
}

@keyframes slideIn { 
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}
</style> 

<script>
function showNotification(message, type = 'success') { 
    const container = document.getElementById('notification-container');
    
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    
    const messageText = document.createElement('span');
    messageText.textContent = message;
    
    const closeButton = document.createElement('button');
    closeButton.className = 'notification-close';
    closeButton.innerHTML = '&times;';
    closeButton.onclick = () => notification.remove();
    
    notification.appendChild(messageText);
    notification.appendChild(closeButton);
    container.appendChild(notification);
    
    setTimeout(() => {
        if (notification.parentElement) {
            notification.remove();
        }
    }, 3000);
}
</script> 