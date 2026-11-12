@extends('layouts.admin')

@section('title', 'Edit User')
@section('breadcrumb', 'Admin / Manajemen User / Edit')

@section('content')
    @include('admin.user.form')
@endsection
