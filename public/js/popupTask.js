const API   = '/api';
const token = localStorage.getItem('carbie_token') || '';
const hdr   = {
  'Authorization': 'Bearer ' + token,
  'Content-Type':  'application/json'
};


function setupPopupFunctionality() {
    // Tab switching functionality with event delegation
    document.addEventListener('click', function(e) {
        // Handle tab buttons
        if (e.target.closest('.tab-button')) {
            const button = e.target.closest('.tab-button');
            const tabButtons = document.querySelectorAll('.tab-button');
            
            // Remove active class from all buttons
            tabButtons.forEach(btn => btn.classList.remove('active'));
            
            // Add active class to clicked button
            button.classList.add('active');
            
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            
            // Show the selected tab content
            const tabId = 'tab-' + button.dataset.tab;
            document.getElementById(tabId).classList.add('active');
            
            // Update icons
            updateIcons();
        }
        
        
if (e.target.closest('.save-btn')) {
  const form  = e.target.closest('.tab-content');
  const inputs= form.querySelectorAll('select, input');
  const title = form.querySelector('#todolist').value || 'Untitled Task';

  // Disable input setelah save (seperti semula)
  inputs.forEach(i => i.disabled = true);
  e.target.disabled = true;

  // Build payload lama
  const taskData = { type: form.id.replace('tab-', ''), details:{ todolist:title } };
  inputs.forEach(i => { if (i.id) taskData.details[i.id] = i.value; });
  taskData.timestamp = new Date().toISOString();

  // Simpan ke localStorage (fungsi lama)
  const saved = JSON.parse(localStorage.getItem('savedTasks')) || [];
  saved.push(taskData);
  localStorage.setItem('savedTasks', JSON.stringify(saved));

  // ----- ⬇ SYNC KE BACKEND ⬇ -----
  fetch(`${API}/todos`, {
    method : 'POST',
    headers: hdr,
    body   : JSON.stringify({
      title  : taskData.details.todolist,
      type   : taskData.type,
      details: taskData.details
    })
  }).catch(console.error);
  // ----- ⬆ ------------------ ⬆ -----

  // Beri tahu parent & tutup popup (kode lama)
  window.opener?.postMessage('taskSaved', '*');
  window.close();
}

        // Handle cancel buttons
        if (e.target.closest('.cancel-btn')) {
            const button = e.target.closest('.cancel-btn');
            const form = button.closest('.tab-content');
            form.querySelectorAll('select, input').forEach(input => {
                input.value = '';
            });
        }
        
        // Handle place recommendation links
        if (e.target.closest('.place-recommendation')) {
            e.preventDefault();
            document.getElementById('confirmation-dialog').style.display = 'flex';
        }
        
        // Handle dialog buttons
        if (e.target.id === 'cancel-btn') {
            document.getElementById('confirmation-dialog').style.display = 'none';
        }
        
        if (e.target.id === 'go-btn') {
    // Tidak perlu alert lagi, langsung navigasi ke placerec.html
    document.getElementById('confirmation-dialog').style.display = 'none';
    // Navigasi akan dihandle oleh tag <a> secara alami
}
    });

    // Show/hide food options
    document.addEventListener('change', function(e) {
        if (e.target.id === 'stuff-type') {
            const foodOptions = document.querySelector('.food-options');
            foodOptions.style.display = e.target.value === 'food' ? 'block' : 'none';
        }
    });

    function updateIcons() {
        document.querySelectorAll('.tab-button').forEach(btn => {
            const icon = btn.querySelector('.icon-sidebar');
            if (btn.classList.contains('active')) {
                icon.src = icon.src.replace('-Primary.svg', '-Secondary.svg');
            } else {
                icon.src = icon.src.replace('-Secondary.svg', '-Primary.svg');
            }
        });
    }

    // Initialize first tab
    if (document.querySelector('.tab-button')) {
        document.querySelector('.tab-button').click();
    }
}

// Panggil fungsi setup saat dokumen siap
if (document.readyState === 'complete') {
    setupPopupFunctionality();
} else {
    document.addEventListener('DOMContentLoaded', setupPopupFunctionality);
}

// Dalam fungsi setupPopupFunctionality(), modifikasi bagian save button
if (e.target.closest('.save-btn')) {
    const button = e.target.closest('.save-btn');
    const form = button.closest('.tab-content');
    const inputs = form.querySelectorAll('select, input');
    
    // Get task title
    const taskTitle = form.querySelector('#todolist').value || 'Untitled Task';
    
    // Disable form after save
    inputs.forEach(input => {
        input.disabled = true;
    });
    button.disabled = true;
    
    // Collect task data
    const taskData = {
        type: form.id.replace('tab-', ''),
        details: {
            todolist: taskTitle // Simpan judul task
        }
    };
    
    // Collect all input values
    inputs.forEach(input => {
        taskData.details[input.id] = input.value;
    });
    
    // Add timestamp
    taskData.timestamp = new Date().toISOString();
    
    // Save to localStorage
    saveTaskToStorage(taskData);
    
    // Notify parent window and close popup
    if (window.opener) {
        window.opener.postMessage({action: 'taskSaved'}, '*');
    }
    window.close();
}


function loadAndDisplayTasks() {
    const tasksContainer = document.querySelector('.floating-bg1 .carbon-content');
    if (!tasksContainer) return;
    
    // Hapus elemen tasks yang ada (jika ada)
    const existingTasks = tasksContainer.querySelectorAll('.task-item');
    existingTasks.forEach(task => task.remove());
    
    // Ambil tasks dari localStorage
    const savedTasks = JSON.parse(localStorage.getItem('savedTasks')) || [];
    
    // Tampilkan 3 task terbaru
    const recentTasks = savedTasks.slice(-3).reverse();
    
    recentTasks.forEach(task => {
        const taskElement = createTaskElement(task);
        tasksContainer.appendChild(taskElement);
    });

    // Tambahkan event listener untuk remove button
    tasksContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-task-btn')) {
            const taskElement = e.target.closest('.task-item');
            const taskId = taskElement.dataset.taskId;
            removeTask(taskId);
        }
    });
}

// Fungsi baru untuk menampilkan tasks di section
function displayTasksInSections() {
    const savedTasks = JSON.parse(localStorage.getItem('savedTasks')) || [];
    
    // Kosongkan semua section terlebih dahulu
    document.querySelectorAll('.task-items').forEach(section => {
        section.innerHTML = '';
    });
    
    // Distribusikan tasks ke section yang sesuai
    savedTasks.forEach(task => {
        // Contoh distribusi acak untuk demo
        // Di sini Anda bisa menambahkan logika distribusi berdasarkan carbon estimate
        const sectionId = getRandomSection();
        const section = document.getElementById(sectionId);
        
        if (section) {
            const taskElement = createTaskListItem(task);
            section.appendChild(taskElement);
        }
    });
}

// Fungsi helper untuk membuat task list item
function createTaskListItem(task) {
    const li = document.createElement('li');
    li.className = 'task-item';
    li.dataset.taskId = task.timestamp;
    
    li.innerHTML = `
        <input type="checkbox" class="task-checkbox">
        <span class="task-name">${getTaskDescription(task)}</span>
        <button class="remove-task-btn">×</button>
    `;
    
    return li;
}

function saveTaskToStorage(taskData) {
    // Get existing tasks or initialize empty array
    const savedTasks = JSON.parse(localStorage.getItem('savedTasks')) || [];
    
    // Add timestamp if not exists
    if (!taskData.timestamp) {
        taskData.timestamp = new Date().toISOString();
    }
    
    // Add new task
    savedTasks.push(taskData);
    
    // Save back to localStorage
    localStorage.setItem('savedTasks', JSON.stringify(savedTasks));
    
    return true;
}


// Fungsi helper untuk mendapatkan section acak (untuk demo)
function getRandomSection() {
    const sections = ['carbon-100-tasks', 'carbon-50-tasks', 'carbon-50-tasks-2'];
    return sections[Math.floor(Math.random() * sections.length)];
}

// At the bottom of popupTask.js
document.addEventListener('DOMContentLoaded', function() {
    setupPopupFunctionality();
    
    // Handle the case where the page is already loaded
    if (document.readyState === 'complete') {
        setupPopupFunctionality();
    }
});