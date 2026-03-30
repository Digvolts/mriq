<script>
    const variantData = @json($variantData);
    const variantMap = {};
    
    variantData.forEach(variant => {
        variantMap[variant.size] = variant;
    });

    let selectedSize = '{{ $availableSizes[0] ?? "" }}';

    function getVariantFromDB() {
        return variantMap[selectedSize] || null;
    }

    function updateAllData() {
        const variant = getVariantFromDB();
        
        if (!variant) {
            document.getElementById('cartBtn').disabled = true;
            document.getElementById('buyBtn').disabled = true;
            document.getElementById('cartBtn').classList.add('opacity-50', 'cursor-not-allowed');
            document.getElementById('buyBtn').classList.add('opacity-50', 'cursor-not-allowed');
            return;
        }

        document.getElementById('cartBtn').disabled = false;
        document.getElementById('buyBtn').disabled = false;
        document.getElementById('cartBtn').classList.remove('opacity-50', 'cursor-not-allowed');
        document.getElementById('buyBtn').classList.remove('opacity-50', 'cursor-not-allowed');

        const displayPrice = variant.discount_price && variant.discount_price > 0 
            ? variant.discount_price 
            : variant.price;
        
        document.getElementById('productPrice').textContent = 
            'Rp' + displayPrice.toLocaleString('id-ID');
        document.getElementById('hargaSpec').textContent = 
            'Rp' + displayPrice.toLocaleString('id-ID');

        if (variant.image) {
            document.getElementById('mainImage').src = '/storage/' + variant.image;
        }

        document.getElementById('descriptionTab').textContent = variant.description || '-';
        document.getElementById('descriptionBox').textContent = variant.description || '-';

        document.getElementById('bahanSpec').textContent = variant.bahan || '-';
        document.getElementById('styleSpec').textContent = variant.style || '-';
        document.getElementById('printingSpec').textContent = variant.printing_design || '-';
        document.getElementById('merchandiseSpec').textContent = variant.exclusive_mercendise || '-';
        document.getElementById('pengirimanSpec').textContent = variant.pengiriman || '-';

        const stock = variant.stock;
        document.getElementById('currentStock').textContent = stock;
        document.getElementById('maxStockDisplay').textContent = stock;
        document.getElementById('totalStockSpec').textContent = stock + ' unit';
        document.getElementById('quantity').max = stock;

        if (stock > 0) {
            document.getElementById('stockStatus').innerHTML = `
                <span class="px-3 py-1 bg-emerald-100 text-emerald-800 text-sm font-semibold rounded-full inline-block">
                    <i class="fas fa-check-circle mr-1"></i>Dapat Dibeli
                </span>
                ${stock <= 5 ? `<p class="text-xs text-red-600 mt-2 font-semibold"><i class="fas fa-warning mr-1"></i>Stok Terbatas!</p>` : ''}
            `;
        } else {
            document.getElementById('stockStatus').innerHTML = `
                <span class="px-3 py-1 bg-red-100 text-red-800 text-sm font-semibold rounded-full inline-block">
                    <i class="fas fa-times-circle mr-1"></i>Stok Habis
                </span>
            `;
            document.getElementById('cartBtn').disabled = true;
            document.getElementById('buyBtn').disabled = true;
            document.getElementById('cartBtn').classList.add('opacity-50', 'cursor-not-allowed');
            document.getElementById('buyBtn').classList.add('opacity-50', 'cursor-not-allowed');
        }

        const currentQty = parseInt(document.getElementById('quantity').value);
        if (currentQty > stock) {
            document.getElementById('quantity').value = 1;
            updateQuantityInput();
        }
    }

    function increaseQty() {
        const qty = document.getElementById('quantity');
        const variant = getVariantFromDB();
        const currentQty = parseInt(qty.value);
        
        if (!variant) {
            return;
        }
        
        if (currentQty < variant.stock) {
            qty.value = currentQty + 1;
            updateQuantityInput();
        }
    }

    function decreaseQty() {
        const qty = document.getElementById('quantity');
        if (parseInt(qty.value) > 1) {
            qty.value = parseInt(qty.value) - 1;
            updateQuantityInput();
        }
    }

    function updateQuantityInput() {
        const qty = parseInt(document.getElementById('quantity').value);
        document.getElementById('quantityInput').value = qty;
    }

    function selectSize(button) {
        document.querySelectorAll('[id^="size-"]').forEach(opt => {
            opt.classList.remove('selected');
        });
        button.classList.add('selected');

        selectedSize = button.dataset.size;
        document.getElementById('sizeInput').value = selectedSize;

        updateAllData();
    }

    function switchTab(button, tabName) {
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.add('hidden');
        });
        document.querySelectorAll('[onclick*="switchTab"]').forEach(btn => {
            btn.classList.remove('border-b-2', 'border-gray-900', 'text-gray-900');
            btn.classList.add('text-gray-600');
        });
        
        document.getElementById(tabName).classList.remove('hidden');
        button.classList.remove('text-gray-600');
        button.classList.add('border-b-2', 'border-gray-900', 'text-gray-900');
    }

    function showError(message) {
        document.getElementById('errorAlert').classList.remove('hidden');
        document.getElementById('errorMessage').textContent = message;
    }

    function hideError() {
        document.getElementById('errorAlert').classList.add('hidden');
    }

    function submitFormAndBuy() {
        const variant = getVariantFromDB();
        const qty = parseInt(document.getElementById('quantity').value);
        
        if (!variant || variant.stock <= 0) {
            return;
        }

        if (qty > variant.stock) {
            showError('Stok tidak cukup');
            return;
        }

        document.getElementById('addToCartForm').submit();
    }

    document.getElementById('wishlistBtn').addEventListener('click', function() {
        this.classList.toggle('far');
        this.classList.toggle('fas');
        this.classList.toggle('text-red-500');
    });

    window.addEventListener('load', function() {
        updateAllData();
        updateQuantityInput();
    });
</script>