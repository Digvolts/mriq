<div
    id="product-{{ \Illuminate\Support\Str::slug($productName) }}"
    class="product-card bg-white rounded-xl overflow-hidden hover:shadow-lg transition"
    data-product-name="{{ strtolower($productName) }}"
    data-product-description="{{ strtolower($data['first']->description ?? '') }}"
>
    <!-- Image -->
    <div class="relative h-56 md:h-64 bg-gray-100 overflow-hidden group">
        @if($data['first']->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($data['first']->image))
            <img
                src="{{ asset('storage/' . $data['first']->image) }}"
                alt="{{ $productName }}"
                class="w-full h-full object-cover group-hover:scale-110 transition duration-500"
            >
        @else
            <div class="w-full h-full flex items-center justify-center bg-gray-200">
                <i class="fas fa-image text-3xl text-gray-400"></i>
            </div>
        @endif
    </div>

    <!-- Info -->
    <div class="p-4">
        @if($data['first']->collection)
            <p class="text-xs text-gray-500 mb-2 uppercase tracking-wide">
                {{ $data['first']->collection->name }}
            </p>
        @endif

        <h3 class="font-semibold text-sm md:text-base text-gray-900 line-clamp-2 mb-2">
            {{ $productName }}
        </h3>

        <p class="text-xs text-gray-500 mb-3 line-clamp-2">
            {{ \Illuminate\Support\Str::limit($data['first']->description, 50) }}
        </p>

        <div class="mb-4">
            @if($data['cheapest']->discount_price && $data['cheapest']->discount_price > 0)
                <div class="space-y-1.5">
                    <p class="text-sm text-red-500 line-through font-semibold">
                        Rp{{ number_format((int) $data['cheapest']->price, 0, ',', '.') }}
                    </p>

                    <div class="flex items-center gap-2 flex-wrap">
                        <p class="text-lg md:text-xl font-bold text-emerald-600">
                            Rp{{ number_format((int) $data['cheapest']->discount_price, 0, ',', '.') }}
                        </p>

                        @php
                            $discount = (($data['cheapest']->price - $data['cheapest']->discount_price) / $data['cheapest']->price) * 100;
                        @endphp

                        <span class="px-2 py-1 bg-red-100 text-red-700 text-xs font-bold rounded-md whitespace-nowrap">
                            -{{ round($discount) }}%
                        </span>
                    </div>
                </div>
            @else
                <p class="text-lg md:text-xl font-bold text-gray-900">
                    Rp{{ number_format((int) $data['cheapest']->price, 0, ',', '.') }}
                </p>
            @endif
        </div>

        <div class="space-y-2">
            <a
                href="{{ route('products.show', $data['first']->id) }}"
                class="w-full px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-black transition font-medium text-sm flex items-center justify-center gap-2"
            >
                <i class="fas fa-eye"></i>Lihat Varian
            </a>
        </div>
    </div>
</div>