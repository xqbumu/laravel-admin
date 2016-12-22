@extends('docore::index')

@section('content')
    <section class="content-header">
        <h1>
            {{ $header or trans('docore::lang.title') }}
            <small>{{ $description or trans('docore::lang.description') }}</small>
        </h1>

    </section>

    <section class="content">

        @include('docore::partials.error')

        {!! $content !!}

    </section>
@endsection