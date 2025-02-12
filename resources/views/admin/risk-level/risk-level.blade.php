@php
    $mainmenu = 'Risk Level';
    $submenu = 'Risk Level';

@endphp
@extends('admin.layout')

@section('container')
    <div class="fluid-container mb-3">

        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default" fdprocessedid="enyy57">
            New
        </button>

    </div>

    <div class="row">

        <div class="col-12">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">All Risk Level</h3>
                </div>


                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>

                                <th>Keywords</th>
                                <th>Risk Level</th>

                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($datas as $doc)
                                <tr>

                                    <td>{{ $doc->keyword }}</td>

                                    <td>{{ $doc->risk_level }}</td>
                                    <td>
                                        <a class="mdi mdi-table-edit" href="{{ route('risk-level.edit', $doc->id) }}"><button
                                                class="btn btn-dark">Edit</button></a>

                                        <form action="{{ route('risk-level.destroy', $doc->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="confirmation btn btn-danger" title="Delete"
                                                onclick="return confirm('Are You sure')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach

                            </tfoot>
                    </table>
                </div>
                <!-- /.card-body -->

                <!-- /.card -->
            </div>
            <!-- /.col -->


        </div>




    </div>

    <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Create Keyword</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ route('risk-level.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="col-md-12">


                        <input type="hidden" name="type" value="appsettings" />
                        <div class="card-body">

                            <div class="form-group">

                                <label for="exampleInputName1">Risk Level*</label>
                                <select class="form-control" id="risk_level" name="risk_level" required>
                                    <option class="selected disabled hidden;">Select Risk Level</option>
                                    <option value="critical">Critical</option>
                                    <option value="minor">Minor</option>
                                </select>
                            </div>
                            <div class="form-group">

                                <label for="exampleInputName1">Keyword Name*</label>
                                <input type="name" name="keyword_name" class="form-control" id="exampleInputName1"
                                    placeholder="Enter name" required>
                            </div>


                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                </form>
            </div>

        </div>

    </div>
@endsection


@section('jquery')
@endsection
