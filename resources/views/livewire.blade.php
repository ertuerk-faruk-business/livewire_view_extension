<x-layout.base title="{{ $title }}">
    @foreach($components as $component)
        @livewire($component)
    @endforeach
</x-layout.base>