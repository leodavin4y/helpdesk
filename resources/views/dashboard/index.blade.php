@extends('layouts.app')

@section('title', 'Панель управления | ' . $_ENV['APP_NAME'])

@section('content')
    <div class="container">
        <h1 class="h3 py-3 text-center">Панель управления</h1>

        <div class="row">
            <div class="col-12">
                <div class="p-2 border rounded">
                    <a href="" class="btn btn-light">
                        <i class="fa fa-plus" aria-hidden="true" style="color:#b1b1b1;"></i> Новая заявка
                    </a>
                </div>

                <ul class="nav nav-tabs mt-3">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Активные заявки</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Завершенные заявки</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endsection