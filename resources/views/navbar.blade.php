
<div class="all-content-wrapper">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="logo-pro">
              <a href="index.html"><h2 id="head"><span id="title-1">Nano</span><span id="title-2">Net</span></h2></a>
            </div>
          </div>
        </div>
      </div>
      <div class="header-advance-area">
        <div class="header-top-area">
          <div class="container-fluid">
            <div class="row">
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="header-top-wraper">
                  <div class="row">
                    <div class="col-lg-1 col-md-0 col-sm-1 col-xs-12">
                      <div class="menu-switcher-pro">
                        <button
                          type="button"
                          id="sidebarCollapse"
                          class="btn bar-button-pro header-drl-controller-btn btn-info navbar-btn">
                          <i class="educate-icon educate-nav"></i>
                        </button>
                      </div>
                    </div>
                    <div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
                      <div class="header-top-menu tabl-d-n">
                        <!-- <ul class="nav navbar-nav mai-top-nav">
                          <li class="nav-item">
                            <a href="#" class="nav-link">Home</a>
                          </li>
                          <li class="nav-item">
                            <a href="#" class="nav-link">About</a>
                          </li>
                          <li class="nav-item dropdown res-dis-nn">
                            <a
                              href="#"
                              data-toggle="dropdown"
                              role="button"
                              aria-expanded="false"
                              class="nav-link dropdown-toggle"
                              >Services
                              <span class="angle-down-topmenu"
                                ><i class="fa fa-angle-down"></i></span
                            ></a>
                            <div role="menu" class="dropdown-menu animated zoomIn">
                              <a href="#" class="dropdown-item">Documentation</a>
                              <a href="#" class="dropdown-item">Expert Backend</a>
                            </div>
                          </li>
                          <li class="nav-item">
                            <a href="#" class="nav-link">Contact</a>
                          </li>
                        </ul> -->
                      </div>
                    </div>
                    <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                      <div class="header-right-info">
                        <ul
                          class="nav navbar-nav mai-top-nav header-right-menu">

                          <li class="nav-item">
                            <a
                              href="#"
                              data-toggle="dropdown"
                              role="button"
                              aria-expanded="false"
                              class="nav-link dropdown-toggle">
                              <img src="<?php echo 'img/teacher/teache.jpg';?>" alt="" />
                              <span class="admin-name">Admin Panel</span>
                              <i
                                class="fa fa-angle-down edu-icon edu-down-arrow"
                              ></i>
                            </a>
                            <ul
                              role="menu"
                              class="dropdown-header-top author-log dropdown-menu animated zoomIn">
                              <li id="admin">
                                <a onclick="openModal();"><span class="edu-icon edu-home-admin author-log-ic"></span>My Account</a>
                              </li>
                              <li>
                                <a href="#"><span class="edu-icon edu-settings author-log-ic"></span>Settings</a>
                              </li>
                              <li>
                                <a href="#"
                                  ><span
                                    class="edu-icon edu-locked author-log-ic"
                                  ></span
                                  >Log Out</a
                                >
                              </li>
                            </ul>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Mobile Menu start -->
        <div class="mobile-menu-area">
          <div class="container">
            <div class="row">
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="mobile-menu">
                  <nav id="dropdown">
                    <ul class="mobile-menu-nav">
                      <li>
                        <a data-toggle="collapse" data-target="#Charts" href="#"
                          >Home
                          <span
                            class="admin-project-icon edu-icon edu-down-arrow"
                          ></span
                        ></a>
                        <ul class="collapse dropdown-header-top">
                          <li><a href="index.php">Dashboard</a></li>
                        </ul>
                      </li>
                      <li>
                        <a
                          data-toggle="collapse"
                          data-target="#demoevent"
                          href="#"
                          >Teacher
                          <span
                            class="admin-project-icon edu-icon edu-down-arrow"
                          ></span
                        ></a>
                        <ul id="demoevent" class="collapse dropdown-header-top">
                          <li>
                            <a href="Add-Teacher.php">Add New Teacher</a>
                          </li>
                          <li>
                            <a href="List-Of-Teachers.php">List Of Teachers</a>
                          </li>
                        </ul>
                      </li>
                      <!-- <li>
                        <a
                          data-toggle="collapse"
                          data-target="#demopro"
                          href="M-Students.php">M-Students<span class="admin-project-icon edu-icon edu-down-arrow"></span></a>
                      </li> -->
                      <li>
                        <a
                          data-toggle="collapse"
                          data-target="#democrou"
                          href="#"
                          >Course
                          <span
                            class="admin-project-icon edu-icon edu-down-arrow"
                          ></span
                        ></a>
                        <ul id="democrou" class="collapse dropdown-header-top">
                          <li><a href="Add-Course.php">Add New Course</a></li>
                          <li><a href="List-Of-Courses.php">List Of Courses</a></li>
                        </ul>
                      </li>
                      <li>
                        <a
                          data-toggle="collapse"
                          data-target="#demodepart"
                          href="#"
                          >Department
                          <span
                            class="admin-project-icon edu-icon edu-down-arrow"
                          ></span
                        ></a>
                        <ul
                          id="demodepart"
                          class="collapse dropdown-header-top"
                        >
                          <li>
                            <a href="Add-Department.php">Add New Department</a>
                          </li>
                          <li>
                            <a href="List-Of-Departments.php">List Of Departments</a>
                          </li>
                        </ul>
                      </li>
                      <!-- <li>
                        <a
                          data-toggle="collapse"
                          data-target="#demodepart"
                          href="Registration.php"
                          >Registration
                          <span
                            class="admin-project-icon edu-icon edu-down-arrow"
                          ></span
                        ></a>
                      </li> -->
                      <li>
                        <a
                          data-toggle="collapse"
                          data-target="#demodepart"
                          href="#"
                          > Backup
                          <span
                            class="admin-project-icon edu-icon edu-down-arrow"
                          ></span
                        ></a>
                      </li>
                      <li>
                        <a
                          class="has-arrow"
                          href="all-courses.html"
                          aria-expanded="false"
                          >
                          <span class="mini-click-non">Reports</span></a
                        >
                        <ul class="submenu-angle" aria-expanded="false">
                          <li>
                            <a title="#" href="Add-Course.html"
                              ><span class="mini-sub-pro">Weekly</span></a
                            >
                          </li>
                          <li>
                            <a title="#" href="List-Of-Courses.html"
                              ><span class="mini-sub-pro">Monthly</span></a
                            >
                          </li>
                          <li>
                            <a title="#" href="List-Of-Courses.html"
                              ><span class="mini-sub-pro">Yearly</span></a
                            >
                          </li>
                        </ul>
                      </li>
                      <!-- <li>
                        <a
                          data-toggle="collapse"
                          data-target="#demodepart"
                          href="#"
                          >Branches
                          <span
                            class="admin-project-icon edu-icon edu-down-arrow"
                          ></span
                        ></a>
                        <ul
                          id="demodepart"
                          class="collapse dropdown-header-top"
                        >
                          <li>
                            <a href="#">Herat</a>
                          </li>
                          <li>
                            <a href="#">Kabul</a>
                          </li>
                        </ul>
                      </li> -->
                    </ul>
                  </nav>
                </div>
              </div>
            </div>
          </div>
        </div>
      <br>
      <hr>
  </div>  
