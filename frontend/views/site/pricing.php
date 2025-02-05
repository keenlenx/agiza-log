<?php
use yii\helpers\Html;
?>

<div class="price-info">
    <h1>Price Calculation Information</h1>

    <p>Here is how our pricing works for both <strong>Delivery</strong> and <strong>Moving</strong> services at <strong>Agiza.fi</strong>:</p>

    <div class="price-details">
        <h2>Delivery Pricing</h2>
        <p><strong>Distance:</strong> Calculated per kilometer, with a minimum of €35.00 for distances up to 15 km.</p>
        <p><strong>Price Multiplier:</strong> €1.50 per kilometer after 15 km.</p>
        <p><strong>Minimum Price:</strong> €35.00 for distances up to 15 km.</p>
        <p><strong>Final Price:</strong> The final price is calculated based on the distance, with a minimum of €35.00 for distances up to 15 km. For distances greater than 15 km, €1.50 per kilometer is added to the base price, plus VAT rate of 25.5% of the calculated price.</p>

        <h3>Example</h3>
        <p>If your delivery distance is <strong>20 km</strong>, the calculated price would be <strong>€35.00</strong> for the first 15 km, plus <strong>€7.50</strong> for the next 5 km (5 km × €1.50). With the 25% increase, the final price would be <strong>€53.44</strong> for this delivery.</p>
    </div>

    <div class="price-details">
        <h2>Moving Pricing</h2>
        <p><strong>Distance:</strong> Calculated per kilometer, with a minimum of €70.00 for distances up to 30 km.</p>
        <p><strong>Price Multiplier:</strong> €1.50 per kilometer after 30 km.</p>
        <p><strong>Minimum Price:</strong> €70.00 for distances up to 30 km.</p>
        <p><strong>Final Price:</strong> The final price is calculated based on the distance, with a minimum of €70.00 for distances up to 30 km. For distances greater than 30 km, €1.50 per kilometer is added to the base price, plus 25% of the calculated price.</p>

        <h3>Example</h3>
        <p>If your moving distance is <strong>35 km</strong>, the calculated price would be <strong>€70.00</strong> for the first 30 km, plus <strong>€7.50</strong> for the next 5 km (5 km × €1.50). With the 25% increase, the final price would be <strong>€96.88</strong> for this move.</p>
    </div>

    <p>If you would like a personalized price quote or need more information, please contact us directly.</p>

    <div class="contact-info">
        <h3>Contact Us</h3>
        <p>Email: <a href="mailto:info@agiza.fi">info@agiza.fi</a></p>
        <p>Phone: <a href="tel:+358452324052">+358 45 232 4052</a></p>
    </div>
</div>

<style>
    .price-info {
        width: 70%;
        margin: 0 auto;
        padding: 30px;
        background-color: #f9f9f9;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h1, h2, h3 {
        color: #333;
    }

    .price-details {
        margin-bottom: 30px;
        padding: 20px;
        background-color: #fff;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .price-details p {
        font-size: 1rem;
        line-height: 1.6;
    }

    .contact-info {
        margin-top: 30px;
        font-size: 1.2rem;
    }

    .contact-info a {
        color: #007bff;
        text-decoration: none;
    }

    .contact-info a:hover {
        text-decoration: underline;
    }
</style>
