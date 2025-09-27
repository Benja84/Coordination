@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2>Ajouter un nouvel utilisateur</h2>

    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf

        <div class="form-group">
            <label>Nom</label>
            <input type="text" name="lastname" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Prenoms</label>
            <input type="text" name="firstname" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Mot de passe</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Confirmation mot de passe</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Créer l’utilisateur</button>
    </form>
</div>
@endsection