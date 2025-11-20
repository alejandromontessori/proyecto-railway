{{-- resources/views/verOpinionesRaiz.blade.php --}}
{{-- Parcial recursivo: muestra una opinión y sus respuestas --}}
<div class="op-card" style="background:#f9f9f9;border:1px solid #e5e7ea;border-radius:10px;padding:12px;margin-bottom:10px;">
    {{-- Si es respuesta de otra --}}
    @if($opinion->id_respondido && $opinion->responde)
        <div class="op-parent" style="background:#f1f1f1;padding:10px;border-left:3px solid #ccc;margin-bottom:10px;font-style:italic;color:#555;">
            En respuesta a: “{{ \Illuminate\Support\Str::limit($opinion->responde->texto, 120) }}”
        </div>
    @endif

    <div class="op-meta" style="font-size:13px;color:#555;margin-bottom:6px;">
        <strong>#{{ $opinion->id }}</strong>
        &nbsp;·&nbsp;<strong>{{ $opinion->autor->apodo ?? 'Anónimo' }}</strong>
        opinó en <em>{{ $opinion->idea->nombre ?? 'Idea eliminada' }}</em>
        · {{ $opinion->created_at?->format('d/m/Y H:i') }}
        · Valoración: {{ $opinion->valoracion }} ⭐
    </div>
    <div class="op-text">“{{ $opinion->texto }}”</div>
</div>

{{-- Respuestas (anidación) --}}
@if($opinion->respuestas && $opinion->respuestas->count())
    <div class="op-replies" style="margin-left:28px;border-left:3px solid #86b7b2;padding-left:12px;margin-top:8px;">
        @foreach($opinion->respuestas as $respuesta)
            @include('verOpinionesRaiz', ['opinion' => $respuesta])
        @endforeach
    </div>
@endif
