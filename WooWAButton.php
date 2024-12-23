<?php
class WhatsApp_Button {

    public function __construct() {
        // Tambahkan tombol WhatsApp di halaman produk tunggal.
        add_action('woocommerce_single_product_summary', array($this, 'add_whatsapp_button'), 35);
    }

    public function add_whatsapp_button() {
        global $product;

        // Ambil informasi produk.
        $product_name  = $product->get_name();
        $product_url   = get_permalink($product->get_id());
        $product_price = $product->get_price();
        $currency      = get_woocommerce_currency_symbol();

        // Tombol WhatsApp.
        echo '<div class="whatsapp-button">';
        echo '<a href="#" id="whatsapp-link" class="button whatsapp-btn">Order via WhatsApp</a>';
        echo '</div>';

        // Script untuk mengatur URL WhatsApp.
        echo "
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const whatsappButton = document.getElementById('whatsapp-link');
            const quantityInput = document.querySelector('.quantity input');

            // Update link WhatsApp saat tombol diklik.
            whatsappButton.addEventListener('click', function(e) {
                e.preventDefault();

                const quantity = quantityInput ? quantityInput.value : 1;
                const totalPrice = (quantity * {$product_price}).toFixed(2);
                const message = `Halo, saya ingin memesan:
- Produk: {$product_name}
- URL: {$product_url}
- Kuantitas: ${quantity}
- Harga Satuan: {$currency}{$product_price}
- Total Harga: {$currency}${totalPrice}`;

                const whatsappNumber = '628123456789'; // Ganti dengan nomor WhatsApp Anda.
                const whatsappURL = `https://wa.me/${whatsappNumber}?text=${encodeURIComponent(message)}`;
                
                window.open(whatsappURL, '_blank');
            });
        });
        </script>
        ";
    }
}

// Inisialisasi class.
new WhatsApp_Button();
