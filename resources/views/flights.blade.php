@extends('components.layout')

@section('title', content: 'Manage Flights')

@section('content')
<x-airline-select />
<x-city-select />
@endsection