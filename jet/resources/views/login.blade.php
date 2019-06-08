@extends('layout')

@section('content')

    <div class="container">

        <div class="row">
            <div class="col-lg-12">
                <div class="title m-b-md">Jet</div>
                <br>
                <form action="/signin" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="user">Usuario</label>
                        <input type="text" name="username" class="form-control" id="user" aria-describedby="emailHelp" placeholder="Ingresa tu usuario">
                    </div>
                    <div class="form-group">
                        <label for="password">Constrasena</label>
                        <input type="password" name="password" class="form-control" id="password" placeholder="Contrasena">
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-outline-primary btn-block">Iniciar sesion</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection