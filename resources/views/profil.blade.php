<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile Modal</title>
  <style>
  
    .modal {
      display: none;
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 350px;
      background-color: white;
      border-radius: 8px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
      padding: 20px;
      z-index: 10;
    }

    .modal-overlay {
      display: none; 
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      z-index: 5;
    }

    .modal input {
      width: 100%;
      margin: 8px 0;
      padding: 8px;
    }

    .modal .close-modal-btn {
      cursor: pointer;
      color: red;
      border: none;
      background: none;
      font-size: 16px;
      position: absolute;
      right: 10px;
      top: 10px;
    }

    .profile-photo {
      display: block;
      margin: 10px auto;
      width: 100px;
      height: 100px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid #ddd;
    }

    .upload-btn {
      margin: 8px 0;
      padding: 6px;
      width: 100%;
      font-size: 14px;
      cursor: pointer;
    }
  </style>
</head>
<body>

<div class="modal-overlay" id="modal-overlay" onclick="closeModal()"></div>

<div class="modal" id="profileModal">
  <button class="close-modal-btn" onclick="closeModal()">✖</button>
  <h3>Edit Profile</h3>

  <!-- Profile photo and upload button -->
  <img src="https://via.placeholder.com/100" id="profilePhoto" alt="Profile Photo" class="profile-photo">
  <input type="file" id="photoInput" class="upload-btn" accept="image/*" onchange="previewPhoto()">

  <!-- Profile details form -->
  <form id="profileForm">
    <label>Email:</label>
    <input type="email" id="email" placeholder="Enter email">
    <label>Username:</label>
    <input type="text" id="username" placeholder="Enter username">
    <label>Password:</label>
    <input type="password" id="password" placeholder="Enter password">
    <button type="button" onclick="saveProfile()">Save Changes</button>
  </form>
</div>

<script>
  function openModal() {
    document.getElementById("profileModal").style.display = "block";
    document.getElementById("modal-overlay").style.display = "block";
  }

  function closeModal() {
    document.getElementById("profileModal").style.display = "none";
    document.getElementById("modal-overlay").style.display = "none";
  }

  function saveProfile() {
    alert("Profile updated successfully!");
    closeModal();
  }

  function previewPhoto() {
    const fileInput = document.getElementById("photoInput");
    const profilePhoto = document.getElementById("profilePhoto");

    if (fileInput.files && fileInput.files[0]) {
      const reader = new FileReader();
      reader.onload = function (e) {
        profilePhoto.src = e.target.result;
      };
      reader.readAsDataURL(fileInput.files[0]);
    }
  }
</script>

</body>
</html>