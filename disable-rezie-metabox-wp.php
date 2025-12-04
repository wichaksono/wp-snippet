<?php

add_action('admin_footer', function () {
	?>
	<style>
	.interface-interface-skeleton__content {display:block}
		.editor-visual-editor.edit-post-visual-editor.is-iframed,
		.admin-ui-navigable-region.components-resizable-box__container.has-show-handle.edit-post-meta-boxes-main,
		.edit-post-meta-boxes-main .edit-post-meta-boxes-main__liner {height:100% !important}
		
		.admin-ui-navigable-region.components-resizable-box__container.has-show-handle.edit-post-meta-boxes-main {
			padding-top:0 !important;
		}
	
		.edit-post-meta-boxes-main,
		.edit-post-meta-boxes-main .edit-post-meta-boxes-main__liner {overflow:unset!important}
		
		.edit-post-meta-boxes-main__presenter {display:none}
		
		.block-editor-iframe__scale-container iframe {
			height: 100vh !important; /* 100% tinggi viewport */
			display: block !important;
			overflow: unset !important; /* Menghilangkan scrollbar secara paksa */
		}
	
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Jeda 100ms
    setTimeout(function() { 
        
        // 1. CARI WADAH UTAMA
        const mainContainer = document.querySelector('.editor-visual-editor');
        
        if (mainContainer) {
            // 2. CARI WADAH SKALA
            const scaleContainer = mainContainer.querySelector('.block-editor-iframe__scale-container');

            if (scaleContainer) {
                // 3. CARI IFRAME
                const iframe = scaleContainer.querySelector('iframe[name="editor-canvas"]');

                if (iframe) {
                    // Terapkan atribut scrolling="no"
                    iframe.scrolling = 'no';
                    
                    // 4. Tunggu hingga konten di dalam iframe selesai dimuat
                    iframe.onload = function() {
                        try {
                            // Hitung tinggi konten
                            const contentHeight = iframe.contentWindow.document.body.scrollHeight;
                            const heightValue = contentHeight + 'px';
                            
                            // 5. ATUR TINGGI WADAH UTAMA DENGAN !IMPORTANT
                            mainContainer.style.setProperty('height', heightValue, 'important'); 

                            // 6. ATUR TINGGI WADAH SKALA DENGAN !IMPORTANT
                            scaleContainer.style.setProperty('height', heightValue, 'important');

                            // 7. ATUR TINGGI IFRAME DENGAN !IMPORTANT
                            iframe.style.setProperty('height', heightValue, 'important'); 
                            
                        } catch (e) {
                            console.error("Gagal mengakses/resize konten iframe: Pelanggaran Same-Origin Policy.", e);
                        }
                    };

                    // Panggil onload secara manual jika iframe sudah selesai dimuat
                    if (iframe.contentWindow && iframe.contentWindow.document.readyState === 'complete') {
                        iframe.onload();
                    }

                } else {
                     console.error("Iframe dengan nama 'editor-canvas' tidak ditemukan di dalam container.");
                }
            } else {
                console.error("Container skala '.block-editor-iframe__scale-container' tidak ditemukan di dalam wadah utama.");
            }
        } else {
            console.error("Wadah utama '.block-editor-iframe__container' tidak ditemukan.");
        }
    }, 100); 
});
</script>
	<?php
});
