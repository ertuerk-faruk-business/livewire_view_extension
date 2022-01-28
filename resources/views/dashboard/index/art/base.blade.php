<div id="art" class="p-6 flex flex-col md:hidden">
    <div class="flex flex-row justify-between items-start pr-4">
        <div class="flex flex-col items-start w-min">
            <div class="text-xl tracking-widest font-bold text-slate-300 uppercase whitespace-nowrap">
                Unsere Arbeit
            </div>
        </div>
        <a href="tel:491736660101" class="text-xs tracking-widest font-bold uppercase text-slate-300 flex flex-row justify-center items-center space-x-2">
            <x-svg.phone class="w-6 h-6"/>
        </a>
    </div>
    <div class="grid grid-cols-1 gap-x-6 gap-y-12 mt-6">
        @foreach($this->getCollection('art')->value as $art)
            <div class="flex flex-col">
                <img class="object-cover aspect-square rounded" src="{{ asset($art['image']['value']) }}">
            </div>
        @endforeach
    </div>
</div>