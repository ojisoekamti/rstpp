<script>
    let lastOrderId = null; // Track the last loaded order ID

    // Function to fetch the latest pending order
    function fetchLatestPendingOrder() {
        fetch('/api/get-latest-order')
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    console.log(data.message); // If no pending orders
                    return;
                }

                // Only display and print if the order is new (not the same as the last one)
                if (data.id !== lastOrderId) {
                    // Update lastOrderId to prevent reloading the same order
                    lastOrderId = data.id;

                    // Process and display the order
                    displayOrder(data);
                    // Auto-print the order bill
                    printOrderBill(data);
                } else {
                    console.log('Order already loaded, skipping...');
                }
            })
            .catch(error => console.error('Error fetching order:', error));
    }

    // Display the fetched order
    function displayOrder(order) {
        const orderList = document.getElementById('order-list');
        const orderElement = document.createElement('div');
        orderElement.classList.add('order-item');
        orderElement.innerHTML = `
            <div class="order-title">${order.customer_name} - Table: ${order.table_id}</div>
            <div>Phone: ${order.phone}</div>
            <div>Items:</div>
            <ul>
                ${order.items.map(item => `<li>${item.name} - ${item.quantity} x Rp ${item.price.toLocaleString()}</li>`).join('')}
            </ul>
            <div class="order-total">Total: Rp ${order.total_amount.toLocaleString()}</div>
        `;
        orderList.prepend(orderElement); // Add the new order at the top of the list
    }

    // Function to print the order bill
    function printOrderBill(order) {
        const orderDetails = `
        Order Bill
    Customer: ${order.customer_name}
    Table: ${order.table_id}
    Phone: ${order.phone}
    -------------------------
    Items: 
    ${order.items.map(item => `${item.name} x ${item.quantity}`).join('\n')}
    -------------------------
    Total: Rp ${order.total_amount.toLocaleString()}
        `;

        // Trigger print
        const printWindow = window.open('', '', 'width=600,height=400');
        printWindow.document.write('<pre>' + orderDetails + '</pre>');
        printWindow.document.close();
        printWindow.print();
    }

    // Call the function to fetch the latest pending order every 3 seconds
    setInterval(fetchLatestPendingOrder, 3000); // 3 seconds interval
</script>
