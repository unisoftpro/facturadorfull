  <header class="header">
      <div class="header-top">
          <div class="container">
              <div class="header-left header-dropdowns">
                

           

             
              </div><!-- End .header-left -->

              <div class="header-right">
               

                  <div class="header-dropdown dropdown-expanded">
                      <a href="#">Links</a>
                      <div class="header-menu">
                          <ul>
                              <li><a href="#">MY ACCOUNT </a></li>
                              <li><a href="#">DAILY DEAL</a></li>
                              <li><a href="#">MY WISHLIST </a></li>
                              <li><a href="#">BLOG</a></li>
                              <li><a href="#">Contact</a></li>
                              @guest
                              <li><a href="{{route('tenant_ecommerce_login')}}" class="login-link">LOG IN</a></li>
                              @else
                              <li><a href="#">{{ Auth::user()->email }}</a></li>
                              <li>
                                  <a role="menuitem" href="{{ route('logout') }}"
                                      onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                      <i class="fas fa-power-off"></i> @lang('app.buttons.logout')
                                  </a>
                                  <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                      style="display: none;">
                                      @csrf
                                  </form>
                              </li>
                              @endguest


                          </ul>
                      </div><!-- End .header-menu -->
                  </div><!-- End .header-dropown -->
              </div><!-- End .header-right -->
          </div><!-- End .container -->
      </div><!-- End .header-top -->

      <div class="header-middle">
          <div class="container">
              <div class="header-left">
                  <a href="/ecommerce" class="logo">
                      <img src="{{ asset('porto-ecommerce/assets/images/logo.png') }}" alt="Porto Logo">
                  </a>
              </div><!-- End .header-left -->

              <div class="header-center">
                  <div class="header-search">
                      <a href="#" class="search-toggle" role="button"><i class="icon-magnifier"></i></a>
                      <form action="#" method="get">
                          <div class="header-search-wrapper">
                              <input type="search" class="form-control" name="q" id="q" placeholder="Search..."
                                  required>
                              <button class="btn" type="submit"><i class="icon-magnifier"></i></button>
                          </div><!-- End .header-search-wrapper -->
                      </form>
                  </div><!-- End .header-search -->
              </div><!-- End .headeer-center -->

              <div class="header-right">
                  <button class="mobile-menu-toggler" type="button">
                      <i class="icon-menu"></i>
                  </button>
                  <div class="header-contact">
                      <span>Call us now</span>
                      <a href="tel:#"><strong>+123 5555</strong></a>
                  </div><!-- End .header-contact -->
                  @include('tenant.layouts.partials_ecommerce.cart_dropdown')
              </div><!-- End .header-right -->
          </div><!-- End .container -->
      </div><!-- End .header-middle -->

      <div class="header-bottom sticky-header">
            <div class="container">
                <nav class="main-nav">
                    
                </nav>
            </div><!-- End .header-bottom -->
        </div><!-- End .header-bottom -->
  </header><!-- End .header -->


  <div class="modal fade" id="moda-succes-add-product" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
      aria-hidden="true">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
              <!--<div class="modal-header ">
                  <h5 class="modal-title" id="exampleModalLabel"></h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>-->
              <div class="modal-body">

                  <div class="alert alert-success" role="alert">
                      <i class="icon-ok"></i> Tu producto se agregó al carrito
                  </div>
              </div>
              <div class="modal-footer">

                  <button type="button" class="btn btn-warning" data-dismiss="modal">Seguir Comprando</button>
              </div>
          </div>
      </div>
  </div>