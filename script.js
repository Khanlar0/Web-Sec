document.addEventListener("DOMContentLoaded", function() {
    
    // --- SEPET SİSTEMİ ---
    let sepet = [];
    const sepetListesi = document.getElementById('sepet-listesi');
    const toplamTutarYazisi = document.getElementById('toplam-tutar');
    const cartBtn = document.getElementById('cartBtn');
    const cartPanel = document.getElementById('cartPanel');
    const cartCount = document.getElementById('cart-count');

    // Sepet panelini açıp kapatma düğmesi
    cartBtn.addEventListener('click', function() {
        cartPanel.classList.toggle('goster');
    });

    const ekleButonlari = document.querySelectorAll('.sepete-ekle');

    ekleButonlari.forEach(function(buton) {
        buton.addEventListener('click', function() {
            const isim = this.getAttribute('data-isim');
            const fiyat = parseInt(this.getAttribute('data-fiyat'));
            const img = this.getAttribute('data-img');
            
            // Silme işlemi için her ürüne o anki saniyeye göre benzersiz bir ID veriyoruz
            const urunId = Date.now(); 

            sepet.push({ id: urunId, isim: isim, fiyat: fiyat, img: img });
            
            cartPanel.classList.add('goster'); // Ürün eklenince sepeti otomatik aç
            sepetArayuzunuGuncelle();
        });
    });

    // Sepet arayüzünü güncelleyen ve Silme butonlarını oluşturan fonksiyon
    function sepetArayuzunuGuncelle() {
        sepetListesi.innerHTML = ""; 
        let toplamTutar = 0;
        
        if (sepet.length === 0) {
            sepetListesi.innerHTML = "<li class='sepet-bos'>Sepətiniz hazırda boşdur.</li>";
            cartCount.innerText = "(0)";
        } else {
            cartCount.innerText = "(" + sepet.length + ")";

            sepet.forEach(function(urun) {
                toplamTutar += urun.fiyat;

                const li = document.createElement('li');
                li.className = 'cart-item';
                
                li.innerHTML = `
                    <div class="cart-item-sol">
                        <img src="${urun.img}" class="cart-item-img">
                        <div>
                            <p class="cart-item-isim">${urun.isim}</p>
                            <p class="cart-item-fiyat">${urun.fiyat} AZN</p>
                        </div>
                    </div>
                    <button class="sil-btn" onclick="urunuSil(${urun.id})">Sil</button>
                `;
                sepetListesi.appendChild(li);
            });
        }
        toplamTutarYazisi.innerText = "Cəmi: " + toplamTutar + " AZN";
    }

    // Ürün silme fonksiyonunu HTML içinden çağırılabilmesi için global objeye (window) ekliyoruz
    window.urunuSil = function(id) {
        // Sepetteki id'si silinmek istenen id'ye eşit olmayanları tut, diğerini çıkar
        sepet = sepet.filter(function(urun) {
            return urun.id !== id;
        });
        sepetArayuzunuGuncelle();
    };

    // --- DESTEK MODALI İŞLEMLERİ ---
    const modal = document.getElementById("destekModal");
    const destekBtn = document.getElementById("destekBtn");
    const kapatBtn = document.getElementsByClassName("kapat")[0];
    const gonderBtn = document.getElementById("sorunGonder");
    const sorunMetni = document.getElementById("sorunMetni");

    destekBtn.onclick = function() { modal.style.display = "block"; }
    kapatBtn.onclick = function() { modal.style.display = "none"; }
    window.onclick = function(event) {
        if (event.target == modal) { modal.style.display = "none"; }
    }
    gonderBtn.onclick = function() {
        if(sorunMetni.value.trim() === "") {
            alert("Zəhmət olmasa probleminizi yazın.");
        } else {
            alert("Mesajınız bizə çatdı! Qısa zamanda geri dönüş ediləcək.");
            sorunMetni.value = ""; 
            modal.style.display = "none"; 
        }
    }
// --- SAHTE "DAHA ÇOX" BUTONU İŞLEMLERİ ---
    const dahaCoxBtn = document.getElementById("dahaCoxBtn");
    
    if (dahaCoxBtn) {
        dahaCoxBtn.addEventListener("click", function() {
            // Butona tıklandığında yükleniyor moduna geç
            this.innerText = "⏳ Yüklənir...";
            this.classList.add("yuklenir");
            this.disabled = true; // Butona tekrar tıklanmasını engelle

            // Sanki internetten veri çekiyormuş gibi 1.5 saniye (1500 milisaniye) bekle
            setTimeout(() => {
                // Süre bitince mesajı değiştir
                this.innerText = "Bütün formalar göstərildi";
            }, 1500);
        });
    }

    // --- YENİ: SİFARİŞ ET BUTONU (MODERN BİLDİRİMLİ) ---
    const sifarisBtn = document.getElementById("sifarisBtn");

    if (sifarisBtn) {
        sifarisBtn.addEventListener("click", function() {
            if (sepet.length === 0) {
                alert("Sepətiniz boşdur! Zəhmət olmasa əvvəlcə məhsul əlavə edin.");
                return;
            }

            // Toplam tutarı hesapla
            let toplam = 0;
            sepet.forEach(urun => toplam += urun.fiyat);

            // PHP'ye gönderilecek veriyi hazırla
            const gonderilecekVeri = {
                sepet: sepet,
                toplam: toplam
            };

            // Butonu bekletme moduna al
            sifarisBtn.innerText = "Gözləyin...";
            sifarisBtn.disabled = true;

            // Arka planda veriyi PHP'ye gönder
            fetch('sifaris_qeyd.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(gonderilecekVeri)
            })
            .then(response => response.json())
            .then(data => {
                if(data.durum === "basarili") {
                    
                    // --- İŞTE YENİ ŞIK BİLDİRİM KISMI ---
                    sifarisBtn.innerText = "Sifarişiniz Alındı! 🎉";
                    sifarisBtn.style.backgroundColor = "#27ae60"; // Butonu başarı rengi olan yeşile çevir
                    sifarisBtn.style.color = "white";

                    // 2 saniye (2000 milisaniye) bekleyip sepeti temizle ve paneli kapat
                    setTimeout(() => {
                        sepet = [];
                        sepetArayuzunuGuncelle();
                        document.getElementById('cartPanel').classList.remove('goster');
                        
                        // Butonu bir sonraki sipariş için eski orijinal haline geri getir
                        sifarisBtn.innerText = "Sifariş Et ✅";
                        sifarisBtn.style.backgroundColor = ""; 
                        sifarisBtn.disabled = false;
                    }, 2000);

                } else {
                    sifarisBtn.innerText = "Xəta Baş Verdi ❌";
                    setTimeout(() => {
                        sifarisBtn.innerText = "Sifariş Et ✅";
                        sifarisBtn.disabled = false;
                    }, 2000);
                }
            })
            .catch(error => {
                console.error('Hata:', error);
                sifarisBtn.innerText = "Sifariş Et ✅";
                sifarisBtn.disabled = false;
            });
        });
    }
});