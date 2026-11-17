@props([
    'url' => '#',
    'label' => 'Excluir',
    'confirm' => 'Tem certeza que deseja excluir este item?',
    'method' => 'DELETE',
])

<form action="{{ $url }}" method="POST" class="d-inline" onsubmit="return confirm('{{ $confirm }}');">
    @csrf
    @method({{ $method }})
    <button type="submit" class="btn btn-danger btn-sm">
        <i class="bi bi-trash"></i> {{ $label }}
    </button>
</form>
