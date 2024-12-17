@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="logo-pro">
                <a href="index.html"><h2 id="head"><span id="title-1">Nano</span><span id="title-2">Net</span></h2></a>
            </div>
        </div>
    </div>
</div>

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

<!-- Slideshow -->
<div class="slideshow-container">
    <div class="mySlides fade">
        <div class="numbertext">1 / 11</div>
        <img src="img/22.jpg" alt="" style="height:65vh; width:100%; object-fit:contain"/>
        <div class="text1"></div>
    </div>

    <div class="mySlides fade">
        <div class="numbertext">2 / 11</div>
        <img src="img/33.jpg" alt="" style="height:65vh; width:100%; object-fit:contain"/>
        <div class="text1"></div>
    </div>

    <div class="mySlides fade">
        <div class="numbertext">3 / 11</div>
        <img src="img/44.jpg" alt="" style="height:65vh; width:100%; object-fit:contain"/>
        <div class="text1"></div>
    </div>

    <div class="mySlides fade">
        <div class="numbertext">4 / 11</div>
        <img src="img/55.jpg" alt="" style="height:65vh; width:100%; object-fit:contain"/>
        <div class="text1"></div>
    </div>

    <div class="mySlides fade">
        <div class="numbertext">5 / 11</div>
        <img src="img/66.jpg" alt="" style="height:65vh; width:100%; object-fit:contain"/>
        <div class="text1"></div>
    </div>

    <div class="mySlides fade">
        <div class="numbertext">6 / 11</div>
        <img src="img/77.jpg" alt="" style="height:65vh; width:100%; object-fit:contain"/>
        <div class="text1"></div>
    </div>

    <div class="mySlides fade">
        <div class="numbertext">7 / 11</div>
        <img src="img/88.jpg" alt="" style="height:65vh; width:100%; object-fit:contain"/>
        <div class="text1"></div>
    </div>

    <div class="mySlides fade">
        <div class="numbertext">8 / 11</div>
        <img src="img/99.jpg" alt="" style="height:65vh; width:100%; object-fit:contain"/>
        <div class="text1"></div>
    </div>

    <div class="mySlides fade">
        <div class="numbertext">9 / 11</div>
        <img src="img/10.jpg" alt="" style="height:65vh; width:100%; object-fit:contain"/>
        <div class="text1"></div>
    </div>

    <div class="mySlides fade">
        <div class="numbertext">10 / 11</div>
        <img src="img/12.jpg" alt="" style="height:65vh; width:100%; object-fit:contain"/>
        <div class="text1"></div>
    </div>

    <div class="mySlides fade">
        <div class="numbertext">11/ 11</div>
        <img src="img/13.jpg" alt="" style="height:65vh; width:100%; object-fit:contain"/>
        <div class="text1"></div>
    </div>
</div>

<div style="text-align:center">
    <span class="dot"></span>
    <span class="dot"></span>
    <span class="dot"></span>
    <span class="dot"></span>
    <span class="dot"></span>
    <span class="dot"></span>
    <span class="dot"></span>
    <span class="dot"></span>
    <span class="dot"></span>
    <span class="dot"></span>
    <span class="dot"></span>
</div>
@endsection
