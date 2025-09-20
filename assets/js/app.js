function addItem() {
    const div = document.getElementById('items');
    const idx = div.children.length;
    const html = `
    <div class="card item">
        <input type="text" name="item[${idx}][descripcion]" placeholder="Descripción" required>
        <input type="number" step="0.01" name="item[${idx}][cantidad]" placeholder="Cantidad" oninput="updateItemTotal(this)" required>
        <input type="number" step="0.01" name="item[${idx}][precio]" placeholder="Precio" oninput="updateItemTotal(this)" required>
        <input type="text" name="item[${idx}][total]" placeholder="Total" readonly>
    </div>`;
    div.insertAdjacentHTML('beforeend', html);
}

function updateItemTotal(el) {
    const card = el.closest('.item');
    const cantidad = parseFloat(card.querySelector('[name*="[cantidad]"]').value) || 0;
    const precio = parseFloat(card.querySelector('[name*="[precio]"]').value) || 0;
    const total = cantidad * precio;
    card.querySelector('[name*="[total]"]').value = total.toFixed(2);
}
