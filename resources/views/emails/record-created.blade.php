@extends('emails.layout.theme')

@section('title')
Nuevo Registro Creado
@endsection

@section('content')
<p>Hola {{ $user->name }},</p>

<p>Se ha creado un nuevo registro en el sistema {{ config('app.name') }} :</p>

<ul>
    <li><strong>Título:</strong> {{ $record->title }}</li>
    <li><strong>Creado por:</strong> {{ $record->user->name ?? 'Sistema' }}</li>
    <li><strong>Fecha:</strong> {{ $record->created_at->format('d/m/Y') }}</li>
</ul>

<p>
    <a href="{{ route('filament.dashboard.resources.records.files.list', ['recordId' => $record->id ]) }}"
       class="button">
        Ver Registro
    </a>
</p>
@endsection
