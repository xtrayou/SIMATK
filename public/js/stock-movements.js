/* stock-movements.js — Logika halaman movement stok */
let itemIndex = 0;
const currentType = window.MOVEMENT_CFG.currentType;
const searchEndpoint = window.MOVEMENT_CFG.searchEndpoint;
const searchTimers = {};
const searchCache = {};

function showAddModal() {
    document.getElementById('addMovementSection').style.display = 'block';
    if (itemIndex === 0) {
        addMovementItem(); // Add first item automatically
    }
}

function hideAddModal() {
    document.getElementById('addMovementSection').style.display = 'none';
    document.getElementById('movementItems').innerHTML = '';
    itemIndex = 0;
    document.getElementById('movementForm').reset();
}

function addMovementItem() {
    const itemHtml = `
    <div class="row mb-3 movement-item" id="item-${itemIndex}">
        <div class="col-md-4">
            <label class="form-label">Barang</label>
            <input type="text" class="form-control product-search" id="product-search-${itemIndex}" placeholder="Ketik nama/kode barang..." autocomplete="off" oninput="handleSearchInput(${itemIndex}, this.value)">
            <input type="hidden" name="movements[${itemIndex}][product_id]" id="product-id-${itemIndex}" required>
            <div class="list-group product-suggestions mt-1 d-none" id="product-suggestions-${itemIndex}"></div>
            <small class="text-muted" id="product-meta-${itemIndex}"></small>
        </div>
        <div class="col-md-2">
            <label class="form-label">${currentType === 'IN' ? 'Jml. Baik' : 'Jumlah'}</label>
            <input type="number" class="form-control" name="movements[${itemIndex}][quantity]" 
                   min="0" value="${currentType === 'IN' ? '0' : '1'}" required id="quantity-${itemIndex}">
            <small class="text-muted" id="stock-info-${itemIndex}"></small>
        </div>
        ${currentType === 'IN' ? `
        <div class="col-md-2">
            <label class="form-label text-danger">Jml. Rusak</label>
            <input type="number" class="form-control" name="movements[${itemIndex}][damaged_quantity]" 
                   min="0" value="0" id="damaged-${itemIndex}">
        </div>
        ` : ''}
        <div class="${currentType === 'IN' ? 'col-md-3' : 'col-md-5'}">
            <label class="form-label">Catatan</label>
            <input type="text" class="form-control" name="movements[${itemIndex}][notes]" 
                   placeholder="Catatan item">
        </div>
        <div class="col-md-1 d-flex align-items-end">
            <button type="button" class="btn btn-outline-danger w-100" onclick="removeMovementItem(${itemIndex})">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </div>
`;

    document.getElementById('movementItems').insertAdjacentHTML('beforeend', itemHtml);
    itemIndex++;
}

function removeMovementItem(index) {
    const row = document.getElementById(`item-${index}`);
    if (row) {
        row.remove();
    }
}

function escapeHtml(text) {
    return String(text || '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

function hideSuggestions(index) {
    const box = document.getElementById(`product-suggestions-${index}`);
    if (!box) {
        return;
    }

    box.classList.add('d-none');
    box.innerHTML = '';
}

function applyProductSelection(index, product) {
    const productInput = document.getElementById(`product-search-${index}`);
    const productIdInput = document.getElementById(`product-id-${index}`);
    const qtyInput = document.getElementById(`quantity-${index}`);
    const stockInfo = document.getElementById(`stock-info-${index}`);
    const productMeta = document.getElementById(`product-meta-${index}`);

    if (!productInput || !productIdInput || !qtyInput || !stockInfo || !productMeta) {
        return;
    }

    const currentStock = parseInt(product.current_stock || 0, 10) || 0;
    const unit = product.unit || 'Pcs';

    productInput.value = `${product.name} (${product.sku})`;
    productIdInput.value = product.id;
    productMeta.textContent = `Kode: ${product.sku} | Satuan: ${unit}`;

    if (currentType === 'OUT') {
        stockInfo.textContent = `Stok tersedia: ${currentStock}`;
        qtyInput.max = currentStock;
        if (currentStock === 0) {
            stockInfo.className = 'text-danger';
            qtyInput.disabled = true;
        } else {
            stockInfo.className = 'text-muted';
            qtyInput.disabled = false;
        }
    } else {
        stockInfo.textContent = `Stok saat ini: ${currentStock}`;
        stockInfo.className = 'text-muted';
        qtyInput.max = '';
        qtyInput.disabled = false;
    }

    hideSuggestions(index);
}

function renderSuggestions(index, items) {
    const box = document.getElementById(`product-suggestions-${index}`);
    if (!box) {
        return;
    }

    if (!items || items.length === 0) {
        hideSuggestions(index);
        return;
    }

    box.innerHTML = items.map((item) => {
        const label = `${item.name} (${item.sku})`;
        const stockText = currentType === 'OUT' ? ` | Stok: ${item.current_stock}` : '';
        return `<button type="button" class="list-group-item list-group-item-action" onclick="selectProduct(${index}, ${item.id})">${escapeHtml(label + stockText)}</button>`;
    }).join('');
    box.classList.remove('d-none');
}

function selectProduct(index, productId) {
    const key = String(productId);
    if (!searchCache[key]) {
        return;
    }

    applyProductSelection(index, searchCache[key]);
}

async function handleSearchInput(index, keyword) {
    const value = String(keyword || '').trim();
    const productIdInput = document.getElementById(`product-id-${index}`);
    if (productIdInput) {
        productIdInput.value = '';
    }

    if (value.length < 2) {
        hideSuggestions(index);
        return;
    }

    clearTimeout(searchTimers[index]);
    searchTimers[index] = setTimeout(async () => {
        try {
            const response = await fetch(`${searchEndpoint}?q=${encodeURIComponent(value)}&limit=10`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const json = await response.json();
            const items = (json && json.status && Array.isArray(json.data)) ? json.data : [];

            items.forEach((item) => {
                searchCache[String(item.id)] = item;
            });

            renderSuggestions(index, items);
        } catch (error) {
            hideSuggestions(index);
        }
    }, 250);
}

// Auto-hide alerts
document.addEventListener('DOMContentLoaded', function() {
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.movement-item')) {
            document.querySelectorAll('.product-suggestions').forEach((el) => {
                el.classList.add('d-none');
                el.innerHTML = '';
            });
        }
    });

    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            if (alert.classList.contains('alert-success')) {
                setTimeout(() => alert.remove(), 3000);
            }
        });
    }, 100);
});
