@extends('layouts.app')

@section('meta_title', 'Trop de tentatives | Portfolio')
@section('body_class', 'error-page')

@section('header')
    <header class="header sticky">
        <a href="{{ route('home') }}" class="logo">
            <i class="fa-solid fa-arrow-left"></i> Retour au Portfolio
        </a>
    </header>
@endsection

@section('content')
    <section class="error-section">
        <div class="error-container">
            <h1 class="error-code">429</h1>
            <h2>Trop de tentatives</h2>
            <p>Vous avez envoyé trop de messages en peu de temps.<br>
               Merci de patienter quelques minutes avant de réessayer.</p>
            <a href="{{ route('home') }}#contact" class="btn">
                <i class="fa-solid fa-arrow-left"></i> Retour au formulaire
            </a>
        </div>
    </section>
@endsection
