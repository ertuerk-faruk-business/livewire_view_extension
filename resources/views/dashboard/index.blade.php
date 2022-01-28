<div>
    @include('dashboard.index.header.base')
    @include('dashboard.index.header.m')
    @include('dashboard.index.header.lg')
    @include('dashboard.index.header.xl')
    <section id="employees">
        @include('dashboard.index.employees.base')
        @include('dashboard.index.employees.m')
        @include('dashboard.index.employees.lg')
        @include('dashboard.index.employees.xl')
    </section>
    <section id="art">
        @include('dashboard.index.art.base')
        @include('dashboard.index.art.m')
        @include('dashboard.index.art.lg')
        @include('dashboard.index.art.xl')
    </section>
    <footer class="flex flex-col w-full p-12 items-center">
        <div class="text-xs text-center tracking-widest">
            ©2021 Million Arts Tattoo
        </div>
        <div class="text-xs text-center tracking-widest mt-4">
            Sheila & Joschka Rothberg GbR; Million Arts Tattoo; Sheila Rothberg; Holm 57-61; 24937 Flensburg
        </div>
    </footer>
</div>