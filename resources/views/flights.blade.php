@extends('components.layout')

@section('title', content: 'Manage Flights')

@section('content')
<x-flight-form />
<x-flight-table />
@endsection