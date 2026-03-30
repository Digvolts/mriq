<section class="mb-12 md:mb-16">
    <div class="relative w-full h-72 md:h-96 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl overflow-hidden shadow-lg">
        <div id="heroSlideshow" class="slideshow-container relative h-full">
            @if($newArrivals->count() > 0)
                @foreach($newArrivals as $index => $arrival)
                    <div class="slide absolute inset-0 w-full h-full {{ $index === 0 ? 'is-active' : '' }}">
                        @if($arrival->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($arrival->image))
                            <img src="{{ asset('storage/' . $arrival->image) }}"
                                 alt="{{ $arrival->name }}"
                                 class="slide-image w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-300 to-gray-400">
                                <i class="fas fa-image text-4xl text-gray-500"></i>
                            </div>
                        @endif

                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent flex items-end p-6 md:p-8">
                            <div class="text-white slide-caption">
                                <h3 class="text-xl md:text-3xl font-bold mb-1">{{ $arrival->name }}</h3>
                                <p class="text-xs md:text-sm text-gray-100 opacity-90">
                                    <i class="fas fa-sparkles mr-2"></i>Terbaru Kami
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-slate-700 to-slate-900">
                    <div class="text-center text-white">
                        <i class="fas fa-store text-5xl mb-4 block opacity-80"></i>
                        <h3 class="text-2xl md:text-4xl font-bold mb-2">Welcome to 2DAY</h3>
                        <p class="text-gray-300 text-sm md:text-base">Produk Pilihan untuk Hari Anda</p>
                    </div>
                </div>
            @endif
        </div>

        @if($newArrivals->count() > 1)
            <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2 z-20">
                @for($i = 0; $i < $newArrivals->count(); $i++)
                    <button
                        class="dot w-2.5 h-2.5 rounded-full bg-white/70 backdrop-blur-sm transition-all duration-300 {{ $i === 0 ? 'opacity-100 scale-125' : 'opacity-40' }}"
                        onclick="currentSlide({{ $i }})"
                        aria-label="Slide {{ $i + 1 }}">
                    </button>
                @endfor
            </div>
        @endif
    </div>
</section>

@push('styles')
<style>
    #heroSlideshow .slide {
        opacity: 0;
        visibility: hidden;
        transform: scale(1.06);
        transition:
            opacity 900ms ease,
            transform 1400ms cubic-bezier(0.22, 1, 0.36, 1),
            visibility 900ms ease;
        z-index: 1;
    }

    #heroSlideshow .slide.is-active {
        opacity: 1;
        visibility: visible;
        transform: scale(1);
        z-index: 10;
    }

    #heroSlideshow .slide-image {
        transform: scale(1.08);
        transition: transform 5.5s ease;
        will-change: transform;
    }

    #heroSlideshow .slide.is-active .slide-image {
        transform: scale(1);
    }

    #heroSlideshow .slide-caption {
        opacity: 0;
        transform: translateY(18px);
        transition:
            opacity 700ms ease 250ms,
            transform 700ms ease 250ms;
    }

    #heroSlideshow .slide.is-active .slide-caption {
        opacity: 1;
        transform: translateY(0);
    }

    .dot {
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.18);
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const slides = document.querySelectorAll('#heroSlideshow .slide');
        const dots = document.querySelectorAll('.dot');
        let currentIndex = 0;
        let interval;

        if (slides.length <= 1) return;

        function showSlide(index) {
            slides.forEach((slide, i) => {
                slide.classList.remove('is-active');

                if (dots[i]) {
                    dots[i].classList.remove('opacity-100', 'scale-125');
                    dots[i].classList.add('opacity-40');
                }
            });

            slides[index].classList.add('is-active');

            if (dots[index]) {
                dots[index].classList.remove('opacity-40');
                dots[index].classList.add('opacity-100', 'scale-125');
            }

            currentIndex = index;
        }

        function nextSlide() {
            const nextIndex = (currentIndex + 1) % slides.length;
            showSlide(nextIndex);
        }

        function resetInterval() {
            clearInterval(interval);
            interval = setInterval(nextSlide, 4500);
        }

        window.currentSlide = function(index) {
            showSlide(index);
            resetInterval();
        };

        showSlide(0);
        resetInterval();
    });
</script>
@endpush