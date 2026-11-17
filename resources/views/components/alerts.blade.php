@props([
    'type' => 'info',
    'title' => null,
    'message' => null,
])

@php
    $alertClasses = [
        'success' => 'alert alert-success alert-dismissible fade show',
        'info'    => 'alert alert-info alert-dismissible fade show',
        'warning' => 'alert alert-warning alert-dismissible fade show',
        'danger'  => 'alert alert-danger alert-dismissible fade show',
    ];

    $alertClass = $alertClasses[$type] ?? $alertClasses['info'];
@endphp

<div class="{{ $alertClass }}" role="alert">
    @if($title)
        <h4 class="alert-heading">{{ $title }}</h4>
    @endif

    @if($message)
        <p class="mb-0">{{ $message }}</p>
    @endif

    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
