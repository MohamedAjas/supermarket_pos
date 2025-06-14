/**
 * public/js/pos_script.js
 * Contains JavaScript logic specific to the POS interface (seller/pos.php).
 * Handles product search, adding/removing items from the cart,
 * quantity updates, total calculations, and payment processing.
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('pos_script.js loaded and DOM fully parsed.');

    const cartItemsContainer = document.getElementById('cart-items');
    const productCards = document.querySelectorAll('.product-card');
    const subtotalEl = document.getElementById('subtotal');
    const taxEl = document.getElementById('tax');
    const totalEl = document.getElementById('total');
    const amountPaidInput = document.getElementById('amount_paid');
    const changeDueEl = document.getElementById('change_due');
    const processSaleBtn = document.querySelector('.btn-process-sale');
    const cancelSaleBtn = document.querySelector('.btn-cancel-sale');
    const searchInput = document.querySelector('.search-bar input[type="text"]');


    let cart = []; // Array to store cart items: { id, name, price, quantity }
    const TAX_RATE = 0.05; // 5% tax

    // Function to render cart items
    function renderCart() {
        cartItemsContainer.innerHTML = ''; // Clear current cart display
        let currentSubtotal = 0;

        if (cart.length === 0) {
            cartItemsContainer.innerHTML = '<tr><td colspan="5" class="text-center text-muted py-3">Cart is empty. Add items from the left panel.</td></tr>';
        } else {
            cart.forEach(item => {
                const itemSubtotal = item.price * item.quantity;
                currentSubtotal += itemSubtotal;

                const row = `
                    <tr data-item-id="${item.id}">
                        <td class="item-name">${item.name}</td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center quantity-controls">
                                <button type="button" class="btn btn-sm btn-minus" data-item-id="${item.id}"><i class="fas fa-minus"></i></button>
                                <input type="number" class="form-control form-control-sm" value="${item.quantity}" min="1" data-item-id="${item.id}">
                                <button type="button" class="btn btn-sm btn-plus" data-item-id="${item.id}"><i class="fas fa-plus"></i></button>
                            </div>
                        </td>
                        <td class="text-end">$${item.price.toFixed(2)}</td>
                        <td class="text-end">$${itemSubtotal.toFixed(2)}</td>
                        <td><button type="button" class="remove-item-btn" data-item-id="${item.id}"><i class="fas fa-trash-alt"></i></button></td>
                    </tr>
                `;
                cartItemsContainer.innerHTML += row;
            });
        }
        updateSummary(currentSubtotal);
    }

    // Function to update summary totals
    function updateSummary(currentSubtotal) {
        const tax = currentSubtotal * TAX_RATE;
        const total = currentSubtotal + tax;
        subtotalEl.textContent = `$${currentSubtotal.toFixed(2)}`;
        taxEl.textContent = `$${tax.toFixed(2)}`;
        totalEl.textContent = `$${total.toFixed(2)}`;

        // Update change due based on amount paid
        updateChangeDue();
    }

    // Function to update change due
    function updateChangeDue() {
        const totalAmount = parseFloat(totalEl.textContent.replace('$', ''));
        const amountPaid = parseFloat(amountPaidInput.value) || 0;
        const change = amountPaid - totalAmount;
        changeDueEl.textContent = `$${Math.max(0, change).toFixed(2)}`;
        // Optionally add styling if change is negative or payment is insufficient
        changeDueEl.style.color = change < 0 ? '#dc3545' : '#28a745';
    }


    // Add event listeners for product cards (Add to Cart)
    productCards.forEach(card => {
        const addButton = card.querySelector('.add-to-cart-btn');
        addButton.addEventListener('click', function(event) {
            event.stopPropagation(); // Prevent card click event from firing on parent
            const itemId = card.dataset.itemId;
            const itemName = card.dataset.itemName;
            const itemPrice = parseFloat(card.dataset.itemPrice);

            addItemToCart(itemId, itemName, itemPrice);
        });
    });

    // Function to add item to cart
    function addItemToCart(id, name, price) {
        const existingItem = cart.find(item => item.id === id);
        if (existingItem) {
            existingItem.quantity++;
        } else {
            cart.push({ id, name, price, quantity: 1 });
        }
        renderCart();
    }

    // Event delegation for cart item actions (quantity change, remove)
    cartItemsContainer.addEventListener('click', function(event) {
        const target = event.target;
        const row = target.closest('tr');
        if (!row) return; // Ensure a table row was clicked within the container

        const itemId = row.dataset.itemId;

        if (target.closest('.btn-minus')) {
            updateItemQuantity(itemId, -1);
        } else if (target.closest('.btn-plus')) {
            updateItemQuantity(itemId, 1);
        } else if (target.closest('.remove-item-btn')) {
            removeItemFromCart(itemId);
        }
    });

    cartItemsContainer.addEventListener('change', function(event) {
        const target = event.target;
        if (target.tagName === 'INPUT' && target.type === 'number') {
            const itemId = target.dataset.itemId;
            const newQuantity = parseInt(target.value);
            if (newQuantity >= 1) {
                 const itemIndex = cart.findIndex(item => item.id === itemId);
                 if (itemIndex !== -1) {
                     cart[itemIndex].quantity = newQuantity;
                     renderCart();
                 }
            } else {
                // If quantity becomes less than 1, remove the item
                removeItemFromCart(itemId);
            }
        }
    });

    // Function to update item quantity in cart
    function updateItemQuantity(id, change) {
        const itemIndex = cart.findIndex(item => item.id === id);
        if (itemIndex !== -1) {
            cart[itemIndex].quantity += change;
            if (cart[itemIndex].quantity <= 0) {
                cart.splice(itemIndex, 1); // Remove if quantity is 0 or less
            }
            renderCart();
        }
    }

    // Function to remove item from cart
    function removeItemFromCart(id) {
        cart = cart.filter(item => item.id !== id);
        renderCart();
    }

    // Event listener for amount paid input to update change due
    amountPaidInput.addEventListener('input', updateChangeDue);

    // Handle Process Sale Button
    processSaleBtn.addEventListener('click', function() {
        if (cart.length === 0) {
            // Using global showCustomAlert from main.js
            window.showCustomAlert('Cart is empty. Please add items to process a sale.', 'warning');
            return;
        }
        const totalAmount = parseFloat(totalEl.textContent.replace('$', ''));
        const amountPaid = parseFloat(amountPaidInput.value) || 0;

        if (amountPaid < totalAmount) {
            // Using global showCustomAlert from main.js
            window.showCustomAlert('Amount paid is less than total. Please collect full amount.', 'error');
            return;
        }

        const paymentMethod = document.getElementById('payment_method').value;
        const changeGiven = Math.max(0, amountPaid - totalAmount);

        // Here, you would send this data to a PHP script (e.g., ../process_sale.php)
        const saleData = {
            cart: cart,
            total_amount: totalAmount,
            amount_paid: amountPaid,
            change_given: changeGiven,
            payment_method: paymentMethod
        };

        console.log('Processing Sale:', saleData);
        // Using global showCustomAlert for success
        window.showCustomAlert('Sale processed successfully! Change: $' + changeGiven.toFixed(2), 'success');

        // Reset cart and UI after successful sale
        cart = [];
        amountPaidInput.value = '';
        renderCart(); // Re-render to clear cart
    });

    // Handle Cancel Sale Button
    cancelSaleBtn.addEventListener('click', function() {
        // Using global showCustomAlert for confirmation (would be a custom modal in production)
        if (confirm('Are you sure you want to cancel this sale? All items in the cart will be removed.')) {
            cart = [];
            amountPaidInput.value = '';
            renderCart();
            window.showCustomAlert('Sale cancelled.', 'info');
        }
    });

    // Initial render of cart (with dummy data for display)
    renderCart();


    // --- Product Search/Filter Logic ---
    searchInput.addEventListener('input', function() {
        const searchTerm = searchInput.value.toLowerCase();
        productCards.forEach(card => {
            const itemName = card.dataset.itemName.toLowerCase();
            const itemSku = card.dataset.itemSku ? card.dataset.itemSku.toLowerCase() : ''; // Assuming SKU and Barcode might be added later
            const itemBarcode = card.dataset.itemBarcode ? card.dataset.itemBarcode.toLowerCase() : '';

            if (itemName.includes(searchTerm) || itemSku.includes(searchTerm) || itemBarcode.includes(searchTerm)) {
                card.style.display = 'flex'; // Show the card
            } else {
                card.style.display = 'none'; // Hide the card
            }
        });
    });

});
