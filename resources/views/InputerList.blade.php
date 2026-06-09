@extends('layout.main')
@section('main-container')

<br><br>

<div class="card">

   <div class="app-content">

      <section class="section" style="padding:10px;">

         <ol class="breadcrumb">

            <li class="breadcrumb-item"><a href="#" class="text-muted">Factory Creation</a></li>

            <li class="breadcrumb-item active text-" aria-current="page">Inputer List </li>

         </ol>

         <div class="row">

            <div class="container">

               <br>

               <div class="row">

                  <div class="col-4">

                     <h5>Inputer View List</h5>

                  </div>

                  <div class="col-8">

                     <div class="row">

                        <div class="col">

                        </div>

                        <div class="col"  style="text-align:right">

                           <a href="{{URL('/AddFactory')}}" class="btn btn-primary">Add Factory</a>

                        </div>

                     </div>

                  </div>

               </div>

               <hr>

               <div>

               </div>

            </div>

         </div>

      </section>

   </div>

</div>

</section>

@endsection