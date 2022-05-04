@extends('layouts.app')

@section('content')
    <h2>{{ $name }}</h2>

    <item-list module-name="{{ $moduleName }}"></item-list>
@endsection
