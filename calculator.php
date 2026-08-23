<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Price Calculator - Bong Boost</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #0f172a; color: #fff; padding-bottom: 80px; }
        .calc-box { background: #1e293b; border-radius: 15px; border: 1px solid #334155; padding: 25px; }
    </style>
</head>
<body>
<div class="container my-5" style="max-width: 450px;">
    <div class="calc-box shadow">
        <h4 class="text-center text-warning fw-bold mb-3">🧮 Order Cost Calculator</h4>
        <div class="mb-3">
            <label class="form-label">Service Rate Per 1000 (₹):</label>
            <input type="number" id="rate" class="form-control bg-dark text-light" placeholder="e.g. 20">
        </div>
        <div class="mb-3">
            <label class="form-label">Required Quantity:</label>
            <input type="number" id="qty" class="form-control bg-dark text-light" placeholder="e.g. 500">
        </div>
        <hr>
        <h5 class="text-center text-info">Total Cost: ₹ <span id="total">0.00</span></h5>
    </div>
</div>
<script>
    const rateInput = document.getElementById('rate');
    const qtyInput = document.getElementById('qty');
    const totalOutput = document.getElementById('total');

    function calculate() {
        const rate = parseFloat(rateInput.value) || 0;
        const qty = parseFloat(qtyInput.value) || 0;
        const total = (rate / 1000) * qty;
        totalOutput.innerText = total.toFixed(2);
    }
    rateInput.addEventListener('input', calculate);
    qtyInput.addEventListener('input', calculate);
</script>
</body>
</html>
