<div class="modal-overlay" id="modal-overlay" onclick="closeModal()"></div>

<div class="modal" id="profileModal">
  <button class="close-modal-btn" onclick="closeModal()">✖</button>
  <h3 id="h">Edit Profile</h3>

  <img src="https://via.placeholder.com/100" id="profilePhoto" alt="Profile Photo" class="profile-photo">
  <input type="file" id="photoInput" class="upload-btn" onchange="previewPhoto()">

  <form id="profileForm">
    <label>Email:</label>
    <input type="email" id="email" placeholder="Enter email">
    <label>Username:</label>
    <input type="text" id="username" placeholder="Enter username">
    <label>Password:</label>
    <input type="password" id="password" placeholder="Enter password">
    <button type="button" onclick="saveProfile()" id="save">Save Changes</button>
  </form>
</div>

<script src="<?php echo'js/script.js';?>">
</script>