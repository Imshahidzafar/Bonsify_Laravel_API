@extends('commonfront.inner_page')
@section('title', 'Dashboard')
@section('content')
@inject('helpers', 'App\Classes\Helpers')
<div class="container">
   <div class="our-products">
      <div>
         <div class="pull-right advertise-phone primary-lang"><strong><i class="fa fa-phone"></i> 
          <?php $number= DB::table('system_settings')->where('type','=','system_number')->first();?>  

            <span class="generic-blue">{{ $number->description}}</span></strong></div>
         <h1 >{{$page_title}}</h1>
      </div>
       @if (session('errors'))
        <div class="alert alert-danger">
          {{ session('errors') }}
        </div> 
        @endif
        @if (session('msg'))
        <div class="alert alert-info">
          {{ session('msg') }}
        </div> 
        @endif

       
        @if(session('success'))
        <div class="alert alert-success">
          {{ session('success') }}
        </div> 
        @endif

      <div class="row">
         @foreach ($packages as $key => $package)
         <div class="modal fade" id="exampleModal_{{$key}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
               <div class="modal-dialog" role="document">
                  <div class="modal-content" style="margin-top: 188px;">
                     <div class="modal-body">
                        <form class="form-horizontal bordered-row" enctype="multipart/form-data" method="post" action="{{url('/add_payement')}}">
                        
                           {{ csrf_field() }}
                           <div class="form-group" hidden="">
                              <label class="col-sm-3 control-label">Package_id<span class="text-danger">*</span></label>
                              <div class="col-sm-6">
                                 <input name="packages_id" id="packages_id" type="text" value="{{ $package->packages_id }}" readonly="" />
                              </div>
                           </div>
                           <div class="form-group" >
                              <label class="col-sm-3 control-label">Package<span class="text-danger">*</span></label>
                              <div class="col-sm-6">
                                 <input name="title" value="{{ $package->title }} " id="title" type="text" readonly="" />
                              </div>
                           </div>
                           <div class="form-group">
                              <label class="col-sm-3 control-label">Price<span class="text-danger">*</span></label>
                              <div class="col-sm-6">
                                 <input name="price" value=" {{ $package->price }}" id="price" type="text" readonly="" />
                              </div>
                           </div>
                           <div class="form-group">
                              <label class="col-sm-3 control-label">Payement Method <span class="text-danger">*</span></label>
                              <div class="col-sm-6">
                                 <?php $payements = DB::table('payement_methods')->get();?>
                                 @foreach ($payements as $payement)  
                                 <select name="payement_method_id" required="" >
                                    <option value="">Select Method</option>
                                    <option value="{{ $payement->payement_method_id}}">{{ $payement->name}}</option>
                                 </select>
                                 @endforeach
                              </div>
                           </div>
                           <div class="form-group">
                              <label class="col-sm-3 control-label">Select image <span class="text-danger">*</span></label>
                              <div class="col-sm-6">
                                 <input type="file" required="" class="form-control" name="image" class="myfrm form-control">
                              </div>
                           </div>
                           <div class="form-group">
                              <div class="col-sm-12">
                                 <input type="submit" class="btn btn-primary"  value="Add" style="float: right;"></button>
                              </div>
                           </div>
                        </form>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-md-4">
               <div class="well mb20">
                  <a href="#">
                     <h2>{{ $package->title }}</h2>
                  </a>
                  <p class="product-details">{{ $package->description }}</p>
                  <a href="#" class="thumbnail">
                  @if($package->package_image)
                  <img alt="Products-carsure" src="{{asset('uploads/packages/'.$package->package_image)}}" style="width: 100%;height: 170px;"> @else
                   <img src="{{asset('uploads/make/default-image.jpg')}}" height=" 170px" width="100%">
                  @endif         
                  </a>
                  <span class="text-left hidden" id="id">{{ $package->price }}</span>
                  <input class="border-0" name="title" id="title" type="text" value="{{ $package->title }}" hidden="" />
                  <input class="border-0" name="price" id="price" type="text" value="{{ $package->price }}" readonly="" />
                  <input class="border-0" name="packages_id" id="packages_id" type="text" value="{{ $package->packages_id }}" hidden="" />
                  <!--  <span class="text-left" id="price">{{ $package->price }}</span>
                     -->
                  <div class=""> 
                     
                     <button class="btn btn-success" id="submit" data-toggle="modal"  data-target="#exampleModal_{{$key}}">Payement </button>
                  </div>
               </div>
           </div>
         @endforeach
      </div>
   </div>
</div>
<!-- Modal -->

<script>
   // $("#submit").click(function () {
   //    var price = $("#price").val();
   //    var title = $("#title").val();
   //    var packages_id = $("#packages_id").val();
     
   //    $(".modal-body #price").val(price);
   //    $(".modal-body #packages_id").val(packages_id);
   //    $(".modal-body #title").val(title);
           
   //      });
</script>
@endsection