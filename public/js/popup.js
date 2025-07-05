document.addEventListener('DOMContentLoaded', function () {
//   const API   = '/api';
//   const token = localStorage.getItem('carbie_token');
//   if (!token) {
//     alert('Silakan login terlebih dahulu.');
//     return window.close();
//   }

document.getElementById('save-button').addEventListener('click', function () {
    const newNickname = document.getElementById('nickname-input').value;
    document.getElementById('nickname').textContent = newNickname + " (Level xx)";
    fetch(`${API}/user/profile`, {
      method: 'PUT',
      headers: {
        'Authorization': 'Bearer ' + token,
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ name: newNickname })
    }).catch(console.error);
    // reload parent dan tutup
    if (window.opener) {
      window.opener.location.reload();
      window.close();
    }
});

document.querySelectorAll('.tab-button').forEach(button => {
    button.addEventListener('click', function() {
 
        document.querySelectorAll('.tab-button').forEach(btn => {
            btn.classList.remove('active');
        });

        this.classList.add('active');


        document.querySelectorAll('.tab-content').forEach(content => {
            content.style.display = 'none';
        });

        const tabId = 'tab-' + this.dataset.tab;
        document.getElementById(tabId).style.display = 'block';

        applyTheme(currentTheme);


        document.querySelectorAll('.tab-button').forEach(btn => {
            const icon = btn.querySelector('.icon-sidebar');
            const iconBase = btn.dataset.icon;
            if (icon && iconBase) {
                if (btn.classList.contains('active')) {
                    icon.src = `../image/${iconBase}-Secondary.svg`;
                } else {
                    icon.src = `../image/${iconBase}-Primary.svg`;
                }
            }
        });
    });
});


document.getElementById('save-button').addEventListener('click', function() {
    const newNickname = document.getElementById('nickname-input').value;
    document.getElementById('nickname').textContent = newNickname + " (Level xx)";
});

document.getElementById('color-select').addEventListener('change', function () {
    const selectedColor = this.value;
    let colorHex;

    switch (selectedColor) {
        case 'green': colorHex = '#3d6f40'; break;
        case 'grey': colorHex = '#999'; break;
        case 'blue': colorHex = '#3a7bd5'; break;
        case 'pink': colorHex = '#ff6fa5'; break;
        case 'purple': colorHex = '#9b59b6'; break;
        case 'yellow': colorHex = '#f1c40f'; break;
        default: colorHex = '#3d6f40';
    }

    document.querySelector('.img-profile').style.backgroundColor = colorHex;

    fetch(`${API}/user/profile`, {
      method: 'PUT',
      headers: {
        'Authorization': 'Bearer ' + token,
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ profile_color: selectedColor })
    }).catch(console.error);

});

document.getElementById('color-select').addEventListener('change', function () {
    const selectedColor = this.value;
    const profileImage = document.querySelector('.img-profile');
    
    // Ubah sumber gambar sesuai warna yang dipilih
    profileImage.src = `../image/Carbie-${capitalizeFirstLetter(selectedColor)}.png`;
    
    // Hapus style background color yang mungkin ada sebelumnya
    profileImage.style.backgroundColor = 'transparent';
});

// Fungsi helper untuk mengkapitalisasi huruf pertama
function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

let currentTheme = 'green'; // default
let themeSettings = {};

const themes = {
    green:  { bg: '#dff5d0', main: '#3d6f40', hover: '#2f5732', text: '#2e4d32' },
    grey:   { bg: '#e3e3e3', main: '#666666', hover: '#4a4a4a', text: '#333333' },
    blue:   { bg: '#d4eaff', main: '#3a7bd5', hover: '#2a6ac2', text: '#1d3b76' },
    pink:   { bg: '#ffe0ea', main: '#ff6fa5', hover: '#e75c92', text: '#7e3559' },
    purple: { bg: '#f3e6ff', main: '#9b59b6', hover: '#823f9e', text: '#5e2d76' },
    yellow: { bg: '#fff5cc', main: '#f1c40f', hover: '#d4aa00', text: '#7c6a00' },
    mint:   { bg: '#d4f9f3', main: '#57c9a7', hover: '#45ae91', text: '#246b5e' },
    peach:  { bg: '#ffe6d5', main: '#ff9e80', hover: '#e8866a', text: '#994d39' },

};


function applyTheme(themeName) {
    const selected = themes[themeName];
    themeSettings = selected;
    currentTheme = themeName;

    document.querySelector('.sidebar').style.backgroundColor = selected.bg;

    document.body.style.color = selected.text;

    document.querySelectorAll('.tab-button').forEach(btn => {
        if (btn.classList.contains('active')) {
            btn.style.backgroundColor = selected.main;
            btn.style.color = 'white';
        } else {
            btn.style.backgroundColor = 'transparent';
            btn.style.color = selected.text;
        }
    });


    document.querySelectorAll('.tab-button').forEach(btn => {
        const icon = btn.querySelector('.icon-sidebar');
        const iconBase = btn.dataset.icon;
        if (icon && iconBase) {
            if (btn.classList.contains('active')) {
                icon.src = `../image/${iconBase}-Secondary.svg`; 
            } else {
                icon.src = `../image/${iconBase}-Primary.svg`; 
            }
        }
    });

    // Profil
    document.querySelectorAll('.img-profile').forEach(img => {
        img.style.backgroundColor = selected.main;
    });

    // Semua tombol
    document.querySelectorAll('#save-button, .save-button-password, .gold-btn').forEach(btn => {
        btn.style.backgroundColor = selected.main;
        btn.style.color = 'white';
        btn.onmouseover = () => btn.style.backgroundColor = selected.hover;
        btn.onmouseout = () => btn.style.backgroundColor = selected.main;
    });

//     // Ganti warna ikon sidebar sesuai tema
// document.querySelectorAll('.icon-sidebar:not(.logout-icon)').forEach(icon => {
//     icon.style.filter = `invert(1) sepia(1) saturate(1000%) hue-rotate(0deg) brightness(0.7)`;
//     icon.style.filter = `brightness(0) saturate(100%) sepia(1) hue-rotate(120deg)`; // Optional tone
// });

    // Nickname text
const nicknameEl = document.getElementById('nickname');
if (nicknameEl) {
    nicknameEl.style.color = selected.text;
}

const arrowSvg = encodeURIComponent(`<svg fill='${selected.text}' height='16' viewBox='0 0 24 24' width='16' xmlns='http://www.w3.org/2000/svg'><path d='M7 10l5 5 5-5z'/></svg>`);
const dropdowns = document.querySelectorAll('.dropdown-color, .theme-dropdown');
dropdowns.forEach(drop => {
    drop.style.backgroundImage = `url("data:image/svg+xml,${arrowSvg}")`;
});

document.querySelectorAll('.theme-dropdown, .dropdown-color').forEach(drop => {
    drop.style.backgroundColor = selected.bg;
    drop.style.borderColor = selected.main;
    drop.style.color = selected.text;
});

}

document.getElementById('theme-select').addEventListener('change', function () {
    applyTheme(this.value);
});


document.getElementById('save-electricity').addEventListener('click', function() {
    const provider = document.getElementById('electricity-input').value;
    if(provider) {
        alert(`Electricity provider set to: ${provider}`);
    }
});


document.getElementById('language-select').addEventListener('change', function() {

    console.log(`Language changed to: ${this.value}`);
});


document.getElementById('timezone-select').addEventListener('change', function() {
    console.log(`Timezone changed to: ${this.value}`);
});

document.getElementById('save-button').addEventListener('click', function() {
    const newNickname = document.getElementById('nickname-input').value;
    if (newNickname.trim() !== '') {
        document.getElementById('nickname').textContent = newNickname + " (Level xx)";
        
        // Simpan ke localStorage
        localStorage.setItem('userNickname', newNickname);
        
        // Beri feedback ke user
        alert('Nickname updated successfully!');
        
        // Jika popup adalah window terpisah, tutup setelah menyimpan
        if (window.opener) {
            window.opener.location.reload(); // Refresh home2.html
            window.close();
        }
    } else {
        alert('Please enter a valid nickname');
    }
});

// Saat halaman dimuat, isi input dengan nilai yang tersimpan
document.getElementById('save-button').addEventListener('click', function() {
    const newNickname = document.getElementById('nickname-input').value;
    if (newNickname.trim() !== '') {
        // Simpan nickname ke localStorage
        localStorage.setItem('userNickname', newNickname);
        
        // Simpan warna tema yang dipilih
        const selectedColor = document.getElementById('color-select').value;
        localStorage.setItem('userProfileColor', selectedColor);
        
        alert('Profil berhasil diperbarui!');
        if (window.opener) {
            window.opener.location.reload(); // Refresh home2.html
            window.close();
        }
    } else {
        alert('Masukkan nickname yang valid.');
    }
});

// Di popup.js
document.getElementById('save-button').addEventListener('click', function() {
    if (window.opener) {
        window.opener.location.reload(); // Refresh home2.html
        window.close();
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const savedNickname = localStorage.getItem('userNickname');
    if (savedNickname) {
        document.getElementById('nickname-input').value = savedNickname;
    }
});

// Saat popup dibuka, isi nilai dari localStorage
document.addEventListener('DOMContentLoaded', function() {
    // Load nickname
    const savedNickname = localStorage.getItem('userNickname');
    if (savedNickname) {
        document.getElementById('nickname-input').value = savedNickname;
        document.getElementById('nickname').textContent = savedNickname + " (Level xx)";
    }

    // Load warna profil
    const savedColor = localStorage.getItem('userProfileColor');
    if (savedColor) {
    // Pastikan warna ada di daftar yang valid
    const validColors = ['green', 'blue', 'grey', 'pink', 'purple', 'yellow'];
    if (validColors.includes(savedColor)) {
        document.getElementById('color-select').value = savedColor;
        updateProfileImage(savedColor);
    }
}
});

// Fungsi update gambar profil
function updateProfileImage(color) {
    const profileImg = document.querySelector('.img-profile');
    // Pilih salah satu:
    profileImg.src = `image/Carbie-${capitalizeFirstLetter(color)}.png`; // Relatif terhadap HTML
    // atau:
    profileImg.src = `image/Carbie-${capitalizeFirstLetter(color)}.png`; // Absolute dari root
} 

document.getElementById('save-button').addEventListener('click', function() {
    const newNickname = document.getElementById('nickname-input').value;
    const selectedColor = document.getElementById('color-select').value;

    if (newNickname.trim() !== '') {
        // Simpan ke localStorage
        localStorage.setItem('userNickname', newNickname);
        localStorage.setItem('userProfileColor', selectedColor);

        // Paksa home2.html update
        if (window.opener) {
            window.opener.location.reload();
        }
        window.close(); // Tutup popup
    } else {
        alert("Nickname tidak boleh kosong!");
    }
});


fetch(`${API}/user/profile`, {
    headers: { 'Authorization': 'Bearer ' + token }
  })
  .then(r => r.json())
  .then(json => {
    const d = json.data;
    document.getElementById('nickname-input').value = d.name;
    document.getElementById('nickname').textContent    = `${d.name} (Level ${d.level})`;
    document.getElementById('color-select').value      = d.profile_color;
    currentTheme = d.profile_color;
    applyTheme(currentTheme);
  })
  .catch(console.error);
});