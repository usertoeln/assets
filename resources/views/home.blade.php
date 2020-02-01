@extends('layouts.app')
@section('content')
    <div id="app">
        <main_panel></main_panel>
    </div>
    @push('scripts')
        <script src="{{ mix('/js/manifest.js') }}"></script>
        <script src="{{ mix('/js/vendor.js') }}"></script>
        <script src="{{ mix('/js/app.js') }}"></script>
    @endpush
    @stack('scripts')
@endsection
