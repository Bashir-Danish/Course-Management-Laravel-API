<div class="left-sidebar-pro">
      <nav id="sidebar" class="">
        <div class="sidebar-header">
          <a href="#"><h2 id="head"><span id="title-1">Nano</span><span id="title-2">Net</span></h2></a>
          <strong><a href="{{ route('dashboard') }}"><img src="img/logo/logosn.png" alt="" /></a></strong>
        </div>
        <div class="left-custom-menu-adp-wrap comment-scrollbar">
            <br><hr><br>
          <nav class="sidebar-nav left-sidebar-menu-pro">
            <ul class="metismenu" id="menu1">
              <li class="active" title="Dashboard">
                <a class="" href="{{ route('dashboard') }}">
                  <span class="educate-icon educate-home icon-wrap"></span>
                  <span class="mini-click-non">Dashboard</span>
                </a>
              </li>

              <li>
                <a title="Teacher" class="has-arrow" href="#" aria-expanded="false">
                  <span class="educate-icon educate-professor icon-wrap"></span>
                  <span class="mini-click-non">Teacher</span>
                </a>
                <ul class="submenu-angle" aria-expanded="false">
                  <li>
                    <a title="Add Teacher" href="{{ route('teachers.create') }}">
                      <span class="mini-sub-pro">Add New Teacher</span>
                    </a>
                  </li>
                  <li>
                    <a title="All Teachers" href="{{ route('teachers.index') }}">
                      <span class="mini-sub-pro">List Of Teachers</span>
                    </a>
                  </li>
                </ul>
              </li>

              <li>
                <a title="Student" class="has-arrow" href="#" aria-expanded="false">
                  <span class="educate-icon educate-student icon-wrap"></span>
                  <span class="mini-click-non">Student</span>
                </a>
                <ul class="submenu-angle" aria-expanded="false">
                  <li>
                    <a title="Add Student" href="{{ route('students.create') }}">
                      <span class="mini-sub-pro">Add New Student</span>
                    </a>
                  </li>
                  <li>
                    <a title="All Students" href="{{ route('students.index') }}">
                      <span class="mini-sub-pro">List Of Students</span>
                    </a>
                  </li>
                </ul>
              </li>

              <!-- <li>
                <a class="" title="M-Students" href="{{ route('students.manage') }}" aria-expanded="false">
                  <span class="educate-icon educate-student icon-wrap"></span>
                  <span class="mini-click-non">M-Students</span>
                </a>
              </li> -->

              <li>
                <a class="has-arrow" title="Course" href="#" aria-expanded="false">
                  <span class="educate-icon educate-course icon-wrap"></span>
                  <span class="mini-click-non">Course</span>
                </a>
                <ul class="submenu-angle" aria-expanded="false">
                  <li>
                    <a title="Add Course" href="{{ route('courses.create') }}">
                      <span class="mini-sub-pro">Add New Courses</span>
                    </a>
                  </li>
                  <li>
                    <a title="All Courses" href="{{ route('courses.index') }}">
                      <span class="mini-sub-pro">List Of Courses</span>
                    </a>
                  </li>
                </ul>
              </li>

              <li>
                <a class="has-arrow" title="Department" href="#" aria-expanded="false">
                  <span class="educate-icon educate-department icon-wrap"></span>
                  <span class="mini-click-non">Department</span>
                </a>
                <ul class="submenu-angle" aria-expanded="false">
                  <li>
                    <a title="Add Department" href="{{ route('departments.create') }}" id="sp1">
                      <span class="mini-sub-pro">Add New Department</span>
                    </a>
                  </li>
                  <li>
                    <a title="All Departments" href="{{ route('departments.index') }}" id="sp1">
                      <span class="mini-sub-pro">List Of Departments</span>
                    </a>
                  </li>
                </ul>
              </li>

              <!-- <li>
                <a href="{{ route('registrations.create') }}" aria-expanded="false" title="Registration">
                  <span class="educate-icon educate-form icon-wrap"></span>
                  <span class="mini-click-non">Registration</span>
                </a>
              </li> -->

              <li>
                <a class="" href="{{ route('backup') }}" aria-expanded="false" title="Backup">
                  <span class="educate-icon educate-data-table icon-wrap"></span>
                  <span class="mini-click-non">Backup</span>
                </a>
              </li>
              
              <li>
                <a class="has-arrow" title="Reports" href="#" aria-expanded="false">
                  <span class="educate-icon educate-data-table icon-wrap"></span>
                  <span class="mini-click-non">Reports</span>
                </a>
                <ul class="submenu-angle" aria-expanded="false">
                  <li>
                    <a onclick="showPopup()" title="Weekly" href="#">
                      <span class="mini-sub-pro">Weekly</span>
                    </a>
                  </li>
                  <li>
                    <a onclick="showPopup()" title="Monthly" href="#">
                      <span class="mini-sub-pro">Monthly</span>
                    </a>
                  </li>
                  <li>
                    <a onclick="showPopup()" title="Yearly" href="#">
                      <span class="mini-sub-pro">Yearly</span>
                    </a>
                  </li>
                </ul>
              </li>

              <!-- <li>
                <a class="has-arrow" href="#" aria-expanded="false" title="Branches">
                  <span class="educate-icon educate-course icon-wrap"></span>
                  <span class="mini-click-non">Branches</span>
                </a>
                <ul class="submenu-angle form-mini-nb-dp" aria-expanded="false">
                  <li>
                    <a title="Herat Branch" href="{{ route('branches.show', 'herat') }}">
                      <span class="mini-sub-pro">Herat</span>
                    </a>
                  </li>
                  <li>
                    <a title="Kabul Branch" href="{{ route('branches.show', 'kabul') }}">
                      <span class="mini-sub-pro">Kabul</span>
                    </a>
                  </li>
                </ul>
              </li> -->
            </ul>
          </nav>
          <br><hr><br><br><br>
        </div>
      </nav>
    </div>