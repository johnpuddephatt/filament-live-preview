@if($block->stats)


<div class="bg-white py-24">
    <div class="max-w-7xl mx-auto rounded-lg bg-slate-900 py-12 px-6 lg:px-8">
        <div class="mx-auto text-center lg:mx-0">
            <h2
                class="text-3xl font-bold tracking-tight text-white sm:text-4xl"
            >
                {{ $block->title }}
            </h2>
            @if($block->description)


            <p class="mt-2 text-xl leading-8 text-gray-400">
                {{ $block->description }}
            </p>


@endif
        </div>
        <dl
            class="mt-12 grid grid-cols-1 gap-x-8 gap-y-16 text-center lg:grid-cols-3"
        >
            @foreach($block->stats as $stat)


            <div class="mx-auto flex max-w-xs flex-col gap-y-4">
                <dt class="text-base leading-7 text-gray-200">
                    {{ $stat->description }}
                </dt>
                <dd
                    class="order-first text-3xl font-semibold tracking-tight text-white sm:text-5xl"
                >
                    {{ $stat->title }}
                </dd>
            </div>


@endforeach
        </dl>
    </div>
</div>


@endif