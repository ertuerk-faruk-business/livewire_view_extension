<header id="header" class="relative w-screen h-screen hidden lg:block xl:hidden">
    <div class="absolute w-full h-full z-0 transform">
        <div class="images-area images-area--lg w-full h-full flex flex-row">
            <img class="w-full h-full object-cover firstImage" src="{{ asset('/images/header.webp') }}">
            <img class="w-full h-full object-cover" src="{{ asset('/images/header1.webp') }}">
            <img class="w-full h-full object-cover" src="{{ asset('/images/header2.webp') }}">
        </div>
    </div>
    <div class="absolute w-full h-full bg-black bg-opacity-60 p-12 flex flex-col justify-between">
        <nav class="flex flex-row items-start w-full justify-between relative">
            <div class="flex flex-row space-x-12 items-start fade-in">
                <div class="text-xl tracking-widest uppercase font-bold">
                    Million Arts<br>Tattoo
                </div>
                <a target="_blank" href="https://maps.google.com/maps?q=Million Arts Tattoo, Holm 57-61, 24937 Flensburg" class="text-sm tracking-widest uppercase font-bold">
                    Holm 57-61, 24937<br>Flensburg Galerie
                </a>
            </div>
            <div class="flex flex-row space-x-12 items-start fade-in--delay-25">
                <a target="_blank" href="https://www.facebook.com/millionartstattoo" class="text-sm tracking-widest uppercase font-bold">
                    Facebook
                </a>
                <a target="_blank" href="https://www.instagram.com/millionartstattoo/" class="text-sm tracking-widest uppercase font-bold">
                    Instagram
                </a>
                <a target="_blank" href="https://www.tiktok.com/@sheilarothberg?lang=de-DE" class="text-sm tracking-widest uppercase font-bold">
                    TikTok
                </a>
            </div>
        </nav>
        <div class="flex flex-col items-start">
            <div class="text-6xl tracking-widest font-bold uppercase fade-in--delay-5">
                Expert Tattoos<br>& Piercings
            </div>
            <div class="flex flex-col items-start mt-12">
                <div class="flex flex-col items-start fade-in--delay-75">
                    <a href="tel:+491736660101" target="_blank" class="text-xl tracking-widest font-bold uppercase">
                        Termin vereinbaren
                    </a>
                    <hr class="bg-white h-0.5 w-full mt-2">
                </div>
            </div>
        </div>
        <a href="#employees" class="absolute -rotate-90 transform text-base tracking-widest font-bold uppercase text-slate-300 right-0 m-auto text-center top-1/2 flex flex-row justify-center items-center space-x-2">
            <x-svg.left class="w-6 h-6 animate-pulse"/>
            <div>
                Das sind wir
            </div>
        </a>
    </div>
</header>