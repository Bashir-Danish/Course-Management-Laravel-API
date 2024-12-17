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
                              <img src="<?php echo 'img/profile/teache.jpg';?>" alt="" />
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
                                <a href="Reset_Cookies.php"
                                  ><span
                                    class="edu-icon edu-locked author-log-ic"
                                  ></span
                                  >Reset all cookies</a
                                >
                              </li>
                              <li>
                                <a href="#" onclick="logout(event)">
                                  <span class="edu-icon edu-locked author-log-ic"></span>Log Out
                                </a>
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

        <script>
        function logout(e) {
            e.preventDefault();
            
            if(confirm('Are you sure you want to logout?')) {
                // Create a form dynamically
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("logout") }}';
                
                // Add CSRF token
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);
                
                // Add to document and submit
                document.body.appendChild(form);
                form.submit();
            }
        }
        </script>
</div>