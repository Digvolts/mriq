@if($collections->count() > 0)
    <section class="mb-12 md:mb-16">
        <div class="mb-6">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900">
                <i class="fas fa-th text-gray-400 mr-2"></i>Kategori
            </h2>
            <div class="h-0.5 w-12 bg-gray-300 mt-2"></div>
        </div>

        <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 md:gap-6">
            @foreach($collections as $collection)
                <a href="{{ route('collection.products', $collection->id) }}" 
                   class="group flex flex-col items-center gap-2 md:gap-3">
                    <div class="w-20 h-20 md:w-24 md:h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl flex items-center justify-center group-hover:from-gray-200 group-hover:to-gray-300 shadow-sm group-hover:shadow-md transition-all">
                        @if($collection->icon && \Illuminate\Support\Facades\Storage::disk('public')->exists($collection->icon))
                            <img src="{{ asset('storage/' . $collection->icon) }}" 
                                 alt="{{ $collection->name }}"
                                 class="w-10 h-10 md:w-12 md:h-12 object-contain">
                        @else
                            <i class="fas fa-box text-gray-400 text-2xl md:text-3xl"></i>
                        @endif
                    </div>
                    <span class="text-xs md:text-sm font-medium text-center text-gray-700 group-hover:text-gray-900 line-clamp-2">
                        {{ $collection->name }}
                    </span>
                </a>
            @endforeach
        </div>
    </section>
@endif