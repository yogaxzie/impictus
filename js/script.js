// Tab Login/Register
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.auth-tab');
    const forms = document.querySelectorAll('.auth-form');
    
    if (tabs.length > 0) {
        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const target = this.dataset.tab;
                
                tabs.forEach(t => t.classList.remove('active'));
                forms.forEach(f => f.classList.remove('active'));
                
                this.classList.add('active');
                document.getElementById(target).classList.add('active');
            });
        });
    }
    
    // Premium Modal
    const premiumLink = document.getElementById('get-premium');
    const premiumModal = document.getElementById('premium-modal');
    const closeModal = document.querySelector('.close-modal');
    
    if (premiumLink) {
        premiumLink.addEventListener('click', function(e) {
            e.preventDefault();
            premiumModal.classList.add('active');
        });
    }
    
    if (closeModal) {
        closeModal.addEventListener('click', function() {
            premiumModal.classList.remove('active');
        });
    }
    
    // Price selection
    const priceItems = document.querySelectorAll('.price-item');
    const buyBtn = document.getElementById('buy-premium');
    
    if (priceItems.length > 0) {
        priceItems.forEach(item => {
            item.addEventListener('click', function() {
                priceItems.forEach(i => i.classList.remove('selected'));
                this.classList.add('selected');
            });
        });
    }
    
    if (buyBtn) {
        buyBtn.addEventListener('click', function() {
            const selected = document.querySelector('.price-item.selected');
            if (selected) {
                const duration = selected.dataset.duration;
                const price = selected.dataset.price;
                const user = document.querySelector('.info-item .value').textContent;
                
                window.open(`https://wa.me/6281234567890?text=Halo%20saya%20mau%20beli%20premium%20${duration}%20dengan%20harga%20Rp${price}%20untuk%20user%20${user}`, '_blank');
            } else {
                alert('Pilih paket dulu tod!');
            }
        });
    }
    
    // Website Builder
    const languageSelect = document.getElementById('language');
    const codeEditor = document.getElementById('code-editor');
    const generateBtn = document.getElementById('generate-website');
    const previewFrame = document.getElementById('preview-frame');
    const urlResult = document.getElementById('url-result');
    
    if (languageSelect) {
        const templates = {
            'html': '<!DOCTYPE html>\n<html>\n<head>\n    <title>My Website</title>\n    <style>\n        body { font-family: Arial; }\n    </style>\n</head>\n<body>\n    <h1>Hello World!</h1>\n</body>\n</html>',
            'python': 'print("Hello World!")\n\ndef main():\n    print("Website Python")\n\nif __name__ == "__main__":\n    main()',
            'php': '<?php\n    echo "Hello World!";\n    \n    $name = "DxiEPro";\n    echo "Welcome to " . $name;\n?>'
        };
        
        languageSelect.addEventListener('change', function() {
            codeEditor.value = templates[this.value] || '';
        });
        
        // Trigger change untuk set default
        languageSelect.dispatchEvent(new Event('change'));
    }
    
    if (generateBtn) {
        generateBtn.addEventListener('click', function() {
            const language = languageSelect.value;
            const code = codeEditor.value;
            const websiteName = document.getElementById('website-name').value || 'MyWebsite';
            
            // Generate random URL
            const randomId = Math.random().toString(36).substring(2, 8);
            const isPremium = document.body.dataset.premium === 'true';
            const domain = isPremium ? 'example.vip.id' : 'dxiepro.it';
            const url = `https://${domain}/${websiteName.toLowerCase().replace(/\s+/g, '-')}-${randomId}`;
            
            urlResult.innerHTML = `<strong>URL Hasil:</strong> <a href="${url}" target="_blank">${url}</a>`;
            
            // Generate preview
            let previewContent = '';
            if (language === 'html') {
                previewContent = code;
            } else if (language === 'python') {
                previewContent = `<pre>${code}</pre><p><i>Output Python akan muncul di console/server</i></p>`;
            } else if (language === 'php') {
                previewContent = `<pre>&lt;?php\n${code}\n?&gt;</pre>`;
            }
            
            const previewDoc = previewFrame.contentDocument || previewFrame.contentWindow.document;
            previewDoc.open();
            previewDoc.write(previewContent);
            previewDoc.close();
            
            // Show temp code
            document.querySelector('.temp-code').innerHTML = `<strong>Tempel Code Disini:</strong><br><pre>${code.substring(0, 200)}${code.length > 200 ? '...' : ''}</pre>`;
        });
    }
});
