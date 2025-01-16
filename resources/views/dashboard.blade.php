<!DOCTYPE html>
<html class="no-js" lang="en">
  <head>
     <!--================================================= Head Part ==================================================== -->
     @include('head')
     <!--================================================================================================================ -->
     
  </head>
  <body>
    <!-- ================================================ Left Sidebar ================================================================ -->
      @include('sidebar')
    <!-- ===============================================================================================================================-->

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
        <!-- header section --->
        @include('header')
        <!-- Mobile Menu start -->
        @include('Mobile_menu')

<!-- Dashboard Content --> 
<div class="dash-content">
    <div class="overview">
        <div class="boxes">
            <div class="box box1">
                <i class="uil uil-thumbs-up"></i>
                <span class="text">Total Students</span>
                <span class="number">{{ $totalStudents }}</span>
            </div>
            <div class="box box2">
                <i class="uil uil-comments"></i>
                <span class="text">Total Teachers</span>
                <span class="number">{{ $totalTeachers }}</span>
            </div>
            <div class="box box3">
                <i class="uil uil-share"></i>
                <span class="text">Total Courses</span>
                <span class="number">{{ $totalCourses }}</span>
            </div>
            <div class="box box4">
                <i class="uil uil-share"></i>
                <span class="text">Total Departments</span>
                <span class="number">{{ $totalDepartments }}</span>
            </div>
        </div>
    </div>
</div>


<div class="slideshow-container">
        <!-- Slides -->
        <div class="slide active">
          <img src="img/10.jpg" alt="Slide 1">
        </div>
        <div class="slide">
          <img src="img/22.jpg" alt="Slide 2">
        </div>
        <div class="slide">
          <img src="img/33.jpg" alt="Slide 3">
        </div>
        <div class="slide">
          <img src="img/44.jpg" alt="Slide 4">
        </div>
        <div class="slide">
          <img src="img/55.jpg" alt="Slide 5">
        </div>
        <div class="slide">
          <img src="img/66.jpg" alt="Slide 6">
        </div>
        <div class="slide">
          <img src="img/77.jpg" alt="Slide 7">
        </div>
        <div class="slide">
          <img src="img/77.jpg" alt="Slide 8">
        </div>
        <div class="slide">
          <img src="img/88.jpg" alt="Slide 9">
        </div>
      
      </div>
      
      <!-- Dots (Indicators) -->
      <div class="dots">
        <span class="dot" onclick="currentSlide(1)"></span>
        <span class="dot" onclick="currentSlide(2)"></span>
        <span class="dot" onclick="currentSlide(3)"></span>
        <span class="dot" onclick="currentSlide(4)"></span>
        <span class="dot" onclick="currentSlide(5)"></span>
        <span class="dot" onclick="currentSlide(6)"></span>
        <span class="dot" onclick="currentSlide(7)"></span>
        <span class="dot" onclick="currentSlide(8)"></span>
        <span class="dot" onclick="currentSlide(9)"></span>
      </div>
      
      <script>
        let slideIndex = 0;
        const slides = document.querySelectorAll(".slide");
        const dots = document.querySelectorAll(".dot");
      
        function showSlides() {
          slides.forEach((slide, index) => {
            slide.classList.remove("active");
          });
          dots.forEach(dot => dot.classList.remove("active"));
      
          slideIndex++;
          if (slideIndex > slides.length) { slideIndex = 1; }
      
          slides[slideIndex - 1].classList.add("active");
          dots[slideIndex - 1].classList.add("active");
      
          setTimeout(showSlides, 5000); // Change slide every 3 seconds
        }
      
      
        function changeSlide(n) {
          slideIndex += n;
          if (slideIndex < 1) { slideIndex = slides.length; }
          if (slideIndex > slides.length) { slideIndex = 1; }
          updateSlide();
        }
      
        function currentSlide(n) {
          slideIndex = n;
          updateSlide();
        }
      
        function updateSlide() {
          slides.forEach((slide, index) => {
            slide.classList.remove("active");
          });
          dots.forEach(dot => dot.classList.remove("active"));
          slides[slideIndex - 1].classList.add("active");
          dots[slideIndex - 1].classList.add("active");
        }
      
        // Initialize auto-play
        showSlides();
      </script>

<!-- ================================================= Admin Panel ==================================================== -->
    @include('Admin_panel')
    <!-- ================================================================================================================== -->
    @include('Reports')
    <!-- ===================================================== Footer =============================================================== -->
     @include('Footer')
    <!--========================================================================================================================== -->
</body>
</html>
