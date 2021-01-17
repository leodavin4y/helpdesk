@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb bg-light">
            <li class="breadcrumb-item"><a href="/">Главная</a></li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.index') }}">Администрирование</a>
            </li>
            <li class="breadcrumb-item active">Обзор</li>
        </ol>

        <h1 class="h3 py-3 text-center">Администрирование</h1>

        <div class="mb-3">
            <h2 class="h4 d-inline-block">Количество заявок</h2>

            <form class="d-inline-block ml-2" onchange="$(this).submit()">
                <select class="form-control" name="period" id="period">
                    @foreach ($periods as $period)
                        <option value="{{ $period['id'] }}" <?=$period['id'] === $selected_period ? 'selected' : ''?>>
                            {{ $period['name'] }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="row">
            <div class="col-12">
                <form class="d-inline-block mb-3" method="post" onchange="$(this).submit()">
                    {{ csrf_field() }}
                    <label>Тип графика</label>
                    <select class="form-control d-inline-block" name="type">
                        @foreach ($types as $type => $name)
                            <option value="{{ $type }}" <?=$type === $selected_type ? 'selected' : ''?>>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>

            <div class="col-12 col-md-6">
                {!! $requestChart->container() !!}
            </div>
        </div>
    </div>

    {{-- ChartScript --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>

    @if($requestChart)
        {!! $requestChart->script() !!}
    @endif
@endsection