<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - Bong Boost</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #0f172a; color: #f8fafc; padding-bottom: 80px; }
        .accordion-item { background: #1e293b; border: 1px solid #334155; color: #fff; }
        .accordion-button { background: #1e293b; color: #38bdf8; font-weight: bold; }
        .accordion-button:not(.collapsed) { background: #0284c7; color: #fff; }
    </style>
</head>
<body>
<div class="container my-4">
    <h3 class="text-center text-info fw-bold mb-4">Frequently Asked Questions (FAQ)</h3>
    <div class="accordion" id="faqAccordion">
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#q1">
                    Where does my money go if an order gets canceled?
                </button>
            </h2>
            <div id="q1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                <div class="accordion-body">If an order is canceled, the funds are automatically refunded to your Bong Boost wallet balance instantly.</div>
            </div>
        </div>
        <div class="accordion-item mt-2">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#q2">
                    How long does it take to process an order?
                </button>
            </h2>
            <div id="q2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                <div class="accordion-body">Most orders start instantly (within 1–10 minutes). Some services may take 30–60 minutes depending on server speed.</div>
            </div>
        </div>
        <div class="accordion-item mt-2">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#q3">
                    What if my payment is not added to my wallet?
                </button>
            </h2>
            <div id="q3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                <div class="accordion-body">Click on the WhatsApp Support button and send your Transaction ID. Your funds will be added manually within 5 minutes.</div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
