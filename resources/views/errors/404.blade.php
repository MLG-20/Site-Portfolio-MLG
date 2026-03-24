@extends('layouts.app')

@section('meta_title', 'Page introuvable | Portfolio')
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
            <h1 class="error-code">404</h1>
            <h2>Page introuvable</h2>
            <p>La page que vous cherchez n'existe pas ou a été déplacée.</p>
            <a href="{{ route('home') }}" class="btn">
                <i class="fa-solid fa-house"></i> Retour à l'accueil
            </a>
        </div>
    </section>
@endsection
