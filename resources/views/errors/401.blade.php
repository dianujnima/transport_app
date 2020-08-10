@extends('errors::minimal')

@section('title', __('Unauthorized Access'))
@section('code', '401')
@section('message', __('You are not authorized to access this'))
