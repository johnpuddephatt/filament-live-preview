<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    @php
 $containers = $getChildComponentContainers(); $addAction =
    $getAction($getAddActionName()); $addBetweenAction =
    $getAction($getAddBetweenActionName()); $cloneAction =
    $getAction($getCloneActionName()); $deleteAction =
    $getAction($getDeleteActionName()); $moveDownAction =
    $getAction($getMoveDownActionName()); $moveUpAction =
    $getAction($getMoveUpActionName()); $reorderAction =
    $getAction($getReorderActionName()); $isAddable = $isAddable(); $isCloneable
    = $isCloneable(); $isCollapsible = $isCollapsible(); $isDeletable =
    $isDeletable(); $isReorderableWithButtons = $isReorderableWithButtons();
    $isReorderableWithDragAndDrop = $isReorderableWithDragAndDrop(); $statePath
    = $getStatePath(); $stylesheet = $getStylesheet();
@endphp

    <div {{ $attributes->
        merge($getExtraAttributes(), escape: false) ->class(['fi-fo-builder grid
        gap-y-4']) }} x-data="{ isPreviewOpen: false, currentPanel: null }"
        x-on:open-block.window="currentPanel = $event.detail"
        x-on:preview-open.window="$event.detail === '{{ $statePath }}' &&
        (isPreviewOpen = true)" x-on:preview-close.window="$event.detail === '{{ $statePath }}' &&
        (isPreviewOpen = false)">

        <div class="flex gap-x-3">
            @if((count($containers) > 1) && $isCollapsible)


            <span
                x-on:click="$dispatch('builder-collapse', '{{ $statePath }}')"
            >
                {{ $getAction('collapseAll') }}
            </span>

            <span x-on:click="$dispatch('builder-expand', '{{ $statePath }}')">
                {{ $getAction('expandAll') }}
            </span>


@endif
            
                <x-filament::button  class="ml-auto"
                x-on:click="$dispatch('builder-collapse', '{{ $statePath }}'); $dispatch('preview-open', '{{ $statePath }}')" outlined color="gray">

             Open preview
    </x-filament::button>
            
        </div>


        <div @keyup.escape="isPreviewOpen = false" :class="{'fixed flex flex-row-reverse bg-gray-50 inset-0 overscroll-y-none  z-50' :
        isPreviewOpen}" >

        <div style="flex-shrink: 0;" :class="{'w-[--sidebar-width] h-screen z-10 shadow-lg border-s overflow-hidden flex flex-col' :
        isPreviewOpen}" >

        <div x-show="isPreviewOpen" class="flex items-center px-4 py-2 border-b justify-between"><h3 class="font-medium text-sm ">{{ $field->getLabel() }}</h3>
             
    <x-filament::button x-on:click.prevent="$dispatch('preview-close', '{{ $statePath }}')" outlined color="gray">
    Close
</x-filament::button>
    </div>

        @if(count($containers))


        <ul
            x-sortable
            wire:end.stop="{{ 'mountFormComponentAction(\'' . $statePath . '\', \'reorder\', { items: $event.target.sortable.toArray() })' }}"
             :class="{'space-y-4' : !isPreviewOpen, 'flex-1 overflow-y-auto   overscroll-y-none' : isPreviewOpen}" 
        >
            @php
 $hasBlockLabels = $hasBlockLabels(); $hasBlockNumbers =
            $hasBlockNumbers();
@endphp @foreach($containers as $uuid => $item)


            <li
                wire:key="{{ $this->getId() }}.{{ $item->getStatePath() }}.{{ $field::class }}.item"
                x-ref="uuid{{  str_replace('-', '',$uuid)  }}"
                x-data="{
                            isCollapsed: @js($isCollapsed($item)),
                        }"
                x-on:builder-expand.window="$event.detail === '{{ $statePath }}' && (isCollapsed = false)"
                x-on:builder-collapse.window="$event.detail === '{{ $statePath }}' && (isCollapsed = true)"
                x-on:open-block.window="$event.detail === '{{ $uuid }}' && ($dispatch('builder-collapse', '{{ $statePath }}'),isCollapsed = false)"

                x-on:expand-concealing-component.window="
                            error = $el.querySelector('[data-validation-error]')

                            if (! error) {
                                return
                            }

                            isCollapsed = false

                            if (document.body.querySelector('[data-validation-error]') !== error) {
                                return
                            }

                            setTimeout(
                                () =>
                                    $el.scrollIntoView({
                                        behavior: 'smooth',
                                        block: 'start',
                                        inline: 'start',
                                    }),
                                200,
                            )
                        "
                x-sortable-item="{{ $uuid }}"
                class="fi-fo-builder-item ring-gray-950/5  shadow-sm ring-1 dark:bg-gray-900 dark:ring-white/10"
                :class="{'rounded-xl' : !isPreviewOpen, 'bg-white ring-1': !isPreviewOpen || !isCollapsed, 'bg-gray-50 ' : isPreviewOpen && isCollapsed }"
            >
                @if($isReorderableWithDragAndDrop || $isReorderableWithButtons
                || $hasBlockLabels || $isCloneable || $isDeletable ||
                $isCollapsible)


                <div class="flex items-center gap-x-3 px-4 py-2">
                    @if($isReorderableWithDragAndDrop ||
                    $isReorderableWithButtons)


                    <ul class="-ms-1.5 flex">
                        @if($isReorderableWithDragAndDrop)


                        <li x-sortable-handle>{{ $reorderAction }}</li>


@endif @if($isReorderableWithButtons)


                        <li class="flex items-center justify-center">
                            {{ $moveUpAction(['item' =>
                            $uuid])->disabled($loop->first) }}
                        </li>

                        <li class="flex items-center justify-center">
                            {{ $moveDownAction(['item' =>
                            $uuid])->disabled($loop->last) }}
                        </li>


@endif
                    </ul>


@endif @if($hasBlockLabels)


                    <h4
                        class="text-gray-950 truncate text-sm font-medium dark:text-white"
                    >
                        @php
 $block = $item->getParentComponent();
                        $block->labelState($item->getRawState());
@endphp {{ $item->getParentComponent()->getLabel() }} @php

                        $block->labelState(null);
@endphp @if($hasBlockNumbers)

                        {{ $loop->iteration }}
@endif
                    </h4>


@endif @if($isCloneable || $isDeletable || $isCollapsible)


                    <ul class="-me-1.5 ms-auto flex">
                        @if($isCloneable)


                        <li>{{ $cloneAction(['item' => $uuid]) }}</li>


@endif @if($isDeletable)


                        <li>{{ $deleteAction(['item' => $uuid]) }}</li>


@endif @if($isCollapsible)


                        <li
                            class="relative transition"
                            x-on:click.stop="isPreviewOpen && $dispatch('builder-collapse', '{{ $statePath }}'); isCollapsed = !isCollapsed"
                            x-bind:class="{ '-rotate-180': isCollapsed }"
                        >
                            <div
                                class="transition"
                                x-bind:class="{ 'opacity-0 pointer-events-none': isCollapsed }"
                            >
                                {{ $getAction('collapse') }}
                            </div>

                            <div
                                class="absolute inset-0 rotate-180 transition"
                                x-bind:class="{ 'opacity-0 pointer-events-none': ! isCollapsed }"
                            >
                                {{ $getAction('expand') }}
                            </div>
                        </li>


@endif
                    </ul>


@endif
                </div>


@endif

                <div
                    class="border-t border-gray-100 bg-white p-4 dark:border-white/10"
                    x-show="! isCollapsed"
                    x-transition
                >
                    {{ $item }}
                </div>
            </li>

            @if((! $loop->last) && $isAddable)


            <li class="relative -top-2 !mt-0 h-0">
                <div
                    class="flex w-full justify-center opacity-0 transition duration-75 hover:opacity-100"
                >
                    <div :class="{'hidden': isPreviewOpen}" class="rounded-lg bg-white dark:bg-gray-900">
                        <x-filament-forms::builder.block-picker
                            :action="$addBetweenAction"
                            :after-item="$uuid"
                            :blocks="$getBlocks()"
                            :state-path="$statePath"
                        >
                            <x-slot name="trigger">
                                {{ $addBetweenAction }}
                            </x-slot>
                        </x-filament-forms::builder.block-picker>
                    </div>

                    <div :class="{'hidden': !isPreviewOpen}" class="rounded-lg bg-white dark:bg-gray-900">
                        <x-filament-forms::builder.block-picker
                            :action="$addBetweenAction"
                            :after-item="$uuid"
                            :blocks="$getBlocks()"
                            :state-path="$statePath"
                        >
                            <x-slot name="trigger">
                                <span class="bg-white block rounded-full border-gray-300 border text-gray-400 hover:text-gray-700 h-5 w-5 p-1" style="line-height: 0.5">+</span>
                            </x-slot>
                        </x-filament-forms::builder.block-picker>
                    </div>
                </div>
            </li>


@endif
@endforeach
        </ul>


@endif @if($isAddable)


        <x-filament-forms::builder.block-picker
            :action="$addAction"
            :blocks="$getBlocks()"
            :state-path="$statePath"
            class="flex justify-center"
        >
            <x-slot name="trigger"> {{ $addAction }} </x-slot>
        </x-filament-forms::builder.block-picker>


@endif
                            </div>
                                    <template x-if="isPreviewOpen">

                            <div x-ref="previewPane" x-init="viewports['Fill'].viewportHeight = $el.clientHeight, viewports['Fill'].viewportWidth = $el.clientWidth" x-data="{
                                
                                selectedViewport:  'Fill',
                                viewports: {
                                    'Fill': {                                    
                                        viewportWidth: null,
                                        viewportHeight: null,
                                        icon: '{{ '<svg class="w-5 h-5" stroke-width="1.5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" color="#000000"><path d="M11 13.6V21H3.6a.6.6 0 01-.6-.6V13h7.4a.6.6 0 01.6.6zM11 21h3M3 13v-3M6 3H3.6a.6.6 0 00-.6.6V6M14 3h-4M21 10v4M18 3h2.4a.6.6 0 01.6.6V6M18 21h2.4a.6.6 0 00.6-.6V18M11 10h3v3" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>' }}'
                                    },
                                    'Phone': {                                    
                                        viewportWidth: 375,
                                        viewportHeight: 667,
                                        icon: '{{ '<svg class="w-5 h-5" stroke-width="1.5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" color="#000000"><path d="M12 16.01l.01-.011" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M7 19.4V4.6a.6.6 0 01.6-.6h8.8a.6.6 0 01.6.6v14.8a.6.6 0 01-.6.6H7.6a.6.6 0 01-.6-.6z" stroke="#000000" stroke-width="1.5"></path></svg>' }}'
                                    },
                                    'Laptop': {                                    
                                        viewportWidth: 1440,
                                        viewportHeight: 814,
                                        icon: '{{ '<svg class="w-5 h-5" stroke-width="1.5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" color="#000000"><path d="M3.2 14.222V4a2 2 0 012-2h13.6a2 2 0 012 2v10.222m-17.6 0h17.6m-17.6 0l-1.48 5.234A2 2 0 003.644 22h16.712a2 2 0 001.924-2.544l-1.48-5.234" stroke="#000000" stroke-width="1.5"></path><path d="M11 19h2" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>' }}'
                                    },
                                    'Desktop': {                                    
                                        viewportWidth: 2560,
                                        viewportHeight: 1352,
                                        icon: '{{ '<svg class="w-5 h-5" stroke-width="1.5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" color="#000000"><path d="M2 15.5V2.6a.6.6 0 01.6-.6h18.8a.6.6 0 01.6.6v12.9m-20 0v1.9a.6.6 0 00.6.6h18.8a.6.6 0 00.6-.6v-1.9m-20 0h20M9 22h1.5m0 0v-4m0 4h3m0 0H15m-1.5 0v-4" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>' }}'
                                    }
                                }
                            }"  :class="{'flex-grow flex items-center bg-gray-100 relative h-screen overflow-y-auto overscroll-y-none' : isPreviewOpen}" >

                    <div x-data="{showViewportMenu: false}" style="left: 50%; transform: translateX(calc(-50% - 160px))" class="top-4 z-20 flex fixed bg-white shadow-lg rounded-lg border">
            <button x-html="`${viewports[selectedViewport].icon} ${selectedViewport}`" class="p-2 whitespace-nowrap flex gap-1 text-sm font-medium items-center  hover:bg-gray-100" @click.prevent="showViewportMenu = !showViewportMenu"></button>

                    <div class="absolute bg-white overflow-hidden rounded top-full left-0" :class="{'hidden' : !showViewportMenu}">
                        <template x-for="(viewport, viewportName) in viewports">
                            <button :class="{'bg-gray-50' : viewportName == selectedViewport }" :title="`Scale to ${viewportName}`" x-html="`${viewport.icon} ${viewportName}`" class="gap-1 items-center whitespace-nowrap font-medium text-sm  w-full p-2 flex hover:bg-gray-100" @click.prevent="selectedViewport = viewportName, showViewportMenu = false">
                            </button>    
                        </template>
                    </div>
                        </div>

            <div class="relative border mx-auto shadow-xl" :style="{
                        overflow: 'scroll',
                        'flex-shrink': 0,
                        width: `${viewports[selectedViewport].viewportWidth}px`,
                        height: `${viewports[selectedViewport].viewportHeight}px`,
                    }" >
@foreach($containers as $uuid => $item)


@php
$data = [];
foreach($item->getState() as $itemKey => $itemValue) {
    $data[$itemKey] =
    (is_array($itemValue) || is_object($itemValue)) ? json_encode($itemValue) : $itemValue;
}
@endphp

@php($item->getParentComponent()->setRawAttributes($data))
        
         @php($viewData = view(
        $item->getParentComponent()->view, [ 'block' => $item->getParentComponent() ] )->render())

            <div
                class="relative hover:"
                
                x-data="{
                    previewData: null,

                    iframeWidth: null,
                    iframeHeight: null,
                    scale: 1,
                }"

                x-effect="previewData = @js($viewData)"
                x-on:message.window="$event.data === '{{ $uuid }}' && (iframeHeight = $refs.iframe.contentWindow.document.body.clientHeight)"
            >

                <div x-show="currentPanel !== '{{ $uuid }}'" class="bg-primary-500 opacity-50 absolute inset-0 border" @click="$dispatch('open-block','{{ $uuid }}' )"></div>

                <iframe
                    
                    x-ref="iframe"
                    @load=" $el.contentWindow.postMessage(previewData, '*');"
                    :style="{
                        height: `${ iframeHeight || viewports[selectedViewport].viewportHeight}px`,
                    }"
                    class=" block overflow-hidden w-full"
                    wire:ignore
                    x-effect="if($el.contentWindow) { $el.contentWindow.postMessage(previewData, '*'); }"
                    srcdoc="
                            <html>
                            <head>
                                <base target='_blank' />
                                <link rel='stylesheet' href='{{ $stylesheet }}' />
                                <script type='module'>
                                window.addEventListener('message', (event) => {
                                    document.body.innerHTML = event.data;
                                    window.parent.postMessage('{{ $uuid }}', '*');
                                });
                                window.addEventListener('load', (event)=> {
                                    window.parent.postMessage('{{ $uuid }}', '*');
                                });
                                window.addEventListener('resize', (event)=> {
                                    window.parent.postMessage('{{ $uuid }}', '*');
                                });
                            </script>
                            </head>
                            <body class='overflow-hidden'>
                            </body>
                            </html>
                        "
                ></iframe>
            </div>
            
            
            @endforeach
        </div>
    </div>
</template>
</x-dynamic-component>
