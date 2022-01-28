<div class="p-12 flex-col hidden lg:flex xl:hidden">
    <div class="flex flex-row justify-between items-start pr-4">
        <div class="flex flex-col items-start w-min">
            <div class="text-xl tracking-widest font-bold text-slate-300 uppercase whitespace-nowrap">
                Das sind wir
            </div>
        </div>
        <a href="#art" class="text-sm tracking-widest font-bold uppercase text-slate-300 flex flex-row justify-center items-center space-x-2">
            <div>
                Unsere Arbeit
            </div>
            <x-svg.down class="w-5 h-5 animate-pulse"/>
        </a>
    </div>
    <div class="grid grid-cols-3 gap-x-6 gap-y-12 mt-12">
        @foreach($this->getCollection('employees')->value as $employee)
            <div class="flex flex-col">
                <div class="text-sm tracking-widest uppercase font-bold px-2">
                    {{ $employee['name']['value'] }}
                </div>
                <img class="mt-4 object-cover aspect-square rounded transform hover:scale-105 duration-200 ease-in-out transition" src="{{ asset($employee['image']['value']) }}">
                <div class="text-sm tracking-widest uppercase font-bold mt-4 text-slate-300 px-2">
                    {{ $employee['description']['value'] }}
                </div>
            </div>
        @endforeach
    </div>
</div>