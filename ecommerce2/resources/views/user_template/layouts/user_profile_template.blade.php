

@section('user-content')
<div class="container">
                <div class="col-lg-4">
                       <div class="box-main">
                          <ul>
                           <li ><a href="">Kullanıcı Panelim </a></li>
                           <li ><a href="">Siparişlerim </a></li>
                           <li ><a href="">Geçmiş</a></li>
                           <li ><a href="">Log out </a></li>

                          </ul>

                       </div>
                   <div class="col-lg-8">
                   <div class="box-main">
                          

                          </div>

                        @yield('prof-content')
                   </div>
            
                </div>

           </div>
           @endsection