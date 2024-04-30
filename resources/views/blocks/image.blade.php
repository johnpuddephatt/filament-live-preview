<div class="rounded-lg bg-slate-300 p-8">
    <h3>{{ $block->title }}</h3>

    <img src="{{ Storage::disk('public')->url($block->image) }}" />
</div>
