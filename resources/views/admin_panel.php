<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="modal-overlay" id="modal-overlay" onclick="closeModal()"></div>

<div class="modal" id="profileModal">
  <button class="close-modal-btn" onclick="closeModal()">✖</button>
  <h3 id="h">Edit Profile</h3>

  <div class="profile-photo-container">
    <img src="https://via.placeholder.com/100" id="profilePhoto" alt="Profile Photo" class="profile-photo" onclick="document.getElementById('photoInput').click()">
    <div class="camera-icon" onclick="document.getElementById('photoInput').click()">
      <i class="fas fa-camera"></i>
    </div>
    <input type="file" id="photoInput" accept="image/*" style="display: none" onchange="previewPhoto()">
  </div>

  <form id="profileForm">
    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
    <label>Email:</label>
    <input type="email" id="email" placeholder="Enter email">
    
    <div class="password-container">
      <label>Password:</label>
      <div class="password-input-wrapper">
        <input type="password" id="password" placeholder="Enter password">
        <i class="fas fa-eye-slash toggle-password" onclick="togglePassword()"></i>
      </div>
    </div>
    
    <button type="button" onclick="saveProfile()" id="save">Save Changes</button>
  </form>
</div>

<style>
.modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1000;
}

.modal {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: white;
    padding: 20px;
    border-radius: 8px;
    z-index: 1001;
    max-width: 500px;
    width: 90%;
}

.profile-photo-container {
  position: relative;
  width: 100px;
  height: 100px;
  margin: 0 auto;
  cursor: pointer;
}

.profile-photo {
  width: 100%;
  height: 100%;
  border-radius: 50%;
  object-fit: cover;
}

.camera-icon {
  position: absolute;
  bottom: 0;
  right: 0;
  background: #007bff;
  width: 32px;
  height: 32px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  cursor: pointer;
  border: 2px solid white;
}

.camera-icon:hover {
  background: #0056b3;
}

.password-container {
  position: relative;
  width: 100%;
}

.password-input-wrapper {
  position: relative;
  display: flex;
  align-items: center;
}

.password-input-wrapper input {
  width: 100%;
}

.toggle-password {
  position: absolute;
  right: 10px;
  cursor: pointer;
  color: #666;
}

.toggle-password:hover {
  color: #333;
}
</style>

<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.querySelector('.toggle-password');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    }
}

function previewPhoto() {
    const input = document.getElementById('photoInput');
    const image = document.getElementById('profilePhoto');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            image.src = e.target.result;
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

function loadProfileData() {
    fetch('/api/profile', {
        method: 'GET',
        credentials: 'same-origin'
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        document.getElementById('email').value = data.email;
        if (data.profile_image) {
            document.getElementById('profilePhoto').src = data.profile_image;
        }
    })
    .catch(error => {
        console.error('Error loading profile:', error);
        alert('Error loading profile data. Please try again.');
    });
}

function saveProfile() {
    const formData = new FormData();
    const photoInput = document.getElementById('photoInput');
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    if (photoInput.files.length > 0) {
        formData.append('profile_image', photoInput.files[0]);
    }
    
    if (email) formData.append('email', email);
    if (password) formData.append('password', password);
    
    formData.append('_token', document.querySelector('input[name="_token"]').value);

    fetch('/api/profile/update', {
        method: 'POST',
        body: formData,
        credentials: 'same-origin'
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            if (data.profile_image) {
                document.getElementById('profilePhoto').src = data.profile_image;
            }
            alert('Profile updated successfully');
            closeModal();
        } else {
            alert(data.message || 'Error updating profile');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating profile. Please try again.');
    });
}

document.getElementById('profileModal').addEventListener('show', loadProfileData);

function openModal() {
    document.getElementById('modal-overlay').style.display = 'block';
    document.getElementById('profileModal').style.display = 'block';
    loadProfileData();
}

function closeModal() {
    document.getElementById('modal-overlay').style.display = 'none';
    document.getElementById('profileModal').style.display = 'none';
}
</script>

<!-- Add Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">