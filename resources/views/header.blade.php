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
                          <svg height="22" width="22" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" fill="none" stroke=""><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill="#ffffff" fill-rule="evenodd" d="M19 4a1 1 0 01-1 1H2a1 1 0 010-2h16a1 1 0 011 1zm0 6a1 1 0 01-1 1H2a1 1 0 110-2h16a1 1 0 011 1zm-1 7a1 1 0 100-2H2a1 1 0 100 2h16z"></path> </g></svg>

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
                              <img src="{{ asset('img/profile/teache.jpg') }}" alt=""/>
                              <span class="admin-name">Admin Panel</span>
                              <i
                                class="fa fa-angle-down edu-icon edu-down-arrow"
                              ></i>
                            </a>
                            <ul
                              role="menu"
                              class="dropdown-header-top author-log dropdown-menu animated zoomIn">
                              @if(auth('admin')->user()->role === 'super_admin')
                                <li id="admin">
                                    <a onclick="openModal();"><span class="edu-icon edu-home-admin author-log-ic"></span>My Account</a>
                                </li>
                              @endif
                              <li>
                                <a href="#"><span class="edu-icon edu-settings author-log-ic"></span>Settings</a>
                              </li>
                              <!-- <li>
                                <a href="Reset_Cookies.php"
                                  ><span
                                    class="edu-icon edu-locked author-log-ic"
                                  ></span
                                  >Reset all cookies</a
                                >
                              </li> -->
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
             
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("logout") }}';
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);
                
                document.body.appendChild(form);
                form.submit();
            }
        }
        </script>
</div>