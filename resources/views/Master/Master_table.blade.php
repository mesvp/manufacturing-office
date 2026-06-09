@extends('layout.main')
@section('main-container')
<style>
    button {
        background-color: #04AA6D;
        color: #ffffff;
        border: none;
        padding: 10px 20px;
        font-size: 17px;
        font-family: Raleway;
        cursor: pointer;
    }

    button:hover {
        opacity: 0.8;
    }

    td.maindffd {
        display: flex;
        justify-content: space-evenly;
        width: 100%;
    }
</style>
<!--<br><br>-->
<div class="card-form">
    <div class="app-content">
        @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <section class="section">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Master View Page</li>
            </ol>
            <div class="addbtn">
                <!-- <a href="{{url('Master/')}}"><button class="btn btn-info">Gate Pass</button></a> -->
            </div>
            <div class="row">
                <div class="container">
                    <div class="table-responsive">
                        <table id="example" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="th-sm">SL. No.</th>                                  
                                    <th class="th-sm">Operation</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td></td>                        
                                    <td class="maindffd">
                                        <!-- <a href="{{url('Master/step1/')}}" class="btn btn-secondary">Edit</a>
                                        <a href="{{url('Master/delete/')}}" class="btn btn-danger">Delete</a> -->
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection