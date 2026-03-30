<section class="mb-12 md:mb-16">
    <div class="relative w-full">
        <div class="relative flex-1">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>

            <input
                type="text"
                id="liveSearchInput"
                placeholder="Cari produk..."
                autocomplete="off"
                class="w-full pl-11 pr-11 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-400 focus:border-transparent transition"
            >

            <button
                type="button"
                id="clearSearchBtn"
                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 hidden"
                aria-label="Hapus pencarian"
            >
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('liveSearchInput');
    const clearBtn = document.getElementById('clearSearchBtn');
    const productCards = document.querySelectorAll('.product-card');
    const resultCount = document.getElementById('resultCount');
    const totalCount = document.getElementById('totalCount');
    const resultsCounter = document.getElementById('resultsCounter');
    const noProductsFound = document.getElementById('noProductsFound');

    function normalize(text) {
        return (text || '').toString().toLowerCase().trim();
    }

    function filterProducts(keyword) {
        const query = normalize(keyword);
        let visibleCount = 0;

        productCards.forEach(card => {
            const productName = normalize(card.dataset.productName);

            if (query === '' || productName.includes(query)) {
                card.classList.remove('hidden');
                visibleCount++;
            } else {
                card.classList.add('hidden');
            }
        });

        if (resultCount) {
            resultCount.textContent = visibleCount;
        }

        if (totalCount) {
            totalCount.textContent = productCards.length;
        }

        if (query === '') {
            clearBtn.classList.add('hidden');

            if (resultsCounter) {
                resultsCounter.classList.add('hidden');
            }

            if (noProductsFound) {
                noProductsFound.classList.add('hidden');
            }

            return;
        }

        clearBtn.classList.remove('hidden');

        if (resultsCounter) {
            resultsCounter.classList.remove('hidden');
        }

        if (noProductsFound) {
            if (visibleCount === 0) {
                noProductsFound.classList.remove('hidden');
            } else {
                noProductsFound.classList.add('hidden');
            }
        }
    }

    window.clearSearch = function () {
        input.value = '';
        filterProducts('');
        input.focus();
    };

    input.addEventListener('input', function () {
        filterProducts(this.value);
    });

    clearBtn.addEventListener('click', function () {
        clearSearch();
    });
});
</script>
@endpush