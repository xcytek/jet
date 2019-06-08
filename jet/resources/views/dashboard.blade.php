@extends('layout')

@section('content')

    <style>
        .row-bordered {
            border: 1px solid gray;
            border-radius: 20px;
            padding: 20px;
            margin-bottom: 25px;
        }

        .row.evidence {
            margin: 15px 0;
        }
    </style>

    <div class="title m-b-md">
        Evidencias
    </div>

    <div class="links">
        <a href="/">Salir</a>
    </div>

    <div class="row row-bordered">
        <div class="col-lg-12">
            <form action="/upload" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <h4>Nueva evidencia</h4>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlFile1">Subir archivos unicamente PDF</label>
                    <input type="file" class="form-control-file btn-outline-default" name="archivo" id="exampleFormControlFile1">
                </div>
                <div class="form-group">

                    @if (session('error') !== false)
                        <small class="alert-danger">{{ session('error') }}</small>
                    @endif
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-outline-primary btn-block">Subir</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row row-bordered">
        <div class="col-lg-12">
            <div class="form-group">
                <h4>Lista de evidencias</h4>
            </div>

            <table id="evidences" class="table table-striped table-bordered">
                <tr><th class="th-sm">Archivo</th><th class="th-sm">Fecha</th></tr>
                @foreach ($evidences as $evidence)
                    <tr>
                        <td><a href="{{ $evidence['file'] }}">{{ $evidence['file_name'] }}</a></td>
                        <td>{{ $evidence['date'] }}</td>
                    </tr>
                @endforeach;
            </table>

        </div>
    </div>

@endsection