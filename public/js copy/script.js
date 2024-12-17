// ======================================== Show Slides ==================================================
let slideIndex = 0;
showSlides();

function showSlides() {
  let i;
  let slides = document.getElementsByClassName("mySlides");
  let dots = document.getElementsByClassName("dot");
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }
  slideIndex++;
  if (slideIndex > slides.length) { slideIndex = 1 }
  for (i = 0; i < dots.length; i++) {
    dots[i].className = dots[i].className.replace(" active2", "");
  }
  slides[slideIndex - 1].style.display = "block";
  dots[slideIndex - 1].className += " active2";
  setTimeout(showSlides, 7000); // Change image every 7 seconds
}

// ======================================= Admin Panel =====================================================

function openModal() {
    document.getElementById("profileModal").style.display = "block";
    document.getElementById("modal-overlay").style.display = "block";
  }

  function closeModal() {
    document.getElementById("profileModal").style.display = "none";
    document.getElementById("modal-overlay").style.display = "none";
  }

  function saveProfile() {
    // Placeholder for saving changes, e.g., send data to the server
    alert("Changes updated successfully!");
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