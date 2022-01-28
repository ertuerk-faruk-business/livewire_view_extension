<div id="art" class="p-12 flex-col hidden md:flex lg:hidden">
    <div class="flex flex-row justify-between items-start pr-4">
        <div class="flex flex-col items-start w-min">
            <div class="text-xl tracking-widest font-bold text-slate-300 uppercase whitespace-nowrap">
                Unsere Arbeit
            </div>
        </div>
        <a href="tel:491736660101" class="text-base tracking-widest font-bold uppercase text-slate-300 flex flex-row justify-center items-center space-x-2">
            <div>
                Termin vereinbaren
            </div>
            <x-svg.phone class="w-5 h-5 animate-pulse"/>
        </a>
    </div>
    <div class="grid grid-cols-2 gap-x-6 gap-y-12 mt-12">
        @foreach($this->getCollection('art')->value as $art)
            <div class="flex flex-col">
                <img class="object-cover aspect-square rounded" src="{{ asset($art['image']['value']) }}">
            </div>
        @endforeach
    </div>
</div>